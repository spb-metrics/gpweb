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

gpweb\classes\CampoCustomizados.class.php

Define a classe de CampoCustomizado que possibilita acrescentar novos campos nos diversos
formulários do sistema

********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class CampoCustomizado {
	var $campo_id;
	var $campo_ordem;
	var $campo_nome;
	var $campo_descricao;
	var $campo_formula;
	var $campo_tipo_html;
	var $campo_publicado;
	var $campo_tipo_dado;
	var $campo_tags_extras;
	var $objeto_id = null;
	var $valor_id = 0;
	var $valor_caractere;
	var $valor_inteiro;
	var $estilo;

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		$this->campo_id = $campo_id;
		$this->campo_nome = $campo_nome;
		$this->campo_ordem = $campo_ordem;
		$this->campo_descricao = $campo_descricao;
		$this->campo_formula = $campo_formula;
		$this->campo_tags_extras = $campo_tags_extras;
		$this->campo_publicado = $campo_publicado;
		}

	function load($objeto_id) {
		global $bd;
		$sql = new BDConsulta;
		$sql->adTabela('campos_customizados_valores');
		$sql->adOnde('valor_campo_id = '.$this->campo_id);
		$sql->adOnde('valor_objeto_id = '.$objeto_id);
		$rs = $sql->exec();
		$linha = $sql->carregarLinha();
		$sql->limpar();
		$valor_id = $linha['valor_id'];
		$valor_caractere = $linha['valor_caractere'];
		$valor_inteiro = $linha['valor_inteiro'];
		if ($valor_id != null) {
			$this->valor_id = $valor_id;
			$this->valor_caractere = $valor_caractere;
			$this->valor_inteiro = $valor_inteiro;
			}
		}

	function armazenar($objeto_id) {
		global $bd;
		if ($objeto_id == null) return 'Erro: Não foi possível armazenar o campo ('.$this->campo_nome.'), id associado não foi suprido.';
		else {
			$ins_valorInteiro = $this->valor_inteiro == null ? '0' : $this->valor_inteiro;
			$ins_valorCaractere = $this->valor_caractere == null ? '' : stripslashes($this->valor_caractere);
			$sql = new BDConsulta;


			//processar valores
			if ($this->campo_tipo_html=='valor'){
				$ins_valorCaractere=float_americano($ins_valorCaractere);
				}
			else if ($this->campo_tipo_html=='formula'){
				$ins_valorCaractere=null;
				}
            else if ($this->campo_tipo_html=='data'){
                if($ins_valorCaractere && strlen($ins_valorCaractere) == 10){
                    $d = substr($ins_valorCaractere, 0, 2);
                    $m = substr($ins_valorCaractere, 3, 2);
                    $y = substr($ins_valorCaractere, 6);
                    $ins_valorCaractere = $y.'-'.$m.'-'.$d;
                    }
                else{
                    $ins_valorCaractere = '';
                    }
                }

			if ($this->valor_id > 0) {
				$sql->adTabela('campos_customizados_valores');
				$sql->adAtualizar('valor_caractere', $ins_valorCaractere);
				$sql->adAtualizar('valor_inteiro', $ins_valorInteiro);
				$sql->adOnde('valor_id = '.$this->valor_id);
				}
			else {
				$sql->adTabela('campos_customizados_valores');
				$sql->adCampo('MAX(valor_id)');
				$max_id = $sql->Resultado();
				$sql->limpar();

				$novo_valor_id = $max_id ? $max_id + 1 : 1;

				$sql->adTabela('campos_customizados_valores');
				$sql->adInserir('valor_id', $novo_valor_id);
				$sql->adInserir('valor_modulo', '');
				$sql->adInserir('valor_campo_id', $this->campo_id);
				$sql->adInserir('valor_objeto_id', $objeto_id);
				$sql->adInserir('valor_caractere', $ins_valorCaractere);
				$sql->adInserir('valor_inteiro', $ins_valorInteiro);
				}
			$rs = $sql->exec();
			$sql->limpar();
			if (!$rs) return $bd->ErrorMsg().' | SQL: ';
			}
		}

	function setValorInt($v) {
		$this->valor_inteiro = $v;
		}

	function intValor() {
		return $this->valor_inteiro;
		}

	function setValor($v) {
		$this->valor_caractere = $v;
		}

	function valor() {
		return $this->valor_caractere;
		}

	function valorCaractere() {
		return $this->valor_caractere;
		}

	function setValorId($v) {
		$this->valor_id = $v;
		}

	function valorId() {
		return $this->valor_id;
		}

	function campoNome() {
		return $this->campo_nome;
		}

	function campoDescricao() {
		return $this->campo_descricao;
		}

	function campoFormula() {
		return $this->campo_formula;
		}

	function campoId() {
		return $this->campo_id;
		}

	function campoTipoHtml() {
		return $this->campo_tipo_html;
		}

	function campoTagExtra() {
		return $this->campo_tags_extras;
		}

	function campoOrdem() {
		return $this->campo_ordem;
		}

	function campoPublicado() {
		return $this->campo_publicado;
		}
	}

