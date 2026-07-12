<?php
 
namespace App\Http\Controllers;
 
use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
 
class UserManagementController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'usuarios' => User::all(),
        ]);
    }
 
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
 
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'cpf' => ['required', 'digits:11', 'unique:users,cpf,'.$user->id],
            'phone' => ['required', 'regex:/^\d{2}9\d{8}$/'],
            'role' => ['required', 'in:aluno,professor,coordenador'],
        ]);
 
        $user->update($validated);
 
        return redirect()->route('users.index')->with('status', 'Usuário atualizado!');
    }
 
    public function destroy(Request $request, User $user)
    {
        $this->authorizeDeletion($request, [$user->id]);
 
        $user->delete();
 
        return redirect()->route('users.index')->with('status', 'Usuário removido!');
    }
 
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:users,id'],
        ]);
 
        $ids = $request->input('ids');
 
        $this->authorizeDeletion($request, $ids);
 
        User::whereIn('id', $ids)
            ->where('role', '!=', Role::Coordenador->value)
            ->delete();
 
        return redirect()->route('users.index')->with('status', 'Usuários removidos!');
    }
 
    /**
     * Confirms the logged-in coordinator's password and ensures no
     * coordinator is among the users being deleted.
     */
    private function authorizeDeletion(Request $request, array $ids): void
    {
        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Confirme sua senha para continuar.',
        ]);
 
        if (! Hash::check($request->input('password'), Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => 'Senha incorreta.',
            ]);
        }
 
        $existeCoordenador = User::whereIn('id', $ids)
            ->where('role', Role::Coordenador->value)
            ->exists();
 
        if ($existeCoordenador) {
            abort(403, 'Não é possível excluir um coordenador por aqui.');
        }
    }
}