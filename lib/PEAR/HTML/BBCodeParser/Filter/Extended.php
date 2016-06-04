<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// $Id: Extended.php 25 2008-01-26 16:00:46Z pedroix $
//

/**
* @package  HTML_BBCodeParser
*/


require_once($Aplic->getClasseBiblioteca('PEAR/HTML/BBCodeParser/Filter'));




class HTML_BBCodeParser_Filter_Extended extends HTML_BBCodeParser_Filter
{

    /**
    * An array of tags parsed by the engine
    *
    * @access   private
    * @var      array
    */
    var $_definedTags = array(
                                'color' => array( 'htmlopen'  => 'span',
                                                'htmlclose' => 'span',
                                                'allowed'   => 'all',
                                                'attributes'=> array('color' =>'style=%2$scolor:%1$s%2$s')),
                                'size' => array( 'htmlopen'  => 'span',
                                                'htmlclose' => 'span',
                                                'allowed'   => 'all',
                                                'attributes'=> array('size' =>'style=%2$sfont-size:%1$spt%2$s')),
                                'font' => array( 'htmlopen'  => 'span',
                                                'htmlclose' => 'span',
                                                'allowed'   => 'all',
                                                'attributes'=> array('font' =>'style=%2$sfont-family:%1$s%2$s')),
                                'align' => array( 'htmlopen'  => 'div',
                                                'htmlclose' => 'div',
                                                'allowed'   => 'all',
                                                'attributes'=> array('align' =>'style=%2$stext-align:%1$s%2$s')),
                                'quote' => array('htmlopen'  => 'q',
                                                'htmlclose' => 'q',
                                                'allowed'   => 'all',
                                                'attributes'=> array('quote' =>'cite=%2$s%1$s%2$s')),
                                'code' => array('htmlopen'  => 'code',
                                                'htmlclose' => 'code',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'h1' => array('htmlopen'  => 'h1',
                                                'htmlclose' => 'h1',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'h2' => array('htmlopen'  => 'h2',
                                                'htmlclose' => 'h2',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'h3' => array('htmlopen'  => 'h3',
                                                'htmlclose' => 'h3',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'h4' => array('htmlopen'  => 'h4',
                                                'htmlclose' => 'h4',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'h5' => array('htmlopen'  => 'h5',
                                                'htmlclose' => 'h5',
                                                'allowed'   => 'all',
                                                'attributes'=> array()),
                                'h6' => array('htmlopen'  => 'h6',
                                                'htmlclose' => 'h6',
                                                'allowed'   => 'all',
                                                'attributes'=> array())

    );


}

?>