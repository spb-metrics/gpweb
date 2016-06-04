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

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$d = $Aplic->getPref('datacurta');
$contato_id = getParam($_REQUEST, 'contato_id', null);
$cia_id = getParam($_REQUEST, 'cia_id', null);
$dept_id = getParam($_REQUEST, 'dept_id', null);
$usuario_id=usuario_id($contato_id);
$cia_nome = getParam($_REQUEST, 'cia_nome', null);
$dept_nome = getParam($_REQUEST, 'dept_nome', null);
$podeAdicionar = $Aplic->checarModulo('contatos', 'adicionar');
$podeEditar = ($Aplic->checarModulo('contatos', 'editar') || $usuario_id==$Aplic->usuario_id);
if (!$contato_id  && !$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if ($contato_id && !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$privado = ((($Aplic->getEstado('filtro_id_responsavel') ? $Aplic->getEstado('filtro_id_responsavel') : 0) == $Aplic->usuario_id) && !$usuario_id);

$q = new BDConsulta;
$q->adTabela('cias');
$q->adCampo('cia_nome,cia_id');
$linhas = $q->Lista();
$q->limpar();

$listagem_om=array();
foreach ($linhas as $om) $listagem_om[$om['cia_id']]=$om['cia_nome'];
$lista_om = array(0 => '') + $listagem_om;
$msg = '';
$linha = new CContato();
$podeExcluir = $linha->podeExcluir($msg, $contato_id);
$eh_usuario = $linha->ehUsuario($contato_id);
$protegidoExclusaoUsuario = (!$podeExcluir ? true : false) ;
if (!$linha->load($contato_id) && $contato_id > 0) {
	$Aplic->setMsg('Contato');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=contatos');
	}
elseif ($linha->contato_privado && $linha->contato_dono != $Aplic->usuario_id && $linha->contato_dono && $contato_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
$df = '%d/%m/%Y';
$df .= ' '.$Aplic->getPref('formatohora');

$botoesTitulo = new CBlocoTitulo(($contato_id > 0 ? 'Editar ' : 'Adicionar ').ucfirst($config['contato']), 'contatos.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();



$cia_detalhe = $linha->getCiaDetalhes();
$dept_detalhe = $linha->getDetalhesProfundos();
if ($contato_id == 0 && $cia_id > 0) {
	$cia_detalhe['cia_id'] = $cia_id;
	$cia_detalhe['cia_nome'] = $cia_nome;
	$dept_detalhe['dept_id'] = $dept_id;
	$dept_detalhe['dept_nome'] = $dept_nome;
	}
$paises = getPais('Paises');
$posto=array();
if ($config['militar'] < 10) $posto+= getSisValor('Posto'.$config['militar']);
else $posto+= getSisValor('PronomeTratamento');
$arma=array(0 => '');
$arma+= getSisValor('Arma'.$config['militar']);
$estado=array('' => '');
$q->adTabela('estado');
$q->adCampo('estado_sigla, estado_nome');
$q->adOrdem('estado_nome');
$estado+= $q->listaVetorChave('estado_sigla', 'estado_nome');
$q->limpar();

echo '<form name="frmEditar" method="post">';
echo '<input type="hidden" name="m" value="contatos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_contato_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="contato_atualizacao_exclusiva" value="'.uniqid('').'" />';
echo '<input type="hidden" name="contato_id" id="contato_id" value="'.$contato_id.'" />';
echo '<input type="hidden" name="contato_posto_valor" value="'.($linha->contato_posto_valor ? (int)$linha->contato_posto_valor : 0).'" />';
echo '<input type="hidden" name="contato_dono" value="'.($linha->contato_dono ? $linha->contato_dono : $Aplic->usuario_id).'" />';
echo '<input type="hidden" name="existe_identidade" id="existe_identidade" value="" />';
echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td colspan="2">';
echo '<table cellpadding=0 cellspacing=0">';

echo '<tr><td align="right">'.dica(($config['militar'] < 10 ? 'Posto/Grad  e Nome Guerra' : 'Pron. Trat. e Nome'), 'Selecione '.($config['militar'] < 10 ? 'o posto/graduação e escreva o nome de guerra' : 'o pron. trat. e escreva o nome').' d'.$config['genero_usuario'].' '.$config['usuario'].'.').($config['militar'] < 10 ? 'Posto/Grad  e Nome Guerra:' : 'Pron. Trat. e Nome:').dicaF().'</td><td>'.selecionaVetor($posto, 'contato_posto', 'class="texto" size=1', $linha->contato_posto, true).'<input type="text" class="texto" size="25" name="contato_nomeguerra" value="'.$linha->contato_nomeguerra.'" maxlength="30" /></td></tr>';
echo '<tr><td align="right"><label for="contato_privado">'.dica('Contato Privado', 'Marque esta opção caso deseje que somente você possa visualizar este contato.').'Contato Privado:'.dicaF().'</label></td><td><input type="checkbox" value="1" name="contato_privado" id="contato_privado" '.($linha->contato_privado || $privado ? 'checked="checked"' : '').' /></td></tr>';
echo '</table></td>';
echo '<td valign="top" align="right" width="150">';
if ($usuario_id!=$Aplic->usuario_id)	{
	echo '<table border=0 width="150" cellpadding=0 cellspacing=0">';
	echo '<td colspan="2" align="center"><b>Atualização</b></td>';
	echo '<tr><td align="right" width="100" nowrap="nowrap">'.dica('Solicitar Atualização', 'Marque esta caixa caso deseje que seje enviado um e-mail para o contato solicitando que o mesmo atualize seu cadastro.').'Solicitar:'.dicaF().'</td><td align="center"><input type="checkbox" value="1" name="contato_atualizarSolicitado" '.($linha->contato_chave_atualizacao ? 'checked="checked"' : '').' onclick="verificarAtualizacao()"/></td></tr>';
	$ultimo_pedido = new CData($linha->contato_pedido_atualizacao);
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Última Solicitação', 'Data da última solicitação para que o contato mesmo atualize seu cadastro.').'Última solicitação:'.dicaF().'</td><td align="center" nowrap="nowrap">'.($linha->contato_pedido_atualizacao ? $ultimo_pedido->format($df) : '').'</td></tr>';
	$ultimo_atualizado = new CData($linha->contato_ultima_atualizacao);
	echo '<tr><td align="right" width="100" nowrap="nowrap">'.dica('Última Atualização', 'Data da última atualização do cadastro.').'Atualizado em:'.dicaF().'</td><td align="center" nowrap="nowrap">'.($linha->contato_ultima_atualizacao && @!($linha->contato_ultima_atualizacao == 0) ? $ultimo_atualizado->format($df) : '').'</td></tr>	';
	echo '</table>';
	}
echo '</td></tr>';

echo '<td valign="top" width="50%"><table border=0 cellpadding=0 cellspacing=0 class="details" width="100%">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome Completo', 'Nome completo do contato.').'Nome completo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_nomecompleto" value="'.$linha->contato_nomecompleto.'" size="40" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' do contato.').ucfirst($config['organizacao']).':'.dicaF().'</td><td width="100%" nowrap="nowrap" colspan="2">'.($Aplic->checarModulo('cias', 'acesso') ? '<div id="combo_cia">'.selecionar_om($linha->contato_cia, 'contato_cia', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"','&nbsp;').'</div>' : '<input type="hidden" name="contato_dept" id="contato_dept" value="'.$linha->contato_cia.'"><input type="text" class="texto" name="dept_nome" size="40" READONLY value="'. nome_cia($linha->contato_cia).'" />').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']), 'Clique o botão <b>selecionar '.strtolower($config['dept']).'</b> à direita para escolher qual '.$config['genero_dept'].' '.strtolower($config['departamento']).' d'.$config['genero_usuario'].' '.$config['usuario'].'.').ucfirst($config['dept']).':'.dicaF().'</td><td nowrap="nowrap"><input type="text" class="texto" name="dept_nome" value="'.$dept_detalhe['dept_nome'].'" size="40" READONLY /><input type="hidden" name="contato_dept" value="'.$dept_detalhe['dept_id'].'" />'.($Aplic->checarModulo('depts', 'acesso') ? botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()') : '').'</td></tr>';
echo '<tr><td align="right">'.dica('Função n'.$config['genero_organizacao'].' '.$config['organizacao'], 'Escreva a função do contato dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'. Embora não tenha impacto no funcionamento do Sistema, facilita a distinção dos contatos.').'Função:'.dicaF().'</td><td nowrap="nowrap"><input type="text" class="texto" name="contato_funcao" value="'.$linha->contato_funcao.'" maxlength="100" size="25" /></td></tr>';
if ($config['militar']<10) echo '<tr><td align="right">'.dica('Arma/Quadro/Sv', 'Escolha na caixa de seleção à direita qual a Arma/Quadro/Sv d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Arma/Quadro/Sv:'.dicaF().'</td><td>'.selecionaVetor($arma, 'contato_arma', 'class="texto" size=1', $linha->contato_arma, true).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'Escreva qual o tipo de contato. Embora não tenha impacto no funcionamento do Sistema, facilita a distinção dos contatos.').'Tipo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_tipo" value="'.$linha->contato_tipo.'" maxlength="50" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'Escreva, caso exista, o código deste contato.').'Código:'.dicaF().'</td><td><input type="text" class="texto" name="contato_codigo" value="'.(isset($linha->contato_codigo) ? $linha->contato_codigo : '').'" size="30" maxlength="255" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Identidade', 'Escreva a identidade do contato.').($config['id_usuario_identidade'] ? '* ' : '').'Identidade:'.dicaF().'</td><td><input type="text" class="texto" name="contato_identidade" id="contato_identidade" value="'.$linha->contato_identidade.'" maxlength="25" size="14" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('CPF', 'Escreva o CPF do contato.').'CPF:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cpf" value="'.$linha->contato_cpf.'" maxlength="14" size="14" onchange="verificarCPF()" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('CNPJ', 'Escreva o CNPJ do contato.').'CNPJ:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cnpj" value="'.$linha->contato_cnpj.'" maxlength="18" size="18" onchange="verificarCNPJ()" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Endereço', 'Escreva o enderço do contato.').'Endereço:'.dicaF().'</td><td><input type="text" class="texto" name="contato_endereco1" value="'.$linha->contato_endereco1.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Complemento do Endereço', 'Escreva o complemento do enderço do contato.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="contato_endereco2" value="'.$linha->contato_endereco2.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Estado', 'Escolha na caixa de seleção à direita o Estado do contato.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'contato_estado', 'class="texto" size=1 onchange="mudar_cidades();"', $linha->contato_estado).'</tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Município', 'Selecione o município do contato.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($linha->contato_estado, 'contato_cidade', 'class="texto" onchange="mudar_comunidades()" style="width:160px;"', '', $linha->contato_cidade, true, false).'</div></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('CEP', 'Escreva o CEP do contato.').'CEP:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cep" value="'.$linha->contato_cep.'" maxlength="11" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('País', 'Escolha na caixa de seleção à direita o país do contato.').'País:'.dicaF().'</td><td>'.selecionaVetor($paises, 'contato_pais', 'size="1" class="texto"',($linha->contato_pais ? $linha->contato_pais : 'BR')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Comercial', 'Escreva o telefone comercial do contato.').'Telefone Comercial:'.dicaF().'</td><td>(<input type="text" class="texto" name="contato_dddtel" value="'.$linha->contato_dddtel.'" maxlength="6" size="1" />) <input type="text" class="texto" name="contato_tel" value="'.$linha->contato_tel.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Residencial', 'Escreva o telefone residencial do contato.').'Telefone Residencial:'.dicaF().'</td><td>(<input type="text" class="texto" name="contato_dddtel2" value="'.$linha->contato_dddtel2.'" maxlength="6" size="1" />) <input type="text" class="texto" name="contato_tel2" value="'.$linha->contato_tel2.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Fax', 'Escreva o Fax do contato.').'Fax:'.dicaF().'</td><td>(<input type="text" class="texto" name="contato_dddfax" value="'.$linha->contato_dddfax.'" maxlength="6" size="1" />) <input type="text" class="texto" name="contato_fax" value="'.$linha->contato_fax.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Celular', 'Escreva o celular do contato.').'Celular:'.dicaF().'</td><td>(<input type="text" class="texto" name="contato_dddcel" value="'.$linha->contato_dddcel.'" maxlength="6" size="1" />) <input type="text" class="texto" name="contato_cel" value="'.$linha->contato_cel.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('E-mail', 'Escreva o e-mail do contato.<br><br>Embora não tenha impacto no funcionamento do Sistema, exceto nas situações em que o mesmo envia mensagens para contatos, facilita a organização dos contatos quando se trabalha com diversas '.$config['organizacao'].'.').'E-mail:'.dicaF().'</td><td nowrap="nowrap"><input type="text" class="texto" name="contato_email" value="'.$linha->contato_email.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('E-mail Alternativo', 'Escreva o e-mail alternativo do contato.').'E-mail alternativo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_email2" value="'.$linha->contato_email2.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Página Web', 'Escreva a página Web do contato.').'Página Web:'.dicaF().'</td><td><input type="text" class="texto" name="contato_url" value="'.$linha->contato_url.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('ICQ', 'Escreva o ICQ do contato.').'ICQ:'.dicaF().'</td><td><input type="text" class="texto" name="contato_icq" value="'.$linha->contato_icq.'" maxlength="20" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('MSN', 'Escreva o MSN do contato.').'MSN:'.dicaF().'</td><td><input type="text" class="texto" name="contato_msn" value="'.$linha->contato_msn.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Yahoo', 'Escreva o Yahoo do contato.').'Yahoo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_yahoo" value="'.$linha->contato_yahoo.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Skype', 'Escreva o Skype do contato.').'Skype:'.dicaF().'</td><td><input type="text" class="texto" name="contato_skype" value="'.$linha->contato_skype.'" maxlength="100" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Jabber', 'Escreva o Jabber do contato.').'Jabber:'.dicaF().'</td><td><input type="text" class="texto" name="contato_jabber" value="'.$linha->contato_jabber.'" maxlength="100" size="25" /></td></tr></tr>';

