<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

global $dialogo, $tab,$vetor_modelo, $msg_id;

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();
$Aplic->carregarComboMultiSelecaoJS();

require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);


$modeloID=getParam($_REQUEST, 'modeloID', null);
if ($modeloID) $modelo_id=reset($modeloID);
else $modelo_id=getParam($_REQUEST, 'modelo_id', null);

$modelo_tipo_id=getParam($_REQUEST, 'modelo_tipo_id', null);
$modelo_dados_id=getParam($_REQUEST, 'modelo_dados_id', null);
$salvar=getParam($_REQUEST, 'salvar', 0);
$editar=getParam($_REQUEST, 'editar', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);
$aprovar=getParam($_REQUEST, 'aprovar', 0);
$assinar=getParam($_REQUEST, 'assinar', 0);
$anterior=getParam($_REQUEST, 'anterior', 0);
$posterior=getParam($_REQUEST, 'posterior', 0);

$campo=getParam($_REQUEST, 'campo', 0);
$retornar=getParam($_REQUEST, 'retornar', 'modelo_pesquisar');
$novo=getParam($_REQUEST, 'novo', 0);
$cancelar=getParam($_REQUEST, 'cancelar', 0);
$lista_doc_referencia=getParam($_REQUEST, 'lista_doc_referencia', array());
$lista_msg_referencia=getParam($_REQUEST, 'lista_msg_referencia', array());

if (isset($vetor_modelo[$tab]) && $vetor_modelo[$tab]) $modelo_id=$vetor_modelo[$tab];
$coletivo=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$Aplic->usuario_id);
$modelo_usuario_id=getParam($_REQUEST, 'modelo_usuario_id', null);

//caso seja um novo documento os anexos usarão a chave criada

$idunico=getParam($_REQUEST, 'idunico', '');
if (!$idunico) $idunico=uniqid('',true);

$sql = new BDConsulta;

if ($excluir){
	$sql->setExcluir('modelos');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->setExcluir('modelos_dados');
	$sql->adOnde('modelo_dados_modelo='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->adTabela('modelos_anexos');
	$sql->adCampo('caminho');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$resultados=$sql->Lista();
	$sql->limpar();
	foreach ($resultados as $anexo){
		$caminho=str_replace('/', '\\', $anexo['caminho']);
		if (file_exists($base_dir.'\\'.$config['pasta_anexos'].'_modelos\\'.$caminho))	@unlink($base_dir.'\\'.$config['pasta_anexos'].'_modelos\\'.$caminho);
		}
	$sql->setExcluir('modelos_anexos');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->setExcluir('anexos');
	$sql->adOnde('modelo='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$Aplic->redirecionar('m=email&a='.$retornar);
	exit();
	}

//leitura do modelo
if ($modelo_id && !$modelo_tipo_id){
	//Se foi enviado um modelo de documento
	$sql->adTabela('modelo_usuario');
	$sql->adCampo('datahora_leitura, de_id, aviso_leitura');
	$sql->adOnde('modelo_id ='.(int)$modelo_id);
	$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$Aplic->usuario_id));
	$sql_resultadosa = $sql->Lista();
	$sql->limpar();
	foreach ($sql_resultadosa as $rs_leitura){
		if (!$rs_leitura['datahora_leitura']) {
			$data = date('Y-m-d H:i:s');
			$sql->adTabela('modelo_usuario');
			$sql->adAtualizar('datahora_leitura', $data);
			$sql->adAtualizar('status', 1);
			$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$Aplic->usuario_id));
			$sql->adOnde('modelo_id='.(int)$modelo_id);
			$sql->adOnde('datahora_leitura IS NULL');
			$sql->exec();
			$sql->limpar();
			if ($rs_leitura['aviso_leitura']==1 && $Aplic->usuario_id==$usuario_id) aviso_leitura_modelo($rs_leitura['de_id'], $msg_id, $data);
			}
		}
	//Para abranger também os modelos anexados em msg_id
	$sql->adTabela('modelo_leitura');
	$sql->adInserir('datahora_leitura', date('Y-m-d H:i:s'));
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('modelo_id', $modelo_id);
	$sql->adInserir('download', 0);
	$sql->exec();
	$sql->limpar();
	}

if ($modelo_id && !$modelo_tipo_id){
	$sql->adTabela('modelos');
	$sql->adCampo('modelo_tipo');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$modelo_tipo_id=$sql->Resultado();
	$sql->limpar();
	}

if (!$modelo_tipo_id){
	$Aplic->setMsg('Houve um erro ao carregar o tipo de documento', UI_MSG_ERRO);
	$Aplic->redirecionar('m=email&a='.$retornar);
	exit();
	}

if ($aprovar){
	$sql->adTabela('modelos');
	$sql->adAtualizar('modelo_versao_aprovada',  $modelo_dados_id);
	$sql->adAtualizar('modelo_autoridade_aprovou',  $Aplic->usuario_id);
	$sql->adAtualizar('modelo_aprovou_nome',  $Aplic->usuario_nome);
	$sql->adAtualizar('modelo_aprovou_funcao',  $Aplic->usuario_funcao);
	$sql->adAtualizar('modelo_data_aprovado',  date('Y-m-d H:i:s'));
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();
	ver2('Documento aprovado.');
	}

if ($assinar){
	$sql->adTabela('modelos');
	$sql->adCampo('modelo_versao_aprovada');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$aprovado=$sql->Resultado();
	$sql->limpar();
	$sql->adTabela('modelos_dados');
	$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
	$sql->adOnde('modelo_dados_modelo='.(int)$aprovado);
	$dados_aprovado=$sql->Linha();
	$sql->limpar();
	$assinatura='';
	if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
		$identificador=$dados_aprovado['modelo_dados_id'].md5($dados_aprovado['modelos_dados_campos']).$dados_aprovado['modelos_dados_criador'].$dados_aprovado['modelo_dados_data'];
		openssl_sign($identificador, $assinatura, $Aplic->chave_privada);
		}
	$sql->adTabela('modelos');
	$sql->adAtualizar('modelo_autoridade_assinou',  $Aplic->usuario_id);
	$sql->adAtualizar('modelo_assinatura_nome',  $Aplic->usuario_nome);
	$sql->adAtualizar('modelo_assinatura_funcao',  $Aplic->usuario_funcao);
	$sql->adAtualizar('modelo_data_assinado',  date('Y-m-d H:i:s'));
	$sql->adAtualizar('modelo_assinatura', base64_encode($assinatura));
	$sql->adAtualizar('modelo_chave_publica', $Aplic->chave_publica_id);
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();
	echo '<script>alert("Documento assinado.")</script>';
	}

