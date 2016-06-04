Ext.define('GPWERP.controller.GPWMain', {
    extend: 'Ext.app.Controller',
    require:[
        'GPWERP.view.filiados.PainelConsulta'
    ],

    refs: [
        {
            ref: 'painelCentral',
            selector: '#pnCentro'
        }
    ],

    init: function(application) {
        var me = this;

        me.control({
            //isto garante que o menu é mostrado quando o mouse passa por cima
            //e que se esconde quando sai
            "#erpMenuPrincipal button": {
                'mouseover': function(btn) {
                    btn.showMenu();
                },
                'click': me.onMenuButtonClick
            },

            "#erpMenuPrincipal menu": {
                'mouseleave': function(menu) {
                    menu.hide();
                },
                'click': me.onMenuItemClick
            }
        });
    },

    /**
    * Método chamado pelo roteamento 'inicio'
    *
    * @param params
    */
    gpwInicio: function(params){
        var me = this,
            pn = me.getPainelCentral();

        if(!!pn) pn.removeAll(true);
    },

    /**
    * Método chamado pelo roteamento 'filiados/consultar'
    *
    * @param params
    */
    gpwFiliadosConsultar: function(params){
        var me = this,
            pn = me.getPainelCentral();

        pn.removeAll(true);
        pn.add(Ext.create('GPWERP.view.filiados.PainelConsulta'));
    },

    gpwAtendimentoOdontologico: function(params){
        var me = this,
            pn = me.getPainelCentral();

        pn.removeAll(true);
        pn.add(Ext.create('GPWERP.view.atendimento.PainelOdontologico'));
    },

    onMenuItemClick: function(menu, item){
        if(!!item.menu) return;
        gpwerp.navegar(item.getItemId());
    },

    onMenuButtonClick: function(btn){
        if(!!btn.menu) return;

        gpwerp.navegar(btn.getItemId());
    }
});