<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado por Profesor - HabilProf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">HabilProf - Listado por Profesor</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('reportes.index') }}" class="text-gray-600 hover:text-gray-900">Reportes</a>
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 class="text-lg font-semibold mb-4">Seleccionar Profesor</h2>
                <form method="GET" action="{{ route('reportes.por-profesor') }}" class="flex space-x-4">
                    <div class="flex-1">
                        <select id="profesor" name="profesor" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Seleccionar profesor...</option>
                            @foreach($profesores as $profesor)
                                <option value="{{ $profesor->rut_profesor }}" {{ $filtroProfesor == $profesor->rut_profesor ? 'selected' : '' }}>
                                    {{ $profesor->nombre_profesor }} ({{ $profesor->rut_profesor }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Generar Reporte
                        </button>
                    </div>
                </form>
            </div>

            @if($profesorSeleccionado)
                <!-- Información del Profesor -->
                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h2 class="text-2xl font-bold mb-4">{{ $profesorSeleccionado->nombre_profesor }}</h2>

                    <!-- Detalle por tipo de rol -->
                    @foreach(['Prof_guia' => 'Como Guía', 'Prof_co_guia' => 'Como Co-Guía', 'Prof_tutor' => 'Como Tutor', 'Prof_comision' => 'En Comisión'] as $tipo => $label)
                        @if(isset($habilitacionesProfesor[$tipo]) && $habilitacionesProfesor[$tipo]->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">{{ $label }}</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Semestre/Año</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nota</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($habilitacionesProfesor[$tipo] as $supervisa)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm">
                                                        {{ $supervisa->habilitacion->alumno->nombre_alumno }}<br>
                                                        <small class="text-gray-400">{{ $supervisa->habilitacion->alumno->rut_alumno }}</small>
                                                    </td>
                                                    <td class="px-4 py-2 text-sm">
                                                        @switch($supervisa->habilitacion->tipo)
                                                            @case('PrIng') PrIng @break
                                                            @case('PrInv') PrInv @break
                                                            @case('PrTut') PrTut @break
                                                        @endswitch
                                                    </td>
                                                    <td class="px-4 py-2 text-sm">
                                                        {{ $supervisa->habilitacion->semestre_inicio }}-{{ $supervisa->habilitacion->anhio }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm">
                                                        @if($supervisa->habilitacion->nota_final)
                                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                                                {{ $supervisa->habilitacion->nota_final }}
                                                            </span>
                                                        @else
                                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">
                                                                Sin nota
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <!-- Lista general de profesores -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold mb-6">Todos los Profesores</h2>

                        @if($profesores->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profesor</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Habilitaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($profesores as $profesor)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="?profesor={{ $profesor->rut_profesor }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                        {{ $profesor->nombre_profesor }}
                                                    </a>
                                                    <div class="text-sm text-gray-500">{{ $profesor->rut_profesor }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold">
                                                    {{ $profesor->total_guias + $profesor->total_co_guias + $profesor->total_tutores + $profesor->total_comisiones }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">No hay profesores registrados en el sistema.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>