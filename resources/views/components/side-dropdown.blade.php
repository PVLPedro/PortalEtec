<div
    class="relative flex cursor-pointer items-center justify-center rounded-regular hover:bg-bg-secondary-hover"
    x-data="{
        openDropdown: false,
        currentSideId: {{ $matricula->id_side ?? 'null' }},
        currentSideName: '{{ $matricula->side?->side_name ?? 'Não definido' }}',
        updating: false,
        async updateSide(sideId, sideName) {
            this.updating = true;

            try {
                const response = await fetch(
                    '{{ route('school-classes.update-side', [$schoolClass, $matricula->user]) }}',
                    {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name=csrf-token]',
                            ).content,
                        },
                        body: JSON.stringify({ id_side: sideId }),
                    },
                );

                if (!response.ok) {
                    throw new Error('Falha ao atualizar o lado');
                }

                this.currentSideId = sideId;
                this.currentSideName = sideName;
            } catch (error) {
                alert('Não foi possível atualizar o lado. Tente novamente.');
            } finally {
                this.updating = false;
                this.openDropdown = false;
            }
        },
    }"
    @click.outside="openDropdown = false"
    @close.stop="openDropdown = false"
    @keydown.escape.window="openDropdown = false"
>
    <div class="relative h-full">
        <div
            class="z-50 flex h-full flex-col items-center justify-center gap-regular rounded-regular p-regular text-base font-medium text-border group-hover:text-text hover:bg-bg-secondary-hover"
            :class="updating && 'opacity-50 pointer-events-none'"
            @click="openDropdown = !openDropdown"
            x-text="currentSideName"
        ></div>
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
        >
            @foreach ($sides as $side)
                <button
                    type="button"
                    @click="updateSide({{ $side->id_side }}, '{{ $side->side_name }}')"
                    class="flex w-full min-w-40 cursor-pointer items-center justify-between gap-small p-regular font-medium text-nowrap hover:bg-bg-secondary-hover"
                    :class="currentSideId === {{ $side->id_side }} && 'bg-bg-secondary-hover'"
                >
                    {{ $side->side_name }}
                    <x-lucide-check x-show="currentSideId === {{ $side->id_side }}" x-cloak />
                </button>
            @endforeach
        </div>
    </div>
</div>
