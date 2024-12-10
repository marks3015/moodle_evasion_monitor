<?php
require_once('../../config.php');
require_login();

$dias = required_param('dias', PARAM_INT);

// Configura cabeçalhos para download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=usuarios_inativos.csv');

// Abre output
$output = fopen('php://output', 'w');

// Cabeçalho do CSV
fputcsv($output, array('Nome', 'Email', 'Último Acesso', 'Dias Inativo'));

// Busca dados
$observer = new \local_monitorevasao\observer();
$usuarios = $observer->verificar_ultimo_acesso($dias);

// Gera CSV
foreach ($usuarios as $usuario) {
    $dias_inativo = floor((time() - $usuario->lastaccess) / (24*60*60));
    fputcsv($output, array(
        fullname($usuario),
        $usuario->email,
        userdate($usuario->lastaccess),
        $dias_inativo
    ));
} 