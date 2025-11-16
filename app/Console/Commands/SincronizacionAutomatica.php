<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;
use App\Models\ProfesorDinf;
use App\Models\HabilitacionProfesional;

class SincronizacionAutomatica extends Command
{
    protected $signature = 'habilprof:sincronizar';
    protected $description = 'Sincronización automática de datos desde sistemas UCSC';

    public function handle()
    {
        $this->info('Iniciando sincronización automática...');
        
        try {
            $resultados = [
                'profesores' => $this->sincronizarProfesores(),
                'alumnos' => $this->sincronizarAlumnos(),
                'notas' => $this->sincronizarNotas(),
            ];
            
            $this->info('Sincronización completada exitosamente');
            $this->info('Profesores procesados: ' . $resultados['profesores']['procesados']);
            $this->info('Alumnos procesados: ' . $resultados['alumnos']['procesados']);
            $this->info('Notas procesadas: ' . $resultados['notas']['procesados']);
            
        } catch (\Exception $e) {
            $this->error('Error en la sincronización: ' . $e->getMessage());
        }
    }

    private function sincronizarProfesores()
    {
        $dbPath = database_path('gestion_academica.db');

        if (!file_exists($dbPath)) {
            throw new \Exception("Base de datos de Gestión Académica no encontrada: " . $dbPath);
        }

        $db = new \SQLite3($dbPath);
        $result = $db->query('SELECT * FROM profesores_dinf');

        $procesados = 0;
        $nuevos = 0;

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $procesados++;

            $profesorExistente = ProfesorDinf::where('rut_profesor', $row['RUT_PROFESOR_DINF'])->first();

            if (!$profesorExistente) {
                $profesor = new ProfesorDinf();
		$profesor->rut_profesor = $row['RUT_PROFESOR_DINF'];
		$profesor->nombre_profesor = $row['NOMBRE_PROFESOR_DINF'];
		$profesor->password = substr($row['RUT_PROFESOR_DINF'], 0, -1); 			$profesor->save();
                $nuevos++;
            }
        }

        $db->close();

        return [
            'procesados' => $procesados,
            'nuevos' => $nuevos,
            'existentes' => $procesados - $nuevos
        ];
    }

    private function sincronizarAlumnos()
    {
        $dbPath = database_path('carga_academica.db');

        if (!file_exists($dbPath)) {
            throw new \Exception("Base de datos de Carga Académica no encontrada: " . $dbPath);
        }

        $db = new \SQLite3($dbPath);
        $result = $db->query('SELECT * FROM alumnos_habilitacion');

        $procesados = 0;
        $nuevos = 0;

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $procesados++;

            $alumnoExistente = Alumno::where('rut_alumno', $row['RUT_ALUMNO'])->first();

            if (!$alumnoExistente) {
                Alumno::create([
                    'rut_alumno' => $row['RUT_ALUMNO'],
                    'nombre_alumno' => $row['NOMBRE_ALUMNO'],
                ]);
                $nuevos++;
            }
        }

        $db->close();

        return [
            'procesados' => $procesados,
            'nuevos' => $nuevos,
            'existentes' => $procesados - $nuevos
        ];
    }

    private function sincronizarNotas()
    {
        $dbPath = database_path('notas_en_linea.db');

        if (!file_exists($dbPath)) {
            throw new \Exception("Base de datos de Notas en Línea no encontrada: " . $dbPath);
        }

        $db = new \SQLite3($dbPath);
        $result = $db->query('SELECT * FROM notas_finales');

        $procesados = 0;
        $actualizados = 0;

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $procesados++;

            $alumno = Alumno::where('rut_alumno', $row['RUT_ALUMNO'])->first();

            if ($alumno) {
                $habilitaciones = HabilitacionProfesional::where('rut_alumno', $row['RUT_ALUMNO'])
                    ->whereNull('nota_final')
                    ->get();

                foreach ($habilitaciones as $habilitacion) {
                    $fechaNotaFinal = sprintf('%04d-%02d-%02d', $row['AAAA'], $row['MM'], $row['DD']);

                    $habilitacion->update([
                        'nota_final' => $row['NOTA_FINAL'],
                        'fecha_nota_final' => $fechaNotaFinal,
                    ]);
                    $actualizados++;
                }
            }
        }

        $db->close();

        return [
            'procesados' => $procesados,
            'actualizados' => $actualizados,
            'sin_alumno' => $procesados - $actualizados
        ];
    }
}