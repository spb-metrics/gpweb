/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa GP-Web
O GP-Web � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

Ext.define('GPWERP.store.filiados.Filiados',{
    extend: 'GPWeb.store.Base',

    model: 'GPWERP.model.filiados.Filiado',

    buffered: true,

    remoteSort: true,
    remoteFilter: true,

    pageSize: 100,

    proxy: {
        type       : 'ajax',
        extraParams: { m: 'erp', a: 'filiado_pro', sem_cabecalho: 1 },
        url        : 'index.php?f=listarFiliados',
        reader     : {
            type  : 'json',
            root  : 'filiados',
            encode: true,
            timeout: 30000000
        }
    }
});