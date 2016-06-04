/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

function getEstilo(nodeNome,sEstilo,iEstilo){
		var element=document.getElementById(nodeNome);
		if(window.getComputedStyle){
			var style=document.defaultView.getComputedStyle(element,null);
			var value=style.getPropertyValue(sEstilo)
			}
		else var value=eval("element.currentStyle."+iEstilo)
		return value
		}
		
function mult_sel(cmbObj,nome_caixa,nome_formulario){
	var f=eval('document.'+nome_formulario);
	var check=cmbObj.checked;
	for(var i=0,i_cmp=f.length;i<i_cmp;i++){
		campoObj=f.elements[i];
		var campo_nome=campoObj.id;if(campoObj.type=='checkbox'&&campo_nome.indexOf(nome_caixa)>=0){
			id=campo_nome.replace('selecionado_tarefa_','');
			var trs=document.getElementsByTagName('tr');
			for(var ri=0,ri_cmp=trs.length;ri<ri_cmp;ri++){
				linhaObj=trs[ri];var linha_id=linhaObj.id;
				if(linha_id.indexOf('tarefa_'+id+'_')>=0) row=document.getElementById(linha_id)
				}
			var checadoAntigo=campoObj.checked;
			campoObj.checked=(check) ? true : false;
			if(check){
				iluminar_tds(row,2,id);
				if(!checadoAntigo)adBlocoComponente(id);
				}
			else{
				iluminar_tds(row,0,id);
				if(checadoAntigo)removerBlocoComponente(id);
				}
			}
		}
	}

function iluminar_tds(row,high,id){
	if(document.getElementsByTagName){
		var tcs=row.getElementsByTagName('td');
		var nome_celula='';
		if(!id)check=false;
		else{
			var f=eval('document.frm_tarefas');
			var check=eval('f.selecionado_tarefa_'+id+'.checked')
			}
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
				if(high==3) tcs[j].style.background='#FFFFCC';
				else if(high==2||check)
				tcs[j].style.background='#FFCCCC';
				else if(high==1) tcs[j].style.background='#FFFFCC';
				else tcs[j].style.background='#FFFFFF';
				}
			}
		}
	}

var estah_marcado;

function selecionar_caixa(box,id,linha_id,nome_formulario){
	var f=eval('document.'+nome_formulario);
	var check=eval('f.'+box+'_'+id+'.checked');
	boxObj=eval('f.elements["'+box+'_'+id+'"]');
	if((estah_marcado&&boxObj.checked&&!boxObj.disabled)||(!estah_marcado&&!boxObj.checked&&!boxObj.disabled)){row=document.getElementById(linha_id);
		boxObj.checked=true;
		iluminar_tds(row,2,id);
		adBlocoComponente(id)
		}
	else if((estah_marcado&&!boxObj.checked&&!boxObj.disabled)||(!estah_marcado&&boxObj.checked&&!boxObj.disabled)){
		row=document.getElementById(linha_id);
		boxObj.checked=false;
		iluminar_tds(row,3,id);
		removerBlocoComponente(id)
		}
	}