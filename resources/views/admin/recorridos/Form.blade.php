<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulario Recorrido - ZooWonderland</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
    <!-- Vue 3 from CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
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
            --trans: all 0.3s ease;
        }

        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #fafafa; margin: 0; color: #333; }
        header { background: var(--selva-dark); color: white; }
        header nav { max-width: 1400px; margin: 0 auto; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        header .logo { font-size: 1.6rem; font-weight: bold; text-decoration: none; color: white; }
        header .menu a { color: white; text-decoration: none; margin-left: 1.5rem; font-weight: 600; }
        header .menu a:hover { text-decoration: underline; }
        main { max-width: 800px; margin: 0 auto; padding: 3rem 5%; }
        .form-section { background: var(--blanco); padding: 2.5rem; border-radius: 20px; box-shadow: var(--shadow-md); }
        .form-section h2 { color: var(--selva-dark); margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; transition: var(--trans); }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: var(--selva-light); box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.1); }
        .checkbox-group { display: flex; flex-wrap: wrap; gap: 1rem; }
        .checkbox-item { display: flex; align-items: center; gap: 0.5rem; }
        .btn { background: linear-gradient(135deg, var(--selva-light), var(--jungle-gold)); color: white; padding: 0.9rem 2rem; border: none; border-radius: 25px; font-weight: 700; cursor: pointer; transition: var(--trans); }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(255, 179, 0, 0.4); }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .back-link { display: inline-block; margin-top: 1rem; color: #555; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .error-list { background: #ffe6e6; border: 1px solid #cc0000; padding: 1rem; margin-bottom: 1rem; border-radius: 6px; }
        .error-list li { color: #c0392b; }
        .success-message { background: #e6f7ff; border: 1px solid #0275d8; padding: 1rem; margin-bottom: 1rem; border-radius: 6px; color: #0275d8; }
        .loading { display: inline-block; width: 20px; height: 20px; border: 3px solid #f3f3f3; border-top: 3px solid var(--selva-dark); border-radius: 50%; animation: spin 1s linear infinite; }
        /* custom area button styles */
        .area-buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        .area-btn {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            color: #475569;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            font-size: 0.9rem;
        }
        .area-btn:hover {
            border-color: var(--selva-light);
            background: #f0f9ff;
            color: var(--selva-dark);
        }
        .area-btn.selected {
            background: var(--selva-light);
            color: white;
            border-color: var(--selva-dark);
            box-shadow: 0 2px 8px rgba(46, 125, 50, 0.3);
        }
        .area-btn.selected:hover {
            background: var(--selva-dark);
            border-color: var(--selva-med);
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div id="app">
        <recorrido-form :recorrido-id="{{ $recorrido ? $recorrido->id_recorrido : 'null' }}"></recorrido-form>
    </div>

    <script>
        // Configurar Axios con CSRF
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // Componente completo del formulario
        const RecorridoForm = {
            template: `
                <div>
                    <header>
                        <nav>
                            <a href="/admin/dashboard" class="logo"><i class="fas fa-leaf"></i> ZooWonderland</a>
                            <div class="menu">
                                <a href="/admin/dashboard" title="Dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                                <a href="/admin/recorridos" title="Gestionar Recorridos"><i class="fas fa-compass"></i> Recorridos</a>
                                <a href="/admin/animales" title="Gestionar Animales"><i class="fas fa-paw"></i> Animales</a>
                                <a href="/admin/areas" title="Gestionar Áreas"><i class="fas fa-shapes"></i> Áreas</a>
                                <a href="/admin/reservas" title="Reservas"><i class="fas fa-calendar-check"></i> Reservas</a>
                                <a href="/admin/eventos" title="Gestionar Eventos"><i class="fas fa-calendar-days"></i> Eventos</a>
                                <a href="/logout" title="Salir"><i class="fas fa-sign-out-alt"></i> Salir</a>
                            </div>
                        </nav>
                    </header>

                    <main>
                        <div class="form-section">
                            <h2><i class="fas fa-route"></i> @{{ isEditing ? 'Editar' : 'Crear' }} Recorrido</h2>

                            <div v-if="mensajeExito" class="success-message">@{{ mensajeExito }}</div>
                            <div v-if="errores.length" class="error-list">
                                <ul>
                                    <li v-for="error in errores" :key="error">@{{ error }}</li>
                                </ul>
                            </div>

                            <form @submit.prevent="guardarRecorrido">
                                <div class="form-group">
                                    <label for="nombre">Nombre *</label>
                                    <input type="text" id="nombre" v-model="form.nombre" required>
                                </div>

                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <select id="tipo" v-model="form.tipo">
                                        <option value="Guiado">Guiado</option>
                                        <option value="No Guiado">No Guiado</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="precio">Precio (Bs.) *</label>
                                    <input type="number" step="0.01" min="0" id="precio" v-model.number="form.precio" required>
                                </div>

                                <div class="form-group">
                                    <label for="duracion">Duración (minutos)</label>
                                    <input type="number" min="0" id="duracion" v-model.number="form.duracion">
                                </div>

                                <div class="form-group">
                                    <label for="capacidad">Capacidad máxima</label>
                                    <input type="number" min="0" id="capacidad" v-model.number="form.capacidad">
                                </div>

                                <div class="form-group">
                                    <label>Áreas *</label>
                                    <div v-if="cargandoAreas" class="loading"></div>
                                    <div v-else class="area-buttons-grid">
                                        <button
                                            v-for="area in areas"
                                            :key="area.id_area"
                                            type="button"
                                            @click="toggleArea(area.id_area)"
                                            :class="['area-btn', form.areas.includes(area.id_area) ? 'selected' : '']"
                                        >
                                            @{{ area.nombre }}
                                        </button>
                                    </div>
                                    <div v-if="!areas.length && !cargandoAreas">
                                        <p>No hay áreas disponibles</p>
                                    </div>
                                </div>

                                <button type="submit" class="btn" :disabled="guardando">
                                    <span v-if="guardando" class="loading"></span>
                                    @{{ isEditing ? 'Actualizar' : 'Guardar' }}
                                </button>
                            </form>

                            <a href="/admin/recorridos" class="back-link"><i class="fas fa-arrow-left"></i> Volver a la lista</a>
                        </div>
                    </main>

                    <footer style="text-align:center; padding:2rem; background:var(--selva-dark); color:white; font-size:0.9rem;">
                        &copy; 2026 ZooWonderland - Panel de Administración.
                    </footer>
                </div>
            `,
            props: {
                recorridoId: { type: [Number, String], default: null }
            },
            data() {
                return {
                    isEditing: false,
                    form: {
                        nombre: '',
                        tipo: 'No Guiado',
                        precio: 0,
                        duracion: 0,
                        capacidad: 0,
                        areas: []
                    },
                    areas: [],
                    cargandoAreas: true,
                    guardando: false,
                    errores: [],
                    mensajeExito: ''
                };
            },
            computed: {
                recorridoIdNumerico() {
                    const id = this.recorridoId;
                    return id && id !== 'null' ? parseInt(id) : null;
                }
            },
            mounted() {
                this.isEditing = !!this.recorridoIdNumerico;
                this.cargarAreas();
                if (this.isEditing) {
                    this.cargarRecorrido();
                }
            },
            methods: {
                cargarAreas() {
                    axios
                        .get('/api/areas')
                        .then(response => {
                            this.areas = response.data.data || response.data;
                        })
                        .catch(err => {
                            console.error(err);
                            this.errores.push('Error al cargar las áreas');
                        })
                        .finally(() => (this.cargandoAreas = false));
                },
                cargarRecorrido() {
                    axios
                        .get(`/api/recorridos/${this.recorridoIdNumerico}`)
                        .then(response => {
                            const data = response.data.data || response.data;
                            this.form.nombre = data.nombre || '';
                            this.form.tipo = data.tipo || 'No Guiado';
                            this.form.precio = parseFloat(data.precio) || 0;
                            this.form.duracion = data.duracion || 0;
                            this.form.capacidad = data.capacidad || 0;
                            this.form.areas = data.areas ? data.areas.map(a => a.id_area) : [];
                        })
                        .catch(err => {
                            console.error(err);
                            this.errores.push('Error al cargar el recorrido');
                        });
                },
                toggleArea(areaId) {
                    const index = this.form.areas.indexOf(areaId);
                    if (index > -1) {
                        this.form.areas.splice(index, 1);
                    } else {
                        this.form.areas.push(areaId);
                    }
                },
                guardarRecorrido() {
                    this.guardando = true;
                    this.errores = [];
                    const datos = {
                        nombre: this.form.nombre,
                        tipo: this.form.tipo,
                        precio: parseFloat(this.form.precio),
                        duracion: parseInt(this.form.duracion) || 0,
                        capacidad: parseInt(this.form.capacidad) || 0,
                        areas: this.form.areas
                    };

                    const url = this.isEditing ? '/admin/recorridos/actualizar' : '/admin/recorridos/guardar';
                    if (this.isEditing) datos.id = this.recorridoIdNumerico;
                    axios
                        .post(url, datos)
                        .then(response => {
                            this.mensajeExito = `Recorrido ${this.isEditing ? 'actualizado' : 'creado'} exitosamente`;
                            setTimeout(() => (window.location.href = '/admin/recorridos'), 2000);
                        })
                        .catch(err => {
                            if (err.response?.data?.errors) {
                                this.errores = Object.values(err.response.data.errors).flat();
                            } else if (err.response?.data?.message) {
                                this.errores = [err.response.data.message];
                            } else {
                                this.errores = ['Error al guardar el recorrido'];
                            }
                        })
                        .finally(() => (this.guardando = false));
                }
            }
        };

        // Crear la app Vue
        const { createApp } = Vue;
        const app = createApp({
            components: {
                'recorrido-form': RecorridoForm
            }
        });

        app.mount('#app');
    </script>

</body>
</html>