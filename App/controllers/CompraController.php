<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\CompraRepository;

class CompraController
{
    private AuthService $auth;
    private CompraRepository $compraRepo;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->compraRepo = new CompraRepository();
    }

    public function historial(): void
    {
        // 1️⃣ Verificar si el usuario está logueado
        if (!$this->auth->check()) {
            header("Location: index.php?r=login");
            exit;
        }

        // 2️⃣ Obtener el usuario logueado
        $user = $this->auth->user();

        // 3️⃣ Buscar el cliente asociado al usuario
        $cliente = $this->compraRepo->findClienteByUsuario($user->id_usuario);

        // 4️⃣ Obtener las compras solo si existe el cliente
        if ($cliente) {
            $compras = $this->compraRepo->findByCliente($cliente['id_cliente']);
        } else {
            $compras = []; // el usuario no tiene un cliente asociado
        }

        // 5️⃣ Cargar la vista
        require APP_PATH . '/views/compras/historial.php';
    }
}