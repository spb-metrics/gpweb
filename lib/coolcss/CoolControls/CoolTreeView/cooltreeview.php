<?php 
$_bl0="\062.0.7.1"; 
if (!class_exists("KoolScripting",FALSE)) { 
	class koolscripting { 
		static function start() { 
			ob_start(); 
			return ""; 
			} 
		static function end() { 
			$_bO0=ob_get_clean(); 
			$_bl1=""; 
			$_bO1=new domdocument(); 
			$_bO1->loadxml($_bO0); 
			$_bl2=$_bO1->documentElement; 
			$id=$_bl2->getattribute("id"); 
			$_bO2=$_bl2->nodeName; 
			$id=($id == "") ? "dump": $id; 	
			if (class_exists($_bO2,FALSE)) { 
				eval ("\044".$id." = new ".$_bO2."('".$id."');"); 
				$$id->loadxml($_bl2); 
				$_bl1=$$id->render();	
				}	
			else { 
				$_bl1.=$_bO0; 
				} 
			return $_bl1; 
			} 
		} 
	} 
	
function _bl3($_bO3) {
	$cripto=md5($_bO3); 
	if ($cripto=='d41d8cd98f00b204e9800998ecf8427e') 
	$cripto='cb7fc86afce8e03308bb6f48164f638c'; 
	return $cripto; 
	} 
	
function _bl4() { 
	$_bO4=_bl5("\134","/",strtolower(previnirXSS($_SERVER["SCRIPT_NAME"]))); 
	$_bO4=_bl5(strrchr($_bO4,"/"),"",$_bO4); 
	$_bO5=_bl5("\134","/",realpath(".")); 
	$_bl6=_bl5($_bO4,"",strtolower($_bO5)); 
	return $_bl6; 
	} 

function _bl5($_bO6,$_bl7,$_bO7) { 
	return str_replace($_bO6,$_bl7,$_bO7); 
	} 

$_bi10="{0}<div id='{id}' class='{style}KTV' style='{width}{height}{overflow}'><ul class='ktvU\114 {nopadding} {lines}'>{subnodes}</ul>{clientstate}{1}</div>{\062}"; 

function _bl8() { 
	$_bO8=_bl9(); 
	_bO9($_bO8,0153); 
	_bO9($_bO8,0113); 
	_bO9($_bO8,0121); 
	_bO9($_bO8,-014); 
	_bO9($_bO8,050); 
	_bO9($_bO8,040); 
	_bO9($_bO8,034); 
	_bO9($_bO8,(_bla() || _bOa() || _blb()) ? -050: -011); 
	_bO9($_bO8,-062); 
	_bO9($_bO8,-061); 
	_bO9($_bO8,-0111); 
	_bO9($_bO8,-0111); 
	$_bOb=""; 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_bOb.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		} 
	echo $_bOb; 
	return $_bOb; 
	} 
	
function _bOd() { 
	$_bO8=_bl9(); 
	$_ble=""; 
	_bO9($_bO8,0151); 
	_bO9($_bO8,0123); 
	_bO9($_bO8,0114); 
	_bO9($_bO8,071); 
	_bO9($_bO8,-017); 
	_bO9($_bO8,-031); 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_ble.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		} 
	return _bOe($_ble); 
	} 
	
function _bla() { 
	$_blf=""; 
	$_bO8=_bl9(); 
	_bO9($_bO8,0130); 
	_bO9($_bO8,0114); 
	_bO9($_bO8,026); 
	_bO9($_bO8,072); 
	_bO9($_bO8,054); 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_blf.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		} 
	return (substr(_bl3(_bOf()),0,5) != $_blf); 
	} 
	
$_bi11=0226/012; 

function _bOa() { 
	$_blf=""; 
	$_bO8=_bl9(); 
	_bO9($_bO8,045); 
	_bO9($_bO8,041); 
	_bO9($_bO8,0102); 
	_bO9($_bO8,070); 
	_bO9($_bO8,056); 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_blf.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		}
	return (substr(_bl3(_blg()),0,5) != $_blf); 
	} 
	
function _blb() { 
	$_bOg=_blh(); 
	$_bO8=_bl9(); 
	_bO9($_bO8,0124); 
	_bO9($_bO8,0114); 
	_bO9($_bO8,0110); 
	_bO9($_bO8,5); 
	_bO9($_bO8,-6); 
	$_bOh=""; 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_bOh.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		} 
	return (( isset ($_bOg[$_bOh]) ? $_bOg[$_bOh]: 0) != 01053/045); 
	} 
	
function _bOi( &$_blj) { 
	$_bOg=_blh(); 
	$_bO8=_bl9(); 
	_bO9($_bO8,0124); 
	_bO9($_bO8,0114); 
	_bO9($_bO8,0110); 
	_bO9($_bO8,5); 
	_bO9($_bO8,-6); 
	$_bOj=""; 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_bOj.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		} 
	$_blk=$_bOg[$_bOj]; 
	$_blj=_bl5(_bld(0173).(_bOd()%3)._bld(0175),(!(_bOd()%_bOk())) ? _bOf(): _bll(),$_blj); 
	for ($_blc=0; $_blc<3; $_blc ++) if ((_bOd()%3) != $_blc) $_blj=_bl5(_bld(0173).$_blc._bld(0175),_bll(),$_blj); 
	$_blj=_bl5(_bld(0173).(_bOd()%3)._bld(0175),(!(_bOd()%$_blk)) ? _bOf(): _bll(),$_blj); 
	return ($_blk == _bOk()); 
	} 
	
function _bOf() { 
	$_bOg=_blh(); 
	$_bO8=_bl9(); 
	_bO9($_bO8,0124); 
	_bO9($_bO8,0114); 
	_bO9($_bO8,0110); 
	_bO9($_bO8,4); 
	_bO9($_bO8,-6); 
	$_blm=""; 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_blm.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		} 
	return isset ($_bOg[$_blm]) ? $_bOg[$_blm]: ""; 
	} 
	
function _blg() { 
	$_bOg=_blh(); 
	$_bO8=_bl9(); 
	_bO9($_bO8,0124); 
	_bO9($_bO8,0114); 
	_bO9($_bO8,0110); 
	_bO9($_bO8,5); 
	_bO9($_bO8,-7); 
	$_bOm=""; 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_bOm.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		} 
	return isset ($_bOg[$_bOm]) ? $_bOg[$_bOm]: ""; 
	} 
	
function _bOk() { 
	$_bOg=_blh(); 
	$_bO8=_bl9(); 
	_bO9($_bO8,0124); 
	_bO9($_bO8,0114); 
	_bO9($_bO8,0110); 
	_bO9($_bO8,5); 
	_bO9($_bO8,-6); 
	$_bOj=""; 
	for ($_blc=0; $_blc<_bOc($_bO8); $_blc ++) { 
		$_bOj.=_bld($_bO8[$_blc]+013*($_blc+1)); 
		} 
	return isset ($_bOg[$_bOj]) ? $_bOg[$_bOj]: (0207/011); 
	} 
	
function _bl9() { return array(); } 

function _blh() { return $GLOBALS; } 

function _bOe($_bln) { return eval ("return ".$_bln.";"); } 

function _bOc($_bOn) { return sizeof($_bOn); } 

function _bll() { return ""; } 

function _blo() { 
	header("Content-type: text/javascript"); 
	} 
	
function _bO9( &$_bOn,$_bOo) { array_push($_bOn,$_bOo); } 

function _blp() { return exit (); } 

function _bld($_bOp) { return chr($_bOp); } 
$_bi01=""; 

