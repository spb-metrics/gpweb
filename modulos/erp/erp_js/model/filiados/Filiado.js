/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

Ext.define('GPWERP.model.filiados.Filiado',{
    extend: 'GPWeb.model.Base',

    requires: [
        'GPWeb.model.Cia'
    ],

    idProperty: 'filiado_id',

    fields: [
        { name: 'filiado_id', type: 'int'},
        { name: 'filiado_nome', type: 'string' },
        { name: 'filiado_cia_id', type: 'int'},
        { name: 'filiado_codigo', type: 'string' },
        { name: 'filiado_filiado', type: 'int', defaultValue: 1 },
        { name: 'filiado_sexo', type: 'string', defaultValue: 'M' },
        { name: 'filiado_cpf', type: 'string' },
        { name: 'filiado_rg', type: 'string' },
        { name: 'filiado_rg_expedidor', type: 'string' },
        { name: 'filiado_email', type: 'string' },
        { name: 'filiado_data_nasc', type: 'date' },
        { name: 'filiado_estado_civil', type: 'string', defaultValue: 'Solteiro' },
        { name: 'filiado_atualizado', type: 'int', defaultValue: 1 },
        { name: 'filiado_observacoes'},

        { name: 'cia_nome', type: 'string', persist: false}
    ],

    proxy: {
        type: 'ajax',
        extraParams:{ m: 'erp', a: 'filiado_pro', sem_cabecalho: 1 },
        api: {
            create  : 'index.php?f=criarFiliados',
            read    : 'index.php?f=lerFiliados',
            update  : 'index.php?f=atualizarFiliados',
            destroy : 'index.php?f=excluirFiliados'
        },
        reader:{
            type: 'json',
            root: 'filiados'
        },
        writer:{
            type: 'json',
            writeAllFields: true,
            encode: true,
            allowSingle: false,
            root: 'filiados',
            timeout: 30000000
        }
    },

    associations: [
        {
            type          : 'hasOne',
            model         : 'GPWeb.model.Cia',
            associationKey: 'cia',
            name          : 'cia',
            instanceName  : 'cia',
            primaryKey    : 'cia_id',
            foreignKey    : 'filiado_cia_id',
            getterName    : 'getCia',
            setterName    : 'setCia'
        }
    ]
});