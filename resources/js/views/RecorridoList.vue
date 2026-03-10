<template>
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
      <div class="page-header">
        <h1>Gestión de Recorridos</h1>
        <p>Administra los recorridos disponibles en el zoológico</p>
      </div>

      <div class="content-section">
        <!-- Loading State -->
        <div v-if="cargando" class="loading-state">
          <div class="spinner"></div>
          <p>Cargando recorridos...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="error-state">
          <p><strong>Error:</strong> {{ error }}</p>
          <button @click="cargarRecorridos" class="btn-primary">Reintentar</button>
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
          <div v-if="recorridos.length > 0" class="table-container">
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
                  <td>{{ recorrido.id_recorrido }}</td>
                  <td>{{ recorrido.nombre }}</td>
                  <td>{{ recorrido.tipo }}</td>
                  <td>${{ parseFloat(recorrido.precio).toFixed(2) }}</td>
                  <td>{{ recorrido.duracion }} min</td>
                  <td>{{ recorrido.capacidad }}</td>
                  <td>
                    <span 
                      :class="recorrido.estado == 1 ? 'badge-active' : 'badge-inactive'"
                    >
                      {{ recorrido.estado == 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                  </td>
                  <td class="actions">
                    <button @click="editarRecorrido(recorrido)" class="btn-edit">
                      <i class="fas fa-edit"></i> Editar
                    </button>
                    <button @click="toggleEstadoRecorrido(recorrido)" class="btn-toggle">
                      <i class="fas fa-power-off"></i> {{ recorrido.estado == 1 ? 'Desactivar' : 'Activar' }}
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-else class="empty-state">
            <p>No hay recorridos registrados</p>
            <button @click="crearRecorrido" class="btn-primary">
              <i class="fas fa-plus"></i> Crear el primer recorrido
            </button>
          </div>
        </div>
      </div>
    </main>

    <footer>
      &copy; 2026 ZooWonderland - Panel de Administración.
    </footer>
  </div>
</template>

<script>
import api from '../services/api';

export default {
  name: 'RecorridoList',
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

      api
        .get('/api/recorridos')
        .then(response => {
          this.recorridos = response.data.data || response.data;
        })
        .catch(err => {
          this.error = err.response?.data?.message || 'Error al cargar recorridos';
          console.error('Error:', err);
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

      if (confirm(`¿Estás seguro de ${accion} el recorrido "${recorrido.nombre}"?`)) {
        api
          .post('/admin/recorridos/toggle-estado', {
            id_recorrido: recorrido.id_recorrido
          })
          .then(response => {
            alert(`Recorrido ${accion}do exitosamente`);
            this.cargarRecorridos();
          })
          .catch(error => {
            alert(
              `Error al ${accion} el recorrido: ` +
              (error.response?.data?.message || 'Error desconocido')
            );
            console.error('Error:', error);
          });
      }
    }
  }
};
</script>

<style scoped>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #fafafa;
  color: #333;
}

header {
  background: linear-gradient(135deg, #0a3d1f, #1b5e20) !important;
  color: white;
  box-shadow: 0 4px 12px rgba(10, 61, 31, 0.25);
}

header nav {
  max-width: 1400px;
  margin: 0 auto;
  padding: 1rem 5%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

header .logo {
  font-size: 1.6rem;
  font-weight: bold;
  text-decoration: none;
  color: white;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

header .menu {
  display: flex;
  gap: 1.5rem;
}

header .menu a {
  color: white;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

header .menu a:hover {
  text-decoration: underline;
  opacity: 0.8;
}

main {
  max-width: 1400px;
  margin: 0 auto;
  padding: 3rem 5%;
}

.page-header h1 {
  font-size: 2.4rem;
  color: #0a3d1f !important;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #666;
}

.content-section {
  background: #ffffff !important;
  border-radius: 20px;
  padding: 2.5rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  animation: fadeInUp 1s ease 0.2s both;
}

.loading-state, .empty-state {
  text-align: center;
  padding: 3rem;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #0a3d1f;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.error-state {
  background: #fdecea;
  border: 1px solid #f5c6cb;
  padding: 1.5rem;
  border-radius: 8px;
  text-align: center;
}

.error-state p { color: #c0392b; margin-bottom: 1rem; }

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  border-bottom: 3px solid #f0f7f4;
  padding-bottom: 1.5rem;
}

.section-header h2 {
  font-size: 1.8rem;
  color: #0a3d1f !important;
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.btn-primary {
  background: linear-gradient(135deg, #2e7d32, #ffb300) !important;
  color: white !important;
  padding: 0.9rem 2rem;
  border-radius: 25px;
  border: none !important;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.8rem;
  box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 25px rgba(255, 179, 0, 0.4);
}

.table-container {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: linear-gradient(90deg, #f5f5f5 0%, #efefef 100%);
  border-bottom: 3px solid #ffb300;
}

th, td {
  padding: 1.2rem;
  text-align: left;
}

th {
  font-weight: 700;
  color: #0a3d1f;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 1px;
}

tbody tr {
  transition: all 0.3s ease;
}

tbody tr:hover {
  background: linear-gradient(90deg, transparent, rgba(255,179,0,0.05), transparent);
}

.actions {
  display: flex;
  gap: 0.5rem;
  font-size: 0.85rem;
}

.btn-edit, .btn-toggle {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.9rem;
}

.btn-edit {
  color: white;
  background: #2e7d32 !important;
}

.btn-edit:hover {
  background: #1b5e20 !important;
  transform: translateX(3px);
}

.btn-toggle {
  color: white;
  background: #ff8c00 !important;
}

.btn-toggle:hover {
  background: #ffb300 !important;
  transform: translateX(3px);
}

.badge-active {
  display: inline-block;
  padding: 0.5rem 1rem;
  background: #2e7d32 !important;
  color: white !important;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  border: 1px solid #2e7d32;
}

.badge-inactive {
  display: inline-block;
  padding: 0.5rem 1rem;
  background: #ff8c00 !important;
  color: white !important;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

footer {
  text-align: center;
  padding: 2rem;
  background: #0a3d1f;
  color: white;
  font-size: 0.9rem;
  margin-top: 3rem;
}
</style>
