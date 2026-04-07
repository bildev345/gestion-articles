<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevisDetail extends Model

{
    use HasFactory;
    protected $guarded = ['id'];
    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
