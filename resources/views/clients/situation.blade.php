@extends('layout.app')
@section('title', 'Situation des Clients')

@section('content')
<div class="card shadow-sm border-0 mt-4">
      <div>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour aux Clients
        </a>
    </div><br>
    <div class="card-header bg-white">
        <h5>Situation des Clients</h5>
    </div>
  

    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
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
                @foreach($clients as $client)
                    @php
                        $totalFacture = $client->factures->sum('total_ttc');
                        $totalPaye   = $client->factures->flatMap->paiements->sum('montant');
                        $reste       = max(0, $totalFacture - $totalPaye);
                        $statut      = $reste <= 0 ? 'Reglé' : 'No reglé';
                        // WhatsApp URL si reste >0 et client a telephone
                        $whatsappUrl = ($reste > 0 && $client->telephone)
                            ? "https://wa.me/212".ltrim($client->telephone,'0')."?text=".
                              urlencode("Bonjour {$client->nom}, vous avez un reste de {$reste} DH de l'apart de EcoCiel. Merci !")
                            : null;
                    @endphp
                    <tr>
                        <td>{{ $client->nom }}</td>
                        <td>{{ $client->telephone ?? '-' }}</td>
                        <td>{{ number_format($totalFacture,2) }} DH</td>
                        <td>{{ number_format($totalPaye,2) }} DH</td>
                        <td>{{ number_format($reste,2) }} DH</td>
                        <td>
                            @if($reste > 0)
                                <span class="badge bg-danger">{{ $statut }}</span>
                            @else
                                <span class="badge bg-success">{{ $statut }}</span>
                            @endif
                        </td>
                        <td>
                            @if($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-sm">
                                    WhatsApp
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection