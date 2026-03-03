<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\RecorridoRepository;

class AdminController
{
    private AuthService $auth;
    private RecorridoRepository $recorridoRepo;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->recorridoRepo = new RecorridoRepository();
    }

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

    public function dashboard(): void
    {
        $user = $this->checkAuth();

        // Obtener lista de recorridos para la administración
        $recorridos = $this->recorridoRepo->findAll() ?? [];
        
        // Estadísticas generales
        $totalRecorridos = count($recorridos);
        $db = \Core\Database::getInstance();
        $conn = $db->getConnection();
        
        // Total de áreas
        $areaResult = $conn->query("SELECT COUNT(*) as total FROM areas");
        $totalAreas = $areaResult ? $areaResult->fetch(\PDO::FETCH_ASSOC)['total'] : 0;
        
        // Total de reservas activas
        $reservasResult = $conn->query("SELECT COUNT(*) as total FROM reservas WHERE estado = 1");
        $totalReservas = $reservasResult ? $reservasResult->fetch(\PDO::FETCH_ASSOC)['total'] : 0;
        
        // Ingresos totales
        $ingresosResult = $conn->query("SELECT SUM(monto) as total FROM compras WHERE estado_pago = 1");
        $totalIngresos = $ingresosResult ? ($ingresosResult->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0) : 0;
        
        // Animales
        $animalesResult = $conn->query("SELECT COUNT(*) as total FROM animales");
        $totalAnimales = $animalesResult ? $animalesResult->fetch(\PDO::FETCH_ASSOC)['total'] : 0;
        
        // Guías
        $guiasResult = $conn->query("SELECT COUNT(*) as total FROM guias");
        $totalGuias = $guiasResult ? $guiasResult->fetch(\PDO::FETCH_ASSOC)['total'] : 0;

        require APP_PATH . '/Views/admin/dashboard.php';
    }

    /**
     * Página de gestión de recorridos (CRUD).
     * La información general queda en el dashboard; aquí sólo aparece la tabla
     * con acciones. Se invoca desde la ruta admin/recorridos.
     */
    public function recorridos(): void
    {
        $user = $this->checkAuth();
        $recorridos = $this->recorridoRepo->findAll() ?? [];
        require APP_PATH . '/Views/admin/recorridos.php';
    }
}
