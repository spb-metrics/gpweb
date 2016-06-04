<?php
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+

//
// PEAR CVS Id: Span.php,v 1.4 2003/04/30 03:56:26 llucax Exp 
//
// The following lines are modified to correctly load the libraries
// from the Gerenciador distribution
require_once( $Aplic->getClasseBiblioteca( 'PEAR/Date' ) );
require_once( $Aplic->getClasseBiblioteca( 'PEAR/Date/Calc' ) );

/**
* Non Numeric Separated Values (NNSV) Input Format.
*
* Input format guessed from something like this:
* dias<sep>horas<sep>minutos<sep>segundos
* Where <sep> is any quantity of non numeric chars. If no values are
* given, time span is set to zero, if one value is given, it's used for
* horas, if two values are given it's used for horas and minutos and if
* three values are given, it's used for horas, minutos and segundos.<br>
* Examples:<br>
* ''                   -> 0, 0, 0, 0 (dias, horas, minutos, segundos)<br>
* '12'                 -> 0, 12, 0, 0
* '12.30'              -> 0, 12, 30, 0<br>
* '12:30:18'           -> 0, 12, 30, 18<br>
* '3-12-30-18'         -> 3, 12, 30, 18<br>
* '3 dias, 12-30-18'   -> 3, 12, 30, 18<br>
* '12:30 with 18 secs' -> 0, 12, 30, 18<br>
*
* @const int
*/
define('DATE_SPAN_INPUT_FORMAT_NNSV', 1);

/**
* Default time format when converting to a string.
*
* @global string
*/
$_DATE_SPAN_FORMAT  = '%C';

/**
* Default time format when converting from a string.
*
* @global mixed
*/
$_DATE_SPAN_INPUT_FORMAT = DATE_SPAN_INPUT_FORMAT_NNSV;

/**
* Generic time span handling class for PEAR.
*
* @package Date
* @version $Revision: 19 $
* @since   1.4
* @todo    Get and set default local input and output formats?
* @access  public
*/

/* Quick patch for implemtations which do not support is_a*/

if (!function_exists('is_a'))
{
  function is_a($object, $classe_name)
  {
     $classe_name = strtolower($classe_name);
   if (get_class($object) == $classe_name) return TRUE;
     else return is_subclass_of($object, $classe_name);
  }
}

class Data_Intervalo {

    /**
     * @var int
     */
    var $dia;

    /**
     * @var int
     */
    var $hora;

    /**
     * @var int
     */
    var $minuto;

    /**
     * @var int
     */
    var $segundo;

    /**
     * Constructor.
     *
     * Creates the time span object calling the set() method.
     *
     * @param  mixed $time   Time span expression.
     * @param  mixed $format Format string to set it from a string or the
     *                       segundo date set it from a date diff.
     *
     * @see    set()
     * @access public
     */
    function Data_Intervalo($time = 0, $format = null)
    {
        $this->set($time, $format);
    }

    /**
     * Set the time span to a new value in a 'smart' way.
     *
     * Sets the time span depending on the argument types, calling
     * to the appropriate setFromXxx() method.
     *
     * @param  mixed $time   Time span expression.
     * @param  mixed $format Format string to set it from a string or the
     *                       segundo date set it from a date diff.
     *
     * @return bool  true on success.
     *
     * @see    setFromObject()
     * @see    setFromArray()
     * @see    setFromString()
     * @see    setFromSeconds()
     * @see    setFromDateDiff()
     * @access public
     */
    function set($time = 0, $format = null)
    {
        if (is_a($time, 'date_span')) {
            return $this->copy($time);
        } elseif (is_a($time, 'date') and is_a($format, 'date')) {
            return $this->setFromDateDiff($time, $format);
        } elseif (is_array($time)) {
            return $this->setFromArray($time);
        } elseif (is_string($time)) {
            return $this->setFromString($time, $format);
        } elseif (is_int($time)) {
            return $this->setFromSeconds($time);
        } else {
            return $this->setFromSeconds(0);
        }
    }