if ($Aplic->checarModulo('usuarios', 'editar', $Aplic->usuario_id, 'hora_custo')) echo '<tr><td align="right" nowrap="nowrap">'.dica('Custo da Hora', 'O custo da hora de trabalho do contato.').'Custo hora:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="contato_hora_custo" value="'.number_format($linha->contato_hora_custo, 2, ',', '.').'" maxlength="100" size="25" /></td></tr>';
else echo '<input type="hidden" name="contato_hora_custo" value="'.number_format($linha->contato_hora_custo, 2, ',', '.').'" />';

$data = new CData($linha->contato_nascimento);
echo '<tr><td align="right">'.dica('Data de Nascimento', 'Escreva a data de nascimento do contato no formato <b>(dd/mm/aaaa)</b>.').'Data de Nascimento:'.dicaF().'</td><td nowrap="nowrap"><input data-gpweb-cmp="calendario" type="text" class="texto" id="contato_nascimento" name="contato_nascimento" value="'.($linha->contato_nascimento && $linha->contato_nascimento !='0000-00-00' ? $data->format($d) : '').'" maxlength="10" size="25" onkeyup="barra(this)" /></td></tr>';

echo '<tr><td align="right">'.dica('Notas Sobre o Contato', 'Escreva informações extras sobre o contato.').'Notas: '.dicaF().'</td><td><textarea class="texto" name="contato_notas" data-gpweb-cmp="ckeditor" rows="4" cols="40">'.$linha->contato_notas.'</textarea></td></td></tr>';


