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
		
gpweb\estilo\rondon\funcao_grafica.php		

Funções utilizadas para apresentar	a barra superior e inferior das diversas caixas					
																																												
********************************************************************************************/
function estiloTopoCaixa($largura='100%',$link='') {
	global $estilo,$Aplic;
	if (isset($Aplic->celular)&&$Aplic->celular) return '<table width="'.$largura.'" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr></table>';
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
	
function estiloFundoCaixa($largura='100%',$link=''){
	global $estilo, $Aplic;
	if (isset($Aplic->celular)&&$Aplic->celular) return '<table width="'.$largura.'" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr></table>';
	if (isset($config['estilo_css']) && $config['estilo_css']=='classico'){
		$ret = '<table width="'.$largura.'" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td valign="top" height="35" align="left">';
		$ret .= '		<img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_e.png"></td>';
		$ret .= '	<td valign="top" width="100%" style="background: repeat-x url('.$link.'estilo/rondon/imagens/sombra_m.png);" align="left">';
		$ret .= '		<img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_m.png"></td>';
		$ret .= '	<td valign="top"  align="right"><img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_d.png"></td></tr></table>';
		}
	else {
		$ret = '<table width="'.($largura ? $largura : '100%').'" cellspacing=0 cellpadding=0 border=0 align="center"><tr>';
		$ret .= '<td valign="top" width="100%" style="background: repeat-x url('.$link.'estilo/rondon/imagens/sombra_m2.png);" align="left"><img alt="" src="'.$link.'estilo/rondon/imagens/sombra_m2.png"/></td>';
		$ret .= '</tr></table>';
		}
	return $ret;
	}


function linha1_titulo($link=''){
global $estilo;
$ret = '<table width="100%" border=0 cellpadding=0 cellspacing=0><tr>';
$ret .= '<th style="background: url('.$link.'estilo/rondon/imagens/titulo_fundo.png);" align="left">&nbsp;</th>';
$ret .= '<th style="background: url('.$link.'estilo/rondon/imagens/titulo_fundo.png);" align="right" width="123"><a '.dica('Site do '.$config['gpweb'], 'Clique para entrar no site oficial do '.$config['gpweb'].'.', 1000).' target="_blank" href="'.$config['endereco_site'].'">';
$ret .= '<img src="'.$link.'estilo/rondon/imagens/titleEB.jpg" border=0 class="letreiro" align="left" ></th>';
$ret .= '<th style="background: url('.$link.'estilo/rondon/imagens/titulo_fundo.png);" align="right" width="5">&nbsp;</th></tr></table>';
return $ret;	
}

function linha2_titulo(){
global $estilo;
$ret = '<table border=0 width="100%" HEIGHT="35" cellpadding=0 cellspacing=0><tr>';
$ret .= '<td width="20" HEIGHT="35" align="left" cellpadding=0 cellspacing=0 background="estilo/rondon/imagens/sombra_e.png"></td>';
$ret .= '<td background="estilo/rondon/imagens/sombra_m.png" >&nbsp;</td><td width="20" HEIGHT="35" align="right" cellpadding=0 cellspacing=0 background="estilo/rondon/imagens/sombra_d.png"></td></tr></table>';
return $ret;
}


function moldura_atualiza ($link=''){
global $estilo;
$ret = '
<BODY>
<LINK href="'.$link.'estilo/rondon/estilo_'.$config['estilo_css'].'.css" type=text/css rel=stylesheet>
<div id="Layer13" style="position:absolute; left:50px; top:10px;">
<table width="900" cellspacing=0 cellpadding=0 border=0 align="center">
<tr><td valign="bottom" height="17" style="background:url('.$link.'estilo/rondon/imagens/caixa_e.png) no-repeat;" align="left">
<img width="19" height="17" alt="" src="'.$link.'estilo/rondon/imagens/caixa_e.png">
</td><td valign="bottom" width="100%" style="background:repeat-x url('.$link.'estilo/rondon/imagens/caixa_m.png);" align="left">
<img width="19" height="17" alt="" src="'.$link.'estilo/rondon/imagens/caixa_m.png">
</td><td valign="bottom" style="background:url('.$link.'estilo/rondon/imagens/caixa_d.png) no-repeat;" align="right">
<img width="19" height="17" alt="" src="'.$link.'estilo/rondon/imagens/caixa_d.png">
</td></tr></table><table align="center" border=0 cellspacing=0 width="900" >
	 <tr><td><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR></td></tr>	
	 <tr><td><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR></td></tr>	
</table><div id="Layer13" style="position:absolute; left:0px; top:550px;">
<table width="900" cellspacing=0 cellpadding=0 border=0 align="center">
<tr><td valign="top" height="35" style="background:url('.$link.'estilo/rondon/imagens/sombra_e.png) no-repeat;" align="left">
<img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_e.png">
</td><td valign="top" width="100%" style="background: repeat-x url('.$link.'estilo/rondon/imagens/sombra_m.png);" align="left">
<img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_m.png">
</td><td valign="top" style="background:url('.$link.'estilo/rondon/imagens/sombra_d.png) no-repeat;" align="right">
<img width="19" height="35" alt="" src="'.$link.'estilo/rondon/imagens/sombra_d.png">
</td></tr></table><div id="Layer13" style="position:absolute; left:20px; top:-540px;">';
return $ret;
}
