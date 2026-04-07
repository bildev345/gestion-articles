<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relation: un Article peut être dans plusieurs FactureDetails
    public function factureDetails()
        {
            return $this->hasMany(FactureDetail::class);
        }

    public function fournisseur()
        {
            return $this->belongsTo(Fournisseur::class);
        }
    // Article utilisé dans détails facture
    public function details()
        {
            return $this->hasMany(FactureDetail::class);
        }

   
}
