<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;
use App\Models\ProfesorDinf;
use App\Models\Empresa;
use App\Models\HabilitacionProfesional;
use App\Models\Pring;
use App\Models\Prinv;
use App\Models\Prtut;
use App\Models\Supervisa;
use App\Models\Realiza;
use App\Services\HabilitacionService;

class HabilitacionController extends Controller
{
    public function index()
    {
        $habilitaciones = HabilitacionProfesional::with(['alumno', 'supervisas.profesor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('habilitaciones.index', compact('habilitaciones'));
    }

    public function create()
    {
        $alumnos = Alumno::all();
        $profesores = ProfesorDinf::all();
        $empresas = Empresa::all();

        return view('habilitaciones.create', compact('alumnos', 'profesores', 'empresas'));
    }

    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'rut_alumno' => 'required|exists:alumno,rut_alumno',
            'tipo_habilitacion' => 'required|in:PrIng,PrInv,PrTut',
            'descripcion' => 'required|string|max:1000',
            'semestre_inicio' => 'required|in:1,2',
            'anhio' => 'required|integer|min:1991|max:2050',
        ]);

        // Validación de roles mínimos según R2.15 y R2.16
        $habilitacionService = new HabilitacionService();
        
        // Obtener profesores asignados
        $profesoresAsignados = [];
        $tiposProfesor = ['Prof_guia', 'Prof_co_guia', 'Prof_tutor', 'Prof_comision'];
        foreach ($tiposProfesor as $tipo) {
            if ($request->has("rut_profesor_$tipo") && $request->{"rut_profesor_$tipo"}) {
                $profesoresAsignados[] = $tipo;
            }
        }

        // Validar roles mínimos
        $erroresRoles = $habilitacionService->validarRolesMinimos($request->tipo_habilitacion, $profesoresAsignados);
        if (!empty($erroresRoles)) {
            return back()->withErrors($erroresRoles)->withInput();
        }

        // Validar que no haya profesores duplicados (R2.18)
        $erroresDuplicados = $this->validarProfesoresDuplicados($request);
        if (!empty($erroresDuplicados)) {
            return back()->withErrors($erroresDuplicados)->withInput();
        }

        // Validar campos obligatorios según tipo
        $erroresCampos = $habilitacionService->validarCamposObligatorios($request->tipo_habilitacion, $request->all());
        if (!empty($erroresCampos)) {
            return back()->withErrors($erroresCampos)->withInput();
        }

        try {
            DB::beginTransaction();

            // Generar ID de habilitación único (formato R2.1: letra + número)
            $idHabilitacion = $this->generarIdHabilitacion();

            // Crear habilitación principal
            $habilitacion = HabilitacionProfesional::create([
                'id_habilitacion' => $idHabilitacion,
                'rut_alumno' => $request->rut_alumno,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo_habilitacion,
                'semestre_inicio' => $request->semestre_inicio,
                'anhio' => $request->anhio,
            ]);

            // Crear registro según el tipo específico
            $this->crearTipoEspecifico($habilitacion, $request);

            // Asignar profesores
            $this->asignarProfesores($habilitacion, $request);

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', 'Habilitación creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la habilitación: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $habilitacion = HabilitacionProfesional::with([
            'alumno',
            'supervisas',
            'realizas'
        ])->findOrFail($id);

        // Cargar el tipo específico manualmente según el tipo de habilitación
        switch ($habilitacion->tipo) {
            case 'PrIng':
                $habilitacion->load('pring');
                $habilitacion->tipoEspecifico = $habilitacion->pring;
                break;
            case 'PrInv':
                $habilitacion->load('prinv');
                $habilitacion->tipoEspecifico = $habilitacion->prinv;
                break;
            case 'PrTut':
                $habilitacion->load('prtut');
                $habilitacion->tipoEspecifico = $habilitacion->prtut;
                break;
        }

        $alumnos = Alumno::all();
        $profesores = ProfesorDinf::all();
        $empresas = Empresa::all();

        return view('habilitaciones.edit', compact('habilitacion', 'alumnos', 'profesores', 'empresas'));
    }

