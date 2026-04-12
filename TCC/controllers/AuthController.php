<?php

require_once __DIR__ . '/../models/dao/UserDAO.php';
require_once __DIR__ . '/SessionManager.php';

/**
 * Controller para gerenciar autenticação (login, logout, register).
 * Compatível com o schema atual: usuario + login + perfil.
 */
class AuthController {
    private $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    /**
     * Renderiza página de login
     */
    public function login(): void {
        $mensagem = "";
        $tipoMensagem = "";
        $redirect = trim((string)($_GET['redirect'] ?? ''));

        if (str_starts_with($redirect, 'index.php')) {
            $_SESSION['redirect_to'] = $redirect;
        }

        if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso') {
            $mensagem = 'Cadastro realizado com sucesso! Faça login.';
            $tipoMensagem = 'sucesso';
        }

        if (isset($_GET['reset']) && $_GET['reset'] === 'sucesso') {
    $mensagem = 'Senha redefinida com sucesso! Faça login.';
    $tipoMensagem = 'sucesso';
}

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!SessionManager::validateCsrfToken($_POST['csrf_token'] ?? null)) {
                $mensagem = 'Sua sessão expirou. Atualize a página e tente novamente.';
                $tipoMensagem = 'erro';
                include __DIR__ . '/../views/auth/login.php';
                return;
            }

            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');

            if (empty($email) || empty($senha)) {
                $mensagem = 'Email e senha são obrigatórios.';
                $tipoMensagem = 'erro';
            } else {
                $usuario = $this->userDAO->login($email, $senha);

                if ($usuario !== null) {
                    SessionManager::setUser($usuario->id, $usuario->nome, $usuario->tipo);

                    $redirect = SessionManager::getRedirectTo();
                    if ($redirect) {
                        header('Location: ' . $redirect);
                    } elseif ($usuario->tipo === 'admin') {
                        header('Location: index.php?page=admin');
                    } else {
                        header('Location: index.php?page=dashboard');
                    }
                    exit;
                }

                $mensagem = 'Email ou senha incorretos.';
                $tipoMensagem = 'erro';
            }
        }

        include __DIR__ . '/../views/auth/login.php';
    }

    public function forgot(): void {
    $mensagem = "";
    $tipoMensagem = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!SessionManager::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $mensagem = 'Sua sessão expirou. Atualize a página e tente novamente.';
            $tipoMensagem = 'erro';
            include __DIR__ . '/../views/auth/forgot.php';
            return;
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            $mensagem = 'Informe o e-mail.';
            $tipoMensagem = 'erro';
        } else {
            $token = bin2hex(random_bytes(32));
            $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

            if ($this->userDAO->saveRecoveryToken($email, $token, $expira)) {
                $mensagem = "Link de recuperação gerado com sucesso:<br>
                <a href='index.php?page=reset&token=$token'>Clique aqui para redefinir sua senha</a>";
                $tipoMensagem = 'sucesso';
            } else {
                $mensagem = 'E-mail não encontrado.';
                $tipoMensagem = 'erro';
            }
        }
    }

    include __DIR__ . '/../views/auth/forgot.php';
}

    /**
     * Renderiza página de cadastro
     */
    public function register(): void {
        $mensagem = '';
        $redirect = trim((string)($_GET['redirect'] ?? ''));

        if (str_starts_with($redirect, 'index.php')) {
            $_SESSION['redirect_to'] = $redirect;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!SessionManager::validateCsrfToken($_POST['csrf_token'] ?? null)) {
                $mensagem = 'Sua sessão expirou. Atualize a página e tente novamente.';
                include __DIR__ . '/../views/auth/register.php';
                return;
            }

            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');

            if (empty($nome) || empty($email) || empty($senha)) {
                $mensagem = 'Nome, email e senha são obrigatórios.';
            } elseif (strlen($senha) < 6) {
                $mensagem = 'Senha deve ter no mínimo 6 caracteres.';
            } else {
                $usuarioId = $this->userDAO->register($nome, $email, $senha);

                if ($usuarioId !== null) {
                    header('Location: index.php?page=login&cadastro=sucesso');
                    exit;
                }

                $mensagem = 'Erro ao cadastrar. Verifique email e tente novamente.';
            }
        }

        include __DIR__ . '/../views/auth/register.php';
    }

    public function reset(): void {
    $mensagem = "";
    $tipoMensagem = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!SessionManager::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $mensagem = 'Sua sessão expirou. Atualize a página e tente novamente.';
            $tipoMensagem = 'erro';
            include __DIR__ . '/../views/auth/reset.php';
            return;
        }

        $token = trim($_POST['token'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        $confirmarSenha = trim($_POST['confirmar_senha'] ?? '');

        if (empty($token) || empty($senha) || empty($confirmarSenha)) {
            $mensagem = 'Preencha todos os campos.';
            $tipoMensagem = 'erro';
        } elseif (strlen($senha) < 6) {
            $mensagem = 'A senha deve ter no mínimo 6 caracteres.';
            $tipoMensagem = 'erro';
        } elseif ($senha !== $confirmarSenha) {
            $mensagem = 'As senhas não coincidem.';
            $tipoMensagem = 'erro';
        } else {
            if ($this->userDAO->resetPassword($token, $senha)) {
                header('Location: index.php?page=login&reset=sucesso');
                exit;
            } else {
                $mensagem = 'Token inválido ou expirado.';
                $tipoMensagem = 'erro';
            }
        }
    }

    include __DIR__ . '/../views/auth/reset.php';
}

    /**
     * Faz logout do usuário
     */
    public function logout(): void {
        SessionManager::destroy();
        header('Location: index.php');
        exit;
    }
}
