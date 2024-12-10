<?php
namespace local_monitorevasao\output;

class block extends \block_base {
    public function init() {
        $this->title = get_string('pluginname', 'local_monitorevasao');
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new \stdClass;
        $observer = new \local_monitorevasao\observer();
        $inativos = $observer->verificar_ultimo_acesso();
        
        $this->content->text = '<div class="monitorevasao-block">';
        $this->content->text .= '<p>Usuários Inativos: ' . count($inativos) . '</p>';
        $this->content->text .= '<a href="' . new \moodle_url('/local/monitorevasao/index.php') . 
                               '" class="btn btn-primary">Ver Detalhes</a>';
        $this->content->text .= '</div>';
        
        return $this->content;
    }
} 