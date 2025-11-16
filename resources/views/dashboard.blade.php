<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HabilProf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">HabilProf - Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Bienvenido, {{ auth()->user()->nombre_profesor }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Tarjeta Ingresar Habilitación -->
                <a href="{{ route('habilitaciones.create') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Ingresar Habilitación</h3>
                                <p class="text-sm text-gray-500">Crear nueva habilitación profesional</p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Tarjeta Gestionar Habilitaciones -->
                <a href="{{ route('habilitaciones.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Gestionar Habilitaciones</h3>
                                <p class="text-sm text-gray-500">Editar, eliminar y ver habilitaciones</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Acciones Rápidas -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <a href="{{ route('habilitaciones.create') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition-colors">
                        <div class="text-green-600 font-semibold">Nueva Habilitación</div>
                        <div class="text-sm text-green-500 mt-1">Crear nueva</div>
                    </a>

                    <a href="{{ route('habilitaciones.index') }}" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition-colors">
                        <div class="text-purple-600 font-semibold">Ver Todas</div>
                        <div class="text-sm text-purple-500 mt-1">Listar habilitaciones</div>
                    </a>

                    <a href="{{ route('reportes.index') }}" class="bg-indigo-50 hover:bg-indigo-100 p-4 rounded-lg text-center transition-colors">
                        <div class="text-indigo-600 font-semibold">Reportes</div>
                        <div class="text-sm text-indigo-500 mt-1">Generar listados</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>