<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
//
// PEAR CVS Id: Date.php,v 1.12 2003/04/27 03:42:17 llucax Exp
//
// Date Class
//

// The following lines are modified to correctly load the libraries
require_once( $Aplic->getClasseBiblioteca( 'PEAR/Date/TimeZone' ) );
require_once( $Aplic->getClasseBiblioteca( 'PEAR/Date/Calc' ) );
require_once( $Aplic->getClasseBiblioteca( 'PEAR/Date/Span' ) );

/**
* "YYYY-MM-DD HH:MM:SS"
*/
define('DATE_FORMAT_ISO', 1);
/**
* "YYYYMMDDHHMMSS"
*/
define('DATE_FORMAT_TIMESTAMP', 2);
/**
* long int, segundos since the unix epoch
*/
define('DATE_FORMAT_UNIXTIME', 3);

/**
* Generic date handling class for PEAR.
*
* Generic date handling class for PEAR.  Attempts to be time zone aware
* through the Date::TimeZone class.  Supports several operations from
* Date::Calc on Date objects.
*
* @package Date
* @access public
* @version 1.1
*/
class Date
{
    /**
     * the ano
     * @var int
     */
    var $ano;
    /**
     * the mes
     * @var int
     */
    var $mes;
    /**
     * the dia
     * @var int
     */
    var $dia;
    /**
     * the hora
     * @var int
     */
    var $hora;
    /**
     * the minuto
     * @var int
     */
    var $minuto;
    /**
     * the segundo
     * @var int
     */
    var $segundo;
    /**
     * timezone for this date
     * @var object Date_TimeZone
     */
    var $tz;


