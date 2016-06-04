(function( $ ) {
    $.fn.svgTextLayouter = function(options, text) {
        var _opts, _textNode, _tspanNode, _textLines, _firstLine,
            _x, _y, _dx, _dy, _width, _height, _padding, _fontSize, _align, _anchor,
            _valign, _line, _lines;

        breakAnyIfNeed = function(tspan, w){
            var el = tspan.get(0),
                lineInfo = _lines[_line-1],
                len, mid, lastOk = 1, atual, widthOk, lastWidth;

            lineInfo.width = el.getComputedTextLength();

            if( w.length > 1 && lineInfo.width > _width ) {
                len = w.length;
                mid = Math.ceil( len / 2 );
                do {
                    atual = w.substr( 0, mid );
                    tspan.text( atual );
                    lastWidth = el.getComputedTextLength();
                    if( lastWidth > _width ) {
                        len = mid;
                        mid = lastOk + Math.ceil( (len - lastOk) / 2 );
                    }
                    else {
                        lastOk = mid;
                        widthOk = lastWidth;
                        mid += Math.ceil( (len - mid) / 2 );
                    }

                } while( mid < len );

                len = w.length;
                if( len > lastOk ) {
                    ++_line;

                    tspan.text( w.substr( 0, lastOk) );
                    lineInfo.width = widthOk;

                    if( ( _line * _dy ) < _height ) {
                        tspan = newLine( w.substr( lastOk ) );
                    }
                    else if( lastOk >= 3 ) {
                        tspan.text( w.substr( 0, lastOk - 3 ) + '...' );
                    }
                    else {
                        tspan.text( "" );
                    }
                }
            }

            return tspan;
        };

        newLine = function(w){
            var tspan, lineInfo = {words: 0, width: 0};

            if( !w ) {
                w = "";
            }
            else{
                lineInfo['words'] = 1;
            }

            tspan = document.createElementNS('http://www.w3.org/2000/svg', 'tspan');

            _textNode.append(tspan);

            tspan = $(tspan);

            tspan.attr( "x", _x + "px" )
                    .attr( "dx", _dx + "px" )
                    .attr( "dy", _dy + "px" )
                    .css( "baseline-shift", "0%" )
                    .attr( "dominant-baseline", "alphabetic" )
                    .text( w );

            _firstLine = false;

            lineInfo['tspan'] = tspan;

            _lines.push(lineInfo);

            return breakAnyIfNeed(tspan, w);
        };

        addWord = function(w){
            var lineInfo = _lines[_line-1],
                text, char, el, lastWidth;

            if(!w.length){
                return;
            }

            text = _tspanNode.text();
            el = _tspanNode.get( 0 );

            if(!text.length){
                _tspanNode.text(w);
                breakAnyIfNeed(_tspanNode, w);
            }
            else{
                char = text.slice(-1);
                if(char === ' '){
                    char = ''
                }
                else{
                    char = ' ';
                }

                _tspanNode.text(text + char + w);
                lastWidth = el.getComputedTextLength();
                if( lastWidth > _width ){
                    if(( (_line+1) * _dy ) < _height) {
                        _tspanNode.text(text);
                        ++_line;
                        _tspanNode = newLine( w );
                    }
                    else{
                        breakAnyIfNeed(_tspanNode, _tspanNode.text());
                        ++_line;
                    }
                }
                else{
                    lineInfo.width = lastWidth;
                    ++lineInfo.words;
                }
            }
        };

        /**
         * Executa o layout de uma linha de texto.
         *
         * @return {boolean} true se houver espaço vertical para mais linhas, false caso contrário.
         */
        wrapTextLine = function(textLine){
            var words = textLine.split( ' ' ),
                full = false;

            if(!words.length){
                return true;
            }

            ++_line;
            _tspanNode = newLine(words.shift());

            if( ( _line * _dy ) < _height ) {
                $.each( words, function( index, word ) {
                    addWord( word );
                    if( ( _line * _dy ) > _height ) {
                        full = true;
                        return false;
                    }
                } );
            }
            else{
                full = true;
            }

            return !full;
        };

        /**
         * Inicializa os dados para iniciar o processo de layout.
         *
         * As informações de formatação são provenientes das opções especificadas para o plugin
         * ou do container.
         */
        initWrapper = function(container){
            _firstLine = true;
            _x = _opts.x || parseInt(container.attr('x')) || 0;
            _y = _opts.y || parseInt(container.attr('y')) || 0;

            _padding = _opts.padding || 0;

            if(container.is('rect')){
                _width = _opts.width || parseInt(container.attr('width')) || 400;
                _height = _opts.height || parseInt(container.attr('height')) || 400;
            }
            else{
                _width = _opts.width || 400;
                _height = _opts.height || 400;
            }

            _width -= _padding*2;
            _height -= _padding*2;

            _x += _padding;
            _y += _padding;

            if(_width < 0){
                _width = 0;
            }

            if(_height < 0){
                _height = 0;
            }
        };

        /**
         * Executa a operação de layout das linhas de texto para o text node corrente.
         */
        wrapText = function() {
            var anchor;

            _textNode.empty();

            _fontSize = _textNode.attr("font-size") || _textNode.style("font-size");
            _fontSize = parseFloat(_fontSize, 10) || 10;

            _dy = parseFloat(_textNode.attr("dy"), 10) || (_fontSize * 1.1);

            _anchor = _textNode.attr('text-anchor') || 'start';

            _valign = _opts.valign;

            _align = _opts.align;

            if(!_align){
                switch(_anchor){
                    case 'middle':
                        _align = 'center';
                        break;
                    case 'end':
                        _align = 'right';
                        break;
                    default:
                        _align = 'left';
                        break;
                }
            }
            else{
                switch(_align){
                    case 'center':
                        _anchor = 'middle';
                        break;
                    case 'right':
                        _anchor = 'end';
                        break;
                    default:
                        _anchor = 'start';
                        break;
                }
            }

            if( _align === "right" ) {
                _dx = _width;
            }
            else if( _align === "center" ) {
                _dx = _width / 2;
            }
            else {
                _dx = 0;
            }


            _textNode.attr('text-anchor', _anchor)
                    .attr('font-size', _fontSize+'px')
                    .css('font-size', _fontSize+'px')
                    .attr('x', _x)
                    .attr('y', _y);


            _line = 0;
            _lines = [];
            $.each(_textLines, function(index, text){
                text = text.replace( /(\r\n|\n|\r)/gm, "");
                text = text.replace( /^\s+|\s+$/g, "");
                return wrapTextLine(text);
            });

            if(_align === 'justify'){
                $.each(_lines, function(index, lineInfo){
                    var tspan = lineInfo.tspan,
                        justify = (_align === 'justify'),
                        span;

                    if(justify) {
                        space = (_width - lineInfo.width) / (lineInfo.words - 1);
                        space = (index != _lines.length - 1) ? space : 0;
                        tspan.css( 'word-spacing', space + 'px' );
                    }
                });
            }

            var dy;
            if(_valign === 'middle'){
                dy = Math.ceil( ((_height/2) - (( _line * _dy )/2) ) ) + (_dy - _fontSize);
                if(dy>0){
                    _textNode.attr('y', _y + dy );
                }
            }
            else if(_valign === 'bottom'){
                dy = _height - ( _line * _dy );
                if(dy>0){
                    _textNode.attr('y', _y + dy);
                }
            }
        };

        wrapToRect = function(rect, text){
            var sibling = rect.next();
            _textNode = null;


            if(!sibling.is('text')){
                return;
            }

            _textNode = sibling;

            if( typeof text !== "undefined" ) {
                if( typeof text === "string" ) {
                    text = [ text ];
                }
                _textLines = text;
            }
            else {
                _textLines = getText( _textNode );
            }

            initWrapper(rect);
            wrapText();
        };

        /**
         * Retorna as linhas de texto do text tag.
         *
         * Cada tspan tag é considerado uma linha, se não houverem tspan tags o texto contido no text tag será
         * considera uma linha única.
         *
         * @param textNode SVG text nó
         * @returns {Array} Lista de linhas de texto contidas no nó.
         */
        getText = function( textNode ) {
            var text   = [],
                tspans = textNode.children( 'tspan' );

            if( tspans.length ) {
                tspans.each( function() {
                    text.push( $( this ).text() );
                } );
            }
            else {
                text.push( textNode.text() );
            }

            return text;
        };

        _opts = $.extend( $.fn.svgTextLayouter.defaults, options );

        this.filter( "text" ).each( function() {
            _textNode = $( this );
            if( typeof text !== "undefined" ) {
                if( typeof text === "string" ) {
                    text = [ text ];
                }
                _textLines = text;
            }
            else {
                _textLines = getText( _textNode );
            }

            initWrapper(_textNode);
            wrapText();
        } );

        this.filter( "rect" ).each( function() {
            wrapToRect($(this), text);
        } );

        return this;
    };


    $.fn.svgTextLayouter.defaults = {
        valign: 'top'
    };

}( jQuery ));