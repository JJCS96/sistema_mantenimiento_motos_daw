<?php

class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    protected function redirect(string $controller, string $action = 'index', array $params = []): void
    {
        $query = array_merge([
            'controller' => $controller,
            'action' => $action,
        ], $params);

        header('Location: index.php?' . http_build_query($query));
        exit;
    }

    protected function setFlash(string $type, string $title, string $text): void
    {
        $_SESSION['mensaje'] = [
            'tipo' => $type,
            'titulo' => $title,
            'texto' => $text,
        ];
    }

    protected function requireAuth(): void
    {
        if (!isset($_SESSION['usuario'])) {
            $this->setFlash('warning', 'Acceso restringido', 'Debe iniciar sesion para continuar.');
            $this->redirect('auth', 'login');
        }
    }
}
