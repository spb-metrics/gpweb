<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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