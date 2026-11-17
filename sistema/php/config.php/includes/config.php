<?php
$host = 'localhost';
$banco = 'gwmd_solucoes';
$usuarioBanco = 'root';
$senhaBanco = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuarioBanco,
        $senhaBanco,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados.');
}

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', '2592000');
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

function esc($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function campo($nome)
{
    $valor = $_POST[$nome] ?? '';
    return is_string($valor) ? $valor : '';
}

function csrf_valido()
{
    return !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], campo('csrf'));
}

function exigir_login()
{
    if (empty($_SESSION['id_usuario'])) {
        header('Location: login.php');
        exit;
    }
}

function cpf_valido($cpf)
{
    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }
        $digito = ((10 * $soma) % 11) % 10;
        if ((int) $cpf[$t] !== $digito) {
            return false;
        }
    }
    return true;
}

function mascarar_cpf($cpf)
{
    return '***.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-**';
}

function mascarar_email($email)
{
    [$local, $dominio] = explode('@', $email, 2);
    return substr($local, 0, 2) . str_repeat('*', max(strlen($local) - 2, 3)) . '@' . $dominio;
}

function formatar_telefone($telefone)
{
    if (strlen($telefone) === 11) {
        return sprintf('(%s) %s-%s', substr($telefone, 0, 2), substr($telefone, 2, 5), substr($telefone, 7));
    }
    return sprintf('(%s) %s-%s', substr($telefone, 0, 2), substr($telefone, 2, 4), substr($telefone, 6));
}