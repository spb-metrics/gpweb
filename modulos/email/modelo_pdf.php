<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');

$orientacao=(isset($_REQUEST["orientacao"]) ? getParam($_REQUEST, 'orientacao', null) : 'portrait');
$relatorio=(isset($_REQUEST["relatorio"]) ? getParam($_REQUEST, 'relatorio', null) : 'relatorio');
$modelo_id=(isset($_REQUEST["modelo_id"]) ? getParam($_REQUEST, 'modelo_id', null) : 1);

$sql = new BDConsulta;
$sql->adTabela('modelos');
$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
$sql->adCampo('class_sigilosa, modelo_assinatura, modelo_chave_publica, modelo_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada, modelo_protocolo, modelo_autoridade_assinou, modelo_autoridade_aprovou, modelo_assunto, modelo_tipo_html');
$sql->adOnde('modelo_id='.$modelo_id);
$linha=$sql->Linha();
$sql->Limpar();
$sql->adTabela('modelos_dados');
$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = modelos_dados_criador');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
$sql->adOnde('modelo_dados_modelo='.$modelo_id);
$sql->adOrdem('modelo_dados_id DESC');	
$dados=$sql->Linha();
$sql->Limpar();
$modelo_dados_id=$dados['modelo_dados_id'];
$criador=$dados['modelos_dados_criador'];
$campos = unserialize($dados['modelos_dados_campos']);
$modelo= new Modelo;
$modelo->set_modelo_tipo($linha['modelo_tipo']);
$modelo->set_modelo_id($modelo_id);
foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
//foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], $campo['dados'], $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
$modelo->set_modelo($tpl);

$modelo->edicao=false;
$modelo->impressao=true;

	
	
for ($i=1; $i <= $modelo->quantidade(); $i++){
	$campo='campo_'.$i;
	$tpl->$campo = $modelo->get_campo($i);
	} 



$fp = fopen("amit2.rtf", 'w+');
fwrite($fp, $tpl->exibir());
fclose($fp);


//header("Content-type: application/msword");
//header("Content-Disposition: attachment;Filename=document_name.doc"); 
//echo $tpl->exibir();


$html='<table width="750" cellspacing=0 cellpadding=0 align="center">
  <tr><td colspan=20 align=center><img src="'.$Aplic->gpweb_brasao.'" alt="" border=0 /></td></tr>
  <tr><td colspan=20><p style="text-align: center;"><strong>MINIST&Eacute;RIO DA DEFESA<br />EX&Eacute;RCITO BRASILEIRO<br />COMANDO MILITAR DO SUL<br />3&ordm; BATALH&Atilde;O DE COMUNICA&Ccedil;&Otilde;ES<br />(3&ordf; Cia Trns / 3&ordm; BE / 1917)</strong></p></td></tr>
  <tr><td width="10">&nbsp;</td><td width="738"><table width="100%" cellspacing=0 cellpadding=0>
		<tr><td style="height:80px">&nbsp;</td></tr>
		<tr><td width=100%><table width=100%> 
	    	<tr><td width=45%>&nbsp;</td><td style="font-weight:bold;">Porto Alegre&nbsp;,&nbsp;15 de maio de 2010</td></tr>
				<tr><td><table cellspacing=0 cellpadding=0>
						<tr style="font-weight:bold;"><td height="30">Parte&nbsp;</td><td>auto</td></tr>
				</table></td><td>&nbsp;</td></tr>
   	<tr><td>&nbsp;</td><td><b>Do</b>&nbsp;admin</td></tr> 
		<tr><td style="height:8px" colspan=20></td></tr>
    <tr><td>&nbsp;</td><td><table width="100%" cellspacing=0 cellpadding=0>
    		<tr><td valign="top" width="20"><b>Ao</b>&nbsp;</td><td>lochas</td></tr>
    </table></td></tr> 
    <tr><td style="height:8px" colspan=20></td></tr>  
    <tr><td>&nbsp;</td><td><table width="100%" cellspacing=0 cellpadding=0>
    		<tr><td valign="top" width="20"><b>Assunto:</b>&nbsp;</td><td><span style="width:270px;">como vai?</span></td></tr>
    </table></td></tr>
  </table></td></tr> 
  <tr><td height="86" colspan=20 style="height:54px"></td></tr>
  <tr><td align=left colspan=20><p>interessante</p></td></tr>
  <tr><td height="70">&nbsp;</td></tr>
	<tr><td align=center><table cellspacing=0 cellpadding=0>
			<tr><td align="center" height="70" valign="bottom">___________________________________________</td></tr><tr><td align="center" style="font-weight:bold;">Sr. Administrador</td></tr><tr><td align="center">admin</td></tr>
	</table></td></tr>

</table></td></tr>
<tr><td colspan=20 height="40">&nbsp;</td></tr>
</table>';





exit(0);




?>
