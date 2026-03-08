<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\RecorridoRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\AreaRepository;
use App\Repositories\ReservaRepository;
use App\Services\EventoService;

class AdminController
{
    private AuthService         $auth;
    private RecorridoRepository $recorridoRepo;
    private UsuarioRepository   $usuarioRepo;
     private EventoService $eventoService;

    public function __construct()
    {
        $this->auth          = new AuthService();
        $this->recorridoRepo = new RecorridoRepository();
        $this->usuarioRepo   = new UsuarioRepository();
        $this->eventoService = new EventoService();
    }

    // ── Auth: redirige si no es admin ─────────────────────────────
    private function checkAuth(): \App\Models\Usuario
    {
        if (!$this->auth->check()) {
            header('Location: index.php?r=login');
            exit;
        }
        $user = $this->auth->user();
        if (!$user || !$user->esAdministrador()) {
            header('Location: index.php');
            exit;
        }
        return $user;
    }

    // ── Auth: lanza 403 para acciones POST ────────────────────────
    private function requireAdmin(): \App\Models\Usuario
    {
        if (!$this->auth->check()) {
            header('Location: index.php?r=login');
            exit;
        }
        $user = $this->auth->user();
        if (!$user || !$user->esAdministrador()) {
            http_response_code(403);
            require_once APP_PATH . '/Views/errors/403.php';
            exit;
        }
        return $user;
    }

    // ════════════════════════════════════════════════════════════
    // FUNCIONES EXISTENTES — sin cambios
    // ════════════════════════════════════════════════════════════

    public function dashboard(): void
    {
        $user = $this->checkAuth();

        $recorridos      = $this->recorridoRepo->findAll() ?? [];
        $totalRecorridos = count($recorridos);

        $db   = \Core\Database::getInstance();
        $conn = $db->getConnection();

        $areaResult     = $conn->query("SELECT COUNT(*) as total FROM areas");
        $totalAreas     = $areaResult ? $areaResult->fetch(\PDO::FETCH_ASSOC)['total'] : 0;

        $reservasResult = $conn->query("SELECT COUNT(*) as total FROM reservas WHERE estado = 1");
        $totalReservas  = $reservasResult ? $reservasResult->fetch(\PDO::FETCH_ASSOC)['total'] : 0;

        $ingresosResult = $conn->query("SELECT SUM(monto) as total FROM compras WHERE estado_pago = 1");
        $totalIngresos  = $ingresosResult ? ($ingresosResult->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0) : 0;

        $animalesResult = $conn->query("SELECT COUNT(*) as total FROM animales");
        $totalAnimales  = $animalesResult ? $animalesResult->fetch(\PDO::FETCH_ASSOC)['total'] : 0;

        $guiasResult = $conn->query("SELECT COUNT(*) as total FROM guias");
        $totalGuias  = $guiasResult ? $guiasResult->fetch(\PDO::FETCH_ASSOC)['total'] : 0;

        require APP_PATH . '/Views/admin/dashboard.php';
    }

    public function recorridos(): void
    {
        $user       = $this->checkAuth();
        $recorridos = $this->recorridoRepo->findAll() ?? [];
        require APP_PATH . '/Views/admin/recorridos.php';
    }

    /**
     * Mostrar formulario para crear un recorrido.
     */
    public function crearRecorrido(): void
    {
        $this->checkAuth();
        $areas = (new AreaRepository())->findAll();
        $recorrido = null;
        $selectedAreas = [];
        $errores = $_SESSION['form_errores'] ?? [];
        $datos = $_SESSION['form_datos'] ?? [];
        unset($_SESSION['form_datos'], $_SESSION['form_errores']);
        $action = 'guardar';
        require APP_PATH . '/Views/admin/recorrido_form.php';
    }

    /**
     * Guarda nuevo recorrido (POST).
     */
    public function guardarRecorrido(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?r=admin/recorridos');
            exit;
        }

        $nombre     = trim($_POST['nombre'] ?? '');
        $tipo       = trim($_POST['tipo'] ?? 'No Guiado');
        $precio     = (float)($_POST['precio'] ?? 0);
        $duracion   = (int)($_POST['duracion'] ?? 0);
        $capacidad  = (int)($_POST['capacidad'] ?? 0);
        $areasIds   = $_POST['areas'] ?? [];

