<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

include_once BASE_DIR.'/incluir/versao.php';

$licenca_tipo = null;
$licenca_contas = null;
$licenca_licenciado = null;
if(file_exists(BASE_DIR.'/incluir/licensa.php')){
    include_once BASE_DIR.'/incluir/licensa.php';
}


echo estiloTopoCaixa();
echo '<table cellspacing="2" cellpadding="4" border=0 class="std" width="100%">';
echo '<tr><td width="100%" valign="top">';
echo '<font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5"><b>gpweb v. '.$_versao_maior.'.'.$_versao_menor.'.'.$_versao_revisao.'</b></font>';
if($licenca_tipo !== null && $licenca_licenciado){
    echo '<br/><br/><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5"><b>Licenciado:&nbsp;';
    echo $licenca_licenciado;
    echo '</b></font>';

    echo '<br/><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5"><b>Licença&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;';
    switch($licenca_tipo){
        case 0:
            echo 'Perpétua';
            break;
        case 1:
            echo 'Consignada';
            break;
        default:
            echo 'Inválida';
            break;
    }
    echo '</b></font>';

    echo '<br/><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5"><b>Nº Contas&nbsp;:&nbsp;';
    if($licenca_contas < 0){
        echo 'Ilimitada';
    }
    else{
        echo $licenca_contas;
    }
    echo '</b></font>';
}
echo '<br />';
echo '<br />Há diversas maneiras de sanar suas dúvidas:<br />';
echo '<li>Visitar a <a href="'.$config['endereco_site'].'" target="_blank">página oficial do '.$config['gpweb'].'</a></li>';
echo '<li>Visitar a comunidade livre do '.$config['gpweb'].' no <a href="http://www.softwarepublico.gov.br" target="_blank">Portal do Software Público</a></li>';
echo '<li>Contatar a empresa Sistema GP-Web Ltda através do e-mail : <a href="mailto:gpweb@sistemagpweb.com" target="_blank">gpweb@sistemagpweb.com</a></li>';
echo '<li>Contatar o desenvolvedor <b>Sérgio Reinert</b> através do e-mail : <a href="mailto:sergioreinert@hotmail.com" target="_blank">sergioreinert@hotmail.com</a></li>';
echo '</ul>';
echo '<p align="LEFT"><font color="#5c8526"><font face="Arial, Helvetica, sans-serif"><font style="font-size: 12pt;" size="5">Principais Funcionalidades do '.$config['gpweb'].'</font></font></font></p>';
echo '<ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><font color="#0066cc"><font style="font-size: 12pt;" size="4"><b>Módulo	de Mensagens Eletrônicas</b></font></font></p>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Ao se passar o mouse em	cima dos títulos das mensagens, na caixa de entrada, <span style="font-style: normal;">o conteúdo da mensagem é visualizado próximo ao ponteiro do mouse, evitando a necessidade de abrir uma a uma as mensagens para lê-las;</span></p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Assinatura eletrônica	das mensagens enviadas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Envio de mensagens criptografadas por chave pública/privada ou por senha;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Níveis de acesso para	mensagens, impedido de pessoas sem o nível de acesso adequado	possam ler mensagens sigilosas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Alerta de leitura;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Selecionar múltiplas mensagens na caixa de entrada para um único despacho, resposta ou encaminhamento para todas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Visualização de toda a tramitação das mensagens, verificando-se horário de leitura,	despachos nas mesmas, assim como múltiplos encaminhamentos;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Criação de grupos de destinatários particulares;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Criação de textos	pré-formatados particulares para despacho e respostas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Criação de pastas	particulares para organizar as mensagens;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Pode-se marcar por cor mensagens, assim como inserir notas (<i>sticky note</i>) para facilitar a organização;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Controle de despachos, verificando quem já respondeu assim como os textos das respostas;</p></li>';
echo '</ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><br></p>';
echo '<ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><font color="#0066cc"><font style="font-size: 12pt;" size="4"><b>Módulo	de Documentos Eletrônicos</b></font></font></p>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Criação no próprio sistema de documentos seguindo diversos modelos tais como parte,	memorando, ofício, etc;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Edição colaborativa	de documentos, visualizando as alterações realizadas por outros usuários;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Interface moderna com	as principais funcionalidades encontradas em aplicados tais como MS	Word;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Todas as principais	funcionalidades elencadas no módulo de mensagens eletrônicas também se aplicam aos documentos eletrônicos criados no sistema;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Completo controle de protocolos com inserção automática de Número Único de	Processo(NUP);</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Interface gráfica para criação com facilidade de novos modelos de documento;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Tramitação dos documentos com opção de aprovação, assinatura eletrônica e	protocolo;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Possibilita anexar documentos criados no sistema em outros documentos assim como em mensagens eletrônicas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Evita a situação comum em repartições de ser necessário fazer download de documentos anexados em E-mail para possíveis correções e ato contínuo anexa-los novamente para envio, ganhando velocidade da criação colaborativa de documentos.</p></li>';
echo '</ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><br></p>';
echo '<ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"></p>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><font color="#0066cc"><font style="font-size: 12pt;" size="4"><b>Módulo de Gerenciamento de Projetos e Práticas de Gestão</b></font></font></p>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Projetos e Práticas	por organizados por Organização e Dept/Seção;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Relatórios completos	dos Projetos/Práticas para diversos sistemas de excelência gerencial;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Custos Planejados e executados, discriminados por Natureza de Despesa (30, 32, 50,	etc.);</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Interface ajustável para o EB, Marinha, Aeronáutica, Órgãos Federais e empresas civis;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Sistema Modular podendo selecionar quais funcionalidades ativar;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Amplas opções de	adaptar as necessidades particulares de cada Organização;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Níveis de acesso aos	projetos, '.$config['tarefas'].', práticas e indicadores configurados, para maior	controle e segurança;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Diversos tipos de relatórios, com a opção de salvar em PDF;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Gráficos Gantt , com	diversas formas de visualização;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Múltiplas opções de inserção e alteração de dados dos projetos e '.$config['tarefas'].' para atender necessidades distintas de interface;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Solução completa de fórum, para cada projeto/tarefa, prática e indicador;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Facilidade em verificar disponibilidade de integrantes para '.$config['tarefas'].';</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Eficiente forma de a chefia/Comando exercer&nbsp; o controle de múltiplos projetos sendo	executados em sua Organização, assim como os indicadores de desempenho e práticas de gestão;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Possibilita verificar pontuação em diversos sistemas de <i>Balanced Score Card,</i><span style="font-style: normal;">, particularmente o da Fundação	Nacional da Qualidade (FNQ) e o do GesPública;</span></p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Facilidade em se anexar documentos a projetos/tarefas, práticas e indicadores;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Avisos automáticos, por E-mail, de  '.$config['tarefas'].' atrasadas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Completa integração com o módulo de mensagens eletrônicas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Moderno calendário	para visualização d'.$config['genero_tarefa'].'s '.$config['tarefas'].' e eventos agendados, assim como	eventos de práticas de gestão e indicadores;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Completo controle de	contatos, individualizados por projeto/tarefa, práticas de gestão	e indicadores;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Ao colocar o mouse sobre quase todos os campos de texto e botões do programa, caixas de aviso oferecerão explicações, o que dispensa a leitura de manuais para poder utilizar o programa;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Solução completa de	gerenciamento dos usuários, podendo dar níveis de acesso individualizados, tanto das funcionalidades do sistema, quanto a projetos e '.$config['tarefas'].' específicos;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Sistema de fácil utilização, se comparado às alternativas existentes hoje no	mercado;</p></li>';
echo '<li><p align="LEFT">Fácil manutenção, por utilizar exclusivamente	PHP.</p></li>';
echo '</ul>';
echo '</td></tr>';


