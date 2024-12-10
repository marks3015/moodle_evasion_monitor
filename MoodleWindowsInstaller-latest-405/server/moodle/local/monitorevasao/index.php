<?php
require_once('../../config.php');
require_login();

// Verifica permissões
$context = context_system::instance();
require_capability('local/monitorevasao:view', $context);

// Configuração da página
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/monitorevasao/index.php'));
$PAGE->set_title(get_string('pluginname', 'local_monitorevasao'));
$PAGE->set_heading(get_string('monitorevasao', 'local_monitorevasao'));

// Obtém dados
$observer = new \local_monitorevasao\observer();
$usuarios_inativos = $observer->verificar_ultimo_acesso();

echo $OUTPUT->header();
?>

<div class="monitorevasao-dashboard">
    <h3>Monitoramento de Evasão</h3>
    
    <!-- Resumo -->
    <div class="dashboard-summary">
        <div class="card">
            <h4>Total de Usuários Inativos</h4>
            <div class="number"><?php echo count($usuarios_inativos); ?></div>
        </div>
    </div>

    <!-- Tabela de usuários -->
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Último Acesso</th>
                <th>Dias Inativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios_inativos as $usuario): ?>
                <tr>
                    <td><?php echo fullname($usuario); ?></td>
                    <td><?php echo userdate($usuario->lastaccess); ?></td>
                    <td><?php echo floor((time() - $usuario->lastaccess) / (24*60*60)); ?></td>
                    <td>
                        <a href="<?php echo new moodle_url('/user/profile.php', ['id' => $usuario->id]); ?>" 
                           class="btn btn-secondary">Ver Perfil</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?php echo new moodle_url('/local/monitorevasao/export.php', ['dias' => $dias]); ?>" 
       class="btn btn-success mb-3">
        Exportar Dados
    </a>
</div>

<?php
echo $OUTPUT->footer(); 