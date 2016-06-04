<?php  
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$tipo=getParam($_REQUEST, 'tipo', 0);
$destino=getParam($_REQUEST, 'destino', 0);
$inserir=getParam($_REQUEST, 'inserir', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);
$novo_favorito=getParam($_REQUEST, 'novo_favorito', 0);
$excluir_favorito=getParam($_REQUEST, 'excluir_favorito', array());
$favorito_id=getParam($_REQUEST, 'favorito_id', 0);
$novo_nome_favorito=getParam($_REQUEST, 'novo_nome_favorito', '');
$idnome_favorito=getParam($_REQUEST, 'idnome_favorito', 0);
$campos_escolhidos=getParam($_REQUEST, 'campos_escolhidos', array());
$brainstorm=getParam($_REQUEST, 'brainstorm', 0);

$plano_acao=getParam($_REQUEST, 'plano_acao', 0);
$projeto=getParam($_REQUEST, 'projeto', 0);
$pratica=getParam($_REQUEST, 'pratica', 0);
$indicador=getParam($_REQUEST, 'indicador', 0);
$checklist=getParam($_REQUEST, 'checklist', 0);
$objetivo=getParam($_REQUEST, 'objetivo', 0);
$fator=getParam($_REQUEST, 'fator', 0);
$estrategia=getParam($_REQUEST, 'estrategia', 0);


$pg_id=getParam($_REQUEST, 'pg_id', 0);
$ano=getParam($_REQUEST, 'pg_ano', 0);
$cia_id=getParam($_REQUEST, 'cia_id', 0);

//ver($_REQUEST);

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);

$sql = new BDConsulta;

if (!$pg_id && $cia_id && $ano){
	//selecionar um ID
	$sql->adTabela('plano_gestao');
	$sql->adCampo('pg_id');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	$sql->adOnde('pg_ano='.$ano);
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	$pg_id=$sql->Resultado();
	$sql->limpar();
	if (!$pg_id) $pg_id=0;
	}

$anos=array();
if ($cia_id){
	$sql->adTabela('plano_gestao');
	$sql->adCampo('DISTINCT pg_id, pg_ano');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	$sql->adOrdem('pg_ano DESC');
	$listaanos=$sql->Lista();
	$sql->limpar();
	foreach ($listaanos AS $ano1) $anos[(int)$ano1['pg_id']]=(int)$ano1['pg_ano'];
	}


if ($idnome_favorito && $alterar){
	$sql->adTabela('favoritos');
	$sql->adCampo('descricao');
	$sql->adOnde('criador_id='.$Aplic->usuario_id);
	$sql->adOnde('favorito_id='.$idnome_favorito);
	$nome_modificar = $sql->Resultado();
	$sql->Limpar();
  }

