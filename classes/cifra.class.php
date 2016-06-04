<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\classes\cifra.class.php		

Define a classe de cifra que possibilita encriptar uma mensagem de acordo com a senha																			
																																												
********************************************************************************************/
class cifra {
	var $senha;


	
	function set_key($senha) {
		$this->senha = $senha;
		}
	
	function get_rnd_iv($iv_len) {
		$iv = '';
		while ($iv_len-- > 0) $iv .= chr(mt_rand() & 0xff);
		return $iv;
		}
	
	function encriptar($texto_claro, $iv_len = 16) {
		$texto_claro .= "\x13";
		$n = strlen($texto_claro);
		if ($n % 16) $texto_claro .= str_repeat("\0", 16 - ($n % 16));
			$i = 0;
			$texto_cripto = cifra::get_rnd_iv($iv_len);
			$iv = substr($this->senha ^ $texto_cripto, 0, 512);
			while ($i < $n) {
				$bloco = substr($texto_claro, $i, 16) ^ pack('H*', sha1($iv));
				$texto_cripto .= $bloco;
				$iv = substr($bloco.$iv, 0, 512) ^ $this->senha;
				$i += 16;
				}
		return base64_encode($texto_cripto);
		}
	
	function decriptar($texto_cripto, $iv_len = 16) {
		$texto_cripto = base64_decode($texto_cripto);
		$n = strlen($texto_cripto);
		$i = $iv_len;
		$texto_claro = '';
		$iv = substr($this->senha ^ substr($texto_cripto, 0, $iv_len), 0, 512);
		while ($i < $n) {
			$bloco = substr($texto_cripto, $i, 16);
			$texto_claro .= $bloco ^ pack('H*', sha1($iv));
			$iv = substr($bloco.$iv, 0, 512) ^ $this->senha;
			$i += 16;
			}
		return stripslashes(preg_replace('/\\x13\\x00*$/', '', $texto_claro));
		}
	}
?>