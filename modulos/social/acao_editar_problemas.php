<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


$social_acao_id=getParam($_REQUEST, 'social_acao_id', null);
$social_acao_problema_id= getParam($_REQUEST, 'social_acao_problema_id', null);
if ($social_acao_problema_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$social_acao_problema_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');

$tipo=getParam($_REQUEST, 'tipo', 0);

$social_acao_problema_descricao=getParam($_REQUEST, 'social_acao_problema_descricao', null);
$excluirsocial_acao=getParam($_REQUEST, 'excluirsocial_acao', '0');
$editarsocial_acao=getParam($_REQUEST, 'editarsocial_acao', '0');
$mudar_social_acao_problema_id=getParam($_REQUEST, 'mudar_social_acao_problema_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');
$sql = new BDConsulta;

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="social_acao_id" value="'.$social_acao_id.'" />';
echo '<input type="hidden" name="tipo" value="'.$tipo.'" />';
echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="social_acao_problema_id" value="" />';
echo '<input type="hidden" name="mudar_social_acao_problema_id" value="" />';
echo '<input type="hidden" name="excluirsocial_acao" value="" />';
echo '<input type="hidden" name="editarsocial_acao" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';



//salvar dados na tabela
if ($inserir && !$mudar_social_acao_problema_id && !$cancelar){
 	$sql->adTabela('social_acao_problema');
	$sql->adCampo('count(social_acao_problema_id) AS soma');
	$sql->adOnde('social_acao_problema_acao_id ='.(int)$social_acao_id);	
	$sql->adOnde('social_acao_problema_tipo='.(int)$tipo);
  $soma_total = 1+(int)$sql->Resultado();
  $sql->Limpar();
	$sql->adTabela('social_acao_problema');
	$sql->adInserir('social_acao_problema_acao_id', (int)$social_acao_id);
	$sql->adInserir('social_acao_problema_tipo', (int)$tipo);
	$sql->adInserir('social_acao_problema_descricao', $social_acao_problema_descricao);
	$sql->adInserir('social_acao_problema_ordem', $soma_total);
	$sql->exec();
	$social_acao_problema_id=$bd->Insert_ID('social_acao_problema','social_acao_problema_id');
	$sql->Limpar();
	}
if ($alterar && $mudar_social_acao_problema_id && !$cancelar){
 	
	$sql->adTabela('social_acao_problema');
	$sql->adAtualizar('social_acao_problema_descricao', $social_acao_problema_descricao);
	$sql->adOnde('social_acao_problema_id = '.$mudar_social_acao_problema_id);
	$sql->exec();
	$sql->Limpar();
	}



if ($excluirsocial_acao){
	$sql->setExcluir('social_acao_problema');
	$sql->adOnde('social_acao_problema_id='.(int)$social_acao_problema_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela social_acao_problema!'.$bd->stderr(true));
	$sql->limpar();	
	}


//ordenar social_acaoes
if($direcao&&$social_acao_problema_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('social_acao_problema');
		$sql->adOnde('social_acao_problema_id != '.(int)$social_acao_problema_id);
		$sql->adOnde('social_acao_problema_acao_id = '.(int)$social_acao_id);
		$sql->adOnde('social_acao_problema_tipo='.(int)$tipo);
		$sql->adOrdem('social_acao_problema_ordem');
		$linhas_social_acao = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($linhas_social_acao) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($linhas_social_acao) + 1)) {
			$sql->adTabela('social_acao_problema');
			$sql->adAtualizar('social_acao_problema_ordem', $novo_ui_ordem);
			$sql->adOnde('social_acao_problema_id = '.$social_acao_problema_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($linhas_social_acao as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('social_acao_problema');
					$sql->adAtualizar('social_acao_problema_ordem', $idx);
					$sql->adOnde('social_acao_problema_id = '.(int)$acao['social_acao_problema_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('social_acao_problema');
					$sql->adAtualizar('social_acao_problema_ordem', $idx + 1);
					$sql->adOnde('social_acao_problema_id = '.(int)$acao['social_acao_problema_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}


if ($tipo==1) $titulo='no Comitê Nacional';
elseif ($tipo==2) $titulo='nas Coordenações Regionais';
elseif ($tipo==3) $titulo='nos Comitês Municipais';
elseif ($tipo==4) $titulo='nas Comissões Comunitárias';
elseif ($tipo==5) $titulo='nas Superintendências';
else $titulo='nos Beneficiados';
$botoesTitulo = new CBlocoTitulo('Possíveis Problemas na Execução da Ação Social '.$titulo, '../../../modulos/social/imagens/acao.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=acao_ver&social_acao_id='.$social_acao_id, 'ver','','Ver esta Ação Social','Ver os detalhes desta ação social.');
$botoesTitulo->mostrar();

	
echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellpadding=0 cellspacing=0>';  
echo '<tr><td colspan=20 align="center"><table cellpadding=0 cellspacing=0>';
if ($editarsocial_acao) {
	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_id='.(int)$social_acao_problema_id);
	$linha_social_acao=$sql->Linha();
	
	echo '<tr>';
	echo '<td align="left"><b>'.dica('Descrição','Qual a descrição do possível problema durante a execução da ação social em uma família.').'<b>Descrição:</b>'.dicaF().'<input type="text" name="social_acao_problema_descricao" id="social_acao_problema_descricao" value="'.$linha_social_acao['social_acao_problema_descricao'].'" style="width:500px;" class="texto" /></td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:env.alterar.value=1; env.mudar_social_acao_problema_id.value='.$social_acao_problema_id.'; env.submit();">'.imagem('icones/ok.png', 'Aceitar Alterações', 'Clique neste ícone '.imagem('icones/ok.png').' para aceitar as alterações na descrição do problema.').'</a><a href="javascript:void(0);" onclick="javascript:env.cancelar.value=1; env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Alterações', 'Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar as alterações na justificativa da ação social inserida à esquerda.').'</a></td>';
	}
else {
	echo '<tr>';
	echo '<td align="left"><b>'.dica('Descrição','Qual a descrição do possível problema durante a execução da ação social em uma família.').'<b>Descrição:</b>'.dicaF().'<input type="text" name="social_acao_problema_descricao" id="social_acao_problema_descricao" value="" style="width:500px;" class="texto" /></td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:env.inserir.value=1; env.submit();">'.imagem('icones/adicionar.png', 'Inserir', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar a descrição do problema.').'</a></td>';
	}
echo '</tr></table></td></tr>';



$sql->adTabela('social_acao_problema');
$sql->adCampo('*');
$sql->adOnde('social_acao_problema_acao_id='.(int)$social_acao_id);
$sql->adOnde('social_acao_problema_tipo='.(int)$tipo);
$sql->adOrdem('social_acao_problema_ordem ASC');
$linhas_social_acao=$sql->Lista();
if ($linhas_social_acao && count($linhas_social_acao)) echo '<tr><td colspan=20>&nbsp;</td></tr><tr><td colspan=20 align=center><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th></th><th>&nbsp;'.dica((count($linhas_social_acao)>1 ? 'Justificativas':'Justificativa'),'Qual o texto '.(count($linhas_social_acao)>1 ? 'das justificativas':'da justificativa').' constante na ação social.').(count($linhas_social_acao)>1 ? 'Justificativas':'Justificativa').'&nbsp;</th><th></th></tr>';
foreach ($linhas_social_acao as $linha_social_acao) {
	echo '<tr>';
	echo '<td nowrap="nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.$linha_social_acao['social_acao_problema_ordem'].'; env.social_acao_problema_id.value='.$linha_social_acao['social_acao_problema_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.$linha_social_acao['social_acao_problema_ordem'].'; env.social_acao_problema_id.value='.$linha_social_acao['social_acao_problema_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.$linha_social_acao['social_acao_problema_ordem'].'; env.social_acao_problema_id.value='.$linha_social_acao['social_acao_problema_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.$linha_social_acao['social_acao_problema_ordem'].'; env.social_acao_problema_id.value='.$linha_social_acao['social_acao_problema_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	echo '<td>'.$linha_social_acao['social_acao_problema_descricao'].'</td>';
	echo '<td><a href="javascript: void(0);" onclick="env.editarsocial_acao.value=1; env.social_acao_problema_id.value='.$linha_social_acao['social_acao_problema_id'].'; env.submit();">'.imagem('icones/editar.gif', 'Editar Negativa', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta justificativa.').'</a>';
	echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta justificativa?\')) {env.excluirsocial_acao.value=1; env.social_acao_problema_id.value='.$linha_social_acao['social_acao_problema_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Negativa', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta justificativa da ação social.').'</a></td>';
	echo '</tr>';
	}
if ($linhas_social_acao && count($linhas_social_acao)) echo '</table></td></tr>';


echo '</table>';
echo '</td></tr></table>';

echo estiloFundoCaixa();
?>
