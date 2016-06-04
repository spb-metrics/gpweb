<?php
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
Classe importar_vcard para importar os contatos	de um vCard

gpweb\modulos\contatos\importar_vcard.class.php	 																																					
																																												
********************************************************************************************/
class importar_vcard {
   
    function __construct() {
    	}

    function doArquivo($nomeArquivo, $decodificar_qp = true) {
       	$handle = fopen ($nomeArquivo, "r");
    		$conteudo = fread($handle, filesize ($nomeArquivo));
    		$conteudo = str_replace("=0D=0A=\r\n", '\n', $conteudo);
    		$conteudo = str_replace("\r", '', $conteudo);
        return $this->doTexto($conteudo, $decodificar_qp);
    		}

    function doTexto($texto, $decodificar_qp = true){
        $this->convertFimLinhas($texto);
        $fold_regex = '(\n)([ |\t])';
        $texto = preg_replace("/$fold_regex/i", "", $texto);
        $linhas = explode("\n", $texto);
        return $this->_doVetor($linhas, $decodificar_qp);
    		}
    
    function convertFimLinhas(&$texto){
        $texto = str_replace("\r", "\n", $texto);
        $texto = str_replace("\n\n", "\n", $texto);
    		}

    function dividirPorPontoVirgula($texto, $converterUnico = false) {
        $regex = '(?<!\\\\)(\;)';
        $tmp = preg_split("/$regex/i", $texto);
        if ($converterUnico && count($tmp) == 1) return $tmp[0];
        else return $tmp;
    		}
    

    function dividirPorVirgula($texto, $converterUnico = false) {
        $regex = '(?<!\\\\)(\,)';
        $tmp = preg_split("/$regex/i", $texto);
        if ($converterUnico && count($tmp) == 1) return $tmp[0];
        else return $tmp;
    		}
    
        
    function semEscape(&$texto){
      if (is_array($texto)) {
        foreach ($texto as $chave => $val) {
            $this->semEscape($val);
            $texto[$chave] = $val;
        		}
    			} 
    		else {
          $texto = str_replace('\;', ';', $texto);
          $texto = str_replace('\,', ',', $texto);
          $texto = str_replace('\n', "\n", $texto);
      		}
				}
    
      
    function _importar_vcard() {
      return true;
    	}
    
    function _doVetor($fonte, $decodificar_qp = true) {
      $info = array();
      $inicio = false;
      $cartao = array();
      foreach ($fonte as $linha) {
        if (trim($linha) == '') continue;
        $pos = strpos($linha, ':');
        if ($pos === false) continue;
        $esquerda = trim(substr($linha, 0, $pos));
        $direita = trim(substr($linha, $pos+1, strlen($linha)));
        if (! $inicio) {
          if (strtoupper($esquerda) == 'BEGIN' && strtoupper($direita) == 'VCARD') $inicio = true;
          continue;
      		} 
        else {
          if (strtoupper($esquerda) == 'END' && strtoupper($direita) == 'VCARD') {
            $info[] = $cartao;
            $inicio = false;
            $cartao = array();
        		} 
          else {
            $tipodef = $this->_getTipoDef($esquerda);
            $params = $this->_getParams($esquerda);
            $this->_decodificar_qp($params, $direita);
            switch ($tipodef) {
	            case 'N':
                $valor = $this->_parseNome($direita);
                break;
	            case 'ADR':
                $valor = $this->_parseEndereco($direita);
                break;
	            case 'NICKNAME':
                $valor = $this->_parseApelido($direita);
                break;
	            case 'ORG':
                $valor = $this->_parseOrganizacao($direita);
                break;
	            case 'CATEGORIES':
                $valor = $this->_parseCategorias($direita);
                break;
	            case 'GEO':
                $valor = $this->_parseGEO($direita);
                break;
	            default:
                $valor = array(array($direita));
                break;
	            }
	          $cartao[$tipodef][] = array('param' => $params,'valor' => $valor);
	          }
	      	}
	  		}
	  	$this->semEscape($info);
	  	return $info;
			}
    
    function _getTipoDef($texto){
	    $dividido = $this->dividirPorPontoVirgula($texto);
	    return $dividido[0];
			}
    
