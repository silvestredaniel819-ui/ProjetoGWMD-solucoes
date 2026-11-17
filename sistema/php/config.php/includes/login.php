<?php
require __DIR__ . '/includes/config.php';

if (!empty($_SESSION['id_usuario'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(campo('email'));
    $senha = campo('senha');

    if (!csrf_valido()) {
        $erro = 'Sessão expirada. Recarregue a página e tente de novo.';
    } else {
        $stmt = $pdo->prepare('SELECT id_usuario, senha_hash FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = $usuario['id_usuario'];

            if (!empty($_POST['lembrar'])) {
                $p = session_get_cookie_params();
                setcookie(session_name(), session_id(), [
                    'expires' => time() + 2592000,
                    'path' => $p['path'],
                    'domain' => $p['domain'],
                    'secure' => $p['secure'],
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);
            }

            header('Location: dashboard.php');
            exit;
        }

        $erro = 'E-mail ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Cadastro - GWMD Soluções</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <div class="container">

            <div class="logo">
                <strong>GWMD</strong>
                <span>Soluções</span>
            </div>
            <nav>

                <ul>
                    <li><a href="../index.html">Início</a></li>
                    <li><a href="../sobre.html">Sobre</a></li>
                    <li><a href="../produtos.html">Produtos</a></li>
                    <li><a href="../contatos.html">Contatos</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <main class="area-login">
        <div class="container-pagina">
            <section class="card-login">
                <h2>Acesso ao Sistema</h2>
                <p>Digite suas credenciais para acessar o painel de dados.</p>

                <?php if (isset($_GET['cadastro'])): ?>
                    <p class="mensagem sucesso">Cadastro realizado. Entre com seu e-mail e senha.</p>
                <?php endif; ?>
                <?php if (isset($_GET['saiu'])): ?>
                    <p class="mensagem sucesso">Você saiu do sistema.</p>
                <?php endif; ?>
                <?php if ($erro): ?>
                    <p class="mensagem erro"><?= esc($erro) ?></p>
                <?php endif; ?>

                <form action="login.php" method="post">
                    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

                    <!-- campo do e-mail/senha e tals -->
                    <div class="campo">
                        <label for="email">E-mail corporativo:</label>
                        <input type="email" id="email" name="email" value="<?= esc($email) ?>" placeholder="seu.nome@empresa.com" required>
                    </div>
                    <div class="campo">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>
                    <div class="opcoes-login">
                        <label>
                            <input type="checkbox" name="lembrar"> Lembrar meu acesso
                        </label>
                        <a href="../contatos.html">Esqueceu a senha?</a>
                    </div>

                    <!-- botão de confirmação -->
                    <button type="submit">Entrar no Painel</button>
                </form>

                <p class="link-alternativo">Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
            </section>
        </div>
    </main>

    <!-- rodapé do site -->
    <footer>
        <div class="container">
            <p><strong>GWMD Soluções</strong> — Gestão • Web • Machine Learning • Meta Data</p>
            <p>(81) 6767-1501 | contato@gwmd.com.br | Recife — PE</p>
            <p>&copy; 2026 GWMD Soluções</p>
        </div>
    </footer>
</body>
</html>