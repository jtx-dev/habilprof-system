<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabilitacionProfesional extends Model
{
    use HasFactory;

    protected $table = 'habilitacion_profesional';
    protected $primaryKey = 'id_habilitacion';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_habilitacion',
        'rut_alumno',
        'descripcion',
        'tipo',
        'nota_final',
        'fecha_nota_final',
        'semestre_inicio',
        'anhio',
    ];

    protected $casts = [
        'fecha_nota_final' => 'date',
        'nota_final' => 'decimal:1',
    ];

    // Relación con Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'rut_alumno', 'rut_alumno');
    }

    // Relación con Supervisa (profesores)
    public function supervisas()
    {
        return $this->hasMany(Supervisa::class, 'id_habilitacion', 'id_habilitacion');
    }

    // Relación con Realiza (empresas para PrTut)
    public function realizas()
    {
        return $this->hasMany(Realiza::class, 'id_habilitacion', 'id_habilitacion');
    }

    // Relaciones directas con cada tipo específico
    public function pring()
    {
        return $this->hasOne(Pring::class, 'id_habilitacion', 'id_habilitacion');
    }

    public function prinv()
    {
        return $this->hasOne(Prinv::class, 'id_habilitacion', 'id_habilitacion');
    }

    public function prtut()
    {
        return $this->hasOne(Prtut::class, 'id_habilitacion', 'id_habilitacion');
    }

    // Relación con el tipo específico - CORREGIDA
    public function tipoEspecifico()
    {
        $tipoModelClass = $this->getTipoModelClass();

        // Si no hay tipo válido, retornar una relación que siempre será null
        if (!$tipoModelClass) {
            // Esto crea una relación que no cargará ningún modelo
            return $this->hasOne(Pring::class, 'id_habilitacion', 'id_habilitacion')
                        ->whereRaw('1 = 0'); // Condición que nunca se cumple
        }

        return $this->hasOne($tipoModelClass, 'id_habilitacion', 'id_habilitacion');
    }

    private function getTipoModelClass()
    {
        return match($this->tipo) {
            'PrIng' => Pring::class,
            'PrInv' => Prinv::class,
            'PrTut' => Prtut::class,
            default => null,
        };
    }
}