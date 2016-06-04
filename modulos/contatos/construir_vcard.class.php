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
		
gpweb\modulos\contatos\construir_vcard.class.php		

Classe construir_vcard para criar arquivo VCard com dado dos contatos																																						
																																												
********************************************************************************************/

define('VCARD_N_FAMILY',     0);
define('VCARD_N_GIVEN',      1);
define('VCARD_N_ADDL',       2);
define('VCARD_N_PREFIX',     3);
define('VCARD_N_SUFFIX',     4);

define('VCARD_ADR_POB',      0);
define('VCARD_ADR_EXTEND',   1);
define('VCARD_ADR_STREET',   2);
define('VCARD_ADR_LOCALITY', 3);
define('VCARD_ADR_REGION',   4);
define('VCARD_ADR_POSTCODE', 5);
define('VCARD_ADR_COUNTRY',  6);

define('VCARD_GEO_LAT',      0);
define('VCARD_GEO_LON',      1);


class construir_vcard {
  var $valor = array();
  var $param = array();
  var $autoparam = null;

  function __construct($versao = '3.0') {
    $this->reset($versao);
  	}

  function escape(&$texto) {
    if (is_object($texto)) {   } 
    elseif (is_array($texto)){
      foreach ($texto as $chave => $val) {
        $this->escape($val);
        $texto[$chave] = $val;
     		}
    	} 
    else {
      $regex = '(?<!\\\\)(\;)';
      $texto = preg_replace("/$regex/i", "\\;", $texto);
      $regex = '(?<!\\\\)(\,)';
      $texto = preg_replace("/$regex/i", "\\,", $texto);
      $regex = '\\n';
      $texto = preg_replace("/$regex/i", "\\n", $texto);
    	}
		}

  function adParametro($param_nome, $param_valor, $comp = null, $iter = null) {
    if ($comp === null) $comp = $this->autoparam;
    if ($iter === null) $iter = count($this->valor[$comp]) - 1;
    $comp = strtoupper(trim($comp));
    $param_nome = strtoupper(trim($param_nome));
    $param_valor = trim($param_valor);
    if (! is_integer($iter) || $iter < 0) {   } 
    else {
      $resultado = $this->validarParametro($param_nome, $param_valor, $comp, $iter);
      $this->param[$comp][$iter][$param_nome][] = $param_valor;
    	}
		}

  function validarParametro($nome, $texto, $comp = null, $iter = null){
      $nome = strtoupper($nome);
      $texto = strtoupper($texto);
      $resultado='';
      if (preg_match('/[^a-zA-Z0-9\-]/i', $texto)) {   }
      elseif (isset($this->valor['VERSION'][0][0][0]) && $this->valor['VERSION'][0][0][0] == '2.1') {
        $tipos = array ('DOM', 'INTL', 'POSTAL', 'PARCEL','HOME', 'WORK', 'PREF', 'VOICE', 'FAX', 'MSG', 'CELL', 'PAGER', 'BBS', 'MODEM', 'CAR', 'ISDN', 'VIDEO', 'AOL', 'APPLELINK', 'ATTMAIL', 'CIS', 'EWORLD', 'INTERNET', 'IBMMAIL', 'MCIMAIL', 'POWERSHARE', 'PRODIGY', 'TLX', 'X400', 'GIF', 'CGM', 'WMF', 'BMP', 'MET', 'PMB', 'DIB', 'PICT', 'TIFF', 'PDF', 'PS', 'JPEG', 'QTIME', 'MPEG', 'MPEG2', 'AVI', 'WAVE', 'AIFF', 'PCM', 'X509', 'PGP');
        switch ($nome) {
        case 'TYPE':
          if (! in_array($texto, $tipos)) {   } 
          else $resultado = true;
          break;
        case 'ENCODING':
          if ($texto != '7BIT' && $texto != '8BIT' && $texto != 'BASE64' && $texto != 'QUOTED-PRINTABLE') { } 
          else $resultado = true;
          break;
        case 'CHARSET':
          $resultado = true;
          break;
        case 'LANGUAGE':
          $resultado = true;
          break;
        case 'value':
          if ($texto != 'INLINE' && $texto != 'CONTENT-ID' && $texto != 'CID' && $texto != 'URL' && $texto != 'VCARD') {  } 
          else $resultado = true;
          break;
        default:
          break;
        }
      } 
   	elseif (isset($this->valor['VERSION'][0][0][0]) && $this->valor['VERSION'][0][0][0] == '3.0') {
      switch ($nome) {
        case 'TYPE':
          $resultado = true;
          break;
        case 'LANGUAGE':
          $resultado = true;
          break;
        case 'ENCODING':
          if ($texto != '8BIT' && $texto != 'B') { } 
          else $resultado = true;
          break;
        case 'value':
          if ($texto != 'BINARY' && $texto != 'PHONE-NUMBER' && $texto != 'TEXT' && $texto != 'URI' && $texto != 'UTC-OFFSET' && $texto != 'VCARD') {} 
          else $resultado = true;
          break;
        default:
          break;
        }
      } 
    else {/*versao desconhecida*/}
    return $resultado;
 		}

