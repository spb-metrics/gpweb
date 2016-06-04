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

gpweb\estilo\rondon\sobrecarga.php

Sobrecarrega as funções definidas na classes CBlocoTitulo_core, estiloTopoCaixa e
estiloFundoCaixa

********************************************************************************************/
require_once BASE_DIR.'/incluir/funcoes_principais.php';
class CBlocoTitulo extends CBlocoTitulo_core {
	}

class CTabBox extends CCaixaTab_nucleo {

	var $semlista=0;

	function mostrar($extra = '', $js_tabs = false, $alinhamento = 'left', $opt_flat = true, $somente_tab=false, $tableId = false) {
		global $Aplic, $config, $tabAtualId, $tabNomeAtual, $m, $a;

		$estilo=(isset($config['estilo_css']) && $config['estilo_css']=='metro' ? '_metro' : '');


		if (($a == 'editar' || $a == 'ver' || $a == 'ver_usuario') && function_exists('estiloFundoCaixa')) echo estiloFundoCaixa();
		reset($this->tabs);
		$s = '';
		if (!$this->semlista || !$pode_lista) {
			if ($opt_flat) echo '<table border=0 cellpadding="2" cellspacing=0 width="100%"><tr>'.$extra .'</tr></table>';
			}
		else if ($extra) echo '<table border=0 cellpadding="2" cellspacing=0 width="100%"><tr>'.$extra.'</tr></table>';

        if($tableId){
		    $s = '<table id="'.$tableId.'" width="100%" border=0 cellpadding=0 cellspacing=0>';
            }
        else{
            $s = '<table width="100%" border=0 cellpadding=0 cellspacing=0>';
            }
		$s .= '<tr><td><table align="'.$alinhamento.'" border=0 cellpadding=0 cellspacing=0>';
		if (count($this->tabs) - 1 < $this->ativo) $this->ativo= 0;
		foreach ($this->tabs as $k => $v) {
			$classe = ($k == $this->ativo) ? 'tabativo' : 'tabinativo';
			$sel = ($k == $this->ativo) ? 's' : '';
			$s .= '<td valign="middle"><img src="./estilo/rondon/imagens/tab_'.$sel.'e'.$estilo.'.png" id="tab_e_'.$k.'" border=0 alt="" /></td>';
			$s .= '<td id="tab_s_'.$k.'" valign="middle" nowrap="nowrap" class="'.$classe.'">&nbsp;<a '.($estilo ? 'class="aba" ' : '').'href="';
			if ($this->javascript) $s .= 'javascript:'.$this->javascript.'('.$this->ativo.', '.$k.')';
			elseif ($js_tabs) $s .= 'javascript:mostrar_tab('.$k.')';
			else $s .= 'javascript:void(0);" onclick="url_passar(0, \''.$this->baseHRef.'tab='.$k.'\');';
			$s .= '">' .($v[3]? dica($v[3], $v[4],TRUE) : '').$v[1].($v[3]? dicaF():'').'</a>&nbsp;</td>';
			$s .= '<td valign="middle" ><img id="tab_d_'.$k.'" src="./estilo/rondon/imagens/tab_'.$sel.'d'.$estilo.'.png" border=0 alt="" /></td>';
			$s .= '<td class="tabsp"><img src="'.acharImagem('shim.gif').'"/></td>';
			}
		$s .= '</table></td></tr>';
		$s .= '<tr><td><table width="100%" cellspacing=0 cellpadding=0 border=0>';
		$s .= '<tr><td valign="bottom" width="100%" background="./estilo/rondon/imagens/tab_t'.$estilo.'.jpg" align="left"><img src="./estilo/rondon/imagens/tab_t'.$estilo.'.jpg"/></td></tr>';
		$s .= '</table></td></tr>';
		$s .= '<tr><td width="100%" colspan="'.(count($this->tabs) * 4 + 1).'" class="tabox">';
		echo $s;
		if (isset($this->tabs[$this->ativo][0]) &&  $this->tabs[$this->ativo][0]) {
			$tabAtualId = $this->ativo;
			$tabNomeAtual = $this->tabs[$this->ativo][1];
			if (!$js_tabs) require $this->baseInc.$this->tabs[$this->ativo][0].'.php';
			}
		if ($js_tabs) {
			foreach ($this->tabs as $k => $v) {
				echo '<div class="tab" id="tab_'.$k.'">';
				$tabAtualId = $k;
				$tabNomeAtual = $v[1];
				require $this->baseInc.$v[0].'.php';
				echo '</div>';
				echo '<script language="JavaScript" type="text/javascript">
					<!--
					mostrar_tab('.$this->ativo. ');
					//-->
					</script>';
				}
			}
		echo '</td></tr></table>';

		}
	}

