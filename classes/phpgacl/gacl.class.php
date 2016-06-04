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

if( !defined('ADODB_DIR') ) define('ADODB_DIR',dirname(__FILE__).'/../adodb');

class gacl{

 var $_debug=FALSE;
 var $_bd_tabela_prefixo= 'gacl_';
 var $_bd_tipo= 'mysql';
 var $_bd_hospegadem= 'localhost';
 var $_bd_usuario= '';
 var $_bd_senha= '';
 var $_db_nome= 'gacl';
 var $_db= '';
 var $_caching=FALSE;
 var $_forcar_expirar_cache=TRUE;
 var $_cache_diretorio= '/tmp/phpgacl_cache'; 
 var $_cache_tempo_expirar=600;
 var $_grupo_troca= '_grupo_';
 var $arquivo_configuracao=NULL;

 function gacl($opcoes=NULL) {
	 $opcoes_disponiveis=array('db','debug','items_per_page','max_select_box_items','max_search_return_items','db_table_prefix','db_type','db_host','db_user','db_password','db_nome','caching','force_cache_expire','cache_dir','cache_expire_time');
	 if(file_exists($this->arquivo_configuracao) ) {
		 $config=parse_ini_file($this->arquivo_configuracao);
		 if(is_array($config) ) $gacl_options=array_merge($config, $opcoes);
		 unset($config);
		 }
	 if(is_array($opcoes)) {
		 foreach($opcoes AS $chave=> $valor) {
			 $this->texto_depanagem("Option: $chave");
			 if(in_array($chave, $opcoes_disponiveis) ) {
				 $this->texto_depanagem("Valid Config options: $chave");
				 $property= '_'.$chave;
				 $this->$property= $valor;
				 }
			 else $this->texto_depanagem("ERROR: Config option: $chave is not a valid option");
			 }
		 }
	 require_once(ADODB_DIR.'/adodb.inc.php');
	 require_once(ADODB_DIR.'/adodb-pager.inc.php');
	 $ADODB_FETCH_MODE=ADODB_FETCH_NUM;
	 if(is_object($this->_db)) $this->db= &$this->_db;
	 else{
		 $this->db=ADONewConnection($this->_bd_tipo);
		 $this->db->SetFetchMode(ADODB_FETCH_NUM);
		 $this->db->PConnect($this->_bd_hospegadem, $this->_bd_usuario, $this->_bd_senha, $this->_db_nome);
		 }
	 $this->db->debug= $this->_debug;
	 if( $this->_caching==TRUE) {
		 if(!class_exists('Hashed_Cache_Lite')) require_once(dirname(__FILE__) .'/Cache_Lite/Hashed_Cache_Lite.php');
		 $cache_options=array( 'caching'=> $this->_caching, 'cacheDir'=> $this->_cache_diretorio.'/', 'lifeTime'=> $this->_cache_tempo_expirar, 'fileLocking'=>TRUE, 'writeControl'=>FALSE, 'readControl'=>FALSE, 'memoryCaching'=>TRUE, 'automaticSerialization'=>FALSE);
		 $this->Cache_Lite=new Hashed_Cache_Lite($cache_options);
		 }
	 return true;
	 }

 function texto_depanagem($text) {
	 if($this->_debug) echo"$text<br>\n";
	 return true;
	 }

  function debug_db($nome_funcao= '') {
	 if($nome_funcao!= '') $nome_funcao.= ' (): ';
	 return $this->texto_depanagem($nome_funcao.'database error: '. $this->db->ErrorMsg() .' ('. $this->db->ErrorNo() .')');
	 }

 function acl_checar($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor, $axo_valor_secao=NULL, $axo_valor=NULL, $raiz_aro_grupo=NULL, $raiz_axo_grupo=NULL) {
	 $acl_result= $this->acl_sql($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor, $axo_valor_secao, $axo_valor, $raiz_aro_grupo, $raiz_axo_grupo);
	 if($acl_result) return $acl_result['permitir'];
	 else return false;
	 }

 function acl_valor_retorno($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor, $axo_valor_secao=NULL, $axo_valor=NULL, $raiz_aro_grupo=NULL, $raiz_axo_grupo=NULL) {
	 $acl_result= $this->acl_sql($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor, $axo_valor_secao, $axo_valor, $raiz_aro_grupo, $raiz_axo_grupo);
	 return $acl_result['valor_retorno'];
	 }

 function acl_checar_array($aco_valor_secao, $aco_valor, $vetor_aro) {
	 if(!is_array($vetor_aro)) {
		 $this->texto_depanagem("acl_sql_array(): ARO Array must be passed");
		 return false;
		 }
	 foreach($vetor_aro AS $aro_valor_secao=> $vetor_aro_valor) {
		 foreach($vetor_aro_valor AS $aro_valor) {
			 $this->texto_depanagem("acl_sql_array(): ARO Valor da Seção: $aro_valor_secao ARO Valor: $aro_valor");
			 if( $this->acl_checar($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor) ) {
				 $this->texto_depanagem("acl_sql_array(): ACL_CHECK True");
				 $retornar[$aro_valor_secao][] = $aro_valor;
				 }
			 else $this->texto_depanagem("acl_sql_array(): ACL_CHECK False");
			 }
		 }
	 return $retornar;
	 }