class CampoCustomizadoCaixaMarcar extends CampoCustomizado {

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'checkbox';
		}

	function getHTML($modo) {
		$html='';
		switch ($modo) {
			case 'editar':
				$bool_tag = ($this->intValor() ? 'checked="checked"': '');
				if ($this->campo_descricao) $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td><input type="checkbox" name="'.$this->campo_nome.'" value="1" '.$bool_tag.$this->campo_tags_extras.'/></td></tr>';
				break;
			case 'ver':
				if ($this->campo_descricao) $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.($this->intValor() ? 'Sim': 'Não').'</td></tr>';
				break;
			}
		return $html;
		}

	function setValor($v) {
		$this->valor_inteiro = $v;
		}
	}

class CampoCustomizadoTexto extends CampoCustomizado {

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'textinput';
		}

	function getHTML($modo) {
		$html ='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td><input type="text" class="texto" name="'.$this->campo_nome.'" value="'.$this->valorCaractere().'" '.($this->campo_tags_extras ? $this->campo_tags_extras : 'size="25"').' /></td></tr>';
				break;
			case 'ver':
				if ($this->valorCaractere()) $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.$this->valorCaractere();
				break;
			}
		return $html;
		}
	}

class CampoCustomizadoAreaTexto extends CampoCustomizado {

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'textarea';
		}

	function getHTML($modo) {
		$html ='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td><textarea data-gpweb-cmp="ckeditor" name="'.$this->campo_nome.'" '.($this->campo_tags_extras ? $this->campo_tags_extras : 'cols="40" rows="4" class="texto"').'>'.$this->valorCaractere().'</textarea></td></tr>';
				break;
			case 'ver':
				if ($this->valorCaractere()) $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.$this->valorCaractere().'</td></tr>';
				break;
			}
		return $html;
		}

	}


class CampoCustomizadoFormula extends CampoCustomizado {

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'formula';
		}

	function getHTML($modo) {
		global $config;
		$html ='';
		switch ($modo) {
			case 'editar':
				//$html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td><input type="text" class="texto" readonly onkeypress="return entradaNumerica(event, this, true, true);" name="'.$this->campo_nome.'" value="'.$this->valorCaractere().'" '.($this->campo_tags_extras ? $this->campo_tags_extras : 'size="25"').' /></td></tr>';
				break;
			case 'ver':
				$sql = new BDConsulta;
				$sql->adTabela('campos_customizados_valores');
				$sql->esqUnir('campos_customizados_estrutura', 'campos_customizados_estrutura', 'valor_campo_id=campo_id');
				$sql->adCampo('campo_id, valor_caractere');
				$sql->adOnde('valor_objeto_id = '.$this->campo_id);
				$sql->adOnde('campo_tipo_html = \'valor\'');
				$variaveis=$sql->listaVetorChave('campo_id','valor_caractere');
				$sql->limpar();

				$formula=$this->campo_formula;

				foreach($variaveis as $campoid => $valor_caractere){
					$chave='I'.($campoid < 10 ? '0' : '').$campoid;
					$formula=str_replace($chave , $valor_caractere, $formula);
					}
				$resultado=$this->calcular_string($formula);

				$html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.$config['simbolo_moeda'].' '.number_format($resultado, 2, ',', '.');
				break;
			}
		return $html;
		}

	function calcular_string($texto){
    $texto = trim($texto);
    $texto=previnirXSS($texto);
    if (!$texto)return 0;

    for($i = 0; $i < strlen($texto); $i++){
    	if (isset($texto[$i]) && $texto[$i]=='I' && isset($texto[$i+1]) && is_int($texto[$i+1]) && isset($texto[$i+2]) &&is_int($texto[$i+2])) {
    		$texto[$i]='0';
    		$texto[$i+1]='.';
    		$texto[$i+2]='0';
    		}
    	}
    $valor=0;
    $computar = @create_function("", "return (".$texto.");");
    $valor=( function_exists($computar) ? @$computar() :  0);
    return 0 + $valor;
		}


	}

class CampoCustomizadoData extends CampoCustomizado {