  function getParametro($comp, $iter = 0) {
    $comp = strtoupper($comp);
    $texto = '';
    if (isset($this->param[$comp][$iter]) && is_array($this->param[$comp][$iter])) {
      foreach ($this->param[$comp][$iter] as $param_nome => $param_val) {
        if ($texto) $texto .= ';';
        if ($param_val === null) {
          $this->escape($param_nome);
          $texto .= $param_nome;
      		} 
        else {
          if ($param_val[0]) $texto .= strtoupper($param_nome).'=';
          else $texto .= strtoupper($param_nome);
          $this->escape($param_val);
          $texto .= implode(',', $param_val);
        	}
    		}
			}
 		return $texto;
		}
  
  function reset($versao = null) {
    $prev = (isset($this->valor['VERSION'][0][0][0]) ? $this->valor['VERSION'][0][0][0] : '');
    $this->valor = array();
    $this->param = array();
    $this->autoparam = null;
    if ($versao === null) $this->setVersao($prev);
    else $this->setVersao($versao);
  	}
 
  function getMeta($comp, $iter = 0) {
    $params = $this->getParametro($comp, $iter);
    if (trim($params) == '') $texto = $comp.':';
   	else $texto = $comp.';'.$params.':';
    return $texto;
		}

  function setValor($comp, $iter, $part, $texto) {
    $comp = strtoupper($comp);
    settype($texto, 'array');
    $this->valor[$comp][$iter][$part] = $texto;
    $this->autoparam = $comp;
		}
  
  function adValor($comp, $iter, $part, $texto) {
    $comp = strtoupper($comp);
    settype($texto, 'array');
    foreach ($texto as $val) $this->valor[$comp][$iter][$part][] = $val;
    $this->autoparam = $comp;
		}
  
  function getValor($comp, $iter = 0, $part = 0, $rept = null) {
    if ($rept === null && isset($this->valor[$comp][$iter][$part]) && is_array($this->valor[$comp][$iter][$part])) {
      $list = array();
      foreach ($this->valor[$comp][$iter][$part] as $chave => $val) $list[] = trim($val);
      $this->escape($list);
      return implode(',', $list);
  		} 
    else {
      $texto = trim($this->valor[$comp][$iter][$part][$rept]);
      $this->escape($texto);
      return $texto;
    	}
		}
  
  function setNome($familia, $dado, $addl, $prefixo, $sufixo) {
    $this->autoparam = 'N';
    $this->setValor('N', 0, VCARD_N_FAMILY, $familia);
    $this->setValor('N', 0, VCARD_N_GIVEN, $dado);
    $this->setValor('N', 0, VCARD_N_ADDL, $addl);
    $this->setValor('N', 0, VCARD_N_PREFIX, $prefixo);
    $this->setValor('N', 0, VCARD_N_SUFFIX, $sufixo);
		}
  