    /**
     * Set the time span from an array.
     *
     * Set the time span from an array. Any value can be a float (but it
     * has no sense in segundos), for example array(23.5, 20, 0) is
     * interpreted as 23 horas, .5*60 + 20 = 50 minutos and 0 segundos.
     *
     * @param  array $time Items are counted from right to left. First
     *                     item is for segundos, segundo for minutos, third
     *                     for horas and fourth for dias. If there are
     *                     less items than 4, zero (0) is assumed for the
     *                     absent values.
     *
     * @return bool  True on success.
     *
     * @access public
     */
    function setFromArray($time)
    {
        if (!is_array($time)) {
            return false;
        }
        $tmp1 = new Data_Intervalo;
        if (!$tmp1->setFromSeconds(@array_pop($time))) {
            return false;
        }
        $tmp2 = new Data_Intervalo;
        if (!$tmp2->setFromMinutes(@array_pop($time))) {
            return false;
        }
        $tmp1->add($tmp2);
        if (!$tmp2->setFromHours(@array_pop($time))) {
            return false;
        }
        $tmp1->add($tmp2);
        if (!$tmp2->setFromDays(@array_pop($time))) {
            return false;
        }
        $tmp1->add($tmp2);
        return $this->copy($tmp1);
    }

    /**
     * Set the time span from a string based on an input format.
     *
     * Set the time span from a string based on an input format. This is
     * some like a mix of format() method and sscanf() PHP function. The
     * error checking and validation of this function is very primitive,
     * so you should be carefull when using it with unknown $time strings.
     * With this method you are assigning dia, hora, minuto and segundo
     * values, and the last values are used. This means that if you use
     * something like setFromString('10, 20', '%H, %h') your time span
     * would be 20 horas long. Allways remember that this method set
     * <b>all</b> the values, so if you had a $time span 30 minutos long
     * and you make $time->setFromString('20 horas', '%H horas'), $time
     * span would be 20 horas long (and not 20 horas and 30 minutos).
     * Input format options:<br>
     *  <code>%C</code> Days with time, same as "%D, %H:%M:%S".<br>
     *  <code>%d</code> Total dias as a float number
     *                  (2 dias, 12 horas = 2.5 dias).<br>
     *  <code>%D</code> Days as a decimal number.<br>
     *  <code>%e</code> Total horas as a float number
     *                  (1 dia, 2 horas, 30 minutos = 26.5 horas).<br>
     *  <code>%f</code> Total minutos as a float number
     *                  (2 minutos, 30 segundos = 2.5 minutos).<br>
     *  <code>%g</code> Total segundos as a decimal number
     *                  (2 minutos, 30 segundos = 90 segundos).<br>
     *  <code>%h</code> Hours as decimal number.<br>
     *  <code>%H</code> Hours as decimal number limited to 2 digits.<br>
     *  <code>%m</code> Minutes as a decimal number.<br>
     *  <code>%M</code> Minutes as a decimal number limited to 2 digits.<br>
     *  <code>%n</code> Newline character (\n).<br>
     *  <code>%p</code> Either 'am' or 'pm' depending on the time. If 'pm'
     *                  is detected it adds 12 horas to the resulting time
     *                  span (without any checks). This is case
     *                  insensitive.<br>
     *  <code>%r</code> Time in am/pm notation, same as "%H:%M:%S %p".<br>
     *  <code>%R</code> Time in 24-hora notation, same as "%H:%M".<br>
     *  <code>%s</code> Seconds as a decimal number.<br>
     *  <code>%S</code> Seconds as a decimal number limited to 2 digits.<br>
     *  <code>%t</code> Tab character (\t).<br>
     *  <code>%T</code> Current time equivalent, same as "%H:%M:%S".<br>
     *  <code>%%</code> Literal '%'.<br>
     *
     * @param  string $time   String from where to get the time span
     *                        information.
     * @param  string $format Format string.
     *
     * @return bool   True on success.
     *
     * @access public
     */
    function setFromString($time, $format = null)
    {
        if (is_null($format)) {
            $format = $GLOBALS['_DATE_SPAN_INPUT_FORMAT'];
        }
        // If format is a string, it parses the string format.
        if (is_string($format)) {
            $str = '';
            $vars = array();
            $pm = 'am';
            $dia = $hora = $minuto = $segundo = 0;
            for ($i = 0; $i < strlen($format); $i++) {
                $char = $format{$i};
                if ($char == '%') {
                    $prox_caracter = $format{++$i};
                    switch ($prox_caracter) {
                        case 'c':
                            $str .= '%d, %d:%d:%d';
                            array_push(
                                $vars, 'dia', 'hora', 'minuto', 'segundo');
                            break;
                        case 'C':
                            $str .= '%d, %2d:%2d:%2d';
                            array_push(
                                $vars, 'dia', 'hora', 'minuto', 'segundo');
                            break;
                        case 'd':
                            $str .= '%f';
                            array_push($vars, 'dia');
                            break;
                        case 'D':
                            $str .= '%d';
                            array_push($vars, 'dia');
                            break;
                        case 'e':
                            $str .= '%f';
                            array_push($vars, 'hora');
                            break;
                        case 'f':
                            $str .= '%f';
                            array_push($vars, 'minuto');
                            break;
                        case 'g':
                            $str .= '%f';
                            array_push($vars, 'segundo');
                            break;
                        case 'h':
                            $str .= '%d';
                            array_push($vars, 'hora');
                            break;
                        case 'H':
                            $str .= '%2d';
                            array_push($vars, 'hora');
                            break;
                        case 'm':
                            $str .= '%d';
                            array_push($vars, 'minuto');
                            break;
                        case 'M':
                            $str .= '%2d';
                            array_push($vars, 'minuto');
                            break;
                        case 'n':
                            $str .= "\n";
                            break;
                        case 'p':
                            $str .= '%2s';
                            array_push($vars, 'pm');
                            break;
                        case 'r':
                            $str .= '%2d:%2d:%2d %2s';
                            array_push(
                                $vars, 'hora', 'minuto', 'segundo', 'pm');
                            break;
                        case 'R':
                            $str .= '%2d:%2d';
                            array_push($vars, 'hora', 'minuto');
                            break;
                        case 's':
                            $str .= '%d';
                            array_push($vars, 'segundo');
                            break;
                        case 'S':
                            $str .= '%2d';
                            array_push($vars, 'segundo');
                            break;
                        case 't':
                            $str .= "\t";
                            break;
                        case 'T':
                            $str .= '%2d:%2d:%2d';
                            array_push($vars, 'hora', 'minuto', 'segundo');
                            break;
                        case '%':
                            $str .= "%";
                            break;
                        default:
                            $str .= $char . $prox_caracter;
                    }
                } else {
                    $str .= $char;
                }
            }
            $vals = sscanf($time, $str);
            foreach ($vals as $i => $val) {
                if (is_null($val)) {
                    return false;
                }
                $$vars[$i] = $val;
            }
            if (strcasecmp($pm, 'pm') == 0) {
                $hora += 12;
            } elseif (strcasecmp($pm, 'am') != 0) {
                return false;
            }
            $this->setFromArray(array($dia, $hora, $minuto, $segundo));
        // If format is a integer, it uses a predefined format
        // detection method.
        } elseif (is_integer($format)) {
            switch ($format) {
                case DATE_SPAN_INPUT_FORMAT_NNSV:
                    $time = preg_split('/\D+/', $time);
                    switch (count($time)) {
                        case 0:
                            return $this->setFromArray(
                                array(0, 0, 0, 0));
                        case 1:
                            return $this->setFromArray(
                                array(0, $time[0], 0, 0));
                        case 2:
                            return $this->setFromArray(
                                array(0, $time[0], $time[1], 0));
                        case 3:
                            return $this->setFromArray(
                                array(0, $time[0], $time[1], $time[2]));
                        default:
                            return $this->setFromArray($time);
                    }
                    break;
            }
        }
        return false;
    }

