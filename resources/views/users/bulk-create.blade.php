<x-app-layout>
    <div
        class="flex flex-col gap-regular"
        x-data="{
            max: {{ $maxRows }},
            rows: @js(array_values(old('students', [['rm' => '', 'name' => '']]))),
            skipped: 0,
            importError: '',
            sessionLost: false,
            draftKey: 'bulk-students-draft',
            init() {
                const hasOld = {{ old('students') ? 'true' : 'false' }};
                const hasResults = {{ $results ? 'true' : 'false' }};

                if (hasResults) {
                    try { sessionStorage.removeItem(this.draftKey); } catch (err) {}
                } else if (!hasOld) {
                    try {
                        const draft = JSON.parse(sessionStorage.getItem(this.draftKey) || 'null');
                        if (Array.isArray(draft) && draft.length) this.rows = draft.slice(0, this.max);
                    } catch (err) {}
                }

                this.$watch('rows', (value) => {
                    try { sessionStorage.setItem(this.draftKey, JSON.stringify(value)); } catch (err) {}
                });

                setInterval(() => this.keepAlive(), 300000);
                document.addEventListener('visibilitychange', () => {
                    if (!document.hidden) this.keepAlive();
                });
            },
            async keepAlive() {
                try {
                    const res = await fetch('{{ route('session.keep-alive') }}', {
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });
                    if (!res.ok) {
                        this.sessionLost = true;
                        return;
                    }
                    const data = await res.json();
                    document.querySelectorAll('input[name=_token]').forEach((i) => (i.value = data.token));
                    this.sessionLost = false;
                } catch (err) {}
            },
            add() {
                if (this.rows.length < this.max) this.rows.push({ rm: '', name: '' });
            },
            remove(i) {
                this.rows.splice(i, 1);
                if (this.rows.length === 0) this.add();
            },
            paste(e, i) {
                const text = e.clipboardData.getData('text');
                if (!text.includes('\n') && !text.includes('\t')) return;
                e.preventDefault();
                const lines = text.split(/\r?\n/).filter((l) => l.trim() !== '');
                lines.forEach((line, k) => {
                    const target = i + k;
                    if (target >= this.max) return;
                    if (!this.rows[target]) this.rows.push({ rm: '', name: '' });
                    const parts = line.split('\t');
                    this.rows[target].rm = (parts.shift() ?? '').trim();
                    this.rows[target].name = parts.join(' ').trim();
                });
            },
            async importFile(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.importError = '';
                this.skipped = 0;
                try {
                    const buffer = await file.arrayBuffer();
                    const workbook = XLSX.read(buffer, { type: 'array' });
                    const sheet = workbook.Sheets[workbook.SheetNames[0]];
                    const data = XLSX.utils.sheet_to_json(sheet, { header: 1, raw: false, defval: '' });
                    const parsed = data
                        .map((r) => ({ rm: String(r[0] ?? '').trim(), name: String(r[1] ?? '').trim() }))
                        .filter((r) => r.rm || r.name);
                    if (parsed.length && !/^\d{7}$/.test(parsed[0].rm)) parsed.shift();
                    this.skipped = Math.max(0, parsed.length - this.max);
                    this.rows = parsed.slice(0, this.max);
                    if (this.rows.length === 0) {
                        this.add();
                        this.importError = 'Nenhuma linha encontrada na planilha.';
                    }
                } catch (err) {
                    this.importError = 'Não foi possível ler o arquivo.';
                }
                e.target.value = '';
            },
        }"
    >
        <h1 class="text-lg font-semibold">Cadastrar Alunos em Lote</h1>

        <p
            x-show="sessionLost"
            x-cloak
            class="rounded-small border border-red-500 p-small text-sm text-red-500"
        >Sua sessão expirou. Abra o sistema em outra aba, entre novamente e volte aqui para criar os alunos, sem recarregar esta página.</p>

        <div class="flex flex-col gap-smaller">
            <label for="turma" class="text-sm font-semibold">Turma</label>
            <select
                id="turma"
                data-base="{{ route('students.bulk.create') }}"
                class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                onchange="
                    window.location = this.dataset.base + (this.value ? '?turma=' + this.value : '')
                "
            >
                <option value="">Sem turma por enquanto</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}" @selected ($selected?->id === $class->id)>
                        {{ $class->nome }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs">Os alunos criados abaixo entram na turma escolhida. Para rever as senhas depois, escolha a mesma turma novamente.</p>
            <x-input-error :messages="$errors->get('school_class_id')" />
        </div>

        <x-input-error :messages="$errors->get('students')" />

        <div class="flex flex-col gap-smaller">
            <label
                for="planilha"
                class="w-fit cursor-pointer rounded-small border border-border bg-bg-primary px-regular py-small text-sm font-semibold text-text hover:bg-bg-primary-hover"
            >
                Importar planilha (.xlsx ou .csv)
            </label>
            <input
                id="planilha"
                type="file"
                accept=".xlsx,.xls,.csv"
                class="hidden"
                @change="importFile($event)"
            />
            <p class="text-xs">Coluna A: RM. Coluna B: Nome. A primeira linha pode ser o cabeçalho. Você confere os dados abaixo antes de criar.</p>
            <p x-show="skipped > 0" x-cloak class="text-xs text-red-500">
                <span x-text="skipped"></span> linha(s) ficaram de fora: o limite é de {{ $maxRows }} por
                vez.
            </p>
            <p x-show="importError" x-cloak class="text-xs text-red-500" x-text="importError"></p>
        </div>

        <form
            method="POST"
            action="{{ route('students.bulk.store') }}"
            class="flex flex-col gap-regular"
        >
            @csrf

            <input type="hidden" name="school_class_id" value="{{ $selected?->id }}" />

            <div class="flex flex-col gap-small">
                <div
                    class="grid grid-cols-[1fr_2fr_auto] gap-small border-b border-border pb-small text-sm font-semibold"
                >
                    <span>RM</span>
                    <span>Nome</span>
                    <span class="w-16"></span>
                </div>

                <template x-for="(row, i) in rows" :key="i">
                    <div class="grid grid-cols-[1fr_2fr_auto] items-center gap-small">
                        <input
                            type="text"
                            inputmode="numeric"
                            maxlength="7"
                            placeholder="RM"
                            x-model="row.rm"
                            :name="`students[${i}][rm]`"
                            @paste="paste($event, i)"
                            class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                        />
                        <input
                            type="text"
                            placeholder="Nome completo"
                            x-model="row.name"
                            :name="`students[${i}][name]`"
                            class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                        />
                        <button
                            type="button"
                            @click="remove(i)"
                            class="w-16 text-sm text-red-500 hover:underline"
                        >
                            Remover
                        </button>
                    </div>
                </template>
            </div>

            <div class="flex items-center justify-between gap-regular">
                <div class="flex items-center gap-regular">
                    <button
                        type="button"
                        @click="add()"
                        :disabled="rows.length >= max"
                        class="rounded-small border border-border bg-bg-primary px-regular py-small text-sm font-semibold text-text hover:bg-bg-primary-hover disabled:opacity-50"
                    >
                        + Adicionar linha
                    </button>
                    <span class="text-xs" x-text="`${rows.length} de ${max} linhas`"></span>
                </div>

                <button
                    type="submit"
                    class="rounded-small bg-accent px-regular py-small text-sm font-semibold text-white hover:bg-accent-hover"
                >
                    CRIAR ALUNOS
                </button>
            </div>
        </form>

        @if ($results)
            @php
                $created = collect($results)->where('status', 'Cadastrado')->count();
                $failed = count($results) - $created;
            @endphp

            <div class="flex flex-col gap-small rounded-small border border-border p-regular">
                <div class="flex items-center justify-between gap-regular">
                    <p class="font-semibold text-accent">
                        {{ $created }} aluno(s) criado(s) com sucesso
                        @if ($failed > 0)
                            <span class="text-red-500">e {{ $failed }} com erro</span>
                        @endif
                    </p>
                    <a
                        href="{{ route('students.bulk.csv') }}"
                        class="rounded-small bg-accent px-regular py-small text-sm font-semibold text-white hover:bg-accent-hover"
                    >
                        Baixar CSV
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="py-small pr-regular">RM</th>
                                <th class="py-small pr-regular">Nome</th>
                                <th class="py-small pr-regular">Senha inicial</th>
                                <th class="py-small">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $r)
                                <tr class="border-b border-border last:border-0">
                                    <td class="py-small pr-regular">{{ $r['rm'] }}</td>
                                    <td class="py-small pr-regular">{{ $r['name'] }}</td>
                                    <td class="py-small pr-regular font-mono">
                                        {{ $r['password'] ?? '—' }}
                                    </td>
                                    <td
                                        class="py-small {{ $r['status'] === 'Cadastrado' ? 'text-accent' : 'text-red-500' }}"
                                    >
                                        {{ $r['status'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="text-xs">Anote ou baixe as senhas agora: este quadro some ao sair da tela. Depois, escolha a turma para rever a lista.</p>
            </div>
        @elseif ($selected)
            <div class="flex flex-col gap-small rounded-small border border-border p-regular">
                <div class="flex items-center justify-between gap-regular">
                    <p class="font-semibold">
                        {{ $selected->nome }}: {{ $classStudents->count() }} aluno(s)
                    </p>
                    @if ($classStudents->isNotEmpty())
                        <a
                            href="{{ route('students.bulk.csv', ['turma' => $selected->id]) }}"
                            class="rounded-small bg-accent px-regular py-small text-sm font-semibold text-white hover:bg-accent-hover"
                        >
                            Baixar CSV da turma
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="py-small pr-regular">RM</th>
                                <th class="py-small pr-regular">Nome</th>
                                <th class="py-small">Senha inicial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($classStudents as $student)
                                <tr class="border-b border-border last:border-0">
                                    <td class="py-small pr-regular">{{ $student->rm }}</td>
                                    <td class="py-small pr-regular">{{ $student->name }}</td>
                                    <td class="py-small font-mono">
                                        {{ $student->initial_password ?? '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-small">
                                        Nenhum aluno nesta turma ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    @push ('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    @endpush
</x-app-layout>
