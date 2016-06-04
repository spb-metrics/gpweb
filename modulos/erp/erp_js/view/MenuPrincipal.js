/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

/**
* Menu geral do sistema de ERP
*/
Ext.define('GPWERP.view.MenuPrincipal',{
    extend: 'Ext.toolbar.Toolbar',

    alias: 'widget.gpw-erp-menu-principal',

    itemId: 'erpMenuPrincipal',
    enableOverflow: true,
    height: 32,
    padding: 0,
    bodyPadding: 0,
    border: 0,

    initComponent: function(cfg){
        var me = this;

        me.addEvents('item_selecionado');

        Ext.apply(me, {
            ignoreParentClicks: true,
            items: [
                {
                    text: 'Filiados',

                    menu: {
                        ignoreParentClicks: true,
                        items: [
                            {
                                text: 'Consultar',
                                itemId: 'filiados_consultar',
                                tooltip: 'Consultar os filiados existentes.',
                                handler: me.onItemSelecionado,
                                scope: me
                            }
                        ]
                    }
                },
                {
                    text: 'Atendimentos',
                    menu: {
                        ignoreParentClicks: true,
                        items: [
                            {
                                text: 'Odontológico ',
                                itemId: 'atendimento_odontologico',
                                tooltip: 'Acesso a lista de atendimentos odontológicos.'
                            },
                            {
                                text: 'Odontológico 1',
                                itemId: 'atendimento_odontologico1',
                                tooltip: 'Acesso a lista de atendimentos odontológicos.'
                            },
                            {
                                text: 'Odontológico 2',
                                itemId: 'atendimento_odontologico2',
                                tooltip: 'Acesso a lista de atendimentos odontológicos.'
                            }
                        ],
                        listener: {
                            scope: me,
                            click: me.onItemSelecionado
                        }
                    }
                },
                { text: 'Convênio' },
                { text: 'Juridico' },
                { text: 'Financeiro' },
                { text: 'Contabilidade' },
                { text: 'Controle Patimonial' },
                { text: 'Digitalização de Documentos' },
                { text: 'Agenda' },
                { text: 'Comunicação' },
                { text: 'RH' },
                { text: 'Dados Auxiliares' },
                { text: 'Usuário' }
            ]
        });
        me.callParent(arguments);
    },

    onItemSelecionado: function(menu, item){
        var me = this,
            btnId = item.getItemId();

        btnId = btnId.replace('_','/');
        gpwerp.navegar(btnId);
    }
});