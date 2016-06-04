<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
if ($editarPG) {
	$Aplic->carregarCKEditorJS();
	}
$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');
$pg_principio_id= getParam($_REQUEST, 'pg_principio_id', '0');
$pg_principio_nome=getParam($_REQUEST, 'pg_principio_nome', '0');


$excluirponto_forte=getParam($_REQUEST, 'excluirponto_forte', '0');
$editarponto_forte=getParam($_REQUEST, 'editarponto_forte', '0');
$mudar_pg_principio_id=getParam($_REQUEST, 'mudar_pg_principio_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_principio_id" value="" />';
echo '<input type="hidden" name="mudar_pg_principio_id" value="" />';
echo '<input type="hidden" name="excluirponto_forte" value="" />';
echo '<input type="hidden" name="editarponto_forte" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';



//salvar dados na tabela
if ($pg_principio_nome && !$mudar_pg_principio_id && !$cancelar){
 	$sql->adTabela('plano_gestao_principios');
	$sql->adCampo('count(pg_principio_id) AS soma');
	$sql->adOnde('pg_principio_pg_id ='.(int)$pg_id);
  $soma_total = 1+(int)$sql->Resultado();
  $sql->Limpar();
	$sql->adTabela('plano_gestao_principios');
	$sql->adInserir('pg_principio_pg_id', $pg_id);
	$sql->adInserir('pg_principio_nome', $pg_principio_nome);
	$sql->adInserir('pg_principio_ordem', $soma_total);
	$sql->adInserir('pg_principio_data', date('Y-m-d H:i:s'));
	$sql->adInserir('pg_principio_usuario', $Aplic->usuario_id);
	$sql->exec();
	$pg_principio_id=$bd->Insert_ID('plano_gestao_principios','pg_principio_id');
	$sql->Limpar();
	}
if ($pg_principio_nome && $mudar_pg_principio_id && !$cancelar){
	$sql->adTabela('plano_gestao_principios');
	$sql->adAtualizar('pg_principio_nome', $pg_principio_nome);
	$sql->adAtualizar('pg_principio_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('pg_principio_usuario', $Aplic->usuario_id);
	$sql->adOnde('pg_principio_id ='.(int)$mudar_pg_principio_id);
	$sql->exec();
	$sql->Limpar();
	}



if ($excluirponto_forte){
	$sql->setExcluir('plano_gestao_principios');
	$sql->adOnde('pg_principio_id='.(int)$pg_principio_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela plano_gestao_principios!'.$bd->stderr(true));
	$sql->limpar();
	}


//ordenar ponto_fortees
if($direcao&&$pg_principio_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_principios');
		$sql->adOnde('pg_principio_id !='.(int)$pg_principio_id);
		$sql->adOnde('pg_principio_pg_id ='.(int)$pg_id);
		$sql->adOrdem('pg_principio_ordem');
		$principios = $sql->Lista();
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
			$novo_ui_ordem = count($principios) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($principios) + 1)) {
			$sql->adTabela('plano_gestao_principios');
			$sql->adAtualizar('pg_principio_ordem', $novo_ui_ordem);
			$sql->adOnde('pg_principio_id ='.(int)$pg_principio_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($principios as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_principios');
					$sql->adAtualizar('pg_principio_ordem', $idx);
					$sql->adOnde('pg_principio_id ='.(int)$acao['pg_principio_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('plano_gestao_principios');
					$sql->adAtualizar('pg_principio_ordem', $idx + 1);
					$sql->adOnde('pg_principio_id ='.(int)$acao['pg_principio_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}



//ordenar arquivo anexo
if($direcao&&$pg_arquivos_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_arquivos');
		$sql->adOnde('pg_arquivos_id !='.(int)$pg_arquivos_id);
		$sql->adOnde('pg_arquivo_pg_id ='.(int)$pg_id);
		$sql->adOrdem('pg_arquivo_ordem');
		$arquivos = $sql->Lista();
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
			$novo_ui_ordem = count($arquivos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($arquivos) + 1)) {
			$sql->adTabela('plano_gestao_arquivos');
			$sql->adAtualizar('pg_arquivo_ordem', $novo_ui_ordem);
			$sql->adOnde('pg_arquivos_id = '.(int)$pg_arquivos_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($arquivos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_arquivos');
					$sql->adAtualizar('pg_arquivo_ordem', $idx);
					$sql->adOnde('pg_arquivos_id = '.(int)$acao['pg_arquivos_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('plano_gestao_arquivos');
					$sql->adAtualizar('pg_arquivo_ordem', $idx + 1);
					$sql->adOnde('pg_arquivos_id = '.(int)$acao['pg_arquivos_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}


if ($excluiranexo){
	$sql->adTabela('plano_gestao_arquivos');
	$sql->adCampo('pg_arquivo_endereco');
	$sql->adOnde('pg_arquivos_id='.(int)$pg_arquivos_id);
	$caminho=$sql->Resultado();
	$sql->limpar();
	@unlink($base_dir.'/arquivos/gestao/'.$caminho);
	$sql->setExcluir('plano_gestao_arquivos');
	$sql->adOnde('pg_arquivos_id='.(int)$pg_arquivos_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela plano_gestao_arquivos!'.$bd->stderr(true));
	$sql->limpar();
	}


if ($salvaranexo){
	grava_arquivo_pg($pg_id, 'arquivo', 'Principio');
	}


if ($salvar){
	//checar se existe
	$sql->adTabela('plano_gestao2');
	$sql->adCampo('count(pg_id)');
	$sql->adOnde('pg_id='.(int)$pg_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	if ($existe) {
		$sql->adTabela('plano_gestao2');
		$sql->adAtualizar('pg_principio', getParam($_REQUEST, 'pg_principio', ''));
		$sql->adOnde('pg_id ='.(int)$pg_id);
		$retorno=$sql->exec();
		$sql->Limpar();
		}
	else {
		$sql->adTabela('plano_gestao2');
		$sql->adInserir('pg_principio', getParam($_REQUEST, 'pg_principio', ''));
		$sql->adInserir('pg_id', (int)$pg_id);
		$sql->exec();
		$sql->Limpar();
		}		
	}


$sql->adTabela('plano_gestao2');
$sql->adCampo('pg_principio');
$sql->adOnde('pg_id='.(int)$pg_id);
$pg=$sql->Linha();
$sql->limpar();

echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Princípios, Crenças e Valores Organizacionais</h1></td></tr>';
if ($editarPG || $pg['pg_principio']) echo '<tr><td colspan=2 align="left"><b>Informações gerais sobre os princípios, crenças e valores d'.$config['genero_organizacao'].' '.$config['organizacao'].'</b></td></tr>';
if ($editarPG) echo '<tr><td colspan=2 align="left"><table width="810"><tr><td style="width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="pg_principio" id="pg_principio" >'.$pg['pg_principio'].'</textarea></td></tr></table></td></tr>';
elseif($pg['pg_principio']) echo '<tr><td colspan=2 align="left"><table width="100%"><tr><td class="realce" width="100%">'.$pg['pg_principio'].'</td></tr></table></td></tr>';


//ponto_fortes

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>Princípio, Crença ou Valor Organizacional</b></td><td></td></tr>';
	if ($editarponto_forte) {
		$sql->adTabela('plano_gestao_principios');
		$sql->adCampo('pg_principio_nome');
		$sql->adOnde('pg_principio_id='.(int)$pg_principio_id);
		$principio=$sql->Linha();

		echo '<tr>';
		echo '<td width="810"><textarea data-gpweb-cmp="ckeditor" rows="3" name="pg_principio_nome" id="pg_principio_nome" style="width:800px; max-width:800px;">'.$principio['pg_principio_nome'].'</textarea></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.mudar_pg_principio_id.value='.(int)$pg_principio_id.'; env.submit();">'.imagem('icones/ok.png', 'Aceitar Alterações', 'Clique neste ícone '.imagem('icones/ok.png').' para aceitar a alteração inserida à esquerda.').'</a><a href="javascript:void(0);" onclick="javascript:env.cancelar.value=1; env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Alterações', 'Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar as alterações à esquerda.').'</a></td>';

		}
	else {
		echo '<tr><td width="810"><textarea data-gpweb-cmp="ckeditor" rows="3" name="pg_principio_nome" id="pg_principio_nome" ></textarea></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.submit();">'.imagem('icones/adicionar.png', 'Inserir Princípios, Crenças e Valores Organizacionais', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar o texto à esquerda.').'</a></td>';
		}
	echo '</tr></table></td></tr>';
	}


$sql->adTabela('plano_gestao_principios');
$sql->adCampo('*');
$sql->adOnde('pg_principio_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_principio_ordem ASC');
$principios=$sql->Lista();

if ($principios && count($principios)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="810"><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;'.(count($principios) >1 ? 'Princípios, Crenças e Valores Organizacionais':'Princípio, Crença ou Valor Organizacional').'&nbsp;</th>'.($editarPG ? '<th width="32"></th>' : '').'</tr>';
foreach ($principios as $principio) {
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$principio['pg_principio_ordem'].'; env.pg_principio_id.value='.(int)$principio['pg_principio_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$principio['pg_principio_ordem'].'; env.pg_principio_id.value='.(int)$principio['pg_principio_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$principio['pg_principio_ordem'].'; env.pg_principio_id.value='.(int)$principio['pg_principio_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$principio['pg_principio_ordem'].'; env.pg_principio_id.value='.(int)$principio['pg_principio_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$principio['pg_principio_nome'].'</td>';
	if ($editarPG) {
		echo '<td><a href="javascript: void(0);" onclick="env.editarponto_forte.value=1; env.pg_principio_id.value='.(int)$principio['pg_principio_id'].'; env.submit();">'.imagem('icones/editar.gif', 'Editar Princípios, Crenças e Valores Organizacionais', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o princípio, crença ou valor.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este principio, crença ou valor?\')) {env.excluirponto_forte.value=1; env.pg_principio_id.value='.(int)$principio['pg_principio_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Princípios, Crenças e Valores Organizacionais', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o princípio, crença ou valor.').'</a></td>';
		}
	echo '</tr>';
	}
if ($principios && count($principios)) echo '</table></td></tr>';







//arquivo anexo

if ($editarPG) echo'<tr><td colspan=2><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="30"></td><td width="720">'.($editarPG ? botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste botão para enviar arquivo e salvar o mesmo no sistema.','','env.salvaranexo.value=1; env.submit()') : '&nbsp').'</td></tr></table></td></tr>';

$sql->adTabela('plano_gestao_arquivos');
$sql->adCampo('pg_arquivos_id, pg_arquivo_usuario, pg_arquivo_data, pg_arquivo_ordem, pg_arquivo_pg_id,pg_arquivo_nome, pg_arquivo_endereco');
$sql->adOnde('pg_arquivo_pg_id='.(int)$pg_id);
$sql->adOnde('pg_arquivo_campo=\'Principio\'');
$sql->adOrdem('pg_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();

if ($arquivos && count($arquivos)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;'.(count($arquivos)>1 ? 'Arquivos Anexados':'Arquivo Anexado').'&nbsp;</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($arquivos as $arquivo) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.nome_funcao('', '', '', '',$arquivo['pg_arquivo_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['pg_arquivo_data']).'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td><a href="javascript:void(0);" onclick="javascript:pg_download('.(int)$arquivo['pg_arquivos_id'].');">&nbsp;'.dica($arquivo['pg_arquivo_nome'],$dentro).$arquivo['pg_arquivo_nome'].'</a></td>';
	if ($editarPG) echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {env.excluiranexo.value=1; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste ícone para excluir o arquivo.').'</a></td>';
	echo '</tr>';
	}
if ($arquivos && count($arquivos)) echo '</table></td></tr>';

echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\''.($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento_swot') ? 'ameacas' : 'visaofuturo').'\');').'</td><td width="40%">&nbsp;</td><td>'.($editarPG ? botao('salvar', 'Salvar', 'Salvar os dados acima.','','env.salvar.value=1; env.submit();') : '&nbsp').'</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'diretrizes_superiores_geral\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';
?>
