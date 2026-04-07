<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Devis;
use App\Models\Client;
use App\Models\DevisDetail;

class DevisPaiement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }
}
