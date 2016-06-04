/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if(navigator.userAgent.indexOf('MSIE')!=-1){var undefined=null;}
navigator.family='ie';
if(window.navigator.userAgent.toLowerCase().match(/gecko/)){navigator.family='gecko';}
if(navigator.userAgent.toLowerCase().indexOf('opera')+1||window.opera){navigator.family='opera';}

function centro_janela(width,height){
	var ix=window.outerWidth;
	var iy=window.outerHeight;
	var mx=window.screenX;
	var my=window.screenY;
	var result;var cx;
	var cy;
	if(width<=0){
		width=ix;
		cx=mx;
		}
	else{
		mx+=(ix/2);
		mx-=(width/2);
		cx=Math.round(mx);
		}
	if(height<=0){
		cy=my;
		height=iy;
		}
	else{
		my+=(iy/2);
		my-=(height/2);
		cy=Math.round(my);
		}
	result='screenX='+cx+',screenY='+cy+',outerHeight='+height+',outerWidth='+width;
	return result;
	}

function CompItem(chave,data){
	this.chave=chave;
	this.data=data;
	this.compare=comp_chaves;
	this.equals=comp_igual;
	}
	
function comp_chaves(target){
	if(this.chave==target.chave)return 0;
	if(this.chave<target.chave)return-1;
	return 1;
	}

function comp_igual(target){
	if(this.chave==target)return true;
	return false;
	}
	
function Comparavel(){
	this.list=new Array();
	this.add=ca_adicionar;
	this.find=ca_achar;
	this.length=ca_largura;
	this.get=ca_get;
	this.search=ca_procurar;
	this.contagem=0;
	}

function ca_adicionar(chave,data){
	var last_id=this.search(chave);
	if(last_id!=-1){this.list[last_id]=new CompItem(chave,data);}
	else{
		this.list[this.contagem]=new CompItem(chave,data);
		this.contagem++;
		}
	}
	
function ca_achar(chave){
	var end=this.list.length;
	for(var i=0;i<end;i++){
		cp=this.list[i];
		if(cp.equals(chave)){return cp.data;}
		}
	return undefined;
	}
	
function ca_procurar(chave){
	var end=this.list.length;
	for(var i=0;i<end;i++){
		cp=this.list[i];
		if(cp.equals(chave)){return i;}
		}
	return-1;
	}

function ca_largura(){
	return this.list.length;
	}
	
function ca_get(id){
	return this.list[id];
	}
	
function HTMLex(){
	this.adTabela=_HTMLadTabela;
	this.addRow=_HTMLadLinha;
	this.addHeader=_HTMLadCabecalho;
	this.addHeaderNode=_HTMLadNoduloCabecalho;
	this.adicionaCelula=_HTMLadCelula;
	this.adicionaCelulaNode=_HTMLadCelulaNodulo;
	this.addTextInput=_HTMLadTextoInput;
	this.addHidden=_HTMLadOculto;
	this.addTextNode=_HTMLadTextoNodulo;
	this.addNode=_HTMLadNodulo;
	this.adIntervalo=_HTMLadSpan;
	this.addSelect=_HTMLadSelecionado;
	this.adOpcao=_HTMLadOpcao;
	}
	
function _HTMLadTabela(id,width,border){
	var c=new Comparavel;
	if(width){c.add('width',width);}
	if(border){c.add('border',border);}
	if(id){c.add('id',id);}
	return this.addNode('table',false,c);
	}
	
function _HTMLadLinha(id){
	var tr=document.createElement('tr');
	if(id){tr.setAttribute('id',id);}
	return tr;
	}
	
function _HTMLadNoduloCabecalho(node,id,width){
	var c=new Comparavel;
	if(id){c.add('id',id);}
	if(width){c.add('width',width);}
	return this.addNode('th',node,c);
	}
	
function _HTMLadCabecalho(text,id,width){
	var c=new Comparavel;
	if(id){c.add('id',id);}
	if(width){c.add('width',width);}
	return this.addTextNode('th',text,c);
	}
	