  function getNome(){
    return $this->getMeta('N', 0).$this->getValor('N', 0, VCARD_N_FAMILY).';'.$this->getValor('N', 0, VCARD_N_GIVEN).';'.$this->getValor('N', 0, VCARD_N_ADDL).';'.$this->getValor('N', 0, VCARD_N_PREFIX).';'.$this->getValor('N', 0, VCARD_N_SUFFIX);
  	}
  
  function setNomeFormatado($texto = null) {
    $this->autoparam = 'FN';
    if ($texto === null) {
      if (isset($this->valor['N']) && is_array($this->valor['N'])) {
        $texto .= $this->getValor('N', 0, VCARD_N_GIVEN, 0);
        if ($texto != '') $texto .= ' ';
        $texto .= $this->getValor('N', 0, VCARD_N_FAMILY, 0);
        if ($texto != '') $texto .= ' ';
        $texto .= $this->getValor('N', 0, VCARD_N_SUFFIX, 0);
      
    		} 
    	else { }
  		}
  	$this->setValor('FN', 0, 0, $texto);
		}
   
  function getNomeFormatado() {
    return $this->getMeta('FN', 0).$this->getValor('FN', 0, 0);
  	}

  function setVersao($texto = '3.0') {
    $this->autoparam = 'VERSION';
    if ($texto != '3.0' && $texto != '2.1') {  } 
    else $this->setValor('VERSION', 0, 0, $texto);
		}
  
  function getVersao() {
    return $this->getMeta('VERSION', 0).$this->getValor('VERSION', 0);
		}

  function setOrigem($texto) {
    $this->autoparam = 'SOURCE';
    $this->setValor('SOURCE', 0, 0, $texto);
		}
 
  function getFonte() {
    return $this->getMeta('SOURCE', 0).$this->getValor('SOURCE', 0, 0);
		}
  
  function setOrigemNome($texto = null) {
    $this->autoparam = 'NAME';
    if ($texto === null) {
      if (isset($this->valor['SOURCE'][0]) && is_array($this->valor['SOURCE']))  $texto = $this->getValor('SOURCE', 0, 0);
    	}
    $this->setValor('NAME', 0, 0, $texto);
		}

  function getFonteNome() {
    return $this->getMeta('NAME', 0). $this->getValor('NAME', 0, 0);
		}

  function setFoto($texto) {
    $this->autoparam = 'PHOTO';
    $this->setValor('PHOTO', 0, 0, $texto);
		}
     
  function getFoto() {
    return $this->getMeta('PHOTO').$this->getValor('PHOTO', 0, 0);
		}
  
  function setLogo($texto) {
    $this->autoparam = 'LOGO';
    $this->setValor('LOGO', 0, 0, $texto);
		}
  
  function getLogo() {
    return $this->getMeta('LOGO').$this->getValor('LOGO', 0, 0);
	 	}
  
  function setSound($texto) {
    $this->autoparam = 'SOUND';
    $this->setValor('SOUND', 0, 0, $texto);
		}
  
  function getSound(){
    return $this->getMeta('SOUND').$this->getValor('SOUND', 0, 0);
		}
  
  function setKey($texto){
    $this->autoparam = 'KEY';
    $this->setValor('KEY', 0, 0, $texto);
		}

  function getKey() {
    return $this->getMeta('KEY').$this->getValor('KEY', 0, 0);
		}
  
  function setAniversario($texto) {
    $this->autoparam = 'BDAY';
    $this->setValor('BDAY', 0, 0, $texto);
		}

  function getAniversario() {
    return $this->getMeta('BDAY').$this->getValor('BDAY', 0, 0);
		}
     
  function setTZ($texto) {
    $this->autoparam = 'TZ';
    $this->setValor('TZ', 0, 0, $texto);
		}

  function getTZ() {
    return $this->getMeta('TZ').$this->getValor('TZ', 0, 0);
		}
  
  function setMailer($texto) {
    $this->autoparam = 'MAILER';
    $this->setValor('MAILER', 0, 0, $texto);
		}

