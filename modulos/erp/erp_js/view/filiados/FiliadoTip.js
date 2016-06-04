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

Ext.define( 'GPWERP.view.filiados.FiliadoTip', {
    requires : [
        'Ext.XTemplate',
        'GPWERP.model.filiados.Filiado'
    ],

    singleton: true,

    /**
     * @property {Ext.XTemplate} tplTip
     * Template padrão do tooltip
     */
    tplTip: Ext.create('Ext.XTemplate',[
            '<b>Detalhes do Filiado</b><br/><br/>',
            '<table class="gpw-tooltip-table">',
            '<tpl if="filiado_nome">',
                '<tr><td class="gpw-tooltip-label">Nome Filiado</td><td>{filiado_nome}</td></tr>',
            '</tpl>',
            '<tpl if="filiado_codigo">',
                '<tr><td class="gpw-tooltip-label">Mat. Sindicato</td><td>{filiado_codigo}</td></tr>',
            '</tpl>',
            '<tpl if="filiado_email">',
                '<tr><td class="gpw-tooltip-label">E-Mail</td><td>{filiado_email}</td></tr>',
            '</tpl>',
            '<tr><td colspan=2 style="white-space: nowrap;"><br/><b><i>',
                'Duplo clique para ver os detalhes.',
            '</i></b></td></tr>',
            '</table>'
        ]),

    /**
     * Método utilizado para montar o conteúdo do tooltip utilizando ajax para buscar os dados no servidor
     *
     * @param tip Instância do objeto tooltip no qual os dados serão mostrados
     * @param id Id do filiado o qual os dados serão solicitados
     */
    load: function( tip, id ) {
        tip.update( 'Lendo os dados...' );

        //carrega os dados da demanda
        GPWERP.model.filiados.Filiado.load( id, {
            scope  : this,
            success: function( record ) {
                if( tip.isHidden() ) return;
                tip.update( GPWERP.view.filiados.FiliadoTip.tplTip.apply( record.data ) );
                tip.doLayout();
            },

            failure: function() {
                if( tip.isHidden() ) return;
                tip.update( 'Filiado não encontrado' );
            }
        } );
    }
} );