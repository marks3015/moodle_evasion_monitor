<?php
defined('MOODLE_INTERNAL') || die();

function local_monitorevasao_extend_navigation($navigation) {
    global $CFG, $PAGE;
    
    if (has_capability('local/monitorevasao:view', context_system::instance())) {
        $monitorevasaonode = navigation_node::create(
            get_string('pluginname', 'local_monitorevasao'),
            new moodle_url('/local/monitorevasao/index.php'),
            navigation_node::TYPE_CUSTOM,
            null,
            'monitorevasao'
        );
        $navigation->add_node($monitorevasaonode);
    }
} 