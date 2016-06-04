<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();
if (!$dialogo) $Aplic->salvarPosicao();
$inserir=getParam($_REQUEST, 'inserir', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);
$novo_modelo=getParam($_REQUEST, 'novo_modelo', 0);
$excluir_modelo=getParam($_REQUEST, 'excluir_modelo', array());
$artefato_tipo_id=getParam($_REQUEST, 'artefato_tipo_id', 0);
$posicao=getParam($_REQUEST, 'posicao', 0);
$excluir_campo=getParam($_REQUEST, 'excluir_campo', 0);
$novo_nome=getParam($_REQUEST, 'novo_nome', '');
$tipo_campo_novo=getParam($_REQUEST, 'tipo_campo_novo', '');
$tipo_tabela_novo=getParam($_REQUEST, 'tipo_tabela_novo', '');


$tipo_campo_alterar=getParam($_REQUEST, 'tipo_campo_alterar', array());
$tipo_tabela_alterar=getParam($_REQUEST, 'tipo_tabela_alterar', array());
$salvar=getParam($_REQUEST, 'salvar', 0);
$backup=getParam($_REQUEST, 'backup', 0);

$carregar_modelo=getParam($_REQUEST, 'carregar_modelo', 0);
$adicionar_campo=getParam($_REQUEST, 'adicionar_campo', 0);


$sql = new BDConsulta;
$sql->adTabela('artefato_campo');
$sql->esqUnir('artefatos_tipo','artefatos_tipo','artefato_tipo_arquivo=artefato_campo_arquivo');
$sql->adCampo('artefato_campo_campo, artefato_campo_descricao');
$sql->adOnde('artefato_tipo_id='.(int)$artefato_tipo_id);
$campos_tabela = $sql->listaVetorChave('artefato_campo_campo','artefato_campo_descricao');
$sql->limpar();

$campos_tabela['data_hoje']='Data da impressão no fomato dd/mm/aaaa';
$campos_tabela['data_hoje_extenso']='Data da impressão no fomato dd de M de aaaa';

$tipos_campos=array(
''=>'',
'brasao'=>'Brasão do documento (Ex: Brasão da República)',
'bloco_simples'=>'Bloco de texto sem formatação',
'cabecalho'=>'Cabeçalho d'.$config['genero_organizacao'].' '.$config['organizacao'],
'data'=>'Data',
'data_extenso'=>'Data por extenso',
'data_hora'=>'Data e hora',
'hora_de_data'=>'Hora de uma data',
'nome_usuario'=>'Nome d'.$config['genero_usuario'].' '.$config['usuario'],
'funcao_usuario'=>'Função d'.$config['genero_usuario'].' '.$config['usuario'],
'nome_funcao_usuario'=>'Nome e função d'.$config['genero_usuario'].' '.$config['usuario'],
'email_usuario'=>'E-mail d'.$config['genero_usuario'].' '.$config['usuario'],
'telefone_usuario'=>'Telefone d'.$config['genero_usuario'].' '.$config['usuario'],
'dept_usuario'=> ucfirst($config['departamento']).' d'.$config['genero_usuario'].' '.$config['usuario'],
'nome_contato'=>'Nome do contato',
'funcao_contato'=>'Função do contato',
'nome_funcao_contato'=>'Nome e função do contato',
'email_contato'=>'E-mail do contato',
'telefone_contato'=>'Telefone do contato',
'dept_contato'=> ucfirst($config['departamento']).' do contato',
'logo'=>'Logo d'.$config['genero_organizacao'].' '.$config['organizacao'],
'lista_especial'=>'Lista especial',
'marcar_x'=>'Marcar X em campo booleano',
'numero_tres_digitos'=>'Número de 3 algarismos'
);


$posicao=0;

if($carregar_modelo && $artefato_tipo_id){
	if(isset($_FILES['modelo']['name']) && file_exists($_FILES['modelo']['tmp_name']) && !empty($_FILES['modelo']['tmp_name']) && $_FILES['modelo']["size"]>0){

		if ($html=file_get_contents($_FILES['modelo']['tmp_name'])){

			$sql->adTabela('artefatos_tipo');
			$sql->adAtualizar('artefato_tipo_html', $html);
			$sql->adOnde('artefato_tipo_id='.$artefato_tipo_id);
			if (!$sql->exec()) die('Não foi possível alterar artefatos_tipo.');
			ver2('Arquivo enviado com sucesso.');
			$sql->limpar();
			}
		}
	else ver2('Houve um erro no envio do arquivo.');
	$carregar_modelo=0;
	}

