<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacturePaiement extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }
}
