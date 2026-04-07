@extends('layout.app')

@section('title', 'Situation des Clients')

@section('content')

<div class="card shadow-sm border-0 mt-4">

    {{-- BTN RETOUR --}}
    <div class="p-3">
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour aux Clients
        </a>
    </div>

    {{-- HEADER --}}
    <div class="card-header bg-white">
        <h5 class="mb-0">Situation des Clients (Devis)</h5>
    </div>

    {{-- BODY --}}
    <div class="card-body table-responsive">

        <table class="table table-striped align-middle">

            <thead class="table-light">
                <tr>
                    <th>Nom Client</th>
                    <th>Téléphone</th>
                    <th>Total TTC</th>
                    <th>Payé</th>
                    <th>Reste</th>
                    <th>Situation</th>
                    <th>WhatsApp</th>
                </tr>
            </thead>

            <tbody>

                @forelse($clients as $client)

                    @php
                        $totaldevis = $client->devis->sum('total_ttc');
                        $totalPaye  = $client->devis->flatMap->paiements->sum('montant');
                        $reste      = max(0, $totaldevis - $totalPaye);
                        $statut     = $reste <= 0 ? 'Réglé' : 'Non réglé';

                        $whatsappUrl = ($reste > 0 && $client->telephone)
                            ? "https://wa.me/212".ltrim($client->telephone,'0')."?text=".
                              urlencode("Bonjour {$client->nom}, vous avez un reste de ".number_format($reste,2)." DH de la part de EcoCiel. Merci !")
                            : null;
                    @endphp

                    <tr>
                        <td>{{ $client->nom }}</td>

                        <td>
                            {{ $client->telephone ?? '-' }}
                        </td>

                        <td>
                            {{ number_format($totaldevis,2) }} DH
                        </td>

                        <td>
                            {{ number_format($totalPaye,2) }} DH
                        </td>

                        <td>
                            <strong>{{ number_format($reste,2) }} DH</strong>
                        </td>

                        <td>
                            <span class="badge {{ $reste > 0 ? 'bg-danger' : 'bg-success' }}">
                                {{ $statut }}
                            </span>
                        </td>

                        <td>
                            @if($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Aucun client trouvé
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection