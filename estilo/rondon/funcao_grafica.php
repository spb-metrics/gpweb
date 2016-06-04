<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\estilo\rondon\funcao_grafica.php		

Fun��es utilizadas para apresentar	a barra superior e inferior das diversas caixas					
																																												
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