function _HTMLadCelula(text,id,width,bold){
	var c=new Comparavel;
	if(id){c.add('id',id);}
	if(width){c.add('width',width);}
	return this.addTextNode('td',text,c,bold);
	}
	
function _HTMLadSpan(text,id){
	var c=new Comparavel;
	if(id){c.add('id',id);}
	return this.addTextNode('span',text,c);
	}
	
function _HTMLadCelulaNodulo(node,id,width){
	var c=new Comparavel;
	if(id){c.add('id',id);}
	if(width){c.add('width',width);}
	return this.addNode('td',node,c);
	}
	
function _HTMLadTextoNodulo(type,text,args,bold){
	var node=document.createElement(type);
	if(bold){
		var b=node.appendChild(document.createElement('b'));
		if(text){b.appendChild(document.createTextNode(text));}
		}
	else{
		if(text){node.appendChild(document.createTextNode(text));}
		}
	var i;
	if(args){
		for(i=args.length()-1;i>=0;i--){
			var elem=args.get(i);
			node.setAttribute(elem.chave,elem.data);
			}
		}
	return node;
	}
	
function _HTMLadNodulo(type,child,args){
	var node=document.createElement(type);
	if(child){node.appendChild(child);}
	var i;
	for(i=args.length()-1;i>=0;i--){
		var elem=args.get(i);
		node.setAttribute(elem.chave,elem.data);
		}
	return node;
	}
	
function _HTMLadTextoInput(id,value,size,maxlength){
	var c=new Comparavel;
	c.add('id',id);
	c.add('nome',id);
	c.add('type','text');
	if(size){c.add('size',size);}
	if(maxlength){c.add('maxlength',maxlength);}
	if(value){c.add('value',value);}
	return this.addNode('input',false,c);
	}

function _HTMLadOculto(id,value){
	var c=new Comparavel;
	c.add('id',id);
	c.add('nome',id);
	if(navigator.family=="gecko"||navigator.family=="opera"){
		c.add('type','hidden');
		type='input';
		}
	else{
		type='textarea';
		c.add('className','hidden');
		}
	c.add('value',value);
	return this.addNode(type,false,c);
	}
	
function _HTMLadSelecionado(id,cls,multi){
	var c=new Comparavel;
	c.add('id',id);
	c.add('nome',id);
	if(cls){c.add('class',cls);}
	if(multi){c.add('multiple','multiple');}
	return this.addNode('select',false,c);
	}
	
function _HTMLadOpcao(value,text,selected){
	var c=new Comparavel;
	c.add('value',value);
	if(selected){c.add('selected','selected');}
	return this.addTextNode('option',text,c);
	}
	
function EventoComum(e){
	/*var target=null;
	var x=0;
	var y=0;
	var type=null;
	var button=null;
	var chavecode=null;
	var altKey=false;
	var shiftKey=false;
	var ctrlKey=false;
	var metaKey=false;*/
	if(e){
		if(e.target){
			this.target=e.target;
			this.type=e.type;
			this.x=e.x;
			this.y=e.y;
			if(e.modifiers){
				this.altKey=(e.modifiers&ALT_MASK)?true:false;
				this.ctrlKey=(e.modifiers&CONTROL_MASK)?true:false;
				this.shiftKey=(e.modifiers&SHIFT_MASK)?true:false;
				this.metaKey=(e.modifiers&META_MASK)?true:false;
				}
			else{
				if(e.altKey)this.altKey=true;
				if(e.shiftKey)this.shiftKey=true;
				if(e.ctrlKey)this.ctrlKey=true;
				if(e.metaKey)this.metaKey=true;
				}
			if(e.type.substr(0,3).toLowerCase()=='chave')this.chavecode=e.which;
			else this.button=e.which;
			}
		else{
			this.target=E;
			this.type='field';
			}
		}
	else if(evento){
		this.target=evento.srcElement;
		this.type=evento.type;
		this.x=evento.x;
		this.y=evento.y;
		this.button=evento.button;
		this.chavecode=evento.chaveCode;
		this.altKey=evento.altKey;
		this.shiftKey=evento.shiftKey;
		this.ctrlKey=evento.ctrlKey;
		}
	}
	
