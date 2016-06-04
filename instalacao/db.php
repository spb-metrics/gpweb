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


include_once 'checar_atualizar.php';
require_once BASE_DIR.'/estilo/rondon/funcao_grafica.php';
$config['popup_ativado']=true;
require_once BASE_DIR.'/incluir/funcoes_principais.php';
if (!isset($config['militar'])) require_once 'config-dist.php';
if (is_file(BASE_DIR.'/config.php')) require_once BASE_DIR.'/config.php';

$localidade_tipo_caract='iso-8859-1';
header("Content-Type: text/html; charset=ISO-8859-1", true);
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<meta name="Description" content="gpweb Default Style" />';
echo '<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<link rel="stylesheet" type="text/css" href="../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="../estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="../lib/mootools/mootools.js"></script>';

echo '<script type="text/javascript" src="../js/calendar.js"></script>';

echo '</head>';
echo '<body>';
if ($_REQUEST['modo']!=0 && checarAtualizacao($config) > 0 )	die('Checagem de Seguran�a: O gpweb aparentemente j� est� instalado.Instala��o cancelada!');



$timestamp = mktime (0, 0, 0, date("m")+1, date("d"),  date("Y"));
$data=date('d/m/Y', $timestamp);

echo '<form name="instFrm" action="checar_existe.php" method="post">';
echo '<input type="hidden" name="fazer_bd" value="0" />';
echo '<input type="hidden" name="fazer_cfg" value="0" />';
echo '<input type="hidden" name="fazer_bd_cfg" value="0" />';
echo '<input type="hidden" name="modo" value="'.getParam($_REQUEST, 'modo', null).'" />';

echo '<table width="100%" cellspacing=0 cellpadding=0><tr><td align=center>'.dica('Site do Sistema', 'Clique para entrar no site oficial do Sistema.').'<a href="http://www.sistemagpweb.com" target="_blank"><img alt="gpweb" src="../estilo/rondon/imagens/organizacao/10/gpweb_logo.png"/></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td></tr></table>';
echo '<table width="700" cellspacing=0 cellpadding=0 align="center"><tr><td colspan="2">'.estiloTopoCaixa(700,'../').'</td></tr><tr><td>';
echo '<table cellspacing=1 cellpadding=1 class="std" align="center" width="100%">';
echo '<tr><td class="title" align="right"><h1>Instalador do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</h1></td><td>&nbsp;</td></tr>';
echo '<tr><td class="title" align="right">Configura��es do Banco de Dados</td><td>&nbsp;</td></tr>';
echo '<tr><td align="right" width="400"  align="right">'.dica('Organiza��o', 'Configura os dados iniciais para se adequarem a sua Organiza��o.').'Organiza��o'.dicaF().'</td><td align="left"><select name="tipoCia" size="1" style="width:200px;" class="texto"><option value="1" '.($config['militar']=='1' ? 'SELECTED' : '').'>Ex�rcito</option><option value="2" '.($config['militar']=='2' ? 'SELECTED' : '').'>Marinha</option><option value="3" '.($config['militar']=='3' ? 'SELECTED' : '').'>Aeron�utica</option><option value="4" '.($config['militar']=='4' ? 'SELECTED' : '').'>Pol�cia Militar</option><option value="5" '.($config['militar']=='5' ? 'SELECTED' : '').'>For�as Armadas</option><option value="10" '.($config['militar']=='10' ? 'SELECTED' : '').'>Civil</option><option value="11" '.($config['militar']=='11' ? 'SELECTED' : '').'>Programa de Qualidade</option></select></td></tr>';
echo '<tr><td align="right">'.dica('Carregar �reas dos Estados e Munic�pios', 'Os limites geogr�ficos dos estados e munic�pios ser�o carregados.').'Carregar �reas dos Estados e Munic�pios?'.dicaF().'</td><td align="left"><input type="checkbox" name="areas" value="1" checked="checked" title="Carrega os limites geogr�ficos dos estados e munic�pios." /></td></tr>';
echo '<tr><td align="right">'.dica('Carregar Dados de Exemplo', 'Uma base de dados exemplo � instalada.<br><br>Todos os usu�rios neste exemplo tem a senha <b>123456</b>.<br><br>Ap�s verificar o exemplo, para come�ar do zero o sistema, com os dados de sua Organiza��o, basta excluir o arquivo <b>config.php</b> que fica dentro da pasta server e reiniciar o processo de instala��o.').'Carregar dados de exemplo?'.dicaF().'</td><td align="left"><input type="checkbox" name="exemplo" value="1" '.($config['exemplo']==true ? 'checked="checked"' : '').' title="Carrega uma base de dados para que possa ter uma melhor vis�o geral do sistema." /></td></tr>';
echo '<tr><td align="right">'.dica('Carregar Usu�rios para Treino', 'Um grupo de sessenta usu�rios com logins no formato alunoN (ex: aluno1, aluno12, etc.) e senha 123456 ser� criado, dentro de uma organiza��o espec�fica para treino.').'Carregar usu�rios para treino?'.dicaF().'</td><td align="left"><input type="checkbox" name="treino" value="1" '.($config['treino']==true ? 'checked="checked"' : '').' title="Carrega um grupo de 60 usu�rios, para serem utilizados em treinos." /></td></tr>';