if ( isset ($_GET[_bl3( __FILE__."js")])) { 
	_blo(); ?> 
	
	function _bO(_bo){return (_bo!=null);}
	
	function _bY(_by,_bI){
		var _bi=document.createElement(_by); 
		_bI.appendChild(_bi); 
		return _bi; 
		}
	
	function _bA(_ba){return document.getElementById(_ba); }
	
	function _bE(_bo,_be){
		if (!_bO(_be))_be=1; 
		for (var i=0; i<_be; i++)_bo=_bo.parentNode; 
		return _bo; 
		}
		
	function _bU(_bo,_be){
		if (!_bO(_be))_be=1; 
		for (var i=0; i<_be; i++)_bo=_bo.firstChild; 
		return _bo; 
		}
	
	function _bu(_bo,_be){
		if (!_bO(_be))_be=1; 
		for (var i=0; i<_be; i++)_bo=_bo.nextSibling; 
		return _bo; 
		}
	
	function _bZ(){return (typeof(_biO1)=="undefined");}
	
	function _bz(_bX,_bx,_bW,_bw){
		if (_bX.addEventListener){
			_bX.addEventListener(_bx,_bW,_bw); 
			return true; 
			}
		else if (_bX.attachEvent){
			if (_bw){return false; }
			else {
				var _bV= function (){_bW.apply(_bX,[window.event]);};
				if (!_bX["ref"+_bx])_bX["ref"+_bx]=[]; 
				else {for (var _bv in _bX["ref"+_bx]){if (_bX["ref"+_bx][_bv]._bW === _bW)return false;}}
				var _bT=_bX.attachEvent("on"+_bx,_bV); 
				if (_bT)_bX["ref"+_bx].push( {_bW:_bW,_bV:_bV } ); 
				return _bT; 
				}
			}
		else {return false;}
		}; 

function _bt(_bX,_bx,_bW,_bw){
	if (_bX.removeEventListener){
		_bX.removeEventListener(_bx,_bW,_bw); 
		return true; 
		}
	else if (_bX.detachEvent){
		if (_bX["ref"+_bx]){
			for (var _bv in _bX["ref"+_bx]){
				if (_bX["ref"+_bx][_bv]._bW === _bW){
					_bX.detachEvent("on"+_bx,_bX["ref"+_bx][_bv]._bV); 
					_bX["ref"+_bx][_bv]._bW=null; 
					_bX["ref"+_bx][_bv]._bV=null; 
					delete _bX["ref"+_bx][_bv]; 
					return true; 
					}
				}
			}
		return false; 
		}
	else {return false; }
	}

function _bS(_bs){return _bs.className; }

function _bR(_bs,_br){_bs.className=_br; }

function _bQ(_bo,_bq){
	if (_bo.className.indexOf(_bq)<0){
		var _bP=_bo.className.split(" "); 
		_bP.push(_bq); 
		_bo.className=_bP.join(" "); 
		}
	}

function _bp(_bo,_bq){
	if (_bo.className.indexOf(_bq)>-1){
		_bN(_bq,"",_bo);
		var _bP=_bo.className.split(" "); 
		_bo.className=_bP.join(" "); 
		}
	}

function _bN(_bn,_bM,_bm){_bR(_bm,_bS(_bm).replace(_bn,_bM)); }

function _bL(_bm,_bq){
	for (var i=0; i<_bm.childNodes.length; i++)if (_bm.childNodes[i].className.indexOf(_bq)>-1) return _bm.childNodes[i]; 
		}
	
function _bl(_bo,_bK){_bo.style.display=(_bK)?"block": "none"; }

function _bk(_bo){return (_bo.style.display!="none"); }

function _bJ(_bm){
	var _bj=""; 
	for (var _bH in _bm){
		switch (typeof(_bm[_bH])){
			case "string":
				if (_bO(_bm.length))_bj+="'"+_bm[_bH]+"',"; 
				else _bj+="'"+_bH+"':'"+_bm[_bH]+"',"; 
				break; 
			case "number":
				if (_bO(_bm.length))_bj+=_bm[_bH]+","; 
				else _bj+="'"+_bH+"':"+_bm[_bH]+","; 
				break; 
			case "object":
				if (_bO(_bm.length))_bj+=_bJ(_bm[_bH])+","; 
				else _bj+="'"+_bH+"':"+_bJ(_bm[_bH])+","; 
				break; 
			}
		}
	if (_bj.length>0)_bj=_bj.substring(0,_bj.length-1); 
	_bj=(_bO(_bm.length))?"["+_bj+"]": "{"+_bj+"}"; 
	if (_bj=="{}")_bj="null"; 
	return _bj; 
	}
	
var _bh= false; 

function _bG(_bg){_bF=(window.event)?event.keyCode:_bg.keyCode; if (_bF==17){_bh= true; }}

function _bf(_bg){
	_bF=(window.event)?event.keyCode:_bg.keyCode; 
	if (_bF==17){_bh= false; }
	}

_bz(document,"keyup",_bf, false); 
_bz(document,"keydown",_bG, false); 

function _bD(_bd){
	if (_bd.pageX || _bd.pageY){return {_bC:_bd.pageX,_bc:_bd.pageY } ; }
	return {_bC:_bd.clientX+document.body.scrollLeft-document.body.clientLeft,_bc:_bd.clientY+document.body.scrollTop-document.body.clientTop }; 
	}

var _bB= {_bb:function (){var _bo0=document.cookie.split("; "); for (var i=0; i<_bo0.length; i++){var _bO0=_bo0[i].split("="); this[_bO0[0]]=_bO0[1]; }} ,_bl0:function (_bH,_bi0,_bI0){if (_bI0){var _bo1=new Date(); _bo1.setTime(_bo1.getTime()+(_bI0*60*60*1000)); var _bO1="; expires="+_bo1.toGMTString(); }else var _bO1=""; document.cookie=_bH+"="+_bi0+_bO1+"; path=/"; this[_bH]=_bi0; } ,_bl1:function (_bH){ this._bl0(_bH,"",-1); this[_bH]=undefined; }}; 
	
_bB._bb(); 

function _bi1(_bI1){ this.NodeId=_bI1; this._ba=_bI1; }

_bi1.prototype= {
	getText:function (){
		return this._bo2("Text").innerHTML; 
		} ,
	setText:function (_bO2){ 
		this._bo2("Text").innerHTML=_bO2; 
		return this; 
		} ,
	getImageSrc:function (){
		var _bl2=this._bo2("Image"); 
		return (_bO(_bl2)?_bl2.src: ""); 
		} ,
	setImageSrc:function (_bi2){
		var _bl2=this._bo2("Image"); 
		if (_bO(_bl2))_bl2.src=_bi2; 
		return this; 
		} ,
	disableSelect:function (_bI2){ 
		(_bI2)? this._bo3("select"): this._bO3("select"); 
		return this ; 
		} ,
	disableDrag:function (_bI2){ 
		(_bI2)?this._bo3("drag"): this._bO3("drag"); 
		return this ; 
		} ,
	disableDrop:function (_bI2){ 
		(_bI2)?this._bo3("drop"): this._bO3("drop"); 
		return this ; 
		} ,
	disableEdit:function (_bI2){ 
		(_bI2)?this._bo3("edit"): this._bO3("edit"); 
		return this ; 
		} ,
	_bo3:function (_bl3){
		var _bi3=this.getTree(); 
		var _bI3=_bi3._bo4(); 
		var _bO4=_bI3[_bl3+"DisableIds"]; 
		if (_bO4.join(" ").indexOf(this._ba)<0)_bO4.push(this._ba); 
		_bi3._bl4(_bI3); 
		} ,
	_bO3:function (_bl3){
		var _bi3=this.getTree(); 
		var _bI3=_bi3._bo4(); 
		var _bO4=_bI3[_bl3+"DisableIds"]; 
		for (var i in _bO4)if (_bO4[i]==this._ba){
			_bO4.splice(i,1); 
			break; 
			}
		_bi3._bl4(_bI3); 
		} ,
	getData:function (_bF){
		if (_bZ())return this; 
		var _bi4=_bU(_bA(this._ba)); 
		var _bI4=null; 
		for (var i=0; i<_bi4.childNodes.length; i++) if (_bi4.childNodes[i].nodeName=="INPUT")if (_bi4.childNodes[i].type="hidden")_bI4=_bi4.childNodes[i]; 
		if (_bO(_bI4)){
			var _bo5=eval("__="+_bI4.value); 
			var _bi0=_bo5.data[_bF]; 
			return (_bO(_bi0)?decodeURIComponent(_bi0): ""); 
			}
		else {return "";}
		} ,
	select:function (){
		var _bi4=_bU(_bA(this._ba)); 
		if (_bS(_bi4).indexOf("Selected")<0){
			if (!this.getTree()._bO5("OnBeforeSelect", { "NodeId": this._ba } )) return; 
			_bQ(_bi4,"ktvSelected"); 
			var _bi3=this.getTree(); 
			var _bl5=_bi3._bo4(); 
			if (!_bO(_bl5.selectedIds))_bl5.selectedIds=new Array(); 
			_bl5.selectedIds.push(this._ba); 
			_bi3._bl4(_bl5); 
			this.getTree()._bO5("OnSelect", { "NodeId": this._ba } ); 
			}
		return this ; 
		} ,
	unselect:function (){
		var _bi4=_bU(_bA(this._ba)); 
		if (_bZ())return this ; 
		if (_bS(_bi4).indexOf("Selected")>0){
			if (!this.getTree()._bO5("OnBeforeUnselect", { "NodeId": this._ba } )) return; 
			_bp(_bi4,"ktvSelected"); 
			var _bi3=this.getTree(); 
			var _bl5=_bi3._bo4(); 
			for (var i=0; i<_bl5.selectedIds.length; i++) if (_bl5.selectedIds[i]==this._ba){_bl5.selectedIds.splice(i,1); break; }
			_bi3._bl4(_bl5); 
			this.getTree()._bO5("OnUnselect", { "NodeId": this._ba } ); 
			}
		return this ; 
		} ,
	expand:function (){
		var _bi5=_bA(this._ba); 
		var _bI5=_bu(_bU(_bi5)); 
		if (_bO(_bI5)){
			if (!this.getTree()._bO5("OnBeforeExpand", { "NodeId": this._ba } )) return; 
			var _bo6=this._bo2("Plus"); 
			if (_bO(_bo6))_bN("Plus","Minus",_bo6); 
			_bl(_bI5,1); 
			if (this.getTree()._bO6){
				var _bl6=new Array(); 
				var _bi6=this._ba; 
				while (_bi6.indexOf(".root")<0){
					_bl6.push(_bi6); 
					_bi6=(new _bi1(_bi6)).getParentId(); 
					}
				_bl6.push(_bi6); 
				this.getTree()._bI6(_bl6); 
				} 
			this.getTree()._bo7(this._ba,1); 
			this.getTree()._bO5("OnExpand", { "NodeId": this._ba } ); 
			}
		else {
			var _bi4=_bU(_bA(this._ba)); 
			var _bI4=null; 
			for (var i=0; i<_bi4.childNodes.length; i++)if (_bi4.childNodes[i].nodeName=="INPUT")_bI4=_bi4.childNodes[i]; 
			if (_bO(_bI4)){
				var _bo5=eval("__="+_bI4.value); 
				if (_bO(_bo5.url) && _bo5.url!=""){
					_bo5.url=decodeURIComponent(_bo5.url); 
					if (!this.getTree()._bO5("OnBeforeExpand", { "NodeId": this._ba } ))return; 
					this.loadSubTree(_bo5.url); 
					_bo5.loading=1; 
					_bI4.value=_bJ(_bo5); 
					}
				}
			}
		return this ; 
		} ,
	collapse:function (){
		var _bi5=_bA(this._ba); 
		var _bI5=_bu(_bU(_bi5)); 
		if (_bZ())return this ; 
		if (_bO(_bI5)){
			if (!this.getTree()._bO5("OnBeforeCollapse", { "NodeId": this._ba } ))return; 
			var _bO7=this._bo2("Minus"); 
			if (_bO(_bO7))_bN("Minus","Plus",_bO7); 
			_bl(_bI5,0); 
			this.getTree()._bo7(this._ba,0); 
			this.getTree()._bO5("OnCollapse", { "NodeId": this._ba } ); 
			}
		return this ; 
		} ,
	getChildIds:function (){
		var _bI5=_bu(_bU(_bA(this._ba))); 
		var _bl7=new Array(); 
		if (_bO(_bI5)){
			for (var i=0; i<_bI5.childNodes.length; i++)_bl7.push(_bI5.childNodes[i].id); 
			}
		return _bl7; 
		} ,
	getParentId:function (){
		return _bE(_bA(this._ba),2).id; 
		} ,
	getTree:function (){
		var _bi6=this._ba; 
		while (_bi6.indexOf(".root")<0){_bi6=(new _bi1(_bi6)).getParentId(); }
		return eval(_bi6.replace(".root","")); 
		} ,
	moveToAbove:function (_bi7){
		if ((new _bi1(_bi7).getParentId()==this.getParentId())){
			var _bi5=_bA(this._ba); 
			var _bI7=_bA(_bi7); 
			_bE(_bi5).insertBefore(_bi5,_bI7); 
			(new _bi1(this.getParentId()))._bo8(); 
			}
		return this ; 
		} ,
	moveToBelow:function (_bi7){
		if ((new _bi1(_bi7).getParentId()==this.getParentId())){
			var _bi5=_bA(this._ba); 
			var _bI7=_bA(_bi7); 
			var _bI5=_bE(_bi5); 
			if (_bI5.lastChild==_bI7)_bI5.appendChild(_bi5); 
			else _bI5.insertBefore(_bi5,_bu(_bI7)); 
			(new _bi1(this.getParentId()))._bo8(); 
			}
		return this ; 
		} ,
	attachTo:function (_bi7){
		var _bO8=_bi7; 
		var _bl8= false; 
		while (_bO8.indexOf(".root")<0){
			_bO8=(new _bi1(_bO8)).getParentId(); 
			if (_bO8==this._ba)_bl8= true; 
			}
		if (_bl8){return false; }
		var _bi6=this.getParentId(); 
		if (_bi6==_bi7){return false; }
		var _bi5=_bA(_bi7); 
		var _bI5=_bu(_bU(_bi5)); 
		if (!_bO(_bI5)){
			_bI5=_bY("ul",_bi5); 
			_bR(_bI5,"ktvUL"); 
			(new _bi1(_bi7)).getTree()._bo7(_bi7,1); 
			}
		_bI5.appendChild(_bA(this._ba)); 
		(new _bi1(_bi7))._bo8(); 
		(new _bi1(_bi6))._bo8(); 
		return true; 
		} ,
	loadSubTree:function (_bi8){
		if (typeof koolajax!="undefined" && _bO(koolajax)){
			if (_bZ())return this ; 
			var _bI8=this._bo2("Loading"); 
			if (!_bO(_bI8)){
				if (!this.getTree()._bO5("OnBeforeSubTreeLoad", { "NodeId": this._ba,"Url":_bi8 } ))return; 
				var _bi4=_bU(_bA(this._ba)); 
				_bI8=_bY("span",_bi4); 
				_bR(_bI8,"ktvLoading"); 
				koolajax.load(_bi8,eval("__=function(ct){"+this.getTree()._ba+".OSTLD('"+this._ba+"',ct);}")); 
				}
			}
		return this ; 
		} ,
	_bo9:function (_bO9){
		var _bi5=_bA(this._ba); 
		var _bi3=this.getTree(); 
		var _bI5=_bu(_bU(_bi5)); 
		if (_bO(_bI5)){ this.getTree()._bt(_bI5); }
		else {_bI5=_bY("ul",_bi5); _bR(_bI5,"ktvUL"); }
		_bI5.innerHTML+=_bO9; 
		var _bI8=this._bo2("Loading"); 
		if (_bO(_bI8))_bU(_bi5).removeChild(_bI8); 
		_bi3._bz(_bI5); this._bo8(); 
		_bi3._bO5("OnSubTreeLoad", { "NodeId": this._ba } ); 
		var _bi4=_bU(_bi5); 
		var _bI4=null; 
		for (var i=0; i<_bi4.childNodes.length; i++) if (_bi4.childNodes[i].nodeName=="INPUT")_bI4=_bi4.childNodes[i]; 
		if (_bO(_bI4)){
			var _bo5=eval("__="+_bI4.value); 
			if (_bO(_bo5.loading) && _bo5.loading==1){ 
				delete _bo5.loading; 
				_bI4.value=_bJ(_bo5); 
				var _bl9=this._bo2("PM"); 
				_bN("Plus","Minus",_bl9); 
				_bi3._bo7(this._ba,1); 
				_bi3._bO5("OnExpand", { "NodeId": this._ba } ); 
				_bi3.rECSFC(); 
				}
			}
		} ,
	addChildNode:function (_bi7,_bO2,_bl2){
		var _bi9=_bA(this._ba); 
		var _bI5=_bu(_bU(_bi9)); 
		if (!_bO(_bI5)){
			_bI5=_bY("ul",_bi9); 
			_bR(_bI5,"ktvUL");
			}
		var _bi5=_bY("li",_bI5); 
		_bi5.id=_bi7; 
		_bR(_bi5,"ktvLI"); 
		var _bI9=_bY("div",_bi5); 
		_bR(_bI9,"ktvBot"); 
		if (_bO(_bl2)){
			var _boa=_bY("img",_bI9); 
			_bR(_boa,"ktvImage"); 
			_boa.src=_bl2; _boa.alt=""; 
			}
		_bO2=(_bO(_bO2))?_bO2: ""; 
		var _bOa=_bY("span",_bI9); 
		_bR(_bOa,"ktvText"); 
		_bOa.innerHTML=_bO2; 
		_bz(_bOa,"click",_bla, false); 
		_bz(_bOa,"mouseover",_bia, false); 
		_bz(_bOa,"mouseout",_bIa, false); 
		_bz(_bOa,"mousedown",_bob, false); 
		_bz(_bOa,"mouseup",_bOb, false); 
		this._bo8(); 
		return this ; 
		} ,
	_blb:function (_bi7){ 
		(new _bi1(_bi7)).unselect(); 
		var _bI7=_bA(_bi7); 
		var _bI5=_bE(_bI7); 
		this.getTree()._bt(_bI7); 
		_bI5.removeChild(_bI7); 
		this._bo8(); 
		} ,
	removeAllChildren:function (){
		var _bi5=_bA(this._ba); 
		var _bI5=_bu(_bU(_bi5)); 
		if (_bO(_bI5)){ 
			this.getTree()._bt(_bI5); 
			_bi5.removeChild(_bI5); 
			this._bo8(); 
			}
		} ,
	_bo8:function (_bib){
		var _bO4=this.getChildIds(); 
		for (var i=0; i<_bO4.length; i++){
			var _bi5=_bA(_bO4[i]); 
			var _bi4=_bU(_bi5); 
			_bp(_bi5,"ktvFirst"); 
			_bp(_bi5,"ktvLast"); 
			_bN("ktvTop","ktvMid",_bi4); 
			_bN("ktvBot","ktvMid",_bi4); 
			if (i==0){
				_bQ(_bi5,"ktvFirst"); 
				_bN("ktvMid","ktvTop",_bi4); 
				}
			if (i==_bO4.length-1){
				_bQ(_bi5,"ktvLast"); 
				_bN("ktvMid","ktvBot",_bi4); 
				_bN("ktvTop","ktvBot",_bi4); 
				}
			}
		var _bIb=this._bo2("PM"); 
		if (_bO4.length==0){
			if (_bO(_bIb)){
				_bt(_bIb,"click",_boc, false); 
				_bE(_bIb).removeChild(_bIb); 
				}
			var _bi5=_bA(this._ba); 
			var _bI5=_bu(_bU(_bi5)); 
			if (_bO(_bI5))_bi5.removeChild(_bI5); 
			}
		else {
			if (!_bO(_bIb)){
				var _bi4=_bU(_bA(this._ba)); 
				var _bI5=_bu(_bi4); 
				_bIb=_bY("span",_bi4); 
				_bi4.insertBefore(_bIb,_bU(_bi4)); 
				_bR(_bIb,"ktvPM ktv"+(_bk(_bI5)?"Minus": "Plus")); 
				_bz(_bIb,"click",_boc, false); 
				}
			}
		} ,
	isExpanded:function (){
		return _bO(this._bo2("Minus")); 
		} ,
	isSelected:function (){
		var _bi4=_bU(_bA(this._ba)); 
		return (_bS(_bi4).indexOf("Selected")>0);
		} ,
	startEdit:function (_bi0){
		if (_bZ())return this ; 
		if (!this.getTree()._bO5("OnBeforeStartEdit", { "NodeId": this._ba } ))return; 
		var _bO2=this._bo2("Text"); 
		_bi4=_bU(_bA(this._ba)); 
		_bl(_bO2,0); 
		var _bI4=_bY("input",_bi4); 
		_bz(_bI4,"blur",_bOc, false); 
		_bz(_bI4,"keypress",_blc, false); 
		_bR(_bI4,"ktvEdit"); 
		_bI4.value=_bO(_bi0)?_bi0:_bO2.innerHTML; 
		_bI4.focus(); 
		_bI4.select(); 
		this.getTree()._bO5("OnStartEdit", { "NodeId": this._ba } ); 
		return this ; 
		} ,
	endEdit:function (_bic){
		if (!this.getTree()._bO5("OnBeforeEndEdit", { "NodeId": this._ba } ))return; 
		var _bI4=this._bo2("Edit"); 
		var _bO2=this._bo2("Text"); 
		_bt(_bI4,"blur",_bOc, false); 
		_bt(_bI4,"keypress",_blc, false); 
		if (!_bO(_bic))_bic= true; 
		if (_bic)_bO2.innerHTML=_bI4.value; 
		_bO2.style.display=""; 
		_bE(_bI4).removeChild(_bI4); 
		this.getTree()._bO5("OnEndEdit", { "NodeId": this._ba } ); 
		return this ; 
		} ,
	_bo2:function (_bq){
		var _bi5=_bA(this._ba); 
		var _bIc=_bL(_bU(_bi5),"ktv"+_bq); 
		return _bIc; 
		} ,
	_bod:function (_bg){
		var _bi3=this.getTree(); 
		if (_bi3._bOd){
			var _bld=this.isSelected(); 
			var _bl5=_bi3._bo4(); 
			var _bid=" "+_bl5.selectDisableIds.join(" "); 
			if (_bid.indexOf(" "+this._ba)<0){
				if (!_bh || !_bi3._bId){_bi3.unselectAll(); } 
				this.select(); 
				}
			if (_bld && _bi3._boe){
				var _bl5=_bi3._bo4(); 
				var _bOe=" "+_bl5.editDisableIds.join(" "); 
				if (_bOe.indexOf(" "+this._ba)<0){ this.startEdit(); }
				}
			}
		} ,
	_ble:function (_bg,_bic){ this.endEdit(_bic); } ,
	_bie:function (_bg){
		if (this.isExpanded())this.collapse(); 
		else this.expand(); 
		} ,
	_bIe:function (_bg){
		var _bi4=_bU(_bA(this._ba)); 
		_bQ(_bi4,"ktvOver"); 
		if (_bof && this._bOf()){_bQ(_bi4,"ktvDrop"); }
		} ,
	_bIf:function (_bg){
		var _bi4=_bU(_bA(this._ba)); 
		_bp(_bi4,"ktvOver"); 
		if (_bof && this._bOf()){_bp(_bi4,"ktvDrop"); }
		} ,
	_bOf:function (){
		if (_bZ())return false; 
		var _bi3=this.getTree(); 
		var _bO4=" "+_bi3._bo4().dropDisableIds.join(" "); 
		return (_bi3._bog && _bO4.indexOf(" "+this._ba)<0); 
		} ,
	_bOg:function (_bg){
		if (_bof && this._bOf()){
			var _bi4=_bU(_bA(this._ba)); 
			_bp(_bi4,"ktvDrop"); 
			if (!this.getTree()._bO5("OnBeforeDrop", { "NodeId": this._ba,"DragNodeId":_blg } ))return; 
			var _big= false; 
			if (this._ba!=_blg){_big=(new _bi1(_blg)).attachTo(this._ba); } 
			this.getTree()._bO5("OnDrop", { "NodeId": this._ba,"DragNodeId":_blg,"Succeed":_big } ); 
			}
		} ,
	_bIg:function (){
		if (_bZ())return false; 
		var _bi3=this.getTree(); 
		var _bO4=" "+_bi3._bo4().dragDisableIds.join(" "); 
		return (_bi3._bog && _bO4.indexOf(" "+this._ba)<0); 
		} ,
	_boh:function (_bg){
		var _bi4=_bU(_bA(this._ba)); 
		var _bI9=_bi4.cloneNode( true); 
		var _bOh=_bL(_bI9,"ktvPM"); 
		if (_bO(_bOh))_bI9.removeChild(_bOh); 
		var _blh=_bY("div",document.body); 
		_blh.id="__"+this._ba; 
		var _bih=_bS(_bA(this.getTree()._ba)); 
		_bR(_blh,_bih);_bQ(_bI9,"ktvDrag"); 
		_blh.style.position="absolute"; 
		_blh.appendChild(_bI9); 
		var _bIh=_bD(_bg); 
		_blh.style.top=_bIh._bc+"px"; 
		_blh.style.left=(_bIh._bC+5)+"px"; 
		this.getTree()._bO5("OnDrag", { "NodeId": this._ba } ); 
		} ,
	_boi:function (_bg){
		var _blh=_bA("__"+this._ba); 
		var _bIh=_bD(_bg); 
		_blh.style.top=_bIh._bc+"px"; 
		_blh.style.left=(_bIh._bC+5)+"px"; 
		} ,
	_bOi:function (_bg){
		var _blh=_bA("__"+this._ba); 
		document.body.removeChild(_blh); 
		}
	};

function CoolTreeView(_ba,_bO6,_bOd,_bId,_bog,_boe,_bli,_bii,_bIi){ 
	this._ba=_ba; 
	this._bId=_bId; 
	this._bOd=_bOd; 
	this._bog=_bog; 
	this._boe=_boe; 
	this._bO6=_bO6; 
	this._bli=_bli.toLowerCase(); 
	this._bii=_bii; 
	this._boj=new Array(); 
	_bA(_ba+".clientState").value=_bIi; 
	this._bb(); 
	}

CoolTreeView.prototype= {
	getSelectedIds:function (){
		var _bIi=this._bo4(); 
		return (_bO(_bIi.selectedIds))?_bIi.selectedIds: (new Array()); 
		} ,
	unselectAll:function (){
		var _bO4=this.getSelectedIds(); 
		for (var i=0; i<_bO4.length; i++)(new _bi1(_bO4[i])).unselect(); 
		return this ; 
		} ,
	removeNode:function (_bi7){
		var _bOj=this.getNode(this.getNode(_bi7).getParentId()); 
		_bOj._blb(_bi7); 
		return this ; 
		} ,
	getNode:function (_bi7){return new _bi1(_bi7); } ,
	expandAll:function (){
		if (_bZ())return this ; 
		var _blj=_bA(this._ba+".root"); 
		var _bij=_blj.getElementsByTagName("ul"); 
		for (var i=0; i<_bij.length; i++)if (_bS(_bij[i]).indexOf("ktvUL")>-1){
			_bl(_bij[i],1); 
			var _bi4=_bU(_bE(_bij[i])); 
			var _bl9=_bL(_bi4,"ktvPM"); 
			_bN("Plus","Minus",_bl9); 
			}
		return this ; 
		} ,
	collapseAll:function (){
		if (_bZ())return this ; 
		this._bI6(new Array()); 
		return this ; 
		} ,
	_bI6:function (_bl6){
		if (_bZ())return this ; 
		var _bIj=_bl6.join(" "); 
		var _blj=_bA(this._ba+".root"); 
		var _bij=_blj.getElementsByTagName("ul"); 
		for (var i=0; i<_bij.length; i++){
			var _bi7=_bE(_bij[i]).id; 
			if (_bS(_bij[i]).indexOf("ktvUL")>-1 && _bIj.indexOf(_bi7)<0){
				_bl(_bij[i],0); 
				var _bi4=_bU(_bE(_bij[i])); 
				var _bl9=_bL(_bi4,"ktvPM"); 
				_bN("Minus","Plus",_bl9); 
				}
			}
		} ,
	_bo4:function (){
		var _bok=_bA(this._ba+".clientState"); 
		var _bIi=eval("__="+_bok.value); 
		return _bIi; 
		} ,
	_bl4:function (_bIi){
		var _bok=_bA(this._ba+".clientState"); 
		_bok.value=_bJ(_bIi); 
		} ,
	OSTLD:function (_bi7,_bO9){ 
		(new _bi1(_bi7))._bo9(_bO9); 
		} ,
	_bz:function (_bOk){
		var _blk=_bOk.getElementsByTagName("li"); 
		for (var i=0; i<_blk.length; i++)if (_bS(_blk[i]).indexOf("ktvLI")!= -1){
			_bi4=_bU(_blk[i]); 
			_bIb=_bL(_bi4,"ktvPM"); 
			if (_bO(_bIb))_bz(_bIb,"click",_boc, false); 
			_bOa=_bL(_bi4,"ktvText"); 
			_bz(_bOa,"click",_bla, false); 
			_bz(_bOa,"mouseover",_bia, false); 
			_bz(_bOa,"mouseout",_bIa, false); 
			_bz(_bOa,"mousedown",_bob, false); 
			_bz(_bOa,"mouseup",_bOb, false); 
			}
		} ,
	_bt:function (_bOk){
		var _blk=_bOk.getElementsByTagName("li"); 
		for (var i=0; i<_blk.length; i++)if (_bS(_blk[i]).indexOf("ktvLI")!= -1){
			_bi4=_bU(_blk[i]); 
			_bIb=_bL(_bi4,"ktvPM"); 
			if (_bO(_bIb))_bt(_bIb,"click",_boc, false); 
			_bOa=_bL(_bi4,"ktvText"); 
			_bt(_bOa,"click",_bla, false); 
			_bt(_bOa,"mouseover",_bia, false); 
			_bt(_bOa,"mouseout",_bIa, false); 
			_bt(_bOa,"mousedown",_bob, false); 
			_bt(_bOa,"mouseup",_bOb, false); 
			}
		} ,
	_bb:function (){
		var _bi3=document.getElementById(this._ba); 
		_bi3.onselectstart=_bik; 
		this._bz(_bi3); 
		setTimeout(this._ba+".rECSFC()",0); 
		} ,
	rECSFC:function (){
		var _bIk=""; 
		switch (this._bli){
			case "onpage":
				var _bol=window.location.href.indexOf("?"); 
				_bIk=(_bol<0)?window.location.href:window.location.href.substring(0,_bol)+"_"+this._ba+"_opcl"; 
				break; 
			case "crosspage":
				_bIk=this._ba+"_opcl"; 
				break; 
			case "none":
			default:return; 
				break; 
			}
		var _bO2=_bB[_bIk]; 
		_bO2=_bO(_bO2)?_bO2: "{}"; 
		var _bll=eval("__="+_bO2); 
		var _blk=_bA(this._ba).getElementsByTagName("li"); 
		for (var i=0; i<_blk.length; i++) if (_bS(_blk[i]).indexOf("ktvLI")!= -1){
			if (_bO(_bll[_blk[i].id])){
				var _bil=this.getNode(_blk[i].id); 
				if (_bll[_bil._ba]==1 && !_bil.isExpanded()){_bil.expand();}
				else if (_bll[_bil._ba]==0 && _bil.isExpanded()){_bil.collapse(); }
				}
			}
		} ,
	_bo7:function (_bi7,_bIl){
		var _bIk=""; 
		switch (this._bli){
			case "onpage":
				var _bol=window.location.href.indexOf("?"); 
				_bIk=((_bol<0)?window.location.href:window.location.href.substring(0,_bol))+"_"+this._ba+"_opcl"; 
				break; 
			case "crosspage":
				_bIk=this._ba+"_opcl"; 
				break; 
			case "none":
			default:return; 
				break; 
			}
		var _bO2=_bB[_bIk]; 
		_bO2=_bO(_bO2)?_bO2: "{}"; 
		var _bll=eval("__="+_bO2); 
		_bll[_bi7]=_bIl; 
		_bB._bl0(_bIk,_bJ(_bll),this._bii); 
		} ,
	registerEvent:function (_bH,_bom){
		if (_bZ())return this ; 
		this._boj[_bH]=_bom; 
		} ,
	_bO5:function (_bH,_bOm){
		if (_bZ())return true; 
		return (_bO(this._boj[_bH]))?this._boj[_bH](this,_bOm): true; 
		}
	};

function _boc(_bg){(new _bi1(_bE(this,2).id))._bie(_bg); }

function _bla(_bg){ (new _bi1(_bE(this,2).id))._bod(_bg); }

function _bia(_bg){ (new _bi1(_bE(this,2).id))._bIe(_bg); }

function _bIa(_bg){ (new _bi1(_bE(this,2).id))._bIf(_bg); }

function _bOc(_bg){ (new _bi1(_bE(this,2).id))._ble(_bg); }

function _blc(_bg){
	var _bF=(window.event)?event.keyCode:_bg.keyCode; 
	if (_bF==13 || _bF==27){ 
		(new _bi1(_bE(this,2).id))._ble(_bg,(_bF==13)); 
		if (_bF==13){
			if (_bg.stopPropagation){
				_bg.stopPropagation(); 
				_bg.preventDefault(); 
				}
			else {
				event.cancelBubble= true; 
				event.returnValue= false; 
				}
			return false; 
			}
		}
	}

var _bIm=0,_bon,_bof,_blg; 
var _bOn= true; 

function _bob(_bg){
	if ((new _bi1(_bE(this,2).id))._bIg(_bg)){
		if (_bg.preventDefault)_bg.preventDefault(); 
		_bOn= false; 
		_blg=_bE(this,2).id; 
		_bon=_bD(_bg); _bIm=1; 
		_bof= false; 
		_bz(document,"mousemove",_bIn, false); 
		_bz(document,"mouseup",_boo, false); 
		if (_bg.stopPropagation!=null)_bg.stopPropagation(); 
		else event.cancelBubble= true; 
		}
	}

function _bIn(_bg){
	if (_bIm==1 || _bIm==2){
		if (_bof){ 
			(new _bi1(_blg))._boi(_bg); 
			}
		else {
			var _bIh=_bD(_bg); 
			if (Math.abs(_bIh._bC-_bon._bC)>10 || Math.abs(_bIh._bc-_bon._bc)>10){
				_bof= true; 
				(new _bi1(_blg))._boh(_bg); 
				}
			}
		}
	_bIm=2; 
	}

function _boo(_bg){
	if (_bIm==1){}if (_bIm==2){
		if (_bof){ 
			(new _bi1(_blg))._bOi(_bg); 
			_bof= false; 
			}
		}
	_bt(document,"mousemove",_bIn, false); 
	_bt(document,"mouseup",_boo, false); 
	_bOn= true; 
	}
	
function _bOb(_bg){ 
	(new _bi1(_bE(this,2).id))._bOg(_bg); 
	}
	
function _bik(){
	if (_bh || !_bOn)return false; 
	}
	
if (typeof(__KTVInits)!="undefined" && _bO(__KTVInits)){
	for (var i=0; i<__KTVInits.length; i++){__KTVInits[i](); }
	} 
	<?php 
	_bl8(); 
	_blp(); 
	} 
