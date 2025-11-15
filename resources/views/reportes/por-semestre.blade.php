<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado por Semestre - HabilProf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">HabilProf - Listado por Semestre</h1>
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
                <h2 class="text-lg font-semibold mb-4">Filtros</h2>
                <form method="GET" action="{{ route('reportes.por-semestre') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="anio" class="block text-sm font-medium text-gray-700">Año</label>
                        <select id="anio" name="anio" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Todos los años</option>
                            @foreach($anios as $anio)
                                <option value="{{ $anio }}" {{ $filtroAnio == $anio ? 'selected' : '' }}>{{ $anio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="semestre" class="block text-sm font-medium text-gray-700">Semestre</label>
                        <select id="semestre" name="semestre" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Todos los semestres</option>
                            <option value="1" {{ $filtroSemestre == '1' ? 'selected' : '' }}>Semestre 1</option>
                            <option value="2" {{ $filtroSemestre == '2' ? 'selected' : '' }}>Semestre 2</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Filtrar
                        </button>
                        <a href="{{ route('reportes.por-semestre') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Listado por Semestre</h2>
                        <div class="text-sm text-gray-500">
                            {{ $habilitaciones->count() }} habilitaciones encontradas
                        </div>
                    </div>

                    @if($habilitaciones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Semestre/Año
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Alumno
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Profesores
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nota
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($habilitaciones as $habilitacion)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $habilitacion->semestre_inicio }}-{{ $habilitacion->anhio }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="font-medium">{{ $habilitacion->alumno->nombre_alumno }}</div>
                                                <div class="text-gray-400">{{ $habilitacion->alumno->rut_alumno }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @switch($habilitacion->tipo)
                                                    @case('PrIng') 
                                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">PrIng</span>
                                                        @break
                                                    @case('PrInv')
                                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">PrInv</span>
                                                        @break
                                                    @case('PrTut')
                                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">PrTut</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @foreach($habilitacion->supervisas as $supervisa)
                                                    <div class="mb-1">
                                                        <span class="font-medium">{{ $supervisa->profesor->nombre_profesor }}</span>
                                                        <span class="text-gray-400 text-xs ml-2">
                                                            @switch($supervisa->tipo_profesor)
                                                                @case('Prof_guia') Guía @break
                                                                @case('Prof_co_guia') Co-Guía @break
                                                                @case('Prof_tutor') Tutor @break
                                                                @case('Prof_comision') Comisión @break
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($habilitacion->nota_final)
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        {{ $habilitacion->nota_final }}
                                                    </span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                                                        Sin nota
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No se encontraron habilitaciones con los filtros aplicados.</p>
                            <a href="{{ route('reportes.por-semestre') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Ver todas las habilitaciones
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>