<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Recorridos - ZooWonderland</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
    <style>
        :root {
            --selva-dark: #2E7D32;
            --selva-med: #43A047;
            --selva-light: #66BB6A;
            --jungle-gold: #FFC107;
            --sky-blue: #0288D1;
            --blanco: #ffffff;
            --gris-bg: #f5f5f5;
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-sm: 0 2px 6px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
            --trans: all 0.3s ease;
        }

        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #fafafa; margin: 0; color: #333; }
        header { background: var(--selva-dark); color: white; }
        header nav { max-width: 1400px; margin: 0 auto; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        header .logo { font-size: 1.6rem; font-weight: bold; text-decoration: none; color: white; }
        header .menu a { color: white; text-decoration: none; margin-left: 1.5rem; font-weight: 600; }
        header .menu a:hover { text-decoration: underline; }
        main { max-width: 1400px; margin: 0 auto; padding: 3rem 5%; }
        .page-header h1 { font-size: 2.4rem; color: var(--selva-dark); margin-bottom: 0.5rem; }
        .page-header p { color: #666; }
        .content-section { background: var(--blanco); border-radius: 20px; padding: 2.5rem; box-shadow: var(--shadow-md); animation: fadeInUp 1s ease 0.2s both; }
        .flash { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1rem; font-weight: 600; }
        .flash.ok    { background: #e6f7ff; color: #0275d8; border: 1px solid #b3e5fc; }
        .flash.error { background: #ffe6e6; color: #c0392b; border: 1px solid #f5c6cb; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 3px solid var(--gris-bg); padding-bottom: 1.5rem; }
        .section-header h2 { font-size: 1.8rem; color: var(--selva-dark); display: flex; align-items: center; gap: 0.8rem; }
        .btn-primary { background: linear-gradient(135deg, var(--selva-light), var(--jungle-gold)); color: white; padding: 0.9rem 2rem; border-radius: 25px; text-decoration: none; font-weight: 700; border: none; cursor: pointer; transition: var(--trans); display: inline-flex; align-items: center; gap: 0.8rem; box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3); letter-spacing: 0.5px; }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 6px 25px rgba(255, 179, 0, 0.4); }
        table { width: 100%; border-collapse: collapse; }
        thead { background: linear-gradient(90deg, #f5f5f5 0%, #efefef 100%); border-bottom: 3px solid var(--jungle-gold); }
        th, td { padding: 1.2rem; text-align: left; }
        th { font-weight: 700; color: var(--selva-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        tbody tr { transition: var(--trans); }
        tbody tr:hover { background: linear-gradient(90deg, transparent, rgba(255,179,0,0.05), transparent); }
        .actions { display: flex; gap: 1rem; font-size: 0.85rem; }
        .actions a { text-decoration: none; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; transition: var(--trans); display: inline-flex; align-items: center; gap: 0.4rem; }
        .edit   { color: var(--selva-light); background: rgba(46, 125, 50, 0.1); }
        .edit:hover   { background: rgba(46, 125, 50, 0.25); transform: translateX(3px); }
        .delete { color: #e74c3c; background: rgba(231, 76, 60, 0.1); }
        .delete:hover { background: rgba(231, 76, 60, 0.25); transform: translateX(3px); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div id="app">
        <header>
            <nav>
                <a href="/admin/dashboard" class="logo">🍃 ZooWonderland Admin</a>
                <div class="menu">
                    <a href="/admin/dashboard">Dashboard</a>
                    <a href="/admin/recorridos">Recorridos</a>
                    <a href="/admin/animales">Animales</a>
                    <a href="/logout">Salir</a>
                </div>
            </nav>
        </header>

        <main>
            <div class="page-header">
                <h1>Gestión de Recorridos</h1>
                <p>Administra los recorridos disponibles en el zoológico</p>
            </div>

            <div class="content-section">
                <!-- Loading State -->
                <div v-if="cargando" class="flex justify-center items-center h-64">
                    <div class="animate-spin">
                        <svg class="w-12 h-12" style="color: var(--selva-dark);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <p class="font-bold">Error</p>
                    <p>@{{ error }}</p>
                    <button @click="cargarRecorridos" class="btn-primary mt-2">Reintentar</button>
                </div>

                <!-- Content -->
                <div v-else>
                    <div class="section-header">
                        <h2><i class="fas fa-route"></i> Lista de Recorridos</h2>
                        <button @click="crearRecorrido" class="btn-primary">
                            <i class="fas fa-plus"></i> Nuevo Recorrido
                        </button>
                    </div>

                    <!-- Table -->
                    <div v-if="recorridos.length > 0" class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Precio</th>
                                    <th>Duración</th>
                                    <th>Capacidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="recorrido in recorridos" :key="recorrido.id_recorrido">
                                    <td>@{{ recorrido.id_recorrido }}</td>
                                    <td>@{{ recorrido.nombre }}</td>
                                    <td>@{{ recorrido.tipo }}</td>
                                    <td>$@{{ parseFloat(recorrido.precio).toFixed(2) }}</td>
                                    <td>@{{ recorrido.duracion }} min</td>
                                    <td>@{{ recorrido.capacidad }}</td>
                                    <td>
                                        <span v-if="recorrido.estado == 1" class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                            Activo
                                        </span>
                                        <span v-else class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                            Inactivo
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <button @click="editarRecorrido(recorrido)" class="edit">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button @click="toggleEstadoRecorrido(recorrido)" class="delete">
                                            <i class="fas fa-power-off"></i> @{{ recorrido.estado == 1 ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <p class="text-gray-500 text-lg">No hay recorridos registrados</p>
                        <button @click="crearRecorrido" class="btn-primary mt-4">
                            <i class="fas fa-plus"></i> Crear el primer recorrido
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Vue 3 from CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <script>
        const { createApp } = Vue;

        // Configurar axios con CSRF token
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        createApp({
            data() {
                return {
                    recorridos: [],
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
                crearRecorrido() {
                    window.location.href = '/admin/recorridos/crear';
                },
                editarRecorrido(recorrido) {
                    window.location.href = `/admin/recorridos/editar?id=${recorrido.id_recorrido}`;
                },
                toggleEstadoRecorrido(recorrido) {
                    const accion = recorrido.estado == 1 ? 'desactivar' : 'activar';
                    const accionCapitalizada = recorrido.estado == 1 ? 'Desactivar' : 'Activar';

                    if (confirm(`¿Estás seguro de ${accion} el recorrido "${recorrido.nombre}"?`)) {
                        axios.post('/admin/recorridos/toggle-estado', {
                            id_recorrido: recorrido.id_recorrido
                        })
                        .then(response => {
                            alert(`Recorrido ${accion}do exitosamente`);
                            this.cargarRecorridos(); // Recargar la lista
                        })
                        .catch(error => {
                            alert(`Error al ${accion} el recorrido: ` + (error.response?.data?.message || 'Error desconocido'));
                            console.error('Error:', error);
                        });
                    }
                }
            }
        }).mount('#app');
    </script>
</body>
</html>