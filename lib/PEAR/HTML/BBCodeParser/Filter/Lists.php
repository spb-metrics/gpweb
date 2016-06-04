<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
//
// $Id: Lists.php 25 2008-01-26 16:00:46Z pedroix $
//


/**
* @package  HTML_BBCodeParser
*/

require_once($Aplic->getClasseBiblioteca('PEAR/HTML/BBCodeParser/Filter'));

/**
 * 
 */
class HTML_BBCodeParser_Filter_Lists extends HTML_BBCodeParser_Filter
{

    /**
    * An array of tags parsed by the engine
    *
    * @access   private
    * @var      array
    */
    var $_definedTags = array(  'list'  => array(   'htmlopen'  => 'ol',
                                                    'htmlclose' => 'ol',
                                                    'allowed'   => 'all',
                                                    'child'     => 'none^li',
                                                    'attributes'=> array('list'  => 'style=%2$slist-style-type:%1$s;%2$s')
                                                    ),
                                'ulist' => array(   'htmlopen'  => 'ul',
                                                    'htmlclose' => 'ul',
                                                    'allowed'   => 'all',
                                                    'child'     => 'none^li',
                                                    'attributes'=> array('list'  => 'style=%2$slist-style-type:%1$s;%2$s')
                                                    ),
                                'li'    => array(   'htmlopen'  => 'li',
                                                    'htmlclose' => 'li',
                                                    'allowed'   => 'all',
                                                    'parent'    => 'none^ulist,list',
                                                    'attributes'=> array()
                                                    )
                                );


    /**
    * Executes statements before the actual array building starts
    *
    * This method should be overwritten in a filter if you want to do
    * something before the parsing process starts. This can be useful to
    * allow certain short alternative tags which then can be converted into
    * proper tags with preg_replace() calls.
    * The main class walks through all the filters and and calls this
    * method if it exists. The filters should modify their private $_text
    * variable.
    *
    * @return   none
    * @access   private
    * @see      $_text
    */
    function _preparse()
    {
        $options = HTML_BBCodeParser_Filter::_getOptions();
        $o = $options['open'];
        $c = $options['close'];
        $oe = $options['open_esc'];
        $ce = $options['close_esc'];
        
        $padrao = array(   "!".$oe."\*".$ce."!",
                            "!".$oe."(u?)list=(?-i:A)(\s*[^".$ce."]*)".$ce."!i",
                            "!".$oe."(u?)list=(?-i:a)(\s*[^".$ce."]*)".$ce."!i",
                            "!".$oe."(u?)list=(?-i:I)(\s*[^".$ce."]*)".$ce."!i",
                            "!".$oe."(u?)list=(?-i:i)(\s*[^".$ce."]*)".$ce."!i",
                            "!".$oe."(u?)list=(?-i:1)(\s*[^".$ce."]*)".$ce."!i",
                            "!".$oe."(u?)list([^".$ce."]*)".$ce."!i");
        
        $replace = array(   $o."li".$c,
                            $o."\$1list=upper-alpha\$2".$c,
                            $o."\$1list=lower-alpha\$2".$c,
                            $o."\$1list=upper-roman\$2".$c,
                            $o."\$1list=lower-roman\$2".$c,
                            $o."\$1list=decimal\$2".$c,
                            $o."\$1list\$2".$c );
        
        $this->_preparsed = preg_replace($padrao, $replace, $this->_text);
    }
}


?>