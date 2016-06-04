<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$chamarVolta = isset($_REQUEST['chamar_volta']) ? getParam($_REQUEST, 'chamar_volta', '') : 0;
//echo '<script language="javascript">function setFechar(cor){window.opener.'.$chamarVolta.'(cor); window.close();}</script>';

echo '<script language="javascript">function setFechar(cor){
	if(parent && parent.gpwebApp){if (cor) parent.gpwebApp._popupCallback(cor); else parent.gpwebApp._popupCallback("");} else {
	if (cor) window.opener.'.$chamarVolta.'(cor); else window.opener.'.$chamarVolta.'(""); window.close();}}</script>';


$cores = getSisValor('CoresProjeto');
echo estiloTopoCaixa();
if ($config['selecao_cor_restrita']) {
	echo '<table border=0 width="100%" cellpadding=1 cellspacing=0 class="std2" align="center"><tr><td valign="top" colspan="2"><b>Seleção de Cor</b></td></tr>';
	foreach ($cores as $chave => $valor) echo '<tr><td style="background-color:#'.$valor.'; border: 1px solid black;"><a href="javascript:setFechar(\''.$valor.'\')">&nbsp;&nbsp;&nbsp;&nbsp;</a></td><td width="300"><a href="javascript:setFechar(\''.$valor.'\')">'.$chave.'</a></td></tr>';
	echo '</table>';
	} 