    /**
     * Set the time span from a total number of segundos.
     *
     * @param  int  $segundos Total number of segundos.
     *
     * @return bool True on success.
     *
     * @access public
     */
    function setFromSeconds($segundos)
    {
        if ($segundos < 0) {
            return false;
        }
        $sec  = intval($segundos);
        $min  = floor($sec / 60);
        $hora = floor($min / 60);
        $dia  = intval(floor($hora / 24));
        $this->segundo = $sec % 60;
        $this->minuto = $min % 60;
        $this->hora   = $hora % 24;
        $this->dia    = $dia;
        return true;
    }

    /**
     * Set the time span from a total number of minutos.
     *
     * @param  float $minutos Total number of minutos.
     *
     * @return bool  True on success.
     *
     * @access public
     */
    function setFromMinutes($minutos)
    {
        return $this->setFromSeconds(round($minutos * 60));
    }

    /**
     * Set the time span from a total number of horas.
     *
     * @param  float $horas Total number of horas.
     *
     * @return bool  True on success.
     *
     * @access public
     */
    function setFromHours($horas)
    {
        return $this->setFromSeconds(round($horas * 3600));
    }

    /**
     * Set the time span from a total number of dias.
     *
     * @param  float $dias Total number of dias.
     *
     * @return bool  True on success.
     *
     * @access public
     */
    function setFromDays($dias)
    {
        return $this->setFromSeconds(round($dias * 86400));
    }

