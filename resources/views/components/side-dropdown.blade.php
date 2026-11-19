<div
    class="relative flex cursor-pointer items-center justify-center rounded-regular hover:bg-bg-secondary-hover"
>
    <div
        class="relative h-full"
        x-data="{ openDropdown: false }"
        @click.outside="openDropdown = false"
        @close.stop="openDropdown = false"
        @keydown.escape.window="openDropdown = false"
    >
        <div
            class="z-50 flex h-full flex-col items-center justify-center gap-regular rounded-regular p-regular text-base font-medium text-border group-hover:text-text hover:bg-bg-secondary-hover"
            @click="openDropdown = !openDropdown"
        >
            {{ $matricula->side->side_name }}
        </div>

        <div
            x-show="openDropdown"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 bottom-0 z-50 flex translate-y-[calc(100%+var(--spacing-small))] translate-z-2 flex-col items-start justify-center gap-smaller rounded-md border border-border bg-bg-secondary shadow-md"
            style="display: none"
            @click="openDropdown = false"
        >
            @foreach ($sides as $side)
                <form
                    method="POST"
                    action="{{ route('school-classes.update-side', [$schoolClass, $matricula->user]) }}"
                    class="w-full"
                    x-data
                    @change="$el.submit()"
                >
                    @csrf
                    @method ('PATCH')
                    <input class="hidden" name="id_side" value="{{ $side->id_side }}" />

                    <button
                        type="submit"
                        class="flex cursor-pointer w-full min-w-40 items-center justify-between gap-small p-regular font-medium text-nowrap hover:bg-bg-secondary-hover {{ $side->id_side === $matricula->id_side ? 'bg-bg-secondary-hover' : '' }}"
                    >
                        {{ $side->side_name }}
                        <x-lucide-check
                            class="{{ $side->id_side === $matricula->id_side ? 'block' : 'hidden' }}"
                        />
                    </button>
                </form>
            @endforeach
        </div>
    </div>
</div>
