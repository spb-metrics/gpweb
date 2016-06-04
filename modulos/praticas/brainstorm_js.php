<!--
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
!-->

<!--********************************************************************************************
		
gpweb\modulos\praticas\brainstorm_js.php		

Funções javascript utilizadas em gpweb\modulos\praticas\brainstorm.php																																							
																																												
******************************************************************************************** !-->

<script type="text/javascript">

function checar_existe(lista, chave){
	//checar se já existe
	var existe=0;
	for(var j=0; j <lista.options.length; j++) { 
		if (lista.options[j].value==chave) {
			existe=1;
			break;
			}
		}
	return existe;
	}

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('brainstorm_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('brainstorm_cia').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function popTarefa() {
	if (!codigo.projetos_escolhidos.value) alert('Necessário selecionar projeto primeiro');
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&projeto_id='+document.getElementById('projetos_escolhidos').value+'&cia_id='+document.getElementById('brainstorm_cia').value, 'Tarefas','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('brainstorm_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('brainstorm_cia').value, 'Prática','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Indicador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('brainstorm_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('brainstorm_cia').value, 'Indicador','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
	
function setProjeto(chave, valor){
	if (!checar_existe(document.getElementById("projetos_escolhidos"), chave)){
		var opcao = document.createElement("OPTION");
		opcao.text = valor
		opcao.value = chave;
		document.getElementById("projetos_escolhidos").options.add(opcao);
		}
	}

function setTarefa(chave, valor){
	if (!checar_existe(document.getElementById("tarefas_escolhidas"), chave)){
		var opcao = document.createElement("OPTION");
		opcao.text = valor
		opcao.value = chave;
		document.getElementById("tarefas_escolhidas").options.add(opcao);
		}
	}

function setIndicador(chave, valor){
	if (!checar_existe(document.getElementById("indicadores_escolhidos"), chave)){
		var opcao = document.createElement("OPTION");
		opcao.text = valor
		opcao.value = chave;
		document.getElementById("indicadores_escolhidos").options.add(opcao);
		}
	}

function setPratica(chave, valor){
	if (!checar_existe(document.getElementById("praticas_escolhidas"), chave)){
		var opcao = document.createElement("OPTION");
		opcao.text = valor
		opcao.value = chave;
		document.getElementById("praticas_escolhidas").options.add(opcao);
		}
	}



function popProjeto_filtro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projto"])?>', 600, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto_filtro&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto_filtro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto_filtro&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}


function setProjeto_filtro(chave, valor){
	frm_filtro.projeto_id.value=chave;
	frm_filtro.submit();
	}


function popPratica_filtro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica_filtro&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica_filtro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica_filtro&tabela=praticas&cia_id='+document.getElementById('cia_id').value, 'Prática','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica_filtro(chave, valor){
	frm_filtro.pratica_id.value=chave;
	frm_filtro.submit();
	}
	
function popIndicador_filtro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Indicador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador_filtro&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, window.setIndicador_filtro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador_filtro&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setIndicador_filtro(chave, valor){
	frm_filtro.pratica_indicador_id.value=chave;
	frm_filtro.submit();
	}
	
	
function Mover(ListaDE,ListaPARA) {
	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value != "0") {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text;
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) { 
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA.options[ListaPARA.options.length] = no;		
				}
			}
		}
	}