if ($salvar && getParam($_REQUEST, 'assunto', '')){
	if (!$modelo_id){
		$sql->adTabela('modelos');
		$sql->adInserir('modelo_tipo', $modelo_tipo_id);
		$sql->adInserir('modelo_criador_original',  $Aplic->usuario_id);
		$sql->adInserir('modelo_criador_nome',  $Aplic->usuario_nome);
		$sql->adInserir('modelo_criador_funcao',  $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela modelos!');
		$modelo_id=$bd->Insert_ID('modelos','modelo_id');
		$sql->Limpar();
		//mudar os anexos que estão sem id do modelo
		$sql->adTabela('modelos_anexos');
		$sql->adCampo('modelo_anexo_id, caminho');
		$sql->adOnde('idunico = \''.$idunico.'\'');
		$anexos=$sql->lista();
		$sql->Limpar();
		foreach($anexos as $anexo){
			$segunda_parte=str_replace($idunico, '', substr($anexo['caminho'],8));
			$novo_caminho=substr($anexo['caminho'],0,8).'M'.$modelo_id.$segunda_parte;
			if (file_exists($base_dir.'/'.$config['pasta_anexos'].'_modelos'.'/'.$anexo['caminho']))	rename($base_dir.'/'.$config['pasta_anexos'].'_modelos'.'/'.$anexo['caminho'], $base_dir.'/'.$config['pasta_anexos'].'_modelos'.'/'.$novo_caminho);
			$sql->adTabela('modelos_anexos');
			$sql->adAtualizar('caminho',  $novo_caminho);
			$sql->adAtualizar('modelo_id',  $modelo_id);
			$sql->adOnde('modelo_anexo_id='.(int)$anexo['modelo_anexo_id']);
			$sql->exec();
			$sql->limpar();
			}
		}

	$sql->adTabela('modelos');
	$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
	$sql->adCampo('modelo_tipo, modelo_data, organizacao, modelo_tipo_html');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$linha=$sql->Linha();

	$sql->adTabela('modelos');
	$sql->adAtualizar('modelo_assunto',  getParam($_REQUEST, 'assunto', ''));
	$sql->adAtualizar('class_sigilosa',  getParam($_REQUEST, 'class_sigilosa', 0));
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	if (!$linha['modelo_data']){
		$sql->adTabela('modelos');
		$sql->adAtualizar('modelo_data',  date('Y-m-d H:i:s'));
		$sql->adOnde('modelo_id='.(int)$modelo_id);
		$sql->exec();
		$sql->limpar();
		}

	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_campos');
	$sql->adOnde('modelo_tipo_id='.(int)$linha['modelo_tipo']);
	$campos = unserialize($sql->Resultado());

	$sql->limpar();
	$modelo= new Modelo;
	$modelo->set_modelo_tipo($linha['modelo_tipo']);
	$modelo->set_modelo_id($modelo_id);

	foreach((array)$campos['campo'] as $posicao => $campo) {
		
		if ($campo['tipo']=='remetente'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'remetente_'.$posicao, '');
			$resultado[1]=getParam($_REQUEST, 'remetente_funcao_'.$posicao, '');
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}

		elseif ($campo['tipo']=='protocolo_secao'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'dept_protocolo', '');
			$resultado[1]=getParam($_REQUEST, 'dept_qnt_nr', '');
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}

		elseif ($campo['tipo']=='impedimento'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'impedimento_'.$posicao, '');
			$resultado[1]=getParam($_REQUEST, 'posto_'.$posicao, '');
			$resultado[2]=getParam($_REQUEST, 'nomeguerra_'.$posicao, '');
			$resultado[3]=getParam($_REQUEST, 'funcao_'.$posicao, '');
			$resultado[7]=getParam($_REQUEST, 'assinante_'.$posicao, '');
			$resultado[9]=getParam($_REQUEST, 'ordem_postonome_'.$posicao, '');
			if ($resultado[0]){
				$resultado[4]=getParam($_REQUEST, 'postor_'.$posicao, '');
				$resultado[5]=getParam($_REQUEST, 'nomeguerrar_'.$posicao, '');
				$resultado[6]=getParam($_REQUEST, 'funcaor_'.$posicao, '');
				$resultado[8]=getParam($_REQUEST, 'assinanter_'.$posicao, '');
				$resultado[10]=getParam($_REQUEST, 'ordem_postonomer_'.$posicao, '');
				}
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}
			
		elseif ($campo['tipo']=='assinatura'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'posto_'.$posicao, '');
			$resultado[1]=getParam($_REQUEST, 'nomeguerra_'.$posicao, '');
			$resultado[2]=getParam($_REQUEST, 'funcao_'.$posicao, '');
			$resultado[3]=getParam($_REQUEST, 'assinante_'.$posicao, '');
			$resultado[4]=getParam($_REQUEST, 'ordem_postonome_'.$posicao, '');
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}
			
		elseif ($campo['tipo']=='destinatarios'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'campo_'.$posicao, '');
			$lista_destinatarios=getParam($_REQUEST, 'lista_destinatarios_'.$posicao, '');
			$funcao_destinatarios=getParam($_REQUEST, 'funcao_destinatarios_'.$posicao, '');
			$lista_destinatarios=explode('#', $lista_destinatarios);
			$funcao_destinatarios=explode('#', $funcao_destinatarios);
			for ($i=0; $i < count($lista_destinatarios); $i++){
				if ($lista_destinatarios[$i]) $resultado[$i+1]=array($lista_destinatarios[$i], $funcao_destinatarios[$i]);
				}
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}

		elseif ($campo['tipo']=='anexo'){
			$anexos=getParam($_REQUEST, 'anexo_'.$posicao, '');
			$nomes_fantasia=getParam($_REQUEST, 'nome_fantasia_'.$posicao, '');
			$resultado=array();
			foreach ((array)$anexos as $chave => $modelo_anexo){
				if (isset($nomes_fantasia[$chave])) $resultado[$modelo_anexo]=$nomes_fantasia[$chave];
				}
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}
		else $modelo->set_campo($campo['tipo'], getParam($_REQUEST, 'campo_'.$posicao, null), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
		
		}
	$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
	$modelo->set_modelo($tpl);
	$modelo->edicao=false;
	$editar=0;
	$vars = get_object_vars($modelo);
	$sql->adTabela('modelos_dados');
	$sql->adInserir('modelo_dados_modelo', $modelo_id);
  if( config('tipoBd') == 'postgres') $sql->adInserir('modelos_dados_campos', addslashes(serialize($vars)));
  else $sql->adInserir('modelos_dados_campos', serialize($vars));

	$sql->adInserir('modelos_dados_criador', $Aplic->usuario_id);
	$sql->adInserir('nome_usuario', ($Aplic->usuario_posto ? $Aplic->usuario_posto.' ' : '').$Aplic->usuario_nomeguerra);
	$sql->adInserir('funcao_usuario', $Aplic->usuario_funcao);
	$sql->adInserir('modelo_dados_data',  date('Y-m-d H:i:s'));
	$sql->exec();
	$sql->limpar();
	$modelo_dados_id=$bd->Insert_ID('modelos_dados','modelo_dados_id');
	//grava o documento

	//referencias
	//excluir antigas referencias
	$sql->setExcluir('referencia');
	$sql->adOnde('referencia_doc_filho = '.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();
	foreach((array)$lista_doc_referencia as $chave => $doc_id_pai){
		$sql->adTabela('referencia');
	  $sql->adInserir('referencia_doc_pai', $doc_id_pai);
		$sql->adInserir('referencia_doc_filho', $modelo_id);
		$sql->adInserir('referencia_responsavel', $Aplic->usuario_id);
		$sql->adInserir('referencia_data', date('Y-m-d H:i:s'));
		$sql->adInserir('referencia_nome_de', $Aplic->usuario_nome);
		$sql->adInserir('referencia_funcao_de', $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela referencia!');
		$sql->limpar();
		}
	foreach((array)$lista_msg_referencia as $chave => $msg_id_pai){
		$sql->adTabela('referencia');
	  $sql->adInserir('referencia_msg_pai', $msg_id_pai);
		$sql->adInserir('referencia_doc_filho', $modelo_id);
		$sql->adInserir('referencia_responsavel', $Aplic->usuario_id);
		$sql->adInserir('referencia_data', date('Y-m-d H:i:s'));
		$sql->adInserir('referencia_nome_de', $Aplic->usuario_nome);
		$sql->adInserir('referencia_funcao_de', $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela referencia!');
		$sql->limpar();
		}

	if ($Aplic->profissional && isset($_REQUEST['uuid']) && $_REQUEST['uuid']){
			$sql->adTabela('modelo_gestao');
			$sql->adAtualizar('modelo_gestao_modelo', (int)$modelo_id);
			$sql->adAtualizar('modelo_gestao_uuid', null);
			$sql->adOnde('modelo_gestao_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			}

	ver2('Documento salvo');
	$salvar=0;
	$novo=0;
	}
elseif ($salvar && !getParam($_REQUEST, 'assunto', '')) ver2('O assunto do documento não foi enviado!');

//criar um novo documento
if (!$modelo_id){
	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_campos, modelo_tipo_html');
	$sql->adOnde('modelo_tipo_id='.(int)$modelo_tipo_id);
	$linha=$sql->linha();
	$sql->limpar();

	$campos = unserialize($linha['modelo_tipo_campos']);

	$modelo= new Modelo;
	$modelo->set_modelo_tipo($modelo_tipo_id);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
	$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
	$modelo->set_modelo($tpl);


	$modelo->set_modelo_id($modelo_id);


	if ($editar) $modelo->edicao=true;
	else $modelo->edicao=false;
	$criador=$Aplic->usuario_id;
	}
elseif ($modelo_id && !$salvar){
	$sql->adTabela('modelos');
	$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
	$sql->adCampo('class_sigilosa, modelo_assinatura, modelo_chave_publica, modelo_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada, modelo_protocolo, modelo_autoridade_assinou, modelo_autoridade_aprovou, modelo_assunto, organizacao, modelo_tipo_html');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$linha=$sql->Linha();

	$sql->Limpar();
	$sql->adTabela('modelos_dados');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = modelos_dados_criador');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
	$sql->adOnde('modelo_dados_modelo='.(int)$modelo_id);
	if ($modelo_dados_id && $anterior) {
		$sql->adOnde('modelo_dados_id <'.$modelo_dados_id);
		$sql->adOrdem('modelo_dados_id DESC');
		}
	elseif ($modelo_dados_id && $posterior) {
		$sql->adOnde('modelo_dados_id >'.(int)$modelo_dados_id);
		$sql->adOrdem('modelo_dados_id ASC');
		}
	else $sql->adOrdem('modelo_dados_id DESC');
	$dados=$sql->Linha();
	$sql->Limpar();
	$modelo_dados_id=$dados['modelo_dados_id'];
	$criador=$dados['modelos_dados_criador'];

  //desserializa o documento gravado
  if( config('tipoBd') == 'postgres') $campos = unserialize(stripslashes($dados['modelos_dados_campos']));
  else $campos = unserialize($dados['modelos_dados_campos']);

	$modelo= new Modelo;
	$modelo->set_modelo_tipo($modelo_tipo_id);
	$modelo->set_modelo_id($modelo_id);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
	$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
	$modelo->set_modelo($tpl);

	if ($editar && !$linha['modelo_versao_aprovada']) $modelo->edicao=true;
	else $modelo->edicao=false;
	}
$qnt_antes=0;
$qnt_depois=0;



if ($modelo_dados_id && $modelo_id){
	$sql->adTabela('modelos_dados');
	$sql->adCampo('count(modelo_dados_id)');
	$sql->adOnde('modelo_dados_id <'.(int)$modelo_dados_id);
	$sql->adOnde('modelo_dados_modelo ='.(int)$modelo_id);
	$qnt_antes=$sql->Resultado();
	$sql->Limpar();
	$sql->adTabela('modelos_dados');
	$sql->adCampo('count(modelo_dados_id)');
	$sql->adOnde('modelo_dados_id >'.(int)$modelo_dados_id);
	$sql->adOnde('modelo_dados_modelo ='.(int)$modelo_id);
	$qnt_depois=$sql->Resultado();
	$sql->Limpar();
	}


echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="'.$a.'">';
echo '<input type=hidden name="m" id="email" value="email">';
echo '<input type=hidden name="anexo" id="anexo"  value="">';
echo '<input type=hidden name="sem_cabecalho" id="sem_cabecalho" value="">';
echo '<input type=hidden name="excluir" id="excluir"  value="">';
echo '<input type=hidden name="salvar" id="salvar"  value="">';
echo '<input type=hidden name="aprovar" id="aprovar"  value="">';
echo '<input type=hidden name="assinar" id="assinar"  value="">';
echo '<input type=hidden name="anterior" id="anterior"  value="">';
echo '<input type=hidden name="posterior" id="posterior"  value="">';
echo '<input type=hidden name="editar" id="editar"  value="'.$editar.'">';
echo '<input type=hidden name="modelo_id" id="modelo_id"  value="'.$modelo_id.'">';
echo '<input type=hidden name="modelo_tipo_id" id="modelo_tipo_id"  value="'.$modelo_tipo_id.'">';
echo '<input type=hidden name="modelo_usuario_id" id="modelo_usuario_id"  value="'.$modelo_usuario_id.'">';
echo '<input type=hidden name="idunico" id="idunico"  value="'.$idunico.'">';
echo '<input type=hidden name="msg_id" id="msg_id"  value="'.(isset($msg_id) ? $msg_id : '').'">';
echo '<input type=hidden name="dialogo" id="dialogo"  value="'.$dialogo.'">';
echo '<input type=hidden name="tab" id="tab"  value="'.(isset($tab) ? $tab : '').'">';
echo '<input type=hidden name="modelo_dados_id" id="modelo_dados_id" value="'.(isset($dados['modelo_dados_id']) ? $dados['modelo_dados_id'] : '').'">';
echo '<input type=hidden name="campo_atual" id="campo_atual"  value="">';
echo '<input type=hidden name="novo" id="novo"  value="'.$novo.'">';
echo '<input type=hidden name="retornar" id="retornar" value="'.$retornar.'">';
echo '<input type=hidden name="cancelar" id="cancelar" value="">';
echo '<input type=hidden name="tipo" id="tipo" value="">';
echo '<input type=hidden name="destino" id="destino" value="">';
echo '<input type=hidden name="status" id="status" value="">';
echo '<input type=hidden name="pasta" id="pasta" value="">';
echo '<input type=hidden name="mover" id="mover" value="">';
echo '<input type=hidden name="arquivar" id="arquivar" value="">';

$assinado='';
if (function_exists('openssl_sign') && isset($linha['modelo_assinatura']) && $linha['modelo_assinatura']){
	$sql->adTabela('chaves_publicas');
	$sql->adCampo('chave_publica_chave, chave_publica_usuario');
	$sql->adOnde('chave_publica_id="'.$linha['modelo_chave_publica'].'"');
	$chave_publica=$sql->Linha();
	$sql->limpar();

	$sql->adTabela('modelos_dados');
	$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
	$sql->adOnde('modelo_dados_modelo='.(int)$linha['modelo_versao_aprovada']);
	$dados_aprovado=$sql->Linha();
	$sql->limpar();

	$identificador=$dados_aprovado['modelo_dados_id'].md5($dados_aprovado['modelos_dados_campos']).$dados_aprovado['modelos_dados_criador'].$dados_aprovado['modelo_dados_data'];
	$ok = openssl_verify($identificador, base64_decode($linha['modelo_assinatura']), $chave_publica['chave_publica_chave'], OPENSSL_ALGO_SHA1);

	if (!$ok) $assinado='&nbsp;'.dica(nome_funcao('','','','',$chave_publica['chave_publica_usuario']),'A assinatura digital do documento não confere! Documento possívelmente adulterado.').'<img src="'.acharImagem('icones/assinatura_erro.gif').'" style="vertical-align:top" width="15" height="13" />'.dicaF();
	else $assinado='&nbsp;'.dica(nome_funcao('','','','',$chave_publica['chave_publica_usuario']),'A assinatura digital do documento confere .').'<img src="'.acharImagem('icones/assinatura.gif').'" style="vertical-align:top" width="15" height="13" />'.dicaF();
	}



$sql->adTabela('modelo_usuario');
$sql->esqUnir('modelo_anotacao','modelo_anotacao','modelo_anotacao.modelo_anotacao_id=modelo_usuario.modelo_anotacao_id');
$sql->adCampo('modelo_usuario.tipo, de_id, para_id, status, pasta_id, data_limite, data_retorno, resposta_despacho, concatenar_tres( modelo_anotacao.nome_de, \' - \', modelo_anotacao.funcao_de) AS nome_despachante, texto');
$sql->adOnde('modelo_usuario.modelo_usuario_id='.(int)$modelo_usuario_id);
$enviado = $sql->Linha();
$sql->limpar();

$podeEditar=false;

if (!$modelo->edicao &&!$dialogo){
	echo '<table rules="ALL" border="1" align="center" cellspacing=0 cellpadding=0 style="width:750px;">';
	echo '<tr><td colspan=2 style="background-color: #e6e6e6">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";

	//responder despacho
	if (isset($enviado) && $enviado['data_limite'] && !$enviado['resposta_despacho']) $km->Add("root","root_resposta_despacho",'<span id="responder_despacho" style="display:">'.dica('Inserir uma Resposta a um Despacho', 'Este doumento lhe foi enviado através de um despacho com solicitação de resposta de '.$enviado['nome_despachante'].' até '.retorna_data($enviado['data_limite'], false).'.<br>'.$enviado['texto'].'<br>Clique neste botão para abrir uma janela onde poderá escrever uma resposta a este despacho.').'Responder despacho'.dicaF().'</span>', "javascript: void(0);' onclick='resposta_despacho();");
	elseif (isset($enviado) && $enviado['tipo']==1 && !$enviado['resposta_despacho']) $km->Add("root","root_resposta_despacho",'<span id="responder_despacho" style="display:">'.dica('Inserir uma Resposta a um Despacho', 'Este doumento lhe foi enviado através de um despacho sem prazo para resposta de '.$enviado['nome_despachante'].'.<br>'.$enviado['texto'].'<br>Clique neste botão para abrir uma janela onde poderá escrever uma resposta a este despacho.').'Responder despacho'.dicaF().'</span>', "javascript: void(0);' onclick='resposta_despacho();");

	//arquivar
	if ($enviado['status']!=4 && $modelo_usuario_id) $km->Add("root","arquivada",dica('Arquivar','Clique nesta opção para mover este documento para a caixa dos arquivados.').'Arquivar'.dicaF(), "javascript: void(0);' onclick='env.status.value=4; env.a.value=\"modelo_grava_status\"; env.retornar.value=\"modelo_pesquisar\"; env.submit();");
	//responder
	if (isset($enviado['de_id'])&& $enviado['de_id']!=$Aplic->usuario_id) $km->Add("root","acao_responder",dica('Responder', 'Responder ao recebimento deste documento com o envio de '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' ao remetente.').'Responder'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=2;	env.a.value=\"modelo_envia_anot\";	env.retornar.value=\"modelo_editar\"; env.submit();");
	//encaminhar
	$km->Add("root","acao_encaminhar",dica('Encaminhar', 'Encaminhe este documento.').'Encaminhar'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=3;	env.destino.value=\"modelo_grava_encaminha\";	env.a.value=\"modelo_seleciona_usuarios\";	env.retornar.value=\"modelo_editar\"; env.submit();");


	//referencias
	$sql->adTabela('referencia');
	$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_pai');
	$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_pai');
	$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
	$sql->adOnde('referencia_doc_filho = '.(int)$modelo_id);
	$lista_referencia_pai = $sql->Lista();
	$sql->limpar();
	if ($lista_referencia_pai && count($lista_referencia_pai)) {
		$qnt_lista_referencia_pai=count($lista_referencia_pai);
		$km->Add("root","root_referencia",dica('Referencias','Lista de'.$config['genero_mensagem'].' '.($config['genero_mensagem']=='o' ? 'ao' : 'a').'s quais este documento faz referencia.').'Referencias'.dicaF());
			for ($i = 0, $i_cmp = $qnt_lista_referencia_pai; $i < $i_cmp; $i++) {
				if ($lista_referencia_pai[$i]['referencia_msg_pai']) {
					$lista= dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="env.a.value=\''.$Aplic->usuario_prefs['modelo_msg'].'\';	env.msg_id.value='.$lista_referencia_pai[$i]['referencia_msg_pai'].'; env.submit();">Msg. '.$lista_referencia_pai[$i]['referencia_msg_pai'].($lista_referencia_pai[$i]['referencia']? ' - '.$lista_referencia_pai[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[$i]['nome_de'], '', $lista_referencia_pai[$i]['funcao_de'], '', $lista_referencia_pai[$i]['de_id']).' - '.retorna_data($lista_referencia_pai[$i]['data_envio'], false).'</a>'.dicaF();
					}
				else {
					if ($lista_referencia_pai[$i]['modelo_autoridade_assinou']) {
						$nome=nome_funcao($lista_referencia_pai[$i]['modelo_assinatura_nome'], '', $lista_referencia_pai[$i]['modelo_assinatura_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_assinou']);
						$data=retorna_data($lista_referencia_pai[$i]['modelo_data_assinado'], false);
						}
					elseif ($lista_referencia_pai[$i]['modelo_autoridade_aprovou']) {
						$nome=nome_funcao($lista_referencia_pai[$i]['modelo_aprovou_nome'], '', $lista_referencia_pai[$i]['modelo_aprovou_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_aprovou']);
						$data=retorna_data($lista_referencia_pai[$i]['modelo_data_aprovado'], false);
						}
					else {
						$nome=nome_funcao($lista_referencia_pai[$i]['modelo_criador_nome'], '', $lista_referencia_pai[$i]['modelo_criador_funcao'], '', $lista_referencia_pai[$i]['modelo_criador_original']);
						$data=retorna_data($lista_referencia_pai[$i]['modelo_data'], false);
						}
					$lista= dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_assunto']? ' - '.$lista_referencia_pai[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF();
					}
				$km->Add("root_referencia","root_ref_".$lista_referencia_pai[$i]['referencia_msg_pai'].'_'.$lista_referencia_pai[$i]['referencia_doc_pai'], $lista);
				}
			}

	//referenciados
	$sql->adTabela('referencia');
	$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_filho');
	$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_filho');
	$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
	$sql->adOnde('referencia_doc_pai = '.(int)$modelo_id);
	$lista_referencia_filho = $sql->Lista();
	$sql->limpar();
	if ($lista_referencia_filho && count($lista_referencia_filho)) {
		$qnt_lista_referencia_pai=count($lista_referencia_filho);
		$km->Add("root","root_referenciados",dica('Referenciad'.$config['genero_mensagem'].'s','Lista de '.$config['mensagens'].' que fazem referencia a este documento.').'Referenciad'.$config['genero_mensagem'].'s'.dicaF());
			for ($i = 0, $i_cmp = $qnt_lista_referencia_pai; $i < $i_cmp; $i++) {
				if ($lista_referencia_filho[$i]['referencia_msg_filho']) {
					$lista= dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="env.a.value=\''.$Aplic->usuario_prefs['modelo_msg'].'\';	env.msg_id.value='.$lista_referencia_filho[$i]['referencia_msg_filho'].'; env.submit();">Msg. '.$lista_referencia_filho[$i]['referencia_msg_filho'].($lista_referencia_filho[$i]['referencia']? ' - '.$lista_referencia_filho[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_filho[$i]['nome_de'], '', $lista_referencia_filho[$i]['funcao_de'], '', $lista_referencia_filho[$i]['de_id']).' - '.retorna_data($lista_referencia_filho[$i]['data_envio'], false).'</a>'.dicaF();
					}
				else {
					if ($lista_referencia_filho[$i]['modelo_autoridade_assinou']) {
						$nome=nome_funcao($lista_referencia_filho[$i]['modelo_assinatura_nome'], '', $lista_referencia_filho[$i]['modelo_assinatura_funcao'], '', $lista_referencia_filho[$i]['modelo_autoridade_assinou']);
						$data=retorna_data($lista_referencia_filho[$i]['modelo_data_assinado'], false);
						}
					elseif ($lista_referencia_filho[$i]['modelo_autoridade_aprovou']) {
						$nome=nome_funcao($lista_referencia_filho[$i]['modelo_aprovou_nome'], '', $lista_referencia_filho[$i]['modelo_aprovou_funcao'], '', $lista_referencia_filho[$i]['modelo_autoridade_aprovou']);
						$data=retorna_data($lista_referencia_filho[$i]['modelo_data_aprovado'], false);
						}
					else {
						$nome=nome_funcao($lista_referencia_filho[$i]['modelo_criador_nome'], '', $lista_referencia_filho[$i]['modelo_criador_funcao'], '', $lista_referencia_filho[$i]['modelo_criador_original']);
						$data=retorna_data($lista_referencia_filho[$i]['modelo_data'], false);
						}
					$lista= dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_assunto']? ' - '.$lista_referencia_filho[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF();
					}
				$km->Add("root_referenciados","root_refa_".$lista_referencia_filho[$i]['referencia_msg_filho'].'_'.$lista_referencia_filho[$i]['referencia_doc_filho'], $lista);
				}
			}

	$km->Add("root","acao",dica('Ação','Selecione qual ação deseja execuar neste documento.').'Ação');
	//editar
	$podeEditar=(!$linha['modelo_versao_aprovada'] && ($Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'edita_modelo') || ($linha['modelo_criador_original']==$Aplic->usuario_id)));

	if ($podeEditar) $km->Add("acao","acao_editar",dica('Editar', 'Editar este documento.').'Editar'.dicaF(), "javascript: void(0);' onclick='env.a.value=\"modelo_editar\"; env.sem_cabecalho.value=0; env.editar.value=1; env.submit();");
	//aprovar
	if ($Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'aprova_modelo') && !$linha['modelo_versao_aprovada']) $km->Add("acao","acao_aprovar",dica('Aprovar', 'Aprovar este documento. Estando aprovado, o mesmo não poderá mais ser modificado e será enviado para o protocolo.').'Aprovar'.dicaF(), "javascript: void(0);' onclick='env.a.value=\"modelo_editar\"; env.sem_cabecalho.value=0; env.editar.value=0; env.aprovar.value=1; env.submit();");
	//assinar
	if ($Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'assina_modelo') && !$linha['modelo_autoridade_assinou'] && ($linha['modelo_autoridade_aprovou']==$Aplic->usuario_id) && function_exists('openssl_sign') && $Aplic->chave_privada) $km->Add("acao","acao_assinar",dica('Assinar', 'Assinar este documento.').'Assinar'.dicaF(), "javascript: void(0);' onclick='env.a.value=\"modelo_editar\"; env.sem_cabecalho.value=0; env.assinar.value=1; env.editar.value=0; env.submit();");
	//despachar
	$km->Add("acao","acao_despachar",dica('Despachar', 'Despachar este documento.').'Despachar'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=1;	env.destino.value=\"modelo_envia_anot\";	env.a.value=\"modelo_seleciona_usuarios\";	env.retornar.value=\"modelo_editar\"; env.submit();");

	//anotar
	$km->Add("acao","acao_anotar",dica('Anotar', 'Anotar neste documento.').'Anotar'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=4; env.a.value=\"modelo_envia_anot\";	env.retornar.value=\"modelo_editar\"; env.submit();");

	//excluir
	if ($linha['modelo_criador_original']==$Aplic->usuario_id && !$linha['modelo_versao_aprovada']) $km->Add("acao","acao_excluir",dica('Excluir', 'Excluir este documento.').'Excluir'.dicaF(), "javascript: void(0);' onclick='if(confirm(\"Tem certeza que deseja excluir este documento?\")){env.a.value=\"modelo_editar\"; env.sem_cabecalho.value=0; env.excluir.value=1; env.submit();}");

	//mover
	if (isset($enviado['status'])) $km->Add("root","mover_msg",dica('Mover Para','Selecione para aonde deseja mover este documento.').'Mover para'.dicaF());
	//entrada
	if (isset($enviado['status'])&& $enviado['status']>1) $km->Add("mover_msg","mover_msg_entrada",dica('Caixa de Entrada', 'Colocar este documento na caixa de entrada.').'Caixa de Entrada'.dicaF(), "javascript: void(0);' onclick='env.status.value=1; env.a.value=\"modelo_grava_status\"; env.retornar.value=\"modelo_pesquisar\"; env.submit();");
	//pender
	if (isset($enviado['status'])&& $enviado['status']!=3) $km->Add("mover_msg","mover_msg_pender",dica('Caixa de Pendentes', 'Colocar este documento na caixa dos pendentes.').'Caixa de Pendentes'.dicaF(), "javascript: void(0);' onclick='env.status.value=3; env.a.value=\"modelo_grava_status\"; env.retornar.value=\"modelo_pesquisar\"; env.submit();");
	//arquivar em uma pasta
	if (isset($enviado['status'])){
		$sql->adTabela('pasta');
		$sql->adCampo('pasta_id, nome');
		$sql->adOnde('usuario_id = '.$Aplic->usuario_id);
		$pastas=$sql->Lista();
		$sql->limpar();
		if (count($pastas)){
			$km->Add("mover_msg","mover_pasta",dica('Para Pasta','Selecione em qual pasta deseja arquivar este documento.').'Para Pasta'.dicaF());
			foreach ($pastas as $linha_pasta) $km->Add("mover_pasta","pasta_".$linha_pasta['pasta_id'],$linha_pasta['nome'], "javascript: void(0);' onclick='mover_pasta(".$linha_pasta['pasta_id'].");");
			}
		}


	//informações
	if (!$novo) $km->Add("root","root_informacao",dica('Informações Complementares','Ao se pressionar este botão irá abrir uma janela onde poderá visualizar os despachos, anotações e encaminhamentos efetuados neste documento.').'Informações'.dicaF(), "javascript: void(0);' onclick='visualizar_extra();");

	//retornar
	if ($retornar) $km->Add("root","root_retornar",dica('Retornar','Ao se pressionar este botão irá retornar a tela anterior.').'Retornar'.dicaF(), "javascript: void(0);' onclick='env.a.value=\"".$retornar."\"; env.submit();");
	echo $km->Render();
	echo '</td></tr>';
	echo '</table>';
	}

echo '<table width="100%" align="center" cellspacing=0 cellpadding=0>';
if (isset($dados['modelo_dados_id'])) echo '<tr style="font-family:verdana, arial, helvetica, sans-serif;font-size:8pt;"><td width="100%" align="center">'.($qnt_antes ? '<a href="javascript: void(0);" onclick="javascript:env.a.value=\'modelo_editar\'; env.sem_cabecalho.value=0; env.anterior.value=1; env.submit();">'.imagem('icones/retroceder.gif', 'Retroceder', 'Clique para visualizar alterações anteriores').'</a>' : '').$dados['nome_usuario'].($dados['contato_funcao'] ? ' - '.$dados['contato_funcao'] : '').' - '.retorna_data($dados['modelo_dados_data']).($qnt_depois ? '<a href="javascript: void(0);" onclick="javascript:env.a.value=\'modelo_editar\'; env.sem_cabecalho.value=0;  env.posterior.value=1; env.submit();">'.imagem('icones/avancar.gif', 'Avançar', 'Clique para visualizar alterações posteriores').'</a>' : '').'</td><td>'.dica('Imprimir o Documento', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o documento a partir do navegador Web.').'<a href="javascript: void(0);" onclick ="imprimir();">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';
else echo '<tr><td width="100%" align="center">'.($qnt_antes ? '<a href="javascript: void(0);" onclick="javascript:env.a.value=\'modelo_editar\'; env.sem_cabecalho.value=0;  env.anterior.value=1; env.submit();">'.imagem('icones/retroceder.gif', 'Retroceder', 'Clique para visualizar alterações anteriores').'</a>' : '').$Aplic->usuario_nome.($Aplic->usuario_funcao ? ' - '.$Aplic->usuario_funcao : '').($qnt_depois ? '<a href="javascript: void(0);" onclick="javascript:env.a.value=\'modelo_editar\'; env.sem_cabecalho.value=0; env.posterior.value=1; env.submit();">'.imagem('icones/avancar.gif', 'Avançar', 'Clique para visualizar alterações posteriores').'</a>' : '').'</td>'.($modelo_id ? '<td>'.dica('Imprimir o Documento', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o documento a partir do navegador Web.').'<a href="javascript: void(0);" onclick ="imprimir();">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td>' : '').'</tr>';
echo '<tr><td width="100%" align="center"><table><tr>';

if ($modelo->edicao) {
	echo '<td>'.botao('salvar', 'Salvar', 'Salvar o documento.','','salvar_doc();').'</td>';
	echo '<td>'.botao('referenciar mensagem', 'Referenciar '.ucfirst($config['mensagem']), 'Abre uma janela para procurar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' que este documento fará referência.','','popMensagem();').'</td><td align="center">'.botao('referenciar documento', 'Referenciar Documento', 'Abre uma janela para procurar um documento criado no '.$config['gpweb'].', à partir de modelo pré-definido, ao qual este documento fará referência.','','popDocumentos_referencia();').'</td>';
	if ($criador==$Aplic->usuario_id  && !$novo) echo '<td>'.botao('excluir', 'Excluir', 'Excluir este documento.','','if(confirm(\'Tem certeza que deseja excluir este documento?\')){env.a.value=\'modelo_editar\'; env.sem_cabecalho.value=0; env.excluir.value=1; env.submit();}').'</td>';
	echo '<td>'.botao('cancelar', 'Cancelar', 'Cancelar a '.(isset($linha['modelo_id']) && $linha['modelo_id'] ? 'edição': 'criação').' deste documento.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td>';
	}

echo '</tr></table></td></tr>';


if ($modelo->edicao) {

	//preencher as referencias

	$vetor_msg_referencia=array();
	$vetor_doc_referencia=array();

	$sql->adTabela('referencia');
	$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_pai');
	$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_pai');
	$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
	$sql->adOnde('referencia_doc_filho = '.(int)$modelo_id);
	$lista_referencia_pai = $sql->Lista();
	$sql->limpar();
	if ($lista_referencia_pai && count($lista_referencia_pai)) {
		$qnt_lista_referencia_pai=count($lista_referencia_pai);
		for ($i = 0, $i_cmp = $qnt_lista_referencia_pai; $i < $i_cmp; $i++) {
			if ($lista_referencia_pai[$i]['referencia_msg_pai']) {
				$lista= 'Msg. '.$lista_referencia_pai[$i]['referencia_msg_pai'].($lista_referencia_pai[$i]['referencia']? ' - '.$lista_referencia_pai[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[$i]['nome_de'], '', $lista_referencia_pai[$i]['funcao_de'], '', $lista_referencia_pai[$i]['de_id']).' - '.retorna_data($lista_referencia_pai[$i]['data_envio'], false);
				$vetor_msg_referencia[$lista_referencia_pai[$i]['referencia_msg_pai']]=$lista;
				}
			else {
				if ($lista_referencia_pai[$i]['modelo_autoridade_assinou']) {
					$nome=nome_funcao($lista_referencia_pai[$i]['modelo_assinatura_nome'], '', $lista_referencia_pai[$i]['modelo_assinatura_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_assinou']);
					$data=retorna_data($lista_referencia_pai[$i]['modelo_data_assinado'], false);
					}
				elseif ($lista_referencia_pai[$i]['modelo_autoridade_aprovou']) {
					$nome=nome_funcao($lista_referencia_pai[$i]['modelo_aprovou_nome'], '', $lista_referencia_pai[$i]['modelo_aprovou_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_aprovou']);
					$data=retorna_data($lista_referencia_pai[$i]['modelo_data_aprovado'], false);
					}
				else {
					$nome=nome_funcao($lista_referencia_pai[$i]['modelo_criador_nome'], '', $lista_referencia_pai[$i]['modelo_criador_funcao'], '', $lista_referencia_pai[$i]['modelo_criador_original']);
					$data=retorna_data($lista_referencia_pai[$i]['modelo_data'], false);
					}
				$lista= 'Doc. '.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_assunto']? ' - '.$lista_referencia_pai[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data;
				$vetor_doc_referencia[$lista_referencia_pai[$i]['referencia_doc_pai']]=$lista;
				}
			}
		}

	echo '<tr id="mensagens_referencia" border=0 style="display:'.(count($vetor_msg_referencia)? '' : 'none').'"><td align="center" colspan=3><table width="100%"><tr><td align="center">'.dica(ucfirst($config['mensagens']).' Referenciad'.$config['genero_mensagem'].'s','Lista de '.$config['mensagens'].' a'.($config['genero_mensagem']=='o' ? 'o' : '').'s quais este documento faz referência.').'Mensagens Referenciadas'.dicaF().'</td></tr><tr><td align="center">'.selecionaVetor($vetor_msg_referencia, 'lista_msg_referencia[]', ' multiple size=3 class="texto" style="width:745px;" ondblClick="javascript:remover_msg(); return false;"','','','lista_msg_referencia').'</td></tr></table></td></tr>';

	echo '<tr id="documentos_referencia" border=0 style="display:'.(count($vetor_doc_referencia)? '' : 'none').'"><td align="center" colspan=3><table width="100%"><tr><td align="center">'.dica('Documentos Referenciados','Lista de documentos aos quais este documento faz referência.').'Documentos Referenciados'.dicaF().'</td></tr><tr><td align="center">'.selecionaVetor($vetor_doc_referencia, 'lista_doc_referencia[]', ' multiple size=3 class="texto" style="width:745px;" ondblClick="javascript:remover_referencia(); return false;"','','','lista_doc_referencia').'</td></tr></table></td></tr>';
	}


if ($modelo->edicao) {
	$class_sigilosa=getSisValor('class_sigilosa', '','CAST(sisvalor_valor_id AS '. ( $config['tipoBd']==	'mysql' ? 'UNSIGNED' : '' ). ' INTEGER) <= '.(int)$Aplic->usuario_acesso_email, 'sisvalor_valor_id ASC');
	echo '<tr><td align="center"><table><tr style="font-family:verdana, arial, helvetica, sans-serif;font-size:8pt;"><td>Documento: '.(isset($linha['modelo_id']) ? $linha['modelo_id'].' - ' : '').$assinado.dica('Assunto','Assunto a que este documento se refere.').'Assunto:'.dicaF().'<input type="text" class="texto" name="assunto" value="'.(isset($linha['modelo_assunto']) ? $linha['modelo_assunto'] : '').'" size="100" /></td><td align="left">'.dica('Sigilo', 'Escolha a classsificação sigilosa deste documento.<br>Somente '.$config['genero_usuario'].'s '.$config['usuarios'].' com perfil acesso compatível poderão visualiza-lo').'Sigilo: '.dicaF().selecionaVetor($class_sigilosa, 'class_sigilosa','class="texto" size=1 style="width:110px"',(isset($linha['class_sigilosa']) ? $linha['class_sigilosa'] : '')).'</td></tr></table></td></tr>';
	}
else echo (isset($linha['modelo_assunto']) ? '<tr style="font-family:verdana, arial, helvetica, sans-serif;font-size:8pt;"><td align="center">Documento: '.(isset($linha['modelo_id']) ? $linha['modelo_id'] : '').' - '.$assinado.dica('Assunto','Assunto a que este documento se refere.').'Assunto: '.dicaF().$linha['modelo_assunto'].'</td></tr>' : '');
echo '<tr><td><table border=1 align="center" cellspacing=0 cellpadding=0><tr><td>';

for ($i=1; $i <= $modelo->quantidade(); $i++){
	$campo='campo_'.$i;
	$tpl->$campo = $modelo->get_campo($i);
	}
echo $tpl->exibir($modelo->edicao);
echo '</td></tr></table>';

echo '</td></tr></table>';

if ($Aplic->profissional && $modelo->edicao) include_once BASE_DIR.'/modulos/email/modelo_editar_pro.php';
elseif ($Aplic->profissional) include_once BASE_DIR.'/modulos/email/modelo_ver_pro.php';
echo '</form>';

?>
<script type="text/javascript">

function popDept() {
  if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&cia_id=<?php echo $Aplic->usuario_cia ?>', window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&cia_id=<?php echo $Aplic->usuario_cia ?>','dept','left=0,top=0,height=600,width=400, scrollbars=yes, resizable');
	}


function setDept(cia, chave, val) {
	document.getElementById('dept_protocolo').value=chave;
	xajax_protocolo_dept_ajax(chave);
	}


function popDocumentos_referencia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&referenciar_documento=1', window.anexar_documento_referencia, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&referenciar_documento=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}
function popMensagem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=mensagem_pesquisar&dialogo=1&referenciar_mensagem=1', window.anexar_mensagem_referencia, window);
	else window.open('./index.php?m=email&a=mensagem_pesquisar&dialogo=1&referenciar_mensagem=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}


function remover_referencia(){
	for(var i=0; i < document.getElementById('lista_doc_referencia').options.length; i++) {
		if (document.getElementById('lista_doc_referencia').options[i].selected && document.getElementById('lista_doc_referencia').options[i].value) {
			document.getElementById('lista_doc_referencia').options[i].value = "";
			document.getElementById('lista_doc_referencia').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_doc_referencia'), document.getElementById('lista_doc_referencia').options.length);
	if (!document.getElementById('lista_doc_referencia').options.length) document.getElementById('documentos_referencia').style.display = 'none';
	}


function remover_msg(){
	for(var i=0; i < document.getElementById('lista_msg_referencia').options.length; i++) {
		if (document.getElementById('lista_msg_referencia').options[i].selected && document.getElementById('lista_msg_referencia').options[i].value) {
			document.getElementById('lista_msg_referencia').options[i].value = "";
			document.getElementById('lista_msg_referencia').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_msg_referencia'), document.getElementById('lista_msg_referencia').options.length);
	if (!document.getElementById('lista_msg_referencia').options.length) document.getElementById('mensagens_referencia').style.display = 'none';
	}

// Limpa Vazios
function limpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		limpaVazios(box, box_len);
		}
	}

function anexar_mensagem_referencia(msg_id, texto){
	document.getElementById('mensagens_referencia').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_msg_referencia').options.length; k++){
		if (document.getElementById('lista_msg_referencia').options[k].value == msg_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert("Est<?php echo ($config['genero_mensagem']=='a' ? 'a': 'e').' '.$config['mensagem']?> já havia sido referenciad<?php echo $config['genero_mensagem']?>");
	else {
		var item = new Option();
		item.value = msg_id;
		item.text = texto;
		document.getElementById('lista_msg_referencia').options[document.getElementById('lista_msg_referencia').options.length] = item;
		}
	}


function anexar_documento_referencia(modelo_id, texto){
	document.getElementById('documentos_referencia').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_doc_referencia').options.length; k++){
		if (document.getElementById('lista_doc_referencia').options[k].value == modelo_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert('Este documento já havia sido referenciado');
	else {
		var item = new Option();
		item.value = modelo_id;
		item.text = texto;
		document.getElementById('lista_doc_referencia').options[document.getElementById('lista_doc_referencia').options.length] = item;
		}
	}





function salvar_doc(){
	if (env.assunto.value.length > 0) {
		var contatos_id_selecionados='';
		env.salvar.value=1;
		var total='';
		var vet = new Array();
    vet = document.getElementsByName('campos_destinatario');
    for(var i = 0; i < vet.length; i++){
	     	var obj = document.getElementsByName('campos_destinatario').item(i);
	     	campo=obj.value;
	     	contatos_id_selecionados='';
	     	var arr = new Array();
		    arr = document.getElementsByName('nome_dest_'+campo);
		    for(var i = 0; i < arr.length; i++){
		       var obj = document.getElementsByName('nome_dest_'+campo).item(i);
		       contatos_id_selecionados+=obj.value+'#';
		       }
		    funcoes_id_selecionados='';
		    arr = document.getElementsByName('funcao_'+campo);
		    for(var i = 0; i < arr.length; i++){
		       var obj = document.getElementsByName('funcao_'+campo).item(i);
		       funcoes_id_selecionados+=obj.value+'#';
		       }

			   document.getElementById('funcao_destinatarios_'+campo).value=funcoes_id_selecionados;
	       document.getElementById('lista_destinatarios_'+campo).value=contatos_id_selecionados;
	     	}
			//anexos
			var vet2 = new Array();
	    vet2 = document.getElementsByName('campos_anexos');
	    if (vet2.length){
		    for(var i = 0; i < vet.length; i++){
			     	var obj2 = document.getElementsByName('campos_anexos').item(i);
			     	campo=obj2.value;
						var arr2 = new Array();
			      var vetor_anexo= new Array();
					  arr2 = document.getElementsByName('anexo_'+campo);
					  for(var i = 0; i < arr2.length; i++){
				       var obj = document.getElementsByName('anexo_'+campo).item(i);
				       vetor_anexo[i]=obj.value;
				       }
			     	document.getElementById('campo_'+campo).value=vetor_anexo;


						var arr3 = new Array();
			      var vetor_anexo_nomes= '';
					  arr3 = document.getElementsByName('nome_fantasia_'+campo);
					  for(var i = 0; i < arr3.length; i++){
				       var obj = document.getElementsByName('nome_fantasia_'+campo).item(i);
				       vetor_anexo_nomes=vetor_anexo_nomes+( i>0 ? '#*' : '')+obj.value;
				       }
			     	document.getElementById('campo_modelos_nomes_'+campo).value=vetor_anexo_nomes;
						}
				}


			for (var i=0; i < document.getElementById('lista_doc_referencia').length ; i++) {
				document.getElementById('lista_doc_referencia').options[i].selected = true;
				}
			for (var i=0; i < document.getElementById('lista_msg_referencia').length ; i++) {
				document.getElementById('lista_msg_referencia').options[i].selected = true;
				}


			env.a.value='modelo_editar';
			env.submit();
			}
	else {
		alert('Necessita escrever o assunto de que se trata este documento.');
		env.assunto.focus();
		}
	}


function sumir(campo){
	document.getElementById(campo).style.display = 'none';
	}

function mover_pasta(pasta_id) {
	url_passar(0, "m=email&a=modelo_pesquisar&arquivar=1&mover=<?php echo $modelo_usuario_id ?>&pasta="+pasta_id);
	};


function visualizar_extra(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 500, 500, 'm=email&a=modelo_exibe_extra&dialogo=1&modelo_usuario_id=<?php echo $modelo_usuario_id ?>&modelo_id=<?php echo $modelo_id ?>', null, window);
	else window.open('./index.php?m=email&a=modelo_exibe_extra&dialogo=1&modelo_usuario_id=<?php echo $modelo_usuario_id ?>&modelo_id=<?php echo $modelo_id ?>', '','height=600, width=810, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no, left=0, top=0');
	}

function resposta_despacho(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 500, 500, 'm=email&a=modelo_resposta_despacho&dialogo=1&modelo_id=<?php echo $modelo_id ?>&modelo_usuario_id=<?php echo $modelo_usuario_id ?>', null, window);
	else window.open('./index.php?m=email&a=modelo_resposta_despacho&dialogo=1&modelo_id=<?php echo $modelo_id ?>&modelo_usuario_id=<?php echo $modelo_usuario_id ?>', '','height=600, width=840, left=0, top=0, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}

function popDadosOrganizacao(campo, tipo_dado){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Remetente', 500, 500, 'm=publico&a=selecao_organizacao&dialogo=1&chamar_volta=setEndereco&tipo_dado='+tipo_dado+'&campo='+campo, window.setEndereco, window);
	else window.open('./index.php?m=publico&a=selecao_organizacao&dialogo=1&chamar_volta=setEndereco&tipo_dado='+tipo_dado+'&campo='+campo, 'Remetente','height=150,width=400,resizable,scrollbars=yes, left=0, top=0');
	}

function setEndereco(campo, tipo_dado, cia_nome, cia_endereco1, cia_endereco2, cia_cidade, cia_estado, cia_cep, cia_tel1, cia_tel2, cia_fax){
	if (tipo_dado=='endereco') document.getElementById('campo_'+campo).value=cia_endereco1+(cia_endereco2 ? "\n"+cia_endereco2 : "")+"\n"+cia_cidade+'-'+cia_estado+(cia_cep ? "\n"+cia_cep : "");
	else if (tipo_dado=='nome') document.getElementById('campo_'+campo).value=cia_nome;
	setGrupoId("campo"+campo+"_nome", cia_nome);
	setGrupoId("campo"+campo+"_cidade", cia_cidade);
	setGrupoId("campo"+campo+"_tel1", cia_tel1);
	setGrupoId("campo"+campo+"_fax", cia_fax);
	setGrupoId("campo"+campo+"_cep", cia_cep);
	setGrupoId("campo"+campo+"_end_completo", cia_endereco1+(cia_endereco2 ? "\n"+cia_endereco2 : "")+"\n"+cia_cidade+'-'+cia_estado+(cia_cep ? "\n"+cia_cep : ""));
	setGrupoId("campo"+campo+"_end", cia_endereco1+(cia_endereco2 ? cia_endereco2+"\n" : ""));
	}

	function setGrupoId(parcialid, valor){
		var elemento = document.getElementById(parcialid);
		if (elemento != null) document.getElementById(parcialid).value=valor;
		}

function imprimir(){
	var sem_assinatura=1;
	if(confirm('Com assinatura digitalizada, se for o caso?')) sem_assinatura=0;


	url_passar(1, "m=email&a=modelo_imprimir&dialogo=1&imprimir=1&modelo_id=<?php echo $modelo_id?>&sem_assinatura="+sem_assinatura);

	}

function popContatos(campo) {
	var contatos_id_selecionados='';
	var arr = new Array();
  arr = document.getElementsByName('nome_dest_'+campo);
  for(var i = 0; i < arr.length; i++){
     var obj = document.getElementsByName('nome_dest_'+campo).item(i);
     contatos_id_selecionados+=obj.value+',';
     }
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&campo='+campo+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&campo='+campo+'&contatos_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setContatos(retorno){
	var pedacos=retorno.split("#");
	var campo=pedacos[0];
	var resto=pedacos[1].split("*");
	var usuarios=resto[0].split(",");
	var nomes=resto[1].split(",");
	var funcoes=resto[2].split(",");
	document.getElementById('destinatarios_'+campo).innerHTML='';
	for(i=0;i<usuarios.length;i++) {
		if (usuarios[i]){
			var ni = document.getElementById('destinatarios_'+campo);
		  var novodiv = document.createElement('div');
		  var divIdNome = 'atual_'+campo+'_'+i;
		  novodiv.setAttribute('id',divIdNome);
		  novodiv.innerHTML = '<font size=1>&nbsp;'+nomes[i]+' - </font>'+'<input type="text" class="texto" name="funcao_'+campo+'" style="width:100px" value="'+funcoes[i]+'"><input type="hidden" name="nome_dest_'+campo+'" value="'+usuarios[i]+'"><a href="javascript: void(0);" onclick=\'var divIdNome="atual_'+campo+'_'+i+'"; env.campo_atual.value='+campo+'; removerElemento("'+divIdNome+'")\'><?php echo imagem("icones/excluir.gif")?></a>';
		  ni.appendChild(novodiv);
			}
		}
	}


function popAssinatura(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Assinatura', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&nome_completo=1&chamar_volta=setAssinatura&campo='+campo, window.setAssinatura, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&nome_completo=1&chamar_volta=setAssinatura&campo='+campo, 'Assinatura','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAssinatura(usuario_id, posto, nome, funcao, campo){
	document.getElementById('funcao_'+campo).value=funcao;
	<?php echo ($config['militar'] < 10	? 'document.getElementById(\'nomeguerra_\'+campo).value=nome.toUpperCase();' : 'document.getElementById(\'nomeguerra_\'+campo).value=nome;')?>
	document.getElementById('posto_'+campo).value=posto;
	document.getElementById('assinante_'+campo).value=usuario_id;
	}


function popAssinaturaImpedido(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Assinatura', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&nome_completo=1&chamar_volta=setAssinaturaImpedido&campo='+campo, window.setAssinaturaImpedido, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&nome_completo=1&chamar_volta=setAssinaturaImpedido&campo='+campo, 'Assinatura','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAssinaturaImpedido(usuario_id, posto, nome, funcao, campo){
	document.getElementById('funcaor_'+campo).value=funcao;
	<?php echo ($config['militar'] < 10	? 'document.getElementById(\'nomeguerrar_\'+campo).value=nome.toUpperCase();' : 'document.getElementById(\'nomeguerrar_\'+campo).value=nome;')?>
	document.getElementById('postor_'+campo).value=posto;
	document.getElementById('assinanter_'+campo).value=usuario_id;
	}

function popRemetente(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Remetente', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setRemetente&campo='+campo, window.setRemetente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setRemetente&campo='+campo, 'Remetente','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setRemetente(usuario_id, posto, nome, funcao, campo){
	document.getElementById('remetente_funcao_'+campo).value=funcao;
	document.getElementById('remetente_'+campo).value=usuario_id;
	}


function removerElemento(entrada){
	var campo_atual=document.getElementById('campo_atual').value;
	var d = document.getElementById('destinatarios_'+campo_atual);
 	var antigo = document.getElementById(entrada);
	d.removeChild(antigo);
	}

function setData(frm_nome, f_data) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'reg_' + f_data );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
      }
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}

function popAnexar(modelo, posicao) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Inserir Anexo', 800, 500, 'm=email&a=modelo_inserir_anexo&dialogo=1&modelo_id='+modelo+'&posicao='+posicao+'&idunico='+document.getElementById('idunico').value, window.reescrever_anexos, window);
	else window.open('./index.php?m=email&a=modelo_inserir_anexo&dialogo=1&modelo_id='+modelo+'&posicao='+posicao+'&idunico='+document.getElementById('idunico').value, 'Inserir Anexo','left=0,top=0,height=280,width=800,scrollbars=yes, resizable=yes');
	}

function popExcluir(anexo, posicao){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Excluir Anexo', 800, 500, 'm=email&a=modelo_excluir_anexo&dialogo=1&modelo_anexo_id='+anexo+'&posicao='+posicao+'&idunico='+document.getElementById('idunico').value, window.reescrever_anexos, window);
	else abrirJanela('./index.php?m=email&a=modelo_excluir_anexo&dialogo=1&modelo_anexo_id='+anexo+'&posicao='+posicao+'&idunico='+document.getElementById('idunico').value, 'Excluir Anexo',285, 520);
	}

function popRenomear(anexo, posicao, qnt){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Renomear Anexo', 800, 500, 'm=email&a=modelo_renomear_anexo&dialogo=1&modelo_anexo_id='+anexo+'&posicao='+posicao+'&qnt='+qnt, window.reescrever_anexos, window);
	else abrirJanela('./index.php?m=email&a=modelo_renomear_anexo&dialogo=1&modelo_anexo_id='+anexo+'&posicao='+posicao+'&qnt='+qnt, 'Renomear Anexo',285, 520);
	}
function popDocumentos(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Documento', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1&campo='+campo, window.anexar_documento, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1&campo='+campo, '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no, left=0, top=0');
	}

function anexar_documento(modelo_id, texto, campo){
	var existe=0;
	//checar se já existe
	var arr = new Array();
    arr = document.getElementsByName('anexo_'+campo);
    for(var i = 0; i < arr.length; i++){
       var obj = document.getElementsByName('anexo_'+campo).item(i);
       if (obj.value==modelo_id) existe=1;
       }
	if (existe) alert('Este documento havia sido anexado!');
	else{
		var qnt=arr.length+1;
		var ni = document.getElementById('anexos_'+campo);
	  var novodiv = document.createElement('div');
	  var divIdNome = 'anexo_'+campo+'_'+qnt.value;
	  novodiv.setAttribute('id',divIdNome);

	  novodiv.innerHTML ='&nbsp;<input type="text" class="texto" name="nome_fantasia_'+campo+'[]" value="'+texto+'"><input type="hidden" name="anexo_'+campo+'[]" value="'+modelo_id+'"><a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='+modelo_id+'&dialogo=1\')"><img style="vertical-align:middle" src="./estilo/rondon/imagens/icones/postagem.gif" alt="" border=0 /></a><a href="javascript: void(0);" onclick=\'var divIdNome="anexos_'+campo+'"; env.campo_atual.value='+campo+'; removerAnexo('+campo+', '+qnt.value+')\'><img style="vertical-align:middle" src="./estilo/rondon/imagens/icones/excluir.gif" alt="" border=0 /></a>';


	  ni.appendChild(novodiv);
		}
	}

function removerAnexo(campo, qnt){
	var d = document.getElementById('anexos_'+campo);
 	var antigo = document.getElementById('anexo_'+campo+'_'+qnt);
	d.removeChild(antigo);
	}

function reescrever_anexos(dados, posicao){
	document.getElementById('bloco_anexo_'+posicao).innerHTML=stripslashes(dados);
	}

function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\&lt;/g,'');
	return str;
	}

function abrirJanela(janelaURL, janelaNome, janelaAltura, janelaLargura){
  var centroLargura = (window.screen.width - janelaLargura) / 2;
  var centroAltura = (window.screen.height - janelaAltura) / 2;
  newWindow = window.open(janelaURL, janelaNome, 'resizable=0,width='+janelaLargura+',height='+janelaAltura+',left='+centroLargura+',top=' + centroAltura);
  newWindow.focus();
  return newWindow.name;
	}

</script>