function estiloTopoCaixa($largura='100%', $link=''){
	global $Aplic, $config, $atualInfoTabId;
	if ($atualInfoTabId) return '';
	if ($Aplic->celular) return '<table width="'.$largura.'" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td width="100%" style="1 #a6a6a6">&nbsp;</td></tr></table>';
	if (isset($config['estilo_css']) && $config['estilo_css']=='classico'){
		$ret = '<table width="'.$largura.'" cellspacing=0 cellpadding=0 border=0 align="center"><tr>';
		$ret .= '<td valign="bottom" height="17" style="background:url('.$link.'estilo/rondon/imagens/caixa_e.png);" align="left"><img width="19" height="17" alt="" src="'.$link.'estilo/rondon/imagens/caixa_e.png"/></td>';
		$ret .= '<td valign="bottom" width="100%" style="background:url('.$link.'estilo/rondon/imagens/caixa_m.png);" align="left"><img width="19" height="17" alt="" src="'.$link.'estilo/rondon/imagens/caixa_m.png"/></td>';
		$ret .= '<td valign="bottom" style="background:url('.$link.'estilo/rondon/imagens/caixa_d.png);" align="right"><img width="19" height="17" alt="" src="'.$link.'estilo/rondon/imagens/caixa_d.png"/></td>';
		$ret .= '</tr></table>';
		}
	else {
		$ret = '<table width="'.($largura ? $largura : '100%').'" cellspacing=0 cellpadding=0 border=0 align="center"><tr>';
		$ret .= '<td valign="top" width="100%" style="background: repeat-x url('.$link.'estilo/rondon/imagens/caixa_m2.png);" align="left"><img alt="" src="'.$link.'estilo/rondon/imagens/caixa_m2.png"/></td>';
		$ret .= '</tr></table>';
		}
	return $ret;
	}

function estiloFundoCaixa($largura='100%', $link='', $tab='') {
	global $Aplic, $config;
	if ($Aplic->celular) return '<table width="'.$largura.'" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr></table>';
	if (isset($config['estilo_css']) && $config['estilo_css']=='classico'){
		$ret = '<table width="'.$largura.'" cellspacing=0 cellpadding=0 border=0 align="center"><tr>';
		$ret .= '<td valign="top" height="35" style="background:url('.$link.'estilo/rondon/imagens/sombra_e.png) no-repeat;" align="left"><img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_e.png"/></td>';
		$ret .= '<td valign="top" width="100%" style="background: repeat-x url('.$link.'estilo/rondon/imagens/sombra_m.png);" align="left"><img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_m.png"/></td>';
		$ret .= '<td valign="top" style="background:url('.$link.'estilo/rondon/imagens/sombra_d.png) no-repeat;" align="right"><img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_d.png"/></td>';
		$ret .= '</tr></table>';
		}
	else {
		$ret = '<table width="'.($largura ? $largura : '100%').'" cellspacing=0 cellpadding=0 border=0 align="center"><tr>';
		$ret .= '<td valign="top" width="100%" style="background: repeat-x url('.$link.'estilo/rondon/imagens/sombra_m2.png);" align="left"><img alt="" src="'.$link.'estilo/rondon/imagens/sombra_m2.png"/></td>';
		$ret .= '</tr></table>';
		}

	return ($tab != -1 ? $ret : '');
	}
?>