echo '<tr><td align="right">'.dica('Restringir Altera��es', 'Caso esteja usando o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' em ambiente de treinamento e este campo esteja marcado ser� restringido certas op��es como mudar senha, excluir usu�rio, etc.').'Restringir altera��es?'.dicaF().'</td><td align="left"><input type="checkbox" name="restrito" value="1" title="Caso esteja usando o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' em ambiente de treinamento pode restringir certas op��es como mudar senha, excluir usu�rio, etc." /></td></tr>';
echo '<tr><td align="right">'.dica('Data de Expira��o', 'Caso esteja usando o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' em ambiente de demonstra��o para potencial client e este campo esteja marcado haver� uma data limite para utiliza��o do sistema.').'Data de expira��o?'.dicaF().'</td><td align="left"><input type="checkbox" id="tem_data_limite" name="tem_data_limite" onclick="if (instFrm.tem_data_limite.checked) {instFrm.tem_data_limite.checked=true; document.getElementById(\'data\').style.display=\'\'; } else {document.getElementById(\'data\').style.display=\'none\';}" value="1" title="Caso esteja usando o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' em ambiente de demonstra��o para potencial client e este campo esteja marcado haver� uma data limite para utiliza��o do sistema." /><input type="hidden" name="data_limite" id="data_limite" value="'.$data.'" /><input type="text" name="data" style="width:70px; display:none" id="data" onchange="setData(\'instFrm\', \'data\', \'data_limite\');" value="'.$data.'" class="texto"/></td></td></tr>';
echo '<tr><td align="right" width="400"  align="right">'.dica('Sistema Gerenciador de Banco de Dados', 'Selecione qual sistema gerenciador de banco de dados ser� utilizado.').'SGBD'.dicaF().'</td><td align="left"><select name="tipoBd" size="1" style="width:200px;" class="texto"><option value="mysql" SELECTED >MySQL</option></select></td></tr>';

echo '<tr><td align="right">'.dica('Login do Administrador do SGBD', 'Nome do administrador do SGBD instalado no servidor.<br> Por <i>default</i> tem o nome <b>root</b>.').'Login do administrador do SGBD'.dicaF().'</td><td align="left"><input type="text" name="usuarioBd" value="'.$config['usuarioBd'].'" title="O usu�rio do Banco de Dados que o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' utilizar� para conex�o ao Banco de Dados" /></td></tr>';
echo '<tr><td align="right">'.dica('Senha do Administrador do SGBD', 'Senha utilizada pelo administrador para acessar o SGDB.<br><br>Vers�es de PHP+MySQL como Xampp e Wamp costumam n�o conter senha por <i>default</i>. Caso este seja o seu caso deixe este campo em branco.').'Senha do Administrador do SGBD'.dicaF().'</td><td align="left"><input type="password" name="senhaBd" value="'.$config['senhaBd'].'" title="A senha para o usu�rio acima." /></td></tr>';

echo '<tr><td align="right">'.dica('Endere�o do Servidor Hospedeiro do Banco de Dados', 'Caso o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' (p�ginas PHP) esteja instalado na mesma m�quina onde esteja o SGBD provavelmente o endere�o seja 127.0.0.1 (localhost).').'Endere�o do Servidor(Host) do Banco de Dados'.dicaF().'</td><td align="left"><input type="text" name="hospedadoBd" value="'.$config['hospedadoBd'].'" title="Nome do Host onde o servidor de banco de dados est� instalado" /></td></tr>';
echo '<tr><td align="right">'.dica('Nome da Base de Dados', 'Nome da base de dados que conter� todas as tabelas do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'.').'Nome da Base de Dados'.dicaF().'</td><td align="left"><input type="text" name="nomeBd" value="'. $config['nomeBd'].'" title="O nome da base de dados que o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' utilizar� ap�s instala��o ou atualizar" /></td></tr>';
echo '<input type="hidden" name="prefixoBd" value="" />';

echo '<tr><td align="left"><table width="100%"><tr><td>'.($_REQUEST['modo']=='0' ? botao('somente o banco de dados', 'Instalar a Base de Dados', 'Instalar apenas a base de dados do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'.<br><br>Obs: Caso j� n�o haja o arquivo de configura��o do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' (gpweb/<b>config.php</b>) no sistema, o aplicativo n�o ir� funcionar. Neste caso selecione a op��o de instalar o banco de dados junto com a cria��o do arquivo de configura��o.','','f=document.instFrm;f.fazer_bd.value=1;f.submit()') : botao('atualizar a base de dados', 'Atualizar','Atualizar apenas a base de dados do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'.<br><br>Obs: Caso j� n�o haja o arquivo de configura��o do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' (server/<b>config.php</b>) no sistema, o aplicativo n�o ir� funcionar. Neste caso selecione a op��o de instalar o banco de dados junto com a cria��o do arquivo de configura��o.','','f=document.instFrm;f.fazer_bd.value=1;f.submit()')).'</td><td>';
echo ($_REQUEST['modo']=='0' ? botao('somente configura��o', 'Criar Arquivo de Configura��o', 'Criar apenas o arquivo de configura��o do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'(gpweb/<b>config.php</b>).<br><br>Obs: Caso j� n�o haja a base de dados instalada, o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' n�o ir� funcionar. Neste caso selecione a op��o de instalar o banco de dados junto com a cria��o do arquivo de configura��o.','','f=document.instFrm;f.fazer_cfg.value=1;f.submit()') : botao('atualizar configura��o', 'Atualizar o Arquivo de Configura��o', 'Atualizar apenas o arquivo de configura��o do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'(server/<b>config.php</b> ).<br><br>Obs: Caso j� n�o haja a base de dados instalada, o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' n�o ir� funcionar. Neste caso selecione a op��o de instalar o banco de dados junto com a cria��o do arquivo de configura��o.','','f=document.instFrm;f.fazer_cfg.value=1;f.submit()')).'</td><td align="right"></td></tr></table></td>';
echo '<td align="left"><table><tr><td>'.($_REQUEST['modo']=='0' ? botao('<b>instalar completo</b>', 'Instalar', 'Instalar a base de dados e criar o arquivo de configura��o (gpweb/<b>config.php</b>) do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'.','','f=document.instFrm;f.fazer_bd_cfg.value=1;f.submit()'): botao('<b>atualizar completo</b>', 'Atualizar','Atualiuzar a base de dados e reescrever o arquivo de configura��o (server/<b>config.php</b>) do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'.','','f=document.instFrm;f.fazer_bd_cfg.value=1;f.submit()')).'</td><td>(recomendado)</td></tr></table></td></tr>';
echo '</table></td></tr>';
echo '<tr><td colspan="2">'.estiloFundoCaixa(700,'../').'</td></tr></table></form>';
echo '<script type="text/javascript">window.addEvent(\'domready\', function(){var as = []; $$(\'span\').each(function(span){if (span.getAttribute(\'title\')) as.push(span);});new Tips(as), {	}});</script>';

?>
<script language="javascript">
function parsfimData(val) {
	var preferEuro=(arguments.length==2)?arguments[1]:false;
	formatosGerais=new Array('yyyyMMddHHmm','dd/MM/yyyy','yyyyMMdd', 'dd/MM/Y');
	mesPrimeiro=new Array();
  dataPrimeiro =new Array();
	var listaChecagem=new Array('formatosGerais',preferEuro?'dataPrimeiro':'mesPrimeiro',preferEuro?'mesPrimeiro':'dataPrimeiro');
	var d=null;
	for (var i=0, i_cmp=listaChecagem.length; i<i_cmp; i++) {
		var l=window[listaChecagem[i]];
		for (var j=0, j_cmp=l.length; j<j_cmp; j++) {
			d=getDataDoFormato(val,l[j]);
			if (d!=0) { return new Date(d); }
			}
		}
	return null;
	}

function setData( frm_nome, f_data, f_data_real ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
    	}
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'dd/MM/yyyy');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/yyyy');
      campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}
</script>
<?php


echo '</body></html>';
?>


