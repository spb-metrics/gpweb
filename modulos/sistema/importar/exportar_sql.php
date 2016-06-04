<?php

global $config;
$exportar = getParam($_REQUEST, 'exportar', 0);
$botoesTitulo = new CBlocoTitulo('Backup dos Dados', 'importar.gif', $m, "$m.$a");
$botoesTitulo->adicionaBotao('m=sistema&a=index&u=', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar(); 
if (!$dialogo) $Aplic->salvarPosicao(); 
 
 if ($exportar){

	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	$data=new CData();
	$nome = 'backup_'.$data->format('%d-%m-%Y').'.sql';
	
	
	if (!is_dir($base_dir))	$res = mkdir($base_dir, 0777);
	if (!is_dir($base_dir.'/arquivos')) $res = mkdir($base_dir.'/arquivos', 0777);
	if (!is_dir($base_dir.'/arquivos/temp')) $res = mkdir($base_dir.'/arquivos/temp', 0777);
	
	
	$arquivoSQL =$base_dir.'/arquivos/temp/'.$nome;
	$scriptSQL = DumpSQL();
	escreveNoTXT($scriptSQL, $arquivoSQL);
	
	
	//gzcompressfile($arquivoSQL); 
	
	//compactar em um único arquivos
	$zip= new ZipArchive();
	if(($zip->open($arquivoSQL.'.zip', ZipArchive::CREATE))!==true){ die('Erro: Não foi possível criar o arquivo zip');}
	$zip->addFile($arquivoSQL,$nome);
	$zip->close();
	
	@unlink($arquivoSQL);
	
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20 align=center><table><tr><td align=right>Arquivos criados:</td><td><b><a href="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/temp/'.$nome.'.zip">'.$nome.'.zip</a></b></td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	}


echo '<form name="env" method="POST">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="a" value="exportar_sql" />';
echo '<input type="hidden" name="u" value="importar" />';
echo '<input type="hidden" name="exportar" value="0" />'; 
 
if(!$exportar){
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20><table align=center><tr><td>Esta funcionalidade tem por finalidade facilitar o envio da base de dados para análise pela empresa Sistema GP-Web Ltda, para os administradores que não saibam utilizar a ferramenta PHPMyAdmin. A mesma não deve ser utilizada para backup de segurança dos dados.</td></tr></table></td></tr>';
	echo'<tr><td colspan=20><table align=center><tr><td>'.botao('criar backup', 'Criar Backup', 'Clique neste botão para criar o arquivo de backup dos dados.','','env.exportar.value=1; env.submit()').'</td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	} 
 
 
 
 
 
 echo '</form>';
 
function gzcompressfile($source,$level=false){
    $dest=$source.'.gz';
    $mode='wb'.$level;
    $error=false;
    if($fp_out=gzopen($dest,$mode)){
    	if($fp_in=fopen($source,'rb')){
      	while(!feof($fp_in)) gzwrite($fp_out,fread($fp_in,1024*512));
        fclose($fp_in);
        }
      else $error=true;
      gzclose($fp_out);
      }
    else $error=true;
    if($error) return false;
    else return $dest;
    } 
 
 
function escreveNoTXT($consultasSQL, $arquivoSQL){
$arquivo = $arquivoSQL;
if (!$abrir = fopen($arquivo,"w")) $retorno = "ERRO AO ABRIR";
else $retorno = true;
if (!fwrite($abrir,$consultasSQL)) $retorno = "ERRO AO ESCREVER";
else $retorno = true;
fclose($abrir);
return $retorno;
}
 
 
function DumpSQL(){
	$ignorar=array('alteracoes','estado_coordenadas','evento_recorrencia','expediente','municipios_coordenadas','sessoes');
	$sql = new BDConsulta;
	$sql->executarScript('SHOW TABLES');
	$lista=$sql->Lista();
	$sql->limpar();
	$saida='SET FOREIGN_KEY_CHECKS=0;'."\n\n";
	foreach($lista as $linha){
		$tabela = array_pop($linha); 
		$saida.= 'DROP TABLE IF EXISTS '.$tabela.';';
		$sql->executarScript('SHOW CREATE TABLE '.$tabela);
		$lista2=$sql->Linha();
		$sql->limpar();
		array_shift($lista2);
		$saida.= "\n\n".array_shift($lista2).";\n\n";
		if (!in_array($tabela, $ignorar)){
			$sql->executarScript('SELECT * FROM '.$tabela);
			$lista2=$sql->Lista();
			$sql->limpar();
			$qnt=0;
			$qnt2=0;
			$saida2='INSERT INTO '.$tabela.' VALUES'."\n";
			foreach($lista2 as $r){
				$qnt2++;
				if ($qnt++) {
					if ($qnt==500){
						$saida2.=';'."\n\n";
						$saida2.='INSERT INTO '.$tabela.' VALUES'."\n";
						$qnt=1;
						}					
					else $saida2.=','."\n";
					}
				$saida2.="('";
				foreach ($r as $chave => $valor) {
					if ($valor==null) $r[$chave]='NULL';
					else $r[$chave]=addslashes($valor);
					}
	
				$meio=implode("','",$r);	
				$meio=str_replace("'NULL'", 'NULL', $meio);	
				$saida2 .=$meio;
				
				$saida2 .= "')";
				}
			$saida2.=';'."\n\n";	
			if ($qnt2) $saida.=$saida2;
			}
		}
	return $saida;
	}

 
 

 
 
 
?>