<?php
require_once('../../config.php');
require_login();

// Configuração inicial.
$PAGE->set_url(new moodle_url('/local/meuplugin/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Enviar E-mail');
$PAGE->set_heading('Enviar E-mail');

// Formulário básico para envio de e-mail.
echo $OUTPUT->header();
echo '<h2>Envio de E-mail</h2>';
echo '<form method="post">
        <label for="email">E-mail do destinatário:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="assunto">Assunto:</label>
        <input type="text" id="assunto" name="assunto" required>
        <br>
        <label for="mensagem">Mensagem:</label>
        <textarea id="mensagem" name="mensagem" required></textarea>
        <br>
        <button type="submit">Enviar</button>
      </form>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = required_param('email', PARAM_EMAIL);
    $assunto = required_param('assunto', PARAM_TEXT);
    $mensagem = required_param('mensagem', PARAM_TEXT);

    // Usuário remetente (usuário de suporte do Moodle).
    $userfrom = core_user::get_support_user();

    // Destinatário.
    $userto = new stdClass();
    $userto->email = $email;
    $userto->firstname = 'Destinatário';
    $userto->lastname = '';

    // Envio do e-mail.
    $success = email_to_user($userto, $userfrom, $assunto, $mensagem);
    if ($success) {
        echo '<p>' . get_string('emailsent', 'local_meuplugin') . '</p>';
    } else {
        echo '<p>' . get_string('emailerror', 'local_meuplugin') . '</p>';
    }
}

echo $OUTPUT->footer();
