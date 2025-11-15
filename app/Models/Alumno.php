<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'alumno';

    // Clave primaria
    protected $primaryKey = 'rut_alumno';

    // Tipo de clave primaria (no auto-incremental)
    public $incrementing = false;

    // Tipo de dato de la clave primaria
    protected $keyType = 'integer';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'rut_alumno',
        'nombre_alumno',
    ];

    // Campos de fecha automáticos
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Relación con HabilitacionProfesional
    public function habilitaciones()
    {
        return $this->hasMany(HabilitacionProfesional::class, 'rut_alumno', 'rut_alumno');
    }
}