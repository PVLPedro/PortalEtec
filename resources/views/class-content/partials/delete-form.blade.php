<details class="text-sm">
    <summary class="cursor-pointer text-red-500">{{ $label ?? 'Excluir' }}</summary>

    <form method="POST" action="{{ $action }}" class="mt-small flex flex-wrap items-center gap-small">
        @csrf
        @method('DELETE')

        <input
            type="password"
            name="password"
            placeholder="Confirme sua senha"
            required
            class="rounded-small border border-border bg-bg-primary p-small text-text"
        />

        <button
            type="submit"
            class="rounded-small bg-danger px-regular py-small text-sm font-semibold text-text-white hover:bg-danger-hover"
        >
            Confirmar exclusão
        </button>
    </form>
</details>
