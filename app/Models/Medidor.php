<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medidor extends Model
{
    use HasFactory;

    protected $table = 'medidores';
    
    protected $fillable = [
        'codigo_medidor',
        'estado',
        'propiedad_id',
        'tarifa_id',
    ];

    public function propiedad(): BelongsTo {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
    
    public function tarifa(): BelongsTo {
        return $this->belongsTo(Tarifa::class, 'tarifa_id');
    }
}
