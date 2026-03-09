<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recorridos - ZooWonderland</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
    <style>
        :root {
            --verde-selva: #2e7d32;
            --verde-oscuro: #1b5e20;
            --amarillo-sol: #ffca28;
            --naranja-tigre: #f57c00;
            --oscuro: #0d3a1f;
        }
        
        .btn-primary {
            background-color: var(--verde-selva);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--verde-oscuro);
        }
        
        .card-recorrido {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card-recorrido:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div id="app">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-3xl font-bold" style="color: var(--verde-oscuro);">
                    🦁 Recorridos del ZooWonderland
                </h1>
                <p class="text-gray-600 mt-2">Explora nuestros increíbles recorridos</p>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Loading State -->
            <div v-if="cargando" class="flex justify-center items-center h-64">
                <div class="animate-spin">
                    <svg class="w-12 h-12" style="color: var(--verde-selva);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <p class="font-bold">Error</p>
                <p>@{{ error }}</p>
                <button @click="cargarRecorridos" class="btn-primary mt-2">Reintentar</button>
            </div>

            <!-- Recorridos Grid -->
            <div v-else-if="recorridos.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="recorrido in recorridos" 
                    :key="recorrido.id_recorrido"
                    class="card-recorrido bg-white rounded-lg shadow-md overflow-hidden"
                >
                    <!-- Card Header -->
                    <div class="h-32 bg-gradient-to-r" :style="{ backgroundImage: 'linear-gradient(to right, var(--verde-selva), var(--verde-oscuro))' }">
                        <div class="h-full flex items-center justify-center">
                            <span class="text-4xl">🗺️</span>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2" style="color: var(--verde-oscuro);">
                            @{{ recorrido.nombre }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4">
                            @{{ recorrido.descripcion }}
                        </p>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="text-center">
                                <span class="text-xs text-gray-500 block">Duración</span>
                                <span class="font-semibold" style="color: var(--naranja-tigre);">
                                    @{{ recorrido.duracion }} min
                                </span>
                            </div>
                            <div class="text-center">
                                <span class="text-xs text-gray-500 block">Capacidad</span>
                                <span class="font-semibold" style="color: var(--naranja-tigre);">
                                    @{{ recorrido.capacidad }} personas
                                </span>
                            </div>
                        </div>

                        <!-- Tipo de Recorrido -->
                        <div class="mb-4">
                            <span class="text-xs text-gray-500 block mb-1">Tipo</span>
                            <div class="flex items-center gap-2">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                    :style="{ backgroundColor: recorrido.tipo === 'Guiado' ? 'rgba(46, 125, 50, 0.1)' : 'rgba(255, 202, 40, 0.1)', color: recorrido.tipo === 'Guiado' ? 'var(--verde-selva)' : 'var(--amarillo-sol)' }">
                                    @{{ recorrido.tipo }}
                                </span>
                            </div>
                        </div>

                        <!-- Precio -->
                        <div class="bg-yellow-50 p-3 rounded-lg mb-4 border-l-4" style="border-color: var(--amarillo-sol);">
                            <span class="text-xs text-gray-600">Precio por persona</span>
                            <p class="text-2xl font-bold" style="color: var(--amarillo-sol);">
                                $@{{ parseFloat(recorrido.precio).toFixed(2) }}
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            <span 
                                v-if="recorrido.estado === 1" 
                                class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                style="background-color: rgba(46, 125, 50, 0.1); color: var(--verde-selva);"
                            >
                                ✓ Disponible
                            </span>
                            <span 
                                v-else 
                                class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"
                            >
                                ✕ No disponible
                            </span>
                        </div>

                        <!-- Actions -->
                        <button 
                            v-if="recorrido.estado === 1"
                            @click="mostrarDetalle(recorrido)"
                            class="w-full btn-primary"
                        >
                            Ver Detalles
                        </button>
                        <button 
                            v-else
                            disabled
                            class="w-full py-2 px-4 rounded bg-gray-300 text-gray-600 cursor-not-allowed"
                        >
                            No Disponible
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <p class="text-gray-500 text-lg">No hay recorridos disponibles</p>
            </div>
        </main>

        <!-- Modal Detalle -->
        <div v-if="recorridoSeleccionado" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold" style="color: var(--verde-oscuro);">
                            @{{ recorridoSeleccionado.nombre }}
                        </h2>
                        <button @click="recorridoSeleccionado = null" class="text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>

                    <div class="space-y-3 mb-6">
                        <p class="text-gray-700">@{{ recorridoSeleccionado.descripcion || recorridoSeleccionado.tipo }}</p>
                        
                        <div class="bg-gray-50 p-3 rounded">
                            <p class="text-sm text-gray-600">
                                <strong>Tipo:</strong> @{{ recorridoSeleccionado.tipo }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Duración:</strong> @{{ recorridoSeleccionado.duracion }} minutos
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Capacidad:</strong> @{{ recorridoSeleccionado.capacidad }} personas máximo
                            </p>
                            <p class="text-sm font-bold" style="color: var(--amarillo-sol);">
                                <strong>Precio:</strong> $@{{ parseFloat(recorridoSeleccionado.precio).toFixed(2) }} por persona
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button 
                            @click="recorridoSeleccionado = null"
                            class="flex-1 px-4 py-2 rounded border-2" 
                            style="border-color: var(--verde-selva); color: var(--verde-selva);"
                        >
                            Cerrar
                        </button>
                        <button 
                            class="flex-1 btn-primary"
                            @click="reservarRecorrido(recorridoSeleccionado)"
                        >
                            Reservar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vue 3 from CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    recorridos: [],
                    recorridoSeleccionado: null,
                    cargando: true,
                    error: null
                };
            },
            mounted() {
                this.cargarRecorridos();
            },
            methods: {
                cargarRecorridos() {
                    this.cargando = true;
                    this.error = null;

                    axios.get('/api/recorridos')
                        .then(response => {
                            this.recorridos = response.data.data || response.data;
                        })
                        .catch(error => {
                            this.error = error.response?.data?.message || 'Error al cargar recorridos';
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            this.cargando = false;
                        });
                },
                mostrarDetalle(recorrido) {
                    this.recorridoSeleccionado = recorrido;
                },
                reservarRecorrido(recorrido) {
                    alert(`¿Quieres reservar el recorrido: ${recorrido.nombre}?\n\nPrecio: $${parseFloat(recorrido.precio).toFixed(2)} por persona\n\nFuncionalidad de reserva próximamente...`);
                    this.recorridoSeleccionado = null;
                },
                formatHora(hora) {
                    if (!hora) return '-';
                    try {
                        return hora.substring(0, 5); // HH:MM
                    } catch {
                        return hora;
                    }
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
