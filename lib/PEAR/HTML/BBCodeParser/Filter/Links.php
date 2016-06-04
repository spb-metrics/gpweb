<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
//
// $Id: Links.php 25 2008-01-26 16:00:46Z pedroix $
//

/**
* @package  HTML_BBCodeParser
*/
require_once($Aplic->getClasseBiblioteca('PEAR/HTML/BBCodeParser/Filter'));

/**
 *
 */
class HTML_BBCodeParser_Filter_Links extends HTML_BBCodeParser_Filter
{
    /**
     * List of allowed schemes
     *
     * @access  private
     * @var     array
     */
    var $_allowedSchemes = array('http', 'https', 'ftp');

    /**
     * Default scheme
     *
     * @access  private
     * @var     string
     */
    var $_defaultScheme = 'http';

    /**
     * An array of tags parsed by the engine
     *
     * @access   private
     * @var      array
     */
    var $_definedTags = array(
        'url' => array(
            'htmlopen'  => 'a',
            'htmlclose' => 'a',
            'allowed'   => 'none^img',
            'attributes'=> array('url' => 'href=%2$s%1$s%2$s')
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

        $schemes = implode('|', $this->_allowedSchemes);

        $padrao = array(   "/(?<![\"'=".$ce."\/])(".$oe."[^".$ce."]*".$ce.")?(((".$schemes."):\/\/|www)[@-a-z0-9.]+\.[a-z]{2,4}[^\s()\[\]]*)/i",
                            "!".$oe."url(".$ce."|\s.*".$ce.")(.*)".$oe."/url".$ce."!iU",
                            "!".$oe."url=((([a-z]*:(//)?)|www)[@-a-z0-9.]+)([^\s\[\]]*)".$ce."(.*)".$oe."/url".$ce."!i");

        $pp = preg_replace_callback($padrao[0], array($this, 'smarterPPLinkExpand'), $this->_text);
        $pp = preg_replace($padrao[1], $o."url=\$2\$1\$2".$o."/url".$c, $pp);
        $this->_preparsed = preg_replace_callback($padrao[2], array($this, 'smarterPPLink'), $pp);

    }

    /**
     * Intelligently expand a URL into a link
     *
     * @return  string
     * @access  private
     */
    function smarterPPLinkExpand($comparados)
    {
        $options = HTML_BBCodeParser_Filter::_getOptions();
        $o = $options['open'];
        $c = $options['close'];

        //If we have an intro tag that is [url], then skip this match
        if ($comparados[1] == $o.'url'.$c) {
            return $comparados[0];
        }

        $punctuation = '.,;:'; // Links can't end with these chars
        $trailing = '';
        // Knock off ending punctuation
        $ultimo = substr($comparados[2], -1);
        while (strpos($punctuation, $ultimo) !== false) {
            // Last character is punctuation - remove it from the url
            $trailing = $ultimo.$trailing;
            $comparados[2] = substr($comparados[2], 0, -1);
            $ultimo = substr($comparados[2], -1);
        }

        $off = strpos($comparados[2], ':');

        //Is a ":" (therefore a scheme) defined?
        if ($off === false) {
            /*
             * Create a link with the default scheme of http. Notice that the
             * text that is verable to the user is unchanged, but the link
             * itself contains the "http://".
             */
            return $comparados[1].$o.'url='.$this->_defaultScheme.'://'.$comparados[2].$c.$comparados[2].$o.'/url'.$c.$trailing;
        }

        $scheme = substr($comparados[2], 0, $off);

        /*
         * If protocol is in the approved list than allow it. Note that this
         * check isn't really needed, but the created link will just be deleted
         * later in smarterPPLink() if we create it now and it isn't on the
         * scheme list.
         */
        if (in_array($scheme, $this->_allowedSchemes)) {
            return $comparados[1].$o.'url'.$c.$comparados[2].$o.'/url'.$c.$trailing;
        }
        
        return $comparados[0];
    }

    /**
     * Finish preparsing URL to clean it up
     *
     * @return  string
     * @access  private
     */
    function smarterPPLink($comparados)
    {
        $options = HTML_BBCodeParser_Filter::_getOptions();
        $o = $options['open'];
        $c = $options['close'];

        $urlServ = $comparados[1];
        $caminho = $comparados[5];

        $off = strpos($urlServ, ':');

        if ($off === false) {
            //Default to http
            $urlServ = $this->_defaultScheme.'://'.$urlServ;
            $off = strpos($urlServ, ':');
        }

        //Add trailing slash if missing (to create a valid URL)
        if (!$caminho) {
            $caminho = '/';
        }

        $protocol = substr($urlServ, 0, $off);

        if (in_array($protocol, $this->_allowedSchemes)) {
            //If protocol is in the approved list than allow it
            return $o.'url='.$urlServ.$caminho.$c.$comparados[6].$o.'/url'.$c;
        }
        
        //Else remove url tag
        return $comparados[6];
    }
}
?>