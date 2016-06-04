/*
 Copyright [2008] -  Sérgio Fernandes Reinert de Lima
 Este arquivo é parte do programa GP-Web
 O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 */

Ext.define( 'GPWERP.view.filiados.PainelConsulta', {
    extend: 'Ext.panel.Panel',

    requires: [
        'GPWERP.view.filiados.ListaFiliados',
        'GPWERP.store.filiados.Filiados'
    ],

    title : 'Filiados >> Consulta',
    layout: {
        type : 'vbox',
        align: 'stretch'
    },

    autoScroll: true,

    /**
     * Inicializa o componente
     */
    initComponent: function() {
        var me  = this,
            leg = GPWeb.util.Legendas;

        me.store = me.store || Ext.create( 'GPWERP.store.filiados.Filiados', { autoLoad: true } );

        Ext.apply( me, {
            tbar: {
                defaults: {
                    //scale    : 'medium'
                },
                items   : [
                    {
                        text   : 'Novo',
                        iconCls: 'iconeAdicionar',
                        tooltip: 'Selecione esta opção para adicionar um novo filiado.',
                        scope  : me,
                        handler: me.onNovoFiliado
                    },
                    {
                        text   : 'Atualizar',
                        iconCls: 'iconeAtualizar',
                        tooltip: 'Selecione esta opção para solicitar os dados do servidor novamente.',
                        scope  : me,
                        handler: me.onAtualizarLista
                    }
                ]
            },

            items: [
                {
                    xtype : 'fieldset',
                    layout: {
                        type          : 'hbox',
                        defaultMargins: {
                            left: 20
                        }
                    },
                    items : [
                        {
                            xtype     : 'textfield',
                            itemId    : 'filtroNome',
                            fieldLabel: 'Nome do filiado',
                            labelAlign: 'top',
                            width     : 350,
                            listeners : {
                                scope     : me,
                                specialkey: me.onPesquisar
                            }
                        },
                        {
                            xtype     : 'textfield',
                            itemId    : 'filtroMatricula',
                            fieldLabel: leg.apply( 'Matrícula no sindicato' ),
                            labelAlign: 'top',
                            width     : 200,
                            listeners : {
                                scope     : me,
                                specialkey: me.onPesquisar
                            }
                        }
                    ]
                },
                {
                    xtype: 'gpw-lista-filiados',
                    flex : 1,
                    store: me.store
                }
            ]
        } );

        me.callParent( arguments );
    },

    /**
     * Inicia um novo registro e adiciona a lista se for salvo.
     */
    onNovoFiliado: function() {
        var janela = Ext.create( 'Ext.window.Window', {
            title      : 'Detalhes do Filiado',
            autoShow   : true,
            modal      : true,
            layout     : 'fit',
            width      : 600,
            height     : 400,
            maximizable: true,
            maximized  : true,

            items: {
                xtype       : 'gpw-painel-filiado',
                editar      : true,
                record      : Ext.create( 'GPWERP.model.filiados.Filiado', {} ),
                novoRegistro: true,
                listeners   : {
                    scope   : this,
                    salvo   : {
                        single: true,
                        fn    : function( view, record ) {
                            this.store.add( record );
                        }
                    },
                    cancelar: function() {
                        janela.close();
                    }
                }
            }
        } );
    },

    onAtualizarLista: function() {
        this.store.reload();
    },

    onPesquisar: function( fld, e ) {
        var me    = this,
            store = me.store;
        if( e.getKey() == e.ENTER ) {
            var filtroMatricula = me.down('#filtroMatricula' ).getValue(),
                filtroNome = me.down('#filtroNome' ).getValue(),
                filtrado = me.filtrado;

            filtros = [];

            if(filtroMatricula){
                filtros.push({property: 'filiado_codigo', value: filtroMatricula});
            }

            if(filtroNome){
                filtros.push({property: 'filiado_nome', value: filtroNome});
            }

            if(filtros.length){
                store.clearFilter( true );
                store.filter(filtros);
                me.filtrado = true;
            }
            else if(filtrado){
                store.clearFilter();
                me.filtrado = false;
            }
        }
    }
} );