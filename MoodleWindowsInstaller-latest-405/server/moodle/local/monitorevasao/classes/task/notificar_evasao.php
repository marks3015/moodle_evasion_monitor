<?php
namespace local_monitorevasao\task;

class notificar_evasao extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('notificarevasao', 'local_monitorevasao');
    }

    public function execute() {
        $observer = new \local_monitorevasao\observer();
        $usuariosInativos = $observer->verificar_ultimo_acesso();
        
        foreach ($usuariosInativos as $usuario) {
            // Lógica de notificação
            // Pode ser email, notificação no Moodle, etc.
        }
    }
} 