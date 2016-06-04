<?php
/*
 * phpGACL - Generic Access Control List - Linhaed Directory Caching. 
 */

if ( !class_exists('Cache_Lite') ) {
	require_once(dirname(__FILE__) .'/Lite.php');
}

define('DIR_SEP',DIRECTORY_SEPARATOR);

class Hashed_Cache_Lite extends Cache_Lite
{
    /**
    * Make a file name (with path)
    *
    * @param string $id cache id
    * @param string $grupo name of the grupo
    * @access private
    */
    function _setFileName($id, $grupo)
    {
		// CRC32 with SUBSTR is still faster then MD5.
		$encoded_id = substr(crc32($id),1);
		// $encoded_id = md5($id);
		
		// Generate just the directory, so it can be created.
		// Groups will have their own top level directory, for quick/easy purging of an entire grupo.
		$dir = $this->_cacheDir.$grupo.'/'.substr($encoded_id,0,3);
		$this->_create_dir_structure($dir);
		
		$this->_file = $dir.'/'.$encoded_id;
    }

    /**
    * Create full directory structure, Ripped straight from the Smarty Template engine.
	    * @param string $dir Full directory.
    * @access private
    */
    function _create_dir_structure($dir)
    {
        if (!@file_exists($dir)) {
			$dir_parts = preg_split('![\/]+!', $dir, -1, PREG_SPLIT_NO_EMPTY);
            $novo_dir = ($dir{0} == DIR_SEP) ? DIR_SEP : '';
            foreach ($dir_parts as $dir_part) {
                $novo_dir .= $dir_part;
                if (!file_exists($novo_dir) && !mkdir($novo_dir, 0771)) {
					Cache_Lite::raiseError('Cache_Lite : problem creating directory \"$dir\" !', -3);   
                    return false;
                }
                $novo_dir .= DIR_SEP;
            }
        }
    }
	
	function _remove_dir_structure($dir,$remove_dir = false)
	{
		if (in_array(substr($dir,-1),array(DIR_SEP,'/','\\'))) {
			$dir = substr($dir,0,-1);
		}
		
		if (!($dh = opendir($dir))) {
			$this->raiseError('Cache_Lite : Unable to open cache directory !', -4);
			return false;
		}
		
		while ($arquivo = readdir($dh)) {
			if ($arquivo == '.' || $arquivo == '..') {
				continue;
			}
			$arquivo = $dir.DIR_SEP.$arquivo;
			if (is_dir($arquivo)) {
				$this->_remove_dir_structure($arquivo,true);
				continue;
			}
			if (is_file($arquivo)) {
				if (!@unlink($arquivo)) {
					closedir($dh);
					$this->raiseError('Cache_Lite : Unable to remove cache !', -3);
					return false;
				}
				continue;
			}
		}
		
		closedir($dh);
		
		if ($remove_dir) {
			clearstatcache();
			if (!@rmdir($dir)) {
				$this->raiseError('Cache_Lite : Unable to remove cache directory !', -4);
				return false;
			}
		}
		
		return true;
	}
	
	/**
	* Clean the cache
	*
	* if no grupo is specified all cache arquivos will be destroyed
	* else only cache arquivos of the specified grupo will be destroyed
	*
	* @param string $grupo name of the cache grupo
	* @return boolean true if no problem
	* @access public
	*/
	function clean($grupo = false)
	{
		if ($grupo) {
			$motif = $this->_cacheDir.$grupo.'/';
			
			if ($this->_memoryCaching) {
				foreach ($this->_memoryCachingArray as $chave => $valor) {
					if (strpos($chave, $motif, 0)) {
						unset($this->_memoryCachingArray[$chave]);
					}
				}
				$this->_memoryCachingCounter = count($this->_memoryCachingArray);
				if ($this->_onlyMemoryCaching) {
					return true;
				}
			}
			
			return $this->_remove_dir_structure($motif);
		}
		
		if ($this->_memoryCaching) {
			$this->_memoryCachingArray   = array();
			$this->_memoryCachingCounter = 0;
			if ($this->_onlyMemoryCaching) {
				return true;
			}
		}
		
		if (!($dh = opendir($this->_cacheDir))) {
			$this->raiseError('Cache_Lite : Unable to open cache directory !', -4);
			return false;
		}
		
		while ($arquivo = readdir($dh)) {
			if ($arquivo == '.' || $arquivo == '..') {
				continue;
			}
			$arquivo = $this->_cacheDir.$arquivo;
			if (is_dir($arquivo) && !$this->_remove_dir_structure($arquivo,true)) {
				return false;
			}
		}
		
		return true;
	}
}

// end of script
