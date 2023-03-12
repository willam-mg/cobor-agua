<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lectura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fecha',
        'hora',
        'periodo_gestion',
        'periodo_mes',
        'lectura_anterior',
        'lectura_actual',
        'metros_cubicos',
        'medidor_id',
        'operario_id',
    ];

    public function operario(): BelongsTo {
        return $this->belongsTo(Operario::class, 'operario_id');
    }

    public function medidor(): BelongsTo {
        return $this->belongsTo(Medidor::class, 'medidor_id');
    }

    public function detalleCobros(): HasMany {
        return $this->hasMany(DetalleCobro::class, 'lectura_id');
    }
}