    function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
        parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
        $this->campo_tipo_html = 'data';
        }

    function getHTML($modo) {
        global $config,$Aplic;
        $html ='';
        switch ($modo) {
            case 'editar':
                $data = $this->valorCaractere();
                if($data){
                    $data = new CData($data);
                    $data = $data->format('%d/%m/%Y');
                    }
                $Aplic->carregarCalendarioJS();
                $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td><input data-gpweb-cmp="calendario" type="text" class="texto" id="'.$this->campo_nome.'" name="'.$this->campo_nome.'" value="'.$data.'" '.($this->campo_tags_extras ? $this->campo_tags_extras : 'size="10"').'/></td></tr>';
                break;
            case 'ver':
                if ($this->valorCaractere()) $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.retorna_data($this->valorCaractere(), false);
                break;
            }
        return $html;
        }

    }

class CampoCustomizadoValor extends CampoCustomizado {

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'valor';
		}

	function getHTML($modo) {
		global $config;
		$html ='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="'.$this->campo_nome.'" value="'.str_replace('.', ',',$this->valorCaractere()).'" '.($this->campo_tags_extras ? $this->campo_tags_extras : 'size="25"').' /></td></tr>';
				break;
			case 'ver':
				if ($this->valorCaractere()) $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.number_format($this->valorCaractere(), 2, ',', '.');
				break;
			}
		return $html;
		}

	}

class CampoCustomizadoLegenda extends CampoCustomizado {

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'label';
		}

	function getHTML($modo) {
		if ($this->campo_descricao) return '<tr><td nowrap="nowrap" align="right"><span '.$this->campo_tags_extras.'>'.$this->campo_descricao.'</span></td></tr>';
		else return '';
		}

	}

class CampoCustomizadoSeparador extends CampoCustomizado {

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'separator';
		}

	function getHTML($modo) {
		return '<tr><td colspan="2"><hr '.$this->campo_tags_extras.' /></td></tr>';
		}

	}

class CampoCustomizadoSelecionar extends CampoCustomizado {
	var $opcoes;

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'selecionar';
		$this->options = new ListaOpcoesCustomizadas($campo_id);
		$this->options->load();
		}

	function getHTML($modo) {
		$html='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td>'.$this->options->getHTML($this->campo_nome, $this->valorCaractere()).'</td></tr>';
				break;
			case 'ver':
				if ($this->valorCaractere()) $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.$this->options->itemNoIndice($this->valorCaractere()).'</td></tr>';
				break;
			}
		return $html;
		}

	}

class CampoCustomizadoLinkWeb extends CampoCustomizado {

	function __construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado) {
		parent::__construct($campo_id, $campo_nome, $campo_ordem, $campo_descricao, $campo_formula, $campo_tags_extras, $campo_publicado);
		$this->campo_tipo_html = 'href';
		}

	function getHTML($modo) {
		$html='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td><input type="text" class="texto" name="'.$this->campo_nome.'" value="'.$this->valorCaractere().'" ' .($this->campo_tags_extras ? $this->campo_tags_extras : 'size="25"'). ' /></td></tr>';
				break;
			case 'ver':
				if(strpos($this->campoTagExtra(),'{'.$this->campoNome().'}') && $this->valorCaractere()) {
					$html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%"><a href="'.str_replace('{'.$this->campoNome().'}', $this->valorCaractere(), $this->campoTagExtra()).'" target="_blank">'.$this->valorCaractere().'</a></td></tr>';
					}
				else if ($this->valorCaractere()) $html = '<tr><td nowrap="nowrap" align="right">'.dica($this->campo_descricao, 'Campo customizado').$this->campo_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%"><a href="'.$this->valorCaractere().'">'.$this->valorCaractere().'</a></td></tr>';
				break;
			}
		return $html;
		}

	}

class CampoCustomizados {
	var $m;
	var $a;
	var $modo;
	var $obj_id;
	var $ordem;
	var $publicado;
	var $campos;

