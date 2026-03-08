<?php
// app/Http/Controllers/Web/HomeController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Repositories\RecorridoRepository;

class HomeController extends Controller
{
    private AuthService $auth;
    private RecorridoRepository $recorridoRepo;

    public function __construct()
    {
        $this->auth          = new AuthService();
        $this->recorridoRepo = new RecorridoRepository();
    }

    public function index()
    {
        $isLoggedIn = $this->auth->check();
        $user       = $isLoggedIn ? $this->auth->user() : null;
        $esCliente  = $isLoggedIn && $user?->esCliente();
        $esGuia     = $isLoggedIn && $user?->esGuia();
        $recorridos = $this->recorridoRepo->findAll();

        return view('home', compact('isLoggedIn', 'user', 'esCliente', 'esGuia', 'recorridos'));
    }
}