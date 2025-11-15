<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prtut extends Model
{
    use HasFactory;

    protected $table = 'prtut';
    protected $primaryKey = 'id_habilitacion';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_habilitacion',
    ];

    // Relación con HabilitacionProfesional
    public function habilitacion()
    {
        return $this->belongsTo(HabilitacionProfesional::class, 'id_habilitacion', 'id_habilitacion');
    }

    // Relación con Realiza (empresas)
    public function realizas()
    {
        return $this->hasMany(Realiza::class, 'id_habilitacion', 'id_habilitacion');
    }
}