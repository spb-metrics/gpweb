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

global $Aplic, $cal_sdf, $ver_todos_projetos;
$mostrarNomeProjeto=nome_projeto($projeto_id);
$qnt=0;
$Aplic->carregarCalendarioJS();
$usuario_id = getParam($_REQUEST, 'usuario_id', '');
$grupo=getParam($_REQUEST, 'grupo', 'designado');
$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', 0);
$usar_periodo = getParam($_REQUEST, 'usar_periodo', 0);
$log_pdf = 1;
$dias = getParam($_REQUEST, 'dias', 30);
$data_inicio= getParam($_REQUEST, 'reg_data_inicio', '');
$data_fim= getParam($_REQUEST, 'reg_data_fim', '');
$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', 0);
$periodo_valor = getParam($_REQUEST, 'pvalor', 1);

if(!$data_inicio){
	$data_inicio = new CData();
	$data_inicio->subtrairIntervalo(new Data_Intervalo('14,0,0,0'));
	}
else $data_inicio = new CData($data_inicio); 

if(!$data_fim) $data_fim = new CData();
else $data_fim = new CData($data_fim);
	
$data_fim->setTime(23, 59, 59);

echo '<input type="hidden" name="fazer_relatorio" id="fazer_relatorio" value="" />';
$data = new CData();	
$titulo=($usar_periodo ? $data_inicio->format($df).' à '.$data_fim->format($df) : $data->format($df)).' - Lista de  '.$config['tarefas'].($usuario_id ? ' ao '.link_usuario($usuario_id): ' a todos '.$config['genero_usuario'].'s '.$config['usuarios']).($projeto_id && (!$ver_todos_projetos) ? ' n'.$config['genero_projeto'].' '.$config['projeto'].' '.$mostrarNomeProjeto : ' em tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos']);
if (!$dialogo){
	echo '<table width="100%">';
	echo '<tr><td width="22">&nbsp;</td>';
	echo '<td align="center">';
	echo '<font size="4"><center><h3>'.$titulo.'</h3></center></font>';
	echo '</td>';
	echo ($dialogo ? '' : '<td width="32">'.dica('Imprimir o relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'<a href="javascript: void(0);" onclick="env.target=\'popup\'; env.dialogo.value=1; env.pdf.value=0; env.sem_cabecalho.value=0; env.submit();"><img src="'.acharImagem('imprimir.png').'" border=0 width="32" heigth="32" /></a>'.dicaF().'</td>');
	echo ($dialogo ? '' : '<td width="32">'.dica('Exportar o relatório para Pdf', 'Clique neste ícone '.imagem('pdf_3.png').' para exportar o relatório no formato Pdf.').'<a href="javascript: void(0);" onclick="env.target=\'\'; env.dialogo.value=1; env.sem_cabecalho.value=1; env.pdf.value=1; env.page_orientation.value=\'P\'; env.submit();"><img src="'.acharImagem('pdf_3.png').'" border=0 width="32" heigth="32" /></a>'.dicaF().'</td>');
	echo '</tr>';
	echo '</table>';
	}
else if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $Aplic->usuario_cia;
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
	echo '<table align="left" cellspacing=0 cellpadding=0 width=100%><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	echo 	'<font size="4"><center>'.$titulo.'</center></font>';
	}
else echo '<font size="4"><center>'.$titulo.'</center></font>';

if (!$dialogo) echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding="4" border=0 width="100%" '.(!$dialogo ? 'class="std"' : '') .'>';
if (!$dialogo){ 
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<td nowrap="nowrap">'.dica('Data Inicial', 'Digite ou escolha no calendário a data de início da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'De:'.dicaF().'<input type="hidden" name="reg_data_inicio" id="reg_data_inicio" value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de início da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td>';
	echo '<td nowrap="nowrap">'.dica('Data Final', 'Digite ou escolha no calendário a data final da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'Até:'.dicaF().'<input type="hidden" name="reg_data_fim" id="reg_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" />'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td>';
	echo '<td nowrap="nowrap"> '.dica('Filtro por '.ucfirst($config['usuario']), 'Selecione na caixa à direita para qual '.$config['usuario'].' deseja visualizar os resultados.').ucfirst($config['usuario']).': '.dicaF().'<input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td>';
	echo '</tr></table></td></tr>';
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap"><input type="checkbox" name="usar_periodo" id="usar_periodo" value="1" '.($usar_periodo ? 'checked="checked"' :'').' />'.dica('Usar o Período', 'Selecione esta caixa para exibir o resultado da pesquisa na faixa de tempo selecionada.').'<label for="usar_periodo">Usar o período</label>'.dicaF().'</td>';	
	echo '<td nowrap="nowratp"><input type="radio" id="responsavel" name="grupo" value="responsavel" '.($grupo == 'responsavel' ? "checked" : '').' />'.dica('Responsáveis', 'Serão considerados apenas os responsáveis pel'.$config['genero_tarefa'].'s '.$config['tarefas'].', sendo ignorado '.$config['genero_usuario'].'s '.$config['usuarios'].' designados por executá-las.').'<label for="responsavel">Responsáveis</label>'.dicaF().'<input type="radio" id="designado" name="grupo" value="designado" '.($grupo == 'designado' ? "checked" : '').' />'.dica('Designados', 'Serão considerados apenas os designados para executar '.$config['genero_tarefa'].'s '.$config['tarefas'].', sendo ignorado '.$config['genero_usuario'].'s '.$config['usuarios'].' responsáveis pelas mesmas.').'<label for="designado">Designados</label>'.dicaF().'</td>';
	echo '<td align="right" width="50%" nowrap="nowrap">'.botao('exibir', 'Exibir', 'Exibir o resultado da pesquisa.','','env.fazer_relatorio.value=1; env.target=\'\'; env.dialogo.value=0; env.sem_cabecalho.value=0; env.pdf.value=0; env.submit();').'</td>';
	echo '</tr></table></td></tr>';
	}
if ($fazer_relatorio || $dialogo) {
	$sql = new BDConsulta();
	$sql->adTabela('tarefas', 't');	
	$sql->esqUnir('projetos', 'pr', 't.tarefa_projeto = pr.projeto_id');
	$sql->esqUnir('usuarios', 'u', 'pr.projeto_responsavel = u.usuario_id');
	$sql->esqUnir('cias', 'cias', 'pr.projeto_cia = cias.cia_id');
	$sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
	$sql->adOnde('pr.projeto_template=0 OR pr.projeto_template IS NULL');
	
	if ($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta) $sql->esqUnir('projeto_gestao', 'projeto_gestao', 'pr.projeto_id=projeto_gestao_projeto');
	
	if ($filtro_criterio){
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=projeto_gestao.projeto_gestao_pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		}
		
	if ($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta)	{
		$filtragem=array();
		if ($filtro_criterio) $filtragem[]='pratica_item_criterio IN ('.$filtro_criterio.')';
		if ($filtro_perspectiva) $filtragem[]='projeto_gestao_perspectiva IN ('.$filtro_perspectiva.')';
		if ($filtro_tema) $filtragem[]='projeto_gestao_tema IN ('.$filtro_tema.')';
		if ($filtro_objetivo) $filtragem[]='projeto_gestao_objetivo IN ('.$filtro_objetivo.')';
		if ($filtro_fator) $filtragem[]='projeto_gestao_fator IN ('.$filtro_fator.')';
		if ($filtro_estrategia) $filtragem[]='projeto_gestao_estrategia IN ('.$filtro_estrategia.')';
		if ($filtro_meta) $filtragem[]='projeto_gestao_meta IN ('.$filtro_meta.')';
		if (count($filtragem)) $sql->adOnde(implode(' OR ', $filtragem));
		}	

	
	
	if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
	if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
	if (!$portfolio && !$portfolio_pai) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
	elseif($portfolio && !$portfolio_pai)  $sql->adOnde('pr.projeto_portfolio=1 AND (pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL)');
	if ($portfolio_pai){
		$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
		$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
		}
	if ($favorito_id){
		$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pr.projeto_id=favoritos_lista.campo_id');
		$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
		$sql->adOnde('favoritos.favorito_id IN ('.$favorito_id.')');
		}
	if($dept_id) $sql->esqUnir('projeto_depts', 'projeto_depts', 'projeto_depts.projeto_id = pr.projeto_id');
	if (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');		
	if ($projetostatus){
		if ($projetostatus == -1) $sql->adOnde('projeto_ativo = 1');
		elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo = 0');
		elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
		}	
	else $sql->adOnde('projeto_ativo = 1');	
	if($dept_id) $sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.')');	
	if ($cia_id  && !$lista_cias && !$favorito_id)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id);
	elseif ($lista_cias && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.')');
	if ($projeto_tipo > -1)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
	if ($projeto_setor) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
	if ($projeto_segmento) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
	if ($projeto_intervencao) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
	if ($projeto_tipo_intervencao) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
	if ($supervisor) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
	if ($autoridade) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
	if ($responsavel) $sql->adOnde('pr.projeto_responsavel IN ('.$responsavel.')');
	if (trim($pesquisar_texto)) $sql->adOnde('pr.projeto_nome LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_objetivos LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_como LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_codigo LIKE \'%'.$pesquisar_texto.'%\'');
	$sql->adOnde('projeto_template = 0');
	if ($grupo=='designado') $sql->internoUnir('tarefa_designados', 'td','t.tarefa_id = td.tarefa_id');
	if ($usar_periodo) $sql->adOnde('(tarefa_inicio BETWEEN \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\' AND \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\' OR tarefa_fim BETWEEN \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\' AND \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\' OR (tarefa_inicio <= \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\' AND tarefa_fim >= \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\') )');
	$sql->adOnde('tarefa_fim IS NOT NULL');
	$sql->adOnde('tarefa_inicio IS NOT NULL');
	$sql->adOnde('tarefa_dinamica != 1');
	$sql->adOnde('tarefa_marco != 1');
	$sql->adOnde('tarefa_duracao  > 0');
	if ($grupo=='responsavel' && $usuario_id) $sql->adOnde('t.tarefa_dono = '.(int)$usuario_id);
	if ($grupo=='designado' && $usuario_id) $sql->adOnde('td.usuario_id = '.(int)$usuario_id);
	if ($projeto_id) $sql->adOnde('t.tarefa_projeto = '.(int)$projeto_id);
	if ($grupo!='designado' && !$usuario_id) $sql->adOnde('t.tarefa_dono IS NOT NULL AND t.tarefa_dono!=0');
	$sql->adCampo('projeto_nome, tarefa_projeto, t.tarefa_id, tarefa_dono, tarefa_duracao');
	$sql->adGrupo('tarefa_id');
	$sql->adOrdem('tarefa_projeto, tarefa_inicio');
	$lista=$sql->Lista();
	$sql->limpar();

	$antigo_projeto='';
	$soma_horas=0;
	$usuario_usado=array();
	echo '<tr><td colspan=20 align=center><table cellspacing=0 cellpadding=2 border=0 class="tbl1">';


	echo '<tr><th>Projeto</th><th>Tarefa</th><th>Responsável</th><th>Designados</th></tr>';
	foreach($lista as $linha){
		echo '<tr>';
		
	if ($linha['tarefa_projeto']!=$antigo_projeto){
			echo '<td>'.link_projeto($linha['tarefa_projeto']).'</td>';
			$antigo_projeto=$linha['tarefa_projeto'];
			}
		else echo '<td>'.$linha['projeto_nome'].'</td>';	
		
		echo '<td>'.link_tarefa($linha['tarefa_id']).'</td>';

		if (!in_array($linha['tarefa_dono'], $usuario_usado)){
			echo '<td>'.link_usuario($linha['tarefa_dono']).'</td>';
			$usuario_usado[]=$linha['tarefa_dono'];
			}
		else echo '<td>'.nome_usuario($linha['tarefa_dono']).'</td>';
		
		$sql->adTabela('tarefa_designados');	
		$sql->esqUnir('usuarios', 'u', 'u.usuario_id = tarefa_designados.usuario_id');
		$sql->esqUnir('contatos', 'con', 'u.usuario_contato = con.contato_id');
		$sql->adCampo(''.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, perc_designado, tarefa_designados.usuario_id');
		$sql->adOnde('tarefa_id='.(int)$linha['tarefa_id']);
		$designados=$sql->lista();
		$sql->limpar();
		
		echo '<td>';
		$qnt=0;
		foreach($designados as $campo){
			if ($qnt++) echo '<br>';
			if (!in_array($campo['usuario_id'], $usuario_usado)){
				echo link_usuario($campo['usuario_id']).' - '.$campo['perc_designado'].'%';
				$usuario_usado[]=$campo['usuario_id'];
				}
			else echo $campo['nome'].' - '.$campo['perc_designado'].'%';
			}
		if (!count($designados)) echo '&nbsp;';
		echo '</td>';
		
		echo'</tr>';
		}
	

	echo '</table></td></tr></table>';




	}
if (!$dialogo) echo estiloFundoCaixa();	
?>
<script type="text/javascript">


function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}
		
function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'reg_' + f_data );
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
    inputField : "reg_data_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide(); 
  	}
  });
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "reg_data_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) { 
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal2.hide(); 
  	}
  });
		
</script>