function Mover2(ListaPARA) {
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "0") {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista
function selecionar(nome,campo) {
	var lista=document.getElementById(nome);
	
	var saida='';
	for (var i=0; i < lista.length ; i++) {
		if (lista.options[i].value) saida+=','+lista.options[i].value;
		}
	document.getElementById(campo).value=saida.substr(1);	
	}



var vetor_observacao=Array();

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&usuarios_id_selecionados='+usuarios_id_selecionados, '<?php echo ucfirst($config["usuarios"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.getElementById('brainstorm_usuarios').value = usuario_id_string;
	contatos_id_selecionados = usuario_id_string;
	}
function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.getElementById('brainstorm_depts').value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	}

function mudar_dept(){
	xajax_exibir_dept('brainstorm_dept', 'dept_cia='+document.getElementById('brainstorm_cia').value, 'style=\'width:200px;\' size=\'1\' class=\'texto\'', 'brainstorm_dept', '', true);
	}

function mudar_projeto(){
	xajax_exibir_combo('brainstorm_projeto', 'projetos',  'projeto_id', 'projeto_nome', 'projeto_cia='+document.getElementById('brainstorm_cia').value, '', 'onchange=\'mudar_tarefa();\' style=\'width:250px;\' size=\'3\' class=\'texto\' ondblclick=\'Mover(document.codigo.lista_projetos, document.codigo.projetos_escolhidos);\'', 'lista_projetos', '', true,'','','','');
	}


function mudar_tarefa(){
	xajax_exibir_combo('brainstorm_tarefa', 'tarefas',  'tarefa_id', 'tarefa_nome', 'tarefa_projeto='+document.getElementById('lista_projetos').value, 'tarefa_inicio', 'style=\'width:250px;\' size=\'3\' class=\'texto\' ondblclick=\'Mover(document.codigo.lista_tarefas, document.codigo.tarefas_escolhidas);\'', 'lista_tarefas', '', true,'','','','');
	}


function mudar_indicador(){
	xajax_exibir_combo('brainstorm_indicador', 'pratica_indicador',  'pratica_indicador_id', 'pratica_indicador_nome', 'pratica_indicador_cia='+document.getElementById('brainstorm_cia').value, '', 'style=\'width:250px;\' size=\'3\' class=\'texto\' ondblclick=\'Mover(document.codigo.lista_indicadores, document.codigo.indicadores_escolhidos);\'', 'lista_indicadores', '', true,'','','','');
	}

function mudar_pratica(){
	xajax_exibir_combo('brainstorm_pratica', 'praticas',  'pratica_id', 'pratica_nome', 'pratica_cia='+document.getElementById('brainstorm_cia').value, '', 'style=\'width:250px;\' size=\'3\' class=\'texto\' ondblclick=\'Mover(document.codigo.lista_praticas, document.codigo.praticas_escolhidas);\'', 'lista_praticas', '', true,'','','','');
	}


function nodeSelect_handle(sender,arg){	
	var treenode = treeview.getNode(arg.NodeId);
	var observacao = treenode.getData("observacao");	
	//sobrescreve com o dado recente
	if (vetor_observacao[arg.NodeId])observacao=vetor_observacao[arg.NodeId];
	if(observacao) document.getElementById('observacao').innerHTML = "<br><table cellspacing=3 cellpadding=3 border=1><tr><td><b>" + observacao + "</b></td></tr></table>";
	else document.getElementById('observacao').innerHTML = "";
	}

function atualizar_obs(){
	var _ids_selecionados = treeview.getSelectedIds();
	var treenode = treeview.getNode(_ids_selecionados[0]);
	
	var observacao = treenode.getData("observacao");	
	//sobrescreve com o dado recente
	if (vetor_observacao[_ids_selecionados[0]])observacao=vetor_observacao[_ids_selecionados[0]];
	if(observacao) document.getElementById('observacao').innerHTML = "<br><table cellspacing=3 cellpadding=3 border=1><tr><td><b>" + observacao + "</b></td></tr></table>";
	else document.getElementById('observacao').innerHTML = "";
	}




function editar(){
	var _ids_selecionados = treeview.getSelectedIds();
	var treenode = treeview.getNode(_ids_selecionados[0]);
	var observacao = treenode.getData("observacao");	


	//sobrescreve com o dado recente
	if (vetor_observacao[_ids_selecionados[0]])observacao=vetor_observacao[_ids_selecionados[0]];

	codigo.nome.value=treenode.getText();
	codigo.campo_observacao.value=observacao;
	document.getElementById('inserir_nodulo').style.display="";
	document.getElementById('confirmar_alteracao').style.display="";
	
	document.getElementById('dados_diagrama').style.display="none";

	}


function alterar_nodulo(){
	var _ids_selecionados = treeview.getSelectedIds();
	var treenode = treeview.getNode(_ids_selecionados[0]);

	var nome  = codigo.nome.value;
	var observacao  = codigo.campo_observacao.value;
	if (nome!=""){
		treenode.setText(nome);
		if (observacao)	vetor_observacao[_ids_selecionados[0]]=observacao;
		}
	else alert('Necessário ter um texto!');	
	esconder();
	}


//poder mostrar dados extras ao selecionar nodulo
treeview.registerEvent("OnSelect",nodeSelect_handle);
	
function adicionar(){
	document.getElementById("botaos_criar").style.display="none";
	codigo.nome.value="";
	codigo.campo_observacao.value="";	
	document.getElementById("inserir").style.display="";
	document.getElementById("inserir_nodulo").style.display="";
	document.getElementById('confirmar_adicao').style.display="";
	document.getElementById('dados_diagrama').style.display="none";
	}

function esconder(){
	document.getElementById("botaos_criar").style.display="";
	codigo.nome.value="";
	codigo.campo_observacao.value="";
	document.getElementById("inserir_nodulo").style.display="none";
	document.getElementById('confirmar_adicao').style.display="none";
	document.getElementById('confirmar_alteracao').style.display="none";
	}
	
function expandir_nodulos_selecionados(){
	var _ids_selecionados = treeview.getSelectedIds();
	for(var i=0;i<_ids_selecionados.length;i++){
		var _tree_node = treeview.getNode(_ids_selecionados[i]);
		if (_tree_node.isExpanded()){
			_tree_node.collapse();
			}
		else{
			_tree_node.expand();
			}
		}
	}

function expandir_nodulos(){
	treeview.expandAll();
	}

//Remove todos os nodulos selecionados
function remover_nodulos_selecionados(){
	var _ids_selecionados = treeview.getSelectedIds();
	for(var i=0;i<_ids_selecionados.length;i++){
		treeview.removeNode(_ids_selecionados[i]);
		}		
	}
	
function adicionar_nodulo(){
	var _node_name  =codigo.nome.value;
	var _observacao  = codigo.campo_observacao.value;
	if (_node_name!=""){
		//pega lista de nodulos selecionados
		var _ids_selecionados = treeview.getSelectedIds();
		//se nenhum nodulo selecionado, adiciona a treeview.root
		if (_ids_selecionados.length==0){
			_count++;  
			var nodulo=treeview.getNode("treeview.root").addChildNode("nodulo"+_count,_node_name,"lib/coolcss/CoolControls/CoolTreeView/icons/ball_glass_greenS.gif");
			}
		else{
			//se existe nodulos selecionados, adicionar o novo neles
			for(var i=0;i<_ids_selecionados.length;i++){
				_count++;
				treeview.getNode(_ids_selecionados[i]).addChildNode("nodulo"+_count,_node_name, "lib/coolcss/CoolControls/CoolTreeView/icons/"+(verde_vermelho(_ids_selecionados[i])? 'ball_glass_redS.gif': 'ball_glass_greenS.gif'));
				vetor_observacao["nodulo"+_count]=_observacao;
				}
			}
		}
	esconder();			
	}




	
function deselecionar_todos_nodulos(){
	//Unselect all
	treeview.unselectAll();
	}
	
	
var objeto_passar='';
var s='';
var i=0;


function verde_vermelho(nodulo){
	var qnt=0;
	var atual = treeview.getNode(nodulo);

	while(atual.NodeId !='treeview.root'){
		qnt++;
		nodulo = treeview.getNode(nodulo).getParentId();
		atual = treeview.getNode(nodulo);
		}
	return (qnt%2);	
	}

function dados_filhos(pai){
	var ids_filhos = treeview.getNode(pai).getChildIds();
	
	for(var i=0;i<ids_filhos.length;i++) {
		var linha_vetor=Array();
		if (pai=='treeview.root') pai='root';
		objeto_passar+=',{"pai" : "'+pai+'", "filho" : "'+ids_filhos[i]+'", "texto" : "'+treeview.getNode(ids_filhos[i]).getText()+'", "obs" : "'+(vetor_observacao[ids_filhos[i]] ? vetor_observacao[ids_filhos[i]] : treeview.getNode(ids_filhos[i]).getData('observacao'))+'"}'; 
		dados_filhos(ids_filhos[i]);
		}
	}

function salvar_brainstorm(){
	objeto_passar='{"pai" : "root", "filho" : "root", "texto" : "'+treeview.getNode('treeview.root').getText()+'", "obs" : "'+(vetor_observacao['treeview.root'] ? vetor_observacao['treeview.root'] : treeview.getNode('treeview.root').getData('observacao'))+'"}'; 
	dados_filhos('treeview.root');
	objeto_passar='['+objeto_passar+']';
	document.codigo.conteudo.value=objeto_passar;
	
	
	if(!document.codigo.brainstorm_nome.value)alert('O diagrama necessita ter um nome!');
	else {
		selecionar('projetos_escolhidos','projetos_ocultos');
		selecionar('tarefas_escolhidas','tarefas_ocultas');
		selecionar('indicadores_escolhidos','indicadores_ocultos');
		selecionar('praticas_escolhidas','praticas_ocultas');
		document.codigo.submit();
		} 
	}	
	
</script>