echo '</table></td>';
echo '<td valign="top" width="50%"><table cellpadding=0 cellspacing=0>';
echo '<tr><td align="right" colspan="3">';
			require_once ($Aplic->getClasseSistema('CampoCustomizados'));
			$campos_customizados = new CampoCustomizados($m, $linha->contato_id, 'editar');
			$campos_customizados->imprimirHTML();
echo '</td></tr>';
echo '</td></tr></table>';
echo '</td></tr>';
echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviar()').'</td><td colspan="2" align="right">'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr>';
echo '</form></table>';
echo estiloFundoCaixa();

?>

<script language="javascript">

function float2moeda(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);
	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	for (var i=0; i< Math.floor((num.length-(1+i))/3); i++) num=num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	ret=num+','+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}

function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}


function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('contato_estado').value,'contato_cidade','combo_cidade', 'class="texto" size=1 style="width:160px;" onchange="mudar_comunidades();"', document.getElementById('contato_cidade').value);
	}

window.cia_id=<?php echo ($cia_detalhe['cia_id'] ? $cia_detalhe['cia_id'] : 0)?>;
window.cia_valor='<?php echo addslashes(($cia_detalhe["cia_nome"] ? $cia_detalhe["cia_nome"] : ''))?>';


function popDept() {
  var f = document.frmEditar;
  if (!f.contato_cia.value) alert("Selecione primeiro uma <?php echo $config['organizacao']?>");
  else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&cia_id='+ f.contato_cia.options[f.contato_cia.selectedIndex].value+'&dept_id='+f.contato_dept.value,'dept','left=0,top=0,height=600,width=400, scrollbars=yes, resizable');
	}


