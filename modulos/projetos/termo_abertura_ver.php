<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/termo_abertura.class.php');
$projeto_abertura_id = intval(getParam($_REQUEST, 'projeto_abertura_id', 0));

$obj = new CTermoAbertura();
$obj->load($projeto_abertura_id);
$sql = new BDConsulta();

$sql->adTabela('demandas');
$sql->adCampo('demanda_viabilidade');
$sql->adOnde('demanda_id = '.(int)$obj->projeto_abertura_demanda);
$demanda_viabilidade = $sql->resultado();
$sql->limpar();

$podeEditar=permiteEditarTermoAbertura($obj->projeto_abertura_acesso,$projeto_abertura_id);

if (!permiteAcessarTermoAbertura($obj->projeto_abertura_acesso,$projeto_abertura_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$dialogo) $Aplic->salvarPosicao();

if (isset($_REQUEST['tab'])) $Aplic->setEstado('TermoAberturaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('TermoAberturaVerTab') !== null ? $Aplic->getEstado('TermoAberturaVerTab') : 0;
$msg = '';





if ($Aplic->profissional){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Termo de Abertura', 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";


	if($obj->projeto_abertura_demanda || $demanda_viabilidade || $obj->projeto_abertura_projeto){
		$km->Add("root","ver",dica('Ver','Menu de op��es de visualiza��o').'Ver'.dicaF(), "javascript: void(0);");
		if ($obj->projeto_abertura_demanda) $km->Add("ver","ver_demanda",dica('Demanda','Clique neste bot�o para visualizar a demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_ver&demanda_id=".$obj->projeto_abertura_demanda."\");");	
		if ($demanda_viabilidade) $km->Add("ver","ver_viabilidade",dica('Estudo de Viabilidade','Clique neste bot�o para visualizar o estudo de viabilidade relacionado.').'Estudo de Viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_ver&projeto_viabilidade_id=".$demanda_viabilidade."\");");	
		if ($obj->projeto_abertura_projeto) $km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste bot�o para visualizar d'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$obj->projeto_abertura_projeto."\");");		
		}


	$km->Add("root","acao",dica('A��o','Menu de a��es.').'A��o'.dicaF(), "javascript: void(0);'");
	
	if ($podeEditar && ($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin)) {
		$km->Add("acao","acao_editar",dica('Editar Termo de Abertura','Editar os detalhes deste termo de abertura.').'Editar Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_editar&projeto_abertura_id=".$projeto_abertura_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este termo de abertura.').'Excluir Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='excluir()");
		}
		
	if ($obj->projeto_abertura_aprovado!=1 && ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'abertura')) && ($Aplic->usuario_super_admin || $obj->projeto_abertura_autoridade==$Aplic->usuario_id)) {
		$km->Add("acao","acao_aprovar",dica('Aprovar o Termo de Abertura', 'Ao pressionar este bot�o o termo de abertura ser� aprovado e um novo projeto automaticamente criado.').'Aprovar Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='aprovar()");
		$km->Add("acao","acao_reprovar",dica('N�o Aprovar o Termo de Abertura', 'Ao pressionar este bot�o o termo de abertura n�o ser� aprovado.').'N�o Aprovar o Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='nao_aprovar()");
		}		
		
		
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para visualizar as op��es de relat�rios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Termo de Abertura', 'Visualize os detalhes deste termo de abertura.').'Termo de Abertura.'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=termo_abertura_imprimir&dialogo=1&projeto_abertura_id=".$projeto_abertura_id."\");");	
	echo $km->Render();
	echo '</td></tr></table>';
	}


if (!$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Detalhes do Termo de Abertura', 'anexo_projeto.png', $m, $m.'.'.$a);
	if ($podeEditar && ($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin)) {
		$botoesTitulo->adicionaBotao('m=projetos&a=termo_abertura_editar&projeto_abertura_id='.$projeto_abertura_id, 'editar','','Editar este Termo de Abertura','Editar os detalhes deste termo de abertura.');
		$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este termo de abertura.');
		}
	if ($obj->projeto_abertura_aprovado==0 && ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin) && $obj->projeto_abertura_autoridade==$Aplic->usuario_id) {
		$botoesTitulo->adicionaBotao('','aprovar', '', 'Aprovar o Termo de Abertura', 'Ao pressionar este bot�o o termo de abertura ser� aprovado e um novo projeto automaticamente criado.','aprovar();');
		$botoesTitulo->adicionaBotao('','n�o aprovar', '', 'N�o Aprovar o Termo de Abertura', 'Ao pressionar este bot�o o termo de abertura n�o ser� aprovado.','nao_aprovar();');
		}	
	$botoesTitulo->adicionaCelula(dica('Imprimir o Termo de Abertura', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir o termo de abertura.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=termo_abertura_imprimir&dialogo=1&projeto_abertura_id='.$projeto_abertura_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}





echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" id="projeto_abertura_id" name="projeto_abertura_id" value="'.$projeto_abertura_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';


echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_abertura_cor.'" colspan="2"><font color="'.melhorCor($obj->projeto_abertura_cor).'"><b>'.$obj->projeto_abertura_nome.'<b></font></td></tr>';

if ($obj->projeto_abertura_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', $config['organizacao'].' do termo de abertura.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->projeto_abertura_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('projeto_abertura_cia');
	$sql->adCampo('projeto_abertura_cia_cia');
	$sql->adOnde('projeto_abertura_cia_projeto_abertura = '.(int)$projeto_abertura_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est�o envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}

if ($obj->projeto_abertura_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Respons�vel', ucfirst($config['genero_dept']).' '.$config['departamento'].' respons�vel.').ucfirst($config['departamento']).' respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->projeto_abertura_dept).'</td></tr>';

$sql->adTabela('projeto_abertura_dept');
$sql->adCampo('projeto_abertura_dept_dept');
$sql->adOnde('projeto_abertura_dept_projeto_abertura = '.(int)$projeto_abertura_id);
$lista_depts=$sql->carregarColuna();
$sql->limpar();

$saida_depts='';
if ($lista_depts && count($lista_depts)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($lista_depts[0]);
		$qnt_lista_depts=count($lista_depts);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($lista_depts[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est�o envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';


if ($obj->projeto_abertura_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Respons�vel pela Minuta', ucfirst($config['usuario']).' respons�vel pela minuta do termo de abertura.').'Respons�vel pela minuta:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_abertura_responsavel, '','','esquerda').'</td></tr>';		
if ($obj->projeto_abertura_autoridade) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Respons�vel por Aprovar o Termo de Abertura', ucfirst($config['usuario']).' respons�vel pelo termo de abertura.').'Respons�vel por aprovar:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_abertura_autoridade, '','','esquerda').'</td></tr>';		
if ($obj->projeto_abertura_gerente_projeto) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Gerente do Projeto', ucfirst($config['usuario']).' designado para ser o gerente do projeto definido no termo de abertura.').'Gerente do projeto:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_abertura_gerente_projeto, '','','esquerda').'</td></tr>';		


$sql->adTabela('projeto_abertura_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=projeto_abertura_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, contato_funcao, contato_dept');
$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
$participantes = $sql->Lista();
$sql->limpar();

$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.$config['usuarios'].' estar�o executando este termo de abertura.').'Designado:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';




$sql->adTabela('projeto_abertura_patrocinadores');
$sql->adUnir('contatos','contatos','contatos.contato_id=projeto_abertura_patrocinadores.contato_id');
$sql->adCampo('contatos.contato_id, contato_funcao, contato_dept');
$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
$patrocinadores = $sql->Lista();
$sql->limpar();

$saida_patrocinadores='';
if ($patrocinadores && count($patrocinadores)) {
		$saida_patrocinadores.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_patrocinadores.= '<tr><td>'.link_contato($patrocinadores[0]['contato_id']).($patrocinadores[0]['contato_dept']? ' - '.link_secao($patrocinadores[0]['contato_dept']) : '');
		$qnt_patrocinadores=count($patrocinadores);
		if ($qnt_patrocinadores > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_patrocinadores; $i < $i_cmp; $i++) $lista.=link_contato($patrocinadores[$i]['contato_id']).($patrocinadores[$i]['contato_dept']? ' - '.link_secao($patrocinadores[$i]['contato_dept']) : '').'<br>';		
				$saida_patrocinadores.= dica('Outros Patrocinadores', 'Clique para visualizar os demais patrocinadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'patrocinadores\');">(+'.($qnt_patrocinadores - 1).')</a>'.dicaF(). '<span style="display: none" id="patrocinadores"><br>'.$lista.'</span>';
				}
		$saida_patrocinadores.= '</td></tr></table>';
		} 
if ($saida_patrocinadores) echo '<tr><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Qual contato foi elencado como patrocinador.').'Patrocinador:'.dicaF().'</td><td width="100%" class="realce" colspan="2">'.$saida_patrocinadores.'</td></td></tr>';
	
	

$sql->adTabela('projeto_abertura_interessados');
$sql->adUnir('contatos','contatos','contatos.contato_id=projeto_abertura_interessados.contato_id');
$sql->adCampo('contatos.contato_id, contato_funcao, contato_dept');
$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
$interessados = $sql->Lista();
$sql->limpar();
$saida_interessados='';
if ($interessados && count($interessados)) {
		$saida_interessados.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_interessados.= '<tr><td>'.link_contato($interessados[0]['contato_id']).($interessados[0]['contato_dept']? ' - '.link_secao($interessados[0]['contato_dept']) : '');
		$qnt_interessados=count($interessados);
		if ($qnt_interessados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_interessados; $i < $i_cmp; $i++) $lista.=link_contato($interessados[$i]['contato_id']).($interessados[$i]['contato_dept']? ' - '.link_secao($interessados[$i]['contato_dept']) : '').'<br>';		
				$saida_interessados.= dica('Outros Interessados', 'Clique para visualizar os demais interessados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'interessados\');">(+'.($qnt_interessados - 1).')</a>'.dicaF(). '<span style="display: none" id="interessados"><br>'.$lista.'</span>';
				}
		$saida_interessados.= '</td></tr></table>';
		} 
if ($saida_interessados) echo '<tr><td align="right" nowrap="nowrap">'.dica('Parte Interessada', 'Qual contato foi elencado como parte interessada.').'Parte interessada:'.dicaF().'</td><td width="100%" class="realce" colspan="2">'.$saida_interessados.'</td></td></tr>';

	


if (isset($obj->projeto_abertura_codigo) && $obj->projeto_abertura_codigo) echo '<tr><td align="right" nowrap="nowrap">'.dica('C�digo', 'O c�digo do termo de abertura.').'C�digo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_abertura_codigo.'</td></tr>';
if (isset($obj->projeto_abertura_setor) && $obj->projeto_abertura_setor) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o termo de abertura.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSetor().'</td></tr>';
if (isset($obj->projeto_abertura_segmento) && $obj->projeto_abertura_segmento) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o termo de abertura.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSegmento().'</td></tr>';
if (isset($obj->projeto_abertura_intervencao) && $obj->projeto_abertura_intervencao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o termo de abertura.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getIntervencao().'</td></tr>';
if (isset($obj->projeto_abertura_tipo_intervencao) && $obj->projeto_abertura_tipo_intervencao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o termo de abertura.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getTipoIntervencao().'</td></tr>';



if ($obj->projeto_abertura_justificativa) echo '<tr><td align="right">'.dica('Justificativa', 'Descrever de forma clara a justificativa contendo um breve hist�rico e as motiva��es do projeto. .').'Justificativa:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_justificativa.'</td></tr>';
if ($obj->projeto_abertura_objetivo) echo '<tr><td align="right">'.dica('Objetivo', 'Descrever qual o objetivo para a qual �rg�o est� realizando o projeto, que pode ser: descri��o concreta de que o projeto quer alcan�ar, uma posi��o estrat�gica a ser alcan�ada, um resultado a ser obtido, um produto a ser produzido ou um servi�o a ser realizado. Os objetivos devem ser espec�ficos, mensur�veis, realiz�veis, real�sticos, e baseados no tempo.>.').'Objetivo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_objetivo.'</td></tr>';
if ($obj->projeto_abertura_escopo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Declara��o de Escopo', 'Descrever a declara��o do escopo, que inclui as principais entregas, fornece uma base documentada para futuras decis�es do projeto e para confirmar ou desenvolver um entendimento comum do escopo do projeto entre as partes interessadas.').'Declara��o de Escopo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_escopo.'</td></tr>';
if ($obj->projeto_abertura_nao_escopo) echo '<tr><td align="right">'.dica('N�o escopo', 'Descrever de forma expl�cita o que est� exclu�do do projeto, para evitar que uma parte interessada possa supor que um produto, servi�o ou resultado espec�fico � um produto do projeto.').'N�o escopo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_nao_escopo.'</td></tr>';
if ($obj->projeto_abertura_tempo) echo '<tr><td align="right">'.dica('Tempo estimado', 'Descrever a estimativa de tempo para finalizar o projeto.').'Tempo estimado:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_tempo.'</td></tr>';
if ($obj->projeto_abertura_custo) echo '<tr><td align="right">'.dica('Custos Estimado e Fonte de Recurso', 'Descrever a estimativa de custo do projeto e a fonte de recurso.').'Custos estimado e fonte de recurso:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_custo.'</td></tr>';
if ($obj->projeto_abertura_premissas) echo '<tr><td align="right">'.dica('Premissas', 'Descrever as premissas do projeto. As premissas s�o fatores que, para fins de planejamento, s�o considerados verdadeiros, reais ou certos sem prova ou demonstra��o. As premissas afetam todos os aspectos do planejamento do projeto e fazem parte da elabora��o progressiva do projeto. Frequentemente, as equipes do projeto identificam, documentam e validam as premissas durante o processo de planejamento. Geralmente, as premissas envolvem um grau de risco.').'Premissas:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_premissas.'</td></tr>';
if ($obj->projeto_abertura_restricoes) echo '<tr><td align="right">'.dica('Restri��es', 'Descrever as restri��es do projeto. Uma restri��o � uma limita��o aplic�vel, interna ou externa ao projeto, que afetar� o desempenho do projeto ou de um processo. Por exemplo, uma restri��o do cronograma � qualquer limita��o ou condi��o colocada em rela��o ao cronograma do projeto que afeta o momento em que uma atividade do cronograma pode ser agendada e geralmente est� na forma de datas impostas fixas.').'Restri��es:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_restricoes.'</td></tr>';
if ($obj->projeto_abertura_riscos) echo '<tr><td align="right">'.dica('Riscos Previamente Identificados', 'Identificar eventos ou condi��es incertos que, se ocorrerem, provocar�o efeitos positivos ou negativos nos objetivos do projeto.').'Riscos previamente identificados:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_riscos.'</td></tr>';
if ($obj->projeto_abertura_infraestrutura) echo '<tr><td align="right">'.dica('Infraestrutura', 'Identificar previamente a infraestrutura para o atingimento dos objetivos do projeto, exemplo, salas, servidores, notebook etc.').'Infraestrutura:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_infraestrutura.'</td></tr>';
if ($obj->projeto_abertura_observacao) echo '<tr><td align="right">'.dica('Observa��es', 'Observa��es sobre o termo de abertura.').'Observa��es:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_observacao.'</td></tr>';





		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('termo_abertura', $obj->projeto_abertura_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				

if ($obj->projeto_abertura_data && $obj->projeto_abertura_aprovado==1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Aprova��o', 'A data em que o termo de abertura foi aprovado, e conseguinte '.$config['genero_projeto'].' '.$config['projeto'].' criado.').'Data de aprova��o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_abertura_data).'</td></tr>';

if ($obj->projeto_abertura_data && $obj->projeto_abertura_aprovado==-1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da N�o Aprova��o', 'A data em que o termo de abertura n�o foi aprovado.').'Data da n�o aprova��o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_abertura_data).'</td></tr>';

if ($obj->projeto_abertura_recusa) echo '<tr><td align="right" nowrap="nowrap>'.dica('Justificativa da N�o Aprova��o', 'Justificativa para o termo de abertura n�o ter sido aprovado.').'Justificativa da n�o aprova��o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_recusa.'</td></tr>';

if ($obj->projeto_abertura_aprovacao) echo '<tr><td align="right" nowrap="nowrap>'.dica('Justificativa da Aprova��o', 'Justificativa para o termo de abertura ter sido aprovado.').'Justificativa da aprova��o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_abertura_aprovacao.'</td></tr>';


if ($obj->projeto_abertura_demanda) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Demanda', 'Visualiza��o dos detalhes da demanda que originou este termo de abertura.').'Demanda:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_demanda($obj->projeto_abertura_demanda).'</td></tr>';				
if ($demanda_viabilidade) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Estudo de Viabilidade', 'Visualiza��o dos detalhes do estudo de viabilidade desta demanda.').'Estudo de viabilidade:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_viabilidade($demanda_viabilidade).'</td></tr>';				
if ($obj->projeto_abertura_projeto) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['projeto']), 'Visualiza��o dos detalhes do '.$config['projeto'].' que foi criado basead'.$config['genero_projeto'].' neste termo de abertura.').ucfirst($config['projeto']).':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_projeto($obj->projeto_abertura_projeto).'</td></tr>';				
	
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativo', 'Se o termo de abertura se encontra ativo.').'Ativo:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->projeto_abertura_ativo ? 'Sim' : 'N�o').'</td></tr>';				
		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">

function  nao_aprovar(){
	url_passar(0, 'm=projetos&a=termo_abertura_nao_aprovar&projeto_abertura_id='+document.getElementById('projeto_abertura_id').value);
	}	


function  aprovar(){
	url_passar(0, 'm=projetos&a=termo_abertura_aprovar&projeto_abertura_id='+document.getElementById('projeto_abertura_id').value);
	}	
	
function excluir() {
	if (confirm('Tem certeza que deseja excluir este demanda')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_termo_abertura';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>