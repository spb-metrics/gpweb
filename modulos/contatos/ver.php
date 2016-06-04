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

$contato_id = intval(getParam($_REQUEST, 'contato_id', 0));
if (!$dialogo) $Aplic->salvarPosicao();
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$msg = '';
$sql = new BDConsulta;
$sql->adTabela('contatos');
$sql->esqUnir('estado', 'estado', 'contato_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'CAST( contato_cidade AS '.( config('tipoBd')=='mysql' ? 'UNSIGNED ' : '' ).' INTEGER) = municipio_id');
$sql->adCampo('estado_nome, municipio_nome');
$sql->adOnde('contato_id='.$contato_id);
$endereco=$sql->Linha();
$sql->limpar();


$obj = new CContato();
$podeExcluir = $obj->podeExcluir($msg, $contato_id);
$eh_usuario = $obj->ehUsuario($contato_id);
if (!$obj->load($contato_id) && $contato_id > 0) {
	$Aplic->setMsg('Contatos');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=contatos');
	} 
elseif ($obj->contato_privado && $obj->contato_dono != $Aplic->usuario_id && $obj->contato_dono && $contato_id) $Aplic->redirecionar('m=publico&a=acesso_negado');

$paises = getPais('Paises');
$cia_detalhe = $obj->getCiaDetalhes();
$dept_detalhe = $obj->getDetalhesProfundos();
$usuario=$obj->ehUsuario();

if (!$dialogo && !$Aplic->profissional) {
$botoesTitulo = new CBlocoTitulo('Detalhes do Contato', 'contatos.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=contatos', 'lista','','Lista de Contatos','Visualizar a lista de contatos.');
if (($obj->contato_dono==$Aplic->usuario_id || $usuario=$Aplic->usuario_id || $Aplic->usuario_super_admin) && $podeEditar && $contato_id) $botoesTitulo->adicionaBotao('m=contatos&a=editar&contato_id='.$contato_id, 'editar','','Editar Contato','Editar os dados deste contato.');
if (($obj->contato_dono==$Aplic->usuario_id || $Aplic->usuario_super_admin) && $podeExcluir && $contato_id) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir Contato','Excluir este contato.');
$botoesTitulo->mostrar();
echo estiloTopoCaixa();
}


if (!$dialogo && $Aplic->profissional){	
	$botoesTitulo = new CBlocoTitulo('Detalhes do Contato', 'contatos.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_links",dica('Lista de Contato','Visualizar a lista de todos os contato cadastrados.').'Lista de Contato'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos\");");
	
	
	if ($podeAdicionar) {
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_link",dica('Novo Contato', 'Criar um novo contato.').'Novo Contato'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos&a=editar\");");
		}	
		
	$editar=((($obj->contato_dono==$Aplic->usuario_id || $usuario=$Aplic->usuario_id || $Aplic->usuario_super_admin) && $podeEditar && $contato_id) ? true : false);
	$excluir=((($obj->contato_dono==$Aplic->usuario_id || $Aplic->usuario_super_admin) && $podeExcluir && $contato_id) ? true : false);	
		
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");

	if ($editar) $km->Add("acao","acao_editar",dica('Editar Contato','Editar os detalhes deste contato.').'Editar Contato'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos&a=editar&contato_id=".(int)$contato_id."\");");
	if ($excluir) $km->Add("acao","acao_excluir",dica('Excluir Contato','Excluir este contato do sistema.').'Excluir Contato'.dicaF(), "javascript: void(0);' onclick='excluir()");

	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes deste contato_id', 'Visualize os detalhes deste contato.').' Detalhes deste Contato'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&contato_id=".$contato_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}
	
	
echo '<form name="frmEditar" method="post">';
echo '<input type="hidden" name="m" value="contatos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_contato_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="contato_id" value="'.$contato_id.'" />';
echo '<input type="hidden" name="contato_dono" value="'.($obj->contato_dono ? $obj->contato_dono : $Aplic->usuario_id).'" />';
echo '</form>';


echo '<table cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%" ><tr>';
echo '<tr><td valign="top" width="50%"><table cellpadding="1" cellspacing="1" class="details" width="100%">';

echo '<tr><td align="left" colspan=2><b>'.$obj->contato_posto.' '.$obj->contato_nomeguerra.'</b></td></tr>';
if ($obj->contato_nomecompleto) echo '<tr><td align="right" width="150" nowrap="nowrap">'.dica('Nome Completo', 'Nome completo do contato.').'Nome completo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_nomecompleto.'</td></tr>';

if ($obj->contato_cia){
	echo '<tr><td align="right" width="150">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' do contato.').ucfirst($config['organizacao']).':'.dicaF().'</td><td  class="realce" nowrap="nowrap">'.link_cia($obj->contato_cia).'</a></td></tr>';
	}
if ($dept_detalhe['dept_nome']) echo '<tr><td align="right" width="150">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' do contato dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').$config['departamento'].':'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$dept_detalhe['dept_nome'].'</td></tr>';
if ($obj->contato_funcao) echo '<tr><td align="right" width="150">'.dica('Cargo/Função', 'O Cargo/Função do contato dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Função:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_funcao.'</td></tr>';
if ($obj->contato_arma) echo '<tr><td align="right" width="150">'.dica('Arma/Quadro/Sv', 'A Arma/Quadro/Sv do contato.').'Arma/Quadro/Sv:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_arma.'</td></tr>';
if ($obj->contato_tipo) echo '<tr><td align="right" width="150">'.dica('Tipo', 'O tipo do contato.<br><br>Não tem relevância para o '.$config['gpweb'].' mas pode facilitar na catalogação dos contatos.').'Tipo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_tipo.'</td></tr>';
if ($obj->contato_codigo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'Código do contato.').'Código:'.dicaF().'</td><td class="realce" width="100%">'.$obj->contato_codigo.'</td></tr>';
if ($obj->contato_identidade) echo '<tr><td align="right" width="150">'.dica('Identidade', 'A identidade do contato.').'Identidade:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$obj->contato_identidade.'</td></tr>';
if ($obj->contato_cpf) echo '<tr><td align="right" width="150">'.dica('CPF', 'O CPF do contato.<br><br>Não tem relevância para o '.$config['gpweb'].' mas pode facilitar na catalogação dos contatos.').'CPF:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$obj->contato_cpf.'</td></tr>';
if ($obj->contato_cnpj) echo '<tr><td align="right" width="150">'.dica('CNPJ', 'O CNPJ do contato.<br><br>Não tem relevância para o '.$config['gpweb'].' mas pode facilitar na catalogação dos contatos.').'CNPJ:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$obj->contato_cnpj.'</td></tr>';
if ($obj->contato_endereco1 || $obj->contato_endereco2 || $obj->contato_cidade || $obj->contato_estado) echo '<tr><td align="right" valign="top" width="150">'.dica('Endereço', 'O endereço do contato.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_endereco1.($obj->contato_endereco1 ? '<br />' :'').$obj->contato_endereco2.($obj->contato_endereco2 ? '<br />' :'').$endereco['municipio_nome'].' '.$endereco['estado_nome'].' '.$obj->contato_cep.($obj->contato_cidade || $obj->contato_estado || $obj->contato_cep ? '<br />' :'').($paises[$obj->contato_pais] ? $paises[$obj->contato_pais] : $obj->contato_pais).'</td></tr>';
if ($obj->contato_endereco1 || $obj->contato_endereco2 || $obj->contato_cidade || $obj->contato_estado) echo '<tr><td align="right" width="150">'.dica('Visualizar Endereço', 'Clique no símbolo do Google Maps à direita para visualizar o endereço do contato.').'Visualizar Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.dica('Google Maps', 'Clique nesta imagem para visualizar no Google Maps, aberto em uma nova janela, o endereço do contato.').'<a target="_blank" href="http://maps.google.com/maps?q='.$obj->contato_endereco1.'+'.$obj->contato_endereco2.'+'.$endereco['municipio_nome'].'+'.$obj->contato_estado.'+'.$obj->contato_cep.'+'.$obj->contato_pais.'"><img align="left" src="'.acharImagem('googlemaps.gif').'" width="55" height="22" alt="Achar no Google Maps" /></a>'.dicaF().'</td></tr>';
if ($obj->contato_tel) echo '<tr><td align="right" width="150">'.dica('Telefone Comercial', 'O telefone comercial do contato, para se comunicar com o mesmo.').'Telefone Comercial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->contato_dddtel ? '('.$obj->contato_dddtel.') ' :'').$obj->contato_tel.'</td></tr>';
if ($obj->contato_tel2) echo '<tr><td align="right" width="150">'.dica('Telefone Residencial', 'O telefone residencial do contato, para se comunicar com o mesmo.').'Telefone Residencial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->contato_dddtel2 ? '('.$obj->contato_dddtel2.') ' :'').$obj->contato_tel2.'</td></tr>';
if ($obj->contato_fax) echo '<tr><td align="right" width="150">'.dica('Fax', 'O fax do contato, para se comunicar com o mesmo.').'Fax:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->contato_dddfax ? '('.$obj->contato_dddfax.') ' :'').$obj->contato_fax.'</td></tr>';
if ($obj->contato_cel) echo '<tr><td align="right" width="150">'.dica('Celular', 'O celular do contato, para se comunicar com o mesmo.').'Celular:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->contato_dddcel ? '('.$obj->contato_dddcel.') ' :'').$obj->contato_cel.'</td></tr>';
if ($obj->contato_email) echo '<tr><td align="right" width="150">'. dica('E-mail', 'O e-mail do contato para se comunicar com o mesmo.').'E-mail:'.dicaF().'</td><td class="realce" nowrap="nowrap">'.link_email($obj->contato_email, $contato_id).'</td></tr>';
if ($obj->contato_email2) echo '<tr><td align="right" width="150">'. dica('E-mail Alternativo', 'O E-mail alternativo do contato, para se comunicar com o mesmo.').'E-mail alternativo:'.dicaF().'</td><td class="realce" nowrap="nowrap">'.link_email($obj->contato_email2, $contato_id).'</td></tr>';
if ($obj->contato_url) echo '<tr><td align="right" width="150">'.dica('Página Web', 'A página na Internet do contato.').'Página Web:'.dicaF().'</td><td nowrap="nowrap" class="realce"><a href="'.$obj->contato_url.'">'.$obj->contato_url.'</a></td></tr>';
if ($obj->contato_icq) echo '<tr><td align="right" width="150">'.dica('ICQ', 'A conta ICQ do contato, para se comunicar com o mesmo.').'ICQ:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="http://web.icq.com/whitepages/message_me?uin="'.$obj->contato_icq.'"&action=message">'.$obj->contato_icq.'</a></td></tr>';
if ($obj->contato_msn) echo '<tr><td align="right" width="150">'.dica('MSN', 'A conta MSN do contato, para se comunicar com o mesmo.').'MSN:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="msnim:chat?contact='.$obj->contato_msn.'">'.$obj->contato_msn.'</a></td></tr>';
if ($obj->contato_yahoo) echo '<tr><td align="right" width="150">'.dica('Yahoo', 'A conta Yahoo do contato, para entrar em contato com o mesmo.').'Yahoo:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="ymsgr:sendim?'.$obj->contato_yahoo.'">'.$obj->contato_yahoo.'</a></td></tr>';
if ($obj->contato_skype) echo '<tr><td align="right" width="150">'.dica('Skype', 'A conta Skype do contato, para se comunicar com o mesmo.').'Skype:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="skype:'.$obj->contato_skype.'?call">'.$obj->contato_skype.'</a></td></tr>';
if ($obj->contato_jabber) echo '<tr><td align="right" width="150">'.dica('Jabber', 'A conta Jabber do contato, para se comunicar com o mesmo.').'Jabber:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_jabber.'</td></tr>';
if ($obj->contato_hora_custo && $Aplic->checarModulo('usuarios', 'acesso', $Aplic->usuario_id, 'hora_custo')) echo '<tr><td align="right" width="150">'.dica('Custo da Hora', 'O custo da hora de trabalho do contato.').'Custo hora:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format($obj->contato_hora_custo, 2, ',', '.').'</td></tr>';


$data = new CData($obj->contato_nascimento);
if ($obj->contato_nascimento && $obj->contato_nascimento!='0000-00-00') echo '<tr><td align="right" width="150">'.dica('Aniversário', 'O aniversário do contato.').'Aniversário:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$data->format($df).'</td></tr>';		
if ($obj->contato_notas) echo '<tr><td align="right" width="150">'.dica('Nota', 'Informação extra sobre o contato.').'Nota:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_notas.'</td></tr>';
echo '</table></td>';
echo '<td valign="top" width="50%" valign="top"><table width="100%" valign="top">';

if ($obj->contato_chave_atualizacao || $obj->contato_pedido_atualizacao || $obj->contato_ultima_atualizacao) {
	echo '<tr><td align="right" colspan="2"><table cellpadding=0 cellspacing=0><tr><td colspan=2 align="center"><b>Atualização</b></td></tr>';
	if ($obj->contato_chave_atualizacao) echo '<tr><td align="right" width="100" nowrap="nowrap">Aguardando:</td><td align="left"><input type="checkbox" value="1" name="contato_pedido_atualizacao" READONLY '.($obj->contato_chave_atualizacao ? 'checked="checked"' : '').' /></td></tr>';	
	$ultimo_pedido = new CData($obj->contato_pedido_atualizacao);
	if ($obj->contato_pedido_atualizacao) echo '<tr><td align="right" nowrap="nowrap">Último Pedido:</td><td align="left" nowrap="nowrap">'.($obj->contato_pedido_atualizacao ? $ultimo_pedido->format($df.' '.$tf) : '').'</td></tr>';	
	$ultimo_atualizado = new CData($obj->contato_ultima_atualizacao);
	if ($obj->contato_ultima_atualizacao) echo '<tr><td align="right" width="100" nowrap="nowrap">Atualização:</td><td align="left" nowrap="nowrap">'.(($obj->contato_ultima_atualizacao && !($obj->contato_ultima_atualizacao == 0)) ? $ultimo_atualizado->format($df.' '.$tf) : '').'</td></tr>';
	echo '</table></td></tr>';
	}
	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $obj->contato_id, 'ver');
if ($campos_customizados->count()) echo $campos_customizados->imprimirHTML();
echo '</table></td></tr>';
if (!$dialogo) echo '<tr><td>'.botao('voltar', 'Voltar', 'Ir para a lista de contatos.','','url_passar(0, \'m=contatos\');').'</td></tr>';
else echo '<script>self.print();</script>';
echo '</form></table>';
if (!$dialogo) echo estiloFundoCaixa();
?>
<script language="JavaScript">
function excluir(){
  var form = document.frmEditar;
  if(confirm( 'Tem certeza que deseja excluir este contao?')) {
    form.del.value = '<?php echo $contato_id; ?>';
    form.submit();
  	}
	}
</script>