if ($novo_nome_favorito && $favorito_id)	{
	$sql->adTabela('favoritos');
	$sql->adAtualizar('descricao', $novo_nome_favorito);
	$sql->adOnde('criador_id='.$Aplic->usuario_id);
	$sql->adOnde('favorito_id='.$favorito_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela favoritos!'.$bd->stderr(true));
	$sql->limpar();	
	}

if ($excluir_favorito)	{
	$sql->setExcluir('favoritos_lista');
	$sql->adOnde('favorito_id IN ('.implode(',',(array)$excluir_favorito).')');
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela favoritos_lista!'.$bd->stderr(true));
	$sql->limpar();	
	$sql->setExcluir('favoritos');
	$sql->adOnde('criador_id='.$Aplic->usuario_id);
	$sql->adOnde('favorito_id IN ('.implode(',',(array)$excluir_favorito).')');
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela favoritos!'.$bd->stderr(true));
	$sql->limpar();
	}
if ($novo_favorito){
	$sql->adTabela('favoritos');
	$sql->adInserir('criador_id', $Aplic->usuario_id);
	$sql->adInserir('descricao', $novo_favorito);
	$sql->adInserir('unidade_id', $Aplic->usuario_cia);
	if ($projeto) $sql->adInserir('projeto', $projeto);
	if ($plano_acao) $sql->adInserir('plano_acao', $plano_acao);
	if ($fator) $sql->adInserir('fator', $fator);
	if ($pratica) $sql->adInserir('pratica', $pratica);
	if ($indicador) $sql->adInserir('indicador', $indicador);
	if ($checklist) $sql->adInserir('checklist', $checklist);
	if ($objetivo) $sql->adInserir('objetivo', $objetivo);
	if ($estrategia) $sql->adInserir('estrategia', $estrategia);
	if ($brainstorm) $sql->adInserir('brainstorm', $brainstorm);
	
	
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela favorito');
	$sql->limpar();
	}	

if (count($campos_escolhidos)){
	$sql->setExcluir('favoritos_lista');
	$sql->adOnde('favorito_id = '.$favorito_id);
	if (!$sql->exec()) die('Erro ao excluir de usuariofavorito'.$bd->stderr(true));
	$sql->limpar();
	foreach((array)$campos_escolhidos AS $chave => $valor){ 	
		if ($valor){
			$sql->adTabela('favoritos_lista');
			$sql->adInserir('campo_id', $valor);
			$sql->adInserir('favorito_id', $favorito_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}	
	
echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="favoritos">';
echo '<input type=hidden id="m" name="m" value="publico">';	
echo '<input type=hidden name="destino" id="destino" value="'.$destino.'">';	
echo '<input type=hidden name="favorito_id" id="favorito_id" value="'.$idnome_favorito.'">';	
echo '<input type=hidden name="tipo" id="tipo" value="'.$tipo.'">';	
echo '<input type=hidden name="inserir" id="inserir" value="">';	
echo '<input type=hidden name="alterar" id="alterar" value="">';
echo '<input type=hidden name="excluir_favorito" id="excluir_favorito" value="">';	
echo '<input type=hidden name="idnome_favorito" id="idnome_favorito" value="">';	
echo '<input type=hidden name="plano_acao" id="plano_acao" value="'.$plano_acao.'">';	
echo '<input type=hidden name="fator" id="fator" value="'.$fator.'">';	
echo '<input type=hidden name="projeto" id="projeto" value="'.$projeto.'">';	
echo '<input type=hidden name="pratica" id="pratica" value="'.$pratica.'">';	
echo '<input type=hidden name="indicador" id="indicador" value="'.$indicador.'">';	
echo '<input type=hidden name="checklist" id="checklist" value="'.$checklist.'">';
echo '<input type=hidden name="objetivo" id="objetivo" value="'.$objetivo.'">';	
echo '<input type=hidden name="estrategia" id="estrategia" value="'.$estrategia.'">';	
echo '<input type=hidden name="brainstorm" id="brainstorm" value="'.$brainstorm.'">';	



echo estiloTopoCaixa(); 
echo '<table width="100%" class="std" align="center" border=0 cellspacing=0 cellpadding=0>';
echo '<tr><td width="200"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Favoritos', 'Grupos de favoritos cadastrados.').'<b>Favoritos</b>'.dicaF().'&nbsp;</legend>';
echo '<select name=listafavorito[] id=listafavorito size=12 style="width:100%;" multiple ondblClick="">';

$sql->adTabela('favoritos');
$sql->adCampo('favorito_id, descricao');
$sql->adOnde('criador_id='.$Aplic->usuario_id);
if ($projeto)$sql->adOnde('projeto=1');
if ($pratica)$sql->adOnde('pratica=1');
if ($indicador)$sql->adOnde('indicador=1');
if ($checklist)$sql->adOnde('checklist=1');
if ($objetivo)$sql->adOnde('objetivo=1');
if ($estrategia)$sql->adOnde('estrategia=1');
if ($plano_acao)$sql->adOnde('plano_acao=1');
if ($fator)$sql->adOnde('fator=1');
if ($brainstorm)$sql->adOnde('brainstorm=1');
$sql_resultado = $sql->Lista();
$sql->Limpar();

foreach ($sql_resultado AS $linha) echo '<option value="'.$linha['favorito_id'].'">'.$linha['descricao'].'</option>';
echo '</option></select></fieldset></td></tr>';
echo '<tr><td>'; 
if (!$inserir && !$alterar) {
		echo '<table><tr>';
		echo '<td>'.dica('Excluir','Clique neste botão para excluir favoritos da caixa de seleção acima.<br><br>Para excluir múltiplos favoritos, selecione estes com a tecla CTRL pressionada.').'<a class="botao" href="javascript:void(0);" onclick="excluir();"><span><b>excluir</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Inserir','Clique neste botão para inserir um novo favorito.<BR><BR>Após criar um novo favorito, clique em EDITAR para selecionar os componentes deste favorito.').'<a class="botao" href="javascript:void(0);" onclick="env.inserir.value=1; env.submit();"><span><b>inserir</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Editar','Clique neste botão para editar um favorito da caixa de seleção acima.<BR><BR>Além de poder mudar o nome do favorito, é possível selecionar na tabela ao fundo desta página os componentes deste favorito.').'<a class="botao" href="javascript:void(0);" onclick="editar();"><span><b>editar</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Voltar','Clique neste botão para voltar à tela anterior.').'<a class="botao" href="javascript:void(0);" onclick="voltar();"><span><b>voltar</b></span></a>'.dicaF().'</td></tr></table>';	
		} 
