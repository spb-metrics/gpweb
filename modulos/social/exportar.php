<?php
if (!($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'exporta_familia')) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
$sql = new BDConsulta;

$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

$botoesTitulo = new CBlocoTitulo('Gerar Arquivo com '.$config['genero_beneficiario'].'s '.ucfirst($config['beneficiarios']), 'importar.gif', $m, "$m.$a");
$botoesTitulo->adicionaBotao('m=sistema&a=index&u=', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar(); 

if (getParam($_REQUEST, 'exportar', 0)){
	$marcado=getParam($_REQUEST, 'marcado', array());
	$novos=0;
	if ($marcado[0]=='novos') {
		$novos=1;
		array_shift($marcado);
		}
	$saida='';
	if (count($marcado)) {
		foreach ($marcado as $chave=> $valor) $saida.='"'.$valor.'"'.($chave ? ',' : '');
		}
	if ($saida) $saida=($novos ? 'social_familia_uuid IS NULL OR ' : '').'social_familia_uuid IN ('.$saida.')';
	elseif ($novos) $saida='social_familia_uuid IS NULL';

	if ($saida){
		$sql->adTabela('social_familia');
		$sql->adCampo('DISTINCT social_familia_id');
		$sql->adOnde($saida);
		$beneficiarios= $sql->carregarColuna();
		$sql->limpar();
		$beneficiarios=implode(',',$beneficiarios);
		$uuid=uuid();
		
		if ($beneficiarios){
			$sql->adTabela('social_familia');
			$sql->adAtualizar('social_familia_uuid', $uuid);
			$sql->adOnde('social_familia_id IN ('.$beneficiarios.')');
			$sql->adOnde('social_familia_uuid IS NULL');
			$sql->exec();
			$sql->limpar();
			
			if (!is_dir($base_dir)){
				$res = mkdir($base_dir, 0777);
				if (!$res) {
					$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
					return false;
					}
				}	
			
			if (!is_dir($base_dir.'/arquivos')){
				$res = mkdir($base_dir.'/arquivos', 0777);
				if (!$res) {
					$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões de esctita em '.$base_dir.'\.', UI_MSG_ALERTA);
					return false;
					}
				}	
	 	
	 	if (!is_dir($base_dir.'/arquivos/temp')){
			$res = mkdir($base_dir.'/arquivos/temp', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões de esctita em '.$base_dir.'\temp', UI_MSG_ALERTA);
				return false;
				}
			}	
			
			if (!is_dir($base_dir.'/arquivos/temp/'.$uuid)){
				$res = mkdir($base_dir.'/arquivos/temp/'.$uuid, 0777);
				if (!$res) {
					$Aplic->setMsg('A pasta para arquivos para exportação não foi configurada para receber arquivos - mude as permissões de esctita em '.$base_dir.'/arquivos/temp/'.$uuid, UI_MSG_ALERTA);
					exit();
					}
				}	
			
			$sql->adTabela('social_familia');
			$sql->adCampo('social_familia.*');
			$sql->adOnde('social_familia_id IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia.txt', $lista, false);
			
			$sql->adTabela('social_familia_acao');
			$sql->adCampo('social_familia_acao.*');
			$sql->adOnde('social_familia_acao_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_acao.txt', $lista, false);
			
			$sql->adTabela('social_familia_acao_negada');
			$sql->adCampo('social_familia_acao_negada.*');
			$sql->adOnde('social_familia_acao_negada_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_acao_negada.txt', $lista, false);
			
			$sql->adTabela('social_familia_arquivo');
			$sql->adCampo('social_familia_arquivo.*');
			$sql->adOnde('social_familia_arquivo_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_arquivo.txt', $lista, false);
			
			$sql->adTabela('social_familia_irrigacao');
			$sql->adCampo('social_familia_irrigacao.*');
			$sql->adOnde('social_familia_irrigacao_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_irrigacao.txt', $lista, false);
			
			$sql->adTabela('social_familia_lista');
			$sql->adCampo('social_familia_lista.*');
			$sql->adOnde('social_familia_lista_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_lista.txt', $lista, false);
			
			$sql->adTabela('social_familia_log');
			$sql->adCampo('social_familia_log.*');
			$sql->adOnde('social_familia_log_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_log.txt', $lista, false);
			
			$sql->adTabela('social_familia_opcao');
			$sql->adCampo('social_familia_opcao.*');
			$sql->adOnde('social_familia_opcao_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_opcao.txt', $lista, false);
			
			
			$sql->adTabela('social_familia_problema');
			$sql->adCampo('social_familia_problema.*');
			$sql->adOnde('social_familia_problema_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_problema.txt', $lista, false);
			
		
			$sql->adTabela('social_familia_producao');
			$sql->adCampo('social_familia_producao.*');
			$sql->adOnde('social_familia_producao_familia IN ('.$beneficiarios.')');
			$lista= $sql->Lista();
			$sql->limpar();
			escrever_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_producao.txt', $lista, false);
			
			$sql->adTabela('social_familia_envio');
			$sql->adInserir('social_familia_envio_uuid', $uuid);
			$sql->adInserir('social_familia_envio_data', date('Y-m-d H:i:s'));
			$sql->adInserir('social_familia_envio_nome', ($Aplic->usuario_posto ? $Aplic->usuario_posto.' ' : '').$Aplic->usuario_nomeguerra);
			$sql->exec();
			$sql->limpar();
			
			
			//compactar em um único arquivos
			$zip= new ZipArchive();
			if(($zip->open($base_dir.'/arquivos/temp/'.$uuid.'.zip', ZipArchive::CREATE))!==true){ die('Erro: Não foi possível criar o arquivo zip');}
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia.txt','social_familia.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_acao.txt','social_familia_acao.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_acao_negada.txt','social_familia_acao_negada.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_arquivo.txt','social_familia_arquivo.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_irrigacao.txt','social_familia_irrigacao.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_lista.txt','social_familia_lista.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_log.txt','social_familia_log.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_opcao.txt','social_familia_opcao.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_problema.txt','social_familia_problema.txt');
			$zip->addFile($base_dir.'/arquivos/temp/'.$uuid.'/social_familia_producao.txt','social_familia_producao.txt');
			$zip->close();

			echo estiloTopoCaixa();
			echo '<table width="100%" border=0 cellpadding="2" cellspacing=0 class="std">';
			echo '<tr><td align=center><h2>Arquivo para Exportação Gerado</h3></td></tr>';
			echo '<tr><td align=center><br><b><a href="'.$base_url.'/arquivos/temp/'.$uuid.'.zip">'.$uuid.'.zip</a></b><br></td></tr>';
			echo '<tr><td>'.botao('voltar', 'Voltar', 'Clique neste botão para voltar à lista de beneficiários.','','url_passar(0, \'m=social&a=familia_lista\');').'</td></tr>';
			echo '</table>';
			echo estiloFundoCaixa();
			exit();
			}
		}
	}

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$ordenar = getParam($_REQUEST, 'ordenar', 'social_familia_envio_data');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('social_familia');
$sql->adCampo('count(social_familia_id)');
$sql->adOnde('social_familia_uuid IS NULL');
$qnt= $sql->Resultado();
$sql->limpar();



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="exportar" value="" />';
echo '<input type="hidden" name="qnt" id="qnt" value="'.$qnt.'" />';

$sql->adTabela('social_familia_envio');
$sql->adCampo('social_familia_envio.*');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$lista= $sql->Lista();
$sql->limpar();

echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<th nowrap="nowrap">'.dica('Marcar Todos', 'Clique nesta caixa de opção para marcar todos os problemas da lista abaixo.').'<input type="checkbox" value="1" name="todos" id="todos" onclick="marcar_todos();" />'.dicaF().'</th>';
echo '<th width="80"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&ordenar=social_familia_envio_data&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_envio_data' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'Neste campo fica a data da geração do arqivo.').'Data'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&ordenar=social_familia_envio_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_envio_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'Neste campo fica o nome do responsável pela geração do arquivo.').'Responsável'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&ordenar=social_familia_envio_uuid&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_envio_uuid' ? imagem('icones/'.$seta[$ordem]) : '').dica('Arquivo', 'Neste campo fica o arquivo gerado.').'Arquivo'.dicaF().'</a></th>';