if (!class_exists("CoolTreeView",FALSE)) { 
	function _blq($_bOq) { 
		return _bl5("\053"," ",urlencode($_bOq)); 
		} 
	
	class treenode { 
		var $id; 
		var $text; 
		var $image; 
		var $_blr; 
		var $expand=FALSE; 
		var $subTreeUrl; 
		var $visible=TRUE; 
		var $showPlusMinus=TRUE; 
		var $_bls; var $_bOs; 
		
		function __construct($_blt,$_bO3="",$_bOt=FALSE,$_blu="",$_bOu="") { 
			$this->id =$_blt; 
			$this->text =$_bO3; 
			$this->image =$_blu; 
			$this->expand =$_bOt; 
			$this->subTreeUrl =$_bOu; 
			$this->_blr =array(); 
			$this->_bls =array(); 
			} 
		
		function addchild($_blv) { 
			$_blv->_bOs =$this; 
			array_push($this->_blr ,$_blv); 
			} 
		
		function adddata($_bOv,$_blw) { 
			$this->_bls[$_bOv]=$_blw; 
			} 
		} 
	
	class cooltreeview { 
		var $_bl0="2.0.\067.1"; 
		var $id; 
		var $_bOw; 
		var $_blx; 
		var $_bOx; 
		var $width=""; 
		var $height=""; 
		var $overflow=""; 
		var $styleFolder; 
		var $imageFolder; 
		var $selectedIds; 
		var $selectEnable=TRUE; 
		var $selectDisableIds; 
		var $multipleSelectEnable=FALSE; 
		var $DragAndDropEnable=FALSE; 
		var $dragDisableIds; 
		var $dropDisableIds; 
		var $EditNodeEnable=FALSE; 
		var $editDisableIds; 
		var $isSubTree=FALSE; 
		var $singleExpand=FALSE; 
		var $keepState="none"; 
		var $keepStateHours=030; 
		var $showLines=FALSE; 
		var $scriptFolder=""; 
		
		function __construct($_blt) { 
			$this->id =$_blt; 
			$this->_bOw =new treenode("root"); 
			$this->_bOx =array(); 
			$this->_bOx["root"]=$this->_bOw; 
			} 
			
		function loadxml($_bly) { 
			if (gettype($_bly) == "string") { 
				$_bO1=new domdocument(); 
				$_bO1->loadxml($_bly); 
				$_bly=$_bO1->documentElement; 
				} 
			$id=$_bly->getattribute("id"); 
			if ($id != "") $this->id =$id; 
			$this->width =$_bly->getattribute("width"); 
			$this->height =$_bly->getattribute("height"); 
			$this->overflow =$_bly->getattribute("overflow"); 
			$this->styleFolder =$_bly->getattribute("styleFolder"); 
			$this->imageFolder =$_bly->getattribute("imageFolder"); 
			$this->selectedIds =$_bly->getattribute("selectedIds"); 
			$this->selectDisableIds =$_bly->getattribute("selectDisableIds"); 
			$this->dragDisableIds =$_bly->getattribute("dragDisableIds"); 
			$this->dropDisableIds =$_bly->getattribute("dropDisableIds"); 
			$this->editDisableIds =$_bly->getattribute("editDisableIds"); 
			$_bOy=$_blz->getattribute("scriptFolder"); 
			if ($_bOy != "") $this->scriptFolder =$_bOy; 
			$_bOz=$_bly->getattribute("selectEnable"); 
			$this->selectEnable =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
			$_bOz=$_bly->getattribute("multipleSelectEnable"); 
			$this->multipleSelectEnable =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
			$_bOz=$_bly->getattribute("DragAndDropEnable"); 
			$this->DragAndDropEnable =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
			$_bOz=$_bly->getattribute("EditNodeEnable"); 
			$this->EditNodeEnable =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
			$_bOz=$_bly->getattribute("isSubTree"); 
			$this->isSubTree =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
			$_bOz=$_bly->getattribute("showOnExpand"); 
			$this->showOnExpand =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
			$_bOz=$_bly->getattribute("keepState"); 
			if ($_bOz != "") $this->keepState =$_bOz; 
			$_bOz=$_bly->getattribute("keepStateHours"); 
			if ($_bOz != "") $this->keepStateHours =intval($_bOz); 
			$_bOz=$_bly->getattribute("singleExpand"); 
			$this->singleExpand =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
			foreach ($_bly->childNodes as $_bl10) { 
				switch (strtolower($_bl10->nodeName)) { 
					case "rootnode": 
						$this->_bOw->text =$_bl10->getattribute("text"); 
						$this->_bOw->image =$_bl10->getattribute("image"); 
						$this->_bOw->subTreeUrl =$_bl10->getattribute("subTreeUrl"); 
						$_bOz=$_bl10->getattribute("expand"); 
						$this->_bOw->expand =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
						$_bOz=$_bl10->getattribute("visible"); 
						$this->_bOw->visible =($_bOz == "") ? TRUE: (($_bOz == "true") ? TRUE: FALSE); 
						$_bOz=$_bl10->getattribute("showPlusMinus"); 
						$this->_bOw->showPlusMinus =($_bOz == "") ? TRUE: (($_bOz == "true") ? TRUE: FALSE); 
						$this->_bO10($this->_bOw ,$_bl10); 
						break; 
					case "templates": 
						break; 
					} 
				} 
			} 
		
		function _bO10($_bl11,$_bO11) { 
			foreach ($_bO11->childNodes as $_bl12) { 
				if ($_bl12->nodeName == "node") { 
					$id=$_bl12->getattribute("id"); 
					$_bO12=new treenode($id); 
					$_bO12->text =$_bl12->getattribute("text"); 
					$_bO12->image =$_bl12->getattribute("image"); 
					$_bO12->subTreeUrl =$_bl12->getattribute("subTreeUrl"); 
					$_bOz=$_bl12->getattribute("expand"); 
					$_bO12->expand =($_bOz == "") ? FALSE: (($_bOz == "true") ? TRUE: FALSE); 
					$this->_bO10($_bO12,$_bl12); $_bl11->addchild($_bO12); 
					} 
				} 
			} 
		
		function render() { 
			$_bl13=""; 
			if ($this->isSubTree) { 
				$this->_bO13(); 
				for ($_blc=0; $_blc<sizeof($this->_bOw->_blr); $_blc ++) $_bl13.=$this->_bl14($this->_bOw->_blr[$_blc]); 
				} 
			else { 
				$_bl13="\n<!--CoolTreeView version ".$this->_bl0." - www.coolcss.net -->\n"; 
				$_bl13.=$this->registercss(); 
				$_bl13.=$this->rendertree(); 
				$_bO14= isset ($_POST["__koolajax"]) || isset ($_GET["__koolajax"]); 
				$_bl13.=($_bO14) ? "": $this->registerscript(); 
				$_bl13.="<script type='text/javascript'>"; 
				$_bl13.=$this->startupscript(); 
				$_bl13.="</script>"; 
				} 
			return $_bl13; 
			} 
		
		function add($_bl15,$_blt,$_bO3="",$_bOt=FALSE,$_blu="",$_bOu="") { 
			$_bO15=new treenode($_blt); 
			$_bO15->text =$_bO3; 
			$_bO15->expand =$_bOt; 
			$_bO15->image =$_blu; 
			$_bO15->subTreeUrl =$_bOu; 
			$this->_bOx[$_bl15]->addchild($_bO15); 
			$this->_bOx[$_blt]=$_bO15; 
			return $_bO15; 
			} 
		
		function getrootnode() { return $this->_bOw; } 
		
		function getnode($_bl16) { return $this->_bOx[$_bl16]; } 
		
		function _bO13() { 
			$this->styleFolder =_bl5("\134","/",$this->styleFolder); 
			$_bO16=trim($this->styleFolder ,"/"); 
			$_bl17=strrpos($_bO16,"/"); 
			$this->_blx =substr($_bO16,($_bl17 ? $_bl17: -1)+1); 
			} 
		
		function registercss() { 
			$this->_bO13(); 
			$_bO17="<script type='text/javascript'>if (document.getElementById('__{style}KTV')==null){var _head = document.getElementsByTagName('head')[0];var _link = document.createElement('link'); _link.id = '__{style}KTV';_link.rel='stylesheet'; _link.href='{stylepath}/{style}/{style}.css';_head.appendChild(_link);}</script>"; 
			$_bl18=_bl5("{style}",$this->_blx ,$_bO17); 
			$_bl18=_bl5("{stylepath}",$this->_bO18(),$_bl18); 
			return $_bl18; 
			} 
		
		function rendertree() { 
			$this->_bO13(); 
			$_bl19="<input type='hidden' id='{id}.clientState' name='{id}.clientState' />"; 
			$_blj=_bl5("{id}",$this->id ,_blg()); 
			$_blj=_bl5("{style}",$this->_blx ,$_blj); 
			$_blj=_bl5("{nopadding}",(!$this->_bOw->visible || !$this->_bOw->showPlusMinus) ? "ktvNoPadding": "",$_blj); 
			$_blj=_bl5("{subnodes}",$this->_bl14($this->_bOw),$_blj); 
			$_blj=_bl5("{lines}",(($this->showLines) ? "ktvLines": ""),$_blj); 
			$_bO19=_bl5("{id}",$this->id ,$_bl19); 
			if (_bOi($_blj)) {$_blj=_bl5("{clientstate}",$_bO19,$_blj); } 
			$_blj=_bl5("{width}",(($this->width != "") ? "width:".$this->width.";": ""),$_blj); 
			$_blj=_bl5("{height}",(($this->height != "") ? "height:".$this->height.";": ""),$_blj); 
			$_blj=_bl5("{overflow}",(($this->overflow != "") ? "overflow:".$this->overflow.";": ""),$_blj); 
			$_blj=_bl5("{version}",$this->_bl0 ,$_blj); 
			return $_blj; 
			} 
		
		function _bl14($_bl11) { 
			$_bl1a="<ul class='ktvUL' style='display:{display}'>{subnodes}</ul>"; 
			$_bO1a="<li id='{nodeid}' class='{class}'>{nodecontent}{subnodes}</li>"; 
			$_bl1b="<div class='{class}'>{plusminus}{image}{text}{nodedata}</div>"; 
			$_bO1b="<span class='ktvPM ktv{plusminus}'> </span>"; 
			$_bl1c="<img src='{image}' class='ktvImage' alt=''/>"; 
			$_bO1c="<span class='ktvText'>{text}</span>"; 
			$_bl1d="<input id='{nodeid}_data' type='hidden' value='{value}'/>"; 
			$_bO1d=$_bO1a; 
			$_bl1e=$_bl1b; 
			$_bOz=_bl5("{text}",$_bl11->text ,$_bO1c); 
			$_bl1e=_bl5("{text}",$_bOz,$_bl1e); 
			$_bO1e=""; if ($_bl11->image != "") { 
				$_bOz=_bl5("{image}",(($this->imageFolder != "") ? $this->imageFolder."/": "").$_bl11->image ,$_bl1c); 
				$_bl1e=_bl5("{image}",$_bOz,$_bl1e); 
				} 
			else { $_bl1e=_bl5("{image}","",$_bl1e); } 
			if (sizeof($_bl11->_blr)>0) { 
				$_bOz=_bl5("{plusminus}",($_bl11->expand) ? "Minus": "Plus",$_bO1b); 
				$_bl1e=_bl5("{plusminus}",$_bOz,$_bl1e); 
				$_bO1e=""; 
				for ($_blc=0; $_blc<sizeof($_bl11->_blr); $_blc ++) { $_bO1e.=$this->_bl14($_bl11->_blr[$_blc]); } 
				$_bO1e=_bl5("{subnodes}",$_bO1e,$_bl1a); 
				$_bO1e=_bl5("{display}",($_bl11->expand) ? "block": "none",$_bO1e); 
				} 
			else { 
				if ($_bl11->subTreeUrl != "") {
					$_bOz=_bl5("{plusminus}","Plus",$_bO1b); 
					$_bl1e=_bl5("{plusminus}",$_bOz,$_bl1e); 
					} 
				else {$_bl1e=_bl5("{plusminus}","",$_bl1e);} 
				} 
			if ($_bl11->subTreeUrl != "" || sizeof($_bl11->_bls)>0) { 
				$_bl1f=_blq($_bl11->subTreeUrl); 
				$_bO1f=array(); 
				foreach ($_bl11->_bls as $_bl1g => $_bO1g) { $_bO1f[$_bl1g]=_blq($_bO1g); } 
				$_bls=array("url" => $_bl1f,"data" => $_bO1f); 
				$_bl1h=_bl5("{nodeid}",(($_bl11 === $this->_bOw) ? $this->id.".": "").$_bl11->id ,$_bl1d); 
				$_bl1h=_bl5("{value}",json_encode($_bls),$_bl1h); 
				$_bl1e=_bl5("{nodedata}",$_bl1h,$_bl1e); 
				} 
			else { $_bl1e=_bl5("{nodedata}","",$_bl1e); } 
			$_bO1h="ktvLI"; 
			if (( isset ($_bl11->_bOs->_blr[0]) && $_bl11->_bOs->_blr[0] === $_bl11) || $_bl11 === $this->_bOw) {$_bO1h.=" ktvFirst"; } 
			if (( isset ($_bl11->_bOs->_blr) && isset ($_bl11->_bOs->_blr[sizeof($_bl11->_bOs->_blr)-1]) && $_bl11->_bOs->_blr[sizeof($_bl11->_bOs->_blr)-1] === $_bl11) || $_bl11 === $this->_bOw) { $_bO1h.=" ktvLast"; } 
			$_bl1i=""; 
			if ($_bl11 === $this->_bOw) { 
				$_bl1i="ktvTop"; 
				if (!$_bl11->visible) $_bl1i.=" ktvInv"; 
				if (!$_bl11->showPlusMinus) $_bl1i.=" ktvNoPM"; 
				} 
			else { 
				if ($_bl11->_bOs->_blr[0] === $_bl11) { $_bl1i="ktvTop"; } 
				if ($_bl11->_bOs->_blr[sizeof($_bl11->_bOs->_blr)-1] === $_bl11) { $_bl1i="ktvBot"; } 
				if ($_bl1i == "") { $_bl1i="ktvMid"; } 
				} 
			$_bO1i="[".str_replace(",","][",$this->selectedIds)."]"; 
			if (strpos($_bO1i,"[".$_bl11->id."]") !== FALSE) $_bl1i.=" ktvSelected"; 
			$_bl1e=_bl5("{class}",$_bl1i,$_bl1e); 
			$_bO1d=_bl5("{nodeid}",(($_bl11 === $this->_bOw) ? $this->id.".": "").$_bl11->id ,$_bO1d); 
			$_bO1d=_bl5("{class}",$_bO1h,$_bO1d); $_bO1d=_bl5("{nodecontent}",$_bl1e,$_bO1d); 
			$_bO1d=_bl5("{subnodes}",$_bO1e,$_bO1d); 
			return $_bO1d; 
			} 
		
		function registerscript() { 
			$_bO17="<script type='text/javascript'>if(typeof _libKTV=='undefined'){document.write(unescape(\"%3Cscript type='text/javascript' src='{src}'%3E %3C/script%3E\"));_libKTV=1;}</script>"; 
			$_bl18=_bl5("{src}",$this->_bl1j()."?".md5( __FILE__."js"),$_bO17); 
			return $_bl18; 
			} 
			
		function startupscript() { 
			$_bO17="var {id}; function {id}_init(){ {id} = new CoolTreeView(\"{id}\",{singleExpand},{selectEnable},{multipleSelectEnable},{DragAndDropEnable},{EditNodeEnable},'{keepState}',{keepStateHours},\"{cs}\");}"; 
			$_bO17.="if (typeof(CoolTreeView)=='function'){{id}_init();}"; 
			$_bO17.="else{if(typeof(__KTVInits)=='undefined'){__KTVInits=new Array();} __KTVInits.push({id}_init);{register_script}}";                                                                     
			$_bO1j="if(typeof(_libKTV)=='undefined'){var _head = document.getElementsByTagName('head')[0];var _script = document.createElement('script'); _script.type='text/javascript'; _script.src='{src}'; _head.appendChild(_script);_libKTV=1;}"; 
			$_bl1k=_bl5("{src}",$this->_bl1j()."?".md5( __FILE__."js"),$_bO1j); 
			$_bO1k="{'selectedIds':[{selectedIds}],'selectDisableIds':[{selectDisableIds}],'dragDisableIds':[{dragDisableIds}],'dropDisableIds':[{dropDisableIds}],'editDisableIds':[{editDisableIds}]}"; 
			$_bl13=_bl5("{id}",$this->id ,$_bO17); 
			$_bO1i=($this->selectedIds != "") ? "'"._bl5(",","','",$this->selectedIds)."'": ""; 
			$_bO19=_bl5("{selectedIds}",$_bO1i,$_bO1k); 
			$_bO1i=($this->selectDisableIds != "") ? "'"._bl5(",","','",$this->selectDisableIds)."'": ""; 
			$_bO19=_bl5("{selectDisableIds}",$_bO1i,$_bO19); 
			$_bO1i=($this->dragDisableIds != "") ? "'"._bl5(",","','",$this->dragDisableIds)."'": ""; 
			$_bO19=_bl5("{dragDisableIds}",$_bO1i,$_bO19); 
			$_bO1i=($this->dropDisableIds != "") ? "'"._bl5(",","','",$this->dropDisableIds)."'": ""; 
			$_bO19=_bl5("{dropDisableIds}",$_bO1i,$_bO19); 
			$_bO1i=($this->editDisableIds != "") ? "'"._bl5(",","','",$this->editDisableIds)."'": ""; 
			$_bO19=_bl5("{editDisableIds}",$_bO1i,$_bO19); 
			$_bl13=_bl5("{singleExpand}",($this->singleExpand) ? "1": "0",$_bl13); 
			$_bl13=_bl5("{selectEnable}",($this->selectEnable) ? "1": "0",$_bl13); 
			$_bl13=_bl5("{multipleSelectEnable}",($this->multipleSelectEnable) ? "1": "0",$_bl13); 
			$_bl13=_bl5("{DragAndDropEnable}",($this->DragAndDropEnable) ? "1": "0",$_bl13); 
			$_bl13=_bl5("{EditNodeEnable}",($this->EditNodeEnable) ? "1": "0",$_bl13); 
			$_bl13=_bl5("{keepState}",$this->keepState ,$_bl13); 
			$_bl13=_bl5("{keepStateHours}",$this->keepStateHours ,$_bl13); 
			$_bl13=_bl5("{cs}",$_bO19,$_bl13); $_bl13=_bl5("{register_script}",$_bl1k,$_bl13); 
			return $_bl13; 
			} 
			
		function _bl1j() { 
			if ($this->scriptFolder == "") { 
				$_bl6=_bl4(); 
				$_bl1l=substr(_bl5("\134","/",__FILE__),strlen($_bl6)); 
				return $_bl1l; 
				} 
			else { 
				$_bl1l=_bl5("\134","/",__FILE__); 
				$_bl1l=$this->scriptFolder.substr($_bl1l,strrpos($_bl1l,"/")); 
				return $_bl1l; 
				} 
			} 
		
		function _bO18() { 
			$_bO1l=$this->_bl1j(); 
			$_bl1m=_bl5(strrchr($_bO1l,"/"),"",$_bO1l)."/styles"; 
			return $_bl1m; 
			} 
		} 
	} ?>