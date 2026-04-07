<div class="card shadow-sm border-0 mt-4">

    <div class="card-header bg-white">
        <h5>Paiements Devis</h5>
    </div>

    <div class="card-body">

        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-3">

            <div class="col-md">
                <strong>Total TTC :</strong> {{ number_format($devis->total_ttc,2) }} DH
            </div>

            <div class="col-md">
                <strong>Payé :</strong> {{ number_format($devis->montantPaye,2) }} DH
            </div>

            <div class="col-md">
                <strong>Reste :</strong> {{ $devis->total_ttc - $devis->paiements->sum('montant') }}
            </div>

            <div class="col-md"> 
                <strong>Statut :</strong> {{ $devis->statut }}
            </div>

        </div>

              @if($this->whatsappUrl)
    <a href="{{ $this->whatsappUrl }}" target="_blank" class="btn btn-success btn-sm">
        Envoyer rappel WhatsApp
    </a>
@endif

        <hr>

        <h6>Ajouter Paiement</h6>

        <div class="row g-2 mb-3">

            <div class="col-md">
                <input type="number" step="0.01" class="form-control"
                       placeholder="Montant"
                       wire:model="montant">
                @error('montant') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md">
                <input type="date" class="form-control"
                       wire:model="date_paiement">
            </div>

            <div class="col-md">
                <input type="text" class="form-control"
                       placeholder="Mode paiement"
                       wire:model="mode_paiement">
            </div>

            <div class="col-md">
                <button wire:click="savePaiement" class="btn btn-primary w-100">
                    Ajouter
                </button>
            </div>

        </div>

        <hr>

        <h6>Historique Paiements</h6>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Mode</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($devis->paiements()->latest()->get() as $paiement)
                    <tr>
                        <td>{{ $paiement->date_paiement }}</td>
                        <td>{{ number_format($paiement->montant,2) }} DH</td>
                        <td>{{ $paiement->mode_paiement }}</td>
                        <td>
                            <button
                                onclick="return confirm('Supprimer ce paiement ?')"
                                wire:click="deletePaiement({{ $paiement->id }})"
                                class="btn btn-danger btn-sm">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>