<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

global $config, $exibir;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
if ($editarPG) {
	$Aplic->carregarCKEditorJS();
	}

$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');
$pg_premiacao_id= getParam($_REQUEST, 'pg_premiacao_id', '0');
$pg_premiacao_nome=getParam($_REQUEST, 'pg_premiacao_nome', '0');
$pg_premiacao_ano=getParam($_REQUEST, 'pg_premiacao_ano', '0');

$excluirfornecedor=getParam($_REQUEST, 'excluirfornecedor', '0');
$editarfornecedor=getParam($_REQUEST, 'editarfornecedor', '0');
$mudar_pg_premiacao_id=getParam($_REQUEST, 'mudar_pg_premiacao_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');
echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_premiacao_id" value="" />';
echo '<input type="hidden" name="mudar_pg_premiacao_id" value="" />';
echo '<input type="hidden" name="excluirfornecedor" value="" />';
echo '<input type="hidden" name="editarfornecedor" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';


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
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela plano_gestao_arquivos!'.$bd->stderr(true));
	$sql->limpar();
	}


if ($salvaranexo){
	grava_arquivo_pg($pg_id, 'arquivo', 'Premiacoes');
	}


if ($salvar){
	$sql->adTabela('plano_gestao');
	$sql->adAtualizar('pg_premiacoes', getParam($_REQUEST, 'pg_premiacoes', ''));
	$sql->adOnde('pg_id ='.(int)$pg_id);
	$retorno=$sql->exec();
	$sql->Limpar();
	}


$sql->adTabela('plano_gestao');
$sql->adCampo('pg_premiacoes');
$sql->adOnde('pg_id='.(int)$pg_id);
$pg=$sql->Linha();
$sql->limpar();

echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Premia��es</h1></td></tr>';
if ($editarPG || $pg['pg_premiacoes']) echo '<tr><td colspan=2 align="left"><b>Informa��es sobre as premia��es, em Qualidade e Gest�o, conquistadas pel'.$config['genero_organizacao'].' '.$config['organizacao'].'</b></td></tr>';
if ($editarPG) echo '<tr><td colspan=2 align="left"><table width="810"><tr><td style="width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="pg_premiacoes" id="pg_premiacoes" >'.$pg['pg_premiacoes'].'</textarea></td></tr></table></td></tr>';
elseif($pg['pg_premiacoes']) echo '<tr><td colspan=2 align="left"><table width="100%"><tr><td class="realce" width="100%">'.$pg['pg_premiacoes'].'</td></tr></table></td></tr>';


//arquivo anexo

if ($editarPG) echo'<tr><td colspan=2><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="30"></td><td width="720">'.($editarPG ? botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste bot�o para enviar arquivo e salvar o mesmo no sistema.','','env.salvaranexo.value=1; env.submit()') : '&nbsp').'</td></tr></table></td></tr>';

$sql->adTabela('plano_gestao_arquivos');
$sql->adCampo('pg_arquivos_id, pg_arquivo_usuario, pg_arquivo_data, pg_arquivo_ordem, pg_arquivo_pg_id,pg_arquivo_nome, pg_arquivo_endereco');
$sql->adOnde('pg_arquivo_pg_id='.(int)$pg_id);
$sql->adOnde('pg_arquivo_campo=\'Premiacoes\'');
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
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td><a href="javascript:void(0);" onclick="javascript:pg_download('.(int)$arquivo['pg_arquivos_id'].');">&nbsp;'.dica($arquivo['pg_arquivo_nome'],$dentro).$arquivo['pg_arquivo_nome'].'</a></td>';
	if ($editarPG) echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {env.excluiranexo.value=1; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste �cone para excluir o arquivo.').'</a></td>';
	echo '</tr>';
	}
if ($arquivos && count($arquivos)) echo '</table></td></tr>';

if ($exibir['programa'] && $exibir['perfil']) $retorno='programasacoes';
else if ($exibir['pessoal'] && $exibir['perfil']) $retorno='quadropessoal';
else if ($exibir['clientes'] && $exibir['perfil']) $retorno='clientes';
else if ($exibir['processos'] && $exibir['perfil']) $retorno='processos_produtos_servicos';
else if ($exibir['fornecedores'] && $exibir['perfil']) $retorno='fornecedores_insumos';
else if ($exibir['estrutura'] && $exibir['perfil']) $retorno='estrutura_organizacional';
else $retorno='';


echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.($retorno ? botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\''.$retorno.'\');') : '').'</td><td width="40%">&nbsp;</td><td>'.($editarPG ? botao('salvar', 'Salvar', 'Salvar os dados acima.','','env.salvar.value=1; env.submit();') : '&nbsp').'</td><td width="40%">&nbsp;</td><td>'.botao('pr�ximo', 'Pr�ximo', 'Ir para a pr�xima tela.','','carregar(\'premiacoes\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';

?>