 function acl_sql($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor, $axo_valor_secao=NULL, $axo_valor=NULL, $raiz_aro_grupo=NULL, $raiz_axo_grupo=NULL, $debug=NULL) {
	 $cache_id= 'acl_sql_'.$aco_valor_secao.'-'.$aco_valor.'-'.$aro_valor_secao.'-'.$aro_valor.'-'.$axo_valor_secao.'-'.$axo_valor.'-'.$raiz_aro_grupo.'-'.$raiz_axo_grupo.'-'.$debug;
	 $retornar= $this->get_cache($cache_id);
	 if(!$retornar) {
		 $aro_grupo_ids= $this->acl_get_grupos($aro_valor_secao, $aro_valor, $raiz_aro_grupo, 'ARO');
		 if(is_array($aro_grupo_ids) && !empty($aro_grupo_ids)) $sql_aro_grupo_ids=implode(',', $aro_grupo_ids);
		 if($axo_valor_secao!= '' && $axo_valor!= '') {
			 $axo_grupo_ids= $this->acl_get_grupos($axo_valor_secao, $axo_valor, $raiz_axo_grupo, 'AXO');
			 if(is_array($axo_grupo_ids) && !empty($axo_grupo_ids)) $sql_axo_grupo_ids=implode(',', $axo_grupo_ids);
			 }
		 $ordem_by=array();
		 $comando_sql= 'SELECT	a.id,a.permitir,a.valor_retorno	FROM '. $this->_bd_tabela_prefixo.'acl a	LEFT JOIN '. $this->_bd_tabela_prefixo.'aco_mapa ac ON ac.acl_id=a.id';
		 if($aro_valor_secao!= $this->_grupo_troca) $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'aro_mapa ar ON ar.acl_id=a.id';
		 if($axo_valor_secao!= $this->_grupo_troca) $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'axo_mapa ax ON ax.acl_id=a.id';
		 if(isset($sql_aro_grupo_ids)) $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'aro_grupos_mapa arg ON arg.acl_id=a.id	LEFT JOIN	'. $this->_bd_tabela_prefixo.'aro_grupos rg ON rg.id=arg.grupo_id';
		 $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'axo_grupos_mapa axg ON axg.acl_id=a.id';
		 if(isset($sql_axo_grupo_ids)) $comando_sql.= ' LEFT JOIN	'. $this->_bd_tabela_prefixo.'axo_grupos xg ON xg.id=axg.grupo_id';
		 $comando_sql.= ' WHERE	a.habilitado=1	AND	(ac.valor_secao='. $this->db->quote($aco_valor_secao) .' AND ac.valor='. $this->db->quote($aco_valor) .')';
		 if($aro_valor_secao== $this->_grupo_troca) {
			 if( !isset($sql_aro_grupo_ids) ) {
				 $this->texto_depanagem('acl_sql(): Invalid ARO Group: '. $aro_valor);
				 return FALSE;
				 }
			 $comando_sql.= '	AND		rg.id IN ('. $sql_aro_grupo_ids.')';
			 $ordem_by[] = '(rg.dir-rg.esq) ASC';
			 }
		 else{
			 $comando_sql.= '	AND	((ar.valor_secao='. $this->db->quote($aro_valor_secao) .' AND ar.valor='. $this->db->quote($aro_valor) .')';
			 if(isset($sql_aro_grupo_ids) ) {
				 $comando_sql.= ' OR rg.id IN ('. $sql_aro_grupo_ids.')';
				 $ordem_by[] = '(CASE WHEN ar.valor IS NULL THEN 0 ELSE 1 END) DESC';
				 $ordem_by[] = '(rg.dir-rg.esq) ASC';
				 }
			 $comando_sql.= ')';
			 }
		 if($axo_valor_secao== $this->_grupo_troca) {
			 if( !isset($sql_axo_grupo_ids) ) {
				 $this->texto_depanagem('acl_sql(): Invalid AXO Group: '. $axo_valor);
				 return FALSE;
				 }
			 $comando_sql.= '	AND	xg.id IN ('. $sql_axo_grupo_ids.')';
			 $ordem_by[] = '(xg.dir-xg.esq) ASC';
			 }
		 else{
			 $comando_sql.= '	AND	(';
			 if($axo_valor_secao== '' && $axo_valor== '') $comando_sql.= '(ax.valor_secao IS NULL AND ax.valor IS NULL)';
			 else $comando_sql.= '(ax.valor_secao='. $this->db->quote($axo_valor_secao) .' AND ax.valor='. $this->db->quote($axo_valor) .')';
			 if(isset($sql_axo_grupo_ids)) {
				 $comando_sql.= ' OR xg.id IN ('. $sql_axo_grupo_ids.')';
				 $ordem_by[] = '(CASE WHEN ax.valor IS NULL THEN 0 ELSE 1 END) DESC';
				 $ordem_by[] = '(xg.dir-xg.esq) ASC';
				 }
			 else $comando_sql.= ' AND axg.grupo_id IS NULL';
			 $comando_sql.= ')';
			 }
		 $ordem_by[] = 'a.data_atualizacao DESC';
		 $comando_sql.= '	ORDER BY	'.implode(',', $ordem_by).'	';
		 $rs= $this->db->SelectLimit($comando_sql,1);
		 if(!is_object($rs)) {
			 $this->debug_db('acl_sql');
			 return FALSE;
			 }
		 $linha=& $rs->FetchRow();
		 if(is_array($linha)) {
			 if(isset($linha[1]) AND $linha[1] ==1) $permitir=TRUE;
			 else $permitir=FALSE;
			 $retornar=array('acl_id'=> &$linha[0], 'valor_retorno'=> &$linha[2], 'permitir'=> $permitir);
			 }
		 else $retornar=array('acl_id'=>NULL, 'valor_retorno'=>NULL, 'permitir'=>FALSE);
		 if($debug==TRUE) $retornar['query'] = &$comando_sql;
		 $this->put_cache($retornar, $cache_id);
		 }
	 $this->texto_depanagem("<b>acl_sql():</b> ACO Seção: $aco_valor_secao ACO Valor: $aco_valor ARO Seção: $aro_valor_secao ARO Value $aro_valor ACL ID: ". $retornar['acl_id'] .' Result: '. $retornar['permitir']);
	 return $retornar;
	 }

