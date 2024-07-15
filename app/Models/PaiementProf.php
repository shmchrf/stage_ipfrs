<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementProf extends Model
{
    use HasFactory;

    protected $fillable = [
        'prof_id',
        'session_id',
        'mode_paiement_id',
        'typeymntprofs_id',
        'montant',
        'montant_a_paye',
        'montant_paye',
        'date_paiement'
    ];

    public function prof()
    {
        return $this->belongsTo(Professeur::class, 'prof_id');
    }

    public function session()
    {
        return $this->belongsTo(Sessions::class, 'session_id');
    }

    public function mode()
    {
        return $this->belongsTo(ModePaiement::class, 'mode_paiement_id');
    }
    public function typeymntprofs()
    {
        return $this->belongsTo(Typeymntprofs::class, 'typeymntprofs_id');
    }
    public function type()
    {
        return $this->belongsTo(Typeymntprofs::class, 'typeymntprofs_id');
    }
}
