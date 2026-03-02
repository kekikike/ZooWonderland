<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\ReservaService;
use App\Repositories\ReservaRepository;

class ReservaController
{
    private AuthService $auth;
    private ReservaService $service;
    private ReservaRepository $repo;

    public function __construct()
    {
        $this->auth    = new AuthService();
        $this->service = new ReservaService();
        $this->repo    = new ReservaRepository();
    }

    public function showForm(): void
    {
        if (!$this->auth->check()) {
            header('Location: index.php?r=login');
            exit;
        }

        $usuario           = $this->auth->user();
        $recorridosGuiados = $this->service->obtenerRecorridosGuiados();
        $fechaMin          = date('Y-m-d', strtotime('+3 days'));

        $selectedRecorridoId = (int)($_GET['recorrido'] ?? 0);

        $form = [
            'recorrido_id'     => $selectedRecorridoId,
            'institucion'      => '',
            'tipo_institucion' => 'colegio',
            'contacto_nombre'  => '',
            'contacto_telefono'=> '',
            'contacto_email'   => '',
            'numero_personas'  => 10,
            'fecha'            => '',
            'hora'             => '',
            'observaciones'    => ''
        ];
        $errores = [];
        $mensaje = '';

        require APP_PATH . '/views/reservas/reservar.php';
    }

    public function processForm(): void
    {
        if (!$this->auth->check()) {
            header('Location: index.php?r=login');
            exit;
        }

        $form = [
            'recorrido_id'     => (int)($_POST['recorrido_id'] ?? 0),
            'institucion'      => trim($_POST['institucion'] ?? ''),
            'tipo_institucion' => trim($_POST['tipo_institucion'] ?? ''),
            'contacto_nombre'  => trim($_POST['contacto_nombre'] ?? ''),
            'contacto_telefono'=> trim($_POST['contacto_telefono'] ?? ''),
            'contacto_email'   => trim($_POST['contacto_email'] ?? ''),
            'numero_personas'  => (int)($_POST['numero_personas'] ?? 0),
            'fecha'            => trim($_POST['fecha'] ?? ''),
            'hora'             => trim($_POST['hora'] ?? ''),
            'observaciones'    => trim($_POST['observaciones'] ?? '')
        ];

        $validacion = $this->service->validarReserva(
            $form['recorrido_id'], $form['institucion'], $form['tipo_institucion'],
            $form['contacto_nombre'], $form['contacto_telefono'], $form['contacto_email'],
            $form['numero_personas'], $form['fecha'], $form['hora'], $form['observaciones']
        );

        if (!$validacion['valido']) {
            $errores           = $validacion['errores'];
            $mensaje           = $validacion['mensaje'];
            $usuario           = $this->auth->user();
            $recorridosGuiados = $this->service->obtenerRecorridosGuiados();
            $fechaMin          = date('Y-m-d', strtotime('+3 days'));
            require APP_PATH . '/views/reservas/reservar.php';
        } else {
            $resultado = $this->service->procesarReserva(
                $form['recorrido_id'], $form['institucion'], $form['tipo_institucion'],
                $form['contacto_nombre'], $form['contacto_telefono'], $form['contacto_email'],
                $form['numero_personas'], $form['fecha'], $form['hora'], $form['observaciones']
            );
            if ($resultado) {
                header('Location: index.php?r=reservas/pagoqr');
                exit;
            } else {
                // Error inesperado
                header('Location: index.php?r=reservar');
                exit;
            }
        }
    }

    public function showPagoQR(): void
    {
        if (!$this->auth->check() || !isset($_SESSION['ultima_reserva_id'])) {
            header('Location: index.php');
            exit;
        }

        $reservaId = $_SESSION['ultima_reserva_id'];
        $datos     = $_SESSION['ultima_reserva_datos'];
        $reserva   = $this->repo->findById($reservaId);
        $usuario   = $this->auth->user();

        require APP_PATH . '/views/reservas/pagoqr.php';
    }

    public function showHistorial(): void
    {
        if (!$this->auth->check()) {
            header('Location: index.php?r=login');
            exit;
        }

        $usuario = $this->auth->user();
        $todasLasReservas = $this->repo->findAllWithExtras();

        require APP_PATH . '/views/reservas/historial.php';
    }

    public function downloadPdf(): void
    {
        if (!$this->auth->check()) {
            exit('No autorizado');
        }

        $id = (int)($_GET['id'] ?? 0);
        $reserva = $this->repo->findById($id);
        
        if (!$reserva) {
            exit('Reserva no encontrada');
        }

        $extras = ($_SESSION['zoo_reservas_extras'] ?? [])[$id] ?? null;
        if (!$extras) {
            exit('Datos adicionales no encontrados');
        }

        $pdfContent = $this->service->generarComprobanteReserva($reserva, $extras);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="comprobante_reserva_'.$id.'.pdf"');
        echo $pdfContent;
        exit;
    }
}
