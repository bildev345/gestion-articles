<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Devis;
use App\Models\DevisPaiement;

class DevisPaiementManager extends Component
{
    public Devis $devis;

    public $montant = '';
    public $date_paiement = '';
    public $mode_paiement = '';
    public $reference = '';
    public $note = '';

    public function mount(Devis $devis)
    {
        $this->devis = $devis->load('client', 'paiements');
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
        return (float) $this->devis->paiements()->sum('montant');
    }

    public function getResteProperty()
    {
        return max(0, $this->devis->total_ttc - $this->montantPaye);
    }

    public function getStatutProperty()
    {
        if ($this->montantPaye <= 0) return 'Non payée';
        if ($this->reste > 0) return 'Partiellement payée';
        return 'Payée';
    }

  public function getWhatsappUrlProperty()
{
    if ($this->devis->reste <= 0) {
        return null;
    }

    $phone = $this->devis->client->phone ?? '';

    $message = "Bonjour, rappel de paiement devis ".$this->devis->numero;

    return "https://wa.me/".$phone."?text=".urlencode($message);
}

    public function savePaiement()
    {
        $this->validate();

        if ((float)$this->montant > $this->reste) {
            $this->addError('montant', 'Montant dépasse le reste.');
            return;
        }

        DevisPaiement::create([
            'devis_id' => $this->devis->id,
            'montant' => (float)$this->montant,
            'date_paiement' => $this->date_paiement,
            'mode_paiement' => $this->mode_paiement,
            'reference' => $this->reference,
            'note' => $this->note,
        ]);

        $this->reset(['montant', 'mode_paiement', 'reference', 'note']);
        $this->date_paiement = now()->format('Y-m-d');

        $this->devis->refresh();
        $this->devis->load('client', 'paiements');

        session()->flash('success', 'Paiement ajouté');
    }

    public function deletePaiement($id)
    {
        $paiement = DevisPaiement::where('devis_id', $this->devis->id)
            ->findOrFail($id);

        $paiement->delete();

        $this->devis->refresh();
        $this->devis->load('client', 'paiements');

        session()->flash('success', 'Paiement supprimé');
    }

    public function render()
    {
        return view('livewire.devis-paiement-manager');
    }
}