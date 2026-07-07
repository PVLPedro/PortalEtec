<div
    class="relative"
    x-data="{ open: false }"
    @click.outside="open = false"
    @close.stop="open = false"
>
    <div
        class="flex items-center gap-regular rounded-regular p-regular text-lg font-medium hover:bg-bg-primary-hover"
        @click="open = !open"
    >
        <x-lucide-user class="size-8"></x-lucide-user>
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 bottom-0 z-50 flex translate-y-[calc(100%+var(--spacing-small))] flex-col items-start justify-center gap-smaller rounded-md border-2 border-border bg-bg-secondary shadow-lg"
        style="display: none"
        @click="open = false"
    >
        <span class="flex w-full min-w-40 items-center justify-center p-small font-semibold">
            {{ $header }}
        </span>
        {{ $content }}
    </div>
</div>
