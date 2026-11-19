<div class="relative flex items-center justify-between gap-small *:flex *:items-center *:gap-small">
    <div class="relative">
        <input
            id="remember_me"
            type="checkbox"
            class="peer size-6 appearance-none rounded-small border border-border shadow-md checked:bg-accent hover:bg-accent-bg checked:hover:bg-accent-hover"
            name="remember"
        />
        <x-lucide-check
            class="pointer-events-none absolute top-1/2 left-1/2 hidden size-4 -translate-1/2 stroke-3 text-white peer-checked:block"
        />
    </div>
    <label for="remember_me" class="flex-1 text-sm font-medium text-text">Lembrar de mim</label>
</div>
