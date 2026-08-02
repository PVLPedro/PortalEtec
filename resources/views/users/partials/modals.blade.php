<x-backdrop x-show="schoolClassModal" x-cloak @keydown.escape.window="schoolClassModal = false">
    <x-form-modal
        method="POST"
        action="{{ route('users.add-to-classMultiple') }}"
        @click.outside="schoolClassModal = false"
    >
        @csrf

        <x-close-button @click="schoolClassModal = false" />

        <h3 class="py-smaller text-center font-semibold">Adicionar à Turma</h3>

        <template x-for="id in selected" :key="id">
            <input type="hidden" name="usuarios[]" :value="id" />
        </template>

        <x-input-label for="school_class_id"> Turma existente </x-input-label>
        <select
            name="school_class_id"
            id="school_class_id"
            class="flex items-center gap-small rounded-small border border-border p-small text-text"
        >
            <option value="">Selecione uma Turma</option>
            @foreach ($schoolClasses as $schoolClass)
                <option value="{{ $schoolClass->id }}">{{ $schoolClass->name }}</option>
            @endforeach
        </select>

        <a
            class="flex items-center gap-small rounded-small bg-bg-primary p-small text-text hover:bg-bg-primary-hover"
            :href="`{{ route('school-classes.create') }}?` + selected.map(id =>
            `usuarios[]=${id}`).join('&')"
        >
            <x-lucide-plus />
            Criar nova turma
        </a>

        <div class="flex justify-between">
            <x-primary-button
                type="button"
                class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                @click="schoolClassModal = false"
            >
                <x-lucide-x />
                Cancelar
            </x-primary-button>
            <x-primary-button type="submit" class="bg-accent text-text-white hover:bg-accent-hover">
                <x-lucide-check />
                Confirmar
            </x-primary-button>
        </div>
    </x-form-modal>
</x-backdrop>

<x-backdrop x-show="addModal" x-cloak @keydown.escape.window="addModal = false">
    @php
        $addToClassBaseAction = route('users.add-to-class', ['__USER_ID__']);
    @endphp
    <x-form-modal
        method="POST"
        x-bind:action="'{{ $addToClassBaseAction }}'.replace('__USER_ID__', userToAdd)"
        @click.outside="addModal = false"
    >
        @csrf

        <x-close-button @click="addModal = false" />

        <h3 class="py-smaller text-center font-semibold">Adicionar à Turma</h3>

        <p class="text-sm text-secondary">Adicionar <span x-text="userRoleToAdd" class="capitalize"></span> <span x-text="userNameToAdd" class="capitalize"></span> a:</p>

        <x-input-label for="school_class_id_single"> Turma </x-input-label>
        <select
            name="school_class_id"
            id="school_class_id_single"
            class="flex items-center gap-small rounded-small border border-border p-small text-text"
        >
            <option value="">Selecione uma Turma</option>
            @foreach ($schoolClasses as $schoolClass)
                <option value="{{ $schoolClass->id }}">{{ $schoolClass->name }}</option>
            @endforeach
        </select>

        <a
            class="flex items-center gap-small rounded-small bg-bg-primary p-small text-text hover:bg-bg-primary-hover"
            :href="`{{ route('school-classes.create') }}?usuarios[]=${userToAdd}`"
        >
            <x-lucide-plus />
            Criar nova turma
        </a>

        <div class="flex justify-between">
            <x-primary-button
                type="button"
                class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                @click="addModal = false"
            >
                <x-lucide-x />
                Cancelar
            </x-primary-button>
            <x-primary-button type="submit" class="bg-accent text-text-white hover:bg-accent-hover">
                <x-lucide-check />
                Confirmar
            </x-primary-button>
        </div>
    </x-form-modal>
</x-backdrop>
<x-backdrop
    x-show="deleteSelectedModal"
    x-cloak
    class=""
    @keydown.escape.window="deleteSelectedModal = false"
>
    <x-form-modal
        method="POST"
        action="{{ route('users.destroyMultiple') }}"
        @click.outside="deleteSelectedModal = false"
        onsubmit="
            return confirm('Excluir os usuários selecionados? Esta ação não pode ser desfeita.');
        "
    >
        @csrf
        @method ('DELETE')

        <x-close-button @click="deleteSelectedModal = false" />

        <h3 class="py-smaller text-center font-semibold">Exclusão de Usuários</h3>

        <template x-for="id in selected" :key="id">
            <input type="hidden" name="ids[]" :value="id" />
        </template>

        <label for="bulk-password" class="text-sm font-medium text-secondary">
            Confirme sua senha para excluir
            <span x-text="selected.length"></span> usuário(s) selecionado(s):
        </label>
        <input
            id="bulk-password"
            type="password"
            name="password"
            class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
            placeholder="Sua senha"
        />

        <div class="flex justify-between">
            <button
                type="button"
                @click="deleteSelectedModal = false"
                class="flex items-center gap-smaller rounded-small bg-bg-primary p-small text-text hover:bg-bg-primary-hover"
            >
                <x-lucide-x />
                Cancelar
            </button>
            <button
                type="submit"
                class="flex items-center gap-small rounded-small bg-danger p-small text-text-white hover:bg-danger-hover"
            >
                <x-lucide-trash-2 />
                Excluir selecionados
            </button>
        </div>
    </x-form-modal>
</x-backdrop>
<x-backdrop x-show="deleteModal" x-cloak class="" @keydown.escape.window="deleteModal = false">
    @php
        $deleteUserBaseAction = route('users.destroy', ['__USER_ID__']);
    @endphp
    <x-form-modal
        method="POST"
        x-bind:action="'{{ $deleteUserBaseAction }}'.replace('__USER_ID__', userToDelete)"
        @click.outside="deleteModal = false"
    >
        @csrf
        @method ('DELETE')

        <x-close-button @click="deleteModal = false" />

        <h3 class="py-smaller text-center font-semibold">Exclusão</h3>

        <label for="bulk_password_2" class="text-sm font-medium text-secondary">
            Confirme sua senha para excluir o usuário
            <span x-text="userRoleToDelete" class="capitalize"></span>
            <span x-text="userNameToDelete" class="capitalize"></span>
        </label>

        <input
            id="bulk_password_2"
            type="password"
            name="password"
            class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
            placeholder="Sua senha"
        />

        <div class="flex justify-between">
            <x-primary-button
                type="button"
                class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                @click="deleteModal = false"
            >
                <x-lucide-x />
                Cancelar
            </x-primary-button>
            <x-primary-button type="submit" class="bg-danger text-text-white hover:bg-danger-hover">
                <x-lucide-trash-2 />
                Excluir
            </x-primary-button>
        </div>
    </x-form-modal>
</x-backdrop>
