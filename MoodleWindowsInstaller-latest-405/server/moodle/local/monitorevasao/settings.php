<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('local_monitorevasao', get_string('pluginname', 'local_monitorevasao')));
    
    $ADMIN->add('local_monitorevasao', new admin_externalpage(
        'local_monitorevasao_dashboard',
        get_string('dashboard', 'local_monitorevasao'),
        new moodle_url('/local/monitorevasao/index.php')
    ));
} 