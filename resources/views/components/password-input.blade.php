{{-- resources/views/components/password-input.blade.php --}}
<div x-data="{ visible: false }" class="flex items-center gap-regular">
    <x-text-input
        type="password"
        x-bind:type="visible ? 'text' : 'password'"
        {{
            $attributes->merge([
                'id' => 'password',
                'name' => 'password',
                'autocomplete' => 'current-password',
            ])
        }}
    >
        <x-slot name="icon">
            <x-lucide-key class="shrink-0" />
        </x-slot>
    </x-text-input>

    <button
        type="button"
        x-on:click="visible = !visible"
        x-bind:aria-pressed="visible"
        x-bind:aria-label="visible ? 'Ocultar senha' : 'Mostrar senha'"
        aria-label="Mostrar senha"
        class="shrink-0 cursor-pointer rounded-small border-2 border-border bg-bg-primary p-small text-text shadow-md hover:bg-bg-primary-hover"
    >
        <x-lucide-eye-off x-show="!visible" />
        <x-lucide-eye x-show="visible" x-cloak />
    </button>
</div>