    public function update(Request $request, $id)
    {
        $habilitacion = HabilitacionProfesional::findOrFail($id);

        // Validación básica - ahora incluye tipo_habilitacion que es modificable según R3.21
        $request->validate([
            'tipo_habilitacion' => 'required|in:PrIng,PrInv,PrTut',
            'descripcion' => 'required|string|max:1000',
            'semestre_inicio' => 'required|in:1,2',
            'anhio' => 'required|integer|min:1991|max:2050',
        ]);

        // Validación de roles mínimos según R2.15 y R2.16
        $habilitacionService = new HabilitacionService();
        
        // Obtener profesores asignados
        $profesoresAsignados = [];
        $tiposProfesor = ['Prof_guia', 'Prof_co_guia', 'Prof_tutor', 'Prof_comision'];
        foreach ($tiposProfesor as $tipo) {
            if ($request->has("rut_profesor_$tipo") && $request->{"rut_profesor_$tipo"}) {
                $profesoresAsignados[] = $tipo;
            }
        }

        // Validar roles mínimos - usa el nuevo tipo del request
        $erroresRoles = $habilitacionService->validarRolesMinimos($request->tipo_habilitacion, $profesoresAsignados);
        if (!empty($erroresRoles)) {
            return back()->withErrors($erroresRoles)->withInput();
        }

        // Validar que no haya profesores duplicados (R2.18)
        $erroresDuplicados = $this->validarProfesoresDuplicados($request);
        if (!empty($erroresDuplicados)) {
            return back()->withErrors($erroresDuplicados)->withInput();
        }

        // Validar campos obligatorios según tipo - usa el nuevo tipo del request
        $erroresCampos = $habilitacionService->validarCamposObligatorios($request->tipo_habilitacion, $request->all());
        if (!empty($erroresCampos)) {
            return back()->withErrors($erroresCampos)->withInput();
        }

        try {
            DB::beginTransaction();

            // Limpiar datos del tipo anterior si se cambió el tipo
            if ($habilitacion->tipo != $request->tipo_habilitacion) {
                $this->limpiarTipoAnterior($habilitacion);
            }

            // Actualizar habilitación principal - ahora incluye tipo_habilitacion
            $habilitacion->update([
                'tipo' => $request->tipo_habilitacion,
                'descripcion' => $request->descripcion,
                'semestre_inicio' => $request->semestre_inicio,
                'anhio' => $request->anhio,
            ]);

            // Actualizar tipo específico si aplica
            $this->actualizarTipoEspecifico($habilitacion, $request);

            // Actualizar profesores
            $this->actualizarProfesores($habilitacion, $request);

            DB::commit();

            return redirect()->route('habilitaciones.index')
                ->with('success', 'Habilitación actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la habilitación: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $habilitacion = HabilitacionProfesional::findOrFail($id);
            $habilitacion->delete();

            return redirect()->route('habilitaciones.index')
                ->with('success', 'Habilitación eliminada exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la habilitación: ' . $e->getMessage());
        }
    }

    public function getAlumnoInfo($rut)
    {
        $alumno = Alumno::find($rut);

        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }

        return response()->json([
            'nombre' => $alumno->nombre_alumno,
        ]);
    }

    public function getProfesorInfo($rut)
    {
        $profesor = ProfesorDinf::find($rut);

        if (!$profesor) {
            return response()->json(['error' => 'Profesor no encontrado'], 404);
        }

        return response()->json([
            'nombre' => $profesor->nombre_profesor,
        ]);
    }

    private function generarIdHabilitacion()
    {
        $habilitacionService = new HabilitacionService();
        return $habilitacionService->generarIdHabilitacionUnico();
    }

    private function crearTipoEspecifico($habilitacion, $request)
    {
        switch ($habilitacion->tipo) {
            case 'PrIng':
                $request->validate(['titulo_pring' => 'required|string|max:150']);
                Pring::create([
                    'id_habilitacion' => $habilitacion->id_habilitacion,
                    'titulo' => $request->titulo_pring,
                ]);
                break;

            case 'PrInv':
                $request->validate(['titulo_prinv' => 'required|string|max:150']);
                Prinv::create([
                    'id_habilitacion' => $habilitacion->id_habilitacion,
                    'titulo' => $request->titulo_prinv,
                ]);
                break;

            case 'PrTut':
                $request->validate(['rut_empresa' => 'required|exists:empresa,rut_empresa']);
                Prtut::create([
                    'id_habilitacion' => $habilitacion->id_habilitacion,
                ]);
                Realiza::create([
                    'id_habilitacion' => $habilitacion->id_habilitacion,
                    'rut_empresa' => $request->rut_empresa,
                ]);
                break;
        }
    }

