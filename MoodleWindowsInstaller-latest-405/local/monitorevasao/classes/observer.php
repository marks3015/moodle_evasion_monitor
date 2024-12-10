<?php
namespace local_monitorevasao;

class observer {
    public static function verificar_ultimo_acesso($segundos = 10) {
        global $DB;
        
        // Calcula o tempo limite (10 segundos atrás)
        $tempoLimite = time() - $segundos;
        
        $sql = "SELECT u.id, u.firstname, u.lastname, u.lastaccess, u.email
                FROM {user} u
                WHERE u.deleted = 0 
                AND u.lastaccess < :tempolimite
                AND u.lastaccess > 0
                AND u.id > 1"; // Exclui o usuário admin
                
        $usuarios = $DB->get_records_sql($sql, ['tempolimite' => $tempoLimite]);
        
        return $usuarios;
    }
} 