<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Habilitaciones - HabilProf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">HabilProf - Gestión de Habilitaciones</h1>
                    </div>
                    <div class="flex items-center space-x-4">
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
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Listado de Habilitaciones</h2>
                        <a href="{{ route('habilitaciones.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Nueva Habilitación
                        </a>
                    </div>

                    @if($habilitaciones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Alumno
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Semestre/Año
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nota
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($habilitaciones as $habilitacion)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $habilitacion->id_habilitacion }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $habilitacion->alumno->nombre_alumno }}<br>
                                                <small class="text-gray-400">{{ $habilitacion->alumno->rut_alumno }}</small>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @switch($habilitacion->tipo)
                                                    @case('PrIng') Práctica de Ingeniería @break
                                                    @case('PrInv') Proyecto de Investigación @break
                                                    @case('PrTut') Práctica Tutelada @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $habilitacion->semestre_inicio }}-{{ $habilitacion->anhio }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($habilitacion->nota_final)
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                                        {{ $habilitacion->nota_final }}
                                                    </span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                                                        Sin nota
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('habilitaciones.edit', $habilitacion->id_habilitacion) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                    Editar
                                                </a>
                                                <form action="{{ route('habilitaciones.destroy', $habilitacion->id_habilitacion) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar esta habilitación?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No hay habilitaciones registradas.</p>
                            <a href="{{ route('habilitaciones.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Crear la primera habilitación
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>