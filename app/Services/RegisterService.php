<?php
// app/Services/RegisterService.php
declare(strict_types=1);

namespace App\Services;

use App\Models\Cliente;
use App\Repositories\UsuarioRepository;
use Exception;

class RegisterService
{
    private UsuarioRepository $repo;

    public function __construct()
    {
        $this->repo = new UsuarioRepository();
    }

    public function register(array $data): array
    {
        try {
            $this->validar($data);

            // Verificar si nombre_usuario ya existe
            if ($this->repo->nombreUsuarioEnUso($data['nombre_usuario'], 0)) {
                throw new Exception("El nombre de usuario ya está en uso.");
            }
            if ($this->repo->correoEnUso($data['correo'], 0)) {
                throw new Exception("El correo ya está registrado.");
            }

            $usuario = $this->repo->create($data);

            // Crear entrada en clientes
            Cliente::create([
                'id_usuario'  => $usuario->id_usuario,
                'nit'         => $data['nit']         ?? '0000000',
                'tipo_cuenta' => $data['tipo_cuenta'] ?? 'Personal',
            ]);

            return ['success' => true, 'message' => 'Registro exitoso. Ahora puedes iniciar sesión.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function validar(array $data): void
    {
        if (empty($data['nombre1']) || empty($data['apellido1'])) {
            throw new Exception("Nombre y apellido son obligatorios.");
        }
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Correo electrónico inválido.");
        }
        if (strlen($data['password']) < 6) {
            throw new Exception("La contraseña debe tener al menos 6 caracteres.");
        }
        if ($data['password'] !== ($data['password_confirm'] ?? '')) {
            throw new Exception("Las contraseñas no coinciden.");
        }
        if (empty($data['nombre_usuario'])) {
            throw new Exception("El nombre de usuario es obligatorio.");
        }
    }
}