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

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

$Aplic->carregarCKEditorJS();


$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$dept_id = getParam($_REQUEST, 'dept_id', null);
$cia_id = getParam($_REQUEST, 'cia_id', null);

require_once (BASE_DIR.'/modulos/depts/depts.class.php');
$obj= new CDept();
$obj->load($dept_id);


$sql = new BDConsulta;

if (!$cia_id){
	$sql->adTabela('depts');
	$sql->adCampo('dept_cia');
	$sql->adOnde('dept_id='.(int)$dept_id);
	$cia_id= $sql->resultado();
	$sql->limpar();
	}

if(!($podeEditar && permiteEditarDept($obj->dept_acesso, $dept_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');
if ((!$podeEditar && $dept_id) || (!$podeAdicionar && !$dept_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');



$paises = array('' => '(Selecione um país)') + getPais('Paises');

$tipos = getSisValor('TipoDepartamento');

$sql->adCampo('estado_sigla, estado_nome');

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();



$cia_id = ($dept_id ? $obj->dept_cia : $cia_id);

$sql->adTabela('cias', 'com');
$sql->adCampo('cia_nome');
$sql->adOnde('com.cia_id = '.(int)$cia_id);
$cia_nome = $sql->Resultado();
$sql->limpar();
if (!$dept_id && $cia_nome === null) {
	$Aplic->setMsg('Est'.$config['genero_organizacao'].' '.$config['organizacao'].' é inválida. Não poderá inserir '.strtolower($config['departamentos']).' nela.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=depts');
	}

$ttl = ($dept_id > 0 ? 'Editar '.ucfirst($config['departamentos']) : 'Adicionar '.ucfirst($config['departamentos']));
$botoesTitulo = new CBlocoTitulo($ttl, 'depts.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$contatos_selecionados = array();
if ($dept_id) {
	$sql->adTabela('dept_contatos');
	$sql->adCampo('dept_contato_contato');
	$sql->adOnde('dept_contato_dept = '.(int)$dept_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="depts" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_dept_ead" />';
echo '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />';
echo '<input type="hidden" name="dept_cia" id="dept_cia" value="'.$cia_id.'" />';
echo '<input name="dept_contatos" id="dept_contatos" type="hidden" value="'.implode(',', $contatos_selecionados).'" />';
echo estiloTopoCaixa();
echo '<table border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']). ' d'.$config['genero_dept'].' '.$config['departamento'], 'Tod'.$config['genero_dept'].' '.strtolower($config['departamento']).' deve pertencer a um'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><b>'.$cia_nome.'</b></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome d'.$config['genero_dept'].' '.$config['departamento'], 'Tod'.$config['genero_dept'].' '.strtolower($config['departamento']).' deve ter um nome exclusivo e obrigatório.').'Nome d'.$config['genero_dept'].' '.$config['departamento'].':'.dicaF().'</td><td><input type="text" class="texto" name="dept_nome" value="'.$obj->dept_nome.'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right">'.dica('Código', 'Escreva, caso exista, o código '.$config['genero_dept'].' '.strtolower($config['departamento']).'.').'Código:'.dicaF().'</td><td><input type="text" class="texto" name="dept_codigo" value="'.$obj->dept_codigo.'" size="30" maxlength="255" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('E-mail', 'Escreva o e-mail d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'E-mail:'.dicaF().'</td><td><input type="text" class="texto" name="dept_email" value="'.$obj->dept_email.'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone', 'Escreva o telefone d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Telefone:'.dicaF().'</td><td><input type="text" class="texto" name="dept_tel" value="'.$obj->dept_tel.'" maxlength="30" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Fax', 'Escreva o fax d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Fax:'.dicaF().'</td><td><input type="text" class="texto" name="dept_fax" value="'.$obj->dept_fax.'" maxlength="30" /></td></tr>';
echo '<tr><td align="right">'.dica('Endereço', 'Escreva o enderço d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Endereço:'.dicaF().'</td><td><input type="text" class="texto" name="dept_endereco1" value="'.$obj->dept_endereco1.'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right">'.dica('Complemento do Endereço', 'Escreva o complemento do enderço d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="dept_endereco2" value="'.$obj->dept_endereco2.'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Estado:'.dicaF().'</td><td>'.meuCombo_chave($estado, 'dept_estado', $obj->dept_estado, true).'</td></tr>';
echo '<tr><td align="right">'.dica('Município', 'Escreva o município d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Município:'.dicaF().'</td><td><input type="text" class="texto" name="dept_cidade" value="'.$obj->dept_cidade.'" size="50" maxlength="50" /></td></tr>';
echo '<tr><td align="right">'.dica('CEP', 'Escreva o CEP d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'CEP:'.dicaF().'</td><td><input type="text" class="texto" name="dept_cep" value="'.$obj->dept_cep.'" maxlength="15" /></td></tr>';
echo '<tr><td align="right">'.dica('País', 'Escolha na caixa de opção à direita o País d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'País:'.dicaF().'</td><td>'.selecionaVetor($paises, 'dept_pais', 'size="1" class="texto"', $obj->dept_pais ? $obj->dept_pais : 'BR').'</td></tr>';
echo '<tr><td align="right">'.dica('Página Web d'.$config['genero_dept'].' '.$config['departamento'], 'Escreva o endereço da página internet d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'URL http://'.dicaF().'<a name="x"></a></td><td><input type="text" class="texto" value="'.$obj->dept_url.'" name="dept_url" size="50" maxlength="255" /><a href="javascript: void(0);" onclick="testeURL(\'dept_url\')">'.dica(' Testar Endereço', 'Clique para abrir em uma nova janela o link digitado à esquerda.').'[testar]'.dicaF().'</a></td></tr>';


echo '<tr><td align="right">'.dica(ucfirst($config['departamento']).' Superior', 'Escolha na caixa de opção à direita '.$config['genero_dept'].' '.strtolower($config['departamento']).' superior a est'.($config['genero_dept']=='a' ? 'a' : 'e').', caso esteja subordinad'.$config['genero_dept'].'.').ucfirst($config['departamento']).' superior:'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="dept_superior" value="'.$obj->dept_superior.'" /><input type="text" id="nome_superior" name="nome_superior" value="'.nome_dept($obj->dept_superior).'" size="50" class="texto" READONLY /></td><td><a href="javascript:void(0);" onclick="popDept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para selecionar '.$config['genero_dept'].' '.$config['departamento'].'.').'</a></td></td></tr></table></td></tr>';



echo '<tr><td align="right">'.dica('Responsável pel'.$config['genero_dept'].' '.$config['departamento'], 'Escolha o responsável pel'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Responsável:'.dicaF().'</td><td><input type="hidden" id="dept_responsavel" name="dept_responsavel" value="'.$obj->dept_responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_contato($obj->dept_responsavel).'" size="50" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar Responsável','Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar um responsável.').'</a></td></tr>';


$saida_contatos='';
if (count($contatos_selecionados)) {
		$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
		$qnt_lista_contatos=count($contatos_selecionados);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
				$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos.= '</td></tr></table>';
		}
else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(strtolower($config['contatos']), 'Quais '.strtolower($config['contatos']).' estão envolvid'.$config['genero_contato'].'s.').ucfirst($config['contatos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td size="50"style="width:275px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos()').'</td></tr></table></td></tr>';





echo '<tr><td align="right">'.dica('Tipo de '.$config['departamento'], 'Escolha na caixa de opção à direita o tipo de '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização ao separar as '.strtolower($config['departamentos']).' por tipo.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'dept_tipo', 'size="1" class="texto"', $obj->dept_tipo).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', $config['genero_dept'].' '.strtolower($config['departamento']).' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver e o responsável junto com os integrantes d'.$config['genero_dept'].' '.strtolower($config['departamento']).' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os integrantes d'.$config['genero_dept'].' '.strtolower($config['departamento']).' podem ver e editar</li><li><b>Privado</b> - Somente os integrantes d'.$config['genero_dept'].' '.strtolower($config['departamento']).' podem ver e o responsável pel'.$config['genero_dept'].' mesm'.$config['genero_dept'].' ver e editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'dept_acesso', 'class="texto"', ($dept_id ? $obj->dept_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Identificador d'.$config['genero_dept'].' '.$config['departamento'].' para NUP', 'Caso utilize o sistema único e processos faz-se necessário informar o número identificador d'.$config['genero_dept'].' '.$config['departamento'].' de 5 algarismos.').'Identificador de NUP'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="dept_nup" value="'.$obj->dept_nup.'" size="5" maxlength="5" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Quantos Protocolos d'.$config['genero_dept'].' '.$config['departamento'].' neste ano já foram inserido', 'Caso utilize utilize um sistema de protocolo, faz-se necessário informar quantos protocolos já foram emitidos, neste ano, para que aqueles emitidos pelo '.$config['gpweb'].' sigam a sequuência numérica crescente.').'Quantos protocolos'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="dept_qnt_nr" value="'.$obj->dept_qnt_nr.'" size="6" maxlength="20" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Prefixo','Preencha, caso exista, o prefixo à numeração sequencial crescente, nos protocolos diversos de NUP.').'Prefixo'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="dept_prefixo" value="'.$obj->dept_prefixo.'" size="15" maxlength="30" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Sufixo','Preencha, caso exista, o sufixo à numeração sequencial crescente, nos protocolos diversos de NUP.').'Sufixo'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="dept_sufixo" value="'.$obj->dept_sufixo.'" size="15" maxlength="30" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Ordem d'.$config['genero_dept'].' '.$config['departamento'], 'Caso deseje que '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' sejam ordenad'.$config['genero_dept'].'s em uma ordem diferente da alfabética este campo deverá ser preenchido. A lista é ordenada por este campo de forma ascendente, ou seja numerações de ordem mais baixas aparecem primeiro.').'Ordem'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="dept_qnt_nr" value="'.(int)$obj->dept_ordem.'" size="6" maxlength="20" /></td></tr>';

echo '<tr><td align="right" valign="middle" nowrap="nowrap">'.dica('Descrição d'.$config['genero_dept'].' '.$config['departamento'], 'Escreva uma descrição para '.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização.').'Descrição:'.dicaF().'</td><td align="left"><textarea data-gpweb-cmp="ckeditor" cols="70" rows="5" class="textarea" name="dept_descricao">'.$obj->dept_descricao.'</textarea></td></tr>';

echo '<tr><td align="right" width="100">'.dica('Ativ'.$config['genero_dept'], 'Caso '.$config['genero_dept'].' '.$config['departamento'].' ainda esteja ativ'.$config['genero_dept'].' deverá estar marcado este campo.').'Ativ'.$config['genero_dept'].':'.dicaF().'</td><td><input type="checkbox" value="1" name="dept_ativo" '.($obj->dept_ativo || !$dept_id ? 'checked="checked"' : '').' /></td></tr>';



require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados($m, $dept_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Retornar à tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \'m=depts&a='.($dept_id ? 'ver&dept_id='.(int)$dept_id : 'index&cia_id='.(int)$cia_id).'\');}').'</td></tr>';
echo '</form></table>';

echo estiloFundoCaixa();

?>
<script language="javascript">

var contatos_id_selecionados = '<?php echo implode(",", $contatos_selecionados)?>';

function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('dept_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('dept_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.dept_contatos.value = contato_id_string;
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados);
	__buildTooltip();
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id=&cia_id='+document.getElementById('dept_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id=&cia_id='+document.getElementById('dept_cia').value, 'Depts','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia, dept_id, dept_nome){
	if (dept_id > 0){
		document.env.dept_superior.value = dept_id;
		document.env.nome_superior.value = dept_nome;
		}
	else {
		document.env.dept_superior.value = null;
		document.env.nome_superior.value = '';
		}
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&contato=1&cia_id=<?php echo $cia_id?>&contato_id='+document.getElementById('dept_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&contato=1&cia_id=<?php echo $cia_id?>&contato_id='+document.getElementById('dept_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(contato_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('dept_responsavel').value=contato_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}



function testeURL( x ) {
	var teste = 'document.env.dept_url.value';
	teste = eval(teste);
	if (teste.length > 6) newwin = window.open( 'http://' + teste, 'newwin', '' );
	}


function enviarDados() {
	var form = document.env;
	if (form.dept_nome.value.length < 2) {
		alert( 'Entre um nome válido para <?php echo $config["genero_dept"].' '.strtolower($config["departamento"])?>.');
		form.dept_nome.focus();
		}
	else form.submit();
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

</script>
