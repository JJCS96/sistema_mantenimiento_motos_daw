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

        $correo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($correo === '' || $password === '') {
                $this->setFlash('warning', 'Validacion', 'Complete todos los campos.');
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('warning', 'Validacion', 'Ingrese un correo valido.');
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

                $this->setFlash('error', 'Acceso denegado', 'Correo o contrasena incorrectos.');
            }
        }

        $this->render('auth/login', [
            'title' => 'Iniciar sesion',
            'correo' => $correo,
        ]);
    }

    public function logout(): void
    {
        unset($_SESSION['usuario']);
        $this->setFlash('success', 'Sesion finalizada', 'Sesion cerrada correctamente.');
        $this->redirect('auth', 'login');
    }
}