function ucfirst(s,delim){
	if(!delim) delim=' ';
	var a=s.split(delim);
	var res="";
	var start=false;
	for(var i=0;i<a.length;i++){
		if(start) res+=" ";
		else start=true;
		res+=a[i].substr(0,1).toUpperCase()+a[i].substr(1);
		}
	return res;
	}
	
function limpar_span(id){
	var span=document.getElementById(id);
	if(span){
		if(span.hasChildNodes()){for(var i=span.childNodes.length-1;i>=0;i--) span.removeChild(span.childNodes.item(i));}
		}
	return span;
	}

function mostrar_mensagem(fname,txt){
	mostrar_msg(txt,fname+'_mensagem');
	}
	
function mostrar_instrucao(txt){
	mostrar_msg(txt,'instruct');
	}
	
function mostrar_msg(txt,elem){
	var span=document.getElementById(elem);
	if(span==null)return;
	var text;
	if(span.hasChildNodes()){
		text=span.childNodes.item(0);
		text.nodeValue=txt;
		}
	else text=span.appendChild(document.createTextNode(txt));
	}

function limpar_mensagem(fname){
	resetar_mensagem(fname+'_mensagem');
	}
	
function limpar_instrucao(){
	resetar_mensagem('instruct');
	}
	
function resetar_mensagem(elem){
	var span=document.getElementById(elem);
	if(span==null){return;}
	var text;
	if(span.hasChildNodes()){
		text=span.childNodes.item(0);
		text.nodeValue='';
		}
	else text=span.appendChild(document.createTextNode(''));
	}

function achar_ancora(a){
	for(var i=0;i<document.anchors.length;i++){
		if(document.anchors[i].name==a)return true;
		}
	return false;
	}
	
function getAlturaInterna(win){
	var winHeight;
	if(win.innerHeight){winHeight=win.innerHeight;}
	else if(win.document.documentElement&&win.document.documentElement.clientHeight) winHeight=win.document.documentElement.clientHeight;
	else if(win.document.body){winHeight=win.document.body.clientHeight;}
	else winHeight=0;
	return winHeight;
	}
	
var linhas_salvas=new Comparavel;

function ativar_colapsar(item,colapsar){
	var item_image=document.getElementById('imagem_'+item);
	if(!item_image){return false;}
	var item_elem=document.getElementById('r_'+item);
	var parent=item_elem.parentNode;
	var bottom=item_image.name.substr(item_image.name.length-2,2);
	if(bottom=='_0'){
		if(colapsar){return false;}
		var orig=linhas_salvas.find(item);
		if(orig){
			var next=item_elem.nextSibling;
			for(var j=0,j_cmp=orig.length;j<j_cmp;j++) parent.insertBefore(orig[j],next);
			item_image.name=item_image.id+'_1';
			item_image.src='./imagens/seta_baixo.gif';
			}
		}
	else{
		item_image.name=item_image.id+'_0';
		item_image.src='./imagens/seta-direita.gif';
		var row_array=new Array();var rid=0;
		var sib=item_elem.nextSibling;
		var nivel_item=document.getElementById('rl_'+item);
		var nivel=nivel_item.value;
		while(sib){
			if(!sib.id){
				sib=sib.nextSibling;
				continue;
				}
			var sib_id=sib.id.substr(2);
			var subnivel=document.getElementById('rl_'+sib_id).value;
			if(subnivel<=nivel) break;
			var nxt=sib.nextSibling;
			row_array[rid++]=parent.removeChild(sib);
			sib=nxt;
			}
		linhas_salvas.add(item,row_array);
		}
	return true;
	}
		
function colapsar_tudo(parent){
	var parent_elem=document.getElementById(parent);
	for(var i=0,i_cmp=parent_elem.childNodes.length;i<i_cmp;i++){
		if(parent_elem.childNodes[i].tagName=='TR'&&parent_elem.childNodes[i].id) ativar_colapsar(parent_elem.childNodes[i].id.substr(2),true);
		}
	}
	
var funcao_mostrar_tab=null;
var funcao_ocultar_tab=null;
	
