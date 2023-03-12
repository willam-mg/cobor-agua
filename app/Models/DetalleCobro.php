<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCobro extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cobro_id',
        'lectura_id',
    ];

    public function cobro(): BelongsTo
    {
        return $this->belongsTo(Cobro::class, 'cobro_id');
    }
    
    public function lectura(): BelongsTo
    {
        return $this->belongsTo(Lectura::class, 'lectura_id');
    }
}