function setDept(cia, chave, val) {
  var f = document.frmEditar;
  if (chave != null) {
    f.contato_dept.value = chave;
    f.dept_nome.value = val;
		}
  else {
    f.contato_dept.value = '';
    f.dept_nome.value = '';
		}
	}


function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('contato_cia').value, 'contato_cia','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"','&nbsp;',1);
	}

function enviar() {
	var form = document.frmEditar;
	var nomeposto=Array("<?php echo implode("\",\"",$posto)?>");
	var valorposto=Array();

	xajax_existe_identidade_ajax(document.getElementById('contato_identidade').value, document.getElementById('contato_id').value);

	<?php foreach ($posto as $valor_posto=> $nome) echo ($nome ? 'valorposto["'.$nome.'"]='.(int)$valor_posto.'; ' : ''); ?>;

	if (form.contato_nascimento.value.length < 10) form.contato_nascimento.value = null;
	else form.contato_nascimento.value=form.contato_nascimento.value.substring(6,10)+'-'+form.contato_nascimento.value.substring(3,5)+'-'+form.contato_nascimento.value.substring(0,2);

	if (form.contato_nomeguerra.value.length < 1) {
		alert( 'Por favor insira um nome de contato válido.' );
		form.contato_nomeguerra.focus();
		}

	else if (form.existe_identidade.value==1) {
      alert('O número de identidade já existe cadastrado!');
      form.contato_identidade.focus();
  		}

	<?php if ($usuario_id!=$Aplic->usuario_id){ ?>
	else if (form.contato_email.value.length < 1 && form.contato_atualizarSolicitado.checked) {
		alert( 'Necessita inserir um e-mail válido antes de utilizar a função de avisar sobre atualização.' );
		form.contato_email.focus();
		}
	<?php }?>

	<?php	if ($config['id_usuario_identidade']) { ?>
  	else if (form.contato_identidade.value.length < 8) {
	    alert("A identidade deverá ser preenchida corretamente");
	    form.contato_identidade.focus();
			}
	<?php } ?>


	else {
		form.contato_posto_valor.value=(valorposto[form.contato_posto.value] != 'undefined' ? valorposto[form.contato_posto.value] : null);

		form.contato_hora_custo.value=moeda2float(form.contato_hora_custo.value);

		form.submit();
		}
	}


