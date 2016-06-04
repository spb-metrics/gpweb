<?php
global $dialogo;

$sql = new BDConsulta;

$status=array('' => '')+getSisValor('StatusProblema');

if (isset($_REQUEST['superintendencia_id'])) $Aplic->setEstado('superintendencia_id', getParam($_REQUEST, 'superintendencia_id', null));
$superintendencia_id = ($Aplic->getEstado('superintendencia_id') !== null ? $Aplic->getEstado('superintendencia_id') : null);

if (isset($_REQUEST['status_id'])) $Aplic->setEstado('status_id', getParam($_REQUEST, 'status_id', null));
$status_id = ($Aplic->getEstado('status_id') !== null ? $Aplic->getEstado('status_id') : null);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('RElatorioSocialaListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('RElatorioSocialaListaTab') !== null ? $Aplic->getEstado('RElatorioSocialaListaTab') : 0);

if (isset($_REQUEST['estado_sigla'])) $Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : null);

if (isset($_REQUEST['municipio_id'])) $Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
$municipio_id = ($Aplic->getEstado('municipio_id') !== null && $estado_sigla ? $Aplic->getEstado('municipio_id') : null);

if (isset($_REQUEST['social_comunidade_id'])) $Aplic->setEstado('social_comunidade_id', getParam($_REQUEST, 'social_comunidade_id', null));
$social_comunidade_id = ($Aplic->getEstado('social_comunidade_id') !== null && $municipio_id  ? $Aplic->getEstado('social_comunidade_id') : 0);

if (isset($_REQUEST['social_id'])) $Aplic->setEstado('social_id', getParam($_REQUEST, 'social_id', null));
$social_id = ($Aplic->getEstado('social_id') !== null ? $Aplic->getEstado('social_id') : null);

if (isset($_REQUEST['acao_id'])) $Aplic->setEstado('acao_id', getParam($_REQUEST, 'acao_id', null));
$acao_id = ($Aplic->getEstado('acao_id') !== null ? $Aplic->getEstado('acao_id') : null);

if (!$social_id) $acao_id=null;

if (isset($_REQUEST['familiabusca'])) $Aplic->setEstado('familiabusca', getParam($_REQUEST, 'familiabusca', null));
$pesquisa = $Aplic->getEstado('familiabusca') !== null ? $Aplic->getEstado('familiabusca') : '';

if (isset($_REQUEST['relatorio_id'])) $Aplic->setEstado('relatorio_id', getParam($_REQUEST, 'relatorio_id', null));
$relatorio_id = ($Aplic->getEstado('relatorio_id') !== null ? $Aplic->getEstado('relatorio_id') : null);

if (isset($_REQUEST['opcao_id'])) $Aplic->setEstado('opcao_id', getParam($_REQUEST, 'opcao_id', null));
$opcao_id = ($Aplic->getEstado('opcao_id') !== null ? $Aplic->getEstado('opcao_id') : null);

$sql = new BDConsulta;

$lista_programas=array('' => '');
$sql->adTabela('social');
$sql->adCampo('social_id, social_nome');
$sql->adOrdem('social_nome');
$lista_programas+=$sql->listaVetorChave('social_id', 'social_nome');
$sql->limpar();

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+=$sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();
$comunidades=array(''=>'');
$cidades=array(''=>'');
if (!$municipio_id) $cidades['5300108']='Brasília';


$lista_superintendencias=array('' => '');
$sql->adTabela('social_superintendencia');
$sql->adCampo('social_superintendencia_id, social_superintendencia_nome');
$sql->adOrdem('social_superintendencia_nome');
$lista_superintendencias+=$sql->listaVetorChave('social_superintendencia_id', 'social_superintendencia_nome');
$sql->limpar();

if ($superintendencia_id){
	$sql->adTabela('social_superintendencia_municipios');
	$sql->adCampo('municipio_id');
	$sql->adOnde('social_superintendencia_id='.(int)$superintendencia_id);
	$municipios_superintendencia=$sql->carregarColuna();
	$sql->limpar();
	$municipios_superintendencia=implode(',',$municipios_superintendencia);
	}
