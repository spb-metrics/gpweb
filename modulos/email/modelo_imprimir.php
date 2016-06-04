<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');

$sem_assinatura=getParam($_REQUEST, 'sem_assinatura', 0);

$modelo_id=getParam($_REQUEST, 'modelo_id', 0);
$modelo_dados_id=getParam($_REQUEST, 'modelo_dados_id', 0);

$sql = new BDConsulta;
$sql->adTabela('modelos');
$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
$sql->adCampo('modelo_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada, modelo_protocolo, modelo_autoridade_assinou, modelo_autoridade_aprovou, modelo_assunto, organizacao, modelo_tipo_html');
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
$campos = unserialize($dados['modelos_dados_campos']);

if( config('tipoBd') == 'postgres') $campos = unserialize(stripslashes($dados['modelos_dados_campos']));
else $campos = unserialize($dados['modelos_dados_campos']);


$modelo= new Modelo;
$modelo->set_modelo_tipo($linha['modelo_tipo']);
$modelo->set_modelo_id($modelo_id);
foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
$modelo->set_modelo($tpl);
$modelo->edicao=false;
	
	
for ($i=1; $i <= $modelo->quantidade(); $i++){
	$campo='campo_'.$i;
	$tpl->$campo = $modelo->get_campo($i);
	} 
echo '<table align=left><tr><td>';
echo $tpl->exibir(); 
echo '</td></tr>';


if (!$Aplic->profissional && $config['barra_modelo']){
	?>	
	<script>
	function barra(){	
		var protocolo=document.getElementById("protocolo").value; 
		if(protocolo) document.write('<img src="?m=publico&a=codigo_barra&sem_cabecalho=1&texto='+protocolo+'\">');
		}	
	</script>	
	<?php	
	echo '<tr><td colpan=20 align=center><script>barra()</script></td></tr>';
	}
echo '</table>';
echo '<script>self.print();</script>';


