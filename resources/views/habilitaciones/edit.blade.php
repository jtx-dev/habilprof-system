<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Habilitación - HabilProf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">HabilProf - Editar Habilitación</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                        <a href="{{ route('habilitaciones.index') }}" class="text-gray-600 hover:text-gray-900">Listado</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido -->
        <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6">Editar Habilitación Profesional</h2>

                    <!-- Información de la Habilitación -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800">Información Actual</h3>
                        <p><strong>ID:</strong> {{ $habilitacion->id_habilitacion }}</p>
                        <p><strong>Alumno:</strong> {{ $habilitacion->alumno->nombre_alumno }} ({{ $habilitacion->alumno->rut_alumno }})</p>
                        <p><strong>Tipo:</strong>
                            @switch($habilitacion->tipo)
                                @case('PrIng') Práctica de Ingeniería @break
                                @case('PrInv') Proyecto de Investigación @break
                                @case('PrTut') Práctica Tutelada @break
                            @endswitch
                        </p>
                    </div>

                    <form method="POST" action="{{ route('habilitaciones.update', $habilitacion->id_habilitacion) }}" id="habilitacionForm">
                        @csrf
                        @method('PUT')

                        <!-- Información Básica -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Información Básica</h3>

                            <!-- Alumno (solo lectura) -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Alumno
                                </label>
                                <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                    {{ $habilitacion->alumno->nombre_alumno }} ({{ $habilitacion->alumno->rut_alumno }})
                                </div>
                                <p class="text-sm text-gray-500 mt-1">El alumno no puede ser modificado</p>
                            </div>

                            <!-- Tipo de Habilitación (modificable según R3.21) -->
                            <div class="mb-4">
                                <label for="tipo_habilitacion" class="block text-gray-700 text-sm font-bold mb-2">
                                    Tipo de Habilitación *
                                </label>
                                <select
                                    id="tipo_habilitacion"
                                    name="tipo_habilitacion"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                    onchange="cambiarTipoHabilitacion(this.value)"
                                >
                                    <option value="PrIng" {{ $habilitacion->tipo == 'PrIng' ? 'selected' : '' }}>Práctica de Ingeniería (PrIng)</option>
                                    <option value="PrInv" {{ $habilitacion->tipo == 'PrInv' ? 'selected' : '' }}>Proyecto de Investigación (PrInv)</option>
                                    <option value="PrTut" {{ $habilitacion->tipo == 'PrTut' ? 'selected' : '' }}>Práctica Tutelada (PrTut)</option>
                                </select>
                            </div>

                            <!-- Semestre y Año -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="semestre_inicio" class="block text-gray-700 text-sm font-bold mb-2">
                                        Semestre *
                                    </label>
                                    <select
                                        id="semestre_inicio"
                                        name="semestre_inicio"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    >
                                        <option value="1" {{ $habilitacion->semestre_inicio == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ $habilitacion->semestre_inicio == 2 ? 'selected' : '' }}>2</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="anhio" class="block text-gray-700 text-sm font-bold mb-2">
                                        Año *
                                    </label>
                                    <input
                                        type="number"
                                        id="anhio"
                                        name="anhio"
                                        value="{{ $habilitacion->anhio }}"
                                        min="1991"
                                        max="2050"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-4">
                                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">
                                    Descripción del Proyecto *
                                </label>
                                <textarea
                                    id="descripcion"
                                    name="descripcion"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Describa el proyecto o práctica a realizar..."
                                    required
                                >{{ $habilitacion->descripcion }}</textarea>
                            </div>
                        </div>

                        <!-- Campos específicos por tipo - PRING/PRINV -->
                        <div id="camposProyecto" class="mb-6 {{ $habilitacion->tipo === 'PrIng' || $habilitacion->tipo === 'PrInv' ? '' : 'hidden' }}">
                            <h3 class="text-lg font-semibold mb-4">Información del Proyecto</h3>
                            <div class="mb-4">
                                <label for="titulo_proyecto" class="block text-gray-700 text-sm font-bold mb-2">
                                    Título del Proyecto *
                                </label>
                                <input
                                    type="text"
                                    id="titulo_proyecto"
                                    name="titulo_proyecto"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Ingrese el título del proyecto..."
                                    value="{{ ($habilitacion->tipo === 'PrIng' || $habilitacion->tipo === 'PrInv') && isset($habilitacion->tipoEspecifico) ? $habilitacion->tipoEspecifico->titulo : '' }}"
                                >
                            </div>
                        </div>

                        <!-- Campos específicos por tipo - PRTUT -->
                        <div id="camposEmpresa" class="mb-6 {{ $habilitacion->tipo === 'PrTut' ? '' : 'hidden' }}">
                            <h3 class="text-lg font-semibold mb-4">Información de la Empresa</h3>
                            <div class="mb-4">
                                <label for="rut_empresa" class="block text-gray-700 text-sm font-bold mb-2">
                                    Empresa *
                                </label>
                                <select
                                    id="rut_empresa"
                                    name="rut_empresa"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Seleccionar Empresa</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->rut_empresa }}"
                                            {{ optional($habilitacion->realizas->first())->rut_empresa == $empresa->rut_empresa ? 'selected' : '' }}>
                                            {{ $empresa->nombre_empresa }} - {{ $empresa->supervi_empresa }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                                               <!-- Nota Final (solo lectura) -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Calificación</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Nota Final
                                    </label>
                                    <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                        @if($habilitacion->nota_final)
                                            {{ $habilitacion->nota_final }}
                                        @else
                                            <span class="text-gray-500">Sin nota registrada</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">La nota se carga automáticamente desde la sincronización</p>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Fecha de Nota
                                    </label>
                                    <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                        @if($habilitacion->fecha_nota_final)
                                            {{ \Carbon\Carbon::parse($habilitacion->fecha_nota_final)->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-500">Sin fecha registrada</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">La fecha se carga automáticamente desde la sincronización</p>
                                </div>
                            </div>
                        </div>

                        <!-- Asignación de Profesores - PRING/PRINV -->
                        <div id="profesoresProyecto" class="mb-6 {{ $habilitacion->tipo === 'PrIng' || $habilitacion->tipo === 'PrInv' ? '' : 'hidden' }}">
                            <h3 class="text-lg font-semibold mb-4">Asignación de Profesores</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                <strong>Requeridos:</strong> Profesor Guía y Profesor Comisión<br>
                                <strong>Opcional:</strong> Profesor Co-Guía
                            </p>

                            @php
                                // Obtener profesores actualmente asignados
                                $profesoresAsignados = [];
                                foreach ($habilitacion->supervisas as $supervisa) {
                                    $profesoresAsignados[$supervisa->tipo_profesor] = $supervisa->rut_profesor;
                                }
                            @endphp

                            @foreach (['Prof_guia' => 'Profesor Guía *', 'Prof_co_guia' => 'Profesor Co-Guía', 'Prof_comision' => 'Profesor Comisión *'] as $tipo => $label)
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        {{ $label }}
                                    </label>
                                    <select
                                        name="rut_profesor_{{ $tipo }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onchange="cargarInfoProfesor('{{ $tipo }}', this.value)"
                                    >
                                        <option value="">Seleccionar Profesor</option>
                                        @foreach ($profesores as $profesor)
                                            <option value="{{ $profesor->rut_profesor }}"
                                                {{ ($profesoresAsignados[$tipo] ?? '') == $profesor->rut_profesor ? 'selected' : '' }}>
                                                {{ $profesor->rut_profesor }} - {{ $profesor->nombre_profesor }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="infoProfesor{{ $tipo }}" class="mt-2 p-2 bg-green-50 rounded hidden">
                                        <span id="textoInfoProfesor{{ $tipo }}"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Asignación de Profesores - PRTUT -->
                        <div id="profesoresTutelada" class="mb-6 {{ $habilitacion->tipo === 'PrTut' ? '' : 'hidden' }}">
                            <h3 class="text-lg font-semibold mb-4">Asignación de Profesores</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                <strong>Requerido:</strong> Profesor Tutor
                            </p>

                            @php
                                // Obtener profesores actualmente asignados
                                $profesoresAsignados = [];
                                foreach ($habilitacion->supervisas as $supervisa) {
                                    $profesoresAsignados[$supervisa->tipo_profesor] = $supervisa->rut_profesor;
                                }
                            @endphp

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Profesor Tutor *
                                </label>
                                <select
                                    name="rut_profesor_Prof_tutor"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="cargarInfoProfesor('Prof_tutor', this.value)"
                                >
                                    <option value="">Seleccionar Profesor</option>
                                    @foreach ($profesores as $profesor)
                                        <option value="{{ $profesor->rut_profesor }}"
                                            {{ ($profesoresAsignados['Prof_tutor'] ?? '') == $profesor->rut_profesor ? 'selected' : '' }}>
                                            {{ $profesor->rut_profesor }} - {{ $profesor->nombre_profesor }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="infoProfesorProf_tutor" class="mt-2 p-2 bg-green-50 rounded hidden">
                                    <span id="textoInfoProfesorProf_tutor"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button
                                type="submit"
                                class="bg-blue-500 text-white py-3 px-6 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Actualizar Habilitación
                            </button>
                            <a href="{{ route('habilitaciones.index') }}" class="bg-gray-500 text-white py-3 px-6 rounded-md hover:bg-gray-600">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cambiarTipoHabilitacion(tipo) {
            console.log('Tipo seleccionado:', tipo);
            
            // Ocultar todos los campos primero
            document.getElementById('camposProyecto').classList.add('hidden');
            document.getElementById('camposEmpresa').classList.add('hidden');
            document.getElementById('profesoresProyecto').classList.add('hidden');
            document.getElementById('profesoresTutelada').classList.add('hidden');
            
            // Limpiar campos
            document.getElementById('titulo_proyecto').value = '';
            document.getElementById('rut_empresa').value = '';
            
            // Mostrar campos según el tipo
            if (tipo === 'PrIng' || tipo === 'PrInv') {
                document.getElementById('camposProyecto').classList.remove('hidden');
                document.getElementById('profesoresProyecto').classList.remove('hidden');
                
                // Actualizar el nombre del campo de título según el tipo
                if (tipo === 'PrIng') {
                    document.getElementById('titulo_proyecto').name = 'titulo_pring';
                } else {
                    document.getElementById('titulo_proyecto').name = 'titulo_prinv';
                }
                
            } else if (tipo === 'PrTut') {
                document.getElementById('camposEmpresa').classList.remove('hidden');
                document.getElementById('profesoresTutelada').classList.remove('hidden');
            }
        }

        function cargarInfoProfesor(tipo, rut) {
            if (!rut) {
                document.getElementById(`infoProfesor${tipo}`).classList.add('hidden');
                return;
            }

            fetch(`/habilitaciones/profesor-info/${rut}`)
                .then(response => response.json())
                .then(data => {
                    const infoProfesor = document.getElementById(`infoProfesor${tipo}`);
                    const textoInfoProfesor = document.getElementById(`textoInfoProfesor${tipo}`);
                    
                    if (data.error) {
                        textoInfoProfesor.textContent = 'Profesor no encontrado';
                    } else {
                        textoInfoProfesor.textContent = `Profesor: ${data.nombre}`;
                    }
                    infoProfesor.classList.remove('hidden');
                })
                .catch(() => {
                    const infoProfesor = document.getElementById(`infoProfesor${tipo}`);
                    const textoInfoProfesor = document.getElementById(`textoInfoProfesor${tipo}`);
                    textoInfoProfesor.textContent = 'Error al cargar información';
                    infoProfesor.classList.remove('hidden');
                });
        }

        // Inicializar mostrando los campos según el tipo actual
        document.addEventListener('DOMContentLoaded', function() {
            // Los campos ya se muestran/ocultan con PHP según el tipo actual
            console.log('Tipo de habilitación actual: {{ $habilitacion->tipo }}');
        });
    </script>
</body>
</html>