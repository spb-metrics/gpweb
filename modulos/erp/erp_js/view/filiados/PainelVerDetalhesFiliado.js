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

Ext.define( 'GPWERP.view.filiados.PainelVerDetalhesFiliado', {
    extend: 'GPWeb.componentes.form.Panel',

    alias: 'widget.gpw-painel-ver-detalhes-filiado',

    requires: [
        'GPWeb.componentes.form.field.VerField',
        'GPWeb.componentes.CiaTip'
    ],

    //minWidth   : 600,
    bodyPadding: 10,
    layout     : 'anchor',
    autoScroll : true,

    /**
     * Inicializa o componente
     */
    initComponent: function() {
        var me   = this,
            pref = GPWeb.util.PreferenciaUsuario,
            leg  = GPWeb.util.Legendas;

        Ext.apply( me, {
            border: 0,

            defaults: {
                labelAlign: 'right'
            },

            defaultType: 'gpw-verfield',

            items: [
                {
                    fieldLabel: 'Nome',
                    name      : 'filiado_nome',
                    tooltip   : 'Nome do filiado'
                },
                {
                    fieldLabel: leg.getCapitalizada( 'organizacao' ),
                    name      : 'cia_nome',
                    tooltip   : leg.apply( 'Selecione {genero_organizacao} {organizacao} do filiado.' ),
                    inputTip  : {
                        scope: me,
                        fn: me.onCiaToolTip
                    }
                },
                {
                    fieldLabel: 'Código',
                    name      : 'filiado_codigo',
                    tooltip   : leg.apply( 'Código do filiado n{genero_organizacao} {organizacao}' )
                },
                {
                    fieldLabel: 'Filiado',
                    name      : 'filiado_filiado',
                    tooltip   : 'Informa se está filiado',
                    renderer  : function( value ) {
                        if( !!value ) {
                            return 'Sim'
                        }

                        return 'Não';
                    }
                },
                {
                    fieldLabel: 'Sexo',
                    name      : 'filiado_sexo',
                    tooltip   : 'O sexo do filiado',
                    renderer  : function( value ) {
                        if( value == 'F' ) {
                            return 'Feminino'
                        }

                        return 'Masculino';
                    }
                },
                {
                    fieldLabel: 'CPF',
                    name      : 'filiado_cpf',
                    tooltip   : 'O CPF do filiado'
                },
                {
                    fieldLabel: 'RG',
                    name      : 'filiado_rg',
                    tooltip   : 'O RG do filiado'
                },
                {
                    fieldLabel: 'Org. Expedidor',
                    name      : 'filiado_rg_expedidor',
                    tooltip   : 'O orgão expedidor do RG do filiado'
                },
                {
                    fieldLabel: 'E-mail',
                    name      : 'filiado_email',
                    tooltip   : 'O e-mail filiado'
                },
                {
                    fieldLabel: 'Data Nascimento',
                    name      : 'filiado_data_nasc',
                    tooltip   : 'A data de nascimento do filiado',
                    renderer  : function( value ) {
                        if( !value ) return '';
                        var data = new Date( value );
                        return Ext.Date.format( data, pref.get( 'datacurta', 'd/m/Y' ) );
                    }
                },
                {
                    fieldLabel: 'Estado Civil',
                    name      : 'filiado_estado_civil',
                    tooltip   : 'O estado civil do filiado'
                },
                {
                    fieldLabel: 'Ativo',
                    name      : 'filiado_atualizado',
                    tooltip   : 'Identifica se o filiado está ativo',
                    renderer  : function( value ) {
                        if( !!value ) {
                            return 'Sim'
                        }

                        return 'Não';
                    }
                },
                {
                    fieldLabel: 'Observações',
                    name      : 'filiado_observacoes',
                    minWidth  : 600,
                    anchor    : '100%',
                    htmlEncode: false
                }
            ]
        } );

        me.callParent( arguments );
    },

    onCiaToolTip: function(cmp, value, tip, cfgTip){
        var me = this,
            ciaId = me.record.get('filiado_cia_id');

        if(!ciaId){
            return false;
        }

        GPWeb.componentes.CiaTip.load(tip, ciaId);
    }
} );
