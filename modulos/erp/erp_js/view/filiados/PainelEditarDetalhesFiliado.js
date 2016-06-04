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

Ext.define( 'GPWERP.view.filiados.PainelEditarDetalhesFiliado', {
    extend: 'GPWeb.componentes.form.Panel',

    alias: 'widget.gpw-painel-editar-detalhes-filiado',

    requires: [
        'GPWeb.componentes.form.field.CpfField',
        'GPWeb.componentes.form.field.CaixaTexto',
        'GPWeb.componentes.ComboCias'
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
            cfg  = GPWeb.util.Configuracao,
            pref = GPWeb.util.PreferenciaUsuario,
            leg  = GPWeb.util.Legendas;

        Ext.apply( me, {
            border: 0,

            defaults: {
                labelAlign      : 'right',
                enforceMaxLength: true,
                msgTarget       : 'side'
            },

            defaultType: 'textfield',

            items: [
                {
                    fieldLabel: 'Nome',
                    name      : 'filiado_nome',
                    width     : 580,
                    allowBlank: false,
                    maxLength : 255,
                    tooltip   : 'Informe o nome do filiado'
                },
                {
                    xtype: 'hiddenfield',
                    name: 'cia_nome'
                },
                {
                    xtype     : 'gpw-combo-cias',
                    name      : 'filiado_cia_id',
                    fieldLabel: leg.getCapitalizada('organizacao'),
                    width     : 350,
                    allowBlank: false,
                    emptyText: leg.apply( 'Selecione {genero_organizacao} {organizacao}...' ),
                    tooltip   : leg.apply( 'Selecione {genero_organizacao} {organizacao} do filiado.' ),
                    listeners: {
                        scope: me,
                        select: me.onSelectCia
                    }
                },
                {
                    fieldLabel: 'Código',
                    name      : 'filiado_codigo',
                    width     : 185,
                    maxLength : 10,
                    tooltip   : leg.apply( 'Informe o código do filiado n{genero_organizacao} {organizacao}' )
                },
                {
                    xtype       : 'combobox',
                    fieldLabel  : 'Filiado',
                    name        : 'filiado_filiado',
                    width       : 155,
                    editable    : false,
                    tooltip     : 'Informe se está filiado',
                    queryMode   : 'local',
                    displayField: 'text',
                    valueField  : 'id',
                    store       : Ext.create( 'Ext.data.Store', {
                        fields: ['id', 'text'],
                        data  : [
                            { "id": 0, "text": "Não" },
                            { "id": 1, "text": "Sim" }
                        ]
                    } )
                },
                {
                    xtype       : 'combobox',
                    fieldLabel  : 'Sexo',
                    name        : 'filiado_sexo',
                    queryMode   : 'local',
                    tooltip     : 'Informe o sexo do filiado',
                    displayField: 'text',
                    valueField  : 'sexo',
                    editable    : false,
                    store       : Ext.create( 'Ext.data.Store', {
                        fields: ['sexo', 'text'],
                        data  : [
                            { "sexo": 'M', "text": "Masculino" },
                            { "sexo": 'F', "text": "Feminino" }
                        ]
                    } )
                },
                {
                    xtype     : 'gpw-cpffield',
                    fieldLabel: 'CPF',
                    name      : 'filiado_cpf',
                    width     : 220,
                    maxLength : 14,
                    tooltip   : 'Informe o CPF do filiado'
                },
                {
                    fieldLabel: 'RG',
                    name      : 'filiado_rg',
                    width     : 185,
                    maxLength : 10,
                    tooltip   : 'Informe o RG do filiado'
                },
                {
                    fieldLabel: 'Org. Expedidor',
                    name      : 'filiado_rg_expedidor',
                    width     : 185,
                    maxLength : 10,
                    tooltip   : 'Informe o orgão expedidor do RG do filiado'
                },
                {
                    vtype     : 'email',
                    fieldLabel: 'E-mail',
                    name      : 'filiado_email',
                    width     : 500,
                    maxLength : 255,
                    tooltip   : 'Informe o e-mail filiado'
                },
                {
                    xtype     : 'datefield',
                    fieldLabel: 'Data Nascimento',
                    name      : 'filiado_data_nasc',
                    width     : 200,
                    format    : pref.get( 'datacurta', 'd/m/Y' ),
                    tooltip   : 'Informe a data de nascimento do filiado'
                },
                {
                    xtype       : 'combobox',
                    fieldLabel  : 'Estado Civil',
                    name        : 'filiado_estado_civil',
                    width       : 200,
                    tooltip     : 'Informe o estado civil do filiado',
                    queryMode   : 'local',
                    displayField: 'text',
                    valueField  : 'text',
                    editable    : false,
                    store       : Ext.create( 'Ext.data.Store', {
                        fields: ['text'],
                        data  : [
                            { "text": "Solteiro" },
                            { "text": "Casado" },
                            { "text": "Divorciado" },
                            { "text": "Viúvo" }
                        ]
                    } )
                },
                {
                    xtype       : 'combobox',
                    fieldLabel  : 'Ativo',
                    name        : 'filiado_atualizado',
                    width       : 155,
                    tooltip     : 'Informe se o filiado está ativo',
                    queryMode   : 'local',
                    displayField: 'text',
                    valueField  : 'id',
                    editable    : false,
                    store       : Ext.create( 'Ext.data.Store', {
                        fields: ['id', 'text'],
                        data  : [
                            { "id": 0, "text": "Não" },
                            { "id": 1, "text": "Sim" }
                        ]
                    } )
                },
                {
                    xtype     : 'gpw-caixatexto',
                    fieldLabel: 'Observações',
                    name      : 'filiado_observacoes',
                    anchor    : '100%',
                    height    : 80,
                    minHeight : 80,
                    minWidth  : 580
                }
            ]
        } );

        me.callParent( arguments );
    },

    /**
     * Atualizamos o nome da organização para caso salvar o registro contenha o nome sem ler do servidor.
     * @param cb
     * @param value
     * @param record
     */
    onSelectCia: function(cb, value, record){
        var me = this,
            ciaNome = me.down('hiddenfield[name="cia_nome"]');

        if(record){
            ciaNome.setValue(record.get('nome'));
        }
    }
} );
