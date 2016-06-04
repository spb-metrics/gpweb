<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();
require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');
$Aplic->carregarCalendarioJS();
if (!$dialogo) $Aplic->salvarPosicao();
$inserir=getParam($_REQUEST, 'inserir', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);
$novo_modelo=getParam($_REQUEST, 'novo_modelo', 0);
$excluir_modelo=getParam($_REQUEST, 'excluir_modelo', array());
$modelo_tipo_id=getParam($_REQUEST, 'modelo_tipo_id', 0);
$posicao=getParam($_REQUEST, 'posicao', 0);
$excluir_campo=getParam($_REQUEST, 'excluir_campo', 0);
$novo_nome=getParam($_REQUEST, 'novo_nome', '');
$tipo_campo_novo=getParam($_REQUEST, 'tipo_campo_novo', '');
$tipo_campo_alterar=getParam($_REQUEST, 'tipo_campo_alterar', array());
$salvar=getParam($_REQUEST, 'salvar', 0);
$carregar_modelo=getParam($_REQUEST, 'carregar_modelo', 0);
$adicionar_campo=getParam($_REQUEST, 'adicionar_campo', 0);
$tipos_campos=array(
''=>'',
'anexo'=>'Anexar arquivos',
'bloco'=>'Bloco de texto com formatação',
'bloco_poucobotao'=>'Bloco de texto com formatação para áreas pequenas',
'bloco_simples'=>'Bloco de texto sem formatação',
'bloco_sem_paragrafo'=>'Bloco de texto formatado sem indentação',
'cabecalho'=>'Cabeçalho d'.$config['genero_organizacao'].' '.$config['organizacao'],
'checar'=>'Caixa de marcar',
'cidade'=>'Município d'.$config['genero_organizacao'].' '.$config['organizacao'],
'data'=>'Data',
'endereco'=>'Endereço d'.$config['genero_organizacao'].' '.$config['organizacao'],
'ao'=>'Escolher Ao/À',
'do'=>'Escolher Do/Da',
'legenda'=>'Legenda na edição',
'remetente'=>'Função do remetente',
'texto'=>'linha de texto',
'destinatarios'=>'Lista de destinatários',
'impedimento'=>'No impedimento/Nome e função para assinatura',
'assinatura'=>'Nome e função para assinatura',
'nome_organizacao'=>'Nome d'.$config['genero_organizacao'].' '.$config['organizacao'],
'numeracao_cresc'=>'Inserir númeração crescente',
'numeracao_aumentar'=>'Aumentar a númeração',
'numeracao_diminuir'=>'Diminuir a númeração',
'numeracao_zerar'=>'Zerar a númeração',
'paragrafo_num_for'=>'Parágrafo númerado com formatação',
'paragrafo_num'=>'Parágrafo númerado sem formatação',
'protocolo'=>'Numeração do documento',
'protocolo_secao'=>'Numeração do documento de '.$config['departamento'],
'tipo_modelo'=>'Tipo de documento',
'urgente'=>'Urgente/Urgentíssimo',
'vocativo'=>'Vocativo',
'vocativo_end'=>'Vocativo para endereçamento',
'fecho'=>'Fecho de mensagem',
'em_no_na'=>'em/no/na',
'botao_organizacao'=>'Botão de seleção de '.$config['organizacao'],
'organizacao_nome'=>'   - Nome d'.$config['genero_organizacao'].' '.$config['organizacao'],
'organizacao_end'=>'   - Endereço d'.$config['genero_organizacao'].' '.$config['organizacao'],
'organizacao_cep'=>'   - CEP d'.$config['genero_organizacao'].' '.$config['organizacao'],
'organizacao_tel1'=>'   - Telefone d'.$config['genero_organizacao'].' '.$config['organizacao'],
'organizacao_fax'=>'   - Fax d'.$config['genero_organizacao'].' '.$config['organizacao'],
'organizacao_cidade'=>'   - Cidade d'.$config['genero_organizacao'].' '.$config['organizacao'],
'organizacao_estado'=>'   - Estado d'.$config['genero_organizacao'].' '.$config['organizacao'],
'organizacao_end_completo'=>'   - Endereço completo d'.$config['genero_organizacao'].' '.$config['organizacao'],
'organizacao_logo'=>'   - Logotipo d'.$config['genero_organizacao'].' '.$config['organizacao']);

$sql = new BDConsulta;
$posicao=0;