if($novo_modelo){
	if(isset($_FILES['modelo']['name']) && file_exists($_FILES['modelo']['tmp_name']) && !empty($_FILES['modelo']['tmp_name']) && $_FILES['modelo']["size"]>0){
		if ($html=file_get_contents($_FILES['modelo']['tmp_name'])){
			$sql->adTabela('artefatos_tipo');
			$sql->adInserir('artefato_tipo_nome', $novo_modelo);
			$sql->adInserir('artefato_tipo_civil', $config['anexo_civil']);
			$sql->adInserir('artefato_tipo_imagem', getParam($_REQUEST, 'artefato_tipo_imagem', null));
			$sql->adInserir('artefato_tipo_descricao', getParam($_REQUEST, 'artefato_tipo_descricao', null));
			$sql->adInserir('artefato_tipo_html', $html);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela modelo');

			$artefato_tipo_id=$bd->Insert_ID('artefatos_tipo','artefato_tipo_id');
			$sql->limpar();

			ver2('Modelo de artefato criado com sucesso. Altere o modelo para inserir os tipos de campos que o mesmo contêm.');
			}
		}
	else ver2('Houve um erro no envio do arquivo.'); 
	$carregar_modelo=0;
	$novo_modelo=0;
	}

if($excluir_campo){
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos');
	$sql->adOnde('artefato_tipo_id='.$artefato_tipo_id);
	$campos = unserialize($sql->Resultado());
	$sql->limpar();
	$qnt_campos=count($campos['campo']);
	$campos_depois=array();
	for ($i=1; $i < $qnt_campos; $i++) $campos_depois[$i]=$campos['campo'][$i];
	$campos['campo']=$campos_depois;
	$sql->adTabela('artefatos_tipo');
	$sql->adAtualizar('artefato_tipo_campos', serialize($campos));
	$sql->adOnde('artefato_tipo_id='.$artefato_tipo_id);
	if (!$sql->exec()) die('Não foi possível alterar artefatos_tipo.');
	$sql->limpar();
	$excluir_campo=0;
	ver2('Campo foi excluído');
	}

if ($artefato_tipo_id && $salvar){

	$modelo= new Modelo;
	$modelo->set_modelo_tipo($artefato_tipo_id);
	foreach($tipo_campo_alterar as $posicao => $tipo_campo) {
		$modelo->set_campo($tipo_campo, $tipo_tabela_alterar[$posicao], $posicao);
		}
	//checar depois
	if ($tipo_campo_novo) $modelo->set_campo($tipo_campo_novo, $tipo_tabela_novo, $posicao+1);
	$vars = get_object_vars($modelo);
	$sql->adTabela('artefatos_tipo');
	$sql->adAtualizar('artefato_tipo_campos', serialize($vars));
	$sql->adOnde('artefato_tipo_id='.$artefato_tipo_id);
	if (!$sql->exec()) die('Não foi possível alterar artefatos_tipo.');
	$sql->limpar();
	if ($adicionar_campo) {
		$alterar=1;
		}
	else {
		$alterar=0;
		}
	$salvar=0;
  }


if ($artefato_tipo_id && $backup){
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos_bk, artefato_tipo_html_bk');
	$sql->adOnde('artefato_tipo_id='.(int)$artefato_tipo_id);
	$linha = $sql->Linha();
	$sql->limpar();

	$sql->adTabela('artefatos_tipo');
	$sql->adAtualizar('artefato_tipo_campos', $linha['artefato_tipo_campos_bk']);
	$sql->adAtualizar('artefato_tipo_html', $linha['artefato_tipo_html_bk']);
	$sql->adOnde('artefato_tipo_id='.(int)$artefato_tipo_id);
	if (!$sql->exec()) die('Não foi possível alterar artefatos_tipo.');
	$sql->limpar();
	$backup=0;
	$alterar=1;
	$salvar=0;
	ver2('Modelo original carregado.');
  }










if ($artefato_tipo_id && $alterar){
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_nome, artefato_tipo_descricao, artefato_tipo_imagem, artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_arquivo, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_id='.$artefato_tipo_id);
	$rs = $sql->Linha();
	$sql->Limpar();
  }
