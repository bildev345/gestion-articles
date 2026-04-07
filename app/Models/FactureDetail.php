<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactureDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
   // Relation: FactureDetail appartient à Facture
    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    // Relation: FactureDetail appartient à Article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    //
}