        $errores = [];
        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio.';
        }
        if ($precio <= 0) {
            $errores[] = 'El precio debe ser mayor que cero.';
        }
        if (!in_array($tipo, ['Guiado','No Guiado'], true)) {
            $tipo = 'No Guiado';
        }

        // validar áreas existentes
        $areaRepo = new AreaRepository();
        $validAreas = [];
        foreach ($areasIds as $aid) {
            $aidInt = (int)$aid;
            if ($aidInt > 0 && $areaRepo->findById($aidInt)) {
                $validAreas[] = $aidInt;
            }
        }
        if (empty($validAreas)) {
            $errores[] = 'Debe seleccionar al menos un área válida.';
        }

        if (!empty($errores)) {
            $_SESSION['form_errores'] = $errores;
            $_SESSION['form_datos']   = [
                'nombre' => $nombre,
                'tipo' => $tipo,
                'precio' => $precio,
                'duracion' => $duracion,
                'capacidad' => $capacidad,
                'areas' => $validAreas,
            ];
            header('Location: index.php?r=admin/recorridos/crear');
            exit;
        }

        $id = $this->recorridoRepo->create([
            'nombre' => $nombre,
            'tipo' => $tipo,
            'precio' => $precio,
            'duracion' => $duracion,
            'capacidad' => $capacidad,
            'areas' => $validAreas,
        ]);

        $_SESSION['flash_mensaje'] = $id ? 'Recorrido creado correctamente.' : 'Error al crear el recorrido.';
        $_SESSION['flash_tipo'] = $id ? 'ok' : 'error';
        header('Location: index.php?r=admin/recorridos');
        exit;
    }

    /**
     * Formulario de edición de recorrido.
     */
    public function editarRecorrido(): void
    {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 0) {
            header('Location: index.php?r=admin/recorridos');
            exit;
        }
        $recorrido = $this->recorridoRepo->findById($id);
        if (!$recorrido) {
            header('Location: index.php?r=admin/recorridos');
            exit;
        }
        $areas = (new AreaRepository())->findAll();
        $selectedAreas = $this->recorridoRepo->getAreas($id);
        $selectedAreas = array_map(fn($a) => $a['id_area'] ?? $a['id'], $selectedAreas);
        $errores = $_SESSION['form_errores'] ?? [];
        $datos   = $_SESSION['form_datos'] ?? [];
        unset($_SESSION['form_errores'], $_SESSION['form_datos']);
        $action = "actualizar&id={$id}";
        require APP_PATH . '/Views/admin/recorrido_form.php';
    }

    /**
     * Actualiza el recorrido (POST).
     */
    public function actualizarRecorrido(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?r=admin/recorridos');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        $recorrido = $this->recorridoRepo->findById($id);
        if (!$recorrido) {
            header('Location: index.php?r=admin/recorridos');
            exit;
        }

        $nombre     = trim($_POST['nombre'] ?? '');
        $tipo       = trim($_POST['tipo'] ?? 'No Guiado');
        $precio     = (float)($_POST['precio'] ?? 0);
        $duracion   = (int)($_POST['duracion'] ?? 0);
        $capacidad  = (int)($_POST['capacidad'] ?? 0);
        $areasIds   = $_POST['areas'] ?? [];

        $errores = [];
        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio.';
        }
        if ($precio <= 0) {
            $errores[] = 'El precio debe ser mayor que cero.';
        }
        if (!in_array($tipo, ['Guiado','No Guiado'], true)) {
            $tipo = 'No Guiado';
        }

        // validar áreas
        $areaRepo = new AreaRepository();
        $validAreas = [];
        foreach ($areasIds as $aid) {
            $aidInt = (int)$aid;
            if ($aidInt > 0 && $areaRepo->findById($aidInt)) {
                $validAreas[] = $aidInt;
            }
        }
        if (empty($validAreas)) {
            $errores[] = 'Debe seleccionar al menos un área válida.';
        }

        if (!empty($errores)) {
            $_SESSION['form_errores'] = $errores;
            $_SESSION['form_datos']   = [
                'nombre' => $nombre,
                'tipo' => $tipo,
                'precio' => $precio,
                'duracion' => $duracion,
                'capacidad' => $capacidad,
                'areas' => $validAreas,
            ];
            header("Location: index.php?r=admin/recorridos/editar&id={$id}");
            exit;
        }

        $ok = $this->recorridoRepo->update($id, [
            'nombre' => $nombre,
            'tipo' => $tipo,
            'precio' => $precio,
            'duracion' => $duracion,
            'capacidad' => $capacidad,
            'areas' => $validAreas,
        ]);

        $_SESSION['flash_mensaje'] = $ok ? 'Recorrido actualizado correctamente.' : 'Error al actualizar el recorrido.';
        $_SESSION['flash_tipo'] = $ok ? 'ok' : 'error';
        header('Location: index.php?r=admin/recorridos');
        exit;
    }

    /**
     * Elimina un recorrido si no tiene reservas activas.
     */
    public function eliminarRecorrido(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 0) {
            header('Location: index.php?r=admin/recorridos');
            exit;
        }

        // comprobar que no existan reservas activas para ese recorrido
        $resRepo = new ReservaRepository();
        $count = $resRepo->countActivasByRecorrido($id);
        if ($count > 0) {
            $_SESSION['flash_mensaje'] = 'No se puede eliminar el recorrido porque hay reservas activas.';
            $_SESSION['flash_tipo'] = 'error';
            header('Location: index.php?r=admin/recorridos');
            exit;
        }

        try {
            $ok = $this->recorridoRepo->delete($id);
            $_SESSION['flash_mensaje'] = $ok ? 'Recorrido eliminado.' : 'Error al eliminar el recorrido.';
            $_SESSION['flash_tipo'] = $ok ? 'ok' : 'error';
        } catch (\PDOException $e) {
            // restricción FK
            $_SESSION['flash_mensaje'] = 'No se puede eliminar el recorrido porque tiene datos relacionados.';
            $_SESSION['flash_tipo'] = 'error';
        }
        header('Location: index.php?r=admin/recorridos');
        exit;
    }

    // ════════════════════════════════════════════════════════════
    // NUEVAS FUNCIONES — gestión de usuarios (HU-10)
    // ════════════════════════════════════════════════════════════

    /**
     * Lista de usuarios con filtros.
     * El admin autenticado nunca aparece en la tabla.
     */
    public function usuarios(): void
    {
        $user = $this->checkAuth();

        $busqueda     = trim($_GET['busqueda']   ?? '');
        $rolFiltro    = trim($_GET['rol']        ?? '');
        $recorrido    = (int)($_GET['recorrido'] ?? 0);
        $estadoFiltro = trim($_GET['estado']     ?? '');

        $usuarios   = $this->usuarioRepo->getUsuariosFiltrados(
            $busqueda,
            $rolFiltro,
            $recorrido,
            $estadoFiltro,
            $user->id_usuario   // ← excluye al admin autenticado
        );
        $recorridos = $this->usuarioRepo->getRecorridosParaFiltro();

        $mensaje = $_SESSION['flash_mensaje'] ?? null;
        $tipoMsg = $_SESSION['flash_tipo']    ?? 'ok';
        unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);

        require APP_PATH . '/Views/admin/usuarios.php';
    }

    /**
     * Formulario de edición de usuario (GET).
     */
    public function editarUsuarioForm(): void
    {
        $user = $this->checkAuth();

        $id = (int)($_GET['id'] ?? 0);
        if ($id === 0) {
            header('Location: index.php?r=admin/usuarios');
            exit;
        }

        $usuarioEditar = $this->usuarioRepo->getUsuarioPorId($id);
        if (!$usuarioEditar) {
            header('Location: index.php?r=admin/usuarios');
            exit;
        }

        // Protección: el admin no puede editarse a sí mismo
        if ((int)$usuarioEditar['id_usuario'] === (int)$user->id_usuario) {
            $_SESSION['flash_mensaje'] = 'No puedes editarte a ti mismo desde este panel.';
            $_SESSION['flash_tipo']    = 'error';
            header('Location: index.php?r=admin/usuarios');
            exit;
        }

        $errores = $_SESSION['form_errores'] ?? [];
        $datos   = $_SESSION['form_datos']   ?? [];
        unset($_SESSION['form_errores'], $_SESSION['form_datos']);

        require APP_PATH . '/Views/admin/usuario_form.php';
    }

    /**
     * Procesa el formulario de edición de usuario (POST).
     */
    public function editarUsuarioPost(): void
    {
        $user = $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?r=admin/usuarios');
            exit;
        }

        $id             = (int)($_POST['id_usuario']     ?? 0);
        $nombre1        = trim($_POST['nombre1']         ?? '');
        $nombre2        = trim($_POST['nombre2']         ?? '');
        $apellido1      = trim($_POST['apellido1']       ?? '');
        $apellido2      = trim($_POST['apellido2']       ?? '');
        $ci             = (int)($_POST['ci']             ?? 0);
        $telefono       = trim($_POST['telefono']        ?? '');
        $rol            = trim($_POST['rol']             ?? '');
        $correo         = trim($_POST['correo']          ?? '');
        $nombre_usuario = trim($_POST['nombre_usuario']  ?? '');

        // Validaciones básicas
        $errores = [];
        if ($nombre1 === '')        $errores[] = 'El primer nombre es obligatorio.';
        if ($apellido1 === '')      $errores[] = 'El primer apellido es obligatorio.';
        if ($ci <= 0)               $errores[] = 'El CI debe ser un número positivo.';
        if ($correo === '')         $errores[] = 'El correo es obligatorio.';
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no tiene un formato válido.';
        }
        if ($nombre_usuario === '') $errores[] = 'El nombre de usuario es obligatorio.';
        if (!in_array($rol, ['cliente', 'administrador', 'guia'], true)) {
            $errores[] = 'Rol inválido.';
        }

        if (!empty($errores)) {
            $_SESSION['form_errores'] = $errores;
            $_SESSION['form_datos']   = $_POST;
            header("Location: index.php?r=admin/usuario-editar&id={$id}");
            exit;
        }

        try {
            $ok = $this->usuarioRepo->actualizarUsuario(
                $id, $nombre1, $nombre2, $apellido1, $apellido2,
                $ci, $telefono, $rol, $correo, $nombre_usuario
            );

            $_SESSION['flash_mensaje'] = $ok
                ? 'Usuario actualizado correctamente.'
                : 'Error al actualizar el usuario.';
            $_SESSION['flash_tipo'] = $ok ? 'ok' : 'error';

        } catch (\Exception $e) {
            // Correo o nombre_usuario duplicado
            $_SESSION['form_errores'] = [$e->getMessage()];
            $_SESSION['form_datos']   = $_POST;
            header("Location: index.php?r=admin/usuario-editar&id={$id}");
            exit;
        }

        header('Location: index.php?r=admin/usuarios');
        exit;
    }

    /**
     * Activa o desactiva la cuenta de un usuario (POST).
     */
    public function toggleEstado(): void
    {
        $user = $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?r=admin/usuarios');
            exit;
        }

        $id     = (int)($_POST['id_usuario'] ?? 0);
        $estado = (int)($_POST['estado']     ?? 1);

        if ($id === 0) {
            header('Location: index.php?r=admin/usuarios');
            exit;
        }

        // No puede desactivarse a sí mismo
        if ((int)$user->id_usuario === $id) {
            $_SESSION['flash_mensaje'] = 'No puedes desactivar tu propia cuenta.';
            $_SESSION['flash_tipo']    = 'error';
            header('Location: index.php?r=admin/usuarios');
            exit;
        }

        $nuevoEstado = $estado === 1 ? 0 : 1;
        $ok = $this->usuarioRepo->cambiarEstado($id, $nuevoEstado);

        $_SESSION['flash_mensaje'] = $ok
            ? ($nuevoEstado === 1 ? 'Cuenta activada correctamente.' : 'Cuenta desactivada correctamente.')
            : 'Error al cambiar el estado.';
        $_SESSION['flash_tipo'] = $ok ? 'ok' : 'error';

        header('Location: index.php?r=admin/usuarios');
        exit;
    }
  //FUNCIONES PARTE PARA LOS EVENTOS
    public function eventos(): void
    {
        $user = $this->checkAuth();
        $filtros = $_GET;  // filtros de GET (vigencia, fecha, nombre)
        $eventos = $this->eventoService->getAll($filtros);
        $guias = (new \App\Repositories\GuiaRepository())->getGuiasDisponibles();
        require APP_PATH . '/Views/admin/eventos.php';
    }