  function getMailer() {
    return $this->getMeta('MAILER') .
    $this->getValor('MAILER', 0, 0);
		}

  function setNota($texto) {
    $this->autoparam = 'NOTE';
    $this->setValor('NOTE', 0, 0, $texto);
		}
  
  function getNote(){
    return $this->getMeta('NOTE').$this->getValor('NOTE', 0, 0);
	 	}
 
  function setTitulo($texto) {
    $this->autoparam = 'TITLE';
    $this->setValor('TITLE', 0, 0, $texto);
		}
 
  function getTitulo() {
    return $this->getMeta('TITLE'). $this->getValor('TITLE', 0, 0);
		}
  
  function setPerfil($texto) {
    $this->autoparam = 'ROLE';
    $this->setValor('ROLE', 0, 0, $texto);
		}

  function getPerfil() {
    return $this->getMeta('ROLE').$this->getValor('ROLE', 0, 0);
		}

  function setURL($texto){
    $this->autoparam = 'URL';
    $this->setValor('URL', 0, 0, $texto);
		}

  function getURL() {
    return $this->getMeta('URL').$this->getValor('URL', 0, 0);
		}
     
  function setClass($texto) {
    $this->autoparam = 'CLASS';
    $this->setValor('CLASS', 0, 0, $texto);
		}

  function getClass() {
    return $this->getMeta('CLASS') .
    $this->getValor('CLASS', 0, 0);
		}
  
  function setSortString($texto) {
    $this->autoparam = 'SORT-STRING';
    $this->setValor('SORT-STRING', 0, 0, $texto);
	 	}
  
  function getSortString() {
    return $this->getMeta('SORT-STRING') .
    $this->getValor('SORT-STRING', 0, 0);
		}
  
  function setProductID($texto) {
    $this->autoparam = 'PRODID';
    $this->setValor('PRODID', 0, 0, $texto);
		}
  
  function getProductID() {
    return $this->getMeta('PRODID') .
    $this->getValor('PRODID', 0, 0);
		}
  
  function setRevision($texto) {
    $this->autoparam = 'REV';
    $this->setValor('REV', 0, 0, $texto);
		}

  function getRevision() {
    return $this->getMeta('REV').$this->getValor('REV', 0, 0);
		}

  function setIdExclusivo($texto) {
    $this->autoparam = 'UID';
    $this->setValor('UID', 0, 0, $texto);
		}
  
  function getUniqueID() {
    return $this->getMeta('UID').$this->getValor('UID', 0, 0);
		}
  
  function setAgent($texto) {
    $this->autoparam = 'AGENT';
    $this->setValor('AGENT', 0, 0, $texto);
		}
  
  function getAgent() {
    return $this->getMeta('AGENT').$this->getValor('AGENT', 0, 0);
		}
   
  function setGeo($lat, $lon){
    $this->autoparam = 'GEO';
    $this->setValor('GEO', 0, VCARD_GEO_LAT, $lat);
    $this->setValor('GEO', 0, VCARD_GEO_LON, $lon);
		}
  
  function getGeo() {
    return $this->getMeta('GEO', 0) .
    $this->getValor('GEO', 0, VCARD_GEO_LAT, 0).';'. $this->getValor('GEO', 0, VCARD_GEO_LON, 0);
		}

  function adEndereco($pob, $extend, $street, $locality, $region, $postcode, $pais){
    $this->autoparam = 'ADR';
    $iter = count((isset($this->valor['ADR']) ? $this->valor['ADR'] : ''));
    $this->setValor('ADR', $iter, VCARD_ADR_POB,       $pob);
    $this->setValor('ADR', $iter, VCARD_ADR_EXTEND,    $extend);
    $this->setValor('ADR', $iter, VCARD_ADR_STREET,    $street);
    $this->setValor('ADR', $iter, VCARD_ADR_LOCALITY,  $locality);
    $this->setValor('ADR', $iter, VCARD_ADR_REGION,    $region);
    $this->setValor('ADR', $iter, VCARD_ADR_POSTCODE,  $postcode);
    $this->setValor('ADR', $iter, VCARD_ADR_COUNTRY,   $pais);
		}

