<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HabilitacionProfesional;
use App\Models\ProfesorDinf;
use App\Models\Alumno;
use App\Models\Supervisa;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

        public function listadoPorSemestre(Request $request)
    {
        $filtroAnio = $request->input('anio');
        $filtroSemestre = $request->input('semestre');

        $query = HabilitacionProfesional::with(['alumno', 'supervisas.profesor'])
            ->join('alumno', 'habilitacion_profesional.rut_alumno', '=', 'alumno.rut_alumno')
            ->orderBy('habilitacion_profesional.anhio', 'desc')
            ->orderBy('habilitacion_profesional.semestre_inicio', 'desc')
            ->orderBy('alumno.nombre_alumno')
            ->select('habilitacion_profesional.*');

        if ($filtroAnio) {
            $query->where('habilitacion_profesional.anhio', $filtroAnio);
        }

        if ($filtroSemestre) {
            $query->where('habilitacion_profesional.semestre_inicio', $filtroSemestre);
        }

        $habilitaciones = $query->get();
        $anios = HabilitacionProfesional::distinct()->pluck('anhio')->sortDesc();

        return view('reportes.por-semestre', compact('habilitaciones', 'anios', 'filtroAnio', 'filtroSemestre'));
    }

    public function listadoPorProfesor(Request $request)
    {
        $filtroProfesor = $request->input('profesor');

        $profesores = ProfesorDinf::withCount([
            'supervisas as total_guias' => function($query) {
                $query->where('tipo_profesor', 'Prof_guia');
            },
            'supervisas as total_co_guias' => function($query) {
                $query->where('tipo_profesor', 'Prof_co_guia');
            },
            'supervisas as total_tutores' => function($query) {
                $query->where('tipo_profesor', 'Prof_tutor');
            },
            'supervisas as total_comisiones' => function($query) {
                $query->where('tipo_profesor', 'Prof_comision');
            }
        ])->get();

        $profesorSeleccionado = null;
        $habilitacionesProfesor = collect();

        if ($filtroProfesor) {
            $profesorSeleccionado = ProfesorDinf::find($filtroProfesor);
            if ($profesorSeleccionado) {
                $habilitacionesProfesor = Supervisa::with(['habilitacion.alumno', 'habilitacion'])
                    ->where('rut_profesor', $filtroProfesor)
                    ->get()
                    ->groupBy('tipo_profesor');
            }
        }

        return view('reportes.por-profesor', compact('profesores', 'profesorSeleccionado', 'habilitacionesProfesor', 'filtroProfesor'));
    }
}  