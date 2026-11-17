<?php
require __DIR__ . '/includes/config.php';
exigir_login();

$stmt = $pdo->prepare('SELECT nome, email, cpf, telefone FROM usuarios WHERE id_usuario = ?');
$stmt->execute([$_SESSION['id_usuario']]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header('Location: logout.php');
    exit;
}

$primeiroNome = explode(' ', $usuario['nome'])[0];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Interno | GWMD Soluções</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

    <header>
        <div class="container">

            <div class="logo">
                <strong>GWMD</strong>
                <span>Soluções</span>
            </div>
            <nav>
                <!-- links do sistema incluindo o dashboard e opção de sai, pensei nesse "Sair" por conta que eu entrei em um site recentemente que essa opçao era de dificil localização ai eu meio que me senti na obrigação de nao fazer igual o dono daquele site -->
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

    <!-- área principal do painel de controle -->
    <main class="pagina-dashboard">
        <div class="container-pagina">
            <section class="boas-vindas">
                <p class="eyebrow">Painel Administrativo</p>
                <h1>Visão Geral de Operações</h1>
                <p class="subtitle">Bem-vindo de volta, <?= esc($primeiroNome) ?>! Aqui está o resumo atualizado dos seus modelos e pipelines de dados.</p>
            </section>

            <section class="dados-usuario">
                <h2>Dados da conta</h2>
                <dl>
                    <div>
                        <dt>Nome</dt>
                        <dd><?= esc($usuario['nome']) ?></dd>
                    </div>
                    <div>
                        <dt>E-mail</dt>
                        <dd><?= esc(mascarar_email($usuario['email'])) ?></dd>
                    </div>
                    <div>
                        <dt>CPF</dt>
                        <dd><?= esc(mascarar_cpf($usuario['cpf'])) ?></dd>
                    </div>
                    <div>
                        <dt>Telefone</dt>
                        <dd><?= esc(formatar_telefone($usuario['telefone'])) ?></dd>
                    </div>
                </dl>
            </section>

            <!-- indicadores principais do sistema -->
            <section class="metricas-grid">

                <article class="card-metrica">
                    <h3>Modelos em Execução</h3>
                    <p class="valor-metrica">12</p>
                    <p class="status-metrica">Todos rodando sem erros</p>
                </article>

                <article class="card-metrica">
                    <h3>Registros Processados (Hoje)</h3>
                    <p class="valor-metrica">1.450.200</p>
                    <p class="status-metrica">+15% em relação a ontem</p>
                </article>

                <article class="card-metrica">
                    <h3>Alertas de Sistema</h3>
                    <p class="valor-metrica">0</p>
                    <p class="status-metrica">Nenhuma anomalia detectada</p>
                </article>

                <article class="card-metrica">
                    <h3>Uso do Servidor</h3>
                    <p class="valor-metrica">34%</p>
                    <p class="status-metrica">Capacidade estável</p>
                </article>

            </section>

            <!-- tabela com relatórios de dados -->
            <section class="tabela-relatorios">
                <h2>Relatórios Preditivos Recentes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID do Relatório</th>
                            <th>Nome do Modelo</th>
                            <th>Data de Execução</th>
                            <th>Registros Analisados</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#REL-9041</td>
                            <td>Previsão de Demanda Q4</td>
                            <td>03/10/2026</td>
                            <td>450.000</td>
                            <td>Concluído</td>
                            <td><a href="#">Baixar PDF</a></td>
                        </tr>
                        <tr>
                            <td>#REL-9040</td>
                            <td>Análise de Churn Trimestral</td>
                            <td>02/10/2026</td>
                            <td>120.000</td>
                            <td>Concluído</td>
                            <td><a href="#">Baixar PDF</a></td>
                        </tr>
                        <tr>
                            <td>#REL-9039</td>
                            <td>Detecção de Anomalias de Vendas</td>
                            <td>01/10/2026</td>
                            <td>880.200</td>
                            <td>Concluído</td>
                            <td><a href="#">Baixar PDF</a></td>
                        </tr>
                        <tr>
                            <td>#REL-9038</td>
                            <td>Segmentação de Base de Clientes</td>
                            <td>28/09/2026</td>
                            <td>310.000</td>
                            <td>Arquivado</td>
                            <td><a href="#">Visualizar</a></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- ações rápidas do painel -->
            <section class="acoes-dashboard">
                <h2>Ações Rápidas</h2>
                <a href="#">Rodar Novo Pipeline</a>
                <a href="#">Exportar Dados Brutos</a>
                <a href="#">Configurar Alertas</a>
                <a href="perfil.php">Meu Perfil</a>
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
</body>
</html>