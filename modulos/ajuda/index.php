<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

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

    echo '<br/><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5"><b>Licen�a&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;';
    switch($licenca_tipo){
        case 0:
            echo 'Perp�tua';
            break;
        case 1:
            echo 'Consignada';
            break;
        default:
            echo 'Inv�lida';
            break;
    }
    echo '</b></font>';

    echo '<br/><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5"><b>N� Contas&nbsp;:&nbsp;';
    if($licenca_contas < 0){
        echo 'Ilimitada';
    }
    else{
        echo $licenca_contas;
    }
    echo '</b></font>';
}
echo '<br />';
echo '<br />H� diversas maneiras de sanar suas d�vidas:<br />';
echo '<li>Visitar a <a href="'.$config['endereco_site'].'" target="_blank">p�gina oficial do '.$config['gpweb'].'</a></li>';
echo '<li>Visitar a comunidade livre do '.$config['gpweb'].' no <a href="http://www.softwarepublico.gov.br" target="_blank">Portal do Software P�blico</a></li>';
echo '<li>Contatar a empresa Sistema GP-Web Ltda atrav�s do e-mail : <a href="mailto:gpweb@sistemagpweb.com" target="_blank">gpweb@sistemagpweb.com</a></li>';
echo '<li>Contatar o desenvolvedor <b>S�rgio Reinert</b> atrav�s do e-mail : <a href="mailto:sergioreinert@hotmail.com" target="_blank">sergioreinert@hotmail.com</a></li>';
echo '</ul>';
echo '<p align="LEFT"><font color="#5c8526"><font face="Arial, Helvetica, sans-serif"><font style="font-size: 12pt;" size="5">Principais Funcionalidades do '.$config['gpweb'].'</font></font></font></p>';
echo '<ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><font color="#0066cc"><font style="font-size: 12pt;" size="4"><b>M�dulo	de Mensagens Eletr�nicas</b></font></font></p>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Ao se passar o mouse em	cima dos t�tulos das mensagens, na caixa de entrada, <span style="font-style: normal;">o conte�do da mensagem � visualizado pr�ximo ao ponteiro do mouse, evitando a necessidade de abrir uma a uma as mensagens para l�-las;</span></p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Assinatura eletr�nica	das mensagens enviadas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Envio de mensagens criptografadas por chave p�blica/privada ou por senha;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">N�veis de acesso para	mensagens, impedido de pessoas sem o n�vel de acesso adequado	possam ler mensagens sigilosas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Alerta de leitura;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Selecionar m�ltiplas mensagens na caixa de entrada para um �nico despacho, resposta ou encaminhamento para todas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Visualiza��o de toda a tramita��o das mensagens, verificando-se hor�rio de leitura,	despachos nas mesmas, assim como m�ltiplos encaminhamentos;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Cria��o de grupos de destinat�rios particulares;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Cria��o de textos	pr�-formatados particulares para despacho e respostas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Cria��o de pastas	particulares para organizar as mensagens;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Pode-se marcar por cor mensagens, assim como inserir notas (<i>sticky note</i>) para facilitar a organiza��o;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Controle de despachos, verificando quem j� respondeu assim como os textos das respostas;</p></li>';
echo '</ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><br></p>';
echo '<ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><font color="#0066cc"><font style="font-size: 12pt;" size="4"><b>M�dulo	de Documentos Eletr�nicos</b></font></font></p>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Cria��o no pr�prio sistema de documentos seguindo diversos modelos tais como parte,	memorando, of�cio, etc;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Edi��o colaborativa	de documentos, visualizando as altera��es realizadas por outros usu�rios;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Interface moderna com	as principais funcionalidades encontradas em aplicados tais como MS	Word;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Todas as principais	funcionalidades elencadas no m�dulo de mensagens eletr�nicas tamb�m se aplicam aos documentos eletr�nicos criados no sistema;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Completo controle de protocolos com inser��o autom�tica de N�mero �nico de	Processo(NUP);</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Interface gr�fica para cria��o com facilidade de novos modelos de documento;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Tramita��o dos documentos com op��o de aprova��o, assinatura eletr�nica e	protocolo;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Possibilita anexar documentos criados no sistema em outros documentos assim como em mensagens eletr�nicas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Evita a situa��o comum em reparti��es de ser necess�rio fazer download de documentos anexados em E-mail para poss�veis corre��es e ato cont�nuo anexa-los novamente para envio, ganhando velocidade da cria��o colaborativa de documentos.</p></li>';
echo '</ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><br></p>';
echo '<ul>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"></p>';
echo '<p style="margin-bottom: 0cm;" align="LEFT"><font color="#0066cc"><font style="font-size: 12pt;" size="4"><b>M�dulo de Gerenciamento de Projetos e Pr�ticas de Gest�o</b></font></font></p>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Projetos e Pr�ticas	por organizados por Organiza��o e Dept/Se��o;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Relat�rios completos	dos Projetos/Pr�ticas para diversos sistemas de excel�ncia gerencial;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Custos Planejados e executados, discriminados por Natureza de Despesa (30, 32, 50,	etc.);</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Interface ajust�vel para o EB, Marinha, Aeron�utica, �rg�os Federais e empresas civis;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Sistema Modular podendo selecionar quais funcionalidades ativar;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Amplas op��es de	adaptar as necessidades particulares de cada Organiza��o;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">N�veis de acesso aos	projetos, '.$config['tarefas'].', pr�ticas e indicadores configurados, para maior	controle e seguran�a;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Diversos tipos de relat�rios, com a op��o de salvar em PDF;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Gr�ficos Gantt , com	diversas formas de visualiza��o;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">M�ltiplas op��es de inser��o e altera��o de dados dos projetos e '.$config['tarefas'].' para atender necessidades distintas de interface;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Solu��o completa de f�rum, para cada projeto/tarefa, pr�tica e indicador;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Facilidade em verificar disponibilidade de integrantes para '.$config['tarefas'].';</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Eficiente forma de a chefia/Comando exercer&nbsp; o controle de m�ltiplos projetos sendo	executados em sua Organiza��o, assim como os indicadores de desempenho e pr�ticas de gest�o;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Possibilita verificar pontua��o em diversos sistemas de <i>Balanced Score Card,</i><span style="font-style: normal;">, particularmente o da Funda��o	Nacional da Qualidade (FNQ) e o do GesP�blica;</span></p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Facilidade em se anexar documentos a projetos/tarefas, pr�ticas e indicadores;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Avisos autom�ticos, por E-mail, de  '.$config['tarefas'].' atrasadas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Completa integra��o com o m�dulo de mensagens eletr�nicas;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Moderno calend�rio	para visualiza��o d'.$config['genero_tarefa'].'s '.$config['tarefas'].' e eventos agendados, assim como	eventos de pr�ticas de gest�o e indicadores;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Completo controle de	contatos, individualizados por projeto/tarefa, pr�ticas de gest�o	e indicadores;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Ao colocar o mouse sobre quase todos os campos de texto e bot�es do programa, caixas de aviso oferecer�o explica��es, o que dispensa a leitura de manuais para poder utilizar o programa;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Solu��o completa de	gerenciamento dos usu�rios, podendo dar n�veis de acesso individualizados, tanto das funcionalidades do sistema, quanto a projetos e '.$config['tarefas'].' espec�ficos;</p></li>';
echo '<li><p style="margin-bottom: 0cm;" align="LEFT">Sistema de f�cil utiliza��o, se comparado �s alternativas existentes hoje no	mercado;</p></li>';
echo '<li><p align="LEFT">F�cil manuten��o, por utilizar exclusivamente	PHP.</p></li>';
echo '</ul>';
echo '</td></tr>';


