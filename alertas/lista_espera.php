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

/********************************************************************************************
		
gpweb\codigo\lista_espera.php		

Processamento dos eventos agendados, para disparar os alarmes. Precisa ser rodado 
constantemente para ser efetivo					
																																												
********************************************************************************************/
require_once '../base.php';
require_once BASE_DIR.'/config.php';
require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/ui.class.php';
require_once BASE_DIR.'/classes/evento_recorrencia.class.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once (BASE_DIR.'/estilo/rondon/sobrecarga.php');
$Aplic = new CAplic;
$Aplic->carregarPrefs();
require_once ($Aplic->getClasseSistema('libmail'));
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<meta http-equiv="Content-Type" content="text/html;charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'" />';
echo '<title>Envio de E-mails</title>';
echo '<meta http-equiv="Pragma" content="no-cache" />';
echo '<meta name="Version" content="'.$Aplic->getVersao().'" />';
echo '<link rel="stylesheet" type="text/css" href="../estilo/rondon/estilo_'.$config['estilo_css'].'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../estilo/rondon/estilo_'.$config['estilo_css'].'.css";</style>';
echo '<link rel="shortcut icon" href="../estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '</head>';
echo '<br><br>';
echo estiloTopoCaixa(770, '../');
echo '<table align="center" class="std" cellspacing=0 width="770" cellpadding=0>';
echo '<tr><td align=center><h1>Envio de E-mails na lista de espera</h1></td></tr>';
echo '<tr><td align=center>&nbsp;</td></tr>';
echo '<tr><td align=center>Verificando a lista ...</td></tr>';
echo '<tr><td align=center>&nbsp;</td></tr>';
$espera = new EventoFila;
$espera->verificar();
echo '<tr><td align=center>Feito, '.($espera->evento_contagem ? ($espera->evento_contagem==1 ? $espera->evento_contagem.' email enviado' : $espera->evento_contagem.' e-mails enviados.') : ' nenhum e-mail enviado!')."\n".'</td></tr>';
echo '<tr><td align=center>&nbsp;</td></tr>';
echo '</table>';
echo estiloFundoCaixa(770, '../');
?>