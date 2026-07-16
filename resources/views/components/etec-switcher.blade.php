@php
    $user = auth()->user();
@endphp

@if ($user->etecs->count() > 1)
    <span class="text-sm text-secondary">Trocar de Etec</span>
    <select id="etec-switcher" class="rounded-md border-gray-300 text-sm focus:ring-[#776eb4]">
        @foreach ($user->etecs as $etec)
            <option value="{{ $etec->id }}" @selected ($etec->id === $user->activeEtec()->id)>
                {{ $etec->nome }}
            </option>
        @endforeach
    </select>
@endif

<script>
    document.getElementById('etec-switcher')?.addEventListener('change', async function (event) {
        const etecId = event.target.value;

        try {
            const response = await fetch('{{ route('etec-context.switch') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ etec_id: etecId }),
            });

            if (!response.ok) {
                throw new Error('Falha ao trocar de Etec');
            }

            // Recarrega a página pra refletir os dados da nova Etec ativa
            window.location.reload();
        } catch (error) {
            console.error(error);
            alert('Não foi possível trocar de Etec. Tente novamente.');
        }
    });
</script>
