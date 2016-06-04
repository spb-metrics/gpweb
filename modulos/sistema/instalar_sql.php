<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$pode_ver_sistem=$Aplic->checarModulo('sistema', 'acesso');
if (!$pode_ver_sistem && !$Aplic->usuario_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql=getParam($_REQUEST, 'sql', '');

if ($sql){
	if (!ini_get('safe_mode')) @set_time_limit(0);
	$retorno=instalacao_carregarSQL(BASE_DIR .'/instalacao/sql/extra/'.$sql);
	ver2('SQL instalado.');
	}


$botoesTitulo = new CBlocoTitulo('Instalar SQL Extra', 'administracao.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$relatorios = $Aplic->lerArquivos(BASE_DIR .'/instalacao/sql/extra', '\.sql$');

echo estiloTopoCaixa();
echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="5">';
foreach($relatorios as $relatorio){
	$nome=explode('.', $relatorio);
	
	$filename = BASE_DIR.'/instalacao/sql/extra/'.$nome[0].'.txt';
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	
	echo '<tr><td><a href="javascript:void(0);" onclick="if(confirm(\'Tem certeza que deseja instalar?\')) {url_passar(0, \'m=sistema&a=instalar_sql&sql='.$relatorio.'\');}">'.dica(ucfirst($relatorio),'Clique neste arquivo para instala-lo.').$relatorio.dicaF().'</a></td><td>'.$contents.'</td></tr>';
	}

echo '</table>';
echo estiloFundoCaixa();

function instalacao_dividirSQL($sql, $ultima_atualizacao) {
	 global $ultimaAtualizacaoBD;
	 $buffer = array();
	 $ret = array();
	 $sql = trim($sql);
	 $compativel =  preg_match_all('/\n#\s*(\d{8})\b/', $sql, $comparados);
	 if ($compativel) {
			$tamanho = count($comparados[0]);
		  $ultimaAtualizacaoBD = $comparados[1][$tamanho-1];
		 	}
	 if ($ultima_atualizacao && $ultima_atualizacao != '00000000') {
	  	msg("Checando por atualizações anteriores");
	  	if ($compativel) {
	   		for ($i = 0; $i < $tamanho; $i++) {
	    		if ((int)$ultima_atualizacao < (int)$comparados[1][$i]) {
	     			$comparar = '/^.*'.trim($comparados[0][$i]).'/Us';
	    			$sql = preg_replace($comparar, "", $sql);
	     			break;
	    			}
	   			}
	   		if ($i == $tamanho) return $ret;
	  		}
	 		}
	 $sql = preg_replace("|\\n#[^\\n]*\\n|", "\n", $sql);

	 $in_string = false;
	 for($i=0; $i<strlen($sql)-1; $i++) {
		 if($sql[$i] == ";" && !$in_string) {
			  $ret[] = substr($sql, 0, $i);
			  $sql = substr($sql, $i + 1);
			  $i = 0;
			  }
	  	if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") $in_string = false;
	  	elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) $in_string = $sql[$i];
	  	if(isset($buffer[1])) $buffer[0] = $buffer[1];
	  	$buffer[1] = $sql[$i];
	 		}
	 if(!empty($sql)) $ret[] = $sql;
	 return($ret);
	 }

function instalacao_carregarSQL($arquivoSQL, $ultima_atualizacao = null){
	 global $erroBD, $msgBD, $bd;
	 if (!file_exists($arquivoSQL))	return;
	 $mqr = false;
	 $pedacos = array();
	 if ($arquivoSQL) {
		  $comando_sql = fread(fopen($arquivoSQL, "r"), filesize($arquivoSQL));
		  $pedacos  = instalacao_dividirSQL($comando_sql, $ultima_atualizacao);
		  }

	 @set_magic_quotes_runtime($mqr);
	 $erros = 0;
	 $nr_pedacos = count($pedacos);
	 for ($i=0; $i < $nr_pedacos; $i++) {
		  $pedacos[$i] = trim($pedacos[$i]);
		  if(!empty($pedacos[$i]) && $pedacos[$i] != "#") {
			   if (!$resultado = $bd->Execute($pedacos[$i])) {
				   $erros++;
				   $erroBD = true;
				   $msgBD .= $bd->ErrorMsg().'<br>';
				   }
	  		}
	 		}
	  echo 'Houve '.$erros.' erros em '.$nr_pedacos.' comandos SQL no arquivo '.$arquivoSQL;
		}


?>