<table class="w-full text-left">
    <thead>
        <tr>
            <th class="w-8">
                <input
                    type="checkbox"
                    @change="selected = $event.target.checked
                        ? @json($usuarios->where('role', '!=', \App\Enums\Role::Coordenador)->pluck('id')->values())
                        : []"
                />
            </th>
            <th class="py-3">Nome</th>
            <th class="py-3">Email</th>
            <th class="py-3">Cargo</th>
            <th class="py-3">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usuarios as $usuario)
            <tr>
                <td>
                    @if ($usuario->role !== \App\Enums\Role::Coordenador)
                        <input type="checkbox" value="{{ $usuario->id }}" x-model="selected" />
                    @endif
                </td>
                <td class="py-4">{{ $usuario->name }}</td>
                <td class="py-4">{{ $usuario->email }}</td>
                <td class="py-4">{{ $usuario->role->value }}</td>
                <td>
                    <a
                        class="group relative inline-flex items-center justify-center rounded-small p-small hover:bg-bg-secondary"
                        href="{{ route('users.edit', $usuario) }}"
                    >
                        <x-lucide-square-pen name="square-pen"></x-lucide-square-pen>
                        <span
                            class="pointer-events-none absolute -top-8 left-1/2 z-10 -translate-x-1/2 rounded-small bg-gray px-small py-smaller text-xs whitespace-nowrap text-white opacity-0 transition-opacity group-hover:opacity-100"
                            >Editar</span
                        >
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
