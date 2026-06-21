<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController extends BaseController
{
    public function login(): void
    {
        if (isset($_SESSION['usuario'])) {
            $this->redirect('dashboard', 'index');
        }

        $error = $_GET['error'] ?? '';
        $correo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($correo === '' || $password === '') {
                $error = 'Complete todos los campos.';
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $error = 'Ingrese un correo valido.';
            } else {
                $usuarioModel = new Usuario();
                $usuario = $usuarioModel->findByCredentials($correo, $password);

                if ($usuario) {
                    $_SESSION['usuario'] = [
                        'id_usuario' => $usuario['id_usuario'],
                        'nombre' => $usuario['nombre'],
                        'correo' => $usuario['correo'],
                        'rol' => $usuario['rol'],
                    ];

                    $this->redirect('dashboard', 'index');
                }

                $error = 'Correo o contrasena incorrectos.';
            }
        }

        $this->render('auth/login', [
            'title' => 'Iniciar sesion',
            'error' => $error,
            'correo' => $correo,
        ]);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}
