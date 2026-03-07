<?php
// app/controllers/Api/AnimalController.php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use Core\Database;

class AnimalController
{
    private \PDO          $db;
    private AuthMiddleware  $auth;
    private AdminMiddleware $admin;

    public function __construct()
    {
        $this->db    = Database::getInstance()->getConnection();
        $this->auth  = new AuthMiddleware();
        $this->admin = new AdminMiddleware();
    }

    // ── GET /api/animales ────────────────────────────────────────
    /** Público. Query params: area, estado, busqueda */
    public function index(array $params = []): void
    {
        $sql    = "SELECT a.*, ar.nombre AS area_nombre FROM animales a
                   LEFT JOIN areas ar ON ar.id_area = a.id_area WHERE 1=1";
        $bind   = [];

        if (!empty($_GET['area'])) {
            $sql .= " AND a.id_area = :area";
            $bind[':area'] = (int)$_GET['area'];
        }
        if (!empty($_GET['estado'])) {
            $sql .= " AND a.estado = :estado";
            $bind[':estado'] = $_GET['estado'];
        }
        if (!empty($_GET['busqueda'])) {
            $like = '%' . $_GET['busqueda'] . '%';
            $sql .= " AND (a.especie LIKE :b1 OR a.nombre_comun LIKE :b2)";
            $bind[':b1'] = $like;
            $bind[':b2'] = $like;
        }

        $sql .= " ORDER BY a.nombre_comun ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($bind);

        Response::ok($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    // ── GET /api/animales/{id} ───────────────────────────────────
    /** Público */
    public function show(array $params): void
    {
        $id   = (int)($params['id'] ?? 0);
        $stmt = $this->db->prepare("
            SELECT a.*, ar.nombre AS area_nombre, ar.restringida
            FROM animales a
            LEFT JOIN areas ar ON ar.id_area = a.id_area
            WHERE a.id_animal = :id
        ");
        $stmt->execute([':id' => $id]);
        $animal = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$animal) Response::notFound("Animal #{$id} no encontrado.");
        Response::ok($animal);
    }

    // ── POST /api/animales ───────────────────────────────────────
    /** Solo admin. Body JSON con los campos del animal. */
    public function store(array $params = []): void
    {
        $authUser = $this->auth->handle();
        $this->admin->handle($authUser);

        $body   = $this->getJson();
        $errors = $this->validate($body);
        if (!empty($errors)) Response::validationError($errors);

        $stmt = $this->db->prepare("
            INSERT INTO animales (especie, nombre_comun, habitat, descripcion, foto, estado, id_area)
            VALUES (:especie, :nombre_comun, :habitat, :descripcion, :foto, :estado, :id_area)
        ");
        $stmt->execute([
            ':especie'     => $body['especie'],
            ':nombre_comun'=> $body['nombre_comun'] ?? null,
            ':habitat'     => $body['habitat']      ?? null,
            ':descripcion' => $body['descripcion']  ?? null,
            ':foto'        => $body['foto']         ?? null,
            ':estado'      => $body['estado']       ?? 'Activo',
            ':id_area'     => (int)$body['id_area'],
        ]);

        $newId = (int)$this->db->lastInsertId();
        $this->show(['id' => $newId]);   // responde con el recurso creado (201 implícito)
    }

    // ── PUT /api/animales/{id} ───────────────────────────────────
    /** Solo admin */
    public function update(array $params): void
    {
        $authUser = $this->auth->handle();
        $this->admin->handle($authUser);

        $id   = (int)($params['id'] ?? 0);
        $body = $this->getJson();
        $errors = $this->validate($body);
        if (!empty($errors)) Response::validationError($errors);

        $stmt = $this->db->prepare("
            UPDATE animales SET
                especie      = :especie,
                nombre_comun = :nombre_comun,
                habitat      = :habitat,
                descripcion  = :descripcion,
                foto         = :foto,
                estado       = :estado,
                id_area      = :id_area
            WHERE id_animal  = :id
        ");
        $stmt->execute([
            ':especie'     => $body['especie'],
            ':nombre_comun'=> $body['nombre_comun'] ?? null,
            ':habitat'     => $body['habitat']      ?? null,
            ':descripcion' => $body['descripcion']  ?? null,
            ':foto'        => $body['foto']         ?? null,
            ':estado'      => $body['estado']       ?? 'Activo',
            ':id_area'     => (int)$body['id_area'],
            ':id'          => $id,
        ]);

        $this->show(['id' => $id]);
    }

    // ── DELETE /api/animales/{id} ────────────────────────────────
    /** Solo admin. Soft delete (estado = 'Inactivo') */
    public function destroy(array $params): void
    {
        $authUser = $this->auth->handle();
        $this->admin->handle($authUser);

        $id   = (int)($params['id'] ?? 0);
        $stmt = $this->db->prepare("UPDATE animales SET estado = 'Inactivo' WHERE id_animal = :id");
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) Response::notFound("Animal #{$id} no encontrado.");
        Response::ok(['id_animal' => $id], 'Animal desactivado.');
    }

    // ── Helpers ──────────────────────────────────────────────────
    private function validate(array $body): array
    {
        $errors = [];
        if (empty($body['especie']))  $errors[] = 'especie es obligatorio.';
        if (empty($body['id_area']))  $errors[] = 'id_area es obligatorio.';
        return $errors;
    }

    private function getJson(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}