    /**
     * Set the span from the elapsed time between two dates.
     *
     * Set the span from the elapsed time between two dates. The time span
     * is allways positive, so the date's order is not important.
     * 
     * @param  object Date $data1 First Date.
     * @param  object Date $data2 Second Date.
     *
     * @return bool  True on success.
     *
     * @access public
     */
    function setFromDateDiff($data1, $data2)
    {
        if (!is_a($data1, 'date') or !is_a($data2, 'date')) {
            return false;
        }
        $data1->toUTC();
        $data2->toUTC();
        if ($data1->after($data2)) {
            list($data1, $data2) = array($data2, $data1);
        }
        $dias = Data_Calc::dateDiff(
            $data1->getDay(), $data1->getMonth(), $data1->getYear(),
            $data2->getDay(), $data2->getMonth(), $data2->getYear()
        );
        $horas = $data2->getHour() - $data1->getHour();
        $mins  = $data2->getMinute() - $data1->getMinute();
        $secs  = $data2->getSecond() - $data1->getSecond();
        $this->setFromSeconds(
            $dias * 86400 + $horas * 3600 + $mins * 60 + $secs
        );
        return true;
    }

    /**
     * Set the time span from another time object.
     *
     * @param  object Data_Intervalo $time Source time span object.
     *
     * @return bool   True on success.
     *
     * @access public
     */
    function copy($time)
    {
        if (is_a($time, 'date_span')) {
            $this->segundo = $time->segundo;
            $this->minuto = $time->minuto;
            $this->hora   = $time->hora;
            $this->dia    = $time->dia;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Time span pretty printing (similar to Date::format()).
     *
     * Formats the time span in the given format, similar to
     * strftime() and Date::format().<br>
     * <br>
     * Formatting options:<br>
     *  <code>%C</code> Days with time, same as "%D, %H:%M:%S".<br>
     *  <code>%d</code> Total dias as a float number
     *                  (2 dias, 12 horas = 2.5 dias).<br>
     *  <code>%D</code> Days as a decimal number.<br>
     *  <code>%e</code> Total horas as a float number
     *                  (1 dia, 2 horas, 30 minutos = 26.5 horas).<br>
     *  <code>%E</code> Total horas as a decimal number
     *                  (1 dia, 2 horas, 40 minutos = 26 horas).<br>
     *  <code>%f</code> Total minutos as a float number
     *                  (2 minutos, 30 segundos = 2.5 minutos).<br>
     *  <code>%F</code> Total minutos as a decimal number
     *                  (1 hora, 2 minutos, 40 segundos = 62 minutos).<br>
     *  <code>%g</code> Total segundos as a decimal number
     *                  (2 minutos, 30 segundos = 90 segundos).<br>
     *  <code>%h</code> Hours as decimal number (0 to 23).<br>
     *  <code>%H</code> Hours as decimal number (00 to 23).<br>
     *  <code>%i</code> Hours as decimal number on 12-hora clock
     *                  (1 to 12).<br>
     *  <code>%I</code> Hours as decimal number on 12-hora clock
     *                  (01 to 12).<br>
     *  <code>%m</code> Minutes as a decimal number (0 to 59).<br>
     *  <code>%M</code> Minutes as a decimal number (00 to 59).<br>
     *  <code>%n</code> Newline character (\n).<br>
     *  <code>%p</code> Either 'am' or 'pm' depending on the time.<br>
     *  <code>%P</code> Either 'AM' or 'PM' depending on the time.<br>
     *  <code>%r</code> Time in am/pm notation, same as "%I:%M:%S %p".<br>
     *  <code>%R</code> Time in 24-hora notation, same as "%H:%M".<br>
     *  <code>%s</code> Seconds as a decimal number (0 to 59).<br>
     *  <code>%S</code> Seconds as a decimal number (00 to 59).<br>
     *  <code>%t</code> Tab character (\t).<br>
     *  <code>%T</code> Current time equivalent, same as "%H:%M:%S".<br>
     *  <code>%%</code> Literal '%'.<br>
     *
     * @param  string $format The format string for returned time span.
     *
     * @return string The time span in specified format.
     *
     * @access public
     */
    function format($format = null)
    {
        if (is_null($format)) {
            $format = $GLOBALS['_DATE_SPAN_FORMAT'];
        }
        $saida = '';
        for ($i = 0; $i < strlen($format); $i++) {
            $char = $format{$i};
            if ($char == '%') {
                $prox_caracter = $format{++$i};
                switch ($prox_caracter) {
                    case 'C':
                        $saida .= sprintf(
                            '%d, %02d:%02d:%02d',
                            $this->dia,
                            $this->hora,
                            $this->minuto,
                            $this->segundo
                        );
                        break;
                    case 'd':
                        $saida .= $this->toDays();
                        break;
                    case 'D':
                        $saida .= $this->dia;
                        break;
                    case 'e':
                        $saida .= $this->toHours();
                        break;
                    case 'E':
                        $saida .= floor($this->toHours());
                        break;
                    case 'f':
                        $saida .= $this->toMinutes();
                        break;
                    case 'F':
                        $saida .= floor($this->toMinutes());
                        break;
                    case 'g':
                        $saida .= $this->toSeconds();
                        break;
                    case 'h':
                        $saida .= $this->hora;
                        break;
                    case 'H':
                        $saida .= sprintf('%02d', $this->hora);
                        break;
                    case 'i':
                        $hora =
                            ($this->hora + 1) > 12 ?
                            $this->hora - 12 :
                            $this->hora;
                        $saida .= ($hora == 0) ? 12 : $hora;
                        break;
                    case 'I':
                        $hora =
                            ($this->hora + 1) > 12 ?
                            $this->hora - 12 :
                            $this->hora;
                        $saida .= sprintf('%02d', $hora==0 ? 12 : $hora);
                        break;
                    case 'm':
                        $saida .= $this->minuto;
                        break;
                    case 'M':
                        $saida .= sprintf('%02d',$this->minuto);
                        break;
                    case 'n':
                        $saida .= "\n";
                        break;
                    case 'p':
                        $saida .= $this->hora >= 12 ? 'pm' : 'am';
                        break;
                    case 'P':
                        $saida .= $this->hora >= 12 ? 'PM' : 'AM';
                        break;
                    case 'r':
                        $hora =
                            ($this->hora + 1) > 12 ?
                            $this->hora - 12 :
                            $this->hora;
                        $saida .= sprintf(
                            '%02d:%02d:%02d %s',
                            $hora==0 ?  12 : $hora,
                            $this->minuto,
                            $this->segundo,
                            $this->hora >= 12 ? 'pm' : 'am'
                        );
                        break;
                    case 'R':
                        $saida .= sprintf(
                            '%02d:%02d', $this->hora, $this->minuto
                        );
                        break;
                    case 's':
                        $saida .= $this->segundo;
                        break;
                    case 'S':
                        $saida .= sprintf('%02d', $this->segundo);
                        break;
                    case 't':
                        $saida .= "\t";
                        break;
                    case 'T':
                        $saida .= sprintf(
                            '%02d:%02d:%02d',
                            $this->hora, $this->minuto, $this->segundo
                        );
                        break;
                    case '%':
                        $saida .= "%";
                        break;
                    default:
                        $saida .= $char . $prox_caracter;
                }
            } else {
                $saida .= $char;
            }
        }
        return $saida;
    }

    /**
     * Convert time span to segundos.
     *
     * @return int Time span as an integer number of segundos.
     *
     * @access public
     */
    function toSeconds()
    {
        return $this->dia * 86400 + $this->hora * 3600 +
            $this->minuto * 60 + $this->segundo;
    }

    /**
     * Convert time span to minutos.
     *
     * @return float Time span as a decimal number of minutos.
     *
     * @access public
     */
    function toMinutes()
    {
        return $this->dia * 1440 + $this->hora * 60 + $this->minuto +
            $this->segundo / 60;
    }

    /**
     * Convert time span to horas.
     *
     * @return float Time span as a decimal number of horas.
     *
     * @access public
     */
    function toHours()
    {
        return $this->dia * 24 + $this->hora + $this->minuto / 60 +
            $this->segundo / 3600;
    }

    /**
     * Convert time span to dias.
     *
     * @return float Time span as a decimal number of dias.
     *
     * @access public
     */
    function toDays()
    {
        return $this->dia + $this->hora / 24 + $this->minuto / 1440 +
            $this->segundo / 86400;
    }

    /**
     * Adds a time span.
     *
     * @param  object Data_Intervalo $time Time span to add.
     *
     * @access public
     */
    function add($time)
    {
        return $this->setFromSeconds(
            $this->toSeconds() + $time->toSeconds()
        );
    }

    /**
     * Subtracts a time span.
     *
     * Subtracts a time span. If the time span to subtract is larger
     * than the original, the result is zero (there's no sense in
     * negative time spans).
     *
     * @param  object Data_Intervalo $time Time span to subtract.
     *
     * @access public
     */
    function subtract($time)
    {
        $sub = $this->toSeconds() - $time->toSeconds();
        if ($sub < 0) {
            $this->setFromSeconds(0);
        } else {
            $this->setFromSeconds($sub);
        }
    }

    /**
     * Tells if time span is equal to $time.
     *
     * @param  object Data_Intervalo $time Time span to compare to.
     *
     * @return bool   True if the time spans are equal.
     *
     * @access public
     */
    function equal($time)
    {
        return $this->toSeconds() == $time->toSeconds();
    }

    /**
     * Tells if this time span is greater or equal than $time.
     *
     * @param  object Data_Intervalo $time Time span to compare to.
     *
     * @return bool   True if this time span is greater or equal than $time.
     *
     * @access public
     */
    function greaterEqual($time)
    {
        return $this->toSeconds() >= $time->toSeconds();
    }

    /**
     * Tells if this time span is lower or equal than $time.
     *
     * @param  object Data_Intervalo $time Time span to compare to.
     *
     * @return bool   True if this time span is lower or equal than $time.
     *
     * @access public
     */
    function lowerEqual($time)
    {
        return $this->toSeconds() <= $time->toSeconds();
    }

    /**
     * Tells if this time span is greater than $time.
     *
     * @param  object Data_Intervalo $time Time span to compare to.
     *
     * @return bool   True if this time span is greater than $time.
     *
     * @access public
     */
    function greater($time)
    {
        return $this->toSeconds() > $time->toSeconds();
    }

    /**
     * Tells if this time span is lower than $time.
     *
     * @param  object Data_Intervalo $time Time span to compare to.
     *
     * @return bool   True if this time span is lower than $time.
     *
     * @access public
     */
    function lower($time)
    {
        return $this->toSeconds() < $time->toSeconds();
    }

    /**
     * Compares two time spans.
     *
     * Compares two time spans. Suitable for use in sorting functions.
     *
     * @param  object Data_Intervalo $time1 The first time span.
     * @param  object Data_Intervalo $time2 The segundo time span.
     *
     * @return int    0 if the time spans are equal, -1 if time1 is lower
     *                than time2, 1 if time1 is greater than time2.
     *
     * @static
     * @access public
     */
    function compare($time1, $time2)
    {
        if ($time1->equal($time2)) {
            return 0;
        } elseif ($time1->lower($time2)) {
            return -1;
        } else {
            return 1;
        }
    }

    /**
     * Tells if the time span is empty (zero length).
     *
     * @return bool True is it's empty.
     */
    function isEmpty()
    {
        return !$this->dia && !$this->hora && !$this->minuto && !$this->segundo;
    }

    /**
     * Set the default input format.
     *
     * @param  mixed $format New default input format.
     *
     * @return mixed Previous default input format.
     *
     * @static
     */
    function setDefaultInputFormat($format)
    {
        $old = $GLOBALS['_DATE_SPAN_INPUT_FORMAT'];
        $GLOBALS['_DATE_SPAN_INPUT_FORMAT'] = $format;
        return $old;
    }

    /**
     * Get the default input format.
     *
     * @return mixed Default input format.
     *
     * @static
     */
    function getDefaultInputFormat()
    {
        return $GLOBALS['_DATE_SPAN_INPUT_FORMAT'];
    }

    /**
     * Set the default format.
     *
     * @param  mixed $format New default format.
     *
     * @return mixed Previous default format.
     *
     * @static
     */
    function setDefaultFormat($format)
    {
        $old = $GLOBALS['_DATE_SPAN_FORMAT'];
        $GLOBALS['_DATE_SPAN_FORMAT'] = $format;
        return $old;
    }

    /**
     * Get the default format.
     *
     * @return mixed Default format.
     *
     * @static
     */
    function getDefaultFormat()
    {
        return $GLOBALS['_DATE_SPAN_FORMAT'];
    }

    /**
     * Returns a copy of the object (workarround for PHP5 forward compatibility).
     *
     * @return object Data_Intervalo Copy of the object.
     */
    function __clone() {
        $c = get_class($this);
        $s = new $c;
        $s->dia    = $this->dia;
        $s->hora   = $this->hora;
        $s->minuto = $this->minuto;
        $s->segundo = $this->segundo;
        return $s;
    }
}

?>
