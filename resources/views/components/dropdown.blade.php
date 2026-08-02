<div
    class="relative"
    x-data="{ openDropdown: false }"
    @click.outside="openDropdown = false"
    @close.stop="openDropdown = false"
    @keydown.escape.window="openDropdown = false"
>
    <div
        class="z-50 flex items-center gap-regular rounded-regular p-regular text-lg font-medium hover:bg-bg-primary-hover"
        @click="openDropdown = !openDropdown"
    >
        <x-lucide-user class="size-8"></x-lucide-user>
        {{ $trigger }}
    </div>

    <div
        x-show="openDropdown"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 bottom-0 z-50 flex translate-y-[calc(100%+var(--spacing-small))] flex-col items-start justify-center gap-smaller rounded-md border border-border bg-bg-secondary shadow-md"
        style="display: none"
        @click="openDropdown = false"
    >
        <span class="flex w-full min-w-40 items-center justify-center p-small font-semibold">
            {{ $header }}
        </span>
        {{ $content }}
    </div>
</div>
