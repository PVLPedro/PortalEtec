<div class="flex flex-col gap-small">
    <a
        href="{{ route('school-class.show', [$schoolClass, 'secao' => $section]) }}"
        class="w-fit text-sm underline"
    >
        &larr; Voltar para {{ $schoolClass->name }}
    </a>

    @if (session('status'))
        <div class="rounded bg-valid p-3">{{ session('status') }}</div>
    @endif
</div>