else if ($inserir){
		echo '<table width="100%"><tr><td width="170">&nbsp;<b>Nome:</b><input type=text class="texto" name="novo_favorito" id="novo_favorito" style="width:100px"></td>';
		echo '<td width="70%">'.dica('Confirmar','Clique neste botão para confirmar a inserção do novo favorito.<BR><BR>Após criar um novo favorito, clique em ALTERAR para selecionar os integrantes deste favorito.').'<a class="botao" href="javascript:void(0);" onclick="env.submit();"><span><b>confirmar</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Cancelar','Clique neste botão para cancelar a inserção do novo favorito.').'<a class="botao" href="javascript:void(0);" onclick="cancelar(\'novo_favorito\');"><span><b>cancelar</b></span></a>'.dicaF().'</td></tr></table>';		
		} 
else {
		//alterar nome 
		echo '<table width="100%"><tr><td>&nbsp;<b>Nome:</b> <input type=text class="texto" name="novo_nome_favorito" id="novo_nome_favorito"  value="'.$nome_modificar.'" style="width:100pt"></td>';
		echo '<td>'.dica('Confirmar','Clique neste botão para confirmar a alteração de um favorito.').'<a class="botao" href="javascript:void(0);" onclick="selecionar();"><span><b>confirmar</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Cancelar','Clique neste botão para cancelar a alteração deste favorito.').'<a class="botao" href="javascript:void(0);" onclick="cancelar(\'novo_nome_favorito\');"><span><b>cancelar</b></span></a>'.dicaF().'</td></tr></table>';	
		
		} 
echo '</td></tr></table>';
echo estiloFundoCaixa();