else {
	echo '<table border=0 width="100%" cellpadding=0 cellspacing=1 class="std2" align="center">';
	echo '<tr><td valign="top"><b>Seleção de Cor</b></td></tr>';
	echo '<tr><td colspan="2"><a href="webpal.map"><img src="'.acharImagem('paleta.png').'" width="292" height="196" border=0 alt="color chart" usemap="#mapa_cores" ismap /></a></td></tr>';
	echo '<tr><td colspan="2" align="left"><font size="1" face="trebuchetms,verdana,arial">Clique em cima da cor de sua preferência.</p></td></tr>';
	echo '</table>';
?>
	<map name="mapa_cores">
	<area coords="2,2,18,18"  href="javascript:setFechar('fff5f5')" />
	<area coords="18,2,34,18" href="javascript:setFechar('fffbf5')" />
	<area coords="34,2,50,18" href="javascript:setFechar('fffef5')" />
	<area coords="50,2,66,18" href="javascript:setFechar('fffff5')" />
	<area coords="66,2,82,18" href="javascript:setFechar('fdfff5')" />
	<area coords="82,2,98,18" href="javascript:setFechar('fafff5')" />

	<area coords="98,2,114,18"  href="javascript:setFechar('f5fff8')" />
	<area coords="114,2,130,18" href="javascript:setFechar('f5fffb')" />
	<area coords="130,2,146,18" href="javascript:setFechar('f5fffe')" />
	<area coords="146,2,162,18" href="javascript:setFechar('f5feff')" />
	<area coords="162,2,178,18" href="javascript:setFechar('f5faff')" />
	<area coords="178,2,194,18" href="javascript:setFechar('f5f5ff')" />

	<area coords="194,2,210,18" href="javascript:setFechar('faf5ff')" />
	<area coords="210,2,226,18" href="javascript:setFechar('fef5ff')" />
	<area coords="226,2,242,18" href="javascript:setFechar('fff5fd')" />
	<area coords="242,2,258,18" href="javascript:setFechar('fff5fa')" />
	<area coords="258,2,274,18" href="javascript:setFechar('f5f5f5')" />
	<area coords="274,2,290,18" href="javascript:setFechar('ffffff')" />

	<!-- 2a linha -->
	<area coords="2,18,18,34"  href="javascript:setFechar('ffebeb')" />
	<area coords="18,18,34,34" href="javascript:setFechar('fff8eb')" />
	<area coords="34,18,50,34" href="javascript:setFechar('fffdeb')" />
	<area coords="50,18,66,34" href="javascript:setFechar('ffffeb')" />
	<area coords="66,18,82,34" href="javascript:setFechar('faffeb')" />
	<area coords="82,18,98,34" href="javascript:setFechar('f6ffeb')" />

	<area coords="98,18,114,34"  href="javascript:setFechar('ebfff1')" />
	<area coords="114,18,130,34" href="javascript:setFechar('ebfff6')" />
	<area coords="130,18,146,34" href="javascript:setFechar('ebfffc')" />
	<area coords="146,18,162,34" href="javascript:setFechar('ebfcff')" />
	<area coords="162,18,178,34" href="javascript:setFechar('ebf6ff')" />
	<area coords="178,18,194,34" href="javascript:setFechar('ebecff')" />

	<area coords="194,18,210,34" href="javascript:setFechar('f6ebff')" />
	<area coords="210,18,226,34" href="javascript:setFechar('fcebff')" />
	<area coords="226,18,242,34" href="javascript:setFechar('ffebfc')" />
	<area coords="242,18,258,34" href="javascript:setFechar('ffebf4')" />
	<area coords="258,18,274,34" href="javascript:setFechar('ebebeb')" />
	<area coords="274,18,290,34" href="javascript:setFechar('daeca3')" />

	<!-- 3a linha -->

	<area coords="2,34,18,50"  href="javascript:setFechar('ffe0e0')" />
	<area coords="18,34,34,50" href="javascript:setFechar('fff4e0')" />
	<area coords="34,34,50,50" href="javascript:setFechar('fffce0')" />
	<area coords="50,34,66,50" href="javascript:setFechar('ffffe0')" />
	<area coords="66,34,82,50" href="javascript:setFechar('f8ffe0')" />
	<area coords="82,34,98,50" href="javascript:setFechar('f1ffe0')" />

	<area coords="98,34,114,50"  href="javascript:setFechar('e0ffe9')" />
	<area coords="114,34,130,50" href="javascript:setFechar('e0fff2')" />
	<area coords="130,34,146,50" href="javascript:setFechar('e0fffb')" />
	<area coords="146,34,162,50" href="javascript:setFechar('e0fbff')" />
	<area coords="162,34,178,50" href="javascript:setFechar('e0f1ff')" />
	<area coords="178,34,194,50" href="javascript:setFechar('e0e1ff')" />

	<area coords="194,34,210,50" href="javascript:setFechar('f0e0ff')" />
	<area coords="210,34,226,50" href="javascript:setFechar('fae0ff')" />
	<area coords="226,34,242,50" href="javascript:setFechar('ffe0fa')" />
	<area coords="242,34,258,50" href="javascript:setFechar('ffe0ee')" />
	<area coords="258,34,274,50" href="javascript:setFechar('e0e0e0')" />
	<area coords="274,34,290,50" href="javascript:setFechar('cac5de')" />

	<!-- 4a linha -->
	<area coords="2,50,18,66"  href="javascript:setFechar('ffd6d6')" />
	<area coords="18,50,34,66" href="javascript:setFechar('fff0d6')" />
	<area coords="34,50,50,66" href="javascript:setFechar('fffbd6')" />
	<area coords="50,50,66,66" href="javascript:setFechar('ffffd6')" />
	<area coords="66,50,82,66" href="javascript:setFechar('f6ffd7')" />
	<area coords="82,50,98,66" href="javascript:setFechar('ecffd7')" />

	<area coords="98,50,114,66"  href="javascript:setFechar('d7ffe2')" />
	<area coords="114,50,130,66" href="javascript:setFechar('d7ffed')" />
	<area coords="130,50,146,66" href="javascript:setFechar('d7fff9')" />
	<area coords="146,50,162,66" href="javascript:setFechar('d7f9ff')" />
	<area coords="162,50,178,66" href="javascript:setFechar('d7ecff')" />
	<area coords="178,50,194,66" href="javascript:setFechar('d7d8ff')" />

	<area coords="194,50,210,66" href="javascript:setFechar('ecd7ff')" />
	<area coords="210,50,226,66" href="javascript:setFechar('f9d7ff')" />
	<area coords="226,50,242,66" href="javascript:setFechar('ffd7f8')" />
	<area coords="242,50,258,66" href="javascript:setFechar('ffd7e9')" />
	<area coords="258,50,274,66" href="javascript:setFechar('d6d6d6')" />
	<area coords="274,50,290,66" href="javascript:setFechar('c8dada')" />

	<!-- 5a linha -->
	<area coords="2,66,18,82"  href="javascript:setFechar('ffcccc')" />
	<area coords="18,66,34,82" href="javascript:setFechar('ffeccc')" />
	<area coords="34,66,50,82" href="javascript:setFechar('fffacc')" />
	<area coords="50,66,66,82" href="javascript:setFechar('ffffcc')" />
	<area coords="66,66,82,82" href="javascript:setFechar('f3fecd')" />
	<area coords="82,66,98,82" href="javascript:setFechar('e8fecd')" />

	<area coords="98,66,114,82"  href="javascript:setFechar('cdfedb')" />
	<area coords="114,66,130,82" href="javascript:setFechar('cdfee9')" />
	<area coords="130,66,146,82" href="javascript:setFechar('cdfef8')" />
	<area coords="146,66,162,82" href="javascript:setFechar('cdf8fe')" />
	<area coords="162,66,178,82" href="javascript:setFechar('cde8fe')" />
	<area coords="178,66,194,82" href="javascript:setFechar('cdcefe')" />

	<area coords="194,66,210,82" href="javascript:setFechar('e7cdff')" />
	<area coords="210,66,226,82" href="javascript:setFechar('f7cdff')" />
	<area coords="226,66,242,82" href="javascript:setFechar('ffcdf6')" />
	<area coords="242,66,258,82" href="javascript:setFechar('ffcde3')" />
	<area coords="258,66,274,82" href="javascript:setFechar('cccccc')" />
	<area coords="274,66,290,82" href="javascript:setFechar('abacc7')" />

	<!-- 6a linha -->
	<area coords="2,82,18,98"  href="javascript:setFechar('ffc2c2')" />
	<area coords="18,82,34,98" href="javascript:setFechar('ffe9c2')" />
	<area coords="34,82,50,98" href="javascript:setFechar('fff9c2')" />
	<area coords="50,82,66,98" href="javascript:setFechar('ffffc2')" />
	<area coords="66,82,82,98" href="javascript:setFechar('f1fec3')" />
	<area coords="82,82,98,98" href="javascript:setFechar('e3fec3')" />

	<area coords="98,82,114,98"  href="javascript:setFechar('c3fed4')" />
	<area coords="114,82,130,98" href="javascript:setFechar('c3fee4')" />
	<area coords="130,82,146,98" href="javascript:setFechar('c3fef7')" />
	<area coords="146,82,162,98" href="javascript:setFechar('c3f7fe')" />
	<area coords="162,82,178,98" href="javascript:setFechar('c3e3fe')" />
	<area coords="178,82,194,98" href="javascript:setFechar('c3c4fe')" />

	<area coords="194,82,210,98" href="javascript:setFechar('e2c3ff')" />
	<area coords="210,82,226,98" href="javascript:setFechar('f6c3ff')" />
	<area coords="226,82,242,98" href="javascript:setFechar('ffc3f4')" />
	<area coords="242,82,258,98" href="javascript:setFechar('ffc3de')" />
	<area coords="258,82,274,98" href="javascript:setFechar('c2c2c2')" />
	<area coords="274,82,290,98" href="javascript:setFechar('daa3a3')" />

	<!-- 7a linha -->
	<area coords="2,98,18,114"  href="javascript:setFechar('ffb8b8')" />
	<area coords="18,98,34,114" href="javascript:setFechar('ffe5b8')" />
	<area coords="34,98,50,114" href="javascript:setFechar('fff7b8')" />
	<area coords="50,98,66,114" href="javascript:setFechar('ffffb8')" />
	<area coords="66,98,82,114" href="javascript:setFechar('effeb9')" />
	<area coords="82,98,98,114" href="javascript:setFechar('defeb9')" />

	<area coords="98,98,114,114"  href="javascript:setFechar('b9fecd')" />
	<area coords="114,98,130,114" href="javascript:setFechar('b9fee0')" />
	<area coords="130,98,146,114" href="javascript:setFechar('b9fef5')" />
	<area coords="146,98,162,114" href="javascript:setFechar('b9f5fe')" />
	<area coords="162,98,178,114" href="javascript:setFechar('b9defe')" />
	<area coords="178,98,194,114" href="javascript:setFechar('b9bbfe')" />

	<area coords="194,98,210,114" href="javascript:setFechar('ddb9ff')" />
	<area coords="210,98,226,114" href="javascript:setFechar('f4b9ff')" />
	<area coords="226,98,242,114" href="javascript:setFechar('ffb9f3')" />
	<area coords="242,98,258,114" href="javascript:setFechar('ffb9d8')" />
	<area coords="258,98,274,114" href="javascript:setFechar('b8b8b8')" />
	<area coords="274,98,290,114" href="javascript:setFechar('cebbc5')" />

	<!-- 8a linha -->
	<area coords="2,114,18,130"  href="javascript:setFechar('ffadad')" />
	<area coords="18,114,34,130" href="javascript:setFechar('ffe1ad')" />
	<area coords="34,114,50,130" href="javascript:setFechar('fff6ad')" />
	<area coords="50,114,66,130" href="javascript:setFechar('ffffad')" />
	<area coords="66,114,82,130" href="javascript:setFechar('ecfeae')" />
	<area coords="82,114,98,130" href="javascript:setFechar('d9feae')" />

	<area coords="98,114,114,130"  href="javascript:setFechar('aefec5')" />
	<area coords="114,114,130,130" href="javascript:setFechar('aefedb')" />
	<area coords="130,114,146,130" href="javascript:setFechar('aefef4')" />
	<area coords="146,114,162,130" href="javascript:setFechar('aef4fe')" />
	<area coords="162,114,178,130" href="javascript:setFechar('aed9fe')" />
	<area coords="178,114,194,130" href="javascript:setFechar('aeb0fe')" />

	<area coords="194,114,210,130" href="javascript:setFechar('d8aeff')" />
	<area coords="210,114,226,130" href="javascript:setFechar('f3aeff')" />
	<area coords="226,114,242,130" href="javascript:setFechar('ffaef1')" />
	<area coords="242,114,258,130" href="javascript:setFechar('ffaed2')" />
	<area coords="258,114,274,130" href="javascript:setFechar('adadad')" />
	<area coords="274,114,290,130" href="javascript:setFechar('b1aea6')" />

	<!-- 9a linha -->
	<area coords="2,130,18,146"  href="javascript:setFechar('ffa3a3')" />
	<area coords="18,130,34,146" href="javascript:setFechar('ffdda3')" />
	<area coords="34,130,50,146" href="javascript:setFechar('fff5a3')" />
	<area coords="50,130,66,146" href="javascript:setFechar('ffffa3')" />
	<area coords="66,130,82,146" href="javascript:setFechar('eafea4')" />
	<area coords="82,130,98,146" href="javascript:setFechar('d5fea4')" />

	<area coords="98,130,114,146"  href="javascript:setFechar('a4febe')" />
	<area coords="114,130,130,146" href="javascript:setFechar('a4fed7')" />
	<area coords="130,130,146,146" href="javascript:setFechar('a4fef2')" />
	<area coords="146,130,162,146" href="javascript:setFechar('a4f2fe')" />
	<area coords="162,130,178,146" href="javascript:setFechar('a4d5fe')" />
	<area coords="178,130,194,146" href="javascript:setFechar('a4a7fe')" />

	<area coords="194,130,210,146" href="javascript:setFechar('d3a4ff')" />
	<area coords="210,130,226,146" href="javascript:setFechar('f1a4ff')" />
	<area coords="226,130,242,146" href="javascript:setFechar('ffa4ef')" />
	<area coords="242,130,258,146" href="javascript:setFechar('ffa4cd')" />
	<area coords="258,130,274,146" href="javascript:setFechar('a3a3a3')" />
	<area coords="274,130,290,146" href="javascript:setFechar('b5c8a3')" />

	<!-- 10a linha -->
	<area coords="2,146,18,162"  href="javascript:setFechar('ff9999')" />
	<area coords="18,146,34,162" href="javascript:setFechar('ffda99')" />
	<area coords="34,146,50,162" href="javascript:setFechar('fff499')" />
	<area coords="50,146,66,162" href="javascript:setFechar('ffff99')" />
	<area coords="66,146,82,162" href="javascript:setFechar('e8fe9b')" />
	<area coords="82,146,98,162" href="javascript:setFechar('d0fe9b')" />

	<area coords="98,146,114,162"  href="javascript:setFechar('9bfeb7')" />
	<area coords="114,146,130,162" href="javascript:setFechar('9bfed3')" />
	<area coords="130,146,146,162" href="javascript:setFechar('9bfef1')" />
	<area coords="146,146,162,162" href="javascript:setFechar('9bf1fe')" />
	<area coords="162,146,178,162" href="javascript:setFechar('9bd0fe')" />
	<area coords="178,146,194,162" href="javascript:setFechar('9b9dfe')" />

	<area coords="194,146,210,162" href="javascript:setFechar('cf9bff')" />
	<area coords="210,146,226,162" href="javascript:setFechar('f09bff')" />
	<area coords="226,146,242,162" href="javascript:setFechar('ff9bed')" />
	<area coords="242,146,258,162" href="javascript:setFechar('ff9bc7')" />
	<area coords="258,146,274,162" href="javascript:setFechar('999999')" />
	<area coords="274,146,290,162" href="javascript:setFechar('a9c4b3')" />

	<!-- 11a linha -->
	<area coords="2,162,18,178"  href="javascript:setFechar('ff8f8f')" />
	<area coords="18,162,34,178" href="javascript:setFechar('ffd68f')" />
	<area coords="34,162,50,178" href="javascript:setFechar('fff38f')" />
	<area coords="50,162,66,178" href="javascript:setFechar('ffff8f')" />
	<area coords="66,162,82,178" href="javascript:setFechar('e6fe91')" />
	<area coords="82,162,98,178" href="javascript:setFechar('ccfe91')" />

	<area coords="98,162,114,178"  href="javascript:setFechar('91feb0')" />
	<area coords="114,162,130,178" href="javascript:setFechar('91fece')" />
	<area coords="130,162,146,178" href="javascript:setFechar('91fef0')" />
	<area coords="146,162,162,178" href="javascript:setFechar('91f0fe')" />
	<area coords="162,162,178,178" href="javascript:setFechar('91ccfe')" />
	<area coords="178,162,194,178" href="javascript:setFechar('9193fe')" />

	<area coords="194,162,210,178" href="javascript:setFechar('ca91ff')" />
	<area coords="210,162,226,178" href="javascript:setFechar('ee91ff')" />
	<area coords="226,162,242,178" href="javascript:setFechar('ff91ec')" />
	<area coords="242,162,258,178" href="javascript:setFechar('ff91c2')" />
	<area coords="258,162,274,178" href="javascript:setFechar('8f8f8f')" />
	<area coords="274,162,290,178" href="javascript:setFechar('ccccb2')" />

	<!-- 12a linha -->
	<area coords="2,178,18,194"  href="javascript:setFechar('ff8585')" />
	<area coords="18,178,34,194" href="javascript:setFechar('ffd385')" />
	<area coords="34,178,50,194" href="javascript:setFechar('fff285')" />
	<area coords="50,178,66,194" href="javascript:setFechar('ffff85')" />
	<area coords="66,178,82,194" href="javascript:setFechar('e3fe87')" />
	<area coords="82,178,98,194" href="javascript:setFechar('c7fe87')" />

	<area coords="98,178,114,194"  href="javascript:setFechar('87fea8')" />
	<area coords="114,178,130,194" href="javascript:setFechar('87feca')" />
	<area coords="130,178,146,194" href="javascript:setFechar('87feee')" />
	<area coords="146,178,162,194" href="javascript:setFechar('87eefe')" />
	<area coords="162,178,178,194" href="javascript:setFechar('87c7fe')" />
	<area coords="178,178,194,194" href="javascript:setFechar('878afe')" />

	<area coords="194,178,210,194" href="javascript:setFechar('c587ff')" />
	<area coords="210,178,226,194" href="javascript:setFechar('ed87ff')" />
	<area coords="226,178,242,194" href="javascript:setFechar('ff87ea')" />
	<area coords="242,178,258,194" href="javascript:setFechar('ff87bc')" />
	<area coords="258,178,274,194" href="javascript:setFechar('858585')" />
	<area coords="274,178,290,194" href="javascript:setFechar('b2cccc')" />
	</map>
<?php
	}
echo estiloFundoCaixa();
?>