<?php
require __DIR__ . '/includes/config.php';
exigir_login();

$erro = '';
$sucesso = '';
$telefoneDigitado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefoneDigitado = preg_replace('/\D/', '', campo('telefone'));

    if (!csrf_valido()) {
        $erro = 'Sessão expirada. Recarregue a página e tente de novo.';
    } elseif (!in_array(strlen($telefoneDigitado), [10, 11], true)) {
        $erro = 'Telefone inválido. Use DDD + número.';
    } else {
        $stmt = $pdo->prepare('UPDATE usuarios SET telefone = ? WHERE id_usuario = ?');
        $stmt->execute([$telefoneDigitado, $_SESSION['id_usuario']]);
        $sucesso = 'Telefone atualizado.';
        $telefoneDigitado = null;
    }
}

$stmt = $pdo->prepare('SELECT nome, telefone FROM usuarios WHERE id_usuario = ?');
$stmt->execute([$_SESSION['id_usuario']]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header('Location: logout.php');
    exit;
}

$telefoneCampo = $telefoneDigitado ?? $usuario['telefone'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - GWMD Soluções</title>
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
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="area-login">
        <div class="container-pagina">
            <section class="card-login">
                <h2>Meu perfil</h2>
                <p><?= esc($usuario['nome']) ?></p>

                <?php if ($sucesso): ?>
                    <p class="mensagem sucesso"><?= esc($sucesso) ?></p>
                <?php endif; ?>
                <?php if ($erro): ?>
                    <p class="mensagem erro"><?= esc($erro) ?></p>
                <?php endif; ?>

                <form action="perfil.php" method="post">
                    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

                    <div class="campo">
                        <label for="telefone">Telefone / WhatsApp:</label>
                        <input type="tel" id="telefone" name="telefone" value="<?= esc($telefoneCampo) ?>" data-mascara="telefone" placeholder="(81) 99999-9999" required>
                    </div>

                    <button type="submit">Salvar telefone</button>
                </form>

                <p class="link-alternativo"><a href="dashboard.php">Voltar ao painel</a></p>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p><strong>GWMD Soluções</strong> — Gestão • Web • Machine Learning • Meta Data</p>
            <p>(81) 6767-1501 | contato@gwmd.com.br | Recife — PE</p>
            <p>&copy; 2026 GWMD Soluções</p>
        </div>
    </footer>
    <script src="assets/js/script.js" defer></script>
</body>
</html>