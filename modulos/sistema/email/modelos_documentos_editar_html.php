<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

global $config, $Aplic;

$Aplic->carregarCKEditorJS();
require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');
$Aplic->carregarCalendarioJS();

$modelo_id=getParam($_REQUEST, 'modelo_id', 0);

$sql = new BDConsulta();
$sql->adTabela('modelos_tipo');
$sql->adCampo('modelo_tipo_campos, modelo_tipo_html');
$sql->adOnde('modelo_tipo_id='.$modelo_id);
$linha=$sql->linha();
$sql->limpar();

$arquivo_modelo = str_replace('src="imagens/', 'src="modulos/email/modelos/'.$config['militar'].'/imagens/', $linha['modelo_tipo_html']);
echo '<div name="bozo" id="editor_html" style="width: 100%; height: 100%;" contenteditable="true">';
echo $arquivo_modelo;
echo '</div>';
echo '<table widht="100%" cellpadding=0 cellspacing=0 border=0><tr>';
echo '<td>'.botao('salvar', 'Salvar HTML', 'Clique neste botão para salvar o conteúdo HTML do modelo editado.','','salvarHtml('.$modelo_id.');').'</td>';
echo '<td>'.botao('cancelar', 'Cancelar Edição', 'Clique neste botão para cancelar as modificações e fechar o editor.','','cancelarEdicao();').'</td>';
echo '</tr></table>';

echo '<script LANGUAGE="javascript">';
echo '$jq(function(){';
         echo 'var config_ckeditor = {';
            echo 'resize_enabled: false,';
            echo 'baseHref: "'.BASE_URL.'/",';
            echo 'baseUrl: "'.BASE_URL.'/",';
            echo "toolbar: [['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'],['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],['Link', 'Unlink'],['Sourcedialog']],";
            echo "extraPlugins: 'sourcedialog'";
        echo "};";

        echo 'CKEDITOR.replace("editor_html", config_ckeditor);';

        echo '$jq(window).resize(onResizeEditorWindow);';
        echo 'CKEDITOR.instances["editor_html"].on("instanceReady", onResizeEditorWindow);';
echo '});';
echo '</script>';
?>

<script type="text/javascript">
function onResizeEditorWindow(){
    CKEDITOR.instances['editor_html'].resize($jq( window ).width(), $jq( window ).height()-45);
    }

function salvarHtml(modelo_id){
    window.parent.gpwebApp._popupCallback(modelo_id, CKEDITOR.instances['editor_html'].getData());
    }

function cancelarEdicao(){
    window.parent.gpwebApp._popupCallback(0);
    }
</script>