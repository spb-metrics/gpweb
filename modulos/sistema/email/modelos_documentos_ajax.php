<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');


function alterar_html($modelo_id, $html){
    global $bd, $Aplic, $config;

    $html = previnirXSS(utf8_decode($html));
    if ($modelo_id){
        $sql = new BDConsulta();
        $sql->adTabela('modelos_tipo');
        $sql->adAtualizar('modelo_tipo_html', $html);
        $sql->adOnde('modelo_tipo_id='.$modelo_id);
        $sql->exec();
        $sql->limpar();

        $sql->adTabela('modelos_tipo');
        $sql->adCampo('modelo_tipo_campos, modelo_tipo_html');
        $sql->adOnde('modelo_tipo_id='.$modelo_id);
        $linha=$sql->linha();
        $sql->limpar();

        $campos = unserialize($linha['modelo_tipo_campos']);

        $modelo= new Modelo;
        $modelo->set_modelo_tipo($modelo_id);

        foreach((array)$campos['campo'] as $posicao => $campo){
            $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
            }

        $tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
        $modelo->set_modelo($tpl);
        $modelo->edicao=true;

        for ($i=1; $i <= $modelo->quantidade(); $i++){
            $campo='campo_'.$i;
            $tpl->$campo = $modelo->get_campo($i);
            }

        $exibir = $tpl->exibir($modelo->edicao);

        $objResposta = new xajaxResponse();
        $objResposta->assign("campo_modelos","innerHTML", utf8_encode($exibir));
        $objResposta->script('aposSalvarHtml();');
        return $objResposta;
        }
    }
$xajax->registerFunction("alterar_html");

$xajax->processRequest();
?>