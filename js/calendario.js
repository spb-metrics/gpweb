/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

var horasMSeg = 3600*1000;

function estarNoVetor(meuVetor, intValor) {
	for (var i = 0, i_cmp = meuVetor.length; i < i_cmp; i++) {
		if (meuVetor[i] == intValor) return true;
		}		
	return false;
	}

function selected(cal, date) {
  cal.sel.value = (cal.date.print("%Y%m%d%H%M"));    
  setDate(cal.form, cal.sel.name);
  if (cal.dateClicked && (cal.sel.id == "ini_date" || cal.sel.id == "data_fim")) cal.callCloseHandler();
	}

function fecharHandler(cal) {
  cal.hide();                        
  _dynarch_popupCalendario = null;
	}

var NOME_MESES=new Array('January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
var NOME_DIAS=new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sun','Mon','Tue','Wed','Thu','Fri','Sat');

function LZ(x) {
	return(x<0||x>9?"":"0")+x
	}

function formatarData(date,format) {
  format=format+"";
  var result="";
  var i_format=0;
  var c="";
  var token="";
  var y=date.getYear()+"";
  var M=date.getMonth()+1;
  var d=date.getDate();
  var E=date.getDay();
  var H=date.getHours();
  var m=date.getMinutes();
  var s=date.getSeconds();
  var Y, yyyy,yy,MMM,MM,dd,hh,h,mm,ss,ampm,HH,H,KK,K,kk,k;
  var value=new Object();
  if(y.length < 4) y=""+(y-0+1900);
  value["y"]=""+y;
  value["yyyy"]=y;
  value["Y"]=y;
  value["yy"]=y.substring(2,4);
  value["M"]=M;
  value["MM"]=LZ(M);
  value["MMM"]=NOME_MESES[M-1];
  value["NNN"]=NOME_MESES[M+11];
  value["b"]=NOME_MESES[M+11];
  value["d"]=d;
  value["dd"]=LZ(d);
  value["E"]=NOME_DIAS[E+7];
  value["EE"]=NOME_DIAS[E];
  value["H"]=H;
  value["HH"]=LZ(H);
  if(H==0) value["h"]=12;
  else if(H>12) value["h"]=H-12;
 	else value["h"]=H;
  value["hh"]=LZ(value["h"]);
  if(H>11) value["K"]=H-12;
  else value["K"]=H;
  value["k"]=H+1;
  value["KK"]=LZ(value["K"]);
  value["kk"]=LZ(value["k"]);
  if(H > 11) value["a"]="pm";
  else value["a"]="am";
  value["m"]=m;
  value["mm"]=LZ(m);
  value["s"]=s;
  value["ss"]=LZ(s);
  while(i_format < format.length) {
    c=format.charAt(i_format);
    token="";
    while((format.charAt(i_format)==c) &&(i_format < format.length)) token += format.charAt(i_format++);
    if(value[token] != null) result=result + value[token];
    else result=result + token;
  	}
  return result;
	}

function _serInteiro(val) {
	var digits="1234567890";
	for (var i=0, i_cmp=val.length; i < i_cmp; i++) {
		if (digits.indexOf(val.charAt(i))==-1) return false; 
		}
	return true;
	}
	
function _getInt(str,i,minlength,maxlength) {
	for (var x=maxlength; x>=minlength; x--) {
		var token=str.substring(i,i+x);
		if (token.length < minlength) return null; 
		if (_serInteiro(token)) return token; 
		}
	return null;
	}

function getDataDoFormato(val,format) {
	val=val+"";
	format=format+"";
	var i_val=0;
	var i_format=0;
	var c="";
	var token="";
	var token2="";
	var x,y;
	var now=new Date();
	var ano=now.getYear();
	var mes=now.getMonth()+1;
	var date=1;
	var hh=now.getHours();
	var mm=now.getMinutes();
	var ss=now.getSeconds();
	var ampm="";
	while (i_format < format.length) {
		c=format.charAt(i_format);
		token="";
		while ((format.charAt(i_format)==c) && (i_format < format.length)) token += format.charAt(i_format++);
		if (token=="yyyy" || token=="yy" || token=="y" || token=="Y") {
			if (token=="Y") { x=4;y=4; }
			if (token=="yyyy") { x=4;y=4; }
			if (token=="yy")   { x=2;y=2; }
			if (token=="y")    { x=2;y=4; }
			ano=_getInt(val,i_val,x,y);
			if (ano==null) { return 0; }
			i_val += ano.length;
			if (ano.length==2) {
				if (ano > 70) ano=1900+(ano-0); 
				else ano=2000+(ano-0); 
				}
			} 
		else if (token=="MMM"||token=="NNN") {
			mes=0;
			for (var i=0, i_cmp=NOME_MESES.length; i<i_cmp; i++) {
				var mes_name=NOME_MESES[i];
				if (val.substring(i_val,i_val+mes_name.length).toLowerCase()==mes_name.toLowerCase()) {
					if (token=="MMM"||(token=="NNN"&&i>11)) {
						mes=i+1;
						if (mes>12) mes -= 12; 
						i_val += mes_name.length;
						break;
						}
					}
				}
			if ((mes < 1)||(mes>12)) return 0;
			} 
		else if (token=="EE"||token=="E") {
			for (var i=0, i_cmp=NOME_DIAS.length; i<i_cmp; i++) {
				var nome_dia=NOME_DIAS[i];
				if (val.substring(i_val,i_val+nome_dia.length).toLowerCase()==nome_dia.toLowerCase()) {
					i_val += nome_dia.length;
					break;
					}
				}
			} 
		else if (token=="MM"||token=="M") {
			mes=_getInt(val,i_val,token.length,2);
			if(mes==null||(mes<1)||(mes>12)) return 0;
			i_val+=mes.length;
			} 
		else if (token=="dd"||token=="d") {
			date=_getInt(val,i_val,token.length,2);
			if(date==null||(date<1)||(date>31)) return 0;
			i_val+=date.length;
			} 
		else if (token=="hh"||token=="h") {
			hh=_getInt(val,i_val,token.length,2);
			if(hh==null||(hh<1)||(hh>12)) return 0;
			i_val+=hh.length;
			} 
		else if (token=="HH"||token=="H") {
			hh=_getInt(val,i_val,token.length,2);
			if(hh==null||(hh<0)||(hh>23)) return 0;
			i_val+=hh.length;
			} 
		else if (token=="KK"||token=="K") {
			hh=_getInt(val,i_val,token.length,2);
			if(hh==null||(hh<0)||(hh>11)) return 0;
			i_val+=hh.length;
			} 
		else if (token=="kk"||token=="k") {
			hh=_getInt(val,i_val,token.length,2);
			if(hh==null||(hh<1)||(hh>24)) return 0;
			i_val+=hh.length;hh--;
			} 
		else if (token=="mm"||token=="m") {
			mm=_getInt(val,i_val,token.length,2);
			if(mm==null||(mm<0)||(mm>59))	return 0;
			i_val+=mm.length;
			} 
		else if (token=="ss"||token=="s") {
			ss=_getInt(val,i_val,token.length,2);
			if(ss==null||(ss<0)||(ss>59))	return 0;
			i_val+=ss.length;
			} 
		else if (token=="a") {
			if (val.substring(i_val,i_val+2).toLowerCase()=="am") ampm="am";
			else if (val.substring(i_val,i_val+2).toLowerCase()=="pm") ampm="pm";
			else return 0;
			i_val+=2;
			} 
		else {
			if (val.substring(i_val,i_val+token.length)!=token) return 0;
			else i_val+=token.length;
			}
		}
	if (i_val != val.length) { return 0; }
	if (mes==2) {
		if ( ( (ano%4==0)&&(ano%100 != 0) ) || (ano%400==0) ) { 
			if (date > 29)return 0;
			} 
		else if (date > 28) return 0;
		} 
	if ((mes==4)||(mes==6)||(mes==9)||(mes==11)) {
		if (date > 30) return 0;
		}
	if (hh<12 && ampm=="pm") hh=hh-0+12;
	else if (hh>11 && ampm=="am") hh-=12;
	var newdate=new Date(ano,mes-1,date,hh,mm,ss);
	return newdate.getTime();
	}