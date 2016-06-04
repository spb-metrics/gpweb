var MooTools = {
	version: '1.11'
};
function $defined(obj) {
	return (obj != undefined)
};
function $type(obj) {
	if (!$defined(obj)) return false;
	if (obj.htmlElement) return 'element';
	var type = typeof obj;
	if (type == 'object' && obj.nodeName) {
		switch (obj.nodeType) {
		case 1:
			return 'element';
		case 3:
			return (/\S/).test(obj.nodeValue) ? 'textnode': 'whitespace'
		}
	}
	if (type == 'object' || type == 'function') {
		switch (obj.constructor) {
		case Array:
			return 'array';
		case RegExp:
			return 'regexp';
		case Class:
			return 'class'
		}
		if (typeof obj.length == 'number') {
			if (obj.item) return 'collection';
			if (obj.callee) return 'arguments'
		}
	}
	return type
};
function $merge() {
	var mix = {};
	for (var i = 0; i < arguments.length; i++) {
		for (var property in arguments[i]) {
			var ap = arguments[i][property];
			var mp = mix[property];
			if (mp && $type(ap) == 'object' && $type(mp) == 'object') mix[property] = $merge(mp, ap);
			else mix[property] = ap
		}
	}
	return mix
};
var $extend = function () {
	var args = arguments;
	if (!args[1]) args = [this, args[0]];
	for (var property in args[1]) args[0][property] = args[1][property];
	return args[0]
};
var $native = function () {
	for (var i = 0, l = arguments.length; i < l; i++) {
		arguments[i].extend = function (props) {
			for (var prop in props) {
				if (!this.prototype[prop]) this.prototype[prop] = props[prop];
				if (!this[prop]) this[prop] = $native.generic(prop)
			}
		}
	}
};
$native.generic = function (prop) {
	return function (bind) {
		return this.prototype[prop].apply(bind, Array.prototype.slice.call(arguments, 1))
	}
};
$native(Function, Array, String, Number);
function $chk(obj) {
	return !! (obj || obj === 0)
};
function $pick(obj, picked) {
	return $defined(obj) ? obj: picked
};
function $random(min, max) {
	return Math.floor(Math.random() * (max - min + 1) + min)
};
function $time() {
	return new Date().getTime()
};
function $clear(timer) {
	clearTimeout(timer);
	clearInterval(timer);
	return null
};
var Abstract = function (obj) {
	obj = obj || {};
	obj.extend = $extend;
	return obj
};
var Window = new Abstract(window);
var Document = new Abstract(document);
document.head = document.getElementsByTagName('head')[0];
window.xpath = !!(document.evaluate);
if (window.ActiveXObject) window.ie = window[window.XMLHttpRequest ? 'ie7': 'ie6'] = true;
else if (document.childNodes && !document.all && !navigator.taintEnabled) window.webkit = window[window.xpath ? 'webkit420': 'webkit419'] = true;
else if (document.getBoxObjectFor != null) window.gecko = true;
window.khtml = window.webkit;
Object.extend = $extend;
if (typeof HTMLElement == 'undefined') {
	var HTMLElement = function () {};
	if (window.webkit) document.createElement("iframe");
	HTMLElement.prototype = (window.webkit) ? window["[[DOMElement.prototype]]"] : {}
}
HTMLElement.prototype.htmlElement = function () {};
if (window.ie6) try {
	document.execCommand("BackgroundImageCache", false, true)
} catch(e) {};
var Class = function (properties) {
	var klass = function () {
		return (arguments[0] !== null && this.initialize && $type(this.initialize) == 'function') ? this.initialize.apply(this, arguments) : this
	};
	$extend(klass, this);
	klass.prototype = properties;
	klass.constructor = Class;
	return klass
};
Class.empty = function () {};
Class.prototype = {
	extend: function (properties) {
		var proto = new this(null);
		for (var property in properties) {
			var pp = proto[property];
			proto[property] = Class.Merge(pp, properties[property])
		}
		return new Class(proto)
	},
	implement: function () {
		for (var i = 0, l = arguments.length; i < l; i++) $extend(this.prototype, arguments[i])
	}
};
Class.Merge = function (previous, current) {
	if (previous && previous != current) {
		var type = $type(current);
		if (type != $type(previous)) return current;
		switch (type) {
		case 'function':
			var merged = function () {
				this.parent = arguments.callee.parent;
				return current.apply(this, arguments)
			};
			merged.parent = previous;
			return merged;
		case 'object':
			return $merge(previous, current)
		}
	}
	return current
};
var Chain = new Class({
	chain: function (fn) {
		this.chains = this.chains || [];
		this.chains.push(fn);
		return this
	},
	callChain: function () {
		if (this.chains && this.chains.length) this.chains.shift().delay(10, this)
	},
	clearChain: function () {
		this.chains = []
	}
});
var Eventos = new Class({
	addEvent: function (type, fn) {
		if (fn != Class.empty) {
			this.$eventos = this.$eventos || {};
			this.$eventos[type] = this.$eventos[type] || [];
			this.$eventos[type].include(fn)
		}
		return this
	},
	fireEvent: function (type, args, delay) {
		if (this.$eventos && this.$eventos[type]) {
			this.$eventos[type].each(function (fn) {
				fn.create({
					'bind': this,
					'delay': delay,
					'arguments': args
				})()
			},
			this)
		}
		return this
	},
	removeEvento: function (type, fn) {
		if (this.$eventos && this.$eventos[type]) this.$eventos[type].remove(fn);
		return this
	}
});
var Options = new Class({
	setOptions: function () {
		this.options = $merge.apply(null, [this.options].extend(arguments));
		if (this.addEvent) {
			for (var option in this.options) {
				if ($type(this.options[option] == 'function') && (/^on[A-Z]/).test(option)) this.addEvent(option, this.options[option])
			}
		}
		return this
	}
});
Array.extend({
	forEach: function (fn, bind) {
		for (var i = 0, j = this.length; i < j; i++) fn.call(bind, this[i], i, this)
	},
	filter: function (fn, bind) {
		var results = [];
		for (var i = 0, j = this.length; i < j; i++) {
			if (fn.call(bind, this[i], i, this)) results.push(this[i])
		}
		return results
	},
	map: function (fn, bind) {
		var results = [];
		for (var i = 0, j = this.length; i < j; i++) results[i] = fn.call(bind, this[i], i, this);
		return results
	},
	every: function (fn, bind) {
		for (var i = 0, j = this.length; i < j; i++) {
			if (!fn.call(bind, this[i], i, this)) return false
		}
		return true
	},
	some: function (fn, bind) {
		for (var i = 0, j = this.length; i < j; i++) {
			if (fn.call(bind, this[i], i, this)) return true
		}
		return false
	},
	indexOf: function (item, from) {
		var len = this.length;
		for (var i = (from < 0) ? Math.max(0, len + from) : from || 0; i < len; i++) {
			if (this[i] === item) return i
		}
		return - 1
	},
	copy: function (start, length) {
		start = start || 0;
		if (start < 0) start = this.length + start;
		length = length || (this.length - start);
		var newArray = [];
		for (var i = 0; i < length; i++) newArray[i] = this[start++];
		return newArray
	},
	remove: function (item) {
		var i = 0;
		var len = this.length;
		while (i < len) {
			if (this[i] === item) {
				this.splice(i, 1);
				len--
			} else {
				i++
			}
		}
		return this
	},
	contains: function (item, from) {
		return this.indexOf(item, from) != -1
	},
	associate: function (keys) {
		var obj = {},
		length = Math.min(this.length, keys.length);
		for (var i = 0; i < length; i++) obj[keys[i]] = this[i];
		return obj
	},
	extend: function (array) {
		for (var i = 0, j = array.length; i < j; i++) this.push(array[i]);
		return this
	},
	merge: function (array) {
		for (var i = 0, l = array.length; i < l; i++) this.include(array[i]);
		return this
	},
	include: function (item) {
		if (!this.contains(item)) this.push(item);
		return this
	},
	getRandom: function () {
		return this[$random(0, this.length - 1)] || null
	},
	getLast: function () {
		return this[this.length - 1] || null
	}
});
Array.prototype.each = Array.prototype.forEach;
Array.each = Array.forEach;
function $A(array) {
	return Array.copy(array)
};
function $each(iterable, fn, bind) {
	if (iterable && typeof iterable.length == 'number' && $type(iterable) != 'object') {
		Array.forEach(iterable, fn, bind)
	} else {
		for (var name in iterable) fn.call(bind || iterable, iterable[name], name)
	}
};
Array.prototype.test = Array.prototype.contains;
String.extend({
	test: function (regex, params) {
		return (($type(regex) == 'string') ? new RegExp(regex, params) : regex).test(this)
	},
	toInt: function () {
		return parseInt(this, 10)
	},
	toFloat: function () {
		return parseFloat(this)
	},
	camelCase: function () {
		return this.replace(/-\D/g, function (match) {
			return match.charAt(1).toUpperCase()
		})
	},
	hyphenate: function () {
		return this.replace(/\w[A-Z]/g, function (match) {
			return (match.charAt(0) + '-' + match.charAt(1).toLowerCase())
		})
	},
	capitalize: function () {
		return this.replace(/\b[a-z]/g, function (match) {
			return match.toUpperCase()
		})
	},
	trim: function () {
		return this.replace(/^\s+|\s+$/g, '')
	},
	clean: function () {
		return this.replace(/\s{2,}/g, ' ').trim()
	},
	rgbToHex: function (array) {
		var rgb = this.match(/\d{1,3}/g);
		return (rgb) ? rgb.rgbToHex(array) : false
	},
	hexToRgb: function (array) {
		var hex = this.match(/^#?(\w{1,2})(\w{1,2})(\w{1,2})$/);
		return (hex) ? hex.slice(1).hexToRgb(array) : false
	},
	contains: function (string, s) {
		return (s) ? (s + this + s).indexOf(s + string + s) > -1 : this.indexOf(string) > -1
	},
	escapeRegExp: function () {
		return this.replace(/([.*+?^${}()|[\]\/\\])/g, '\\$1')
	}
});
Array.extend({
	rgbToHex: function (array) {
		if (this.length < 3) return false;
		if (this.length == 4 && this[3] == 0 && !array) return 'transparent';
		var hex = [];
		for (var i = 0; i < 3; i++) {
			var bit = (this[i] - 0).toString(16);
			hex.push((bit.length == 1) ? '0' + bit: bit)
		}
		return array ? hex: '#' + hex.join('')
	},
	hexToRgb: function (array) {
		if (this.length != 3) return false;
		var rgb = [];
		for (var i = 0; i < 3; i++) {
			rgb.push(parseInt((this[i].length == 1) ? this[i] + this[i] : this[i], 16))
		}
		return array ? rgb: 'rgb(' + rgb.join(',') + ')'
	}
});
Function.extend({
	create: function (options) {
		var fn = this;
		options = $merge({
			'bind': fn,
			'evento': false,
			'arguments': null,
			'delay': false,
			'periodical': false,
			'attempt': false
		},
		options);
		if ($chk(options.arguments) && $type(options.arguments) != 'array') options.arguments = [options.arguments];
		return function (evento) {
			var args;
			if (options.evento) {
				evento = evento || window.evento;
				args = [(options.evento === true) ? evento: new options.evento(evento)];
				if (options.arguments) args.extend(options.arguments)
			} else args = options.arguments || arguments;
			var returns = function () {
				return fn.apply($pick(options.bind, fn), args)
			};
			if (options.delay) return setTimeout(returns, options.delay);
			if (options.periodical) return setInterval(returns, options.periodical);
			if (options.attempt) try {
				return returns()
			} catch(err) {
				return false
			};
			return returns()
		}
	},
	pass: function (args, bind) {
		return this.create({
			'arguments': args,
			'bind': bind
		})
	},
	attempt: function (args, bind) {
		return this.create({
			'arguments': args,
			'bind': bind,
			'attempt': true
		})()
	},
	bind: function (bind, args) {
		return this.create({
			'bind': bind,
			'arguments': args
		})
	},
	bindAsEventoListener: function (bind, args) {
		return this.create({
			'bind': bind,
			'evento': true,
			'arguments': args
		})
	},
	delay: function (delay, bind, args) {
		return this.create({
			'delay': delay,
			'bind': bind,
			'arguments': args
		})()
	},
	periodical: function (interval, bind, args) {
		return this.create({
			'periodical': interval,
			'bind': bind,
			'arguments': args
		})()
	}
});
Number.extend({
	toInt: function () {
		return parseInt(this)
	},
	toFloat: function () {
		return parseFloat(this)
	},
	limit: function (min, max) {
		return Math.min(max, Math.max(min, this))
	},
	round: function (precision) {
		precision = Math.pow(10, precision || 0);
		return Math.round(this * precision) / precision
	},
	times: function (fn) {
		for (var i = 0; i < this; i++) fn(i)
	}
});
var Element = new Class({
	initialize: function (el, props) {
		if ($type(el) == 'string') {
			if (window.ie && props && (props.name || props.type)) {
				var name = (props.name) ? ' name="' + props.name + '"': '';
				var type = (props.type) ? ' type="' + props.type + '"': '';
				delete props.name;
				delete props.type;
				el = '<' + el + name + type + '>'
			}
			el = document.createElement(el)
		}
		el = $(el);
		return (!props || !el) ? el: el.set(props)
	}
});
var Elements = new Class({
	initialize: function (elements) {
		return (elements) ? $extend(elements, this) : this
	}
});
Elements.extend = function (props) {
	for (var prop in props) {
		this.prototype[prop] = props[prop];
		this[prop] = $native.generic(prop)
	}
};
function $(el) {
	if (!el) return null;
	if (el.htmlElement) return Garbage.collect(el);
	if ([window, document].contains(el)) return el;
	var type = $type(el);
	if (type == 'string') {
		el = document.getElementById(el);
		type = (el) ? 'element': false
	}
	if (type != 'element') return null;
	if (el.htmlElement) return Garbage.collect(el);
	if (['object', 'embed'].contains(el.tagName.toLowerCase())) return el;
	$extend(el, Element.prototype);
	el.htmlElement = function () {};
	return Garbage.collect(el)
};
document.getElementsBySelector = document.getElementsByTagName;
function $$() {
	var elements = [];
	for (var i = 0, j = arguments.length; i < j; i++) {
		var selecionar = arguments[i];
		switch ($type(selecionar)) {
		case 'element':
			elements.push(selecionar);
		case 'boolean':
			break;
		case false:
			break;
		case 'string':
			selecionar = document.getElementsBySelector(selecionar, true);
		default:
			elements.extend(selecionar)
		}
	}
	return $$.unique(elements)
};
$$.unique = function (array) {
	var elements = [];
	for (var i = 0, l = array.length; i < l; i++) {
		if (array[i].$included) continue;
		var element = $(array[i]);
		if (element && !element.$included) {
			element.$included = true;
			elements.push(element)
		}
	}
	for (var n = 0, d = elements.length; n < d; n++) elements[n].$included = null;
	return new Elements(elements)
};
Elements.Multi = function (property) {
	return function () {
		var args = arguments;
		var items = [];
		var elements = true;
		for (var i = 0, j = this.length, returns; i < j; i++) {
			returns = this[i][property].apply(this[i], args);
			if ($type(returns) != 'element') elements = false;
			items.push(returns)
		};
		return (elements) ? $$.unique(items) : items
	}
};
Element.extend = function (properties) {
	for (var property in properties) {
		HTMLElement.prototype[property] = properties[property];
		Element.prototype[property] = properties[property];
		Element[property] = $native.generic(property);
		var elementsProperty = (Array.prototype[property]) ? property + 'Elements': property;
		Elements.prototype[elementsProperty] = Elements.Multi(property)
	}
};
Element.extend({
	set: function (props) {
		for (var prop in props) {
			var val = props[prop];
			switch (prop) {
			case 'styles':
				this.setStyles(val);
				break;
			case 'eventos':
				if (this.addEvents) this.addEvents(val);
				break;
			case 'properties':
				this.setProperties(val);
				break;
			default:
				this.setProperty(prop, val)
			}
		}
		return this
	},
	inject: function (el, where) {
		el = $(el);
		switch (where) {
		case 'before':
			el.parentNode.insertBefore(this, el);
			break;
		case 'after':
			var next = el.getNext();
			if (!next) el.parentNode.appendChild(this);
			else el.parentNode.insertBefore(this, next);
			break;
		case 'top':
			var first = el.firstChild;
			if (first) {
				el.insertBefore(this, first);
				break
			}
		default:
			el.appendChild(this)
		}
		return this
	},
	injectBefore: function (el) {
		return this.inject(el, 'before')
	},
	injectAfter: function (el) {
		return this.inject(el, 'after')
	},
	injectInside: function (el) {
		return this.inject(el, 'bottom')
	},
	injectTop: function (el) {
		return this.inject(el, 'top')
	},
	adopt: function () {
		var elements = [];
		$each(arguments, function (argument) {
			elements = elements.concat(argument)
		});
		$$(elements).inject(this);
		return this
	},
	remove: function () {
		return this.parentNode.removeChild(this)
	},
	clone: function (contents) {
		var el = $(this.cloneNode(contents !== false));
		if (!el.$eventos) return el;
		el.$eventos = {};
		for (var type in this.$eventos) el.$eventos[type] = {
			'keys': $A(this.$eventos[type].keys),
			'values': $A(this.$eventos[type].values)
		};
		return el.removeEvents()
	},
	replaceWith: function (el) {
		el = $(el);
		this.parentNode.replaceChild(el, this);
		return el
	},
	appendText: function (text) {
		this.appendChild(document.createTextNode(text));
		return this
	},
	hasClass: function (className) {
		return this.className.contains(className, ' ')
	},
	addClass: function (className) {
		if (!this.hasClass(className)) this.className = (this.className + ' ' + className).clean();
		return this
	},
	removeClass: function (className) {
		this.className = this.className.replace(new RegExp('(^|\\s)' + className + '(?:\\s|$)'), '$1').clean();
		return this
	},
	toggleClass: function (className) {
		return this.hasClass(className) ? this.removeClass(className) : this.addClass(className)
	},
	setStyle: function (property, value) {
		switch (property) {
		case 'opacity':
			return this.setOpacity(parseFloat(value));
		case 'float':
			property = (window.ie) ? 'styleFloat': 'cssFloat'
		}
		property = property.camelCase();
		switch ($type(value)) {
		case 'number':
			if (! ['zIndex', 'zoom'].contains(property)) value += 'px';
			break;
		case 'array':
			value = 'rgb(' + value.join(',') + ')'
		}
		this.style[property] = value;
		return this
	},
	setStyles: function (source) {
		switch ($type(source)) {
		case 'object':
			Element.setMany(this, 'setStyle', source);
			break;
		case 'string':
			this.style.cssText = source
		}
		return this
	},
	setOpacity: function (opacity) {
		if (opacity == 0) {
			if (this.style.visibility != "hidden") this.style.visibility = "hidden"
		} else {
			if (this.style.visibility != "visible") this.style.visibility = "visible"
		}
		if (!this.currentStyle || !this.currentStyle.hasLayout) this.style.zoom = 1;
		if (window.ie) this.style.filter = (opacity == 1) ? '': "alpha(opacity=" + opacity * 100 + ")";
		this.style.opacity = this.$tmp.opacity = opacity;
		return this
	},
	getEstilo: function (property) {
		property = property.camelCase();
		var result = this.style[property];
		if (!$chk(result)) {
			if (property == 'opacity') return this.$tmp.opacity;
			result = [];
			for (var style in Element.Styles) {
				if (property == style) {
					Element.Styles[style].each(function (s) {
						var style = this.getEstilo(s);
						result.push(parseInt(style) ? style: '0px')
					},
					this);
					if (property == 'border') {
						var every = result.every(function (bit) {
							return (bit == result[0])
						});
						return (every) ? result[0] : false
					}
					return result.join(' ')
				}
			}
			if (property.contains('border')) {
				if (Element.Styles.border.contains(property)) {
					return ['Width', 'Style', 'Color'].map(function (p) {
						return this.getEstilo(property + p)
					},
					this).join(' ')
				} else if (Element.borderShort.contains(property)) {
					return ['Top', 'Right', 'Bottom', 'Left'].map(function (p) {
						return this.getEstilo('border' + p + property.replace('border', ''))
					},
					this).join(' ')
				}
			}
			if (document.defaultView) result = document.defaultView.getComputedStyle(this, null).getPropertyValue(property.hyphenate());
			else if (this.currentStyle) result = this.currentStyle[property]
		}
		if (window.ie) result = Element.fixStyle(property, result, this);
		if (result && property.test(/color/i) && result.contains('rgb')) {
			return result.split('rgb').splice(1, 4).map(function (color) {
				return color.rgbToHex()
			}).join(' ')
		}
		return result
	},
	getEstilos: function () {
		return Element.getMany(this, 'getEstilo', arguments)
	},
	walk: function (brother, start) {
		brother += 'Sibling';
		var el = (start) ? this[start] : this[brother];
		while (el && $type(el) != 'element') el = el[brother];
		return $(el)
	},
	getPrevious: function () {
		return this.walk('previous')
	},
	getNext: function () {
		return this.walk('next')
	},
	getFirst: function () {
		return this.walk('next', 'firstChild')
	},
	getLast: function () {
		return this.walk('previous', 'lastChild')
	},
	getParent: function () {
		return $(this.parentNode)
	},
	getChildren: function () {
		return $$(this.childNodes)
	},
	hasChild: function (el) {
		return !! $A(this.getElementsByTagName('*')).contains(el)
	},
	getProperty: function (property) {
		var index = Element.Properties[property];
		if (index) return this[index];
		var flag = Element.PropertiesIFlag[property] || 0;
		if (!window.ie || flag) return this.getAttribute(property, flag);
		var node = this.attributes[property];
		return (node) ? node.nodeValue: null
	},
	removeProperty: function (property) {
		var index = Element.Properties[property];
		if (index) this[index] = '';
		else this.removeAttribute(property);
		return this
	},
	getProperties: function () {
		return Element.getMany(this, 'getProperty', arguments)
	},
	setProperty: function (property, value) {
		var index = Element.Properties[property];
		if (index) this[index] = value;
		else this.setAttribute(property, value);
		return this
	},
	setProperties: function (source) {
		return Element.setMany(this, 'setProperty', source)
	},
	setHTML: function () {
		this.innerHTML = $A(arguments).join('');
		return this
	},
	setText: function (text) {
		var tag = this.getTag();
		if (['style', 'script'].contains(tag)) {
			if (window.ie) {
				if (tag == 'style') this.styleSheet.cssText = text;
				else if (tag == 'script') this.setProperty('text', text);
				return this
			} else {
				this.removeChild(this.firstChild);
				return this.appendText(text)
			}
		}
		this[$defined(this.innerText) ? 'innerText': 'textContent'] = text;
		return this
	},
	getText: function () {
		var tag = this.getTag();
		if (['style', 'script'].contains(tag)) {
			if (window.ie) {
				if (tag == 'style') return this.styleSheet.cssText;
				else if (tag == 'script') return this.getProperty('text')
			} else {
				return this.innerHTML
			}
		}
		return ($pick(this.innerText, this.textContent))
	},
	getTag: function () {
		return this.tagName.toLowerCase()
	},
	empty: function () {
		Garbage.trash(this.getElementsByTagName('*'));
		return this.setHTML('')
	}
});
Element.fixStyle = function (property, result, element) {
	if ($chk(parseInt(result))) return result;
	if (['height', 'width'].contains(property)) {
		var values = (property == 'width') ? ['left', 'right'] : ['top', 'bottom'];
		var size = 0;
		values.each(function (value) {
			size += element.getEstilo('border-' + value + '-width').toInt() + element.getEstilo('padding-' + value).toInt()
		});
		return element['offset' + property.capitalize()] - size + 'px'
	} else if (property.test(/border(.+)Width|margin|padding/)) {
		return '0px'
	}
	return result
};
Element.Styles = {
	'border': [],
	'padding': [],
	'margin': []
};
['Top', 'Right', 'Bottom', 'Left'].each(function (direction) {
	for (var style in Element.Styles) Element.Styles[style].push(style + direction)
});
Element.borderShort = ['borderWidth', 'borderStyle', 'borderColor'];
Element.getMany = function (el, method, keys) {
	var result = {};
	$each(keys, function (key) {
		result[key] = el[method](key)
	});
	return result
};
Element.setMany = function (el, method, pairs) {
	for (var key in pairs) el[method](key, pairs[key]);
	return el
};
Element.Properties = new Abstract({
	'class': 'className',
	'for': 'htmlFor',
	'colspan': 'colSpan',
	'rowspan': 'rowSpan',
	'accesskey': 'accessKey',
	'tabindex': 'tabIndex',
	'maxlength': 'maxLength',
	'readonly': 'readOnly',
	'frameborder': 'frameBorder',
	'value': 'value',
	'disabled': 'disabled',
	'checked': 'checked',
	'multiple': 'multiple',
	'selected': 'selected'
});
Element.PropertiesIFlag = {
	'href': 2,
	'src': 2
};
Element.Methods = {
	Listeners: {
		addListener: function (type, fn) {
			if (this.addEventListener) this.addEventListener(type, fn, false);
			else this.attachEvent('on' + type, fn);
			return this
		},
		removeListener: function (type, fn) {
			if (this.removeEventListener) this.removeEventListener(type, fn, false);
			else this.detachEvent('on' + type, fn);
			return this
		}
	}
};
window.extend(Element.Methods.Listeners);
document.extend(Element.Methods.Listeners);
Element.extend(Element.Methods.Listeners);
var Garbage = {
	elements: [],
	collect: function (el) {
		if (!el.$tmp) {
			Garbage.elements.push(el);
			el.$tmp = {
				'opacity': 1
			}
		}
		return el
	},
	trash: function (elements) {
		for (var i = 0, j = elements.length, el; i < j; i++) {
			if (! (el = elements[i]) || !el.$tmp) continue;
			if (el.$eventos) el.fireEvent('trash').removeEvents();
			for (var p in el.$tmp) el.$tmp[p] = null;
			for (var d in Element.prototype) el[d] = null;
			Garbage.elements[Garbage.elements.indexOf(el)] = null;
			el.htmlElement = el.$tmp = el = null
		}
		Garbage.elements.remove(null)
	},
	empty: function () {
		Garbage.collect(window);
		Garbage.collect(document);
		Garbage.trash(Garbage.elements)
	}
};
window.addListener('beforeunload', function () {
	window.addListener('unload', Garbage.empty);
	if (window.ie) window.addListener('unload', CollectGarbage)
});
var Evento = new Class({
	initialize: function (evento) {
		if (evento && evento.$extended) return evento;
		this.$extended = true;
		evento = evento || window.evento;
		this.evento = evento;
		this.type = evento.type;
		this.target = evento.target || evento.srcElement;
		if (this.target.nodeType == 3) this.target = this.target.parentNode;
		this.shift = evento.shiftKey;
		this.control = evento.ctrlKey;
		this.alt = evento.altKey;
		this.meta = evento.metaKey;
		if (['DOMMouseScroll', 'mousewheel'].contains(this.type)) {
			this.wheel = (evento.wheelDelta) ? evento.wheelDelta / 120 : -(evento.detail || 0) / 3
		} else if (this.type.contains('key')) {
			this.code = evento.which || evento.keyCode;
			for (var name in Evento.keys) {
				if (Evento.keys[name] == this.code) {
					this.key = name;
					break
				}
			}
			if (this.type == 'keydown') {
				var fKey = this.code - 111;
				if (fKey > 0 && fKey < 13) this.key = 'f' + fKey
			}
			this.key = this.key || String.fromCharCode(this.code).toLowerCase()
		} else if (this.type.test(/(click|mouse|menu)/)) {
			if (this.ie) {
				this.page = {
					'x': evento.pageX || evento.clientX + document.body.scrollLeft,
					'y': evento.pageY || evento.clientY + document.body.scrollTop
				}
			} else {
				this.page = {
					'x': evento.pageX || evento.clientX + document.documentElement.scrollLeft,
					'y': evento.pageY || evento.clientY + document.documentElement.scrollTop
				}
			}
			this.client = {
				'x': evento.pageX ? evento.pageX - window.pageXOffset: evento.clientX,
				'y': evento.pageY ? evento.pageY - window.pageYOffset: evento.clientY
			};
			this.rightClick = (evento.which == 3) || (evento.button == 2);
			switch (this.type) {
			case 'mouseover':
				this.relatedTarget = evento.relatedTarget || evento.fromElement;
				break;
			case 'mouseout':
				this.relatedTarget = evento.relatedTarget || evento.toElement
			}
			this.fixRelatedTarget()
		}
		return this
	},
	stop: function () {
		return this.stopPropagation().preventDefault()
	},
	stopPropagation: function () {
		if (this.evento.stopPropagation) this.evento.stopPropagation();
		else this.evento.cancelBubble = true;
		return this
	},
	preventDefault: function () {
		if (this.evento.preventDefault) this.evento.preventDefault();
		else this.evento.returnValue = false;
		return this
	}
});
Evento.fix = {
	relatedTarget: function () {
		if (this.relatedTarget && this.relatedTarget.nodeType == 3) this.relatedTarget = this.relatedTarget.parentNode
	},
	relatedTargetGecko: function () {
		try {
			Evento.fix.relatedTarget.call(this)
		} catch(e) {
			this.relatedTarget = this.target
		}
	}
};
Evento.prototype.fixRelatedTarget = (window.gecko) ? Evento.fix.relatedTargetGecko: Evento.fix.relatedTarget;
Evento.keys = new Abstract({
	'enter': 13,
	'up': 38,
	'down': 40,
	'left': 37,
	'right': 39,
	'esc': 27,
	'space': 32,
	'backspace': 8,
	'tab': 9,
	'delete': 46
});
Element.Methods.Eventos = {
	addEvent: function (type, fn) {
		this.$eventos = this.$eventos || {};
		this.$eventos[type] = this.$eventos[type] || {
			'keys': [],
			'values': []
		};
		if (this.$eventos[type].keys.contains(fn)) return this;
		this.$eventos[type].keys.push(fn);
		var realType = type;
		var custom = Element.Eventos[type];
		if (custom) {
			if (custom.add) custom.add.call(this, fn);
			if (custom.map) fn = custom.map;
			if (custom.type) realType = custom.type
		}
		if (!this.addEventListener) fn = fn.create({
			'bind': this,
			'evento': true
		});
		this.$eventos[type].values.push(fn);
		return (Element.NativeEvents.contains(realType)) ? this.addListener(realType, fn) : this
	},
	removeEvento: function (type, fn) {
		if (!this.$eventos || !this.$eventos[type]) return this;
		var pos = this.$eventos[type].keys.indexOf(fn);
		if (pos == -1) return this;
		var key = this.$eventos[type].keys.splice(pos, 1)[0];
		var value = this.$eventos[type].values.splice(pos, 1)[0];
		var custom = Element.Eventos[type];
		if (custom) {
			if (custom.remove) custom.remove.call(this, fn);
			if (custom.type) type = custom.type
		}
		return (Element.NativeEvents.contains(type)) ? this.removeListener(type, value) : this
	},
	addEvents: function (source) {
		return Element.setMany(this, 'addEvent', source)
	},
	removeEvents: function (type) {
		if (!this.$eventos) return this;
		if (!type) {
			for (var evType in this.$eventos) this.removeEvents(evType);
			this.$eventos = null
		} else if (this.$eventos[type]) {
			this.$eventos[type].keys.each(function (fn) {
				this.removeEvento(type, fn)
			},
			this);
			this.$eventos[type] = null
		}
		return this
	},
	fireEvent: function (type, args, delay) {
		if (this.$eventos && this.$eventos[type]) {
			this.$eventos[type].keys.each(function (fn) {
				fn.create({
					'bind': this,
					'delay': delay,
					'arguments': args
				})()
			},
			this)
		}
		return this
	},
	cloneEvents: function (from, type) {
		if (!from.$eventos) return this;
		if (!type) {
			for (var evType in from.$eventos) this.cloneEvents(from, evType)
		} else if (from.$eventos[type]) {
			from.$eventos[type].keys.each(function (fn) {
				this.addEvent(type, fn)
			},
			this)
		}
		return this
	}
};
window.extend(Element.Methods.Eventos);
document.extend(Element.Methods.Eventos);
Element.extend(Element.Methods.Eventos);
Element.Eventos = new Abstract({
	'mouseenter': {
		type: 'mouseover',
		map: function (evento) {
			evento = new Evento(evento);
			if (evento.relatedTarget != this && !this.hasChild(evento.relatedTarget)) this.fireEvent('mouseenter', evento)
		}
	},
	'mouseleave': {
		type: 'mouseout',
		map: function (evento) {
			evento = new Evento(evento);
			if (evento.relatedTarget != this && !this.hasChild(evento.relatedTarget)) this.fireEvent('mouseleave', evento)
		}
	},
	'mousewheel': {
		type: (window.gecko) ? 'DOMMouseScroll': 'mousewheel'
	}
});
Element.NativeEvents = ['click', 'dblclick', 'mouseup', 'mousedown', 'mousewheel', 'DOMMouseScroll', 'mouseover', 'mouseout', 'mousemove', 'keydown', 'keypress', 'keyup', 'load', 'unload', 'beforeunload', 'resize', 'move', 'focus', 'blur', 'change', 'submit', 'reset', 'select', 'error', 'abort', 'contextmenu', 'scroll'];
Function.extend({
	bindWithEvento: function (bind, args) {
		return this.create({
			'bind': bind,
			'arguments': args,
			'evento': Evento
		})
	}
});
Elements.extend({
	filterByTag: function (tag) {
		return new Elements(this.filter(function (el) {
			return (Element.getTag(el) == tag)
		}))
	},
	filterByClass: function (className, nocash) {
		var elements = this.filter(function (el) {
			return (el.className && el.className.contains(className, ' '))
		});
		return (nocash) ? elements: new Elements(elements)
	},
	filterById: function (id, nocash) {
		var elements = this.filter(function (el) {
			return (el.id == id)
		});
		return (nocash) ? elements: new Elements(elements)
	},
	filterByAttribute: function (name, operator, value, nocash) {
		var elements = this.filter(function (el) {
			var current = Element.getProperty(el, name);
			if (!current) return false;
			if (!operator) return true;
			switch (operator) {
			case '=':
				return (current == value);
			case '*=':
				return (current.contains(value));
			case '^=':
				return (current.substr(0, value.length) == value);
			case '$=':
				return (current.substr(current.length - value.length) == value);
			case '!=':
				return (current != value);
			case '~=':
				return current.contains(value, ' ')
			}
			return false
		});
		return (nocash) ? elements: new Elements(elements)
	}
});
function $E(selecionar, filter) {
	return ($(filter) || document).getElement(selecionar)
};
function $ES(selecionar, filter) {
	return ($(filter) || document).getElementsBySelector(selecionar)
};
$$.shared = {
	'regexp': /^(\w*|\*)(?:#([\w-]+)|\.([\w-]+))?(?:\[(\w+)(?:([!*^$]?=)["']?([^"'\]]*)["']?)?])?$/,
	'xpath': {
		getParam: function (items, context, param, i) {
			var temp = [context.namespaceURI ? 'xhtml:': '', param[1]];
			if (param[2]) temp.push('[@id="', param[2], '"]');
			if (param[3]) temp.push('[contains(concat(" ", @class, " "), " ', param[3], ' ")]');
			if (param[4]) {
				if (param[5] && param[6]) {
					switch (param[5]) {
					case '*=':
						temp.push('[contains(@', param[4], ', "', param[6], '")]');
						break;
					case '^=':
						temp.push('[starts-with(@', param[4], ', "', param[6], '")]');
						break;
					case '$=':
						temp.push('[substring(@', param[4], ', string-length(@', param[4], ') - ', param[6].length, ' + 1) = "', param[6], '"]');
						break;
					case '=':
						temp.push('[@', param[4], '="', param[6], '"]');
						break;
					case '!=':
						temp.push('[@', param[4], '!="', param[6], '"]')
					}
				} else {
					temp.push('[@', param[4], ']')
				}
			}
			items.push(temp.join(''));
			return items
		},
		getItems: function (items, context, nocash) {
			var elements = [];
			var xpath = document.evaluate('.//' + items.join('//'), context, $$.shared.resolver, XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE, null);
			for (var i = 0, j = xpath.snapshotLength; i < j; i++) elements.push(xpath.snapshotItem(i));
			return (nocash) ? elements: new Elements(elements.map($))
		}
	},
	'normal': {
		getParam: function (items, context, param, i) {
			if (i == 0) {
				if (param[2]) {
					var el = context.getElementById(param[2]);
					if (!el || ((param[1] != '*') && (Element.getTag(el) != param[1]))) return false;
					items = [el]
				} else {
					items = $A(context.getElementsByTagName(param[1]))
				}
			} else {
				items = $$.shared.getElementsByTagName(items, param[1]);
				if (param[2]) items = Elements.filterById(items, param[2], true)
			}
			if (param[3]) items = Elements.filterByClass(items, param[3], true);
			if (param[4]) items = Elements.filterByAttribute(items, param[4], param[5], param[6], true);
			return items
		},
		getItems: function (items, context, nocash) {
			return (nocash) ? items: $$.unique(items)
		}
	},
	resolver: function (prefix) {
		return (prefix == 'xhtml') ? 'http://www.w3.org/1999/xhtml': false
	},
	getElementsByTagName: function (context, tagName) {
		var found = [];
		for (var i = 0, j = context.length; i < j; i++) found.extend(context[i].getElementsByTagName(tagName));
		return found
	}
};
$$.shared.method = (window.xpath) ? 'xpath': 'normal';
Element.Methods.Dom = {
	getElements: function (selecionar, nocash) {
		var items = [];
		selecionar = selecionar.trim().split(' ');
		for (var i = 0, j = selecionar.length; i < j; i++) {
			var sel = selecionar[i];
			var param = sel.match($$.shared.regexp);
			if (!param) break;
			param[1] = param[1] || '*';
			var temp = $$.shared[$$.shared.method].getParam(items, this, param, i);
			if (!temp) break;
			items = temp
		}
		return $$.shared[$$.shared.method].getItems(items, this, nocash)
	},
	getElement: function (selecionar) {
		return $(this.getElements(selecionar, true)[0] || false)
	},
	getElementsBySelector: function (selecionar, nocash) {
		var elements = [];
		selecionar = selecionar.split(',');
		for (var i = 0, j = selecionar.length; i < j; i++) elements = elements.concat(this.getElements(selecionar[i], true));
		return (nocash) ? elements: $$.unique(elements)
	}
};
Element.extend({
	getElementById: function (id) {
		var el = document.getElementById(id);
		if (!el) return false;
		for (var parent = el.parentNode; parent != this; parent = parent.parentNode) {
			if (!parent) return false
		}
		return el
	},
	getElementsByClassName: function (className) {
		return this.getElements('.' + className)
	}
});
document.extend(Element.Methods.Dom);
Element.extend(Element.Methods.Dom);
Element.extend({
	getValue: function () {
		switch (this.getTag()) {
		case 'select':
			var values = [];
			$each(this.options, function (option) {
				if (option.selected) values.push($pick(option.value, option.text))
			});
			return (this.multiple) ? values: values[0];
		case 'input':
			if (! (this.checked && ['checkbox', 'radio'].contains(this.type)) && !['hidden', 'text', 'password'].contains(this.type)) break;
		case 'textarea':
			return this.value
		}
		return false
	},
	getFormElements: function () {
		return $$(this.getElementsByTagName('input'), this.getElementsByTagName('select'), this.getElementsByTagName('textarea'))
	},
	toQueryString: function () {
		var queryString = [];
		this.getFormElements().each(function (el) {
			var name = el.name;
			var value = el.getValue();
			if (value === false || !name || el.disabled) return;
			var qs = function (val) {
				queryString.push(name + '=' + encodeURIComponent(val))
			};
			if ($type(value) == 'array') value.each(qs);
			else qs(value)
		});
		return queryString.join('&')
	}
});
Element.extend({
	scrollTo: function (x, y) {
		this.scrollLeft = x;
		this.scrollTop = y
	},
	getSize: function () {
		return {
			'scroll': {
				'x': this.scrollLeft,
				'y': this.scrollTop
			},
			'size': {
				'x': this.offsetWidth,
				'y': this.offsetHeight
			},
			'scrollSize': {
				'x': this.scrollWidth,
				'y': this.scrollHeight
			}
		}
	},
	getPosition: function (overflown) {
		overflown = overflown || [];
		var el = this,
		left = 0,
		top = 0;
		do {
			left += el.offsetLeft || 0;
			top += el.offsetTop || 0;
			el = el.offsetParent
		} while (el);
		overflown.each(function (element) {
			left -= element.scrollLeft || 0;
			top -= element.scrollTop || 0
		});
		return {
			'x': left,
			'y': top
		}
	},
	getTop: function (overflown) {
		return this.getPosition(overflown).y
	},
	getLeft: function (overflown) {
		return this.getPosition(overflown).x
	},
	getCoordinates: function (overflown) {
		var position = this.getPosition(overflown);
		var obj = {
			'width': this.offsetWidth,
			'height': this.offsetHeight,
			'left': position.x,
			'top': position.y
		};
		obj.right = obj.left + obj.width;
		obj.bottom = obj.top + obj.height;
		return obj
	}
});
Element.Eventos.domready = {
	add: function (fn) {
		if (window.loaded) {
			fn.call(this);
			return
		}
		var domReady = function () {
			if (window.loaded) return;
			window.loaded = true;
			window.timer = $clear(window.timer);
			this.fireEvent('domready')
		}.bind(this);
		if (document.readyState && window.webkit) {
			window.timer = function () {
				if (['loaded', 'complete'].contains(document.readyState)) domReady()
			}.periodical(50)
		} else if (document.readyState && window.ie) {
			if (!$('ie_ready')) {
				var src = (window.location.protocol == 'https:') ? '://0': 'javascript:void(0)';
				document.write('<script id="ie_ready" defer src="' + src + '"><\/script>');
				$('ie_ready').onreadystatechange = function () {
					if (this.readyState == 'complete') domReady()
				}
			}
		} else {
			window.addListener("load", domReady);
			document.addListener("DOMContentLoaded", domReady)
		}
	}
};
window.onDomReady = function (fn) {
	return this.addEvent('domready', fn)
};
window.extend({
	getWidth: function () {
		if (this.innerWidth) return this.innerWidth;
		if (this.opera) return document.body.clientWidth;
		if (this.ie) return document.body.clientWidth;
		return document.documentElement.clientWidth
	},
	getHeight: function () {
		if (this.innerHeight) return this.innerHeight;
		return document.documentElement.clientHeight
	},
	getScrollWidth: function () {
		if (this.ie) return Math.max(document.body.offsetWidth, document.body.scrollWidth);
		if (this.webkit) return document.body.scrollWidth;
		return document.documentElement.scrollWidth
	},
	getScrollHeight: function () {
		if (this.ie) return Math.max(document.body.offsetHeight, document.body.scrollHeight);
		if (this.webkit) return document.body.scrollHeight;
		return document.documentElement.scrollHeight
	},
	getScrollLeft: function () {
		if (this.ie) return this.pageXOffset || document.body.scrollLeft;
		return this.pageXOffset || document.documentElement.scrollLeft
	},
	getScrollTop: function () {
		if (this.ie) return this.pageYOffset || document.body.scrollTop;
		return this.pageYOffset || document.documentElement.scrollTop
	},
	getSize: function () {
		return {
			'size': {
				'x': this.getWidth(),
				'y': this.getHeight()
			},
			'scrollSize': {
				'x': this.getScrollWidth(),
				'y': this.getScrollHeight()
			},
			'scroll': {
				'x': this.getScrollLeft(),
				'y': this.getScrollTop()
			}
		}
	},
	getPosition: function () {
		return {
			'x': 0,
			'y': 0
		}
	}
});
var Fx = {};
Fx.Base = new Class({
	options: {
		onStart: Class.empty,
		onComplete: Class.empty,
		onCancel: Class.empty,
		transition: function (p) {
			return - (Math.cos(Math.PI * p) - 1) / 2
		},
		duration: 500,
		unit: 'px',
		wait: true,
		fps: 50
	},
	initialize: function (options) {
		this.element = this.element || null;
		this.setOptions(options);
		if (this.options.initialize) this.options.initialize.call(this)
	},
	step: function () {
		var time = $time();
		if (time < this.time + this.options.duration) {
			this.delta = this.options.transition((time - this.time) / this.options.duration);
			this.setNow();
			this.increase()
		} else {
			this.stop(true);
			this.set(this.to);
			this.fireEvent('onComplete', this.element, 10);
			this.callChain()
		}
	},
	set: function (to) {
		this.now = to;
		this.increase();
		return this
	},
	setNow: function () {
		this.now = this.compute(this.from, this.to)
	},
	compute: function (from, to) {
		return (to - from) * this.delta + from
	},
	start: function (from, to) {
		if (!this.options.wait) this.stop();
		else if (this.timer) return this;
		this.from = from;
		this.to = to;
		this.change = this.to - this.from;
		this.time = $time();
		this.timer = this.step.periodical(Math.round(1000 / this.options.fps), this);
		this.fireEvent('onStart', this.element);
		return this
	},
	stop: function (end) {
		if (!this.timer) return this;
		this.timer = $clear(this.timer);
		if (!end) this.fireEvent('onCancel', this.element);
		return this
	},
	custom: function (from, to) {
		return this.start(from, to)
	},
	clearTimer: function (end) {
		return this.stop(end)
	}
});
Fx.Base.implement(new Chain, new Eventos, new Options);
Fx.CSS = {
	select: function (property, to) {
		if (property.test(/color/i)) return this.Color;
		var type = $type(to);
		if ((type == 'array') || (type == 'string' && to.contains(' '))) return this.Multi;
		return this.Single
	},
	parse: function (el, property, fromTo) {
		if (!fromTo.push) fromTo = [fromTo];
		var from = fromTo[0],
		to = fromTo[1];
		if (!$chk(to)) {
			to = from;
			from = el.getEstilo(property)
		}
		var css = this.select(property, to);
		return {
			'from': css.parse(from),
			'to': css.parse(to),
			'css': css
		}
	}
};
Fx.CSS.Single = {
	parse: function (value) {
		return parseFloat(value)
	},
	getNow: function (from, to, fx) {
		return fx.compute(from, to)
	},
	getValue: function (value, unit, property) {
		if (unit == 'px' && property != 'opacity') value = Math.round(value);
		return value + unit
	}
};
Fx.CSS.Multi = {
	parse: function (value) {
		return value.push ? value: value.split(' ').map(function (v) {
			return parseFloat(v)
		})
	},
	getNow: function (from, to, fx) {
		var now = [];
		for (var i = 0; i < from.length; i++) now[i] = fx.compute(from[i], to[i]);
		return now
	},
	getValue: function (value, unit, property) {
		if (unit == 'px' && property != 'opacity') value = value.map(Math.round);
		return value.join(unit + ' ') + unit
	}
};
Fx.CSS.Color = {
	parse: function (value) {
		return value.push ? value: value.hexToRgb(true)
	},
	getNow: function (from, to, fx) {
		var now = [];
		for (var i = 0; i < from.length; i++) now[i] = Math.round(fx.compute(from[i], to[i]));
		return now
	},
	getValue: function (value) {
		return 'rgb(' + value.join(',') + ')'
	}
};
Fx.Style = Fx.Base.extend({
	initialize: function (el, property, options) {
		this.element = $(el);
		this.property = property;
		this.parent(options)
	},
	hide: function () {
		return this.set(0)
	},
	setNow: function () {
		this.now = this.css.getNow(this.from, this.to, this)
	},
	set: function (to) {
		this.css = Fx.CSS.select(this.property, to);
		return this.parent(this.css.parse(to))
	},
	start: function (from, to) {
		if (this.timer && this.options.wait) return this;
		var parsed = Fx.CSS.parse(this.element, this.property, [from, to]);
		this.css = parsed.css;
		return this.parent(parsed.from, parsed.to)
	},
	increase: function () {
		this.element.setStyle(this.property, this.css.getValue(this.now, this.options.unit, this.property))
	}
});
Element.extend({
	effect: function (property, options) {
		return new Fx.Style(this, property, options)
	}
});
Fx.Styles = Fx.Base.extend({
	initialize: function (el, options) {
		this.element = $(el);
		this.parent(options)
	},
	setNow: function () {
		for (var p in this.from) this.now[p] = this.css[p].getNow(this.from[p], this.to[p], this)
	},
	set: function (to) {
		var parsed = {};
		this.css = {};
		for (var p in to) {
			this.css[p] = Fx.CSS.select(p, to[p]);
			parsed[p] = this.css[p].parse(to[p])
		}
		return this.parent(parsed)
	},
	start: function (obj) {
		if (this.timer && this.options.wait) return this;
		this.now = {};
		this.css = {};
		var from = {},
		to = {};
		for (var p in obj) {
			var parsed = Fx.CSS.parse(this.element, p, obj[p]);
			from[p] = parsed.from;
			to[p] = parsed.to;
			this.css[p] = parsed.css
		}
		return this.parent(from, to)
	},
	increase: function () {
		for (var p in this.now) this.element.setStyle(p, this.css[p].getValue(this.now[p], this.options.unit, p))
	}
});
Element.extend({
	effects: function (options) {
		return new Fx.Styles(this, options)
	}
});
Fx.Elements = Fx.Base.extend({
	initialize: function (elements, options) {
		this.elements = $$(elements);
		this.parent(options)
	},
	setNow: function () {
		for (var i in this.from) {
			var iFrom = this.from[i],
			iTo = this.to[i],
			iCss = this.css[i],
			iNow = this.now[i] = {};
			for (var p in iFrom) iNow[p] = iCss[p].getNow(iFrom[p], iTo[p], this)
		}
	},
	set: function (to) {
		var parsed = {};
		this.css = {};
		for (var i in to) {
			var iTo = to[i],
			iCss = this.css[i] = {},
			iParsed = parsed[i] = {};
			for (var p in iTo) {
				iCss[p] = Fx.CSS.select(p, iTo[p]);
				iParsed[p] = iCss[p].parse(iTo[p])
			}
		}
		return this.parent(parsed)
	},
	start: function (obj) {
		if (this.timer && this.options.wait) return this;
		this.now = {};
		this.css = {};
		var from = {},
		to = {};
		for (var i in obj) {
			var iProps = obj[i],
			iFrom = from[i] = {},
			iTo = to[i] = {},
			iCss = this.css[i] = {};
			for (var p in iProps) {
				var parsed = Fx.CSS.parse(this.elements[i], p, iProps[p]);
				iFrom[p] = parsed.from;
				iTo[p] = parsed.to;
				iCss[p] = parsed.css
			}
		}
		return this.parent(from, to)
	},
	increase: function () {
		for (var i in this.now) {
			var iNow = this.now[i],
			iCss = this.css[i];
			for (var p in iNow) this.elements[i].setStyle(p, iCss[p].getValue(iNow[p], this.options.unit, p))
		}
	}
});
Fx.Scroll = Fx.Base.extend({
	options: {
		overflown: [],
		offset: {
			'x': 0,
			'y': 0
		},
		wheelStops: true
	},
	initialize: function (element, options) {
		this.now = [];
		this.element = $(element);
		this.bound = {
			'stop': this.stop.bind(this, false)
		};
		this.parent(options);
		if (this.options.wheelStops) {
			this.addEvent('onStart', function () {
				document.addEvent('mousewheel', this.bound.stop)
			}.bind(this));
			this.addEvent('onComplete', function () {
				document.removeEvento('mousewheel', this.bound.stop)
			}.bind(this))
		}
	},
	setNow: function () {
		for (var i = 0; i < 2; i++) this.now[i] = this.compute(this.from[i], this.to[i])
	},
	scrollTo: function (x, y) {
		if (this.timer && this.options.wait) return this;
		var el = this.element.getSize();
		var values = {
			'x': x,
			'y': y
		};
		for (var z in el.size) {
			var max = el.scrollSize[z] - el.size[z];
			if ($chk(values[z])) values[z] = ($type(values[z]) == 'number') ? values[z].limit(0, max) : max;
			else values[z] = el.scroll[z];
			values[z] += this.options.offset[z]
		}
		return this.start([el.scroll.x, el.scroll.y], [values.x, values.y])
	},
	toTop: function () {
		return this.scrollTo(false, 0)
	},
	toBottom: function () {
		return this.scrollTo(false, 'full')
	},
	toLeft: function () {
		return this.scrollTo(0, false)
	},
	toRight: function () {
		return this.scrollTo('full', false)
	},
	toElement: function (el) {
		var parent = this.element.getPosition(this.options.overflown);
		var target = $(el).getPosition(this.options.overflown);
		return this.scrollTo(target.x - parent.x, target.y - parent.y)
	},
	increase: function () {
		this.element.scrollTo(this.now[0], this.now[1])
	}
});
Fx.Slide = Fx.Base.extend({
	options: {
		mode: 'vertical'
	},
	initialize: function (el, options) {
		this.element = $(el);
		this.wrapper = new Element('div', {
			'styles': $extend(this.element.getEstilos('margin'), {
				'overflow': 'hidden'
			})
		}).injectAfter(this.element).adopt(this.element);
		this.element.setStyle('margin', 0);
		this.setOptions(options);
		this.now = [];
		this.parent(this.options);
		this.open = true;
		this.addEvent('onComplete', function () {
			this.open = (this.now[0] === 0)
		});
		if (window.webkit419) this.addEvent('onComplete', function () {
			if (this.open) this.element.remove().inject(this.wrapper)
		})
	},
	setNow: function () {
		for (var i = 0; i < 2; i++) this.now[i] = this.compute(this.from[i], this.to[i])
	},
	vertical: function () {
		this.margin = 'margin-top';
		this.layout = 'height';
		this.offset = this.element.offsetHeight
	},
	horizontal: function () {
		this.margin = 'margin-left';
		this.layout = 'width';
		this.offset = this.element.offsetWidth
	},
	slideIn: function (mode) {
		this[mode || this.options.mode]();
		return this.start([this.element.getEstilo(this.margin).toInt(), this.wrapper.getEstilo(this.layout).toInt()], [0, this.offset])
	},
	slideOut: function (mode) {
		this[mode || this.options.mode]();
		return this.start([this.element.getEstilo(this.margin).toInt(), this.wrapper.getEstilo(this.layout).toInt()], [ - this.offset, 0])
	},
	hide: function (mode) {
		this[mode || this.options.mode]();
		this.open = false;
		return this.set([ - this.offset, 0])
	},
	mostrar: function (mode) {
		this[mode || this.options.mode]();
		this.open = true;
		return this.set([0, this.offset])
	},
	toggle: function (mode) {
		if (this.wrapper.offsetHeight == 0 || this.wrapper.offsetWidth == 0) return this.slideIn(mode);
		return this.slideOut(mode)
	},
	increase: function () {
		this.element.setStyle(this.margin, this.now[0] + this.options.unit);
		this.wrapper.setStyle(this.layout, this.now[1] + this.options.unit)
	}
});
Fx.Transition = function (transition, params) {
	params = params || [];
	if ($type(params) != 'array') params = [params];
	return $extend(transition, {
		easeIn: function (pos) {
			return transition(pos, params)
		},
		easeOut: function (pos) {
			return 1 - transition(1 - pos, params)
		},
		easeInOut: function (pos) {
			return (pos <= 0.5) ? transition(2 * pos, params) / 2 : (2 - transition(2 * (1 - pos), params)) / 2
		}
	})
};
Fx.Transitions = new Abstract({
	linear: function (p) {
		return p
	}
});
Fx.Transitions.extend = function (transitions) {
	for (var transition in transitions) {
		Fx.Transitions[transition] = new Fx.Transition(transitions[transition]);
		Fx.Transitions.compat(transition)
	}
};
Fx.Transitions.compat = function (transition) { ['In', 'Out', 'InOut'].each(function (easeType) {
		Fx.Transitions[transition.toLowerCase() + easeType] = Fx.Transitions[transition]['ease' + easeType]
	})
};
Fx.Transitions.extend({
	Pow: function (p, x) {
		return Math.pow(p, x[0] || 6)
	},
	Expo: function (p) {
		return Math.pow(2, 8 * (p - 1))
	},
	Circ: function (p) {
		return 1 - Math.sin(Math.acos(p))
	},
	Sine: function (p) {
		return 1 - Math.sin((1 - p) * Math.PI / 2)
	},
	Back: function (p, x) {
		x = x[0] || 1.618;
		return Math.pow(p, 2) * ((x + 1) * p - x)
	},
	Bounce: function (p) {
		var value;
		for (var a = 0, b = 1; 1; a += b, b /= 2) {
			if (p >= (7 - 4 * a) / 11) {
				value = -Math.pow((11 - 6 * a - 11 * p) / 4, 2) + b * b;
				break
			}
		}
		return value
	},
	Elastic: function (p, x) {
		return Math.pow(2, 10 * --p) * Math.cos(20 * p * Math.PI * (x[0] || 1) / 3)
	}
});
['Quad', 'Cubic', 'Quart', 'Quint'].each(function (transition, i) {
	Fx.Transitions[transition] = new Fx.Transition(function (p) {
		return Math.pow(p, [i + 2])
	});
	Fx.Transitions.compat(transition)
});
var Drag = {};
Drag.Base = new Class({
	options: {
		handle: false,
		unit: 'px',
		onStart: Class.empty,
		onBeforeStart: Class.empty,
		onComplete: Class.empty,
		onSnap: Class.empty,
		onDrag: Class.empty,
		limit: false,
		modifiers: {
			x: 'left',
			y: 'top'
		},
		grid: false,
		snap: 6
	},
	initialize: function (el, options) {
		this.setOptions(options);
		this.element = $(el);
		this.handle = $(this.options.handle) || this.element;
		this.mouse = {
			'now': {},
			'pos': {}
		};
		this.value = {
			'start': {},
			'now': {}
		};
		this.bound = {
			'start': this.start.bindWithEvento(this),
			'check': this.check.bindWithEvento(this),
			'drag': this.drag.bindWithEvento(this),
			'stop': this.stop.bind(this)
		};
		this.attach();
		if (this.options.initialize) this.options.initialize.call(this)
	},
	attach: function () {
		this.handle.addEvent('mousedown', this.bound.start);
		return this
	},
	detach: function () {
		this.handle.removeEvento('mousedown', this.bound.start);
		return this
	},
	start: function (evento) {
		this.fireEvent('onBeforeStart', this.element);
		this.mouse.start = evento.page;
		var limit = this.options.limit;
		this.limit = {
			'x': [],
			'y': []
		};
		for (var z in this.options.modifiers) {
			if (!this.options.modifiers[z]) continue;
			this.value.now[z] = this.element.getEstilo(this.options.modifiers[z]).toInt();
			this.mouse.pos[z] = evento.page[z] - this.value.now[z];
			if (limit && limit[z]) {
				for (var i = 0; i < 2; i++) {
					if ($chk(limit[z][i])) this.limit[z][i] = ($type(limit[z][i]) == 'function') ? limit[z][i]() : limit[z][i]
				}
			}
		}
		if ($type(this.options.grid) == 'number') this.options.grid = {
			'x': this.options.grid,
			'y': this.options.grid
		};
		document.addListener('mousemove', this.bound.check);
		document.addListener('mouseup', this.bound.stop);
		this.fireEvent('onStart', this.element);
		evento.stop()
	},
	check: function (evento) {
		var distance = Math.round(Math.sqrt(Math.pow(evento.page.x - this.mouse.start.x, 2) + Math.pow(evento.page.y - this.mouse.start.y, 2)));
		if (distance > this.options.snap) {
			document.removeListener('mousemove', this.bound.check);
			document.addListener('mousemove', this.bound.drag);
			this.drag(evento);
			this.fireEvent('onSnap', this.element)
		}
		evento.stop()
	},
	drag: function (evento) {
		this.out = false;
		this.mouse.now = evento.page;
		for (var z in this.options.modifiers) {
			if (!this.options.modifiers[z]) continue;
			this.value.now[z] = this.mouse.now[z] - this.mouse.pos[z];
			if (this.limit[z]) {
				if ($chk(this.limit[z][1]) && (this.value.now[z] > this.limit[z][1])) {
					this.value.now[z] = this.limit[z][1];
					this.out = true
				} else if ($chk(this.limit[z][0]) && (this.value.now[z] < this.limit[z][0])) {
					this.value.now[z] = this.limit[z][0];
					this.out = true
				}
			}
			if (this.options.grid[z]) this.value.now[z] -= (this.value.now[z] % this.options.grid[z]);
			this.element.setStyle(this.options.modifiers[z], this.value.now[z] + this.options.unit)
		}
		this.fireEvent('onDrag', this.element);
		evento.stop()
	},
	stop: function () {
		document.removeListener('mousemove', this.bound.check);
		document.removeListener('mousemove', this.bound.drag);
		document.removeListener('mouseup', this.bound.stop);
		this.fireEvent('onComplete', this.element)
	}
});
Drag.Base.implement(new Eventos, new Options);
Element.extend({
	makeResizable: function (options) {
		return new Drag.Base(this, $merge({
			modifiers: {
				x: 'width',
				y: 'height'
			}
		},
		options))
	}
});
Drag.Move = Drag.Base.extend({
	options: {
		droppables: [],
		container: false,
		overflown: []
	},
	initialize: function (el, options) {
		this.setOptions(options);
		this.element = $(el);
		this.droppables = $$(this.options.droppables);
		this.container = $(this.options.container);
		this.position = {
			'element': this.element.getEstilo('position'),
			'container': false
		};
		if (this.container) this.position.container = this.container.getEstilo('position');
		if (! ['relative', 'absolute', 'fixed'].contains(this.position.element)) this.position.element = 'absolute';
		var top = this.element.getEstilo('top').toInt();
		var left = this.element.getEstilo('left').toInt();
		if (this.position.element == 'absolute' && !['relative', 'absolute', 'fixed'].contains(this.position.container)) {
			top = $chk(top) ? top: this.element.getTop(this.options.overflown);
			left = $chk(left) ? left: this.element.getLeft(this.options.overflown)
		} else {
			top = $chk(top) ? top: 0;
			left = $chk(left) ? left: 0
		}
		this.element.setStyles({
			'top': top,
			'left': left,
			'position': this.position.element
		});
		this.parent(this.element)
	},
	start: function (evento) {
		this.overed = null;
		if (this.container) {
			var cont = this.container.getCoordinates();
			var el = this.element.getCoordinates();
			if (this.position.element == 'absolute' && !['relative', 'absolute', 'fixed'].contains(this.position.container)) {
				this.options.limit = {
					'x': [cont.left, cont.right - el.width],
					'y': [cont.top, cont.bottom - el.height]
				}
			} else {
				this.options.limit = {
					'y': [0, cont.height - el.height],
					'x': [0, cont.width - el.width]
				}
			}
		}
		this.parent(evento)
	},
	drag: function (evento) {
		this.parent(evento);
		var overed = this.out ? false: this.droppables.filter(this.checkAgainst, this).getLast();
		if (this.overed != overed) {
			if (this.overed) this.overed.fireEvent('leave', [this.element, this]);
			this.overed = overed ? overed.fireEvent('over', [this.element, this]) : null
		}
		return this
	},
	checkAgainst: function (el) {
		el = el.getCoordinates(this.options.overflown);
		var now = this.mouse.now;
		return (now.x > el.left && now.x < el.right && now.y < el.bottom && now.y > el.top)
	},
	stop: function () {
		if (this.overed && !this.out) this.overed.fireEvent('drop', [this.element, this]);
		else this.element.fireEvent('emptydrop', this);
		this.parent();
		return this
	}
});
Element.extend({
	makeDraggable: function (options) {
		return new Drag.Move(this, options)
	}
});
var Cookie = new Abstract({
	options: {
		domain: false,
		path: false,
		duration: false,
		secure: false
	},
	set: function (key, value, options) {
		options = $merge(this.options, options);
		value = encodeURIComponent(value);
		if (options.domain) value += '; domain=' + options.domain;
		if (options.path) value += '; path=' + options.path;
		if (options.duration) {
			var date = new Date();
			date.setTime(date.getTime() + options.duration * 24 * 60 * 60 * 1000);
			value += '; expires=' + date.toGMTString()
		}
		if (options.secure) value += '; secure';
		document.cookie = key + '=' + value;
		return $extend(options, {
			'key': key,
			'value': value
		})
	},
	get: function (key) {
		var value = document.cookie.match('(?:^|;)\\s*' + key.escapeRegExp() + '=([^;]*)');
		return value ? decodeURIComponent(value[1]) : false
	},
	remove: function (cookie, options) {
		if ($type(cookie) == 'object') this.set(cookie.key, '', $merge(cookie, {
			duration: -1
		}));
		else this.set(cookie, '', $merge(options, {
			duration: -1
		}))
	}
});
var Json = {
	toString: function (obj) {
		switch ($type(obj)) {
		case 'string':
			return '"' + obj.replace(/(["\\])/g, '\\$1') + '"';
		case 'array':
			return '[' + obj.map(Json.toString).join(',') + ']';
		case 'object':
			var string = [];
			for (var property in obj) string.push(Json.toString(property) + ':' + Json.toString(obj[property]));
			return '{' + string.join(',') + '}';
		case 'number':
			if (isFinite(obj)) break;
		case false:
			return 'null'
		}
		return String(obj)
	},
	evaluate: function (str, secure) {
		return (($type(str) != 'string') || (secure && !str.test(/^("(\\.|[^"\\\n\r])*?"|[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t])+?$/))) ? null: eval('(' + str + ')')
	}
};
var Hash = new Class({
	length: 0,
	initialize: function (object) {
		this.obj = object || {};
		this.setLength()
	},
	get: function (key) {
		return (this.hasKey(key)) ? this.obj[key] : null
	},
	hasKey: function (key) {
		return (key in this.obj)
	},
	set: function (key, value) {
		if (!this.hasKey(key)) this.length++;
		this.obj[key] = value;
		return this
	},
	setLength: function () {
		this.length = 0;
		for (var p in this.obj) this.length++;
		return this
	},
	remove: function (key) {
		if (this.hasKey(key)) {
			delete this.obj[key];
			this.length--
		}
		return this
	},
	each: function (fn, bind) {
		$each(this.obj, fn, bind)
	},
	extend: function (obj) {
		$extend(this.obj, obj);
		return this.setLength()
	},
	merge: function () {
		this.obj = $merge.apply(null, [this.obj].extend(arguments));
		return this.setLength()
	},
	empty: function () {
		this.obj = {};
		this.length = 0;
		return this
	},
	keys: function () {
		var keys = [];
		for (var property in this.obj) keys.push(property);
		return keys
	},
	values: function () {
		var values = [];
		for (var property in this.obj) values.push(this.obj[property]);
		return values
	}
});
function $H(obj) {
	return new Hash(obj)
};
Hash.Cookie = Hash.extend({
	initialize: function (name, options) {
		this.name = name;
		this.options = $extend({
			'autoSave': true
		},
		options || {});
		this.load()
	},
	save: function () {
		if (this.length == 0) {
			Cookie.remove(this.name, this.options);
			return true
		}
		var str = Json.toString(this.obj);
		if (str.length > 4096) return false;
		Cookie.set(this.name, str, this.options);
		return true
	},
	load: function () {
		this.obj = Json.evaluate(Cookie.get(this.name), true) || {};
		this.setLength()
	}
});
Hash.Cookie.Methods = {};
['extend', 'set', 'merge', 'empty', 'remove'].each(function (method) {
	Hash.Cookie.Methods[method] = function () {
		Hash.prototype[method].apply(this, arguments);
		if (this.options.autoSave) this.save();
		return this
	}
});
Hash.Cookie.implement(Hash.Cookie.Methods);
var Sortables = new Class({
	options: {
		handles: false,
		onStart: Class.empty,
		onComplete: Class.empty,
		ghost: true,
		snap: 3,
		onDragStart: function (element, ghost) {
			ghost.setStyle('opacity', 0.7);
			element.setStyle('opacity', 0.7)
		},
		onDragComplete: function (element, ghost) {
			element.setStyle('opacity', 1);
			ghost.remove();
			this.trash.remove()
		}
	},
	initialize: function (list, options) {
		this.setOptions(options);
		this.list = $(list);
		this.elements = this.list.getChildren();
		this.handles = (this.options.handles) ? $$(this.options.handles) : this.elements;
		this.bound = {
			'start': [],
			'moveGhost': this.moveGhost.bindWithEvento(this)
		};
		for (var i = 0, l = this.handles.length; i < l; i++) {
			this.bound.start[i] = this.start.bindWithEvento(this, this.elements[i])
		}
		this.attach();
		if (this.options.initialize) this.options.initialize.call(this);
		this.bound.move = this.move.bindWithEvento(this);
		this.bound.end = this.end.bind(this)
	},
	attach: function () {
		this.handles.each(function (handle, i) {
			handle.addEvent('mousedown', this.bound.start[i])
		},
		this)
	},
	detach: function () {
		this.handles.each(function (handle, i) {
			handle.removeEvento('mousedown', this.bound.start[i])
		},
		this)
	},
	start: function (evento, el) {
		this.active = el;
		this.coordinates = this.list.getCoordinates();
		if (this.options.ghost) {
			var position = el.getPosition();
			this.offset = evento.page.y - position.y;
			this.trash = new Element('div').inject(document.body);
			this.ghost = el.clone().inject(this.trash).setStyles({
				'position': 'absolute',
				'left': position.x,
				'top': evento.page.y - this.offset
			});
			document.addListener('mousemove', this.bound.moveGhost);
			this.fireEvent('onDragStart', [el, this.ghost])
		}
		document.addListener('mousemove', this.bound.move);
		document.addListener('mouseup', this.bound.end);
		this.fireEvent('onStart', el);
		evento.stop()
	},
	moveGhost: function (evento) {
		var value = evento.page.y - this.offset;
		value = value.limit(this.coordinates.top, this.coordinates.bottom - this.ghost.offsetHeight);
		this.ghost.setStyle('top', value);
		evento.stop()
	},
	move: function (evento) {
		var now = evento.page.y;
		this.previous = this.previous || now;
		var up = ((this.previous - now) > 0);
		var prev = this.active.getPrevious();
		var next = this.active.getNext();
		if (prev && up && now < prev.getCoordinates().bottom) this.active.injectBefore(prev);
		if (next && !up && now > next.getCoordinates().top) this.active.injectAfter(next);
		this.previous = now
	},
	serialize: function (converter) {
		return this.list.getChildren().map(converter ||
		function (el) {
			return this.elements.indexOf(el)
		},
		this)
	},
	end: function () {
		this.previous = null;
		document.removeListener('mousemove', this.bound.move);
		document.removeListener('mouseup', this.bound.end);
		if (this.options.ghost) {
			document.removeListener('mousemove', this.bound.moveGhost);
			this.fireEvent('onDragComplete', [this.active, this.ghost])
		}
		this.fireEvent('onComplete', this.active)
	}
});
Sortables.implement(new Eventos, new Options);
var Tips = new Class({
	options: {
		onMostrar: function (toolTip) {
			this.fx.start(1)
		},
		onHide: function (toolTip) {
			this.fx.start(0)
		},
		maxTitleChars: 100,
		mostrarDelay: 100,
		hideDelay: 100,
		className: 'tool',
		offsets: {
			'x': 16,
			'y': 16
		},
		fixed: false
	},
	initialize: function (elements, options) {
		this.setOptions(options);
		this.toolTip = new Element('div', {
			'class': this.options.className + '-tip',
			'styles': {
				'position': 'absolute',
				'top': '0',
				'left': '0',
				'visibility': 'hidden'
			}
		}).inject(document.body);
		this.wrapper = new Element('div').inject(this.toolTip);
		$$(elements).each(this.build, this);
		this.fx = new Fx.Style(this.toolTip, 'opacity', {
			duration: 125,
			wait: false
		}).set(0);
		if (this.options.initialize) this.options.initialize.call(this)
	},
	build: function (el) {
		el.$tmp.myTitle = (el.href && el.getTag() == 'a') ? el.href.replace('http://', '') : (el.rel || false);
		if (el.title) {
			var dual = el.title.split('::');
			if (dual.length > 1) {
				el.$tmp.myTitle = dual[0].trim();
				el.$tmp.myText = dual[1].trim()
			} else {
				el.$tmp.myText = el.title
			}
			el.removeAttribute('title')
		} else {
			el.$tmp.myText = false
		}
		if (el.$tmp.myTitle && el.$tmp.myTitle.length > this.options.maxTitleChars) el.$tmp.myTitle = el.$tmp.myTitle.substr(0, this.options.maxTitleChars - 1) + "&hellip;";
		el.addEvent('mouseenter', function (evento) {
			this.start(el);
			if (!this.options.fixed) this.locate(evento);
			else this.position(el)
		}.bind(this));
		if (!this.options.fixed) el.addEvent('mousemove', this.locate.bindWithEvento(this));
		var end = this.end.bind(this);
		el.addEvent('mouseleave', end);
		el.addEvent('trash', end)
	},
	start: function (el) {
		this.wrapper.empty();
		if (el.$tmp.myTitle) {
			this.title = new Element('span').inject(new Element('div', {
				'class': this.options.className + '-title'
			}).inject(this.wrapper)).setHTML(el.$tmp.myTitle)
		}
		if (el.$tmp.myText) {
			this.text = new Element('span').inject(new Element('div', {
				'class': this.options.className + '-text'
			}).inject(this.wrapper)).setHTML(el.$tmp.myText)
		}
		$clear(this.timer);
		this.timer = this.mostrar.delay(this.options.mostrarDelay, this)
	},
	end: function (evento) {
		$clear(this.timer);
		this.timer = this.hide.delay(this.options.hideDelay, this)
	},
	position: function (element) {
		var pos = element.getPosition();
		this.toolTip.setStyles({
			'left': pos.x + this.options.offsets.x,
			'top': pos.y + this.options.offsets.y
		})
	},
	locate: function (evento) {
		var win = {
			'x': window.getWidth(),
			'y': window.getHeight()
		};
		var scroll = {
			'x': window.getScrollLeft(),
			'y': window.getScrollTop()
		};
		var tip = {
			'x': this.toolTip.offsetWidth,
			'y': this.toolTip.offsetHeight
		};
		var prop = {
			'x': 'left',
			'y': 'top'
		};
		for (var z in prop) {
			var pos = evento.page[z] + this.options.offsets[z];
			if ((pos + tip[z] - scroll[z]) > win[z]) pos = evento.page[z] - this.options.offsets[z] - tip[z];
			this.toolTip.setStyle(prop[z], pos)
		}
	},
	mostrar: function () {
		if (this.options.timeout) this.timer = this.hide.delay(this.options.timeout, this);
		this.fireEvent('onMostrar', [this.toolTip])
	},
	hide: function () {
		this.fireEvent('onHide', [this.toolTip])
	}
});
Tips.implement(new Eventos, new Options);
var Accordion = Fx.Elements.extend({
	options: {
		onActive: Class.empty,
		onBackground: Class.empty,
		display: 0,
		mostrar: false,
		height: true,
		width: false,
		opacity: true,
		fixedHeight: false,
		fixedWidth: false,
		wait: false,
		alwaysHide: false
	},
	initialize: function () {
		var options, togglers, elements, container;
		$each(arguments, function (argument, i) {
			switch ($type(argument)) {
			case 'object':
				options = argument;
				break;
			case 'element':
				container = $(argument);
				break;
			default:
				var temp = $$(argument);
				if (!togglers) togglers = temp;
				else elements = temp
			}
		});
		this.togglers = togglers || [];
		this.elements = elements || [];
		this.container = $(container);
		this.setOptions(options);
		this.previous = -1;
		if (this.options.alwaysHide) this.options.wait = true;
		if ($chk(this.options.mostrar)) {
			this.options.display = false;
			this.previous = this.options.mostrar
		}
		if (this.options.start) {
			this.options.display = false;
			this.options.mostrar = false
		}
		this.effects = {};
		if (this.options.opacity) this.effects.opacity = 'fullOpacity';
		if (this.options.width) this.effects.width = this.options.fixedWidth ? 'fullWidth': 'offsetWidth';
		if (this.options.height) this.effects.height = this.options.fixedHeight ? 'fullHeight': 'scrollHeight';
		for (var i = 0, l = this.togglers.length; i < l; i++) this.addSection(this.togglers[i], this.elements[i]);
		this.elements.each(function (el, i) {
			if (this.options.mostrar === i) {
				this.fireEvent('onActive', [this.togglers[i], el])
			} else {
				for (var fx in this.effects) el.setStyle(fx, 0)
			}
		},
		this);
		this.parent(this.elements);
		if ($chk(this.options.display)) this.display(this.options.display)
	},
	addSection: function (toggler, element, pos) {
		toggler = $(toggler);
		element = $(element);
		var test = this.togglers.contains(toggler);
		var len = this.togglers.length;
		this.togglers.include(toggler);
		this.elements.include(element);
		if (len && (!test || pos)) {
			pos = $pick(pos, len - 1);
			toggler.injectBefore(this.togglers[pos]);
			element.injectAfter(toggler)
		} else if (this.container && !test) {
			toggler.inject(this.container);
			element.inject(this.container)
		}
		var idx = this.togglers.indexOf(toggler);
		toggler.addEvent('click', this.display.bind(this, idx));
		if (this.options.height) element.setStyles({
			'padding-top': 0,
			'border-top': 'none',
			'padding-bottom': 0,
			'border-bottom': 'none'
		});
		if (this.options.width) element.setStyles({
			'padding-left': 0,
			'border-left': 'none',
			'padding-right': 0,
			'border-right': 'none'
		});
		element.fullOpacity = 1;
		if (this.options.fixedWidth) element.fullWidth = this.options.fixedWidth;
		if (this.options.fixedHeight) element.fullHeight = this.options.fixedHeight;
		element.setStyle('overflow', 'hidden');
		if (!test) {
			for (var fx in this.effects) element.setStyle(fx, 0)
		}
		return this
	},
	display: function (index) {
		index = ($type(index) == 'element') ? this.elements.indexOf(index) : index;
		if ((this.timer && this.options.wait) || (index === this.previous && !this.options.alwaysHide)) return this;
		this.previous = index;
		var obj = {};
		this.elements.each(function (el, i) {
			obj[i] = {};
			var hide = (i != index) || (this.options.alwaysHide && (el.offsetHeight > 0));
			this.fireEvent(hide ? 'onBackground': 'onActive', [this.togglers[i], el]);
			for (var fx in this.effects) obj[i][fx] = hide ? 0 : el[this.effects[fx]]
		},
		this);
		return this.start(obj)
	},
	mostrarThisHideOpen: function (index) {
		return this.display(index)
	}
});
Fx.Accordion = Accordion;