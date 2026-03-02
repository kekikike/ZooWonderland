<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\GuiaRepository;
use App\Repositories\ReporteRepository;

class GuiaController
{
    private AuthService $auth;
    private GuiaRepository $guiaRepo;
    private ReporteRepository $reporteRepo;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->guiaRepo = new GuiaRepository();
        $this->reporteRepo = new ReporteRepository();
    }

    private function checkAuth(): \App\Models\Usuario
    {
        if (!$this->auth->check()) {
            header('Location: index.php?r=login');
            exit;
        }
        $user = $this->auth->user();
        if (!$user->esGuia()) {
            header('Location: index.php');
            exit;
        }
        return $user;
    }

    public function dashboard(): void
    {
        $user = $this->checkAuth();
        $recorridosAsignados = $this->guiaRepo->getRecorridosAsignados($user->id_usuario);
        require APP_PATH . '/Views/guias/dashboard.php';
    }

    public function showReportForm(): void
    {
        $user = $this->checkAuth();
        $id_gr = (int)($_GET['id_gr'] ?? 0);

        if ($id_gr === 0) {
            header('Location: index.php?r=guias/dashboard');
            exit;
        }

        // Verificar que el recorrido ya tenga reporte
        $reporteExistente = $this->reporteRepo->findByGuiaRecorrido($id_gr);
        if ($reporteExistente) {
            // Ya tiene reporte, mostrar historial o aviso
            header('Location: index.php?r=guias/reportes-historial');
            exit;
        }

        require APP_PATH . '/Views/guias/reporte_crear.php';
    }

    public function processReport(): void
    {
        $user = $this->checkAuth();
        $id_gr = (int)($_POST['id_guia_recorrido'] ?? 0);
        $observaciones = trim($_POST['observaciones'] ?? '');

        if ($id_gr === 0 || strlen($observaciones) < 10 || strlen($observaciones) > 500) {
            $mensaje = "Error: El reporte debe tener entre 10 y 500 caracteres.";
            require APP_PATH . '/Views/guias/reporte_crear.php';
            return;
        }

        // Verificar si ya existe
        if ($this->reporteRepo->findByGuiaRecorrido($id_gr)) {
            header('Location: index.php?r=guias/reportes-historial');
            exit;
        }

        if ($this->reporteRepo->save($id_gr, $observaciones)) {
            $_SESSION['mensaje_exito'] = "Reporte guardado correctamente. Ya no puede ser modificado.";
            header('Location: index.php?r=guias/reportes-historial');
            exit;
        } else {
            $mensaje = "Hubo un error al guardar el reporte.";
            require APP_PATH . '/Views/guias/reporte_crear.php';
        }
    }

    public function showReportHistory(): void
    {
        $user = $this->checkAuth();
        $reportes = $this->reporteRepo->getReportesPorGuia($user->id_usuario);
        require APP_PATH . '/Views/guias/reporte_historial.php';
    }
}