echo '<tr><td><table class="std2"><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5">Sobre a Empresa:</font><br><br>';
echo '<tr><td valign="top"><img src="modulos/ajuda/pt/gpweb_logo_sobre.png"  border="1" align="top" /></td>';
echo '<td width="617" valign="top">
<p>A Sistema GP-Web Ltda. foi fundada em 2008, foi constituída com o intuito de oferecer suporte técnico, customizações, treinamento e transferência tecnológica do software '.$config['gpweb'].', que é o sistema mais completo, para o mercado nacional de gestão estratégica alinhada com gerenciamento de projetos.</p>
<br><p>Como forma de patrocinar a melhoria da gestão no Brasil, disponibilizamos uma versão simplificada do '.$config['gpweb'].' no <a href="https://softwarepublico.gov.br/social/gpweb" target="_blank">Portal do Software Público</a> para toda a sociedade brasileira.</p>
<br><p>Num mercado em que a maioria da concorrência se limita a revender softwares americanos, pouco adaptados à nossa realidade, o '.$config['gpweb'].' foi feito por brasileiros e para brasileiros. Um dos nossos principais diferenciais é a possibilidade de customização do sistema para setores específicos da sociedade (ex: Programa Brasil sem Miséria, Setor Elétrico e Órgãos Militares).</p>
<br><p><strong>Negócio</strong></p>
<p>“Suporte técnico, treinamento, customizações, transferências tecnológicas e geração de empregos regionais, relacionados com o Sistema GP­Web.”</p>
<br><p><strong>Visão de Futuro</strong></p>
<p>“Ser referência nacional em gerenciamento de projetos e gestão estratégica.”</p>
<br><p><strong>Missão</strong></p>
<p>“Oferecer a entidades da administração pública, mista e privada o produto do conhecimento aliado a tecnologia da informação, tendo o cliente como foco, a qualidade do atendimento como principal vocação e o '.$config['gpweb'].' como eficaz ferramenta de comunicação, coordenação, planejamento, controle e gestão.”</p>';
echo '</td></tr></table>';
echo '</td></tr>';


echo '<tr><td><table class="std2"><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5">Sobre o Desenvolvedor Original:</font><br><br>';
echo '<tr><td valign="top"><img src="modulos/ajuda/pt/reinert.jpg"  border="1" align="top" /></td>';
echo '<td width="617" valign="top">Sérgio Reinert desenvolveu a versão inicial do '.$config['gpweb'].' totalmente em PHP.<br><br>Administrator e sócio majoritário da Sistema GP-Web Ltda.<br><br>Abaixo as principais áreas de conhecimento do desenvolvedor:';
echo '<ul><li>Programação (de 1983 até a presente data) em :';
echo '<ul>';
echo '<li>Fortran</li>';
echo '<li>Pascal</li>';
echo '<li>Delphi</li>';
echo '<li>Assembler (micro-controladores)</li>';
echo '<li>C (micro-controladores)</li>';
echo '<li>C++</li>';
echo '<li>Java</li>';
echo '<li>PHP (estruturado e orientado à objetos)</li>';
echo '</ul>';
echo '<li>Criação de conteúdo de ensino à distância</li>';
echo '<li>Modelagem e programação de banco de dados em SQL</li>';
echo '<li>Eletrônica digital com foco em circuitos micro-controlados (PIC)</li>';
echo '</ul>';
echo '</td></tr></table>';
echo '</td></tr>';
echo '</table>';

echo estiloFundoCaixa();
?>