function excluir(){
	if (<?php echo ($protegidoExclusaoUsuario ? '1' : '0')?>) alert('Erro ao excluir, por não ser permitido.');
	else {
		var form = document.frmEditar;
		if(confirm('Tem a certeza que deseja excluir este contato?')) {
			form.del.value = '<?php echo $contato_id; ?>';
			form.submit();
			}
		}
	}

function verificarAtualizacao() {
	var form = document.frmEditar;
	if (form.contato_email.value.length < 1 && form.contato_atualizarSolicitado.checked) {
		alert('Necessita inserir um e-mail válido antes de utilizar esta função');
		form.contato_atualizarSolicitado.checked = false;
		form.contato_email.focus();
		}
	}

function barra(objeto){
	if (objeto.value.length == 2 || objeto.value.length ==5) objeto.value = objeto.value+"/";
	}



var NUM_DIGITOS_CPF = 11;
var NUM_DIGITOS_CNPJ = 14;
var NUM_DGT_CNPJ_BASE = 8;

String.prototype.lpad = function (pSize, pCharPad) {
	var str = this;
	var dif = pSize - str.length;
	var ch = String(pCharPad).charAt(0);
	for (; dif > 0; dif--) str = ch + str;
	return (str);
	}
String.prototype.trim = function () {
	return this.replace(/^\s*/, "").replace(/\s*$/, "");
	}

