<?php
function exportar_social(){
	global $config;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	$data=new CData();
	$nome = 'notebook_'.$data->format('%d-%m-%Y_%H-%M-%S');
	$arquivoSQL =$base_dir.'/arquivos/temp/'.$nome.'.sql';
	$scriptSQL = DumpSQL();
	escreveNoTXT($scriptSQL, $arquivoSQL);
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
			$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
			return false;
			}
		}	
	
	if (!is_dir($base_dir.'/arquivos/temp')){
		$res = mkdir($base_dir.'/arquivos/temp', 0777);
		if (!$res) {
			$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\temp.', UI_MSG_ALERTA);
			return false;
			}
		}	
	$zip= new ZipArchive();
	if(($zip->open($base_dir.'/arquivos/temp/'.$nome.'.zip', ZipArchive::CREATE))!==true){ die('Erro: Não foi possível criar o arquivo zip');}
	$zip->addFile($arquivoSQL,$nome.'.sql');
	$zip->close();
	@unlink($arquivoSQL);
	return $nome;
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
	$sql = new BDConsulta;
	$saida='SET FOREIGN_KEY_CHECKS=0;'."\n\n";
	$tabelas=array(
		'social',
		'social_acao',
		'social_acao_arquivo',
		'social_acao_conceder',
		'social_acao_depts',
		'social_acao_lista',
		'social_acao_log',
		'social_acao_negacao',
		'social_acao_problema',
		'social_acao_usuarios',
		'social_comite',
		'social_comite_acao',
		'social_comite_arquivo',
		'social_comite_lista',
		'social_comite_log',
		'social_comite_problema',
		'social_comite_membros',
		'social_comunidade',
		'social_comunidade_depts',
		'social_comunidade_log',
		'social_comunidade_usuarios',
		'social_depts',
		'social_log',
		'social_usuarios'
		);
		
	foreach($tabelas as $tabela){
    $saida.= "DELETE FROM ".$tabela.";\n\n";
		$sql->adTabela($tabela);
		$sql->adCampo($tabela.'.*');
		$lista= $sql->Lista();
		$sql->limpar();
		if (count($lista)){
			$saida.='INSERT INTO '.$tabela.' (';
			$qnt=0;
			foreach($lista[0] as $chave1 => $valor1) $saida.=($qnt++ ? ' ,' : '').$chave1;
			$saida.=') VALUES'."\n";
			$qnt=0;
			foreach($lista as $linha){ 
				$r=array();
				if ($qnt++) $saida.=','."\n";
				$saida.="('";
				foreach ($linha as $chave2 => $valor2) $r[$chave2]=addslashes($valor2);
				$saida .= implode("','",$r);
				$saida .= "')";
				}
			$saida.=';'."\n\n";	
			}
		}
	$campos=array(
		'EstadoCivil',
		'Escolaridade',
		'OrganizacaoSocial',
		'TipoResidencia',
		'TipoCoberta',
		'Lixo',
		'TratamentoAgua',
		'FrequenciaTratamento',
		'FonteAgua',
		'Ocupacao',
		'FonteRenda',
		'PeriodoRenda',
		'UsoTerra',
		'Cultura',
		'Animais',
		'FinalidadeProducao',
		'FonteAgropecuaria',
		'SistemaIrrigacao',
		'Assistencia',
		'StatusProblema',
		'Sexo',
		'ComiteTipo',
		'FamiliaCampo'
		);	
		
	foreach($campos as $campo){
    $saida.= "\n\nDELETE FROM sisvalores WHERE sisvalor_titulo='".$campo."';\n\n";
		$sql->adTabela('sisvalores');
		$sql->adCampo('*');
		$sql->adOnde('sisvalor_titulo="'.$campo.'"');
		$lista= $sql->Lista();
		$sql->limpar();
		if (count($lista)){
			$saida.='INSERT INTO sisvalores (';
			$qnt=0;
			foreach($lista[0] as $chave1 => $valor1) {
				if ($chave1!='sisvalor_id') $saida.=($qnt++ ? ' ,' : '').$chave1;
				}
			$saida.=') VALUES'."\n";
			$qnt=0;
			foreach($lista as $linha){ 
				$r=array();
				if ($qnt++) $saida.=','."\n";
				$saida.="('";
				foreach ($linha as $chave2 => $valor2) {
					if ($chave2!='sisvalor_id') $r[$chave2]=addslashes($valor2);
					}
				$saida .= implode("','",$r);
				$saida .= "')";
				}
			$saida.=';'."\n\n";	
			}
		}
	return $saida;
	}
	
?>