public function detalleEvento(): void
    {
       $user = $this->checkAuth();
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: index.php?r=admin/eventos');
            exit;
        }

        $evento = $this->eventoService->getById($id);

        if (!$evento) {
            $_SESSION['error'] = 'Evento no encontrado';
            header('Location: index.php?r=admin/eventos');
            exit;
        }

        require APP_PATH . '/Views/admin/evento_detalle.php';
    }
    public function eventoForm(): void
{
    $user = $this->checkAuth();
    $id = (int) ($_GET['id'] ?? 0);
    $evento = $id ? $this->eventoService->getById($id) : null;

    // Cargar guías disponibles (esto faltaba o estaba mal)
    $guiaRepo = new \App\Repositories\GuiaRepository();
    $guias = $guiaRepo->getGuiasDisponibles();

    // Cargar áreas si las necesitas
    $areaRepo = new \App\Repositories\AreaRepository(); // crea si no existe
    $areas = $areaRepo->findAll();

    $error = $_SESSION['error'] ?? null;
    $success = $_SESSION['success'] ?? null;
    $old = $_SESSION['old'] ?? [];

    unset($_SESSION['error'], $_SESSION['success'], $_SESSION['old']);

    require APP_PATH . '/Views/admin/evento_form.php';
}


    public function saveEvento(): void
    {
         $user = $this->checkAuth();

        $data = $_POST;
        $data['id'] = (int) ($data['id'] ?? 0);
        $data['tiene_costo'] = isset($data['tiene_costo']) ? 1 : 0;
        $data['precio'] = $data['precio'] ?? 0.00;
        $data['limite_participantes'] = $data['limite_participantes'] ?? null;

        // Manejar actividades (array de actividades)
        $actividades = [];
        if (!empty($data['actividad_nombre'])) {
            for ($i = 0; $i < count($data['actividad_nombre']); $i++) {
                if (!empty($data['actividad_nombre'][$i])) {
                    $actividades[] = [
                        'nombre' => $data['actividad_nombre'][$i],
                        'descripcion' => $data['actividad_desc'][$i] ?? '',
                        'hora_inicio' => $data['actividad_inicio'][$i] ?? null,
                        'hora_fin' => $data['actividad_fin'][$i] ?? null,
                    ];
                }
            }
        }
        $data['actividades'] = $actividades;

        if ($data['id'] > 0) {
            $result = $this->eventoService->update($data['id'], $data);
        } else {
            $result = $this->eventoService->create($data);
        }

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            header('Location: index.php?r=admin/eventos');
        } else {
            $_SESSION['error'] = $result['message'];
            $_SESSION['old'] = $data;
            header('Location: index.php?r=admin/eventos/crear' . ($data['id'] > 0 ? '&id=' . $data['id'] : ''));
        }
        exit;
    }

   public function deleteEvento(): void
{
         $user = $this->checkAuth();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?r=admin/eventos');
        exit;
    }

    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        $_SESSION['error'] = 'ID de evento inválido';
        header('Location: index.php?r=admin/eventos');
        exit;
    }

    $result = $this->eventoService->delete($id);

    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
    } else {
        $_SESSION['error'] = $result['message'];
    }

    header('Location: index.php?r=admin/eventos');
    exit;
}

    // ════════════════════════════════════════════════════════════
    // GESTIÓN DE ASIGNACIONES DE GUÍAS (HU-07)
    // ════════════════════════════════════════════════════════════

    public function asignaciones(): void
    {
        $this->checkAuth();
        $guiaRepo = new \App\Repositories\GuiaRepository();
        $asignaciones = $guiaRepo->getAllAsignaciones();
        
        $mensaje = $_SESSION['flash_mensaje'] ?? null;
        $tipoMsg = $_SESSION['flash_tipo'] ?? 'ok';
        unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);
        
        require APP_PATH . '/Views/admin/asignaciones.php';
    }

    public function crearAsignacion(): void
    {
        $this->checkAuth();
        $guiaRepo = new \App\Repositories\GuiaRepository();
        
        $guias = $guiaRepo->getGuiasDisponibles();
        $recorridos = $this->recorridoRepo->findByTipo('Guiado');
        
        $mensaje = $_SESSION['flash_mensaje'] ?? null;
        $tipoMsg = $_SESSION['flash_tipo'] ?? 'error';
        $old     = $_SESSION['form_datos'] ?? [];
        
        unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo'], $_SESSION['form_datos']);

        require APP_PATH . '/Views/admin/asignacion_form.php';
    }

    public function guardarAsignacion(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?r=admin/asignaciones');
            exit;
        }

        $id_guia = (int)($_POST['id_guia'] ?? 0);
        $id_recorrido = (int)($_POST['id_recorrido'] ?? 0);
        $fecha = trim($_POST['fecha'] ?? '');
        $hora = trim($_POST['hora'] ?? '');

        // Guardar para persistencia en caso de error
        $_SESSION['form_datos'] = $_POST;

        $guiaRepo = new \App\Repositories\GuiaRepository();
        $recorrido = $this->recorridoRepo->findById($id_recorrido);
        $guia = $guiaRepo->findGuiaById($id_guia);

        if (!$recorrido || !$guia || empty($fecha) || empty($hora)) {
            $_SESSION['flash_mensaje'] = 'Faltan datos obligatorios.';
            $_SESSION['flash_tipo'] = 'error';
            header('Location: index.php?r=admin/asignaciones/crear');
            exit;
        }

        // 0. Validar que la fecha no sea en el pasado
        $hoy = date('Y-m-d');
        if ($fecha < $hoy) {
            $_SESSION['flash_mensaje'] = "No se pueden realizar asignaciones a fechas pasadas.";
            $_SESSION['flash_tipo'] = 'error';
            header('Location: index.php?r=admin/asignaciones/crear');
            exit;
        }

        // 1. Validar según horarios de atención
        $diasMap = [
            'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'
        ];
        $timestamp = strtotime($fecha);
        if ($timestamp === false) {
             $_SESSION['flash_mensaje'] = "Fecha inválida.";
             $_SESSION['flash_tipo'] = 'error';
             header('Location: index.php?r=admin/asignaciones/crear');
             exit;
        }
        $diaNombre = $diasMap[date('l', $timestamp)];
        $guiaDias = $guia['dias_trabajo'] ?? '';
        $trabajaEsteDia = false;

        if ($guiaDias === 'Todos los días' || $guiaDias === 'Todos los dias') {
            $trabajaEsteDia = true;
        } elseif (strpos($guiaDias, $diaNombre) !== false) {
            $trabajaEsteDia = true;
        } elseif (preg_match('/(\w+)\s+a\s+(\w+)/i', $guiaDias, $rangeMatches)) {
            $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $inicioIdx = array_search($rangeMatches[1], $diasSemana);
            $finIdx = array_search($rangeMatches[2], $diasSemana);
            $actualIdx = array_search($diaNombre, $diasSemana);
            
            if ($inicioIdx !== false && $finIdx !== false && $actualIdx !== false) {
                 if ($inicioIdx <= $finIdx) {
                     $trabajaEsteDia = ($actualIdx >= $inicioIdx && $actualIdx <= $finIdx);
                 } else { // Caso circular (ej: Sábado a Martes)
                     $trabajaEsteDia = ($actualIdx >= $inicioIdx || $actualIdx <= $finIdx);
                 }
            }
        }

        if (!$trabajaEsteDia) {
            $_SESSION['flash_mensaje'] = "No se pudo guardar: El guía no trabaja los días $diaNombre (Su disponibilidad: $guiaDias).";
            $_SESSION['flash_tipo'] = 'error';
            header('Location: index.php?r=admin/asignaciones/crear');
            exit;
        }

        // Rango horario y Duración del Recorrido
        if (preg_match('/(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})/', $guia['horarios'], $matches)) {
            $hEntrada = date("H:i", strtotime($matches[1]));
            $hSalida  = date("H:i", strtotime($matches[2]));
            
            $horaInicioSec = strtotime($hora);
            $horaFinSec    = $horaInicioSec + ((int)$recorrido['duracion'] * 60);
            
            $horaFormatted    = date("H:i", $horaInicioSec);
            $horaFinFormatted = date("H:i", $horaFinSec);
            
            if ($horaFormatted < $hEntrada) {
                $_SESSION['flash_mensaje'] = "No se pudo guardar: La hora de inicio ($horaFormatted) es antes de la entrada del guía ($hEntrada).";
                $_SESSION['flash_tipo'] = 'error';
                header('Location: index.php?r=admin/asignaciones/crear');
                exit;
            }

            if ($horaFinFormatted > $hSalida) {
                $_SESSION['flash_mensaje'] = "No se pudo guardar: El recorrido termina a las $horaFinFormatted, pero el guía sale a las $hSalida.";
                $_SESSION['flash_tipo'] = 'error';
                header('Location: index.php?r=admin/asignaciones/crear');
                exit;
            }
        } else {
            // Si el formato no es el esperado, al menos validamos algo básico o dejamos pasar con advertencia en log
            // Pero para el usuario, si está vacío o mal, podríamos avisar
            if (empty($guia['horarios'])) {
                $_SESSION['flash_mensaje'] = "No se pudo guardar: El guía seleccionado no tiene un horario configurado.";
                $_SESSION['flash_tipo'] = 'error';
                header('Location: index.php?r=admin/asignaciones/crear');
                exit;
            }
        }

        // 2. Verificar disponibilidad (traslapes)
        if ($guiaRepo->existsAsignacion($id_guia, $fecha, $hora, (int)$recorrido['duracion'])) {
            $_SESSION['flash_mensaje'] = 'El guía ya tiene otra asignación que se traslapa con estos horarios.';
            $_SESSION['flash_tipo'] = 'error';
            header('Location: index.php?r=admin/asignaciones/crear');
            exit;
        }

        // 3. Registrar asignación
        try {
            $ok = $guiaRepo->asignarGuia($id_guia, $id_recorrido, $fecha, $hora);
            
            if ($ok) {
                unset($_SESSION['form_datos']);
                $_SESSION['flash_mensaje'] = 'Asignación realizada correctamente.';
                $_SESSION['flash_tipo'] = 'ok';
                header('Location: index.php?r=admin/asignaciones');
            } else {
                throw new \Exception("No se pudo completar el registro en la base de datos.");
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == '42S22') {
                $_SESSION['flash_mensaje'] = "Error: Faltan columnas en la base de datos. Por favor, ejecuta el script update_db.sql.";
            } elseif ($e->getCode() == '23000') {
                $_SESSION['flash_mensaje'] = "Este guía ya tiene asignado este recorrido (Restricción de duplicados).";
            } else {
                $_SESSION['flash_mensaje'] = "Error de base de datos: " . $e->getMessage();
            }
            $_SESSION['flash_tipo'] = 'error';
            header('Location: index.php?r=admin/asignaciones/crear');
        } catch (\Exception $e) {
            $_SESSION['flash_mensaje'] = $e->getMessage();
            $_SESSION['flash_tipo'] = 'error';
            header('Location: index.php?r=admin/asignaciones/crear');
        }
        exit;
    }

    public function eliminarAsignacion(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $ok = (new \App\Repositories\GuiaRepository())->deleteAsignacion($id);
            $_SESSION['flash_mensaje'] = $ok ? 'Asignación eliminada correctamente.' : 'Error al eliminar.';
            $_SESSION['flash_tipo'] = $ok ? 'ok' : 'error';
        }
        header('Location: index.php?r=admin/asignaciones');
        exit;
    }
    