echo '<tr><td><table class="std2"><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5">Sobre a Empresa:</font><br><br>';
echo '<tr><td valign="top"><img src="modulos/ajuda/pt/gpweb_logo_sobre.png"  border="1" align="top" /></td>';
echo '<td width="617" valign="top">
<p>A Sistema GP-Web Ltda. foi fundada em 2008, foi constitu�da com o intuito de oferecer suporte t�cnico, customiza��es, treinamento e transfer�ncia tecnol�gica do software '.$config['gpweb'].', que � o sistema mais completo, para o mercado nacional de gest�o estrat�gica alinhada com gerenciamento de projetos.</p>
<br><p>Como forma de patrocinar a melhoria da gest�o no Brasil, disponibilizamos uma vers�o simplificada do '.$config['gpweb'].' no <a href="https://softwarepublico.gov.br/social/gpweb" target="_blank">Portal do Software P�blico</a> para toda a sociedade brasileira.</p>
<br><p>Num mercado em que a maioria da concorr�ncia se limita a revender softwares americanos, pouco adaptados � nossa realidade, o '.$config['gpweb'].' foi feito por brasileiros e para brasileiros. Um dos nossos principais diferenciais � a possibilidade de customiza��o do sistema para setores espec�ficos da sociedade (ex: Programa Brasil sem Mis�ria, Setor El�trico e �rg�os Militares).</p>
<br><p><strong>Neg�cio</strong></p>
<p>�Suporte t�cnico, treinamento, customiza��es, transfer�ncias tecnol�gicas e gera��o de empregos regionais, relacionados com o Sistema GP�Web.�</p>
<br><p><strong>Vis�o de Futuro</strong></p>
<p>�Ser refer�ncia nacional em gerenciamento de projetos e gest�o estrat�gica.�</p>
<br><p><strong>Miss�o</strong></p>
<p>�Oferecer a entidades da administra��o p�blica, mista e privada o produto do conhecimento aliado a tecnologia da informa��o, tendo o cliente como foco, a qualidade do atendimento como principal voca��o e o '.$config['gpweb'].' como eficaz ferramenta de comunica��o, coordena��o, planejamento, controle e gest�o.�</p>';
echo '</td></tr></table>';
echo '</td></tr>';


echo '<tr><td><table class="std2"><font color="#5c8526" face="Arial, Helvetica, sans-serif" style="font-size: 12pt;" size="5">Sobre o Desenvolvedor Original:</font><br><br>';
echo '<tr><td valign="top"><img src="modulos/ajuda/pt/reinert.jpg"  border="1" align="top" /></td>';
echo '<td width="617" valign="top">S�rgio Reinert desenvolveu a vers�o inicial do '.$config['gpweb'].' totalmente em PHP.<br><br>Administrator e s�cio majorit�rio da Sistema GP-Web Ltda.<br><br>Abaixo as principais �reas de conhecimento do desenvolvedor:';
echo '<ul><li>Programa��o (de 1983 at� a presente data) em :';
echo '<ul>';
echo '<li>Fortran</li>';
echo '<li>Pascal</li>';
echo '<li>Delphi</li>';
echo '<li>Assembler (micro-controladores)</li>';
echo '<li>C (micro-controladores)</li>';
echo '<li>C++</li>';
echo '<li>Java</li>';
echo '<li>PHP (estruturado e orientado � objetos)</li>';
echo '</ul>';
echo '<li>Cria��o de conte�do de ensino � dist�ncia</li>';
echo '<li>Modelagem e programa��o de banco de dados em SQL</li>';
echo '<li>Eletr�nica digital com foco em circuitos micro-controlados (PIC)</li>';
echo '</ul>';
echo '</td></tr></table>';
echo '</td></tr>';
echo '</table>';

echo estiloFundoCaixa();
?>