function unformatNumber(pNum) {
	return String(pNum).replace(/\D/g, "").replace(/^0+/, "");
	}

function formatCpfCnpj(pCpfCnpj, pUseSepar, pIsCnpj) {
	if (pIsCnpj == null) pIsCnpj = false;
	if (pUseSepar == null) pUseSepar = true;
	var maxDigitos = pIsCnpj ? NUM_DIGITOS_CNPJ : NUM_DIGITOS_CPF;
	var numero = unformatNumber(pCpfCnpj);
	numero = numero.lpad(maxDigitos, '0');
	if (!pUseSepar) return numero;
	if (pIsCnpj) {
		reCnpj = /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/;
		numero = numero.replace(reCnpj, "$1.$2.$3/$4-$5")
		}
	else {
		reCpf = /(\d{3})(\d{3})(\d{3})(\d{2})$/;
		numero = numero.replace(reCpf, "$1.$2.$3-$4")
		}
	return numero
	}

function dvCpfCnpj(pEfetivo, pIsCnpj) {
	if (pIsCnpj == null) pIsCnpj = false;
	var i, j, k, soma, dv;
	var cicloPeso = pIsCnpj ? NUM_DGT_CNPJ_BASE : NUM_DIGITOS_CPF;
	var maxDigitos = pIsCnpj ? NUM_DIGITOS_CNPJ : NUM_DIGITOS_CPF;
	var calculado = formatCpfCnpj(pEfetivo + "00", false, pIsCnpj);
	calculado = calculado.substring(0, maxDigitos - 2);
	var result = "";
	for (j = 1; j <= 2; j++) {
		k = 2;
		soma = 0;
		for (i = calculado.length - 1; i >= 0; i--) {
			soma += (calculado.charAt(i) - '0') * k;
			k = (k - 1) % cicloPeso + 2
			}
		dv = 11 - soma % 11;
		if (dv > 9) dv = 0;
		calculado += dv;
		result += dv
		}
	return result
	}

function isCpf(pCpf) {
	var numero = formatCpfCnpj(pCpf, false, false);
	if (numero.length > NUM_DIGITOS_CPF) return false;
	var base = numero.substring(0, numero.length - 2);
	var digitos = dvCpfCnpj(base, false);
	var algUnico, i;
	if (numero != "" + base + digitos) return false;
	algUnico = true;
	for (i = 1; algUnico && i < NUM_DIGITOS_CPF; i++) algUnico = (numero.charAt(i - 1) == numero.charAt(i));
	return (!algUnico);
	}

function isCnpj(pCnpj) {
	var numero = formatCpfCnpj(pCnpj, false, true);
	if (numero.length > NUM_DIGITOS_CNPJ) return false;
	var base = numero.substring(0, NUM_DGT_CNPJ_BASE);
	var ordem = numero.substring(NUM_DGT_CNPJ_BASE, 12);
	var digitos = dvCpfCnpj(base + ordem, true);
	var algUnico;
	if (numero != "" + base + ordem + digitos) return false;
	algUnico = numero.charAt(0) != '0';
	for (i = 1; algUnico && i < NUM_DGT_CNPJ_BASE; i++) algUnico = (numero.charAt(i - 1) == numero.charAt(i));
	if (algUnico) return false;
	if (ordem == "0000") return false;
	return (base == "00000000" || parseInt(ordem, 10) <= 300 || base.substring(0, 3) != "000");
	}

function isCpfCnpj(pCpfCnpj) {
	var numero = pCpfCnpj.replace(/\D/g, "");
	if (numero.length > NUM_DIGITOS_CPF) return isCnpj(pCpfCnpj);
	else return isCpf(pCpfCnpj);
	}


function verificarCPF(){
	var cpf=frmEditar.contato_cpf.value;
	if(!isCpf(cpf)){
		alert('CPF inválido!');
		frmEditar.contato_cpf.focus();
		}
	else
	frmEditar.contato_cpf.value=formatCpfCnpj(cpf, true, false);
	}

function verificarCNPJ(){
	var cnpj=frmEditar.contato_cnpj.value;
	if(!isCnpj(cnpj)){
		alert('CNPJ inválido!');
		frmEditar.contato_cnpj.focus();
		}
	else
	frmEditar.contato_cnpj.value=formatCpfCnpj(cnpj, true, true);
	}
</script>