    /**
     * Constructor
     *
     * Creates a new Date Object
     * initialized to the current date/time in the
     * system default time zone by default.  A date optionally
     * passed in may be in the ISO, TIMESTAMP or UNIXTIME format,
     * or another Date object.
     *
     * @access public
     * @param mixed $data optional - date/time to initialize
     * @return object Date the new Date object
     */
    function Date($data = null)
    {
        $this->tz = Date_TimeZone::getDefault();
        if (is_null($data)) {
            $this->setData(date('Y-m-d H:i:s'));
// following line has been modified by Andrew Eddie to support extending the Date class
        //} elseif (is_object($data) && (get_class($data) == 'date')) {
        } elseif (is_object($data) && (get_class($data) == get_class($this))) {
            $this->copy($data);
        } elseif (preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $data)) {
            $this->setData($data);
        } elseif (preg_match('/\d{14}/',$data)) {
            $this->setData($data,DATE_FORMAT_TIMESTAMP);
        } elseif (preg_match('/\d{4}-\d{2}-\d{2}/', $data)) {
            $this->setData($data.' 00:00:00');
        } elseif (preg_match('/\d{8}/',$data)) {
            $this->setData($data.'000000',DATE_FORMAT_TIMESTAMP);
        } else {
            $this->setData($data,DATE_FORMAT_UNIXTIME);
        }
    }

    /**
     * Set the fields of a Date object based on the input date and format
     *
     * Set the fields of a Date object based on the input date and format,
     * which is specified by the DATE_FORMAT_* constants.
     *
     * @access public
     * @param string $data input date
     * @param int $format format constant (DATE_FORMAT_*) of the input date
     */
    function setData($data, $format = DATE_FORMAT_ISO)
    {
        switch($format) {
            case DATE_FORMAT_ISO:
                if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})/i",$data,$regs)) {
                    $this->ano   = $regs[1];
                    $this->mes  = $regs[2];
                    $this->dia    = $regs[3];
                    $this->hora   = $regs[4];
                    $this->minuto = $regs[5];
                    $this->segundo = $regs[6];
                } else {
                    $this->ano   = 0;
                    $this->mes  = 1;
                    $this->dia    = 1;
                    $this->hora   = 0;
                    $this->minuto = 0;
                    $this->segundo = 0;
                }
                break;
            case DATE_FORMAT_TIMESTAMP:
                if (preg_match("/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/i",$data,$regs)) {
                    $this->ano   = $regs[1];
                    $this->mes  = $regs[2];
                    $this->dia    = $regs[3];
                    $this->hora   = $regs[4];
                    $this->minuto = $regs[5];
                    $this->segundo = $regs[6];
                } else {
                    $this->ano   = 0;
                    $this->mes  = 1;
                    $this->dia    = 1;
                    $this->hora   = 0;
                    $this->minuto = 0;
                    $this->segundo = 0;
                }
                break;
            case DATE_FORMAT_UNIXTIME:
                $this->setData(date("Y-m-d H:i:s", intval($data)));
                break;
        }
    }

    /**
     * Get a string (or other) representation of this date
     *
     * Get a string (or other) representation of this date in the
     * format specified by the DATE_FORMAT_* constants.
     *
     * @access public
     * @param int $format format constant (DATE_FORMAT_*) of the output date
     * @return string the date in the requested format
     */
    function getData($format = DATE_FORMAT_ISO)
    {
        switch($format) {
            case DATE_FORMAT_ISO:
                return $this->format("%Y-%m-%d %T");
                break;
            case DATE_FORMAT_TIMESTAMP:
                return $this->format("%Y%m%d%H%M%S");
                break;
            case DATE_FORMAT_UNIXTIME:
                return mktime($this->hora, $this->minuto, $this->segundo, $this->mes, $this->dia, $this->ano);
                break;
        }
    }

    /**
     * Copy values from another Date object
     *
     * Makes this Date a copy of another Date object.
     *
     * @access public
     * @param object Date $data Date to copy from
     */
    function copy($data)
    {
        $this->ano = $data->ano;
        $this->mes = $data->mes;
        $this->dia = $data->dia;
        $this->hora = $data->hora;
        $this->minuto = $data->minuto;
        $this->segundo = $data->segundo;
        $this->tz = $data->tz;
    }

    /**
     *  Date pretty printing, similar to strftime()
     *
     *  Formats the date in the given format, much like
     *  strftime().  Most strftime() options are supported.<br><br>
     *
     *  formatting options:<br><br>
     *
     *  <code>%a  </code>  abbreviated weekdia name (Sun, Mon, Tue) <br>
     *  <code>%A  </code>  full weekdia name (Sunday, Monday, Tuesday) <br>
     *  <code>%b  </code>  abbreviated mes name (Jan, Feb, Mar) <br>
     *  <code>%B  </code>  full mes name (January, February, March) <br>
     *  <code>%C  </code>  century number (the ano divided by 100 and truncated to an integer, range 00 to 99) <br>
     *  <code>%d  </code>  dia of mes (range 00 to 31) <br>
     *  <code>%D  </code>  same as "%m/%d/%y" <br>
     *  <code>%e  </code>  dia of mes, single digit (range 0 to 31) <br>
     *  <code>%E  </code>  number of dias since unspecified epoch (integer, Data_Calc::dataParaDias()) <br>
     *  <code>%H  </code>  hora as decimal number (00 to 23) <br>
     *  <code>%I  </code>  hora as decimal number on 12-hora clock (01 to 12) <br>
     *  <code>%j  </code>  dia of ano (range 001 to 366) <br>
     *  <code>%m  </code>  mes as decimal number (range 01 to 12) <br>
     *  <code>%M  </code>  minuto as a decimal number (00 to 59) <br>
     *  <code>%n  </code>  newline character (\n) <br>
     *  <code>%O  </code>  dst-corrected timezone offset expressed as "+/-HH:MM" <br>
     *  <code>%o  </code>  raw timezone offset expressed as "+/-HH:MM" <br>
     *  <code>%p  </code>  either 'am' or 'pm' depending on the time <br>
     *  <code>%P  </code>  either 'AM' or 'PM' depending on the time <br>
     *  <code>%r  </code>  time in am/pm notation, same as "%I:%M:%S %p" <br>
     *  <code>%R  </code>  time in 24-hora notation, same as "%H:%M" <br>
     *  <code>%S  </code>  segundos as a decimal number (00 to 59) <br>
     *  <code>%t  </code>  tab character (\t) <br>
     *  <code>%T  </code>  current time, same as "%H:%M:%S" <br>
     *  <code>%w  </code>  weekdia as decimal (0 = Sunday) <br>
     *  <code>%U  </code>  week number of current ano, first Sunday as first week <br>
     *  <code>%y  </code>  ano as decimal (range 00 to 99) <br>
     *  <code>%Y  </code>  ano as decimal including century (range 0000 to 9999) <br>
     *  <code>%%  </code>  literal '%' <br>
     * <br>
     *
     * @access public
     * @param string format the format string for returned date/time
     * @return string date/time in given format
     */
    function format($format){
    	global $Aplic;
        $saida = "";

        for($strpos = 0; $strpos < strlen($format); $strpos++) {
            $char = substr($format,$strpos,1);
            if($char == "%") {
                $prox_caracter = substr($format,$strpos + 1,1);
                switch($prox_caracter) {
                    case "a":
                        $saida .= Data_Calc::getSemanaNomeAbrev($this->dia,$this->mes,$this->ano);
                        break;
                    case "A":
                        $saida .= Data_Calc::getSemanaNomeCompl($this->dia,$this->mes,$this->ano);
                        break;
                    case "b":
						setlocale(LC_TIME, $Aplic->usuario_linguagem);
                        $saida .= Data_Calc::getMesNomeAbrev($this->mes);
						setlocale(LC_ALL, $Aplic->usuario_linguagem);
                        break;
                    case "B":
                        $saida .= Data_Calc::getMesNomeCompl($this->mes);
                        break;
                    case "C":
                        $saida .= sprintf("%02d",intval($this->ano/100));
                        break;
                    case "d":
                        $saida .= sprintf("%02d",$this->dia);
                        break;
                    case "D":
                        $saida .= sprintf("%02d/%02d/%02d",$this->mes,$this->dia,$this->ano);
                        break;
                    case "e":
                        $saida .= $this->dia;
                        break;
                    case "E":
                        $saida .= Data_Calc::dataParaDias($this->dia,$this->mes,$this->ano);
                        break;
                    case "H":
                        $saida .= sprintf("%02d", $this->hora);
                        break;
                    case "I":
                        $hora = ($this->hora + 1) > 12 ? $this->hora - 12 : $this->hora;
                        $saida .= sprintf("%02d", $hora==0 ? 12 : $hora);
                        break;
                    case "j":
                        $saida .= Data_Calc::dataJuliana($this->dia,$this->mes,$this->ano);
                        break;
                    case "m":
                        $saida .= sprintf("%02d",$this->mes);
                        break;
                    case "M":
                        $saida .= sprintf("%02d",$this->minuto);
                        break;
                    case "n":
                        $saida .= "\n";
                        break;
                    case "O":
                        $offms = $this->tz->getOffset($this);
                        $direcao = $offms >= 0 ? "+" : "-";
                        $offmins = abs($offms) / 1000 / 60;
                        $horas = $offmins / 60;
                        $minutos = $offmins % 60;
                        $saida .= sprintf("%s%02d:%02d", $direcao, $horas, $minutos);
                        break;
                    case "o":
                        $offms = $this->tz->getRawOffset($this);
                        $direcao = $offms >= 0 ? "+" : "-";
                        $offmins = abs($offms) / 1000 / 60;
                        $horas = $offmins / 60;
                        $minutos = $offmins % 60;
                        $saida .= sprintf("%s%02d:%02d", $direcao, $horas, $minutos);
                        break;
                    case "p":
                        $saida .= $this->hora >= 12 ? "pm" : "am";
                        break;
                    case "P":
                        $saida .= $this->hora >= 12 ? "PM" : "AM";
                        break;
                    case "r":
                        $hora = ($this->hora + 1) > 12 ? $this->hora - 12 : $this->hora;
                        $saida .= sprintf("%02d:%02d:%02d %s", $hora==0 ?  12 : $hora, $this->minuto, $this->segundo, $this->hora >= 12 ? "PM" : "AM");
                        break;
                    case "R":
                        $saida .= sprintf("%02d:%02d", $this->hora, $this->minuto);
                        break;
                    case "S":
                        $saida .= sprintf("%02d", $this->segundo);
                        break;
                    case "t":
                        $saida .= "\t";
                        break;
                    case "T":
                        $saida .= sprintf("%02d:%02d:%02d", $this->hora, $this->minuto, $this->segundo);
                        break;
                    case "w":
                        $saida .= Data_Calc::diaDaSemana($this->dia,$this->mes,$this->ano);
                        break;
                    case "U":
                        $saida .= Data_Calc::semanaDoAno($this->dia,$this->mes,$this->ano);
                        break;
                    case "y":
                        $saida .= substr($this->ano,2,2);
                        break;
                    case "Y":
                        $saida .= $this->ano;
                        break;
                    case "Z":
                        $saida .= $this->tz->inDaylightTime($this) ? $this->tz->getDSTNomeCurto() : $this->tz->getNomeCurto();
                        break;
                    case "%":
                        $saida .= "%";
                        break;
                    default:
                        $saida .= $char.$prox_caracter;
                }
                $strpos++;
            } else {
                $saida .= $char;
            }
        }
        return $saida;

    }

    /**
     * Get this date/time in Unix time() format
     *
     * Get a representation of this date in Unix time() format.  This may only be
     * valid for dates from 1970 to ~2038.
     *
     * @access public
     * @return int number of segundos since the unix epoch
     */
    function getTempo()
    {
        return $this->getData(DATE_FORMAT_UNIXTIME);
    }

    /**
     * Sets the time zone of this Date
     *
     * Sets the time zone of this date with the given
     * Date_TimeZone object.  Does not alter the date/time,
     * only assigns a new time zone.  For conversion, use
     * convertTZ().
     *
     * @access public
     * @param object Date_TimeZone $tz the Date_TimeZone object to use
     */
    function setTZ($tz)
    {
        $this->tz = $tz;
    }

    /**
     * Sets the time zone of this date with the given time zone id
     *
     * Sets the time zone of this date with the given
     * time zone id, or to the system default if the
     * given id is invalid. Does not alter the date/time,
     * only assigns a new time zone.  For conversion, use
     * convertTZ().
     *
     * @access public
     * @param string id a time zone id
     */
    function setTZbyID($id)
    {
        if(Date_TimeZone::ehIdValido($id)) {
            $this->tz = new Date_TimeZone($id);
        } else {
            $this->tz = Date_TimeZone::getDefault();
        }
    }

    /**
     * Tests if this date/time is in DST
     *
     * Returns true if dialight savings time is in effect for
     * this date in this date's time zone.  See Date_TimeZone::inDaylightTime()
     * for compatability information.
     *
     * @access public
     * @return boolean true if DST is in effect for this date
     */
    function inDaylightTime()
    {
        return $this->tz->inDaylightTime($this);
    }

    /**
     * Converts this date to UTC and sets this date's timezone to UTC
     *
     * Converts this date to UTC and sets this date's timezone to UTC
     *
     * @access public
     */
    function toUTC()
    {
        if($this->tz->getOffset($this) > 0) {
            $this->subtrairSegundos(intval($this->tz->getOffset($this) / 1000));
        } else {
            $this->adSegundos(intval(abs($this->tz->getOffset($this)) / 1000));
        }
        $this->tz = new Date_TimeZone('UTC');
    }

    /**
     * Converts this date to a new time zone
     *
     * Converts this date to a new time zone.
     * WARNING: This may not work correctly if your system does not allow
     * putenv() or if localtime() does not work in your environment.  See
     * Date::TimeZone::inDaylightTime() for more information.
     *
     * @access public
     * @param object Date_TimeZone $tz the Date::TimeZone object for the conversion time zone
     */
    function convertTZ($tz)
    {
        // convert to UTC
        if($this->tz->getOffset($this) > 0) {
            $this->subtrairSegundos(intval(abs($this->tz->getOffset($this)) / 1000));
        } else {
            $this->adSegundos(intval(abs($this->tz->getOffset($this)) / 1000));
        }
        // convert UTC to new timezone
        if($tz->getOffset($this) > 0) {
            $this->adSegundos(intval(abs($tz->getOffset($this)) / 1000));
        } else {
            $this->subtrairSegundos(intval(abs($tz->getOffset($this)) / 1000));
        }
        $this->tz = $tz;
    }

    /**
     * Converts this date to a new time zone, given a valid time zone ID
     *
     * Converts this date to a new time zone, given a valid time zone ID
     * WARNING: This may not work correctly if your system does not allow
     * putenv() or if localtime() does not work in your environment.  See
     * Date::TimeZone::inDaylightTime() for more information.
     *
     * @access public
     * @param string id a time zone id
     */
    function convertTZbyID($id)
    {
       if(Date_TimeZone::ehIdValido($id)) {
          $tz = new Date_TimeZone($id);
       } else {
          $tz = Date_TimeZone::getDefault();
       }
       $this->convertTZ($tz);
    }

    /**
     * Adds a given number of segundos to the date
     *
     * Adds a given number of segundos to the date
     *
     * @access public
     * @param int $sec the number of segundos to add
     */
    function adSegundos($sec)
    {
        $this->adIntervalo(new Data_Intervalo($sec));
    }

    /**
     * Adds a time span to the date
     *
     * Adds a time span to the date
     *
     * @access public
     * @param object Data_Intervalo $intervalo the time span to add
     */
    function adIntervalo($intervalo)
    {
        $this->segundo += $intervalo->segundo;
        if($this->segundo >= 60) {
            $this->minuto++;
            $this->segundo -= 60;
        }

        $this->minuto += $intervalo->minuto;
        if($this->minuto >= 60) {
            $this->hora++;
            if($this->hora >= 24) {
                list($this->ano, $this->mes, $this->dia) =
                    sscanf(Data_Calc::proxDia($this->dia, $this->mes, $this->ano), "%04s%02s%02s");
                $this->hora -= 24;
            }
            $this->minuto -= 60;
        }

        $this->hora += $intervalo->hora;
        if($this->hora >= 24) {
            list($this->ano, $this->mes, $this->dia) =
                sscanf(Data_Calc::proxDia($this->dia, $this->mes, $this->ano), "%04s%02s%02s");
            $this->hora -= 24;
        }

        $d = Data_Calc::dataParaDias($this->dia, $this->mes, $this->ano);
        $d += $intervalo->dia;

        list($this->ano, $this->mes, $this->dia) =
            sscanf(Data_Calc::diasParaData($d), "%04s%02s%02s");
        $this->ano  = intval($this->ano);
        $this->mes = intval($this->mes);
        $this->dia   = intval($this->dia);
    }

    /**
     * Subtracts a given number of segundos from the date
     *
     * Subtracts a given number of segundos from the date
     *
     * @access public
     * @param int $sec the number of segundos to subtract
     */
    function subtrairSegundos($sec)
    {
        $this->subtrairIntervalo(new Data_Intervalo($sec));
    }

    /**
     * Subtracts a time span to the date
     *
     * Subtracts a time span to the date
     *
     * @access public
     * @param object Data_Intervalo $intervalo the time span to subtract
     */
    function subtrairIntervalo($intervalo)
    {
        $this->segundo -= $intervalo->segundo;
        if($this->segundo < 0) {
            $this->minuto--;
            $this->segundo += 60;
        }

        $this->minuto -= $intervalo->minuto;
        if($this->minuto < 0) {
            $this->hora--;
            if($this->hora < 0) {
                list($this->ano, $this->mes, $this->dia) =
                    sscanf(Data_Calc::diaAnterior($this->dia, $this->mes, $this->ano), "%04s%02s%02s");
                $this->hora += 24;
            }
            $this->minuto += 60;
        }

        $this->hora -= $intervalo->hora;
        if($this->hora < 0) {
            list($this->ano, $this->mes, $this->dia) =
                sscanf(Data_Calc::diaAnterior($this->dia, $this->mes, $this->ano), "%04s%02s%02s");
            $this->hora += 24;
        }

        $d = Data_Calc::dataParaDias($this->dia, $this->mes, $this->ano);
        $d -= $intervalo->dia;

        list($this->ano, $this->mes, $this->dia) =
            sscanf(Data_Calc::diasParaData($d), "%04s%02s%02s");
        $this->ano  = intval($this->ano);
        $this->mes = intval($this->mes);
        $this->dia   = intval($this->dia);
    }

    /**
     * Compares two dates
     *
     * Compares two dates.  Suitable for use
     * in sorting functions.
     *
     * @access public
     * @param object Date $d1 the first date
     * @param object Date $d2 the segundo date
     * @return int 0 if the dates are equal, -1 if d1 is before d2, 1 if d1 is after d2
     */
    function compare($d1, $d2)
    {
        $d1->convertTZ(new Date_TimeZone('UTC'));
        $d2->convertTZ(new Date_TimeZone('UTC'));
        $dias1 = Data_Calc::dataParaDias($d1->dia, $d1->mes, $d1->ano);
        $dias2 = Data_Calc::dataParaDias($d2->dia, $d2->mes, $d2->ano);
        if($dias1 < $dias2) return -1;
        if($dias1 > $dias2) return 1;
        if($d1->hora < $d2->hora) return -1;
        if($d1->hora > $d2->hora) return 1;
        if($d1->minuto < $d2->minuto) return -1;
        if($d1->minuto > $d2->minuto) return 1;
        if($d1->segundo < $d2->segundo) return -1;
        if($d1->segundo > $d2->segundo) return 1;
        return 0;
    }

    /**
     * Test if this date/time is before a certian date/time
     *
     * Test if this date/time is before a certian date/time
     *
     * @access public
     * @param object Date $when the date to test against
     * @return boolean true if this date is before $when
     */
    function before($when)
    {
        if($this->compare($this,$when) == -1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Test if this date/time is after a certian date/time
     *
     * Test if this date/time is after a certian date/time
     *
     * @access public
     * @param object Date $when the date to test against
     * @return boolean true if this date is after $when
     */
    function after($when)
    {
        if($this->compare($this,$when) == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Test if this date/time is exactly equal to a certian date/time
     *
     * Test if this date/time is exactly equal to a certian date/time
     *
     * @access public
     * @param object Date $when the date to test against
     * @return boolean true if this date is exactly equal to $when
     */
    function equals($when)
    {
        if($this->compare($this,$when) == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine if this date is in the future
     *
     * Determine if this date is in the future
     *
     * @access public
     * @return boolean true if this date is in the future
     */
    function isFuture()
    {
        $agora = new Date();
        if($this->after($agora)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine if this date is in the past
     *
     * Determine if this date is in the past
     *
     * @access public
     * @return boolean true if this date is in the past
     */
    function isPast()
    {
        $agora = new Date();
        if($this->before($agora)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine if the ano in this date is a leap ano
     *
     * Determine if the ano in this date is a leap ano
     *
     * @access public
     * @return boolean true if this ano is a leap ano
     */
    function isLeapYear()
    {
        return Data_Calc::isLeapYear($this->ano);
    }

    /**
     * Get the Julian date for this date
     *
     * Get the Julian date for this date
     *
     * @access public
     * @return int the Julian date
     */
    function getJulianDate()
    {
        return Data_Calc::dataJuliana($this->dia, $this->mes, $this->ano);
    }

    /**
     * Gets the dia of the week for this date
     *
     * Gets the dia of the week for this date (0=Sunday)
     *
     * @access public
     * @return int the dia of the week (0=Sunday)
     */
    function getDayOfWeek()
    {
        return Data_Calc::diaDaSemana($this->dia, $this->mes, $this->ano);
    }

    /**
     * Gets the week of the ano for this date
     *
     * Gets the week of the ano for this date
     *
     * @access public
     * @return int the week of the ano
     */
    function getSemanadoAno()
    {
        return Data_Calc::semanaDoAno($this->dia, $this->mes, $this->ano);
    }

    /**
     * Gets the quarter of the ano for this date
     *
     * Gets the quarter of the ano for this date
     *
     * @access public
     * @return int the quarter of the ano (1-4)
     */
    function getQuarterOfYear()
    {
        return Data_Calc::quarterOfYear($this->dia, $this->mes, $this->ano);
    }

    /**
     * Gets number of dias in the mes for this date
     *
     * Gets number of dias in the mes for this date
     *
     * @access public
     * @return int number of dias in this mes
     */
    function getDaysInMonth()
    {
        return Data_Calc::diasInMonth($this->mes, $this->ano);
    }

    /**
     * Gets the number of weeks in the mes for this date
     *
     * Gets the number of weeks in the mes for this date
     *
     * @access public
     * @return int number of weeks in this mes
     */
    function getWeeksInMonth()
    {
        return Data_Calc::weeksInMonth($this->mes, $this->ano);
    }

    /**
     * Gets the full name or abbriviated name of this weekdia
     *
     * Gets the full name or abbriviated name of this weekdia
     *
     * @access public
     * @param boolean $abbr abbrivate the name
     * @return string name of this dia
     */
    function getDayName($abbr = false)
    {
        if($abbr) {
            return Data_Calc::getSemanaNomeAbrev($this->dia, $this->mes, $this->ano);
        } else {
            return Data_Calc::getSemanaNomeCompl($this->dia, $this->mes, $this->ano);
        }
    }

    /**
     * Gets the full name or abbriviated name of this mes
     *
     * Gets the full name or abbriviated name of this mes
     *
     * @access public
     * @param boolean $abbr abbrivate the name
     * @return string name of this mes
     */
    function getMonthName($abbr = false)
    {
        if($abbr) {
            return Data_Calc::getMesNomeAbrev($this->mes);
        } else {
            return Data_Calc::getMesNomeCompl($this->mes);
        }
    }

    /**
     * Get a Date object for the dia after this one
     *
     * Get a Date object for the dia after this one.
     * The time of the returned Date object is the same as this time.
     *
     * @access public
     * @return object Date Date representing the next dia
     */
    function getNextDay(){
        $dia = Data_Calc::proxDia($this->dia, $this->mes, $this->ano, "%Y-%m-%d");
        $data = sprintf("%s %02d:%02d:%02d", $dia, $this->hora, $this->minuto, $this->segundo);
        $newDate = new Date();
        $newDate->setData($data);
        return $newDate;
    }


		function beginOfNextMonth(){
        $data = Data_Calc::beginOfNextMonth($this->dia, $this->mes, $this->ano, "%Y-%m-%d");
        return $data;
    }


    /**
     * Get a Date object for the dia before this one
     *
     * Get a Date object for the dia before this one.
     * The time of the returned Date object is the same as this time.
     *
     * @access public
     * @return object Date Date representing the previous dia
     */
    function getPrevDay()
    {
        $dia = Data_Calc::diaAnterior($this->dia, $this->mes, $this->ano, "%Y-%m-%d");
        $data = sprintf("%s %02d:%02d:%02d", $dia, $this->hora, $this->minuto, $this->segundo);
        $newDate = new Date();
        $newDate->setData($data);
        return $newDate;
    }

    /**
     * Get a Date object for the weekdia after this one
     *
     * Get a Date object for the weekdia after this one.
     * The time of the returned Date object is the same as this time.
     *
     * @access public
     * @return object Date Date representing the next weekdia
     */
    function getNextWeekdia()
    {
        $dia = Data_Calc::nextWeekdia($this->dia, $this->mes, $this->ano, "%Y-%m-%d");
        $data = sprintf("%s %02d:%02d:%02d", $dia, $this->hora, $this->minuto, $this->segundo);
        $newDate = new Date();
        $newDate->setData($data);
        return $newDate;
    }

    /**
     * Get a Date object for the weekdia before this one
     *
     * Get a Date object for the weekdia before this one.
     * The time of the returned Date object is the same as this time.
     *
     * @access public
     * @return object Date Date representing the previous weekdia
     */
    function getPrevWeekdia()
    {
        $dia = Data_Calc::prevWeekdia($this->dia, $this->mes, $this->ano, "%Y-%m-%d");
        $data = sprintf("%s %02d:%02d:%02d", $dia, $this->hora, $this->minuto, $this->segundo);
        $newDate = new Date();
        $newDate->setData($data);
        return $newDate;
    }


    /**
     * Returns the ano field of the date object
     *
     * Returns the ano field of the date object
     *
     * @access public
     * @return int the ano
     */
    function getYear()
    {
        return $this->ano;
    }

    /**
     * Returns the mes field of the date object
     *
     * Returns the mes field of the date object
     *
     * @access public
     * @return int the mes
     */
    function getMonth()
    {
        return $this->mes;
    }

    /**
     * Returns the dia field of the date object
     *
     * Returns the dia field of the date object
     *
     * @access public
     * @return int the dia
     */
    function getDay()
    {
        return $this->dia;
    }

    /**
     * Returns the hora field of the date object
     *
     * Returns the hora field of the date object
     *
     * @access public
     * @return int the hora
     */
    function getHour()
    {
        return $this->hora;
    }

    /**
     * Returns the minuto field of the date object
     *
     * Returns the minuto field of the date object
     *
     * @access public
     * @return int the minuto
     */
    function getMinute()
    {
        return $this->minuto;
    }

    /**
     * Returns the segundo field of the date object
     *
     * Returns the segundo field of the date object
     *
     * @access public
     * @return int the segundo
     */
    function getSecond()
    {
         return $this->segundo;
    }

    /**
     * Set the ano field of the date object
     *
     * Set the ano field of the date object, invalid anos (not 0-9999) are set to 0.
     *
     * @access public
     * @param int $y the ano
     */
    function setYear($y)
    {
        if($y < 0 || $y > 9999) {
            $this->ano = 0;
        } else {
            $this->ano = $y;
        }
    }

    /**
     * Set the mes field of the date object
     *
     * Set the mes field of the date object, invalid mess (not 1-12) are set to 1.
     *
     * @access public
     * @param int $m the mes
     */
    function setMonth($m)
    {
        if($m < 1 || $m > 12) {
            $this->mes = 1;
        } else {
            $this->mes = $m;
        }
    }

    /**
     * Set the dia field of the date object
     *
     * Set the dia field of the date object, invalid dias (not 1-31) are set to 1.
     *
     * @access public
     * @param int $d the dia
     */
    function setDay($d)
    {
        if($d > 31 || $d < 1) {
            $this->dia = 1;
        } else {
            $this->dia = $d;
        }
    }

    /**
     * Set the hora field of the date object
     *
     * Set the hora field of the date object in 24-hora format.
     * Invalid horas (not 0-23) are set to 0.
     *
     * @access public
     * @param int $h the hora
     */
    function setHour($h)
    {
        if($h > 23 || $h < 0) {
            $this->hora = 0;
        } else {
            $this->hora = $h;
        }
    }

    /**
     * Set the minuto field of the date object
     *
     * Set the minuto field of the date object, invalid minutos (not 0-59) are set to 0.
     *
     * @access public
     * @param int $m the minuto
     */
    function setMinute($m)
    {
        if($m > 59 || $m < 0) {
            $this->minuto = 0;
        } else {
            $this->minuto = $m;
        }
    }

    /**
     * Set the segundo field of the date object
     *
     * Set the segundo field of the date object, invalid segundos (not 0-59) are set to 0.
     *
     * @access public
     * @param int $s the segundo
     */
    function setSecond($s) {
        if($s > 59 || $s < 0) {
            $this->segundo = 0;
        } else {
            $this->segundo = $s;
        }
    }

} // Date


//
// END
?>