if($carregar_modelo && $modelo_tipo_id){
	if(isset($_FILES['modelo']['name']) && file_exists($_FILES['modelo']['tmp_name']) && !empty($_FILES['modelo']['tmp_name']) && $_FILES['modelo']["size"]>0){
		move_uploaded_file($_FILES['modelo']['tmp_name'], BASE_DIR.'/modulos/email/modelos/'.$config['militar'].'/modelo'.$modelo_tipo_id.'.html');

		$html=file_get_contents(BASE_DIR.'/modulos/email/modelos/'.$config['militar'].'/modelo'.$modelo_tipo_id.'.html');

		$sql->adTabela('modelos_tipo');
		$sql->adAtualizar('modelo_tipo_html', $html);
		$sql->adOnde('modelo_tipo_id='.(int)$modelo_tipo_id);
		$sql->exec();
		$sql->limpar();

		echo '<script>alert("Arquivo enviado com sucesso.")</script>';
		}
	else echo '<script>alert("Houve um erro no envio do arquivo.")</script>';
	$carregar_modelo=0;


	}

if($novo_modelo){
	if(isset($_FILES['modelo']['name']) && file_exists($_FILES['modelo']['tmp_name']) && !empty($_FILES['modelo']['tmp_name']) && $_FILES['modelo']["size"]>0){

		$sql->adTabela('modelos_tipo');
		$sql->adInserir('modelo_tipo_nome', $novo_modelo);
		$sql->adInserir('organizacao', $config['militar']);
		$sql->adInserir('imagem', getParam($_REQUEST, 'imagem', null));
		$sql->adInserir('descricao', getParam($_REQUEST, 'descricao', null));
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela modelos_tipo');
		$modelo_tipo_id=$bd->Insert_ID('modelos_tipo','modelo_tipo_id');
		$sql->limpar();

		if (!is_dir(BASE_DIR.'/modulos/email/modelos/'.$config['militar'])){
			$res = mkdir(BASE_DIR.'/modulos/email/modelos/'.$config['militar'], 0777);
			if (!$res) {
				$Aplic->setMsg('A pasta para modelos de documentos não foi configurada para receber arquivos html - mude as permissões escrita no diretório /modulos/email/modelos.', UI_MSG_ALERTA);
				}
			}


		move_uploaded_file($_FILES['modelo']['tmp_name'], BASE_DIR.'/modulos/email/modelos/'.$config['militar'].'/modelo'.$modelo_tipo_id.'.html');

		$html=file_get_contents(BASE_DIR.'/modulos/email/modelos/'.$config['militar'].'/modelo'.$modelo_tipo_id.'.html');

		$sql->adTabela('modelos_tipo');
		$sql->adAtualizar('modelo_tipo_html', $html);
		$sql->adOnde('modelo_tipo_id='.(int)$modelo_tipo_id);
		$sql->exec();
		$sql->limpar();

		echo '<script>alert("Modelo criado com sucesso. Altere o modelo para inserir os tipos de campos que o mesmo contêm.")</script>';
		}
	else echo '<script>alert("Houve um erro no envio do arquivo.")</script>';
	$carregar_modelo=0;
	$novo_modelo=0;
	}

if($excluir_campo){
	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_campos');
	$sql->adOnde('modelo_tipo_id='.$modelo_tipo_id);
	$campos = unserialize($sql->Resultado());
	$sql->limpar();
	$qnt_campos=count($campos['campo']);
	$campos_depois=array();
	for ($i=1; $i < $qnt_campos; $i++) $campos_depois[$i]=$campos['campo'][$i];
	$campos['campo']=$campos_depois;
	$sql->adTabela('modelos_tipo');
	$sql->adAtualizar('modelo_tipo_campos', serialize($campos));
	$sql->adOnde('modelo_tipo_id='.$modelo_tipo_id);
	if (!$sql->exec()) die('Não foi possível alterar modelos_tipo.');
	$sql->limpar();
	$excluir_campo=0;
	echo '<script>alert("Campo foi excluídos.")</script>';
	}

