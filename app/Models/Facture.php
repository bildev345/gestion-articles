<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Client;
use App\Models\FactureDetail;

class Facture extends Model
{
    use HasFactory;
  
    protected $guarded = ['id'];

 
    public function client()
        {
            return $this->belongsTo(Client::class);
        }
    public function details()
        {
            return $this->hasMany(FactureDetail::class);
        }
        
    public function paiements()
    {
        return $this->hasMany(FacturePaiement::class);
    }
    public function getResteAttribute()
{
    return $this->total_ttc - $this->paiements->sum('montant');
}
    public function getStatutAttribute()
{
    if ($this->reste <= 0) {
        return 'payée';
    }

    return 'non_payée';
}

    public function getMontantPayeAttribute()
    {
        return $this->paiements->sum('montant');
    }
        
        
}
    