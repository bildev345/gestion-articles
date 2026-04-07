<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FournisseurFacture extends Model
{
    use HasFactory;

    // allow mass assignment except id
    protected $guarded = ['id'];

    /**
     * Fournisseur factures belong to a fournisseur
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * A facture can have many detail lines
     */
    public function details()
    {
        return $this->hasMany(FournisseurFactureDetail::class);
    }
}