  function getEndereco($iter) {
    if (! is_integer($iter) || $iter < 0) { } 
    else return $this->getMeta('ADR', $iter).$this->getValor('ADR', $iter, VCARD_ADR_POB).';'.$this->getValor('ADR', $iter, VCARD_ADR_EXTEND).';' .$this->getValor('ADR', $iter, VCARD_ADR_STREET).';'.$this->getValor('ADR', $iter, VCARD_ADR_LOCALITY).';' .$this->getValor('ADR', $iter, VCARD_ADR_REGION).';'.$this->getValor('ADR', $iter, VCARD_ADR_POSTCODE).';'.$this->getValor('ADR', $iter, VCARD_ADR_COUNTRY);
		}

  function adLegenda($texto) {
    $this->autoparam = 'LABEL';
    $iter = count((isset($this->valor['LABEL']) ? $this->valor['LABEL'] : ''));
    $this->setValor('LABEL', $iter, 0, $texto);
		}
  
  function getLegenda($iter) {
      if (! is_integer($iter) || $iter < 0) {  } 
      else return $this->getMeta('LABEL', $iter).$this->getValor('LABEL', $iter, 0);
  		}
   
  function adTelefone($texto) {
    $this->autoparam = 'TEL';
    $iter = count((isset($this->valor['TEL']) ? $this->valor['TEL'] : ''));
    $this->setValor('TEL', $iter, 0, $texto);
		}
 
  function getTelefone($iter) {
    if (! is_integer($iter) || $iter < 0) {  } 
    else return $this->getMeta('TEL', $iter). $this->getValor('TEL', $iter, 0);
	 	}
     
  function adEmail($texto){
    $this->autoparam = 'EMAIL';
    $iter = count((isset($this->valor['EMAIL']) ? $this->valor['EMAIL'] : ''));
    $this->setValor('EMAIL', $iter, 0, $texto);
		}
  
  function getEmail($iter) {
    if (! is_integer($iter) || $iter < 0) {   } 
    else return $this->getMeta('EMAIL', $iter).$this->getValor('EMAIL', $iter, 0);
		}

  function adApelido($texto) {
    $this->autoparam = 'NICKNAME';
    $this->adValor('NICKNAME', 0, 0, $texto);
		}
  
  function getApelido() {
    return $this->getMeta('NICKNAME').$this->getValor('NICKNAME', 0, 0);
		}
  
  function adCategorias($texto, $anexar = true)  {
    $this->autoparam = 'CATEGORIES';
    $this->adValor('CATEGORIES', 0, 0, $texto);
		}
  
  function getCategorias() {
    return $this->getMeta('CATEGORIES', 0). $this->getValor('CATEGORIES', 0, 0);
		}
  
  function adOrganizacao($texto) {
    $this->autoparam = 'ORG';
    settype($texto, 'array');
    $base = count((isset($this->valor['ORG'][0]) ? $this->valor['ORG'][0] : ''));
    foreach ($texto as $part => $val) $this->setValor('ORG', 0, $base + $part, $val);
		}
		
		
	function adYahoo($texto) {
    $this->autoparam = 'X-YAHOO';
    settype($texto, 'array');
    $base = count((isset($this->valor['X-YAHOO'][0]) ? $this->valor['X-YAHOO'][0] : ''));
    foreach ($texto as $part => $val) $this->setValor('X-YAHOO', 0, $base + $part, $val);
		}	

	function getYahoo() {
    $texto = $this->getMeta('X-YAHOO', 0);
    $k = count((isset($this->valor['X-YAHOO'][0]) ? $this->valor['X-YAHOO'][0] : ''));
    $ultimo = $k - 1;
    for ($part = 0; $part < $k; $part++) {
      $texto .= $this->getValor('X-YAHOO', 0, $part);
      if ($part != $ultimo) $texto .= ';';
  		}
    return $texto;
		}

