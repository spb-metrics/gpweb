<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
//
// $Id: Basic.php 25 2008-01-26 16:00:46Z pedroix $
//

/**
* @package  HTML_BBCodeParser
*/


require_once($Aplic->getClasseBiblioteca('PEAR/HTML/BBCodeParser/Filter'));




class HTML_BBCodeParser_Filter_Basic extends HTML_BBCodeParser_Filter
{

    /**
    * An array of tags parsed by the engine
    *
    * @access   private
    * @var      array
    */
    var $_definedTags = array(  'b' => array(   'htmlopen'  => 'strong',
                                                'htmlclose' => 'strong',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'i' => array(   'htmlopen'  => 'em',
                                                'htmlclose' => 'em',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'u' => array(   'htmlopen'  => 'span style="text-decoration:underline;"',
                                                'htmlclose' => 'span',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                's' => array(   'htmlopen'  => 'del',
                                                'htmlclose' => 'del',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'sub' => array( 'htmlopen'  => 'sub',
                                                'htmlclose' => 'sub',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'sup' => array( 'htmlopen'  => 'sup',
                                                'htmlclose' => 'sup',
                                                'allowed'   => 'all',
                                                'attributes'=> array())
                            );

}


?>