else $municipios_superintendencia='';

$lista_relatorios=array(
''=>'',
'local'=>'Localização dos beneficiários',
'lista'=>'Lista dos beneficiários',
'porcentagem'=>'Percentagem executada',
'atividade'=>'Atividades nos beneficiários',
'custo'=>'Valor médio das unidades',
'pizza'=>'Percentual de beneficiários',
'problema'=>'Problemas verificados',
'projetos'=>'Projetos',
);


if ($relatorio_id=='local') $lista_opcoes=array('local_familia'=>'Ação andamento e finalizada','local_familia_completado'=>'Ação finalizada','local_familia_incompleto'=>'Ação em andamento');
elseif ($relatorio_id=='lista') $lista_opcoes=array('lista_familia'=>'Ação andamento e finalizada','lista_familia_completado'=>'Ação finalizada','lista_familia_incompleto'=>'Ação em andamento');
elseif ($relatorio_id=='porcentagem') $lista_opcoes=array('relatorio_porcentagem_municipio_grafico'=>'Área dos municípios','relatorio_porcentagem_estado_grafico'=>'Área dos Estados','relatorio_porcentagem_estado'=>'Lista de Estados','relatorio_porcentagem_municipio'=>'Lista de municípios','relatorio_porcentagem_comunidade'=>'Lista de comunidades');
elseif ($relatorio_id=='atividade') $lista_opcoes=array('relatorio_atividade_familia'=>'Lista por família','relatorio_atividade_resumo_familia'=>'Resumo','relatorio_atividade_resumo_familia_estado'=>'Resumo por Estado','relatorio_atividade_resumo_familia_grafico'=>'Gráfico do resumo');
elseif ($relatorio_id=='custo') $lista_opcoes=array('relatorio_custo_medio_municipio'=>'Valor médio das unidades por município','relatorio_custo_medio_estado'=>'Valor médio das unidades por Estado');
elseif ($relatorio_id=='pizza') $lista_opcoes=Array('bolsa_comunidade'=>'Com bolsa família - Comunidade',	'bolsa_municipio'=>'Com bolsa família - Município',	'bolsa_estado'=>'Com bolsa família - Estado',	'mulher_comunidade'=>'Com mulher chefe da família - Comunidade', 'mulher_municipio'=>'Com mulher chefe da família - Município', 'mulher_estado'=>'Com mulher chefe da família - Estado', 'crianca_comunidade'=>'Com criança de 0 a 6 anos - Comunidade', 'crianca_municipio'=>'Com criança de 0 a 6 anos - Município',	'crianca_estado'=>'Com criança de 0 a 6 anos - Estado',	'escola_comunidade'=>'Com criança e adolecente na escola - Comunidade',	'escola_municipio'=>'Com criança e adolecente na escola - Município',	'escola_estado'=>'Com criança e adolecente na escola - Estado',	'idoso_comunidade'=>'Com adultos idosos (65 anos ou mais) - Comunidade', 'idoso_municipio'=>'Com adultos idosos (65 anos ou mais) - Município', 'idoso_estado'=>'Com adultos idosos (65 anos ou mais) - Estado', 'deficiente_comunidade'=>'Com deficiente físico ou mental - Comunidade', 'deficiente_municipio'=>'Com deficiente físico ou mental - Município','deficiente_estado'=>'Com deficiente físico ou mental - Estado');	
elseif ($relatorio_id=='problema') $lista_opcoes=array(
'problema_nacional_beneficiario'=> ucfirst($config['beneficiario']).' - lista Nacional',
'problema_estado_beneficiario'=>ucfirst($config['beneficiario']).' - lista de Estados',
'problema_municipio_beneficiario'=>ucfirst($config['beneficiario']).' - lista de municípios',
'problema_comunidade_beneficiario'=>ucfirst($config['beneficiario']).' - lista de comunidades',
'problema_nacional_comunidade'=>'Comissões Comunitárias - lista Nacional',
'problema_estado_comunidade'=>'Comissões Comunitárias - lista de Estados',
'problema_municipio_comunidade'=>'Comissões Comunitárias - lista de municípios',
'problema_comunidade_comunidade'=>'Comissões Comunitárias - lista de comunidades',
'problema_nacional_municipio'=>'Comitês Municipais - lista Nacional',
'problema_estado_municipio'=>'Comitês Municipais - lista de Estados',
'problema_municipio_municipio'=>'Comitês Municipais - lista de municípios',
'problema_nacional_estado'=>'Coordenações Regionais - lista Nacional',
'problema_estado_estado'=>'Coordenações Regionais - lista de Estados',
'problema_nacional_nacional'=>'Comitê Nacional');
elseif ($relatorio_id=='projetos') $lista_opcoes=array('relatorio_projeto' => 'Lista de projetos relacionados');
else $lista_opcoes=array(''=>'');