 function acl_get_grupos($secao_valor, $valor, $raiz_grupo=NULL, $grupo_tipo='ARO') {
	 switch(strtolower($grupo_tipo)) {
		 case'axo':
		 $grupo_tipo= 'axo';
		 $objeto_tabela= $this->_bd_tabela_prefixo.'axo';
		 $tabela_grupo= $this->_bd_tabela_prefixo.'axo_grupos';
		 $grupo_mapa_table= $this->_bd_tabela_prefixo.'grupos_axo_mapa';
		 break;
		 default:
		 $grupo_tipo= 'aro';
		 $objeto_tabela= $this->_bd_tabela_prefixo.'aro';
		 $tabela_grupo= $this->_bd_tabela_prefixo.'aro_grupos';
		 $grupo_mapa_table= $this->_bd_tabela_prefixo.'grupos_aro_mapa';
		 break;
		 }
	 $cache_id= 'acl_get_grupos_'.$secao_valor.'-'.$valor.'-'.$raiz_grupo.'-'.$grupo_tipo;
	 $retornar= $this->get_cache($cache_id);
	 if(!$retornar) {
		 $comando_sql= 'SELECT DISTINCT g2.id';
		 if($secao_valor== $this->_grupo_troca) {
			 $comando_sql.= '	FROM '. $tabela_grupo. ' g1,'. $tabela_grupo. ' g2';
			 $onde= ' WHERE g1.valor='. $this->db->quote( $valor);
			 }
		 else{
			 $comando_sql.= '	FROM '. $objeto_tabela.' o,'. $grupo_mapa_table.' gm,'. $tabela_grupo.' g1,'. $tabela_grupo.' g2';
			 $onde= '	WHERE		(o.valor_secao='. $this->db->quote($secao_valor) .' AND o.valor='. $this->db->quote($valor) .')	AND		gm.'. $grupo_tipo.'_id=o.id	AND	g1.id=gm.grupo_id';
			 }
		 
		 if( $raiz_grupo!= '') {
			 $comando_sql.= ','. $tabela_grupo.' g3';
			 $onde.= '	AND		g3.valor='. $this->db->quote( $raiz_grupo) .'	AND	((g2.esq BETWEEN g3.esq AND g1.esq) AND (g2.dir BETWEEN g1.dir AND g3.dir))';
			 }
		 else $onde.= '	AND		(g2.esq <= g1.esq AND g2.dir >= g1.dir)';
		 $comando_sql.= $onde;
		 $rs= $this->db->Execute($comando_sql);
		 if(!is_object($rs)) {
		 $this->debug_db('acl_get_grupos');
		 return FALSE;
		 }
		 $retornar=array();
		 while(!$rs->EOF) {
		 $retornar[] =reset($rs->fields);
		 $rs->MoveNext();
		 }
		 $this->put_cache($retornar, $cache_id);
		 }
	 return $retornar;
	 }

 function get_cache($cache_id){
	 if( $this->_caching==TRUE){
		 $this->texto_depanagem("get_cache(): on ID: $cache_id");
		 if(is_string($this->Cache_Lite->get($cache_id))) return unserialize($this->Cache_Lite->get($cache_id));
		 }
	 return false;
	 }

 function put_cache($data, $cache_id) {
	 if( $this->_caching==TRUE) {
		 $this->texto_depanagem("put_cache(): Cache MISS on ID: $cache_id");
		 return $this->Cache_Lite->save(serialize($data), $cache_id);
		 }
	 return false;
	 }
}
?>
