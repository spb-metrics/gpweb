/*********************************************************************************************************************
 ** Copyright (C) 2008 Sistema GP-Web Ltda - ME.
 ** Contato: http://www.sistemagpweb.com.br
 **          sac@sistemagpweb.com.br
 **
 ** Este arquivo é parte do sistema GPWeb Profissional.
 ** Este software esta registrado no INPI sob o número RS 11802-5 e protegido pelo direito de autor.
 **
 ** É expressamente proibido utilizar este código em parte ou integralmente sem o expresso consentimento do autor.
 **
 ** Usuário: Evandro
 **    Data: 20/08/2015
 /********************************************************************************************************************/

Ext.define( 'GPWERP.view.filiados.ListaFiliados', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.gpw-lista-filiados',

    requires: [
        'Ext.ux.grid.plugin.GridTooltip',
        'GPWERP.store.filiados.Filiados',
        'GPWERP.view.filiados.FiliadoTip',
        'GPWERP.view.filiados.PainelFiliado'
    ],

    verticalScrollerType       : 'paginggridscroller',
    invalidateScrollerOnRefresh: false,
    disableSelection           : true,

    /**
     * Inicializa o componente e configura a instância da classe
     */
    initComponent: function() {
        var me  = this,
            leg = GPWeb.util.Legendas;

        //se não foi fornecida uma store na declaração, criamos a nossa
        if( !me.store ) {
            me.store = Ext.create( 'GPWERP.store.filiados.Filiados', { autoLoad: true } );

            //como a store é criada pelo componente também deve ser destruída com ele
            me.destroyStore = true;
        }

        Ext.applyIf( me, {
            sortableColumns: true,

            viewConfig: {
                markDirty : false,
                selModel  : {
                    mode: 'SINGLE'
                },
                stripeRows: true
            },

            normalViewConfig: {
                loadingText   : 'Lendo os dados...',
                emptyText     : '<div class="gpw-empty-grid">Nenhum filiado encontrado.</div>',
                deferEmptyText: false
            },

            columns: [
                {
                    xtype    : 'rownumberer',
                    resizable: false,
                    width    : 40,
                    maxWidth : 40,
                    minWidth : 40,
                    draggable: false,
                    text     : 'Nº',
                    sortable : false,
                    hideable : true,
                    locked   : true,
                    menuText : 'Número',
                    tooltip  : 'Número ordinal de linhas na lista.',
                    gridTip  : true
                },
                {
                    xtype       : 'actioncolumn',
                    text        : 'Ações',
                    width       : 48,
                    maxWidth    : 48,
                    minWidth    : 48,
                    resizable   : false,
                    sortable    : false,
                    hideable    : false,
                    draggable   : false,
                    locked      : true,
                    menuDisabled: true,
                    tooltip     : 'Esta coluna contém as ações possíveis diretamente da lista.',
                    items       : [
                        {
                            iconCls : 'gpw-icon gpw-link iconeEditar',
                            tooltip : Ext.htmlEncode(
                                'Clique neste ícone <span class="gpw-icon iconeEditar"></span> para editar os detalhes do filiado' ),
                            action  : 'editar',
                            getClass: function( value, meta, record ) {
                                //if( !record.get( 'editar' ) ) return 'x-hide-display';
                                return 'gpw-icon gpw-link iconeEditar';
                            },
                            handler : me.onEditarFiliado,
                            scope   : me
                        },
                        {
                            iconCls : 'gpw-icon gpw-link iconeDeletar',
                            tooltip : Ext.htmlEncode(
                                'Clique neste ícone <span class="gpw-icon iconeDeletar"></span> para excluir o filiado' ),
                            action  : 'excluir',
                            getClass: function( value, meta, record ) {
                                //if( !record.get( 'excluir' ) ) return 'x-hide-display';
                                return 'gpw-icon gpw-link iconeDeletar';
                            },
                            handler : me.onExcluirFiliado,
                            scope   : me
                        }
                    ]
                },
                {
                    text     : leg.getCapitalizada( 'organizacao' ),
                    dataIndex: 'cia_nome',
                    minWidth : 150,
                    tooltip  : leg.apply( 'Nome d{genero_organizacao} {organizacao:capitalize} do filiado.' ),
                    flex     : 1,
                    gridTip  : {
                        scope: me,
                        fn: me.onCiaToolTip
                    }
                },
                {
                    text     : leg.apply( 'Mat. {organizacao:capitalize}' ),
                    dataIndex: 'filiado_id',
                    width    : 100,
                    minWidth : 50,
                    tooltip  : leg.apply(
                        'Número da matrícula do filiado n{genero_organizacao} {organizacao:capitalize}.' ),
                    gridTip  : true
                },
                {
                    text     : 'Mat. Sindicato',
                    width    : 100,
                    minWidth : 50,
                    dataIndex: 'filiado_codigo',
                    tooltip  : 'Número da matrícula do filiado no sindicato.',
                    gridTip  : true
                },
                {
                    text     : 'Nome',
                    dataIndex: 'filiado_nome',
                    gridTip  : true,
                    hideable : false,
                    minWidth : 150,
                    tooltip  : 'Nome do filiado.',
                    flex     : 1
                },
                {
                    text     : 'Situação',
                    dataIndex: 'filiado_atualizado',
                    width    : 55,
                    minWidth : 55,
                    resizable: false,
                    tooltip  : 'Situação do filiado',
                    gridTip  : true,
                    sortable : false,
                    renderer : function( value ) {
                        if( value ) {
                            return 'Ativo';
                        }

                        return 'Inativo'
                    }
                },
                {
                    text     : 'Filiado?',
                    dataIndex: 'filiado_filiado',
                    width    : 40,
                    minWidth : 40,
                    resizable: false,
                    tooltip  : 'Informação se é filiado ou não',
                    gridTip  : true,
                    renderer : function( value ) {
                        if( value ) {
                            return 'Sim';
                        }

                        return 'Não'
                    }
                }
            ],

            plugins: [
                {
                    ptype       : 'gridtooltip',
                    dismissDelay: 0,
                    columnMode  : true,
                    showDelay   : 1000,
                    tpl         : GPWERP.view.filiados.FiliadoTip.tplTip
                }
            ],

            listeners: {
                scope       : me,
                itemdblclick: me.onVerFiliado
            }
        } );


        this.callParent( arguments );
    },

    /**
     * Abre a janela de detalhes do filiado em modo de visualização.
     */
    onVerFiliado: function( view, record ) {
        var janela = Ext.create( 'Ext.window.Window', {
            title      : 'Detalhes do Filiado',
            autoShow   : true,
            modal      : true,
            layout     : 'fit',
            width      : 800,
            height     : 600,
            maximizable: true,
            maximized  : true,
            edicao     : false,

            items: {
                xtype    : 'gpw-painel-filiado',
                record   : record,
                editar   : false,
                listeners: {
                    scope   : this,
                    cancelar: function() {
                        janela.close();
                    }
                }
            }
        } );
    },

    /**
     * Abre a janela de detalhes do filiado em modo de edição.
     */
    onEditarFiliado: function( view, rowIndex, colIndex, item, e, record ) {
        var janela = Ext.create( 'Ext.window.Window', {
            title      : 'Detalhes do Filiado',
            autoShow   : true,
            modal      : true,
            layout     : 'fit',
            width      : 800,
            height     : 600,
            maximizable: true,
            maximized  : true,

            items: {
                xtype    : 'gpw-painel-filiado',
                record   : record,
                editar   : true,
                listeners: {
                    scope   : this,
                    cancelar: function() {
                        janela.close();
                    }
                }
            }
        } );
    },

    /**
     * Remove um registro do sistema.
     *
     * É solicitada a confirmação.
     */
    onExcluirFiliado: function( view, rowIndex, colIndex, item, e, record ) {
        var me = this;

        Ext.Msg.confirm(
            'Excluir Filiado',
            'Deseja remover definitivamente o filiado <b>' + record.get( 'filiado_nome' ) + '</b>?',
            function( btn ) {
                if( btn == 'yes' ) {
                    me.mask( 'Excluindo os dados...', 'loading' );
                    record.destroy( {
                        callback: function( records, op ) {
                            me.unmask();
                            if( op.success ) {
                                me.store.remove( record );
                            }
                            else {
                                Ext.Msg.alert( 'Erro',
                                    'Não foi possível excluir o filiado.<br/>Tente novamente mais tarde e se o problema persistir contate o administrador do sistema' );
                            }
                        }
                    } );
                }
            },
            me
        );

    },

    onCiaToolTip: function( cmp, tip, record ) {
        var me    = this,
            ciaId = record.get( 'filiado_cia_id' );

        if( !ciaId ) {
            return false;
        }

        GPWeb.componentes.CiaTip.load( tip, ciaId );
    }
} );