if ($idnome_favorito){ 	
	echo estiloTopoCaixa(); 
	echo '<table align="center" border=0 width="100%" cellpadding=0 cellspacing=4 class="std">';
	
	$sql->adTabela('favoritos_lista');
	$sql->esqUnir('favoritos','favoritos','favoritos.favorito_id=favoritos_lista.favorito_id');
	if ($pratica){
		$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = praticas.pratica_cia');
	 	$sql->adCampo('praticas.pratica_id AS campo, pratica_nome AS nome, cia_nome');
	 	}
	
	if ($checklist){
		$sql->esqUnir('checklist', 'checklist', 'checklist.checklist_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = checklist_cia');
	 	$sql->adCampo('checklist.checklist_id AS campo, checklist_nome AS nome, cia_nome');
	 	} 	
	
	if ($indicador){
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pratica_indicador.pratica_indicador_cia');
	 	$sql->adCampo('pratica_indicador.pratica_indicador_id AS campo, pratica_indicador_nome AS nome, cia_nome');
	 	} 	
	
	if ($fator){
		$sql->esqUnir('fatores_criticos', 'fatores_criticos', 'fatores_criticos.pg_fator_critico_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_fator_critico_cia');
	 	$sql->adCampo('fatores_criticos.pg_fator_critico_id AS campo, pg_fator_critico_nome AS nome, cia_nome');
	 	}
	 	 	
	if ($plano_acao){
		$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = plano_acao_cia');
	 	$sql->adCampo('plano_acao.plano_acao_id AS campo, plano_acao_nome AS nome, cia_nome');
	 	}
	if ($projeto){
		$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = projetos.projeto_cia');
	 	$sql->adCampo('projetos.projeto_id AS campo, projeto_nome AS nome, cia_nome');
	 	} 	

	if ($objetivo){
		$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'objetivos_estrategicos.pg_objetivo_estrategico_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_objetivo_estrategico_cia');
	 	$sql->adCampo('objetivos_estrategicos.pg_objetivo_estrategico_id AS campo, pg_objetivo_estrategico_nome AS nome, cia_nome');
	 	} 	
	if ($estrategia){
		$sql->esqUnir('estrategias', 'estrategias', 'estrategias.pg_estrategia_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_estrategia_cia');
	 	$sql->adCampo('estrategias.pg_estrategia_id AS campo, pg_estrategia_nome AS nome, cia_nome');
	 	} 
	 	
	if ($brainstorm){
		$sql->esqUnir('brainstorm', 'brainstorm', 'brainstorm.brainstorm_id = favoritos_lista.campo_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = brainstorm_cia');
	 	$sql->adCampo('brainstorm.brainstorm_id AS campo, brainstorm_nome AS nome, cia_nome');
	 	} 	
	 		 	
  $sql->adOnde('favoritos.favorito_id='.(int)$idnome_favorito);
	$sql->adOrdem('descricao ASC');
	$lista=$sql->Lista();
	$sql->Limpar();
	$campos_escolhidos=array();
	foreach($lista AS $linha) $campos_escolhidos[$linha['campo']]=$linha['nome'].($Aplic->getPref('om_usuario') && $linha['cia_nome'] ? ' - '.$linha['cia_nome']: '');
	
	
	if ($pratica){
		$sql->adTabela('praticas');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = praticas.pratica_cia');
	 	$sql->adCampo('praticas.pratica_id AS campo, pratica_nome AS nome, cia_nome');
	 	$sql->adOnde('pratica_cia='.(int)$Aplic->usuario_cia);
	 	}
	if ($fator){
		$sql->adTabela('fatores_criticos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_fator_critico_cia');
	 	$sql->adCampo('pg_fator_critico_id AS campo, pg_fator_critico_nome AS nome, cia_nome');
	 	$sql->adOnde('pg_fator_critico_cia='.(int)$Aplic->usuario_cia);
	 	}	
	if ($plano_acao){
		$sql->adTabela('plano_acao');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = plano_acao_cia');
	 	$sql->adCampo('plano_acao_id AS campo, plano_acao_nome AS nome, cia_nome');
	 	$sql->adOnde('plano_acao_cia='.(int)$Aplic->usuario_cia);
	 	} 	 
	if ($projeto){
		$sql->adTabela('projetos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = projetos.projeto_cia');
	 	$sql->adCampo('projetos.projeto_id AS campo, projeto_nome AS nome, cia_nome');
	 	$sql->adOnde('projeto_cia='.(int)$Aplic->usuario_cia);
	 	} 	
	if ($indicador){
		$sql->adTabela('pratica_indicador');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pratica_indicador.pratica_indicador_cia');
	 	$sql->adCampo('pratica_indicador.pratica_indicador_id AS campo, pratica_indicador_nome AS nome, cia_nome');
	 	$sql->adOnde('pratica_indicador_cia='.(int)$Aplic->usuario_cia);
	 	} 
	if ($checklist){
		$sql->adTabela('checklist');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = checklist_cia');
	 	$sql->adCampo('checklist.checklist_id AS campo, checklist_nome AS nome, cia_nome');
	 	$sql->adOnde('checklist_cia='.(int)$Aplic->usuario_cia);
	 	} 	
	if ($objetivo){
		$sql->adTabela('objetivos_estrategicos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_objetivo_estrategico_cia');
	 	$sql->adCampo('objetivos_estrategicos.pg_objetivo_estrategico_id AS campo, pg_objetivo_estrategico_nome AS nome, cia_nome');
	 	$sql->adOnde('pg_objetivo_estrategico_cia='.(int)$Aplic->usuario_cia);
	 	} 	
	if ($estrategia){
		$sql->adTabela('estrategias');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_estrategia_cia');
	 	$sql->adCampo('estrategias.pg_estrategia_id AS campo, pg_estrategia_nome AS nome, cia_nome');
	 	$sql->adOnde('pg_estrategia_cia='.(int)$Aplic->usuario_cia);
	 	} 	 	
	if ($brainstorm){
		$sql->adTabela('brainstorm');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = brainstorm_cia');
	 	$sql->adCampo('brainstorm_id AS campo,brainstorm_nome AS nome, cia_nome');
	 	$sql->adOnde('brainstorm_cia='.(int)$Aplic->usuario_cia);
	 	} 	
	$sql->adOrdem('nome ASC');
	$lista=$sql->Lista();
	$sql->Limpar();
	
	$campos_dispiniveis=array();
	foreach($lista AS $linha) $campos_dispiniveis[$linha['campo']]=$linha['nome'].($Aplic->getPref('om_usuario') && $linha['cia_nome'] ? ' - '.$linha['cia_nome']: '');
	
	echo '<tr><td colspan=20><table><tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia_disponiveis">'.selecionar_om($Aplic->usuario_cia, 'cia_disponiveis', 'class=texto size=1 style="width:300px;" onchange="mudar_om_disponiveis();"','',1).'</div></td><td><a href="javascript:void(0);" onclick="'.($objetivo || $estrategia ? 'atualizar_anos();' : '') .'mudar_disponiveis()">'.imagem('icones/atualizar.png','Atualizar Disponíveis','Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de disponíveis.').'</a></td></tr></table></td></tr>';
	
	if ($objetivo || $estrategia) echo '<tr><td colspan=20><table><tr><td align=right>'.dica('Seleção do Ano', 'Selecione o ano d'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].' ao qual esta estratégia está vinculada.').'Ano:'.dicaF().'</td><td><div id="combo_ano">'.selecionaVetor($anos, 'pg_id', 'class="texto" size=1 onchange="mudar_disponiveis()"', $pg_id).'</div></td></tr></table></td></tr>';
	
	
	echo '<tr><td>'.dica('Disponíveis', 'Disponíveis para fazerem parte do grupo de favoritos.').'Disponíveis'.dicaF().'</td><td>'.dica('Pertencentes', 'Lista dos pertencentes a ao grupo de favoritos').'Pertencentes'.dicaF().'</td></tr>';
	echo '<tr><td valign="top" width="50%"><div id="combo_disponiveis">'.selecionaVetor($campos_dispiniveis, 'lista_disponiveis[]', 'style="width:100%;" size="10" class="texto" multiple="multiple" ondblclick="mover(document.env.lista_disponiveis, document.env.campos_escolhidos);"','','','lista_disponiveis').'</div></td>
	<td width="50%">'.selecionaVetor($campos_escolhidos, 'campos_escolhidos[]', 'style="width:100%;" size="10" class="texto" multiple="multiple" ondblclick="mover2(document.env.campos_escolhidos,document.env.lista_disponiveis);"','','','campos_escolhidos').'</td></tr>';
	echo '<tr><td align="left">'.botao('&nbsp;&gt;&nbsp;', 'Adicionar', 'Utilize este botão para adicionar um disdponível à lista dos pertencentes.</p>Caso deseja inserir multiplos disponíveis de uma única vez, mantenha o botão <i>CTRL</i> apertado enquanto clica com o botão esquerdo do mouse nos disponíveis da lista acima.','','mover(document.env.lista_disponiveis, document.env.campos_escolhidos);','','',0).'</td><td align="right">'.botao('&nbsp;&lt;&nbsp;', 'Retirar', 'Utilize este botão para retirar um dos pertencentes da lista dos pertencentes. </p>Caso deseja retirar multiplos pertencentes de uma única vez, mantenha o botão <i>CTRL</i> apertado enquanto clica com o botão esquerdo do mouse nos pertencentes da lista acima.','','mover2(document.env.campos_escolhidos,document.env.lista_disponiveis);','','',0).'</td></tr>';
	echo '</table></td></tr>';		
	echo '</table>';
	echo estiloFundoCaixa(); 
	}