if (!$dialogo){
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="" />';
	$botoesTitulo = new CBlocoTitulo('Relatório', '../../../modulos/social/imagens/relatorio.gif', $m, $m.'.'.$a);
	
	$filtro_status='<tr id="filtro_status" '.($relatorio_id=='problema' ? '' : 'style="display:none"').' ><td nowrap="nowrap" align="right">'.dica('Status', 'Filtre os problemas pelo estatus.').'Status:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($status, 'status_id', 'size="1" style="width:160px;" class="texto"', $status_id) .'</td></tr>';
	$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado dos beneficiários.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município dos beneficiários.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto" onchange="mudar_comunidades()" style="width:160px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_comunidade='<tr><td align="right">'.dica('Comunidade', 'Selecione a comunidade dos beneficiários.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade">'.selecionar_comunidade_para_ajax($municipio_id,'social_comunidade_id', 'class="texto" style="width:160px;"', '', $social_comunidade_id, false).'</div></td></tr>';
	$botao_filtro='<tr><td><a href="javascript:void(0);" onclick="if (document.getElementById(\'acao_id\').value){document.frm_filtro.submit();} else {alert(\'Precisa escolher uma ação social\'); document.getElementById(\'acao_id\').focus();}">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar os beneficiários pelos parâmetros selecionados à esquerda.').'</a></td></tr>';
	$programas='<tr><td nowrap="nowrap" align="right">'.dica('Programa Social', 'Filtre os beneficiários por programa social em que estão inseridas.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_programas, 'social_id', 'size="1" style="width:240px;" class="texto" onchange="mudar_acao()"', $social_id) .'</td></tr>';
	$acoes='<tr><td align="right" nowrap="nowrap">'.dica('Ação Social', 'Filtre os beneficiários pela ação social.').'Ação:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="acao_combo">'.selecionar_acao_para_ajax($social_id, 'acao_id', 'size="1" style="width:240px;" class="texto"', '', $acao_id, false).'</div></td></tr>';
	$relatorios='<tr><td align="right">'.dica('Relatório', 'Escolha na caixa de opção à direita o tipo de relatório a ser exibido.').'Relatório:'.dicaF().'</td><td>'.selecionaVetor($lista_relatorios, 'relatorio_id', 'class="texto" style="width:240px;" size="1" onchange="mudar_opcao();"', $relatorio_id).'</td></tr>';
	$opcoes='<tr><td align="right">'.dica('Opção', 'Escolha na caixa de opção à direita a opção do relatório a ser exibido.').'Opção:'.dicaF().'</td><td>'.selecionaVetor($lista_opcoes, 'opcao_id', 'class="texto" style="width:240px;" size="1"', $opcao_id).'</td></tr>';
	
	$superintendencias=($lista_superintendencias ? '<tr><td nowrap="nowrap" align="right">'.dica('Superintendência', 'Filtre os beneficiários por área de atuação da superintendência selecionada.').'Superintendência:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_superintendencias, 'superintendencia_id', 'size="1" style="width:160px;" class="texto" onchange="document.getElementById(\'social_comunidade_id\').length=0; document.getElementById(\'social_comunidade_id\').value=0; document.getElementById(\'municipio_id\').length=0; document.getElementById(\'estado_sigla\').value=\'\';"', $superintendencia_id) .'</td></tr>' : '');
	
	
	
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$programas.$acoes.$relatorios.$opcoes.'</table>');
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$procurar_estado.$procurar_municipio.$procurar_comunidade.$superintendencias.$filtro_status.'</table>');
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$botao_filtro.'</table>');
	$botoesTitulo->adicionaCelula('<td nowrap="nowrap" align="right">'.dica('Imprimir '.ucfirst($config['beneficiario']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de famílias.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a='.$a.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo '</form>';
	}

$cabecalho='';
if ($dialogo){
	
	$sql->adTabela('cias');
	$sql->adCampo('cia_cabacalho, cia_logo');
	$cabecalho= $sql->linha();
	$sql->limpar();
	//imprimir abeçalho
	$cabecalho='<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td width="175">'.($cabecalho['cia_logo'] ? '<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/organizacoes/'.$cabecalho['cia_logo'].'" alt="" border=0 />' : '').'</td><td width="400">'.$cabecalho['cia_cabacalho'].'</td><td width="175" align=center>'.retorna_data(date('Y-m-d H:i:s')).'</td></tr></table></td></tr>';
	}



if ($opcao_id=='local_familia' || $opcao_id=='local_familia_completado' || $opcao_id=='local_familia_incompleto') include_once(BASE_DIR.'/modulos/social/relatorio_local_familia.php');
elseif ($opcao_id=='lista_familia' || $opcao_id=='lista_familia_completado' || $opcao_id=='lista_familia_incompleto') include_once(BASE_DIR.'/modulos/social/relatorio_lista_familia.php');
elseif ($opcao_id=='bolsa_comunidade' || $opcao_id=='mulher_comunidade' || $opcao_id=='crianca_comunidade' || $opcao_id=='escola_comunidade' || $opcao_id=='idoso_comunidade' || $opcao_id=='deficiente_comunidade')  include_once(BASE_DIR.'/modulos/social/relatorio_pizza_comunidade.php');
elseif ($opcao_id=='bolsa_municipio' || $opcao_id=='mulher_municipio' || $opcao_id=='crianca_municipio' || $opcao_id=='escola_municipio' || $opcao_id=='idoso_municipio' || $opcao_id=='deficiente_municipio')  include_once(BASE_DIR.'/modulos/social/relatorio_pizza_municipio.php');
elseif ($opcao_id=='bolsa_estado' || $opcao_id=='mulher_estado' || $opcao_id=='crianca_estado' || $opcao_id=='escola_estado' || $opcao_id=='idoso_estado' || $opcao_id=='deficiente_estado')  include_once(BASE_DIR.'/modulos/social/relatorio_pizza_estado.php');
elseif ($opcao_id=='problema_nacional_beneficiario' || $opcao_id=='problema_nacional_comunidade' || $opcao_id=='problema_nacional_municipio' || $opcao_id=='problema_nacional_estado' || $opcao_id=='problema_nacional_nacional')  include_once(BASE_DIR.'/modulos/social/relatorio_problema_nacional.php');
elseif ($opcao_id=='problema_estado_beneficiario' || $opcao_id=='problema_estado_comunidade' || $opcao_id=='problema_estado_municipio' || $opcao_id=='problema_estado_estado')  include_once(BASE_DIR.'/modulos/social/relatorio_problema_estado.php');
elseif ($opcao_id=='problema_municipio_beneficiario' || $opcao_id=='problema_municipio_comunidade' || $opcao_id=='problema_municipio_municipio')  include_once(BASE_DIR.'/modulos/social/relatorio_problema_municipio.php');
elseif ($opcao_id=='problema_comunidade_beneficiario' || $opcao_id=='problema_comunidade_comunidade')  include_once(BASE_DIR.'/modulos/social/relatorio_problema_comunidade.php');
elseif ($opcao_id) include_once(BASE_DIR.'/modulos/social/'.$opcao_id.'.php');
else echo '<tr><td colspan=20>Selecione um relatório</td></tr>';

if ($dialogo) echo '<script language="javascript">self.print();</script>';



?>
<script type="text/javascript">

function mudar_opcao(){
	var relatorio=document.getElementById('relatorio_id').value;
	document.getElementById('opcao_id').length=0;
	
	if (relatorio=='local'){
		var opt = document.createElement("option");
	  opt.text = 'Ação andamento e finalizada';
	  opt.value = 'local_familia';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Ação finalizada';
	  opt.value = 'local_familia_completado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Ação em andamento';
	  opt.value = 'local_familia_incompleto';
		document.getElementById("opcao_id").options.add(opt);
		}
	
	if (relatorio=='lista'){
		var opt = document.createElement("option");
	  opt.text = 'Ação andamento e finalizada';
	  opt.value = 'lista_familia';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Ação finalizada';
	  opt.value = 'lista_familia_completado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Ação em andamento';
	  opt.value = 'lista_familia_incompleto';
		document.getElementById("opcao_id").options.add(opt);
		}
	
	if (relatorio=='porcentagem'){
		var opt = document.createElement("option");
	  opt.text = 'Área dos municípios';
	  opt.value = 'relatorio_porcentagem_municipio_grafico';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Área dos Estados';
	  opt.value = 'relatorio_porcentagem_estado_grafico';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Lista de Estados';
	  opt.value = 'relatorio_porcentagem_estado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Lista de municípios';
	  opt.value = 'relatorio_porcentagem_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Lista de comunidades';
	  opt.value = 'relatorio_porcentagem_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		}

	if (relatorio=='atividade'){
		var opt = document.createElement("option");
	  opt.text = 'Lista por família';
	  opt.value = 'relatorio_atividade_familia';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Resumo';
	  opt.value = 'relatorio_atividade_resumo_familia';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Resumo por Estado';
	  opt.value = 'relatorio_atividade_resumo_familia_estado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Gráfico do resumo';
	  opt.value = 'relatorio_atividade_resumo_familia_grafico';
		document.getElementById("opcao_id").options.add(opt);
		}
	
	if (relatorio=='custo'){
		var opt = document.createElement("option");
	  opt.text = 'Valor médio das unidades por município';
	  opt.value = 'relatorio_custo_medio_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Valor médio das unidades por Estado';
	  opt.value = 'relatorio_custo_medio_estado';
		document.getElementById("opcao_id").options.add(opt);
		}	

	
	if (relatorio=='pizza'){
		var opt = document.createElement("option");
	  opt.text = 'Com bolsa família - Comunidade';
	  opt.value = 'bolsa_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com bolsa família - Município';
	  opt.value = 'bolsa_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
		opt.text = 'Com bolsa família - Estado';
	  opt.value = 'bolsa_estado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com mulher chefe da família - Comunidade';
	  opt.value = 'mulher_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com mulher chefe da família - Município';
	  opt.value = 'mulher_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
		opt.text = 'Com mulher chefe da família - Estado';
	  opt.value = 'mulher_estado';
		document.getElementById("opcao_id").options.add(opt);

		var opt = document.createElement("option");
	  opt.text = 'Com criança de 0 a 6 anos - Comunidade';
	  opt.value = 'crianca_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com criança de 0 a 6 anos - Município';
	  opt.value = 'crianca_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
		opt.text = 'Com criança de 0 a 6 anos - Estado';
	  opt.value = 'crianca_estado';
		document.getElementById("opcao_id").options.add(opt);
					
		var opt = document.createElement("option");
	  opt.text = 'Com criança e adolecente na escola - Comunidade';
	  opt.value = 'escola_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com criança e adolecente na escola - Município';
	  opt.value = 'escola_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
		opt.text = 'Com criança e adolecente na escola - Estado';
	  opt.value = 'escola_estado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com adultos idosos (65 anos ou mais) - Comunidade';
	  opt.value = 'idoso_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com adultos idosos (65 anos ou mais) - Município';
	  opt.value = 'idoso_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
		opt.text = 'Com adultos idosos (65 anos ou mais) - Estado';
	  opt.value = 'idoso_estado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com deficiente físico ou mental - Comunidade';
	  opt.value = 'deficiente_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Com deficiente físico ou mentala - Município';
	  opt.value = 'deficiente_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
		opt.text = 'Com deficiente físico ou mental - Estado';
	  opt.value = 'deficiente_estado';
		document.getElementById("opcao_id").options.add(opt);
		}			
	
	
	if (relatorio=='projetos'){
		var opt = document.createElement("option");
	  opt.text = 'Lista de projetos relacionados';
	  opt.value = 'relatorio_projeto';
		document.getElementById("opcao_id").options.add(opt);
		}
	
	
	
	if (relatorio=='problema'){
		document.getElementById("filtro_status").style.display='';
		var opt = document.createElement("option");
	  opt.text = '<?php echo ucfirst($config["beneficiarios"])?> - lista Nacional';
	  opt.value = 'problema_nacional_beneficiario';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = '<?php echo ucfirst($config["beneficiarios"])?> - lista de Estados';
	  opt.value = 'problema_estado_beneficiario';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = '<?php echo ucfirst($config["beneficiarios"])?> - lista de municípios';
	  opt.value = 'problema_municipio_beneficiario';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = '<?php echo ucfirst($config["beneficiarios"])?> - lista de comunidades';
	  opt.value = 'problema_comunidade_beneficiario';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Comissões Comunitárias - lista Nacional';
	  opt.value = 'problema_nacional_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Comissões Comunitárias - lista de Estados';
	  opt.value = 'problema_estado_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Comissões Comunitárias - lista de municípios';
	  opt.value = 'problema_municipio_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Comissões Comunitárias - lista de comunidades';
	  opt.value = 'problema_comunidade_comunidade';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Comitês Municipais - lista Nacional';
	  opt.value = 'problema_nacional_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Comitês Municipais - lista de Estados';
	  opt.value = 'problema_estado_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Comitês Municipais - lista de municípios';
	  opt.value = 'problema_municipio_municipio';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Coordenações Regionais - lista Nacional';
	  opt.value = 'problema_nacional_estado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Coordenações Regionais - lista de Estados';
	  opt.value = 'problema_estado_estado';
		document.getElementById("opcao_id").options.add(opt);
		
		var opt = document.createElement("option");
	  opt.text = 'Comitê Nacional';
	  opt.value = 'problema_nacional_nacional';
		document.getElementById("opcao_id").options.add(opt);
		
		}
	else document.getElementById("filtro_status").style.display='none';
	
	}


function mudar_acao(){
	xajax_acao_ajax(document.getElementById('social_id').value, 0);
	}

function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:160px;" onchange="mudar_comunidades();"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>)); 	
	document.getElementById('social_comunidade_id').length=0;
	}	
	
function mudar_comunidades(){
	var municipio_id=(document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>);
	var social_comunidade_id=(document.getElementById('social_comunidade_id').value ? document.getElementById('social_comunidade_id').value : <?php echo ($social_comunidade_id ? $social_comunidade_id : 0) ?>);
	xajax_selecionar_comunidade_ajax(municipio_id, 'social_comunidade_id', 'combo_comunidade', 'class="texto" size=1 style="width:160px;"', '', social_comunidade_id); 	
	}		
	

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	

</script>