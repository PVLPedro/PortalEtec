<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Etec;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentBulkController extends Controller
{
    private const MAX_ROWS = 50;

    private array $classInfo = ['curso' => '', 'serie' => '', 'turno' => ''];

    public function create(Request $request)
    {
        $etec = $request->user()->activeEtec();

        $classes = SchoolClass::where('etec_id', $etec?->id)
            ->with(['course', 'grade', 'shift'])
            ->get()
            ->sortBy('name')
            ->values();

        $selected = $classes->firstWhere('id', (int) $request->query('turma'));

        $results = session()->pull('bulk_result');

        if ($results) {
            session()->put('bulk_csv', $results);
        }

        return view('users.bulk-create', [
            'maxRows' => self::MAX_ROWS,
            'results' => $results,
            'classes' => $classes,
            'selected' => $selected,
            'classStudents' => $selected ? $this->studentsOfClass($etec, $selected) : collect(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'students' => ['required', 'array', 'max:' . self::MAX_ROWS],
            'students.*.rm' => ['nullable', 'string', 'max:20'],
            'students.*.name' => ['nullable', 'string', 'max:255'],
            'school_class_id' => ['nullable', 'integer'],
        ]);

        $etec = $request->user()->activeEtec();

        if (!$etec) {
            return back()->withErrors(['students' => 'Você não está vinculado a nenhuma Etec.']);
        }

        $schoolClass = null;

        if ($request->filled('school_class_id')) {
            $schoolClass = SchoolClass::where('etec_id', $etec->id)
                ->with(['course', 'grade', 'shift'])
                ->find($request->input('school_class_id'));

            if (!$schoolClass) {
                return back()
                    ->withInput()
                    ->withErrors(['school_class_id' => 'Turma inválida.']);
            }

            $this->classInfo = $this->infoOf($schoolClass);
        }

        $results = [];

        foreach ($request->input('students') as $row) {
            $rm = trim((string) ($row['rm'] ?? ''));
            $name = trim((string) ($row['name'] ?? ''));

            if ($rm === '' && $name === '') {
                continue;
            }

            if ($rm === '' || $name === '' || !preg_match('/^\d{7}$/', $rm)) {
                $results[] = $this->row(
                    $rm,
                    $name,
                    null,
                    'Erro: informe o RM com 7 dígitos e o nome.',
                );
                continue;
            }

            $results[] = $this->createStudent($etec->id, (int) $rm, $name, $schoolClass?->id);
        }

        if (empty($results)) {
            return back()
                ->withInput()
                ->withErrors(['students' => 'Preencha pelo menos uma linha.']);
        }

        session()->put('bulk_result', $results);

        return redirect()->route('students.bulk.create', ['turma' => $schoolClass?->id]);
    }

    public function csv(Request $request)
    {
        $etec = $request->user()->activeEtec();
        $rows = [];

        if ($request->filled('turma') && $etec) {
            $schoolClass = SchoolClass::where('etec_id', $etec->id)
                ->with(['course', 'grade', 'shift'])
                ->findOrFail($request->input('turma'));

            $info = $this->infoOf($schoolClass);

            foreach ($this->studentsOfClass($etec, $schoolClass) as $student) {
                $rows[] = [
                    $student->rm,
                    $student->name,
                    $student->initial_password,
                    $info['curso'],
                    $info['serie'],
                    $info['turno'],
                    'Cadastrado',
                ];
            }
        } else {
            $results = session('bulk_csv');

            if (!$results) {
                return redirect()->route('students.bulk.create');
            }

            foreach ($results as $r) {
                $rows[] = [
                    $r['rm'],
                    $r['name'],
                    $r['password'],
                    $r['curso'] ?? '',
                    $r['serie'] ?? '',
                    $r['turno'] ?? '',
                    $r['status'],
                ];
            }
        }

        return response()->streamDownload(
            function () use ($rows) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF");
                fputcsv(
                    $out,
                    ['RM', 'Nome', 'Senha inicial', 'Curso', 'Série', 'Turno', 'Status'],
                    ';',
                );

                foreach ($rows as $row) {
                    fputcsv($out, $row, ';');
                }

                fclose($out);
            },
            'alunos-cadastrados.csv',
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }

    private function infoOf(SchoolClass $schoolClass): array
    {
        return [
            'curso' => (string) $schoolClass->course?->name,
            'serie' => (string) $schoolClass->grade?->name,
            'turno' => (string) $schoolClass->shift?->name,
        ];
    }

    private function studentsOfClass(?Etec $etec, SchoolClass $schoolClass)
    {
        return User::query()
            ->join('user_students', 'user_students.user_id', '=', 'users.id')
            ->join('etec_worker', 'etec_worker.user_id', '=', 'users.id')
            ->where('user_students.id_class', $schoolClass->id)
            ->where('etec_worker.id_etec', $etec?->id)
            ->select('users.*', 'etec_worker.rm as rm')
            ->orderBy('users.name')
            ->get();
    }

    private function createStudent(int $etecId, int $rm, string $name, ?int $schoolClassId): array
    {
        try {
            $password = $this->generatePassword();

            DB::transaction(function () use ($etecId, $rm, $name, $password, $schoolClassId) {
                $exists = DB::table('etec_worker')
                    ->where('id_etec', $etecId)
                    ->where('rm', $rm)
                    ->exists();

                if ($exists) {
                    throw new \DomainException('RM já cadastrado nesta Etec.');
                }

                $user = User::create([
                    'name' => $name,
                    'email' => null,
                    'role' => Role::Aluno,
                    'password' => $password,
                    'initial_password' => $password,
                ]);

                $user->etecs()->attach($etecId, [
                    'rm' => $rm,
                    'role' => Role::Aluno->value,
                ]);

                $user->student()->create([
                    'rm' => $rm,
                    'id_class' => $schoolClassId,
                ]);
            });

            return $this->row((string) $rm, $name, $password, 'Cadastrado');
        } catch (\DomainException $e) {
            return $this->row((string) $rm, $name, null, 'Erro: ' . $e->getMessage());
        } catch (UniqueConstraintViolationException) {
            return $this->row((string) $rm, $name, null, 'Erro: RM já cadastrado nesta Etec.');
        }
    }

    private function row(string $rm, string $name, ?string $password, string $status): array
    {
        return [
            'rm' => $rm,
            'name' => $name,
            'password' => $password,
            'status' => $status,
            'curso' => $this->classInfo['curso'],
            'serie' => $this->classInfo['serie'],
            'turno' => $this->classInfo['turno'],
        ];
    }

    private function generatePassword(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        $max = strlen($alphabet) - 1;
        $password = '';

        for ($i = 0; $i < 10; $i++) {
            $password .= $alphabet[random_int(0, $max)];
        }

        return $password;
    }
}
