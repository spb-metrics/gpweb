/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

/**
* Tela principal do módulo ERP
*/
Ext.define('GPWERP.view.Viewport', {
    extend: 'Ext.container.Viewport',

    requires: ['GPWERP.view.MenuPrincipal'],

    itemId: 'erpViewport',
    layout: 'fit',

    items: [
        {
            xtype: 'panel',
            itemId: 'pnCentro',
            padding: 0,
            bodyPadding: 0,
            border: 0,
            layout: 'fit',
            dockedItems: [
                {
                    xtype: 'gpw-erp-menu-principal',
                    dock: 'top'
                }
            ]
        }
    ]
});