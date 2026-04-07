<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fournisseur extends Model
{
    
    use HasFactory;

    protected $guarded = ['id'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    public function factures()
    {
        return $this->hasMany(FournisseurFacture::class);
    }
}