	function adSkype($texto) {
    $this->autoparam = 'X-SKYPE-USERNAME';
    settype($texto, 'array');
    $base = count((isset($this->valor['X-SKYPE-USERNAME'][0]) ? $this->valor['X-SKYPE-USERNAME'][0] : ''));
    foreach ($texto as $part => $val) $this->setValor('X-SKYPE-USERNAME', 0, $base + $part, $val);
		}	

	function getSkype() {
    $texto = $this->getMeta('X-SKYPE-USERNAME', 0);
    $k = count((isset($this->valor['X-SKYPE-USERNAME'][0]) ? $this->valor['X-SKYPE-USERNAME'][0] : ''));
    $ultimo = $k - 1;
    for ($part = 0; $part < $k; $part++) {
      $texto .= $this->getValor('X-SKYPE-USERNAME', 0, $part);
      if ($part != $ultimo) $texto .= ';';
  		}
    return $texto;
		}

	function adMSN($texto) {
    $this->autoparam = 'X-MSN';
    settype($texto, 'array');
    $base = count((isset($this->valor['X-MSN'][0]) ? $this->valor['X-MSN'][0] : ''));
    foreach ($texto as $part => $val) $this->setValor('X-MSN', 0, $base + $part, $val);
		}	

	function getMSN() {
    $texto = $this->getMeta('X-MSN', 0);
    $k = count((isset($this->valor['X-MSN'][0]) ? $this->valor['X-MSN'][0] : ''));
    $ultimo = $k - 1;
    for ($part = 0; $part < $k; $part++) {
      $texto .= $this->getValor('X-MSN', 0, $part);
      if ($part != $ultimo) $texto .= ';';
  		}
    return $texto;
		}

	function adICQ($texto) {
    $this->autoparam = 'X-ICQ';
    settype($texto, 'array');
    $base = count((isset($this->valor['X-ICQ'][0]) ? $this->valor['X-ICQ'][0] : ''));
    foreach ($texto as $part => $val) $this->setValor('X-ICQ', 0, $base + $part, $val);
		}	

	function getICQ() {
    $texto = $this->getMeta('X-ICQ', 0);
    $k = count((isset($this->valor['X-ICQ'][0]) ? $this->valor['X-ICQ'][0] : ''));
    $ultimo = $k - 1;
    for ($part = 0; $part < $k; $part++) {
      $texto .= $this->getValor('X-ICQ', 0, $part);
      if ($part != $ultimo) $texto .= ';';
  		}
    return $texto;
		}

	function adJabber($texto) {
    $this->autoparam = 'X-JABBER';
    settype($texto, 'array');
    $base = count((isset($this->valor['X-JABBER'][0]) ? $this->valor['X-JABBER'][0] : ''));
    foreach ($texto as $part => $val) $this->setValor('X-JABBER', 0, $base + $part, $val);
		}	

	function getJabber() {
    $texto = $this->getMeta('X-JABBER', 0);
    $k = count((isset($this->valor['X-JABBER'][0]) ? $this->valor['X-JABBER'][0] : ''));
    $ultimo = $k - 1;
    for ($part = 0; $part < $k; $part++) {
      $texto .= $this->getValor('X-JABBER', 0, $part);
      if ($part != $ultimo) $texto .= ';';
  		}
    return $texto;
		}


	function adDepartamento($texto) {
    $this->autoparam = 'DEPT';
    settype($texto, 'array');
    $base = count((isset($this->valor['DEPT'][0]) ? $this->valor['DEPT'][0] : ''));
    foreach ($texto as $part => $val) $this->setValor('DEPT', 0, $base + $part, $val);
		}
		
		
		

