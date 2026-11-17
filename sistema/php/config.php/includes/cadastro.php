<?php
require __DIR__ . '/includes/config.php';

if (!empty($_SESSION['id_usuario'])) {
    header('Location: dashboard.php');
    exit;
}

$erros = [];
$nome = $email = $cpf = $telefone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim(campo('nome'));
    $email = trim(campo('email'));
    $cpf = preg_replace('/\D/', '', campo('cpf'));
    $telefone = preg_replace('/\D/', '', campo('telefone'));
    $senha = campo('senha');
    $confirmar = campo('confirmar_senha');

    if (!csrf_valido()) {
        $erros[] = 'Sessão expirada. Recarregue a página e tente de novo.';
    }
    if ($nome === '' || mb_strlen($nome) > 100) {
        $erros[] = 'Informe seu nome completo (até 100 caracteres).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $erros[] = 'E-mail inválido.';
    }
    if (!cpf_valido($cpf)) {
        $erros[] = 'CPF inválido.';
    }
    if (!in_array(strlen($telefone), [10, 11], true)) {
        $erros[] = 'Telefone inválido. Use DDD + número.';
    }
    if (strlen($senha) < 8) {
        $erros[] = 'A senha precisa ter pelo menos 8 caracteres.';
    }
    if ($senha !== $confirmar) {
        $erros[] = 'As senhas não conferem.';
    }

    if (!$erros) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO usuarios (nome, email, cpf, telefone, senha_hash) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$nome, $email, $cpf, $telefone, password_hash($senha, PASSWORD_DEFAULT)]);
            header('Location: login.php?cadastro=ok');
            exit;
        } catch (PDOException $e) {
            $erros[] = $e->getCode() === '23000'
                ? 'Já existe uma conta com esse e-mail ou CPF.'
                : 'Erro ao salvar o cadastro. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - GWMD Soluções</title>
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
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="area-login">
        <div class="container-pagina">
            <section class="card-login">
                <h2>Criar conta</h2>
                <p>Preencha os dados abaixo para acessar o painel.</p>

                <?php foreach ($erros as $erro): ?>
                    <p class="mensagem erro"><?= esc($erro) ?></p>
                <?php endforeach; ?>

                <form action="cadastro.php" method="post">
                    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

                    <div class="campo">
                        <label for="nome">Nome completo:</label>
                        <input type="text" id="nome" name="nome" value="<?= esc($nome) ?>" maxlength="100" required>
                    </div>
                    <div class="campo">
                        <label for="email">E-mail corporativo:</label>
                        <input type="email" id="email" name="email" value="<?= esc($email) ?>" maxlength="100" placeholder="seu.nome@empresa.com" required>
                    </div>
                    <div class="campo">
                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" value="<?= esc($cpf) ?>" data-mascara="cpf" inputmode="numeric" placeholder="000.000.000-00" required>
                    </div>
                    <div class="campo">
                        <label for="telefone">Telefone / WhatsApp:</label>
                        <input type="tel" id="telefone" name="telefone" value="<?= esc($telefone) ?>" data-mascara="telefone" placeholder="(81) 99999-9999" required>
                    </div>
                    <div class="campo">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" minlength="8" placeholder="Mínimo de 8 caracteres" required>
                    </div>
                    <div class="campo">
                        <label for="confirmar_senha">Confirmar senha:</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="8" placeholder="Repita a senha" required>
                    </div>

                    <button type="submit">Criar conta</button>
                </form>

                <p class="link-alternativo">Já tem conta? <a href="login.php">Entrar</a></p>
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