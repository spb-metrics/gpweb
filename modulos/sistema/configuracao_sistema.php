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
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$modulo=array('email'=>'E-mail', 'calendario'=>'Calendário', 'praticas'=> ucfirst($config['praticas']), 'projetos'=>ucfirst($config['projetos']));
$vazio=array();
include_once BASE_DIR.'/localidades/pt/sistema.php';

$sql = new BDConsulta;
$sql->adTabela('modulos');
$sql->adCampo('mod_diretorio');
$sql->adOnde('mod_tipo !="core"');
$sql->adOnde('mod_ativo=1');
$sql->adOrdem('mod_ui_ordem, mod_ui_nome');
$extras = $sql->carregarColuna();
$sql->limpar();
foreach($extras as $extra) {
	if (is_file(BASE_DIR.'/modulos/'.$extra.'/sistema.php')) include_once BASE_DIR.'/modulos/'.$extra.'/sistema.php';
	}

if (!$Aplic->usuario_super_admin)	$Aplic->redirecionar('m=publico&a=acesso_negado');
$Apliccfg = new CConfig();

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ConfiguracaoSistemaTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ConfiguracaoSistemaTab') !== null ? $Aplic->getEstado('ConfiguracaoSistemaTab') : 0;
$ativo = intval(!$Aplic->getEstado('ConfigIdxTab'));

//verificar se está instalado em servidor da gpweb compartilhado
$bronze=(isset($config['bronze']) && $config['bronze'] ? 1 : 0);

if ($bronze){
	
	$vetor_trinta=array();
	$vetor_vinte=array();
	
	for($i=20; $i>0; $i--) $vetor_vinte[$i]=$i;
	for($i=30; $i>0; $i--) $vetor_trinta[$i]=$i;
	}

$botoesTitulo = new CBlocoTitulo('Configuração do Sistema', 'config-sistema.png', $m);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

$Aplic->salvarPosicao();


echo '<form name="cfgFrm" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="dialogo" type="hidden" value="1" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sistemaconfig_aed" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%" align="center">';
echo '<tr><td colspan="2">';
echo 'As variáveis abaixo tem um impacto direto no funcionamento do sistema. Uma configuração incorreta poderá tornar o '.$config['gpweb'].' inoperante.<br><br>';

if (is_dir(BASE_DIR.'/instalacao')) {
	$Aplic->setMsg(dica('Exclua Instalação', 'Exclua no servidor a pasta de instalação.<ul><li>'.BASE_DIR.'\<b>instalacao</b></li></ul>').'<a href="javascript: void(0);">Você não removeu o diretório de instalação. Isto é um fator de risco muito grave para o sistema!</a>'.dicaF(), UI_MSG_ALERTA);
	echo '<span class="error">'.$Aplic->getMsg().'</span>';
	}

if (!function_exists('openssl_sign')) {
	$Aplic->setMsg(dica('Instale o OpenSSL', 'Para instalar a biblioteca OpenSSL, abra o arquivo php.ini e descomente (tirar o ponto-virgula da frente) ou insira a linha extension=php_openssl.dll.').'<a href="javascript: void(0);">Você não instalou a biblioteca OpenSSL. Não poderá assinar eletrônicamente nem enviar E-mail criptografado com chave pública!</a>'.dicaF(), UI_MSG_ALERTA);
	echo '<span class="error">'.$Aplic->getMsg().'</span>';
	}	

if (!is_writable($base_dir.'/arquivos')) {
	$Aplic->setMsg(dica('Dê Permissão de Escrita', 'No ambiente linux basta dar o comando chmod 666 na pasta<br>'.$base_dir.'\arquivos').'<a href="javascript: void(0);">A pasta '.BASE_DIR.'\arquivos não está com permissão de escrita! Não conseguirá anexar arquivos d'.$config['genero_projeto'].'s '.$config['projetos'].'</a>'.dicaF(), UI_MSG_ALERTA);
	echo '<span class="error">'.$Aplic->getMsg().'</span>';
	}	

