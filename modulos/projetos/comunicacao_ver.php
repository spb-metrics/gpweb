<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/comunicacao.class.php');
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);
$acessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
$editar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
if (!$projeto_id) {
	$Aplic->setMsg('N�o foi passado um ID de '.$config['projeto'].' ao tentar ver o plano de comunicacao.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


$obj = new CComunicacao();
$obj->load($projeto_id);
$sql = new BDConsulta();




if (!$dialogo) $Aplic->salvarPosicao();


$msg = '';
if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Plano de Comunica��o d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de op��es de visualiza��o').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste bot�o para visualizar dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$projeto_id."\");");
	if ($editar && $podeEditar){
		$km->Add("root","inserir",dica('Inserir','Menu de op��es').'Inserir'.dicaF(), "javascript: void(0);'");
		if (!$obj->projeto_comunicacao_usuario) $km->Add("inserir","inserir_comunicacao",dica('Inserir Plano de Comunica��o','Inserir os detalhes do plano de comunica��o.').'Plano de Comunica��o'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=comunicacao_editar&projeto_id=".$projeto_id."\");");
		if ($obj->projeto_comunicacao_usuario) $km->Add("inserir","inserir_evento",dica('Inserir Eventos','Inserir lista de eventos no plano de comunica��o.').'Inserir Eventos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=comunicacao_evento&projeto_id=".$projeto_id."\");");
		}	
	if ($obj->projeto_comunicacao_usuario){
		$km->Add("root","acao",dica('A��o','Menu de a��es.').'A��o'.dicaF(), "javascript: void(0);'");
		if ($editar && $podeEditar) $km->Add("acao","editar_comunicacao",dica('Editar Plano de Comunica��o','Editar os detalhes do plano de comunica��o.').'Editar Plano de Comunica��o'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=comunicacao_editar&projeto_id=".$projeto_id."\");");
		if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este plano de comunica��o do sistema.').'Excluir Plano de Comunica��o'.dicaF(), "javascript: void(0);' onclick='excluir()");
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para visualizar as op��es de relat�rios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
		$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes do Plano de Comunica��o', 'Visualize os detalhes deste plano de comunica��o.').' Detalhes do Plano de Comunica��o'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=comunicacao_imprimir&dialogo=1&projeto_id=".$projeto_id."\");");
		}	
	echo $km->Render();
	echo '</td></tr></table>';
	}
else {	
	$botoesTitulo = new CBlocoTitulo('Plano de Comunica��o d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	if ($editar && $podeEditar) {
		$botoesTitulo->adicionaBotao('m=projetos&a=comunicacao_editar&projeto_id='.$projeto_id, ($obj->projeto_comunicacao_usuario ? 'editar' : 'inserir'),'',($obj->projeto_comunicacao_usuario ? 'Editar' : 'Inserir').' Plano de Comunica��o',($obj->projeto_comunicacao_usuario ? 'Editar' : 'Inserir').' os detalhes do plano de comunica��o.');
		if ($obj->projeto_comunicacao_usuario) {
			$botoesTitulo->adicionaBotao('m=projetos&a=comunicacao_evento&projeto_id='.$projeto_id, 'inserir eventos','','Inserir Eventos','Inserir lista de eventos no plano de comunica��o.');
			$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este plano de comunica��o.');
			}
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir o Plano de Comunica��o', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir o plano de comunica��o.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=comunicacao_imprimir&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_comunicacao_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';


echo '<table id="tblObjetivos" cellpadding=1 cellspacing=1 width="100%" class="std">';
if ($obj->projeto_comunicacao_descricao) echo '<tr><td align="right">'.dica('Descri��o', 'Descri��o sobre o plano de comunica��o').'Descri��o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_comunicacao_descricao.'</td></tr>';
if ($obj->projeto_comunicacao_usuario) echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel', 'O respons�vel pelo plano de comunica��o.').'Respons�vel:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->projeto_comunicacao_usuario, '','','esquerda').'</td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('projeto_comunicacao', $obj->projeto_comunicacao_projeto, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				
if ($obj->projeto_comunicacao_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data', 'A data em que o plano de comunica��o foi criado ou editado').'Data:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_comunicacao_data).'</td></tr>';
if (!$obj->projeto_comunicacao_usuario) echo '<tr><td colspan=20 class="realce">Ainda n�o h� dados cadastrados</td></tr>';




$sql->adTabela('projeto_comunicacao_evento');
$sql->adCampo('*');
$sql->adOnde('projeto_comunicacao_evento_projeto='.$projeto_id);
$sql->adOrdem('projeto_comunicacao_evento_ordem ASC');
$eventos=$sql->Lista();


if ($eventos && count($eventos)) echo '<tr><td>&nbsp</td><td><table class="tbl1" cellspacing=0 cellpadding=2 border=0><tr><th>Evento</th><th>Objetivo</th><th>Respons�vel</th><th>Publico alvo</th><th>Canal</th><th>Periodicidade</th></tr></tr>';
foreach ($eventos as $evento) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Quem Inseriu</b></td><td>'.nome_funcao('', '', '', '',$evento['projeto_comunicacao_evento_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($evento['projeto_comunicacao_evento_data']).'</td></tr>';
	$dentro .= '</table>';
	echo '<tr>';
	echo '<td>'.($evento['projeto_comunicacao_evento_evento'] ? $evento['projeto_comunicacao_evento_evento'] : '&nbsp;').'</td>';
	echo '<td>'.($evento['projeto_comunicacao_evento_objetivo'] ? $evento['projeto_comunicacao_evento_objetivo'] : '&nbsp;').'</td>';
	echo '<td>'.($evento['projeto_comunicacao_evento_responsavel'] ? $evento['projeto_comunicacao_evento_responsavel'] : '&nbsp;').'</td>';
	echo '<td>'.($evento['projeto_comunicacao_evento_publico'] ? $evento['projeto_comunicacao_evento_publico'] : '&nbsp;').'</td>';
	echo '<td>'.($evento['projeto_comunicacao_evento_canal'] ? $evento['projeto_comunicacao_evento_canal'] : '&nbsp;').'</td>';
	echo '<td>'.($evento['projeto_comunicacao_evento_periodicidade'] ? $evento['projeto_comunicacao_evento_periodicidade'] : '&nbsp;').'</td>';
	echo '</tr>';

	if ($Aplic->profissional){
		//checar se tem eventos de calendario
		$sql->adTabela('projeto_comunicacao_evento_calendario');
		$sql->esqUnir('eventos','eventos', 'projeto_comunicacao_evento_calendario.evento_id=eventos.evento_id');
		$sql->adCampo('eventos.evento_id, evento_titulo,formatar_data(evento_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(evento_fim, \'%d/%m/%Y  %H:%i\') AS fim');
		$sql->adOnde('projeto_comunicacao_evento_id ='.(int)$evento['projeto_comunicacao_evento_id']);	
	  $lista = $sql->Lista();
	  $sql->Limpar();
		foreach ($lista as $linha) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="pop_evento('.$evento['projeto_comunicacao_evento_id'].', '.$linha['evento_id'].');">'.$linha['inicio'].' - '.$linha['fim'].' - '.$linha['evento_titulo'].'</a></td></tr>';
		}
	}
if ($eventos && count($eventos)) echo '</table></td></tr>';





		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">

function pop_evento(projeto_comunicacao_evento_id, evento_id){
	parent.gpwebApp.popUp('Evento de Calend�rio', 800, 600, 'm=calendario&a=ver&dialogo=1&projeto_comunicacao_evento_id='+projeto_comunicacao_evento_id+'&evento_projeto=<?php echo $projeto_id ?>'+(evento_id > 0 ? '&evento_id='+evento_id : ''), null, window);
	}	
	
function excluir() {
	if (confirm('Tem certeza que deseja excluir este plano de comunica��o')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_comunicacao';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}
</script>