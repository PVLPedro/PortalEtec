@php
    $user = auth()->user();
@endphp

@if ($user->etecs->count() > 1 &&
    in_array(request()->route()->getName(), [
        'dashboard',
        'school-classes.index',
        'users.index'
    ]))
    <div class="w-80 *:text-nowrap">
        <select id="etec_switcher">
            @foreach ($user->etecs as $etec)
                <option value="{{ $etec->id }}" @selected ($etec->id === $user->activeEtec()->id)>
                    {{ $etec->name }} ({{ $etec->code }})
                </option>
            @endforeach
        </select>
    </div>
@endif

@push ('scripts')
    <script>
        document.getElementById('etec_switcher')?.addEventListener('change', async function (event) {
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

        $(function () {
            const plainSelectIds = ['etec_switcher'];

            plainSelectIds.forEach((id) => {
                new Choices($('#' + id).get(0), {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    placeholder: true,
                });
            });
        });
    </script>
@endpush
