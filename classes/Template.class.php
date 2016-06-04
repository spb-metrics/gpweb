<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\classes\Template.class.php		

Define a classe de Template que auxilia a classe Modelo na criação e edição de documentos
internos, tais como parte, MDO, etc.																		
																																												
********************************************************************************************/

class Template {
	
	private $vars = array();
	private $valores = array();
	private $propriedades = array();
	private $instancias = array();
	private $blocos = array();
	private $superiores = array();
	private $acurado;
	private static $REG_NAME = "([[:alnum:]]|_)+";
	

	public function __construct($html, $acurado = false, $imagem, $email=false){
		$this->acurado = $acurado;
		$this->carregarArquivo(".", $html, $imagem, $email);
	}

	public function adicionarArquivo($nomeVariavel, $nome_arquivo){
		if(!in_array($nomeVariavel, $this->vars)) throw new InvalidArgumentException("adicionarArquivo: variavel $nomeVariavel não existe");
		$this->carregarArquivo($nomeVariavel, $nome_arquivo);
	}
	

	public function __set($nomeVariavel, $valor){
		if(in_array($nomeVariavel, $this->vars)){
			$textoValor = $valor;
			if(is_object($valor)){
				$this->instancias[$nomeVariavel] = $valor;
				if(!array_key_exists($nomeVariavel, $this->propriedades)) $this->propriedades[$nomeVariavel] = array();
				if(method_exists($valor, "__toString")) $textoValor = $valor->__toString();
				else $textoValor = "Object";
			} 
			$this->setValor($nomeVariavel, $textoValor);
			return $valor;
			}
	}

	public function __get($nomeVariavel){
		if (isset($this->valores["{".$nomeVariavel."}"])) return $this->valores["{".$nomeVariavel."}"];
		throw new RuntimeException("var $nomeVariavel não existe");
	}


	private function carregarArquivo($nomeVariavel, $html, $imagem='', $email=false) {
		global $Aplic;

		$str = preg_replace("/<!---.*?--->/smi", "", $html);
		if ($imagem) $str = str_ireplace('src="imagens/', 'src="./modulos/email/modelos/'.$imagem.'/imagens/', $str);
		if ($email) $str = str_ireplace('<img src="./modulos/email/modelos/'.$imagem.'/imagens/brasao_republica.gif" alt="" border=0 />', '', $str);
		$str = str_ireplace('src="./modulos/email/modelos/'.$imagem.'/imagens/brasao_republica.gif', 'src="'.$Aplic->gpweb_brasao, $str);
		$str = str_ireplace('campo', 'campo_', $str);
		
		$blocos = $this->reconhecer($str, $nomeVariavel);
		if (empty($str)) throw new InvalidArgumentException("arquivo está vazio");
		$this->setValor($nomeVariavel, $str);
		$this->criarBloco($blocos);
	}
	

	private function reconhecer(&$content, $nomeVariavel){
		$blocos = array();
		$blocos_em_espera = array();
		foreach (explode("\n", $content) as $linha) {
			if (strpos($linha, "{")!==false) $this->indentificarVariaveis($linha);
			if (strpos($linha, "<!--")!==false) $this->indentificarBlocos($linha, $nomeVariavel, $blocos_em_espera, $blocos);
		}
		return $blocos;
	}


	private function indentificarBlocos(&$linha, $nomeVariavel, &$blocos_em_espera, &$blocos){
		$reg = "/<!--\s*BEGIN\s+(".self::$REG_NAME.")\s*-->/sm";
		preg_match($reg, $linha, $m);
		if (1==preg_match($reg, $linha, $m)){
			if (0==sizeof($blocos_em_espera)) $pai = $nomeVariavel;
			else $pai = end($blocos_em_espera);
			if (!isset($blocos[$pai])){
				$blocos[$pai] = array();
			}
			$blocos[$pai][] = $m[1];
			$blocos_em_espera[] = $m[1];
		}
		$reg = "/<!--\s*END\s+(".self::$REG_NAME.")\s*-->/sm";
		if (1==preg_match($reg, $linha)) array_pop($blocos_em_espera);
	}
	

	private function indentificarVariaveis(&$linha){
		$r = preg_match_all("/{(".self::$REG_NAME.")((\-\>(".self::$REG_NAME."))*)?}/", $linha, $m);
		if ($r){
			for($i=0; $i<$r; $i++){
				// Object var detected
				if($m[3][$i] && (!array_key_exists($m[1][$i], $this->propriedades) || !in_array($m[3][$i], $this->propriedades[$m[1][$i]]))){
					$this->propriedades[$m[1][$i]][] = $m[3][$i];
				}
				if(!in_array($m[1][$i], $this->vars)) $this->vars[] = $m[1][$i];
			}
		}
	}
	