  function getOrganizacao() {
  	//verifica se tem departamento
  	$dept = '';
  	$k = count((isset($this->valor['DEPT'][0]) ? $this->valor['DEPT'][0] : ''));
    $ultimo = $k - 1;
    for ($part = 0; $part < $k; $part++) {
      $dept .= $this->getValor('DEPT', 0, $part);
      if ($part != $ultimo) $dept .= ';';
  		}
 	
    $texto = $this->getMeta('ORG', 0);
    $k = count((isset($this->valor['ORG'][0]) ? $this->valor['ORG'][0] : ''));
    $ultimo = $k - 1;
    for ($part = 0; $part < $k; $part++) {
      $texto .= $this->getValor('ORG', 0, $part);
      if ($part != $ultimo) $texto .= ';';
  		}
    return $texto.($dept ? ';'.$dept : '');
		}
     
  function setDeVetor($src) {
    $this->valor = array();
    $this->param = array();
    foreach ($src AS $comp => $comp_val) {
      $this->autoparam = $comp; 
      foreach ($comp_val AS $iter => $iter_val) {
        foreach ($iter_val AS $tipo => $tipo_val) {
          foreach ($tipo_val AS $part => $part_val) {
            foreach ($part_val AS $rept => $texto) {
              if (strtolower($tipo) == 'value') $this->valor[strtoupper($comp)][$iter][$part][$rept] = $texto;
              elseif (strtolower($tipo) == 'param') $this->param[strtoupper($comp)][$iter][$part][$rept] = $texto;
            	}
          	}
        	}
      	}
    	}
		}
  
