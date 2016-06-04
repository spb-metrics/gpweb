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
$pg_objetivo_estrategico_id= getParam($_REQUEST, 'pg_objetivo_estrategico_id', '0');
$pg_objetivo_estrategico_nome=getParam($_REQUEST, 'pg_objetivo_estrategico_nome', '0');


$pg_objetivo_estrategico_usuario=getParam($_REQUEST, 'pg_objetivo_estrategico_usuario', '0');

$excluir_obj_estrategico=getParam($_REQUEST, 'excluir_obj_estrategico', '0');
$editar_obj_estrategico=getParam($_REQUEST, 'editar_obj_estrategico', '0');
$mudar_pg_objetivo_estrategico_id=getParam($_REQUEST, 'mudar_pg_objetivo_estrategico_id', '0');
$pg_objetivo_estrategico_perspectiva=getParam($_REQUEST, 'pg_objetivo_estrategico_perspectiva', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');

$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');


echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_objetivo_estrategico_id" value="" />';
echo '<input type="hidden" name="mudar_pg_objetivo_estrategico_id" value="" />';
echo '<input type="hidden" name="excluir_obj_estrategico" value="" />';
echo '<input type="hidden" name="editar_obj_estrategico" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';
echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';



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
	grava_arquivo_pg($pg_id, 'arquivo', 'ObjEstrategicos');
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
		$sql->adAtualizar('pg_objetivo_estrategico', getParam($_REQUEST, 'pg_objetivo_estrategico', ''));
		$sql->adOnde('pg_id ='.(int)$pg_id);
		$retorno=$sql->exec();
		$sql->Limpar();
		}
	else {
		$sql->adTabela('plano_gestao2');
		$sql->adInserir('pg_objetivo_estrategico', getParam($_REQUEST, 'pg_objetivo_estrategico', ''));
		$sql->adInserir('pg_id', (int)$pg_id);
		$sql->exec();
		$sql->Limpar();
		}		
	}


$sql->adTabela('plano_gestao2');
$sql->adCampo('pg_objetivo_estrategico');
$sql->adOnde('pg_id='.(int)$pg_id);
$pg=$sql->Linha();
$sql->limpar();

echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>'.ucfirst($config['objetivos']).' e Organizacionais</h1></td></tr>';
if ($editarPG || $pg['pg_objetivo_estrategico']) echo '<tr><td colspan=2 align="left"><b>Informações gerais sobre '.$config['genero_objetivo'].'s '.$config['objetivos'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].'</b></td></tr>';
if ($editarPG) echo '<tr><td colspan=2 align="left"><table width="810"><tr><td style="width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="pg_objetivo_estrategico" id="pg_objetivo_estrategico" >'.$pg['pg_objetivo_estrategico'].'</textarea></td></tr></table></td></tr>';
elseif($pg['pg_objetivo_estrategico']) echo '<tr><td colspan=2 align="left"><table width="100%"><tr><td class="realce" width="100%">'.$pg['pg_objetivo_estrategico'].'</td></tr></table></td></tr>';



//arquivo anexo

if ($editarPG) echo'<tr><td colspan=2><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="30"></td><td width="720">'.($editarPG ? botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste botão para enviar arquivo e salvar o mesmo no sistema.','','env.salvaranexo.value=1; env.submit()') : '&nbsp').'</td></tr></table></td></tr>';

$sql->adTabela('plano_gestao_arquivos');
$sql->adCampo('pg_arquivos_id, pg_arquivo_usuario, pg_arquivo_data, pg_arquivo_ordem, pg_arquivo_pg_id,pg_arquivo_nome, pg_arquivo_endereco');
$sql->adOnde('pg_arquivo_pg_id='.(int)$pg_id);
$sql->adOnde('pg_arquivo_campo=\'ObjEstrategicos\'');
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

echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'temas\');').'</td><td width="40%">&nbsp;</td><td>'.($editarPG ? botao('salvar', 'Salvar', 'Salvar os dados acima.','','env.salvar.value=1; env.submit();') : '&nbsp').'</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'objetivos_estrategicos\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';

?>

<script language="javascript">
function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('pg_objetivo_estrategico_usuario').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('pg_objetivo_estrategico_usuario').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('pg_objetivo_estrategico_usuario').value=usuario_id;
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}

var contatos_id_selecionados = '<?php echo implode(',', $usuarios)?>';


function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&cia_id='+document.getElementById('cia_id').value+'&chamar_volta=setUsuarios&usuarios_id_selecionados='+contatos_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&cia_id='+document.getElementById('cia_id').value+'&chamar_volta=setUsuarios&usuarios_id_selecionados='+contatos_id_selecionados, '<?php echo ucfirst($config["usuarios"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.pg_objetivo_estrategico_usuarios.value = usuario_id_string;
	contatos_id_selecionados = usuario_id_string;
	}



var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';
function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('cia_id').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('cia_id').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pg_objetivo_estrategico_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>