if ($modelo_tipo_id && $salvar){
	$tipo_campo_extra=getParam($_REQUEST, 'tipo_campo_extra', array());
	$tipo_larg_max=getParam($_REQUEST, 'tipo_larg_max', array());
	$outro_campo=getParam($_REQUEST, 'outro_campo', array());

	$modelo= new Modelo;
	$modelo->set_modelo_tipo($modelo_tipo_id);
	foreach($tipo_campo_alterar as $posicao => $tipo_campo) {
		$modelo->set_campo($tipo_campo, ($tipo_campo !='data' ? getParam($_REQUEST, 'campo_'.$posicao, '') : null), $posicao, $tipo_campo_extra[$posicao], $tipo_larg_max[$posicao], $outro_campo[$posicao]);
		}
	//checar depois
	if ($tipo_campo_novo) $modelo->set_campo($tipo_campo_novo, ($tipo_campo_novo !='data' ? getParam($_REQUEST, 'campo_'.($posicao+1), '') : null), $posicao+1, (isset($tipo_campo_extra[$posicao+1]) ? $tipo_campo_extra[$posicao+1] : ''),  (isset($tipo_larg_max[$posicao+1]) ? $tipo_larg_max[$posicao+1] : ''),  (isset($outro_campo[$posicao+1]) ? $outro_campo[$posicao+1] : ''));
	$vars = get_object_vars($modelo);
	$sql->adTabela('modelos_tipo');
	$sql->adAtualizar('modelo_tipo_campos', serialize($vars));
	$sql->adOnde('modelo_tipo_id='.$modelo_tipo_id);
	if (!$sql->exec()) die('Não foi possível alterar modelos_tipo.');
	$sql->limpar();
	if ($adicionar_campo) {
		$alterar=1;
		echo '<script>alert("Campo adicionado no modelo.")</script>';
		}
	else {
		$alterar=0;
		echo '<script>alert("Alterações no modelo foram gravadas.")</script>';
		}
	$salvar=0;
  }

if ($modelo_tipo_id && $alterar){
	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_nome, descricao, imagem, modelo_tipo_campos');
	$sql->adOnde('modelo_tipo_id='.$modelo_tipo_id);
	$rs = $sql->Linha();
	$sql->Limpar();
  }