  function fetch() {
      if (!isset($this->valor['VERSION']) || !is_array($this->valor['VERSION'])) {   }
      if (!isset($this->valor['FN']) || ! is_array($this->valor['FN'])) {   }
      if (!isset($this->valor['N']) || ! is_array($this->valor['N'])) {   }
      $linhas = array();
      $linhas[] = "BEGIN:VCARD";
      $linhas[] = $this->getVersao();
      $linhas[] = $this->getNomeFormatado();
      $linhas[] = $this->getNome();
      if (isset($this->valor['VERSION'][0][0][0]) && $this->valor['VERSION'][0][0][0] == '3.0') $linhas[] = "PROFILE:VCARD";
      if (isset($this->valor['NAME'][0][0][0]) && is_array($this->valor['NAME']) && isset($this->valor['VERSION'][0][0][0]) && $this->valor['VERSION'][0][0][0] == '3.0') $linhas[] = $this->getFonteNome();
      if (isset($this->valor['SOURCE'][0][0][0]) && is_array($this->valor['SOURCE']) && isset($this->valor['VERSION'][0][0][0]) && $this->valor['VERSION'][0][0][0] == '3.0') $linhas[] = $this->getFonte();
      if (isset($this->valor['NICKNAME'][0][0][0]) && is_array($this->valor['NICKNAME']) &&  isset($this->valor['VERSION'][0][0][0]) && $this->valor['VERSION'][0][0][0] == '3.0') $linhas[] = $this->getApelido();
      if (isset($this->valor['PHOTO'][0][0][0]) && is_array($this->valor['PHOTO'])) $linhas[] = $this->getFoto();
      if (isset($this->valor['BDAY'][0][0][0]) && is_array($this->valor['BDAY'])) $linhas[] = $this->getAniversario();
      if (isset($this->valor['ADR'][0][0][0]) && is_array($this->valor['ADR'])) foreach ($this->valor['ADR'] as $chave => $val) $linhas[] = $this->getEndereco($chave);
      if (isset($this->valor['LABEL'][0][0][0]) && is_array($this->valor['LABEL'])) foreach ($this->valor['LABEL'] as $chave => $val) $linhas[] = $this->getLegenda($chave);
      if (isset($this->valor['TEL'][0][0][0]) && is_array($this->valor['TEL'])) foreach ($this->valor['TEL'] as $chave => $val) $linhas[] = $this->getTelefone($chave);
      if (isset($this->valor['EMAIL'][0][0][0]) && is_array($this->valor['EMAIL'])) foreach ($this->valor['EMAIL'] as $chave => $val) $linhas[] = $this->getEmail($chave);
      if (isset($this->valor['MAILER'][0][0][0]) && is_array($this->valor['MAILER'])) $linhas[] = $this->getMailer();
      if (isset($this->valor['TZ'][0][0][0]) && is_array($this->valor['TZ'])) $linhas[] = $this->getTZ();
      if (isset($this->valor['GEO'][0][0][0]) && is_array($this->valor['GEO'])) $linhas[] = $this->getGeo();
      if (isset($this->valor['TITLE'][0][0][0]) && is_array($this->valor['TITLE'])) $linhas[] = $this->getTitulo();
      if (isset($this->valor['ROLE'][0][0][0]) && is_array($this->valor['ROLE'])) $linhas[] = $this->getPerfil();
      if (isset($this->valor['LOGO'][0][0][0]) && is_array($this->valor['LOGO'])) $linhas[] = $this->getLogo();
      if (isset($this->valor['AGENT'][0][0][0]) && is_array($this->valor['AGENT'])) $linhas[] = $this->getAgent();
      if (isset($this->valor['ORG'][0][0][0]) && is_array($this->valor['ORG'])) $linhas[] = $this->getOrganizacao();
      if (isset($this->valor['CATEGORIES'][0][0][0]) && is_array($this->valor['CATEGORIES']) && $this->valor['VERSION'][0][0][0] == '3.0') $linhas[] = $this->getCategorias();
      if (isset($this->valor['NOTE'][0][0][0]) && is_array($this->valor['NOTE'])) $linhas[] = $this->getNote();
      if (isset($this->valor['PRODID'][0][0][0]) && is_array($this->valor['PRODID']) &&  $this->valor['VERSION'][0][0][0] == '3.0') $linhas[] = $this->getProductID();
      if (isset($this->valor['REV'][0][0][0]) && is_array($this->valor['REV'])) $linhas[] = $this->getRevision();
      if (isset($this->valor['SORT-STRING'][0][0][0]) && is_array($this->valor['SORT-STRING']) &&  $this->valor['VERSION'][0][0][0] == '3.0') $linhas[] = $this->getSortString();
      if (isset($this->valor['SOUND'][0][0][0]) && is_array($this->valor['SOUND'])) $linhas[] = $this->getSound();
      if (isset($this->valor['UID'][0][0][0]) && is_array($this->valor['UID'])) $linhas[] = $this->getUniqueID();
      if (isset($this->valor['URL'][0][0][0]) && is_array($this->valor['URL'])) $linhas[] = $this->getURL();
      if (isset($this->valor['X-YAHOO'][0][0][0]) && is_array($this->valor['X-YAHOO'])) $linhas[] = $this->getYahoo();
      if (isset($this->valor['X-MSN'][0][0][0]) && is_array($this->valor['X-MSN'])) $linhas[] = $this->getMSN();
      if (isset($this->valor['X-ICQ'][0][0][0]) && is_array($this->valor['X-ICQ'])) $linhas[] = $this->getICQ();
      if (isset($this->valor['X-JABBER'][0][0][0]) && is_array($this->valor['X-JABBER'])) $linhas[] = $this->getJabber();
      if (isset($this->valor['X-SKYPE-USERNAME'][0][0][0]) && is_array($this->valor['X-SKYPE-USERNAME'])) $linhas[] = $this->getSkype();
      if (isset($this->valor['CLASS'][0][0][0]) && is_array($this->valor['CLASS']) && $this->valor['VERSION'][0][0][0] == '3.0') $linhas[] = $this->getClass();
      if (isset($this->valor['KEY'][0][0][0]) && is_array($this->valor['KEY'])) $linhas[] = $this->getKey();
      $linhas[] = "END:VCARD";
      $novalinha = "\n";
      if (isset($this->valor['VERSION'][0][0][0]) && $this->valor['VERSION'][0][0][0] == '2.1') $novalinha = "\r\n";
      $regex = "(.{1,80})";
      foreach ($linhas as $chave => $val) {
      	$val=trim(preg_replace("/<br>/i", "\\1$novalinha", $val));
      	$linhas[$chave] =$val;
    		}
    	return implode($novalinha, $linhas);
  		}

  function _construir_vcard(){
  	return true;
  	}
	}

?>
