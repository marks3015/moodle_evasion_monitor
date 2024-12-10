# Plugin Moodle: Monitoramento de Evasão e Envio de E-mails
Descrição

Este plugin para Moodle monitora a evasão de alunos em cursos, verificando o tempo desde o último login. Se um aluno ultrapassar um período configurável sem acessar a plataforma, o plugin envia um e-mail personalizado para incentivá-lo a retornar ao curso.

Funcionalidades

Monitoramento Automático: Verifica regularmente a última data de login dos alunos.

Envio de E-mails Personalizados: Mensagens de incentivo configuráveis pelo administrador.

Configuração Simples: Parâmetros como período de inatividade e conteúdo do e-mail ajustáveis.

Relatórios: Visualize uma lista de alunos notificados para acompanhamento.

Requisitos

Moodle 4.x ou superior

PHP 7.4 ou superior

Banco de dados compatível com o Moodle (MySQL, PostgreSQL, etc.)

Instalação

Baixe o pacote do plugin.

Acesse o painel de administração do Moodle.

Vá até Site Administration > Plugins > Install Plugins.

Faça upload do arquivo ZIP e conclua a instalação.

Após a instalação, configure o plugin em Site Administration > Plugins > Evasão de Cursos.

Configuração

Acesse Administração do Site > Plugins > Evasão de Cursos > Configurações.

Defina:

Período de Inatividade: Número de dias sem login para acionar a notificação.

Mensagem de E-mail: Personalize o assunto e o corpo da mensagem.

Intervalo de Verificação: Frequência com que o sistema verifica a evasão.

Como Usar

O sistema verifica automaticamente os alunos inativos e envia e-mails com base nas configurações definidas.

Administradores podem consultar um relatório de notificações enviadas.

Personalização

Para personalizações adicionais, modifique os seguintes arquivos:

locallib.php: Lógica principal do plugin.

settings.php: Configurações de administração.

language/pt_br/pluginname.php: Mensagens e textos exibidos no Moodle.

Licença

Este projeto está licenciado sob a Licença GPL v3. Consulte o arquivo LICENSE para mais informações.

Contato

Desenvolvedor: [Marcus Flávio]E-mail: [marcusgdasilva@gmail.com]

Obrigado por utilizar o plugin! 