    function _getParams($texto) {
      $dividido = $this->dividirPorPontoVirgula($texto);
      
     // array_shift($dividido);
      $params = array();
      
      foreach ($dividido as $completo) {
        $tmp = explode("=", $completo);
        $chave = strtoupper(trim($tmp[0]));
        $nome = $this->_getParamNome($chave);
        $listaTudo = trim($tmp[1]);
        $lista = $this->dividirPorVirgula($listaTudo);
        foreach ($lista as $val) {
          if (trim($val)) $params[$nome][] = trim($val);
          else $params[$nome][] = $chave;
      		}
        if (count($params[$nome]) == 0) unset($params[$nome]);
      	}
      return $params;
  		}
      
    function _decodificar_qp(&$params, &$texto){
      foreach ($params as $param_chave => $param_val) {
        if (trim(strtoupper($param_chave)) == 'ENCODING') {
          foreach ($param_val as $enc_chave => $enc_val) {
            if (trim(strtoupper($enc_val)) == 'QUOTED-PRINTABLE') {
              $texto = quoted_printable_decode($texto);
              return;
          		}
        		}
      		}
  			}
			}
    
  
    function _getParamNome($valor){
  		static $tipos = array ('DOM', 'INTL', 'POSTAL', 'PARCEL','HOME', 'WORK', 'PREF', 'VOICE', 'FAX', 'MSG', 'CELL', 'PAGER', 'BBS', 'MODEM', 'CAR', 'ISDN', 'VIDEO', 'AOL', 'APPLELINK', 'ATTMAIL', 'CIS', 'EWORLD', 'INTERNET', 'IBMMAIL', 'MCIMAIL', 'POWERSHARE', 'PRODIGY', 'TLX', 'X400', 'GIF', 'CGM', 'WMF', 'BMP', 'MET', 'PMB', 'DIB','PICT', 'TIFF', 'PDF', 'PS', 'JPEG', 'QTIME', 'MPEG', 'MPEG2', 'AVI','WAVE', 'AIFF', 'PCM', 'X509', 'PGP');
      static $valores = array ('INLINE', 'URL', 'CID', 'CONTENT-ID');
      static $codificacao = array ('7BIT', '8BIT', 'QUOTED-PRINTABLE', 'BASE64');
      $nome = $valor;
      if (in_array($valor, $tipos)) $nome = 'TYPE';
      elseif (in_array($valor, $valores)) $nome = 'value';
      elseif (in_array($valor, $codificacao)) $nome = 'ENCODING';
      return $nome;
  		}
    
    function _parseNome($texto){
      $tmp = $this->dividirPorPontoVirgula($texto);
      return array($this->dividirPorVirgula($tmp[0]), $this->dividirPorVirgula($tmp[1]), $this->dividirPorVirgula($tmp[2]), $this->dividirPorVirgula($tmp[3]), $this->dividirPorVirgula($tmp[4]));
  		}
    
    
    function _parseEndereco($texto) {
      $tmp = $this->dividirPorPontoVirgula($texto);
      return array($this->dividirPorVirgula($tmp[0]), $this->dividirPorVirgula($tmp[1]), $this->dividirPorVirgula($tmp[2]), $this->dividirPorVirgula($tmp[3]), $this->dividirPorVirgula($tmp[4]), $this->dividirPorVirgula($tmp[5]), $this->dividirPorVirgula($tmp[6]));
  		}
    
   
    
    function _parseApelido($texto){
      return array($this->dividirPorVirgula($texto));
  		}
    
    function _parseTel($texto){
      return array($this->dividirPorVirgula($texto));
  		}
    
    function _parseOrganizacao($texto){
      $tmp = $this->dividirPorPontoVirgula($texto);
      $lista = array();
      foreach ($tmp as $val) $lista[] = array($val);
      return $lista;
  		}
    
    function _parseCategorias($texto) {
      return array($this->dividirPorVirgula($texto));
  		}
    
    function _parseGEO($texto){
      $tmp = $this->dividirPorPontoVirgula($texto);
      return array(array($tmp[0]), array($tmp[1]));
  		}
		}

?>
