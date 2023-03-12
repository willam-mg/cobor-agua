<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propiedad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'propiedades';

    protected $fillable = [
        'direccion',
        'descripcion',
        'socio_id',
    ];

    public function socio(): BelongsTo {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function medidores(): HasMany {
        return $this->hasMany(Medidor::class, 'propiedad_id');
    }
}