// reportes para el admin
public function reportes(): void
{
    
    $this->checkAuth();

    $db   = \Core\Database::getInstance();
    $conn = $db->getConnection();
   if (isset($_GET['inicio']) && $_GET['inicio'] !== '') {
        $inicio = $_GET['inicio'];
    } else {
        $minReservas = $conn->query("SELECT MIN(fecha) FROM reservas")->fetchColumn();
        $minCompras  = $conn->query("SELECT MIN(fecha) FROM compras")->fetchColumn();
        $inicio = min(array_filter([$minReservas, $minCompras])) ?: date('Y-01-01');
    }

    if (isset($_GET['fin']) && $_GET['fin'] !== '') {
        $fin = $_GET['fin'];
    } else {
        $fin = date('Y-m-d');
    }

    $reservas     = [];
    $compras      = [];
    $reportesGuias = [];
    $errores      = [];

    // ── RESERVAS ─────────────────────────────────────────────────
    try {
        $stmt = $conn->prepare("
            SELECT
                fecha,
                COUNT(*)                                          AS total_reservas,
                COALESCE(SUM(cupos), 0)                          AS total_cupos,
                SUM(CASE WHEN estado_pago = 1 THEN 1 ELSE 0 END) AS pagadas,
                SUM(CASE WHEN estado_pago = 0 THEN 1 ELSE 0 END) AS pendientes
            FROM reservas
            WHERE estado = 1
              AND fecha BETWEEN :inicio AND :fin
            GROUP BY fecha
            ORDER BY fecha
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        $reservas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        $errores[] = 'Error al cargar reservas: ' . $e->getMessage();
    }

    // ── COMPRAS ──────────────────────────────────────────────────
    try {
        $stmt = $conn->prepare("
            SELECT
                fecha,
                COUNT(*)                                                   AS total_compras,
                COALESCE(SUM(monto), 0)                                    AS total_ingresos,
                COALESCE(SUM(CASE WHEN estado_pago = 1 THEN monto ELSE 0 END), 0) AS pagadas,
                COALESCE(SUM(CASE WHEN estado_pago = 0 THEN monto ELSE 0 END), 0) AS pendientes
            FROM compras
            WHERE estado = 1
              AND fecha BETWEEN :inicio AND :fin
            GROUP BY fecha
            ORDER BY fecha
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        $compras = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        $errores[] = 'Error al cargar compras: ' . $e->getMessage();
    }

    // ── REPORTES DE GUÍAS ────────────────────────────────────────
    try {
        $stmt = $conn->prepare("
            SELECT
                DATE(r.fecha_reporte)        AS fecha_reporte,
                gr.id_guia_recorrido,
                r.observaciones,
                r.estado
            FROM reportes r
            INNER JOIN guia_recorrido gr
                ON r.id_guia_recorrido = gr.id_guia_recorrido
            WHERE DATE(r.fecha_reporte) BETWEEN :inicio AND :fin
            ORDER BY r.fecha_reporte
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        $reportesGuias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        $errores[] = 'Error al cargar reportes de guías: ' . $e->getMessage();
    }

    require APP_PATH . '/Views/admin/reportes.php';
    
}

public function reportePDF(): void
{
    $this->checkAuth();

    $tipo = $_GET['tipo'] ?? '';

    $db   = \Core\Database::getInstance();
    $conn = $db->getConnection();

    $minReservas = $conn->query("SELECT MIN(fecha) FROM reservas")->fetchColumn();
    $minCompras  = $conn->query("SELECT MIN(fecha) FROM compras")->fetchColumn();
    $minFecha    = min(array_filter([$minReservas, $minCompras]));

    $inicio = $_GET['inicio'] ?? $minFecha;
    $fin    = $_GET['fin']    ?? date('Y-m-d');

    $titulo = '';
    $datos  = [];

    if ($tipo === 'reservas') {
        $titulo = 'Reporte de Reservas';
        $stmt = $conn->prepare("
            SELECT fecha, COUNT(*) AS total_reservas, COALESCE(SUM(cupos), 0) AS total_cupos
            FROM reservas
            WHERE estado = 1 AND fecha BETWEEN :inicio AND :fin
            GROUP BY fecha ORDER BY fecha
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        $datos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    } elseif ($tipo === 'compras') {
        $titulo = 'Reporte de Compras';
        $stmt = $conn->prepare("
            SELECT fecha, COUNT(*) AS total_compras, COALESCE(SUM(monto), 0) AS total_ingresos
            FROM compras
            WHERE estado = 1 AND fecha BETWEEN :inicio AND :fin
            GROUP BY fecha ORDER BY fecha
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        $datos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    } elseif ($tipo === 'guias') {
        $titulo = 'Reporte de Guías';
        $stmt = $conn->prepare("
            SELECT DATE(r.fecha_reporte) AS fecha_reporte, r.observaciones
            FROM reportes r
            WHERE DATE(r.fecha_reporte) BETWEEN :inicio AND :fin
            ORDER BY r.fecha_reporte
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        $datos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    ob_start();
    require APP_PATH . '/Views/admin/reportespdf.php';
    $html = ob_get_clean();

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("reporte_{$tipo}.pdf", ["Attachment" => true]);
}
}