	function __construct($m, $obj_id = null, $modo = 'editar', $publicado = 0) {
		$this->m = $m;
		$this->obj_id = $obj_id;
		$this->modo = $modo;
		$this->publicado = $publicado;
		$sql = new BDConsulta;
		$sql->adTabela('campos_customizados_estrutura');
		$sql->adOnde('campo_modulo = \''.$this->m.'\' AND campo_pagina = \'editar\'');
		if ($publicado)	$sql->adOnde('campo_publicado = 1');
		$sql->adOrdem('campo_ordem ASC');
		$linhas = $sql->Lista();
		if ($linhas == null) {	}
		else {
			foreach ($linhas as $linha) {
				switch ($linha['campo_tipo_html']) {
					case 'checkbox':
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoCaixaMarcar($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
					case 'href':
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoLinkWeb($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
					case 'textarea':
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoAreaTexto($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
					case 'selecionar':
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoSelecionar($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
					case 'label':
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoLegenda($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
					case 'separator':
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoSeparador($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
          case 'data':
            $this->campos[$linha['campo_nome']] = new CampoCustomizadoData($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
            break;
					case 'valor':
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoValor($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
					case 'formula':
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoFormula($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
					default:
						$this->campos[$linha['campo_nome']] = new CampoCustomizadoTexto($linha['campo_id'], $linha['campo_nome'], $linha['campo_ordem'], stripslashes($linha['campo_descricao']), stripslashes($linha['campo_formula']), stripslashes($linha['campo_tags_extras']), $linha['campo_ordem'], $linha['campo_publicado']);
						break;
					}
				}
			if ($obj_id > 0) {
				foreach ($this->campos as $chave => $cCampo) $this->campos[$chave]->load($this->obj_id);
				}
			}
		}

	function adicionar($uuid, $campo_nome, $campo_descricao, $campo_formula, $campo_tipo_html, $campo_tipo_dado, $campo_tags_extras, $campo_ordem, $campo_publicado, &$erro_msg) {
		global $bd, $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela('campos_customizados_estrutura');
		$sql->adCampo('MAX(campo_id)');
		$max_id = $sql->Resultado();
		$sql->limpar();
		$next_id = ($max_id ? $max_id + 1 : 1);
		$campo_ordem = ($campo_ordem ? $campo_ordem : 1);
		$campo_publicado = ($campo_publicado ? 1 : 0);
		$campo_a = 'editar';

		$sql->adTabela('campos_customizados_estrutura');
		$sql->adInserir('campo_id', $next_id);
		$sql->adInserir('campo_modulo', $this->m);
		$sql->adInserir('campo_pagina', $campo_a);
		$sql->adInserir('campo_tipo_html', $campo_tipo_html);
		$sql->adInserir('campo_tipo_dado', $campo_tipo_dado);
		$sql->adInserir('campo_ordem', $campo_ordem);
		$sql->adInserir('campo_nome', $campo_nome);
		$sql->adInserir('campo_descricao', $campo_descricao);
		$sql->adInserir('campo_formula', $campo_formula);
		$sql->adInserir('campo_tags_extras', $campo_tags_extras);
		$sql->adInserir('campo_ordem', $campo_ordem);
		$sql->adInserir('campo_publicado', $campo_publicado);
		if (!$sql->exec()) {
			$erro_msg = $bd->ErrorMsg();
			return 0;
			}

    $sql->limpar();

    if($Aplic->profissional && ($campo_tipo_html == 'data' || $campo_tipo_html == 'selecionar' || $campo_tipo_html == 'textinput' || $campo_tipo_html == 'textarea' || $campo_tipo_html == 'checkbox' || $campo_tipo_html == 'valor')){
      //adiciona como opção para os formulários
      $sql->adTabela('campo_formulario');
      $sql->adInserir('campo_formulario_ativo', '0');
      $sql->adInserir('campo_formulario_campo', $campo_nome.'_ex');
      $sql->adInserir('campo_formulario_tipo', $this->m.'_ex');
      $sql->adInserir('campo_formulario_descricao', $campo_descricao);
      $sql->adInserir('campo_formulario_customizado_id', $next_id);
      $sql->exec();
      $sql->limpar();
      }

    if ($uuid){
    	$sql->adTabela('campo_customizado_lista');
      $sql->adAtualizar('campo_customizado_lista_campo', $next_id);
      $sql->adAtualizar('campo_customizado_lista_uuid', null);
      $sql->adOnde('campo_customizado_lista_uuid=\''.$uuid.'\'');
      $sql->exec();
      $sql->limpar();

    	}


    return $next_id;
		}

	function atualizar($campo_id, $campo_nome, $campo_descricao, $campo_formula, $campo_tipo_html, $campo_tipo_dado, $campo_tags_extras, $campo_ordem, $campo_publicado, &$erro_msg) {
		global $bd, $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela('campos_customizados_estrutura');
		$sql->adAtualizar('campo_nome', $campo_nome);
		$sql->adAtualizar('campo_descricao', $campo_descricao);
		$sql->adAtualizar('campo_formula', $campo_formula);
		$sql->adAtualizar('campo_tipo_html', $campo_tipo_html);
		$sql->adAtualizar('campo_tipo_dado', $campo_tipo_dado);
		$sql->adAtualizar('campo_tags_extras', $campo_tags_extras);
		$sql->adAtualizar('campo_ordem', $campo_ordem);
		$sql->adAtualizar('campo_publicado', $campo_publicado);
		$sql->adOnde('campo_id = '.$campo_id);
		if (!$sql->exec()) {
			$erro_msg = $bd->ErrorMsg();
			return 0;
			}

    $sql->limpar();

    if($Aplic->profissional && ($campo_tipo_html == 'data' || $campo_tipo_html == 'selecionar' || $campo_tipo_html == 'textinput' || $campo_tipo_html == 'textarea' || $campo_tipo_html == 'checkbox' || $campo_tipo_html == 'valor')){
      //adiciona como opção para os formulários
      $sql->adTabela('campo_formulario');
      $sql->adAtualizar('campo_formulario_campo', $campo_nome.'_ex');
      $sql->adAtualizar('campo_formulario_descricao', $campo_descricao);
      $sql->adOnde('campo_formulario_customizado_id='.$campo_id);
      $sql->exec();
      $sql->limpar();

      return $campo_id;
      }
		}

	function campoComId($campo_id) {
		foreach ($this->campos as $k => $v) {
			if ($this->campos[$k]->campo_id == $campo_id) return $this->campos[$k];
			}
		}

	function join(&$variaveis) {
		if (!count($this->campos) == 0) {
			foreach ($this->campos as $k => $v) $this->campos[$k]->setValor(@$variaveis[$k]);
			}
		}

	function armazenar($objeto_id) {
		if (!count($this->campos) == 0) {
			$armazenar_erros = '';
			foreach ($this->campos as $k => $cf) {
				$resultado = $this->campos[$k]->armazenar($objeto_id);
				if ($resultado) $armazenar_erros .= 'Erro ao armazenar o campo customizado '.$k.':'.$resultado;
				}
			if ($armazenar_erros) echo $armazenar_erros;
			}
		}

	function excluirCampo($campo_id) {
		global $bd;
		$sql = new BDConsulta;
		$sql->setExcluir('campos_customizados_estrutura');
		$sql->adOnde('campo_id = '.$campo_id);
		if (!$sql->exec()) {
			return $bd->ErrorMsg();
			}
    $sql->limpar();

    $sql->setExcluir('campo_formulario');
    $sql->adOnde('campo_formulario_customizado_id = '.$campo_id);
		}

	function count() {
		return count($this->campos);
		}

	function getHTML() {
		if ($this->count() == 0) return '';
		else {
			$html = '';
			foreach ($this->campos as $cCampo) {
				if (!$this->publicado) $html .=  $cCampo->getHTML($this->modo);
				else $html .= $cCampo->getHTML($this->modo);
				}
			return $html;
			}
		}

	function imprimirHTML() {
		$html = $this->getHTML();
		echo $html;
		}


	}

class ListaOpcoesCustomizadas {
	var $campo_id;
	var $opcoes;

	function __construct($campo_id) {
		$this->campo_id = $campo_id;
		$this->options = array();
		}

	function load($oid = null, $tira = true) {
		global $bd;
		$sql = new BDConsulta;
		$sql->adTabela('campo_customizado_lista');
		$sql->adOnde('campo_customizado_lista_campo = '.$this->campo_id);
		$sql->adOrdem('campo_customizado_lista_valor');
		$opcoes=$sql->lista();
		$sql->limpar();
		$this->options = array();
		foreach($opcoes as $linha) $this->options[$linha['campo_customizado_lista_opcao']] = $linha['campo_customizado_lista_valor'];
		}

	function excluir() {
		$sql = new BDConsulta;
		$sql->setExcluir('campo_customizado_lista');
		$sql->adOnde('campo_customizado_lista_campo = '.$this->campo_id);
		$sql->exec();
		$sql->limpar();
		}

	function setOpcoes($opcao_array) {
		$this->options = $opcao_array;
		}

	function getOpcoes() {
		return $this->options;
		}

	function itemNoIndice($i) {
		if (isset($this->options[$i])) return $this->options[$i];
		}

	function getHTML($campo_nome, $selecionado) {
		$html = '<select class="texto" name="'.$campo_nome.'">';
		foreach ($this->options as $i => $opt) {
			$html .= "\t".'<option value="'.$i.'"';
			if ($i == $selecionado) $html .= ' selected="selected" ';
			$html .= '>'.$opt.'</option>';
			}
		$html .= '</select>';
		return $html;
		}
	}
?>