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


if (!$Aplic->usuario_super_admin)	$Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();

$campo_formulario_tipo = getParam($_REQUEST, 'campo_formulario_tipo', 'projeto');

$sql = new BDConsulta;


if (getParam($_REQUEST, 'salvar', null)){
	$campo=getParam($_REQUEST, 'campo', array());
	
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo, campo_formulario_descricao');
	$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista as $linha) {
		$sql->adTabela('campo_formulario');
		$sql->adAtualizar('campo_formulario_ativo', (isset($campo[$linha['campo_formulario_campo']]) ? 1 : 0));
		$sql->adOnde('campo_formulario_campo = "'.$linha['campo_formulario_campo'].'"');
		$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\'');
		$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
		$sql->exec();
		$sql->limpar();
		}
	ver2('Opções de campos foram salvas.');
	}


$sql->adTabela('campo_formulario');
$sql->adCampo('DISTINCT campo_formulario_tipo');
$lista = $sql->carregarColuna();
$sql->limpar();
$tipos=array();
//ver($lista);
foreach($lista as $linha) $tipos[$linha]=ucfirst($linha);

$tipos['iniciativas']='Lista de '.ucfirst($config['iniciativas']);


$tipos['agenda']='Compromisso';
$tipos['avaliacao']='Avaliação';
$tipos['projeto']=ucfirst($config['projeto']); 
$tipos['perspectiva']=ucfirst($config['perspectiva']); 
$tipos['tema']=ucfirst($config['tema']); 
$tipos['objetivo']=ucfirst($config['objetivo']); 
$tipos['fator']=ucfirst($config['fator']); 
$tipos['estrategia']=ucfirst($config['iniciativa']); 
$tipos['meta']=ucfirst($config['meta']);
$tipos['acao']=ucfirst($config['acao']);
$tipos['pratica']=ucfirst($config['pratica']);
$tipos['ata']='Ata de Reunião';
$tipos['swot']='Campo SWOT';
$tipos['operativo']='Plano Operativo';
$tipos['causa_efeito']='Diagrama de Causa-Efeito';
$tipos['calendario']='Agenda';
$tipos['instrumento']=ucfirst($config['instrumento']);
$tipos['recurso']=ucfirst($config['recurso']);
$tipos['licao']=ucfirst($config['licao']);
$tipos['projetos']='Lista de '.ucfirst($config['projetos']);
$tipos['planejamento']='Planejamento estratégico';
$tipos['planos']='Lista de '.ucfirst($config['acoes']);
$tipos['viabilidade']='Estudo de viabilidade'; 
$tipos['abertura']='Termo de abertura'; 
$tipos['cias']=ucfirst($config['organizacoes']);
if ($Aplic->profissional) {
	$tipos['risco']=ucfirst($config['risco']);
	$tipos['risco_resposta']=ucfirst($config['risco_resposta']);
	$tipos['tgn']=ucfirst($config['tgn']);
	$tipos['problema']=ucfirst($config['problema']);
	$tipos['programa']=ucfirst($config['programa']);
	$tipos['tr']=ucfirst($config['tr']);
	$tipos['me']=ucfirst($config['me']);
	$tipos['tgns']='Lista de '.ucfirst($config['tgns']);
	$tipos['canvas']=ucfirst($config['canvas']);
	$tipos['canvass']='Lista de '.ucfirst($config['canvass']);
	}

asort($tipos);

if ($Aplic->modulo_ativo('social')) $tipos['familia']=ucfirst($config['beneficiario']);

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo, campo_formulario_descricao');
$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$lista = $sql->lista();
$sql->limpar();




$botoesTitulo = new CBlocoTitulo('Campos dos Formulários', 'config-sistema.png', $m);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();



echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input name="a" type="hidden" value="campo_formulario" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="salvar" type="hidden" value="" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%" align="center">';

echo '<tr><td colspan=2><table><tr><td>'.dica('Módulo', 'Escolha na caixa de opção à direita o módulo no qual deseja marcar quais campos serão preenchidos quando da criação e edição de objetos do tipo específico..').'Módulo:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'campo_formulario_tipo', 'class="texto" size="1" onchange="env.submit();"', $campo_formulario_tipo).'</td></tr></table></td></tr>';

foreach($lista as $linha) echo '<tr><td width=16 ><input class="texto" type="checkbox" name="campo['.$linha['campo_formulario_campo'].']" value="1" '.($linha['campo_formulario_ativo'] ? 'checked="checked"': '').'  /></td><td>'.$linha['campo_formulario_descricao'].'</td></tr>';


echo '<tr><td colspan="2">'.botao('salvar', 'Salvar', 'Salvar as configurações.','','env.salvar.value=1; env.submit();').'</td></tr>';
echo '</table></form>';
echo estiloFundoCaixa();
?>