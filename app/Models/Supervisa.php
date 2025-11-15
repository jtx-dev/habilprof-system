<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisa extends Model
{
    use HasFactory;

    protected $table = 'supervisa';

    protected $fillable = [
        'rut_profesor',
        'id_habilitacion',
        'tipo_profesor',
    ];

    // Relación con ProfesorDinf
    public function profesor()
    {
        return $this->belongsTo(ProfesorDinf::class, 'rut_profesor', 'rut_profesor');
    }

    // Relación con HabilitacionProfesional
    public function habilitacion()
    {
        return $this->belongsTo(HabilitacionProfesional::class, 'id_habilitacion', 'id_habilitacion');
    }
}