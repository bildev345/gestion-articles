<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Facture;
use App\Models\FacturePaiement;

class FacturePaiementManager extends Component
{
    public Facture $facture;

    public $montant = '';
    public $date_paiement = '';
    public $mode_paiement = '';
    public $reference = '';
    public $note = '';

    public function mount(Facture $facture)
    {
        $this->facture = $facture->load('client', 'paiements');
        $this->date_paiement = now()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'montant' => 'required|numeric|min:0.01',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000',
        ];
    }

    public function getMontantPayeProperty()
    {
        return (float) $this->facture->paiements()->sum('montant');
    }

    public function getResteProperty()
    {
        return max(0, $this->facture->total_ttc - $this->montantPaye);
    }

    public function getStatutProperty()
    {
        if ($this->montantPaye <= 0) return 'Non payée';
        if ($this->reste > 0) return 'Partiellement payée';
        return 'Payée';
    }

  public function getWhatsappUrlProperty()
{
    if ($this->facture->reste <= 0) {
        return null;
    }

    $phone = $this->facture->client->phone ?? '';

    $message = "Bonjour, rappel de paiement facture ".$this->facture->numero;

    return "https://wa.me/".$phone."?text=".urlencode($message);
}

    public function savePaiement()
    {
        $this->validate();

        if ((float)$this->montant > $this->reste) {
            $this->addError('montant', 'Montant dépasse le reste.');
            return;
        }

        FacturePaiement::create([
            'facture_id' => $this->facture->id,
            'montant' => (float)$this->montant,
            'date_paiement' => $this->date_paiement,
            'mode_paiement' => $this->mode_paiement,
            'reference' => $this->reference,
            'note' => $this->note,
        ]);

        $this->reset(['montant', 'mode_paiement', 'reference', 'note']);
        $this->date_paiement = now()->format('Y-m-d');

        $this->facture->refresh();
        $this->facture->load('client', 'paiements');

        session()->flash('success', 'Paiement ajouté');
    }

    public function deletePaiement($id)
    {
        $paiement = FacturePaiement::where('facture_id', $this->facture->id)
            ->findOrFail($id);

        $paiement->delete();

        $this->facture->refresh();
        $this->facture->load('client', 'paiements');

        session()->flash('success', 'Paiement supprimé');
    }

    public function render()
    {
        return view('livewire.facture-paiement-manager');
    }
}