echo '</form></BODY></html>';
?>

<script LANGUAGE="javascript">

function cancelar(campo){
	document.getElementById(campo).value='';
	env.submit();
	}

function voltar(){
	<?php 
	if ($projeto) echo 'url_passar(0, "m=projetos&a=index");';
	elseif ($fator) echo 'url_passar(0, "m=praticas&a=fator_lista");';
	elseif ($brainstorm) echo 'url_passar(0, "m=praticas&a=brainstorm_pro_lista");';
	elseif ($plano_acao) echo 'url_passar(0, "m=praticas&a=plano_acao_lista");';
	elseif ($pratica) echo 'url_passar(0, "m=praticas&a=pratica_lista");';
	elseif ($indicador) echo 'url_passar(0, "m=praticas&a=indicador_lista");';
	elseif ($checklist) echo 'url_passar(0, "m=praticas&a=checklist_lista");';
	elseif ($objetivo) echo 'url_passar(0, "m=praticas&a=obj_estrategico_lista");';
	elseif ($estrategia) echo 'url_passar(0, "m=praticas&a=estrategia_lista");';
	?>
	}


function mover(ListaDE,ListaPARA) {

//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value != "0") {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text;
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) { 
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA.options[ListaPARA.options.length] = no;		
				}
			}
		}
	}

