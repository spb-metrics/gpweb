<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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