echo '<tr><td nowrap="nowrap" width="20">'.dica('Marcar para Exportar', 'Clique nesta caixa para exportar novos beneficiários').'<input type="checkbox" value="novos" name="marcado[]" '.(!$qnt ? 'DISABLED' : '').' /></td><td nowrap="nowrap" >'.retorna_data(date('Y-m-d H:i:s')).'</td><td>'.$qnt.' novos beneficiários</td><td>&nbsp</td></tr>';
foreach($lista as $linha){
	echo '<tr>';
	echo '<td nowrap="nowrap" width="20">'.dica('Marcar para Exportar', 'Clique nesta caixa para marcar este lote para nova exportação.').'<input type="checkbox" value="'.$linha['social_familia_envio_uuid'].'" name="marcado[]" /></td>';
	echo '<td nowrap="nowrap" width="20">'.retorna_data($linha['social_familia_envio_data']).'</td>';
	echo '<td>'.$linha['social_familia_envio_nome'].'</td>';
	echo '<td>'.dica('Download','Clique neste link para fazer download deste arquivo de exportação zipado.').'<a href="'.$base_url.'/arquivos/temp/'.$linha['social_familia_envio_uuid'].'.zip"><b>'.$linha['social_familia_envio_uuid'].'.zip</b></a>'.dicaF().'</td>';
	echo '</tr>';
	}	