function mostrar_tab(i){
	ocultar_tabs();
	if(funcao_mostrar_tab){
		funcao_mostrar_tab(i);
		return;
		}
	var tab=document.getElementById('tab_'+i);
	tab.style.display='block';
	tab=document.getElementById('tab_s_'+i);
	tab.className='tabativo';
	tab.style='font-style:bold;';
	}
		
function ocultar_tabs(){
	if(funcao_ocultar_tab){
		funcao_ocultar_tab();
		return;
		}
	var tabs=document.getElementsByTagName('td');
	var i;
	for(i=0;i<tabs.length;i++){
		if(tabs[i].className=='tabativo'){
			tabs[i].className='tabinativo';
			tabs[i].style='font-style:normal;';
			}
		}
	tabs=document.getElementsByTagName('div');
	for(i=0;i<tabs.length;i++){
		if(tabs[i].className=='tab'){tabs[i].style.display='none';}
		}
	}

funcao_ocultar_tab=gt_esconder_tabs;
funcao_mostrar_tab=gt_mostrar_tab;

function expandir_colapsar(id,tabelaNome,option,opt_nivel,root){
	var expandir=(option=='expandir'?1:0);
	var colapsar=(option=='colapsar'?1:0);
	var nivel=(opt_nivel==0?0:(opt_nivel>0?opt_nivel:-1));
	var include_root=(root?root:0);var done=false;
	var encontrado=false;var trs=document.getElementsByTagName('tr');
	for(var i=0;i<trs.length;i++){
		var tr_nome=trs.item(i).id;
		if((tr_nome.indexOf(id)>=0)&&nivel<0){
			var tr=document.getElementById(tr_nome);
			if(colapsar||expandir){
				if(colapsar){
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="none";
						img_expandir.style.display="inline";
						}
					else{
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="none";
						img_expandir.style.display="inline";
						}
					}
				else{
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="visible";
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="inline";
						img_expandir.style.display="none";
						}
				else{
					tr.style.display="";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
					img_colapsar.style.display="inline";
					img_expandir.style.display="none";
					}
				}
			}
		else {
			if(navigator.family=="gecko"||navigator.family=="opera"){
				tr.style.visibility=(tr.style.visibility==''||tr.style.visibility=="colapsar") ? "visible":"colapsar";
				tr.style.display=(tr.style.display=="none")? "" : "none";
				var img_expandir=document.getElementById(tr_nome+'_expandir');
				var img_colapsar=document.getElementById(tr_nome+'_colapsar');
				if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
				if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
				img_colapsar.style.display=(tr.style.visibility=='visible') ? "inline" : "none";
				img_expandir.style.display=(tr.style.visibility==''||tr.style.visibility=="colapsar")?"inline":"none";
				}
			else{
				tr.style.display=(tr.style.display=="none")?"":"none";
				var img_expandir=document.getElementById(tr_nome+'_expandir');
				var img_colapsar=document.getElementById(tr_nome+'_colapsar');
				if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
				if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
				img_colapsar.style.display=(tr.style.display=='')?"inline":"none";
				img_expandir.style.display=(tr.style.display=='none')?"inline":"none";
				}
			}
		}
		else if((tr_nome.indexOf(id)>=0)&&nivel>=0&&!done&&!encontrado&&!include_root){
			encontrado=true;
			//var tr=document.getElementById(tr_nome);
			var img_expandir=document.getElementById(tr_nome+'_expandir');
			var img_colapsar=document.getElementById(tr_nome+'_colapsar');
			if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
			if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
			if(!(img_colapsar==null)) img_colapsar.style.display=(img_colapsar.style.display=='none')?"inline":"none";
			if(!(img_expandir==null)){
				img_expandir.style.display=(img_expandir.style.display=='none')?"inline":"none";
				opt=(img_expandir.style.display=="inline")?"colapsar":"expandir";
				colapsar=(opt=='colapsar'?1:0);expandir=(opt=='expandir'?1:0);
				}
			}
		else if((tr_nome.indexOf(id)>=0)&&nivel>=0&&include_root){
			encontrado=true;
			var tr=document.getElementById(tr_nome);
			nivel_atual=parseInt(tr_nome.substr(tr_nome.indexOf('>')+1,tr_nome.indexOf('<')-tr_nome.indexOf('>')-1));
			if(colapsar){
				if(navigator.family=="gecko"||navigator.family=="opera"){
					if((include_root==1&&nivel==0)||(nivel_atual>0)){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						}
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null)) img_colapsar.style.display="none";
					if(!(img_expandir==null)) img_expandir.style.display="inline";
					}
				else{
					if((include_root==1&&nivel==0)||(nivel_atual>0)) tr.style.display="none";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null)) img_colapsar.style.display="none";
					if(!(img_expandir==null)) img_expandir.style.display="inline";
					}
				}
			else{
				if(navigator.family=="gecko"||navigator.family=="opera"){
					if((include_root==1&&nivel==0)||(nivel_atual>0)) tr.style.visibility="visible";tr.style.display="";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null))	img_colapsar.style.display="inline";
					if(!(img_expandir==null))	img_expandir.style.display="none";
					}
			else{
				if((include_root==1&&nivel==0)||(nivel_atual>0)){
					tr.style.display="";}
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null){img_colapsar=document.getElementById(id+'_colapsar');}
					if(!(img_colapsar==null)){img_colapsar.style.display="inline";}
					if(!(img_expandir==null)){img_expandir.style.display="none";}
					}
				}
			}
		else if(nivel>0&&!done&&(encontrado||nivel==0)){
			nivel_atual=parseInt(tr_nome.substr(tr_nome.indexOf('>')+1,tr_nome.indexOf('<')-tr_nome.indexOf('>')-1));
			if(nivel_atual<nivel){
				done=true;
				return;
				}
			else{
				var tr=document.getElementById(tr_nome);
				if(colapsar){
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null){img_colapsar=document.getElementById(id+'_colapsar');}
						if(!(img_colapsar==null)){img_colapsar.style.display="none";}
						if(!(img_expandir==null)){img_expandir.style.display="inline";}
						}
					else{
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){img_expandir=document.getElementById(id+'_expandir');}
						if(img_colapsar==null){img_colapsar=document.getElementById(id+'_colapsar');}
						if(!(img_colapsar==null)){img_colapsar.style.display="none";}
						if(!(img_expandir==null)){img_expandir.style.display="inline";}
						}
					}
				else{
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="visible";
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){img_expandir=document.getElementById(id+'_expandir');}
						if(img_colapsar==null){img_colapsar=document.getElementById(id+'_colapsar');}
						if(!(img_colapsar==null)){img_colapsar.style.display="inline";}
						if(!(img_expandir==null)){img_expandir.style.display="none";}
						}
					else{
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){img_expandir=document.getElementById(id+'_expandir');}
						if(img_colapsar==null){img_colapsar=document.getElementById(id+'_colapsar');}
						if(!(img_colapsar==null)){img_colapsar.style.display="inline";}
						if(!(img_expandir==null)){img_expandir.style.display="none";}
						}
					}
				}
			}
		}
	}

function expandirTudo(id,tabelaNome){
	Expandir_colapsar(id,tabelaNome,'expandir');
	}
	
function colapsarTudo(id,tabelaNome){
	Expandir_colapsar(id,tabelaNome,'colapsar');
	}
	
function adOpcao(selectId,val,txt){
	var objOption=new Option(txt,val);
	document.getElementById(selectId).options.add(objOption);
	}

function comboVazio(combo) {
	combo.options.length = 0;
	}

function TudoPorTag() {}

TudoPorTag.setStyleDisplay = function(tagName,value) {
  var elements = document.getElementsByTagName(tagName);
  for (var i = 0; i < elements.length; i++) elements[i].style.display = value;
	};

TudoPorTag.mostrar = function(tagName,dispType) {
  TudoPorTag.setStyleDisplay(tagName, dispType ? dispType : 'inline');
	};

TudoPorTag.hide = function(tagName,dispType) {
  TudoPorTag.setStyleDisplay(tagName, 'none');
	};