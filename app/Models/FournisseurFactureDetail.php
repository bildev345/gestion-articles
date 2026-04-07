<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FournisseurFactureDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Each detail belongs to a fournisseur facture
     */
    public function fournisseurFacture()
    {
        return $this->belongsTo(FournisseurFacture::class);
    }

    /**
     * Detail line is linked to an article
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}