echo '</table>';	
echo '<table cellpadding=0 cellspacing=0 class="std" width="100%"><tr><td>'.botao('exportar', 'Exportar', 'Clique neste botão para gerar o arquivo de exportação dos beneficiários.','','exportar();').'</td></tr></table>';
echo estiloFundoCaixa();

echo '</form>';

function tira_quebra($texto){
	return strtr($texto, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
	}
 
function escrever_arquivo_tab($caminho, $array, $salvar_chave=false){
	$conteudo = '';
 
 	$cabecalho='';
 	if (isset($array[0])){
 		foreach ($array[0] as $chave => $valor) $cabecalho.=$chave."\t";
 		$cabecalho.="\n";
		}

  while(list($chave, $val) = each($array)){
    $chave = str_replace("\t", " ", $chave);
    $val = str_replace("\t", " ", $val);
    
    if (is_array($val)){
    	foreach ($val as $chave1 => $valor1) $val[$chave1]=tira_quebra($valor1);
    	}
   
    if ($salvar_chave){ $conteudo .=  $chave."\t"; }
    $conteudo .= (is_array($val)) ? implode("\t", $val) : $val;
    $conteudo .= "\n";
		}
	$conteudo=$cabecalho.$conteudo;
  if (file_exists($caminho) && !is_writeable($caminho)) return false;
  if ($fp = fopen($caminho, 'w+')){
    fwrite($fp, $conteudo);
    fclose($fp);
  	}
  else return false;
  return true;
	}




?>

<script type="text/javascript">

function marcar_todos(){
	
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		esteelem = document.getElementById('env').elements[i];
		if (esteelem.name=='marcado[]' && esteelem.value=='novos' && document.getElementById('qnt').value==0){}
		else esteelem.checked=!esteelem.checked; 
		}	
	
	}


function verifica_marcado(){
	var j=0;
	var total=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		esteelem = document.getElementById('env').elements[i];
		if (esteelem.checked)total++; 
		}	
	return total;
	}


function exportar(){
	if (!verifica_marcado()) alert('Precisa selecionar ao menos um lote para exportação!');
	else {
		document.env.exportar.value=1;
		document.env.submit();
		}
	}
	
</script>