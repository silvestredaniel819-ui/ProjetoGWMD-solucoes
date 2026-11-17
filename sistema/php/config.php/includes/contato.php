<?php
require __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contatos.html');
    exit;
}

if (!empty($_POST['site'])) {
    header('Location: ../contatos.html?status=ok');
    exit;
}

$assuntos = ['analise', 'automacao', 'machine-learning', 'treinamento', 'sistema', 'outro'];

$nome = trim(campo('nome'));
$email = trim(campo('email'));
$telefone = preg_replace('/\D/', '', campo('telefone'));
$assunto = campo('assunto');
$mensagem = trim(campo('mensagem'));

$valido = $nome !== '' && mb_strlen($nome) <= 100
    && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 100
    && in_array(strlen($telefone), [10, 11], true)
    && in_array($assunto, $assuntos, true)
    && $mensagem !== '' && mb_strlen($mensagem) <= 2000;

if (!$valido) {
    header('Location: ../contatos.html?status=invalido');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO mensagens_contato (nome, email, telefone, assunto, mensagem) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$nome, $email, $telefone, $assunto, $mensagem]);
    header('Location: ../contatos.html?status=ok');
} catch (PDOException $e) {
    header('Location: ../contatos.html?status=erro');
}
exit;