if (!is_writable($base_dir.'/'.$config['pasta_anexos'])) {
	$Aplic->setMsg(dica('Dê Permissão de Escrita', 'No ambiente linux basta dar o comando chmod 666 na pasta<br>'.$base_dir.'\\'.$config["pasta_anexos"]).'<a href="javascript: void(0);">A pasta '.$base_dir.'\\'.$config["pasta_anexos"].' não está com permissão de escrita! Não conseguirá anexar arquivos nos e-mails</a>'.dicaF(), UI_MSG_ALERTA);
	echo '<span class="error">'.$Aplic->getMsg().'</span>';
	}	

$Aplic->setMsg(dica('Aviso de Compromisso', 'Para que o alarme dos avisos de compromissos e eventos possa funcionar é necessário criar um trabalho Cron (linux) para chamar a cada 10 minutos o script '.$config['gpweb'].'/codigo/lista_espera.php<br><br> ex:  wget -O- http://seu.dominio/'.$config['gpweb'].'/codigo/lista_espera.php').'<a href="javascript: void(0);">Não esqueça de configurar o sistema de alarme de avisos do '.$config['gpweb'].'!</a>'.dicaF(), UI_MSG_AVISO);
echo '<span class="error">'.$Aplic->getMsg().'</span>';
echo '</td></tr>';

$ultimo_grupo = '';
$rs = $Apliccfg->carregarTudo('config_grupo');

