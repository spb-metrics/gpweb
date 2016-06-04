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
 **    Data: 30/08/2015
 /********************************************************************************************************************/

/**
 * Painel de visualização/edição de dados dos filiados.
 */
Ext.define( 'GPWERP.view.filiados.PainelFiliado', {
    extend: 'Ext.tab.Panel',

    alias: 'widget.gpw-painel-filiado',

    requires: [
        'GPWERP.model.filiados.Filiado',
        'GPWERP.view.filiados.PainelEditarDetalhesFiliado',
        'GPWERP.view.filiados.PainelVerDetalhesFiliado'
    ],

    layout        : 'fit',
    deferredRender: true,
    record        : null,
    editar        : false,
    novoRegistro  : false,

    /**
     * Inicializa o componente.
     */
    initComponent: function() {
        var me = this;

        me.addEvents( 'salvo', 'cancelar' );

        //se nenhum registro foi fornecido cria um
        if( !me.record ) {
            me.record = Ext.create( 'GPWERP.model.filiados.Filiado' );
        }

        Ext.apply( me, {
            tbar: [
                {
                    itemId : 'btnSalvar',
                    text   : 'Salvar',
                    iconCls: 'iconeSalvar',
                    tooltip: 'Salvar os dados modificados.',
                    handler: me.onSalvar,
                    scope  : me
                },
                {
                    itemId : 'btnCancelar',
                    text   : 'Cancelar',
                    iconCls: 'iconeSair',
                    tooltip: 'Cancelar a edição.',
                    handler: me.onCancelar,
                    scope  : me
                },
                '->',
                {
                    itemId : 'btnVerEditar',
                    text   : !me.editar ? 'Editar' : 'Ver',
                    iconCls: !me.editar ? 'iconeEditar' : 'iconeVer',
                    hidden : !!me.novoRegistro,
                    handler: me.onAlternarEdicao,
                    scope  : me
                }
            ],

            defaults: {
                layout: 'fit'
            },

            items: [
                {
                    title  : 'Filiado',
                    tooltip: 'Detalhes do Filiado',
                    items  : [
                        {
                            xtype : 'gpw-painel-editar-detalhes-filiado',
                            itemId: 'editarDetalhesFiliado',
                            record: me.record
                        },
                        {
                            xtype : 'gpw-painel-ver-detalhes-filiado',
                            itemId: 'verDetalhesFiliado',
                            record: me.record
                        }
                    ]
                },
                { title: 'Empresa', html: '<h1>Aqui devem ir os demais campos</h1>' },
                { title: 'Pecúlio', html: '<h1>Aqui devem ir os demais campos</h1>' },
                { title: 'Licitação', html: '<h1>Aqui devem ir os demais campos</h1>' },
                { title: 'Filiação', html: '<h1>Aqui devem ir os demais campos</h1>' },
                { title: 'Carteira', html: '<h1>Aqui devem ir os demais campos</h1>' },
                { title: 'Outros Dados', html: '<h1>Aqui devem ir os demais campos</h1>' },
                { title: 'Dependentes', html: '<h1>Aqui devem ir os demais campos</h1>' },
                { title: 'Arquivos', html: '<h1>Aqui devem ir os demais campos</h1>' },
                { title: 'Histório de atendimento', html: '<h1>Aqui devem ir os demais campos</h1>' },
            ]
        } );

        me.callParent( arguments );
    },

    /**
     * Método chamado quando o componente foi renderizado no navegador.
     */
    afterRender: function() {
        var me = this;
        me._acertarVerEditar();
        me.callParent( arguments );
    },

    /**
     * Verifica se todos os dados dos formulários estão em um estado válido.
     *
     * Se houverem dados inválidos uma notificação é emitida e o foco vai para o primeiro campo inválido.
     *
     * @returns {boolean} true se válidos, false caso contrário.
     */
    validarDados: function() {
        var me       = this,
            detalhes = me.down( '#editarDetalhesFiliado' ),
            invalidFields;

        if( detalhes.hasInvalidField() ) {
            invalidFields = detalhes.getInvalidFields();
            Ext.Msg.alert( 'Aviso',
                'Alguns campos são inválidos.<br/>Por favor verifique e tente novamente.',
                function() {
                    me.setActiveTab( 0 );
                    invalidFields[0].focus( false, 10 );
                } );
            return false;
        }

        return true;
    },

    /**
     * Valida os dados e salva no sistema se necessário.
     *
     * Dispara o evento "salvo" se o registro salvou no sistema.
     *
     * @returns {boolean} true se os dados eram válidos, false caso contrário.
     */
    onSalvar: function() {
        var me       = this,
            detalhes = me.down( '#editarDetalhesFiliado' );

        if( !me.validarDados() ) return false;

        detalhes.updateRecord();

        if( me.record.dirty ) {
            Ext.getBody().mask( 'Salvando os dados', 'loading' );
            me.record.save( {
                callback: function( record, op ) {
                    Ext.getBody().unmask();
                    if( !!op.success ) {
                        me.fireEvent( 'salvo', me, record );
                        Ext.Msg.alert( 'Salvo', 'Os dados foram salvos com sucesso.' );
                        me.down( '#btnVerEditar' ).setVisible( true );
                        me.novoRegistro = false;
                    }
                    else {
                        record.reject();
                        Ext.Msg.alert( 'Erro',
                            'Não foi possível salvar os dados.<br/>Por favor entre em contato com o administrador do sistema.' );
                    }
                }
            } );
        }

        return true;
    },

    /**
     *
     */
    onCancelar: function() {
        var me       = this,
            detalhes = me.down( '#editarDetalhesFiliado' );

        detalhes.reset();

        me.fireEvent( 'cancelar', me, me.record );
    },

    /**
     * Verifica se algum dos formulários tem dados modificados.
     *
     * @returns {boolean}
     */
    hasModificacoes: function() {
        var me       = this,
            detalhes = me.down( '#editarDetalhesFiliado' );

        if( detalhes.isDirty() ) {
            return true;
        }

        return false;
    },

    /**
     * Método utilizado para consolidar os componentes conforme o modo de edição/visualização
     * @private
     */
    _acertarVerEditar: function() {
        var me             = this,
            editarDetalhes = me.down( '#editarDetalhesFiliado' ),
            verDetalhes    = me.down( '#verDetalhesFiliado' ),
            btnSalvar      = me.down( '#btnSalvar' ),
            btnCancelar    = me.down( '#btnCancelar' ),
            btnVerEditar   = me.down( '#btnVerEditar' );

        if( !me.editar ) {
            verDetalhes.loadRecord( me.record );
        }
        else {
            editarDetalhes.loadRecord( me.record );
        }


        editarDetalhes.setVisible( !!me.editar );
        verDetalhes.setVisible( !me.editar );

        btnCancelar.setVisible( !!me.editar );
        btnSalvar.setVisible( !!me.editar );


        btnVerEditar.setText( !me.editar ? 'Editar' : 'Ver' );
        btnVerEditar.setIconCls( !me.editar ? 'iconeEditar' : 'iconeVer' );
        btnVerEditar.setTooltip( !me.editar ? 'Editar os dados deste filiado' : 'Visualizar os dados deste filiado' );
    },

    /**
     * Alterna para o modo de visualização
     */
    iniciarVisualizacao: function() {
        var me = this;
        me.editar = false;
        me._acertarVerEditar();
    },

    /**
     * Alterna para o modo de edição
     */
    iniciarEdicao: function() {
        var me = this;
        me.editar = true;
        me._acertarVerEditar();
    },

    /**
     * Verifica se existem dados modificas nos formulários e pergunta se deseja salvar os mesmos.
     *
     * @param {Function} callback Método a ser chamado após a conclusão.
     */
    handleModificados: function( callback ) {
        var me = this;
        if( me.hasModificacoes() ) {
            Ext.Msg.show( {
                title  : 'Confirmar',
                msg    : 'Existem modificações não salvas.<br/>Deseja salvar agora?',
                buttons: Ext.Msg.YESNOCANCEL,
                icon   : Ext.Msg.QUESTION,

                fn: function( btn ) {
                    if( btn == 'cancel' ) {
                        return;
                    }

                    if( btn == 'yes' ) {
                        Ext.defer( function() {
                            if( !me.onSalvar() ) {
                                return;
                            }
                            if( !!callback ) callback.apply( me );
                        }, 10, me );

                        return;
                    }

                    if( !!callback ) callback.apply( me );

                }
            } );

            return;
        }

        if( !!callback ) callback.apply( me );
    },

    /**
     * Alterna entre os modos de edição e visualização.
     */
    onAlternarEdicao: function() {
        var me = this;

        if( me.editar ) {
            me.handleModificados( me.iniciarVisualizacao );
        }
        else {
            me.iniciarEdicao();
        }
    }
} );
