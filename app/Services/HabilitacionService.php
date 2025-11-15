<?php

namespace App\Services;

use App\Models\HabilitacionProfesional;

class HabilitacionService
{
    public function generarIdHabilitacionUnico()
    {
        $maxIntentos = 1000; // Límite para evitar bucle infinito
        $intento = 0;

        while ($intento < $maxIntentos) {
            // Generar par ordenado según R2.1
            $letra = chr(rand(97, 122)); // a-z
            $numero = rand(1, 100); // 1-100

            $idHabilitacion = $letra . $numero;

            // Verificar si ya existe
            if (!HabilitacionProfesional::where('id_habilitacion', $idHabilitacion)->exists()) {
                return $idHabilitacion;
            }

            $intento++;
        }

        throw new \Exception('No se pudo generar un ID único después de ' . $maxIntentos . ' intentos');
    }

    public function validarRolesMinimos($tipoHabilitacion, $profesoresAsignados)
    {
        $errores = [];

        switch ($tipoHabilitacion) {
            case 'PrIng':
            case 'PrInv':
                // R2.15: Requiere PROFESOR_GUIA y PROFESOR_COMISION
                if (!in_array('Prof_guia', $profesoresAsignados)) {
                    $errores[] = 'Se requiere al menos un PROFESOR_GUIA para ' . $tipoHabilitacion;
                }
                if (!in_array('Prof_comision', $profesoresAsignados)) {
                    $errores[] = 'Se requiere al menos un PROFESOR_COMISION para ' . $tipoHabilitacion;
                }
                break;

            case 'PrTut':
                // R2.16: Requiere PROFESOR_TUTOR
                if (!in_array('Prof_tutor', $profesoresAsignados)) {
                    $errores[] = 'Se requiere al menos un PROFESOR_TUTOR para PrTut';
                }
                break;
        }

        return $errores;
    }

    public function validarCamposObligatorios($tipoHabilitacion, $datos)
    {
        $errores = [];

        switch ($tipoHabilitacion) {
            case 'PrIng':
            case 'PrInv':
                // R2.15: Campos obligatorios para PrIng/PrInv
                if (empty($datos['titulo_pring'] ?? $datos['titulo_prinv'] ?? '')) {
                    $errores[] = 'El título del proyecto es obligatorio para ' . $tipoHabilitacion;
                }
                break;

            case 'PrTut':
                // R2.16: Campos obligatorios para PrTut
                if (empty($datos['rut_empresa'] ?? '')) {
                    $errores[] = 'La empresa es obligatoria para PrTut';
                }
                break;
        }

        return $errores;
    }
}
