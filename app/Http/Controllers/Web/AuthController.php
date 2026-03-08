<?php
// app/Http/Controllers/Web/AuthController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\RegisterService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $auth;
    private RegisterService $register;

    public function __construct()
    {
        $this->auth     = new AuthService();
        $this->register = new RegisterService();
    }

    public function showLogin()
    {
        if ($this->auth->check()) {
            return $this->redirectSegunRol();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $login    = trim($request->input('login', ''));
        $password = trim($request->input('password', ''));

        if ($login === '' || $password === '') {
            return view('auth.login', ['error' => 'Por favor ingresa tu usuario y contraseña.']);
        }

        $result = $this->auth->attempt($login, $password);

        if (!$result['success']) {
            return view('auth.login', ['error' => $result['message']]);
        }

        return $this->redirectSegunRol();
    }

    public function showRegister()
    {
        if ($this->auth->check()) {
            return $this->redirectSegunRol();
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = [
            'nombre1'          => trim($request->input('nombre1', '')),
            'nombre2'          => trim($request->input('nombre2', '')),
            'apellido1'        => trim($request->input('apellido1', '')),
            'apellido2'        => trim($request->input('apellido2', '')),
            'ci'               => trim($request->input('ci', '')),
            'correo'           => trim($request->input('correo', '')),
            'telefono'         => trim($request->input('telefono', '')),
            'nombre_usuario'   => trim($request->input('nombre_usuario', '')),
            'password'         => trim($request->input('password', '')),
            'password_confirm' => trim($request->input('password_confirm', '')),
        ];

        if (!$data['nombre1'] || !$data['apellido1'] || !$data['correo'] || !$data['nombre_usuario'] || !$data['password']) {
            return view('auth.register', ['error' => 'Todos los campos son obligatorios.']);
        }

        $result = $this->register->register($data);

        if (!$result['success']) {
            return view('auth.register', ['error' => $result['message']]);
        }

        $login = $this->auth->attempt($data['nombre_usuario'], $data['password']);
        if ($login['success']) {
            return $this->redirectSegunRol();
        }

        return redirect('/login')->with('success', 'Cuenta creada. Inicia sesión.');
    }

    public function logout()
    {
        $this->auth->logout();
        return redirect('/');
    }

    private function redirectSegunRol()
    {
        $user = $this->auth->user();

        if (!$user)               return redirect('/login');
        if ($user->esAdministrador()) return redirect('/admin/dashboard');
        if ($user->esGuia())          return redirect('/guias/dashboard');
        return redirect('/');
    }
}