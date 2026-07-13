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
            <th>Nome</th>
            <th>Email</th>
            <th>Cargo</th>
            <th>Ações</th>
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
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->role->value }}</td>
                <td>
                    <a href="{{ route('users.edit', $usuario) }}">Editar</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
