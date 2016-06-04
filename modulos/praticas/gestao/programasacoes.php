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
$pg_arquivo_pg_id = getParam($_REQUEST, 'pg_arquivo_pg_id', '0');
$ordem = getParam($_REQUEST, 'ordem', '0');

echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';

if($direcao) {
		$novo_ui_ordem = $ordem;
		$q = new BDConsulta;
		$q->adTabela('plano_gestao_arquivos');
		$q->adOnde('pg_arquivos_id !='.(int)$pg_arquivos_id);
		$q->adOnde('pg_arquivo_pg_id ='.(int)$pg_id);
		$q->adOnde('pg_arquivo_campo = \'ProgramasAcoes\'');
		$q->adOrdem('pg_arquivo_ordem');
		$arquivos = $q->Lista();
		$q->limpar();
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
			$q = new BDConsulta;
			$q->adTabela('plano_gestao_arquivos');
			$q->adAtualizar('pg_arquivo_ordem', $novo_ui_ordem);
			$q->adOnde('pg_arquivos_id ='.(int)$pg_arquivos_id);
			$q->exec();
			$q->limpar();
			$idx = 1;
			foreach ($arquivos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$q->adTabela('plano_gestao_arquivos');
					$q->adAtualizar('pg_arquivo_ordem', $idx);
					$q->adOnde('pg_arquivos_id ='.(int)$acao['pg_arquivos_id']);
					$q->exec();
					$q->limpar();
					$idx++;
					}
				else {
					$q->adTabela('plano_gestao_arquivos');
					$q->adAtualizar('pg_arquivo_ordem', $idx + 1);
					$q->adOnde('pg_arquivos_id ='.(int)$acao['pg_arquivos_id']);
					$q->exec();
					$q->limpar();
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
	grava_arquivo_pg($pg_id, 'arquivo', 'ProgramasAcoes');
	}


if ($salvar){
	$sql->adTabela('plano_gestao');
	$sql->adAtualizar('pg_programas_acoes', getParam($_REQUEST, 'pg_programas_acoes', ''));
	$sql->adOnde('pg_id ='.(int)$pg_id);
	$retorno=$sql->exec();
	$sql->Limpar();
	}


$sql->adTabela('plano_gestao');
$sql->adCampo('pg_programas_acoes');
$sql->adOnde('pg_id='.(int)$pg_id);
$pg=$sql->Linha();
$sql->limpar();

echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Programas e Ações</h1></td></tr>';
if ($editarPG || $pg['pg_programas_acoes']) echo '<tr><td colspan=2 align="left"><b>Descrição dos programas e açoes desenvolvidos pel'.$config['genero_organizacao'].' '.$config['organizacao'].'</b></td></tr>';
if ($editarPG) echo '<tr><td colspan=2 align="left"><table width="810"><tr><td style="width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="pg_programas_acoes" id="pg_programas_acoes" >'.$pg['pg_programas_acoes'].'</textarea></td></tr></table></td></tr>';
elseif($pg['pg_programas_acoes'])  echo '<tr><td colspan=2 align="left"><table width="100%"><tr><td class="realce" width="100%">'.$pg['pg_programas_acoes'].'</td></tr></table></td></tr>';

//arquivo anexo
$sql->adTabela('plano_gestao_arquivos');
$sql->adCampo('pg_arquivos_id, pg_arquivo_usuario, pg_arquivo_data, pg_arquivo_ordem, pg_arquivo_pg_id,pg_arquivo_nome, pg_arquivo_endereco');
$sql->adOnde('pg_arquivo_pg_id='.(int)$pg_id);
$sql->adOnde('pg_arquivo_campo=\'ProgramasAcoes\'');
$sql->adOrdem('pg_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();
if ($arquivos && count($arquivos))echo '<tr><td colspan=2><b>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</b></td></tr>';
foreach ($arquivos as $arquivo) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.nome_funcao('', '', '', '',$arquivo['pg_arquivo_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['pg_arquivo_data']).'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
	echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['pg_arquivo_ordem'].'; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td><a href="javascript:void(0);" onclick="javascript:pg_download('.(int)$arquivo['pg_arquivos_id'].');">'.dica($arquivo['pg_arquivo_nome'],$dentro).$arquivo['pg_arquivo_nome'].'</a></td>';
	if ($editarPG) echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {env.excluiranexo.value=1; env.pg_arquivos_id.value='.(int)$arquivo['pg_arquivos_id'].'; env.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste ícone para excluir o arquivo.').'</a></td>';
	echo '</tr></table></td></tr>';
	}
if ($editarPG) echo'<tr><td colspan=2><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="30"></td><td width="720">'.($editarPG ? botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste botão para enviar arquivo e salvar o mesmo no sistema.','','env.salvaranexo.value=1; env.submit()') : '&nbsp').'</td></tr></table></td></tr>';


if ($exibir['pessoal'] && $exibir['perfil']) $retorno='quadropessoal';
else if ($exibir['clientes'] && $exibir['perfil']) $retorno='clientes';
else if ($exibir['processos'] && $exibir['perfil']) $retorno='processos_produtos_servicos';
else if ($exibir['fornecedores'] && $exibir['perfil']) $retorno='fornecedores_insumos';
else if ($exibir['estrutura'] && $exibir['perfil']) $retorno='estrutura_organizacional';
else $retorno='';


echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.($retorno ? botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\''.$retorno.'\');') : '').'</td><td width="40%">&nbsp;</td><td>'.($editarPG ? botao('salvar', 'Salvar', 'Salvar os dados acima.','','env.salvar.value=1; env.submit();') : '&nbsp').'</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'premiacoes_geral\');').'</td></tr></table></td></tr>';
echo '</table>';
echo '</td></tr></table>';


?>
