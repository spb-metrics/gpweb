<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
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
echo '<td>'.botao('salvar', 'Salvar HTML', 'Clique neste bot�o para salvar o conte�do HTML do modelo editado.','','salvarHtml('.$modelo_id.');').'</td>';
echo '<td>'.botao('cancelar', 'Cancelar Edi��o', 'Clique neste bot�o para cancelar as modifica��es e fechar o editor.','','cancelarEdicao();').'</td>';
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