	private function criarBloco(&$blocos) {
		$this->superiores = array_merge($this->superiores, $blocos);
		foreach($blocos as $pai => $bloco){
			foreach($bloco as $filho){
				if(in_array($filho, $this->blocos)) throw new UnexpectedValueException("bloco duplicado: $filho");
				$this->blocos[] = $filho;
				$this->setBloco($pai, $filho);
			}
		}
	}
	

	private function setBloco($pai, $bloco) {
		$name = "B_".$bloco;
		$str = $this->getVar($pai);
		if($this->acurado){
			$str = str_replace("\r\n", "\n", $str);
			$reg = "/\t*<!--\s*BEGIN\s+$bloco\s+-->\n*(\s*.*?\n?)\t*<!--\s+END\s+$bloco\s*-->\n?/sm";
		} 
		else $reg = "/<!--\s*BEGIN\s+$bloco\s+-->\s*(\s*.*?\s*)<!--\s+END\s+$bloco\s*-->\s*/sm";
		if(1!==preg_match($reg, $str, $m)) throw new UnexpectedValueException("bloco $bloco está mal formado");
		$this->setValor($name, '');
		$this->setValor($bloco, $m[1]);
		$this->setValor($pai, preg_replace($reg, "{".$name."}", $str));
	}


	private function setValor($nomeVariavel, $valor) {
		$this->valores["{".$nomeVariavel."}"] = $valor;
	}
	

	private function getVar($nomeVariavel) {
		return $this->valores['{'.$nomeVariavel.'}'];
	}
	

	public function limpar($nomeVariavel) {
		$this->setValor($nomeVariavel, "");
	}
	

	private function subst($nomeVariavel) {
		$s = $this->getVar($nomeVariavel);

		$s = str_replace(array_keys($this->valores), $this->valores, $s);

		foreach($this->instancias as $var => $instance){
			foreach($this->propriedades[$var] as $propriedades){
				if(false!==strpos($s, "{".$var.$propriedades."}")){
					$ponteiro = $instance;
					$propriedade = explode("->", $propriedades);
					for($i = 1; $i < sizeof($propriedade); $i++){
						$obj = str_replace('_', '', $propriedade[$i]);

						if(method_exists($ponteiro, "get$obj")){
							$ponteiro = $ponteiro->{"get$obj"}();
						}

						elseif(method_exists($ponteiro, "is$obj")){
							$ponteiro = $ponteiro->{"is$obj"}();
						}

						elseif(method_exists($ponteiro, "__get")){
							$ponteiro = $ponteiro->__get($propriedade[$i]);
						}

						else {
							$nomeClasse = $propriedade[$i-1] ? $propriedade[$i-1] : get_class($instance);
							$class = is_null($ponteiro) ? "NULL" : get_class($ponteiro);
							throw new BadMethodCallException("não existe método na classe ".$class." para acessar ".$nomeClasse."->".$propriedade[$i]);
						}
					}
					if(is_object($ponteiro)){
						if(method_exists($ponteiro, "__toString")){
							$ponteiro = $ponteiro->__toString();
						} else {
							$ponteiro = "Object";
						}
					}
					$s = str_replace("{".$var.$propriedades."}", $ponteiro, $s);
				}
			}
		}
		return $s;
	}
	

	private function limparBlocos($bloco) {
		if (isset($this->superiores[$bloco])){
			$filhos = $this->superiores[$bloco];
			foreach($filhos as $filho){
				$this->limpar("B_".$filho);
			}
		}
	}

	public function bloco($bloco, $incluir = true) {
		if(!in_array($bloco, $this->blocos)) throw new InvalidArgumentException("bloco $bloco não existe");
		if ($incluir) $this->setValor("B_".$bloco, $this->getVar("B_".$bloco).$this->subst($bloco));
		else $this->setValor("B_".$bloco, $this->subst($bloco));
		$this->limparBlocos($bloco);
	}
	
	public function lista_blocos(){
		return $this->blocos;
		}

	public function parse() {
		return preg_replace("/{(".self::$REG_NAME.")((\-\>(".self::$REG_NAME."))*)?}/", "", $this->subst("."));
	}

	public function exibir($edicao=0) {
		$saida=$this->parse();
		if ($edicao) $saida=str_replace('text-indent: 104px;','', $saida);
		return $saida;
	}
		
}
?>