    private function asignarProfesores($habilitacion, $request)
    {
        $tiposProfesor = ['Prof_guia', 'Prof_co_guia', 'Prof_tutor', 'Prof_comision'];

        foreach ($tiposProfesor as $tipo) {
            if ($request->has("rut_profesor_$tipo") && $request->{"rut_profesor_$tipo"}) {
                Supervisa::create([
                    'rut_profesor' => $request->{"rut_profesor_$tipo"},
                    'id_habilitacion' => $habilitacion->id_habilitacion,
                    'tipo_profesor' => $tipo,
                ]);
            }
        }
    }

    private function actualizarTipoEspecifico($habilitacion, $request)
    {
        switch ($habilitacion->tipo) {
            case 'PrIng':
                if ($request->has('titulo_pring')) {
                    $request->validate(['titulo_pring' => 'required|string|max:150']);
                    Pring::updateOrCreate(
                        ['id_habilitacion' => $habilitacion->id_habilitacion],
                        ['titulo' => $request->titulo_pring]
                    );
                }
                break;

            case 'PrInv':
                if ($request->has('titulo_prinv')) {
                    $request->validate(['titulo_prinv' => 'required|string|max:150']);
                    Prinv::updateOrCreate(
                        ['id_habilitacion' => $habilitacion->id_habilitacion],
                        ['titulo' => $request->titulo_prinv]
                    );
                }
                break;

            case 'PrTut':
                if ($request->has('rut_empresa')) {
                    $request->validate(['rut_empresa' => 'required|exists:empresa,rut_empresa']);
                    Realiza::updateOrCreate(
                        ['id_habilitacion' => $habilitacion->id_habilitacion],
                        ['rut_empresa' => $request->rut_empresa]
                    );
                }
                break;
        }
    }

    private function actualizarProfesores($habilitacion, $request)
    {
        // Eliminar asignaciones existentes
        Supervisa::where('id_habilitacion', $habilitacion->id_habilitacion)->delete();

        // Crear nuevas asignaciones
        $tiposProfesor = ['Prof_guia', 'Prof_co_guia', 'Prof_tutor', 'Prof_comision'];

        foreach ($tiposProfesor as $tipo) {
            if ($request->has("rut_profesor_$tipo") && $request->{"rut_profesor_$tipo"}) {
                Supervisa::create([
                    'rut_profesor' => $request->{"rut_profesor_$tipo"},
                    'id_habilitacion' => $habilitacion->id_habilitacion,
                    'tipo_profesor' => $tipo,
                ]);
            }
        }
    }

    private function limpiarTipoAnterior($habilitacion)
    {
        switch ($habilitacion->tipo) {
            case 'PrIng':
                Pring::where('id_habilitacion', $habilitacion->id_habilitacion)->delete();
                break;
            case 'PrInv':
                Prinv::where('id_habilitacion', $habilitacion->id_habilitacion)->delete();
                break;
            case 'PrTut':
                Prtut::where('id_habilitacion', $habilitacion->id_habilitacion)->delete();
                Realiza::where('id_habilitacion', $habilitacion->id_habilitacion)->delete();
                break;
        }
    }

    private function validarProfesoresDuplicados($request)
    {
        $errores = [];
        $profesoresAsignados = [];
        $tiposProfesor = ['Prof_guia', 'Prof_co_guia', 'Prof_tutor', 'Prof_comision'];

        // Recoger todos los profesores asignados
        foreach ($tiposProfesor as $tipo) {
            if ($request->has("rut_profesor_$tipo") && $request->{"rut_profesor_$tipo"}) {
                $rutProfesor = $request->{"rut_profesor_$tipo"};
                
                // Verificar si este profesor ya fue asignado en otro rol
                if (in_array($rutProfesor, $profesoresAsignados)) {
                    $profesor = ProfesorDinf::find($rutProfesor);
                    $nombreProfesor = $profesor ? $profesor->nombre_profesor : 'Profesor no encontrado';
                    $errores[] = "El profesor {$nombreProfesor} no puede tener múltiples roles en la misma habilitación";
                }
                
                $profesoresAsignados[] = $rutProfesor;
            }
        }

        return $errores;
    }
}