function mover2(ListaPARA,ListaDE) {
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "0") {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista
function selecionar() {
	for (var i=0; i < document.getElementById('campos_escolhidos').length ; i++) document.getElementById('campos_escolhidos').options[i].selected = true;
	env.submit();
	}


	
function mudar_om_disponiveis(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_disponiveis').value,'cia_disponiveis','combo_cia_disponiveis', 'class="texto" size=1 style="width:300px;" onchange="mudar_om_disponiveis();"','',1); 	
	}
	
<?php 
if ($objetivo || $estrategia) {
?>	

function atualizar_anos(){
	xajax_atualizar_anos_ajax(document.getElementById('cia_disponiveis').value, 'combo_ano'); 
	}

function mudar_disponiveis(){	
	xajax_mudar_disponiveis_ajax(document.getElementById('cia_disponiveis').value, 'lista_disponiveis','combo_disponiveis', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="mover(document.env.lista_disponiveis, document.env.campos_escolhidos);"', document.getElementById('projeto').value, document.getElementById('pratica').value, document.getElementById('indicador').value, document.getElementById('objetivo').value, document.getElementById('estrategia').value, document.getElementById('pg_id').value, document.getElementById('plano_acao').value, document.getElementById('fator').value, document.getElementById('brainstorm').value); 	
	}		
<?php } else { ?>
	
function mudar_disponiveis(){	
	xajax_mudar_disponiveis_ajax(document.getElementById('cia_disponiveis').value, 'lista_disponiveis','combo_disponiveis', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="mover(document.env.lista_disponiveis, document.env.campos_escolhidos);"', document.getElementById('projeto').value, document.getElementById('pratica').value, document.getElementById('indicador').value, document.getElementById('objetivo').value, document.getElementById('estrategia').value, 0, document.getElementById('plano_acao').value, document.getElementById('fator').value, document.getElementById('brainstorm').value); 	
	}	
			
<?php } ?>	
	
function excluir() {
	var j=0;
	var vetor = new Array();
	for(var i=0; i<env.listafavorito.options.length; i++) {
			if (env.listafavorito.options[i].selected && env.listafavorito.options[i].value >0) {
				vetor[j++]=env.listafavorito.options[i].value;
				}
			}	
			env.excluir_favorito.value=vetor;
			if (vetor.length>0) env.submit();
			else alert('Necessita selecionar um favorito primeiro.');
	};

function editar() {
	var idnome_favorito;
	for(var i=0; i<env.listafavorito.options.length; i++) {
			if (env.listafavorito.options[i].selected && env.listafavorito.options[i].value >0) {
				idnome_favorito=env.listafavorito.options[i].value;
				}
			}	
	env.alterar.value=1;
	env.idnome_favorito.value=idnome_favorito;
	if (idnome_favorito) env.submit();
	else {
		alert('Necessita selecionar um favorito primeiro.');
		env.idnome_favorito.value=null;
		}
	};
</script>	