if ($novo_nome && $modelo_tipo_id){
	$sql->adTabela('modelos_tipo');
	$sql->adAtualizar('modelo_tipo_nome', $novo_nome);
	$sql->adAtualizar('imagem', getParam($_REQUEST, 'imagem', null));
	$sql->adAtualizar('descricao', getParam($_REQUEST, 'descricao', null));
	$sql->adOnde('modelo_tipo_id='.$modelo_tipo_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela modelos_tipo!'.$bd->stderr(true));
	$sql->limpar();
	}
if (count($excluir_modelo)>0){
	foreach ((array)$excluir_modelo as $modelo_id) @unlink(($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR).'/modulos/email/modelos/'.$config['militar'].'/modelo'.$modelo_id.'.html');
	$sql->setExcluir('modelos_tipo');
	$sql->adOnde('modelo_tipo_id IN ('.implode(',',(array)$excluir_modelo).')');
	if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela modelos_tipo!'.$bd->stderr(true));
	$sql->limpar();
	}

echo '<form method="POST" id="env" enctype="multipart/form-data" name="env">';
echo '<input type=hidden id="m" name="m" value="sistema">';
echo '<input type=hidden id="a" name="a" value="modelos_documentos">';
echo '<input type=hidden id="u" name="u" value="email">';
echo '<input type=hidden name="inserir" id="inserir" value="">';
echo '<input type=hidden name="alterar" id="alterar" value="">';
echo '<input type=hidden name="excluir_campo" id="excluir_campo" value="">';
echo '<input type=hidden name="salvar" id="salvar" value="">';
echo '<input type=hidden name="carregar_modelo" id="carregar_modelo" value="">';
echo '<input type=hidden name="novo_modelo" id="novo_modelo" value="">';
echo '<input type=hidden name="modelo_tipo_id" id="modelo_tipo_id" value="'.$modelo_tipo_id.'">';
echo '<input type=hidden name="excluir_modelo" id="excluir_modelo" value="">';
echo '<input type=hidden name="posicao" id="posicao" value="">';
echo '<input type=hidden name="adicionar_campo" id="adicionar_campo" value="">';


$botoesTitulo = new CBlocoTitulo('Modelos', 'modelos.jpg', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table width="100%" align="center" class="std2" cellspacing=0 cellpadding=0>';
if (!$inserir && !$alterar) {
	echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td width="200" valign="top"><fieldset><legend class=texto style="color: black;">&nbsp;<b>Modelos de documentos</b>&nbsp;</legend>';
	echo '<select name=ListaModelo[] id="ListaModelo" size=8 style="width:100%;" ondblClick="">';
	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_id, modelo_tipo_nome');
	$sql->adOnde('organizacao='.$config['militar']);
	$sql_resultado = $sql->Lista();
	$sql->Limpar();
	foreach ($sql_resultado as $linha) echo '<option value="'.$linha['modelo_tipo_id'].'">'.$linha['modelo_tipo_nome'].'</option>';
	echo '</option></select></fieldset></td><td>&nbsp;</td></tr>';
	echo '<tr><td colspan=20><table width="100%" cellspacing=0 cellpadding=0><tr>';
	echo '<td style="width:50pt">'.dica("Inserir","Clique neste botão para inserir um novo modelo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.inserir.value=1; env.submit();"><span><b>inserir</b></span></a>'.dicaF().'</td>';
	echo '<td style="width:50pt">'.dica("Editar","Clique neste botão para editar um modelo da caixa de seleção acima.").'<a class="botao" href="javascript:void(0);" onclick="javascript: alterar_modelo()"><span><b>editar</b></span></a></td>';
	echo '<td style="width:50pt">'.dica("Excluir","Clique neste botão para excluir os modelos selecionados da caixa de seleção acima.<br><br>Para excluir múltiplos modelos, selecione estes com a tecla CTRL pressionada.").'<a class="botao" href="javascript:void(0);" onclick="javascript:excluir()"><span><b>excluir</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica("Voltar","Clique neste botão para voltar à tela do sistema.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'index\'; env.u.value=\'\'; env.submit();"><span><b>voltar</b></span></a>'.dicaF().'</td></tr></table>';
	echo '</td></tr>';
	}
elseif ($inserir){
	echo '<tr><td align=right>'.dica('Nome','Nome do modelo de documento').'Nome : '.dicaF().'</td><td colspan=20><input type=text class="texto" name="nome_novo_modelo" id="nome_novo_modelo" style="width:100pt"> '.dica('Descrição','Informações sobre este modelo de documento a ser mostrado no menu de seleção de documento a ser criado.').'Descrição: '.dicaF().'<input type=text class="texto" name="descricao" id="descricao" style="width:250pt">	'.dica('Imagem','Escreva o nome do ícone a ser mostrado no menu de seleção de documento a ser criado, ex: <b>oficio.gif</b> .<br><br>Local para colocar o ícone: gpweb/estilo/rondon/imagens/icones/').'Imagem: '.dicaF().'<input type=text class="texto" name="imagem" id="imagem" style="width:100pt"></td></tr>';
	echo '<tr><td align="right">Modelo HTML :&nbsp;</td><td><input type="file" class="arquivo" name="modelo" size="45"></td></tr>';
	echo '<tr><td colspan=20><table><tr><td>'.botao('salvar', 'Salvar','Clique neste botão para salvar a inserção do novo modelo.','','if (env.nome_novo_modelo.value.length>0) {env.novo_modelo.value=env.nome_novo_modelo.value; env.submit();} else alert (\'Escreva o nome do modelo!\');').'</td><td width="100%">&nbsp;</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para cancelar a inserção do novo modelo.','','env.submit();').'</td></tr></table></td></tr>';
	echo '</table>';
	}
else {
	echo '<tr><td><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align=right>'.dica('Nome','Nome do modelo de documento').'Nome : '.dicaF().'</td><td colspan=20><input type=text class="texto" name="novo_nome" id="novo_nome" value="'.$rs['modelo_tipo_nome'].'" style="width:100pt"> '.dica('Descrição','Informações sobre este modelo de documento a ser mostrado no menu de seleção de documento a ser criado.').'Descrição: '.dicaF().'<input type=text class="texto" name="descricao" id="descricao" value="'.$rs['descricao'].'" style="width:250pt">	'.dica('Imagem','Escreva o nome do ícone a ser mostrado no menu de seleção de documento a ser criado, ex: <b>oficio.gif</b> .<br><br>Local para colocar o ícone: gpweb/estilo/rondon/imagens/icones/').'Imagem: '.dicaF().'<input type=text class="texto" name="imagem" id="imagem" value="'.$rs['imagem'].'" style="width:100pt"></td></tr>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_campos, modelo_tipo_html');
	$sql->adOnde('modelo_tipo_id='.$modelo_tipo_id);
	$linha=$sql->linha();
	$sql->limpar();

	$campos = unserialize($linha['modelo_tipo_campos']);

	$posicao=0;
	$qnt_campos=count((array)$campos['campo']);
	foreach((array)$campos['campo'] as $posicao => $campo) {
		echo '<tr><td align="right">campo '.$posicao.' :&nbsp;</td><td>'.selecionaVetor($tipos_campos, 'tipo_campo_alterar['.$posicao.']', 'class="texto"', $campo['tipo']).'</td><td>'.dica('HTML Extra', 'Pode-se inserir dados extras de formatação do campo,tais como largura<br>ex: style="width:300px;"').'&nbsp;&nbsp;HTML extra: '.dicaF().'<input type="text" class="texto" style="width:200px;" name="tipo_campo_extra['.$posicao.']" value="'.htmlspecialchars($campo['extra'], ENT_QUOTES, $localidade_tipo_caract).'" /></td><td>'.dica('Largura máxima', 'Insira a largura máxima do campo, em números de caracteres. Caso o texto passe da largura inserida, será inserida uma quebra de linha."').'&nbsp;&nbsp;Larg. Max: '.dicaF().'<input type="text" class="texto" style="width:50px;" name="tipo_larg_max['.$posicao.']" value="'.(isset($campo['larg_max']) ? $campo['larg_max'] : '').'" /></td><td>'.dica('Campo Pai', 'Caso este campo seja um subcampo deduzido a partir de outro, coloque o número deste outro campo.<br><br>ex: campo 3 é botão de selecionar '.$config['organizacao'].' e campo6 é telefone d'.$config['genero_organizacao'].' '.$config['organizacao'].', logo no campo 6 deve-se preencher 3.').'&nbsp;&nbsp;Campo pai: '.dicaF().'<input type="text" class="texto" style="width:20px;" name="outro_campo['.$posicao.']" value="'.(isset($campo['outro_campo']) ? $campo['outro_campo'] : '').'" /></td><td width="16">'.($posicao==$qnt_campos ? '<a href="javascript:void(0);" onclick="env.posicao.value='.$posicao.'; env.alterar.value=1; env.excluir_campo.value=1; env.submit();">'.imagem('icones/remover.png','Excluir Campo','Clique neste icone '.imagem('icones/remover.png').' para excluir este campo').'</a>' : '&nbsp;').'</td></tr>';
		}
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
	echo '<tr><td align="right">novo '.($posicao+1).' :&nbsp;</td><td>'.selecionaVetor($tipos_campos, 'tipo_campo_novo', 'class="texto"').'</td><td>'.dica('HTML Extra', 'Pode-se inserir dados extras de formatação do campo,tais como largura<br>ex: style="width:300px;"').'&nbsp;&nbsp;HTML extra: '.dicaF().'<input type="text" class="texto" style="width:200px;" id="tipo_campo_extra['.($posicao+1).']" name="tipo_campo_extra[]" value="" /></td><td>'.dica('Largura máxima', 'Insira a largura máxima do campo, em números de caracteres. Caso o texto passe da largura inserida, será inserida uma quebra de linha."').'&nbsp;&nbsp;Larg. Max: '.dicaF().'<input type="text" class="texto" style="width:50px;" name="tipo_larg_max['.($posicao+1).']" value="" /></td><td>'.dica('Campo Pai', 'Caso este campo seja um subcampo deduzido a partir de outro, coloque o número deste outro campo.<br><br>ex: campo 3 é botão de selecionar '.$config['organizacao'].' e campo6 é telefone d'.$config['genero_organizacao'].' '.$config['organizacao'].', logo no campo 6 deve-se preencher 3.').'&nbsp;&nbsp;Campo pai: '.dicaF().'<input type="text" class="texto" style="width:20px;" name="outro_campo['.($posicao+1).']" value="'.(isset($campo['outro_campo']) ? $campo['outro_campo'] : '').'" /></td><td width="16"><a href="javascript:void(0);" onclick="env.modelo_tipo_id.value='.$modelo_tipo_id.'; env.salvar.value=1;  env.adicionar_campo.value=1; env.submit()">'.imagem('icones/adicionar.png','Adicionar Campo','Clique neste icone '.imagem('icones/adicionar.png').' para adicionar este campo').'</a></td></tr>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
	echo '<tr><td colspan=20><table width="100%"><tr><td>Modelo HTML :&nbsp;<input type="file" class="arquivo" name="modelo" size="40"></td><td>'.dica("Alterar Modelo HTML","Clique neste botão para enviar o arquivo HTML contendo o modelo html substituto.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.carregar_modelo.value=1; env.submit();"><span><b>carregar</b></span></a>'.dicaF().'</td></tr></table></td></tr>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
	echo '<tr><td colspan=20><table width="100%"><tr><td align="left">'.dica("Salvar","Clique neste botão para salvar as alteração no modelo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:if (env.novo_nome.value.length>0) {env.modelo_tipo_id.value='.$modelo_tipo_id.'; env.salvar.value=1; env.submit();} else alert (\'Escreve o nome do modelo!\');"><span><b>salvar</b></span></a>'.dicaF().'</td><td align="center">'.botao('cancelar', 'Cancelar', 'Clique neste botão para cancelar a alteração dos campos e nome do modelo.','','env.submit();').'</td><td align="right">'.botao('editar html', 'Editar HTML', 'Clique neste botão para editar o conteúdo HTML atual do modelo.','','editarHtml('.$modelo_tipo_id.');').'</td></tr></table></td></tr>';


	echo '</table></td><td></td></tr>';
	echo '<tr><td colspan=20 align="center"><div><div id="campo_modelos" style="width: 840px; padding: 10px; border: 1px solid black; background-color: white;">';
	$modelo= new Modelo;
	$modelo->set_modelo_tipo($modelo_tipo_id);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
	$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
	$modelo->set_modelo($tpl);
	$modelo->edicao=true;
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		}
	echo $tpl->exibir($modelo->edicao);
	//$arquivo_modelo = str_replace('src="imagens/', 'src="./modulos/email/modelos/'.$config['militar'].'/imagens/', $linha['modelo_tipo_html']);
	//echo '<tr><td colspan=20 align=center><table cellspacing=0 cellpadding=0><tr id="modelo_html" style="display:none"><td>'.$arquivo_modelo.'</td></tr>';
	echo '</div></div></td></tr></table>';
	}
echo '</td></tr></table></td></tr></table>';
echo estiloFundoCaixa();
echo '</form></BODY></html>';
?>

<script LANGUAGE="javascript">

function alterar_modelo() {
	var modelo_tipo_id;
	var qnt=0;

	for(var i=0; i< env.ListaModelo.options.length; i++) {
			if (env.ListaModelo.options[i].selected && env.ListaModelo.options[i].value >0) {
				modelo_tipo_id=env.ListaModelo.options[i].value;
				++qnt;
				}
			}
	if (qnt>0) {
		env.alterar.value=1;
		env.modelo_tipo_id.value=modelo_tipo_id;
		env.submit();
		}
	else alert('Selecione um modelo!');

	}

function popAnexar() {
alert('Como é um modelo não faz sentido querer anexar documentos nele');
}

function mudar(){
	if (document.getElementById('modelo_campos').style.display=='none'){
		document.getElementById('modelo_campos').style.display='block';
		document.getElementById('modelo_html').style.display='none';
		}
	else {
		document.getElementById('modelo_campos').style.display='none';
		document.getElementById('modelo_html').style.display='block';
		}
	}


function excluir() {
	var qnt=0;
	var excluido = new Array();
	if(confirm('Tem certeza quanto à excluir?')){
		for(var i=0; i<env.ListaModelo.options.length; i++) {
				if (env.ListaModelo.options[i].selected && env.ListaModelo.options[i].value >0){
				excluido[qnt++]=env.ListaModelo.options[i].value;
				}
			}
		if (qnt>0) {
			env.excluir_modelo.value=excluido;
			env.submit();
			}
		else alert ('Selecione um modelo!');
		}
	}

function editarHtml(modelo_id){
    parent.gpwebApp.popUp('Editar Modelo Documento', 780, 580, 'm=sistema&u=email&a=modelos_documentos_editar_html&dialogo=1&modelo_id='+ modelo_id, window.salvarHtml, window, true, false);
}

function salvarHtml(modelo_id, html){
    if(modelo_id){
        xajax_alterar_html(modelo_id, html);
        }
    }

var botoes_ckeditor=<?php echo botoesCKEditor(); ?>;
function aposSalvarHtml(){
    var as = $jq("textarea[data-gpweb-cmp=\'ckeditor\']");
    as.each(function(){CKEDITOR.inline(this, botoes_ckeditor)});
    as = $jq("input[data-gpweb-cmp=\'calendario\']");
    as.each(function(){criarCampoCalendario(this);});
    }

</script>