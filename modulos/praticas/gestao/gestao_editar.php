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

require_once (BASE_DIR.'/modulos/praticas/gestao/gestao.class.php');

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$pg_id = getParam($_REQUEST, 'pg_id', null);

$msg = '';
$obj = new CGestao();
$obj->load($pg_id);

if (!$podeAdicionar && !$pg_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$podeEditar && $pg_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
if ($pg_id &&!permiteEditarPlanoGestao($obj->pg_acesso, $pg_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');



$sql = new BDConsulta;

$usuarios_selecionados=array();
$depts_selecionados=array();
$cias_selecionadas =array();
if ($pg_id) {
	$sql->adTabela('plano_gestao_usuario');
	$sql->adCampo('plano_gestao_usuario_usuario');
	$sql->adOnde('plano_gestao_usuario_plano = '.(int)$pg_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('plano_gestao_dept');
	$sql->adCampo('plano_gestao_dept_dept');
	$sql->adOnde('plano_gestao_dept_plano ='.(int)$pg_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('plano_gestao_cia');
		$sql->adCampo('plano_gestao_cia_cia');
		$sql->adOnde('plano_gestao_cia_plano = '.(int)$pg_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;


$ttl = $pg_id ? 'Editar Planejamento Estratégico' : 'Adicionar Planejamento Estratégico';
$botoesTitulo = new CBlocoTitulo($ttl, 'planogestao.png', $m, $m.'.'.$u.'.'.$a);
$botoesTitulo->mostrar();



echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="gestao" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_gestao_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="pg_id" id="pg_id" value="'.$pg_id.'" />';
echo '<input name="pg_usuarios" id="pg_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pg_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="plano_gestao_cias"  id="plano_gestao_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($pg_id ? '' : uuid()).'" />';


echo '<input type="hidden" name="pg_usuario_ultima_alteracao" value="'.$obj->pg_usuario_ultima_alteracao.'" />';
echo '<input type="hidden" name="pg_ano" value="'.$obj->pg_ano.'" />';
echo '<input type="hidden" name="pg_modelo" value="'.$obj->pg_modelo.'" />';
echo '<input type="hidden" name="pg_estrut_org" value="'.$obj->pg_estrut_org.'" />';
echo '<input type="hidden" name="pg_fornecedores" value="'.$obj->pg_fornecedores.'" />';
echo '<input type="hidden" name="pg_ultima_alteracao" value="'.$obj->pg_ultima_alteracao.'" />';
echo '<input type="hidden" name="pg_processos_apoio" value="'.$obj->pg_processos_apoio.'" />';
echo '<input type="hidden" name="pg_processos_finalistico" value="'.$obj->pg_processos_finalistico.'" />';
echo '<input type="hidden" name="pg_produtos_servicos" value="'.$obj->pg_produtos_servicos.'" />';
echo '<input type="hidden" name="pg_clientes" value="'.$obj->pg_clientes.'" />';
echo '<input type="hidden" name="pg_posgraduados" value="'.$obj->pg_posgraduados.'" />';
echo '<input type="hidden" name="pg_graduados" value="'.$obj->pg_graduados.'" />';
echo '<input type="hidden" name="pg_nivelmedio" value="'.$obj->pg_nivelmedio.'" />';
echo '<input type="hidden" name="pg_nivelfundamental" value="'.$obj->pg_nivelfundamental.'" />';
echo '<input type="hidden" name="pg_semescolaridade" value="'.$obj->pg_semescolaridade.'" />';
echo '<input type="hidden" name="pg_pessoalinterno" value="'.$obj->pg_pessoalinterno.'" />';
echo '<input type="hidden" name="pg_programas_acoes" value="'.$obj->pg_programas_acoes.'" />';
echo '<input type="hidden" name="pg_premiacoes" value="'.$obj->pg_premiacoes.'" />';


echo estiloTopoCaixa();
echo '<table border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome', 'Preencha neste campo um nome para identificação deste planejamento estratégico.').'Nome:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="pg_nome" style="width:284px" value="'.(isset($obj->pg_nome) ? $obj->pg_nome : '').'"></td></tr>';
echo '<tr><td align=right nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' responsável pelo planejamento estratégico.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'pg_cia', 'class=texto size=1 style="width:288px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

if ($Aplic->profissional) {
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else $saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}


echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por este planejamento estratégico.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="pg_dept" id="pg_dept" value="'.($pg_id ? $obj->pg_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($pg_id ? $obj->pg_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

$saida_depts='';
if (count($depts_selecionados)) {
		$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável', 'Todo planejamento estratégico deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="pg_usuario" name="pg_usuario" value="'.(!$pg_id ? $Aplic->usuario_id : $obj->pg_usuario).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om((!$pg_id ? $Aplic->usuario_id : $obj->pg_usuario), $Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';

$data_inicio = new CData($obj->pg_inicio ? $obj->pg_inicio : date('Y').'-01-01');
$data_fim = new CData($obj->pg_fim ? $obj->pg_fim : date('Y').'-12-31');

echo '<tr><td nowrap="nowrap" align="right">'.dica('Data Inicial', 'Digite ou escolha no calendário a data de início.').'De:'.dicaF().'</td><td align="left"><input type="hidden" name="pg_inicio" id="pg_inicio" value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'pg_inicio\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de início da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td nowrap="nowrap" align="right">'.dica('Data Final', 'Digite ou escolha no calendário a data final.').'Até:'.dicaF().'</td><td align="left"><input type="hidden" name="pg_fim" id="pg_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'pg_fim\', \'data_fim\');" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Texto explicativo para facilitar a compreensão do planejamento estratégico e facilitar futuras pesquisas.').'Descrição:'.dicaF().'</td><td align="left"><textarea data-gpweb-cmp="ckeditor" name="pg_descricao" class="textarea" rows="4" style="width:270px">'.(isset($obj->pg_descricao) ? $obj->pg_descricao : '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O planejamento estratégico pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem ver e editar</li><li><b>Privado</b> - Somente o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'pg_acesso', 'class="texto" style="width:288px;"', ($pg_id ? $obj->pg_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Importar', 'Utilize esta opção caso deseje importar os dados de um planejamento estratégico.').'Importar:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="importar_id" value="" /><input type="text" name="nome_importar" value="" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPG();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="pg_cor" value="'.($obj->pg_cor ? $obj->pg_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->pg_cor ? $obj->pg_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o planejamento estratégico ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="pg_ativo" '.($obj->pg_ativo || !$pg_id ? 'checked="checked"' : '').' /></td></tr>';




echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td>'.(!$dialogo ? '<td align="right">'.botao('cancelar', 'Cancelar', 'Abortar esta operação.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td>' : '').'</tr>';
echo '</form></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('pg_cia').value+'&cias_id_selecionadas='+document.getElementById('plano_gestao_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.plano_gestao_cias.value = organizacao_id_string;
	document.getElementById('plano_gestao_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('plano_gestao_cias').value);
	__buildTooltip();
	}

function popPG() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Planejamento Estratégico", 610, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPG&tabela=plano_gestao&cia_id='+document.getElementById('pg_cia').value, window.setPG, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPG&tabela=plano_gestao&cia_id='+document.getElementById('pg_cia').value, 'Planejamento Estratégico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPG(chave, valor){
	env.importar_id.value=(chave > 0 ? chave : null);
	env.nome_importar.value=valor;
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_cor.value;
	}


function setData( frm_nome, f_data_real, f_data) {

	campo_data=document.getElementById(f_data);
	campo_data_real=document.getElementById(f_data_real);

	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
 			}
   	else{
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}

  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "pg_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) {
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pg_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide();
  	}
  });

	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "pg_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) {
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pg_fim").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal2.hide();
  	}
  });



var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.pg_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


function popGerente() {
		if (window.parent.gpwebApp)parent.gpwebApp.popUp("Responsável", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_cia').value+'&usuario_id='+document.getElementById('pg_usuario').value, window.setGerente, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_cia').value+'&usuario_id='+document.getElementById('pg_usuario').value, 'Gerente','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('pg_usuario').value=usuario_id;
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function enviarDados() {
	var f = document.env;

	if (f.pg_nome.value.length < 1) {
		alert( "Insira um nome para o planejamento estratégico." );
		f.pg_nome.focus();
		return;
		}
	else if (f.pg_fim.value < f.pg_inicio.value) {
		alert( "A data de final não pode ser anterior a inicial." );
		f.data_inicio.focus();
		return;
		}
	else f.submit();
	}

function excluir() {
	if (confirm( "Excluir este planejamento estratégico?" )) {
		var f = document.env;
		f.del.value='1';
		f.submit();
		}
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('pg_cia').value,'pg_cia','combo_cia', 'class="texto" size=1 style="width:288px;" onchange="javascript:mudar_om();"');
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pg_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_dept').value+'&cia_id='+document.getElementById('pg_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_dept').value+'&cia_id='+document.getElementById('pg_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('pg_cia').value=cia_id;
	document.getElementById('pg_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

</script>