foreach ($rs as $c){
	$popup =  (isset($traducao[$c['config_nome'].'_dica']) ? $traducao[$c['config_nome'].'_dica'] : '');
	$valor = '';
	$extra='';
	switch ($c['config_tipo']){
		case 'select':
			if ($c['config_nome']=='ldap_perfil'){
				$sql->adTabela('perfil');
				$sql->adCampo('perfil.*');
				$perfis=$sql->lista();
				$sql->Limpar();
				$perfis_arr = array();
				$i=0;
				foreach ($perfis as $perfil) if ($i++) $perfis_arr[$perfil['perfil_id']] = $perfil['perfil_nome'];
				$entrada=selecionaVetor($perfis_arr, 'cfg['.$c['config_nome'].']', 'style="width:300px;" size="1" class="texto"', $c['config_valor']);
				}
			elseif ($c['config_nome']=='externo_perfil'){
				$sql->adTabela('perfil');
				$sql->adCampo('perfil.*');
				$perfis=$sql->lista();
				$sql->Limpar();
				$perfis_arr = array();
				$i=0;
				foreach ($perfis as $perfil) if ($i++) $perfis_arr[$perfil['perfil_id']] = $perfil['perfil_nome'];
				$entrada=selecionaVetor($perfis_arr, 'cfg['.$c['config_nome'].']', 'style="width:300px;" size="1" class="texto"', $c['config_valor']);
				}		
			else {
				$entrada = '<select class="texto" style="width:300px;" name="cfg['.$c['config_nome'].']">';
				$subordinada = $Apliccfg->getSubordinada($c['config_nome']);
				foreach ($subordinada as $sub) {
					$entrada .= '<option value="'.$sub['config_lista_nome'].'"'.($sub['config_lista_nome'] == $c['config_valor'] ? ' selected="selected" ' : '').'>'.(isset($traducao[$c['config_nome'].'_'.$sub['config_lista_nome'].'_item_titulo']) ? $traducao[$c['config_nome'].'_'.$sub['config_lista_nome'].'_item_titulo'] : (isset($traducao[$sub['config_lista_nome'].'_item_titulo']) ? $traducao[$sub['config_lista_nome'].'_item_titulo']  : '')).'</option>';
					}
				$entrada .= '</select>';
				}	
			break;
			
		case 'combo_calendario':
			$sql->adTabela('jornada');
			$sql->adCampo('jornada_id, jornada_nome');
			$sql->adOrdem('jornada_nome'); 
			$calendarios=$sql->listaVetorChave('jornada_id','jornada_nome');
			$sql->limpar();
			$entrada=selecionaVetor($calendarios, 'cfg['.$c['config_nome'].']', 'class="texto" style="width:300px;"', $c['config_valor']);
			break;		
			
			
			
		case 'combo_cor':
			$entrada = '<select class="texto" name="cfg['.$c['config_nome'].']" style="width:300px;">';
			$entrada .= '<option value="ff3d3d" style="background-color: #ff3d3d;" '.('ff3d3d' == $c['config_valor'] ? ' selected="selected" ' : '').'>ff3d3d</option>';
			$entrada .= '<option value="ff813d" style="background-color: #ff813d;" '.('ff813d' == $c['config_valor'] ? ' selected="selected" ' : '').'>ff813d</option>';
			$entrada .= '<option value="ffa63d" style="background-color: #ffa63d;" '.('ffa63d' == $c['config_valor'] ? ' selected="selected" ' : '').'>ffa63d</option>';
			$entrada .= '<option value="ffc63d" style="background-color: #ffc63d;" '.('ffc63d' == $c['config_valor'] ? ' selected="selected" ' : '').'>ffc63d</option>';
			$entrada .= '<option value="ffdd3d" style="background-color: #ffdd3d;" '.('ffdd3d' == $c['config_valor'] ? ' selected="selected" ' : '').'>ffdd3d</option>';
			$entrada .= '<option value="fff83d" style="background-color: #fff83d;" '.('fff83d' == $c['config_valor'] ? ' selected="selected" ' : '').'>fff83d</option>';
			$entrada .= '<option value="eaff3d" style="background-color: #eaff3d;" '.('eaff3d' == $c['config_valor'] ? ' selected="selected" ' : '').'>eaff3d</option>';
			$entrada .= '<option value="d4ff3d" style="background-color: #d4ff3d;" '.('d4ff3d' == $c['config_valor'] ? ' selected="selected" ' : '').'>d4ff3d</option>';
			$entrada .= '<option value="c1ff3d" style="background-color: #c1ff3d;" '.('c1ff3d' == $c['config_valor'] ? ' selected="selected" ' : '').'>c1ff3d</option>';
			$entrada .= '<option value="8bf22f" style="background-color: #8bf22f;" '.('8bf22f' == $c['config_valor'] ? ' selected="selected" ' : '').'>8bf22f</option>';
			$entrada .= '<option value="51d529" style="background-color: #51d529;" '.('51d529' == $c['config_valor'] ? ' selected="selected" ' : '').'>51d529</option>';
			$entrada .= '<option value="49f2d4" style="background-color: #49f2d4;" '.('49f2d4' == $c['config_valor'] ? ' selected="selected" ' : '').'>49f2d4</option>';
			$entrada .= '<option value="49f2f0" style="background-color: #49f2f0;" '.('49f2f0' == $c['config_valor'] ? ' selected="selected" ' : '').'>49f2f0</option>';
			$entrada .= '<option value="49e4f2" style="background-color: #49e4f2;" '.('49e4f2' == $c['config_valor'] ? ' selected="selected" ' : '').'>49e4f2</option>';
			$entrada .= '<option value="3fd0ef" style="background-color: #3fd0ef;" '.('3fd0ef' == $c['config_valor'] ? ' selected="selected" ' : '').'>3fd0ef</option>';
			$entrada .= '<option value="3fbbef" style="background-color: #3fbbef;" '.('3fbbef' == $c['config_valor'] ? ' selected="selected" ' : '').'>3fbbef</option>';
			$entrada .= '<option value="3fa2ef" style="background-color: #3fa2ef;" '.('3fa2ef' == $c['config_valor'] ? ' selected="selected" ' : '').'>3fa2ef</option>';
			$entrada .= '<option value="3f79ef" style="background-color: #3f79ef;" '.('3f79ef' == $c['config_valor'] ? ' selected="selected" ' : '').'>3f79ef</option>';
			$entrada .= '<option value="3f4fef" style="background-color: #3f4fef;" '.('3f4fef' == $c['config_valor'] ? ' selected="selected" ' : '').'>3f4fef</option>';
			$entrada .= '<option value="753fef" style="background-color: #753fef;" '.('753fef' == $c['config_valor'] ? ' selected="selected" ' : '').'>753fef</option>';
			$entrada .= '<option value="923fef" style="background-color: #923fef;" '.('923fef' == $c['config_valor'] ? ' selected="selected" ' : '').'>923fef</option>';
			$entrada .= '<option value="a23fef" style="background-color: #a23fef;" '.('a23fef' == $c['config_valor'] ? ' selected="selected" ' : '').'>a23fef</option>';
			$entrada .= '</select>';
			break;	
			

		case 'checkbox':
			$extra = ($c['config_valor'] == 'true') ? 'checked="checked"': '';
			$valor = 'true';
			$entrada = '<input class="texto" type="'.$c['config_tipo'].'" name="cfg['.$c['config_nome'].']" value="'.$valor.'" '.$extra.'/>';
			break;
		
		case 'usuario':
			$entrada ='<input type="hidden" id="'.$c['config_nome'].'" name="cfg['.$c['config_nome'].']" value="'.$c['config_valor'].'" /><input type="text" id="'.$c['config_nome'].'_nome" name="'.$c['config_nome'].'_nome" value="'.nome_om($c['config_valor'],$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popUsuario(\''.$c['config_nome'].'\');">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a>';
			break;	
			
		case 'dept':
			$entrada ='<input type="hidden" id="'.$c['config_nome'].'" name="cfg['.$c['config_nome'].']" value="'.$c['config_valor'].'" /><input type="text" id="'.$c['config_nome'].'_nome" name="'.$c['config_nome'].'_nome" value="'.nome_dept($c['config_valor']).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popDept(\''.$c['config_nome'].'\');">'.imagem('secoes_p.gif', 'Selecionar '.ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para selecionar '.($config['departamento']=='o' ? 'um' : 'uma').' '.$config['departamento'].'.').'</a>';
			break;	
		
		case 'quantidade':
		if (!$valor) $valor = $c['config_valor'];
			
			if ($bronze) {
				if ($c['config_nome']!='qnt_indicadores') $entrada=selecionaVetor($vetor_trinta, 'cfg['.$c['config_nome'].']', 'class="texto" style="width:300px;"', $c['config_valor']);	
				else $entrada=selecionaVetor($vetor_vinte, 'cfg['.$c['config_nome'].']', 'class="texto" style="width:300px;"', $c['config_valor']);	
				}
			else $entrada ='<input class="texto" style="width:300px;" type="'.$c['config_tipo'].'" name="cfg['.$c['config_nome'].']" id="cfg['.$c['config_nome'].']" value="'.$valor.'" '.$extra.'/>';
			break;	
		
		
		default:
			if (!$valor) $valor = $c['config_valor'];
			if ($c['config_nome']=='padrao_ver_m') $entrada=selecionaVetor($modulo, 'cfg[padrao_ver_m]','class="texto" size=1 onchange="submodulo();"', $valor);
			elseif ($c['config_nome']=='padrao_ver_a') {
				$entrada=selecionaVetor($vazio, 'cfg[padrao_ver_a]','class="texto" size=1 onchange="tab_submodulo();"', $valor);
				echo '<script type="text/javascript">var ver_a_original="'.$valor.'";</script>';
				}
			else $entrada = '<input class="texto" style="width:300px;" type="'.$c['config_tipo'].'" name="cfg['.$c['config_nome'].']" id="cfg['.$c['config_nome'].']" value="'.$valor.'" '.$extra.'/>';
			break;
		}
	

	if ($c['config_grupo'] != $ultimo_grupo) {
		echo '<tr><td align="right" nowrap="nowrap"><br><b>'.(isset($traducao[$c['config_grupo'].'_grupo_titulo']) ? $traducao[$c['config_grupo'].'_grupo_titulo'] : $c['config_grupo']).'</b></td><td width="100%">&nbsp;</td></tr>';
		$ultimo_grupo = $c['config_grupo'];
		}

	if ($c['config_nome']=='om_padrao') echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Padrão', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' quando da criação de nova conta por '.$config['usuario'].' ainda sem login de acesso.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_superior">'.selecionar_om($c['config_valor'], 'cfg['.$c['config_nome'].']', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"','','','cfg['.$c['config_nome'].']').'</div><input value="'.$c['config_id'].'" type="hidden" name="cfgId['.$c['config_nome'].']" /></td></tr>';
	else echo '<tr><td align="right" nowrap="nowrap">'.dica((isset($traducao[$c['config_nome'].'_titulo']) ? $traducao[$c['config_nome'].'_titulo'] : ''), $popup). (isset($traducao[$c['config_nome'].'_titulo']) ? $traducao[$c['config_nome'].'_titulo']  : $c['config_nome']).':'.dicaF().'</td><td align="left">'.$entrada.'<input value="'.$c['config_id'].'" type="hidden" name="cfgId['.$c['config_nome'].']" /></td></tr>';
	}








echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar as configurações.','','cfgFrm.submit()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a a edição das configurações.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr>';
echo '</table></form>';
echo estiloFundoCaixa();
?>
<script type="text/javascript">
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cfg[om_padrao]').value, 'cfg[om_padrao]','combo_superior', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"'); 	
	}		



function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&campo='+campo+'&usuario_id='+document.getElementById(campo).value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&campo='+campo+'&usuario_id='+document.getElementById(campo).value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById(campo).value=usuario_id;
	document.getElementById(campo+'_nome').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}


function popDept(campo) {
  window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&campo='+campo+'&dept_id='+document.getElementById(campo).value,'dept','left=0,top=0,height=600,width=400, scrollbars=yes, resizable');
	}


function setDept(cia, chave, val, campo) {
  if (chave != null && chave !='') {
    document.getElementById(campo).value=chave;
		document.getElementById(campo+'_nome').value=val;	
		} 
  else {
     document.getElementById(campo).value=null;
		document.getElementById(campo+'_nome').value='';	
		}

	}




var tab_original=document.getElementById("cfg[padrao_ver_tab]").value;
var ver_m_original=document.getElementById("cfg[padrao_ver_m]").value;

function submodulo(){
	var f = document.getElementById("cfg[padrao_ver_m]");
  var modulo = f.value;
  a=document.getElementById("cfg[padrao_ver_a]");
  a.length=0; 
  switch (modulo) {
    case "email":
    adicionarOpcao(a, 'lista_msg', 'Caixa de entrada de E-mails');
    adicionarOpcao(a, 'modelo_pesquisar', 'Caixa de entrada de modelos de documentos');
    break;

    case "calendario":
    adicionarOpcao(a, 'ver_dia', 'Eventos do dia ');
    adicionarOpcao(a, 'ver_dia', 'Compromissos do dia ');
   	adicionarOpcao(a, 'ver_dia', '<?php echo ucfirst($config["tarefa"])?> a serem realizadas');
    break;
    
    case "praticas":
    adicionarOpcao(a, 'index', 'Menu geral de gerenciamento da excelência');
    adicionarOpcao(a, 'pratica_lista', 'Lista de <?php echo $config["praticas"]?>');
    adicionarOpcao(a, 'indicador_lista', 'Lista de indicadores');
    break;
    
    case "projetos":
    adicionarOpcao(a, 'index', 'Lista de <?php echo $config["projetos"]?>');
    break;
    } 
  tab_submodulo();         
	}

function adicionarOpcao(selectbox,value,text){

	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;
	
	if (ver_a_original==value){
		optn.selected = true;
		}
	selectbox.options.add(optn);
	}
	

function tab_submodulo(){
	var m=document.getElementById("cfg[padrao_ver_m]").value;
	var indice=document.getElementById("cfg[padrao_ver_a]").selectedIndex;
	var tab=0;
	if (m=='calendario' && indice==0) tab=0;
	if (m=='calendario' && indice==1) tab=1;
	if (m=='calendario' && indice==2) tab=2;
	document.getElementById("cfg[padrao_ver_tab]").value=tab;
	}


submodulo();
tab_submodulo();	
</script>	