if ($novo_nome && $artefato_tipo_id){
	$sql->adTabela('artefatos_tipo');
	$sql->adAtualizar('artefato_tipo_nome', $novo_nome);
	$sql->adAtualizar('artefato_tipo_imagem', getParam($_REQUEST, 'artefato_tipo_imagem', null));
	$sql->adAtualizar('artefato_tipo_descricao', getParam($_REQUEST, 'artefato_tipo_descricao', null));
	$sql->adOnde('artefato_tipo_id='.$artefato_tipo_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela artefatos_tipo!'.$bd->stderr(true));
	$sql->limpar();
	}
if ($excluir_modelo){
	$sql->setExcluir('artefatos_tipo');
	$sql->adOnde('artefato_tipo_id IN ('.implode(',',(array)$excluir_modelo).')');
	if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela artefatos_tipo!'.$bd->stderr(true));
	$sql->limpar();
	}

echo '<form method="POST" id="env" enctype="multipart/form-data" name="env">';
echo '<input type=hidden id="m" name="m" value="sistema">';
echo '<input type=hidden id="a" name="a" value="modelos_artefatos">';
echo '<input type=hidden id="u" name="u" value="projeto">';
echo '<input type=hidden name="inserir" id="inserir" value="">';
echo '<input type=hidden name="alterar" id="alterar" value="">';
echo '<input type=hidden name="excluir_campo" id="excluir_campo" value="">';
echo '<input type=hidden name="salvar" id="salvar" value="">';
echo '<input type=hidden name="backup" id="backup" value="">';
echo '<input type=hidden name="carregar_modelo" id="carregar_modelo" value="">';
echo '<input type=hidden name="novo_modelo" id="novo_modelo" value="">';
echo '<input type=hidden name="artefato_tipo_id" id="artefato_tipo_id" value="'.$artefato_tipo_id.'">';
echo '<input type=hidden name="excluir_modelo" id="excluir_modelo" value="">';
echo '<input type=hidden name="posicao" id="posicao" value="">';
echo '<input type=hidden name="adicionar_campo" id="adicionar_campo" value="">';


$botoesTitulo = new CBlocoTitulo('Modelos de Artefatos', 'modelos.jpg', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
if ($artefato_tipo_id) $botoesTitulo->adicionaBotao('m=sistema&u=projeto&a=modelos_artefatos', 'voltar','','Voltar','Voltar à tela de escolha de artefato.');

$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table width="100%" align="center" class="std" cellspacing=0 cellpadding=0>';
if (!$inserir && !$alterar) {
	echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td width="600" valign="top"><fieldset><legend class=texto style="color: black;">&nbsp;<b>Modelos de artefatos</b>&nbsp;</legend>';
	echo '<select name=ListaModelo[] id="ListaModelo" size=25 style="width:100%;" ondblClick="">';
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_id, artefato_tipo_nome');
	$sql->adOnde('artefato_tipo_civil="'.$config['anexo_civil'].'"');
	$sql_resultado = $sql->Lista();
	$sql->Limpar();
	foreach ($sql_resultado as $linha) echo '<option value="'.$linha['artefato_tipo_id'].'">'.$linha['artefato_tipo_nome'].'</option>';
	echo '</option></select></fieldset></td><td>&nbsp;</td></tr>';
	echo '<tr><td colspan=20><table width="100%" cellspacing=0 cellpadding=0><tr>';
	echo '<td style="width:50pt">'.dica("Editar","Clique neste botão para editar um modelo da caixa de seleção acima.").'<a class="botao" href="javascript:void(0);" onclick="javascript: alterar_modelo()"><span><b>editar</b></span></a></td>';
	echo '<td style="width:80%">&nbsp;</td>';
	echo '<td align=right>'.dica("Voltar","Clique neste botão para voltar à tela do sistema.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'index\'; env.u.value=\'\'; env.submit();"><span><b>voltar</b></span></a>'.dicaF().'</td></tr></table>';
	echo '</td></tr>';
	}
elseif ($inserir){
	echo '<tr><td align=right>'.dica('Nome','Nome do modelo de documento').'Nome:&nbsp;'.dicaF().'</td><td colspan=20><input type=text class="texto" name="nome_novo_modelo" id="nome_novo_modelo" style="width:100pt"> '.dica('Descrição','Informações sobre este modelo de documento a ser mostrado no menu de seleção de documento a ser criado.').'Descrição: '.dicaF().'<input type=text class="texto" name="artefato_tipo_descricao" id="artefato_tipo_descricao" style="width:250pt">	'.dica('Imagem','Escreva o nome do ícone a ser mostrado no menu de seleção de documento a ser criado, ex: <b>oficio.gif</b> .<br><br>Local para colocar o ícone: gpweb/estilo/rondon/imagens/icones/').'Imagem: '.dicaF().'<input type=text class="texto" name="artefato_tipo_imagem" id="artefato_tipo_imagem" style="width:100pt"></td></tr>';
	
	
	echo '<tr><td align="right">'.dica('HTML','Arquivo HTML do modelo de artefato a ser importado para o sistema.').'HTML:'.dicaF().'&nbsp;</td><td><input type="file" class="arquivo" name="modelo" size="45"></td></tr>';
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td>'.botao('salvar', 'Salvar','Clique neste botão para salvar a inserção do novo modelo.','','if (env.nome_novo_modelo.value.length>0) {env.novo_modelo.value=env.nome_novo_modelo.value; env.submit();} else alert (\'Escreva o nome do modelo!\');').'</td><td width="100%">&nbsp;</td>';
	echo '<td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para cancelar a inserção do novo modelo.','','env.submit();').'</tr></table></td></tr>';
	echo '</table>';
	}
else {
	echo '<tr><td><table cellspacing=0 cellpadding=0 width=100%>';
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td>'.dica('Nome','Nome do modelo de documento').'Nome:&nbsp;'.dicaF().'</td><td><input type=text class="texto" name="novo_nome" id="novo_nome" value="'.$rs['artefato_tipo_nome'].'" style="width:200px;"> '.dica('Descrição','Informações sobre este modelo de documento a ser mostrado no menu de seleção de documento a ser criado.').'Descrição: '.dicaF().'<input type=text class="texto" name="artefato_tipo_descricao" id="artefato_tipo_descricao" value="'.$rs['artefato_tipo_descricao'].'" style="width:250pt">	'.dica('Imagem','Escreva o nome do ícone a ser mostrado no menu de seleção de documento a ser criado, ex: <b>oficio.gif</b> .<br><br>Local para colocar o ícone: gpweb/estilo/rondon/imagens/icones/').'Imagem: '.dicaF().'<input type=text class="texto" name="artefato_tipo_imagem" id="artefato_tipo_imagem" value="'.$rs['artefato_tipo_imagem'].'" style="width:100pt"></td></tr></table></td></tr>';
  echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td>Modelo HTML :&nbsp;<input type="file" class="arquivo" name="modelo" size="40"></td><td>'.dica("Alterar Modelo HTML","Clique neste botão para enviar o arquivo HTML contendo o modelo html substituto.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.carregar_modelo.value=1; env.submit();"><span><b>carregar</b></span></a>'.dicaF().'</td><td>'.botao('original', 'Artefato Original','Clique neste botão para carregar o modelo original do artefato caso tenha ficado insatisfeito com as alterações realizadas.','','if (confirm(\'Tem certeza que deseja carregar o modelo original?\')) {env.artefato_tipo_id.value='.$artefato_tipo_id.'; env.backup.value=1; env.submit();}').'</td></tr></table></td></tr>';

	
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos');
	$sql->adOnde('artefato_tipo_id='.(int)$artefato_tipo_id);
	$campos = unserialize($sql->Resultado());
	$sql->limpar();
	$posicao=0;
	$qnt_campos=count((array)$campos['campo']);

	foreach((array)$campos['campo'] as $posicao => $campo) {
		echo '<tr><td align="right" width="70px">campo '.$posicao.' :&nbsp;</td><td width="250px">'.selecionaVetor($tipos_campos, 'tipo_campo_alterar['.$posicao.']', 'class="texto" style="width:250px;"', $campo['tipo']).'</td><td width="250px">'.selecionaVetor($campos_tabela, 'tipo_tabela_alterar['.$posicao.']', 'class="texto" style="width:250px;"', $campo['dados']).'</td><td width="16">'.($posicao==$qnt_campos ? '<a href="javascript:void(0);" onclick="env.posicao.value='.$posicao.'; env.alterar.value=1; env.excluir_campo.value=1; env.submit();">'.imagem('icones/remover.png','Excluir Campo','Clique neste icone '.imagem('icones/remover.png').' para excluir este campo').'</a>' : '&nbsp;').'</td><td>&nbsp;</td></tr>';
		}
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
	echo '<tr><td align="right"  width="70px">novo '.($posicao+1).' :&nbsp;</td><td width="250px">'.selecionaVetor($tipos_campos, 'tipo_campo_novo', 'class="texto" style="width:250px;"').'</td><td width="250px">'.selecionaVetor($campos_tabela, 'tipo_tabela_novo', 'class="texto" style="width:250px;"').'</td><td width="16"><a href="javascript:void(0);" onclick="env.artefato_tipo_id.value='.$artefato_tipo_id.'; env.salvar.value=1; env.adicionar_campo.value=1; env.submit()">'.imagem('icones/adicionar.png','Adicionar Campo','Clique neste icone '.imagem('icones/adicionar.png').' para adicionar este campo').'</a></td><td>&nbsp</td></tr>';

	echo '<tr><td colspan=20>&nbsp;</td></tr>';
	
	
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar','Clique neste botão para salvar as alteração no modelo.','','if (env.novo_nome.value.length>0) {env.artefato_tipo_id.value='.$artefato_tipo_id.'; env.salvar.value=1; env.submit();} else alert (\'Escreve o nome do modelo!\');').'</td><td align=right>'.botao('cancelar', 'Cancelar','Clique neste botão para cancelar as alteração no modelo.','','if (confirm(\'Tem certeza que deseja cancelar?\')) {env.submit();}').'</td></tr></table></td></tr>';
	echo '</table></td><td></td></tr>';

	$arquivo_modelo = str_replace('src="imagens/', 'src='.$rs['artefato_tipo_endereco'].'/imagens/', $rs['artefato_tipo_html']);
	$arquivo_modelo = str_replace('src="./'.$rs['artefato_tipo_endereco'].'/imagens/brasao_republica.gif','src="'.$Aplic->gpweb_brasao,  $arquivo_modelo);
	echo '<tr><td colspan=20 align=center>';
	echo '<div id="modelo_html" style="border: 1px solid black; margin-top: 10px;">';

    if($Aplic->profissional){
        echo '<div name="bozo" id="bozo" contenteditable="true">'.$arquivo_modelo.'</div>';
        }
    else{
        echo '<div name="bozo" id="bozo">'.$arquivo_modelo.'</div>';
        }

    if($Aplic->profissional){
        echo '<div>'.botao('Salvar HTML', 'Salvar HTML', 'Clique neste botão para salvar as mudanças no html do modelo.','','salvar_html('.$artefato_tipo_id.');').'</div>';
        }
    echo '</div>';
    echo '</td></tr>';
	}
echo '</td></tr></table></td></tr></table>';

if($Aplic->profissional){
    echo '<script LANGUAGE="javascript">';
    echo '$jq(function(){';
             echo 'var config_ckeditor = {';
                echo 'baseHref: "'.BASE_URL.'/",';
                echo 'baseUrl: "'.BASE_URL.'/",';
                echo "toolbar: [['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],    '/',['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],['Link', 'Unlink'],['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'],['Sourcedialog']],";
                echo "extraPlugins: 'sourcedialog'";
            echo "};";

            echo 'CKEDITOR.inline("bozo", config_ckeditor);';
    echo '});';
    echo '</script>';
    }

echo estiloFundoCaixa();
echo '</form></BODY></html>';
?>

<script LANGUAGE="javascript">

function salvar_html(artefato_tipo_id){
	xajax_alterar_html(artefato_tipo_id, CKEDITOR.instances['bozo'].getData());
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


function alterar_modelo() {
	var artefato_tipo_id;
	var qnt=0;
	for(var i=0; i< env.ListaModelo.options.length; i++) {
			if (env.ListaModelo.options[i].selected && env.ListaModelo.options[i].value >0) {
				artefato_tipo_id=env.ListaModelo.options[i].value;
				++qnt;
				}
			}
	if (qnt>0) {
		env.alterar.value=1;
		env.artefato_tipo_id.value=artefato_tipo_id;
		env.submit();
		}
	else alert('Selecione um modelo!');
	}

function popAnexar() {
alert('Como é um modelo não faz sentido querer anexar documentos nele');
}


function excluir() {
	var qnt=0;
	var excluido = new Array();
	for(var i=0; i<env.ListaModelo.options.length; i++) {
			if (env.ListaModelo.options[i].selected && env.ListaModelo.options[i].value >0){
			excluido[qnt++]=env.ListaModelo.options[i].value;
			}
		if (qnt>0) {
			env.excluir_modelo.value=excluido;
			env.submit();
			}
		else alert ('Selecione um modelo!');
		}
	}



</script>