<?php

// The constant telling us what dia starts the week. Monday (1) is the
// international standard. Redefine this to 0 if you want weeks to
// begin on Sunday.
define('DATE_CALC_BEGIN_WEEKDAY', 1);

/**
 * Data_Calc is a calendario class used to calculate and
 * manipulate calendario dates and retrieve dates in a calendario
 * format. It does not rely on 32-bit system date stamps, so
 * you can display calendarios and compare dates that date
 * pre 1970 and post 2038.
 *
 */

class Data_Calc
{
    /**
     * Returns the current local date. NOTE: This function
     * retrieves the local date using strftime(), which may
     * or may not be 32-bit safe on your system.
     *
     * @param string the strftime() format to return the date
     *
     * @access public
     *
     * @return string the current date in specified format
     */

    static function dateNow($format="%Y%m%d")
    {
        return(strftime($format,time()));

    } // end func dateNow

     /**
     * Returns true for valid date, false for invalid date.
     *
     * @param string ano in format CCYY
     * @param string mes in format MM
     * @param string dia in format DD
     *
     * @access public
     *
     * @return boolean true/false
     */

    static function isValidDate($dia, $mes, $ano)
    {
        if($ano < 0 || $ano > 9999)
            return false;
        if(!checkdate($mes,$dia,$ano))
            return false;

        return true;
    } // end func isValidDate

     /**
     * Returns true for a leap ano, else false
     *
     * @param string ano in format CCYY
     *
     * @access public
     *
     * @return boolean true/false
     */

    static function isLeapYear($ano="")
    {

        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");

        if(strlen($ano) != 4)
            return false;

        if(preg_match("/\D/",$ano))
            return false;

        return (($ano % 4 == 0 && $ano % 100 != 0) || $ano % 400 == 0);

    } // end func isLeapYear

    /**
     * Determines if given date is a future date from now.
     *
     * @param string ano in format CCYY
     * @param string mes in format MM
     * @param string dia in format DD
     *
     * @access public
     *
     * @return boolean true/false
     */

    static function isFuturfimData($dia,$mes,$ano)
    {
        $this_ano = Data_Calc::dateNow("%Y");
        $this_mes = Data_Calc::dateNow("%m");
        $this_dia = Data_Calc::dateNow("%d");


        if($ano > $this_ano)
            return true;
        elseif($ano == $this_ano)
            if($mes > $this_mes)
                return true;
            elseif($mes == $this_mes)
                if($dia > $this_dia)
                    return true;

        return false;

    } // end func isFuturfimData

    /**
     * Determines if given date is a past date from now.
     *
     * @param string ano in format CCYY
     * @param string mes in format MM
     * @param string dia in format DD
     *
     * @access public
     *
     * @return boolean true/false
     */

    static function isPastDate($dia,$mes,$ano)
    {
        $this_ano = Data_Calc::dateNow("%Y");
        $this_mes = Data_Calc::dateNow("%m");
        $this_dia = Data_Calc::dateNow("%d");


        if($ano < $this_ano)
            return true;
        elseif($ano == $this_ano)
            if($mes < $this_mes)
                return true;
            elseif($mes == $this_mes)
                if($dia < $this_dia)
                    return true;

        return false;

    } // end func isPastDate

    /**
     * Returns dia of week for given date, 0=Sunday
     *
     * @param string ano in format CCYY, default is current local ano
     * @param string mes in format MM, default is current local mes
     * @param string dia in format DD, default is current local dia
     *
     * @access public
     *
     * @return int $weekdia_number
     */

    static function diaDaSemana($dia="",$mes="",$ano="")
    {

        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        if($mes > 2)
            $mes -= 2;
        else
        {
            $mes += 10;
            $ano--;
        }

        $dia =     ( floor((13 * $mes - 1) / 5) +
                $dia + ($ano % 100) +
                floor(($ano % 100) / 4) +
                floor(($ano / 100) / 4) - 2 *
                floor($ano / 100) + 77);

        $weekdia_number = (($dia - 7 * floor($dia / 7)));

        return $weekdia_number;

    } // end func diaDaSemana

    /**
     * Returns week of the ano, first Sunday is first dia of first week
     *
     * @param string dia in format DD
     * @param string mes in format MM
     * @param string ano in format CCYY
     *
     * @access public
     *
     * @return integer $week_number
     */

    static function semanaDoAno($dia,$mes,$ano)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $week_ano = $ano - 1501;
        $week_dia = $week_ano * 365 + floor($week_ano / 4) - 29872 + 1
                - floor($week_ano / 100) + floor(($week_ano - 300) / 400);

        $week_number =
                ceil((Data_Calc::dataJuliana($dia,$mes,$ano) + floor(($week_dia + 4) % 7)) / 7);

        return $week_number;

    } // end func semanaDoAno

    /**
     * Returns number of dias since 31 December of ano before given date.
     *
     * @param string ano in format CCYY, default is current local ano
     * @param string mes in format MM, default is current local mes
     * @param string dia in format DD, default is current local dia
     *
     * @access public
     *
     * @return int $julian
     */

    static function dataJuliana($dia="",$mes="",$ano="")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $dias = array(0,31,59,90,120,151,181,212,243,273,304,334);

        $julian = ($dias[$mes - 1] + $dia);

        if($mes > 2 && Data_Calc::isLeapYear($ano))
            $julian++;

        return($julian);

    } // end func dataJuliana

    /**
     * Returns quarter of the ano for given date
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     *
     * @access public
     *
     * @return int $ano_quarter
     */

    static function quarterOfYear($dia="",$mes="",$ano="")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $ano_quarter = (intval(($mes - 1) / 3 + 1));

        return $ano_quarter;

    } // end func quarterOfYear

    /**
     * Returns date of begin of next mes of given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function beginOfNextMonth($dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        if($mes < 12)
        {
            $mes++;
            $dia=1;
        }
        else
        {
            $ano++;
            $mes=1;
            $dia=1;
        }

        return Data_Calc::dateFormat($dia,$mes,$ano,$format);

    } // end func beginOfNextMonth

    /**
     * Returns date of the last dia of next mes of given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function endOfNextMonth($dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");


        if($mes < 12)
        {
            $mes++;
        }
        else
        {
            $ano++;
            $mes=1;
        }

        $dia = Data_Calc::diasInMonth($mes,$ano);

        return Data_Calc::dateFormat($dia,$mes,$ano,$format);

    } // end func endOfNextMonth

    /**
     * Returns date of the first dia of previous mes of given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function beginOfPrevMonth($dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        if($mes > 1)
        {
            $mes--;
            $dia=1;
        }
        else
        {
            $ano--;
            $mes=12;
            $dia=1;
        }

        return Data_Calc::dateFormat($dia,$mes,$ano,$format);

    } // end func beginOfPrevMonth

    /**
     * Returns date of the last dia of previous mes for given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function endOfPrevMonth($dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        if($mes > 1)
        {
            $mes--;
        }
        else
        {
            $ano--;
            $mes=12;
        }

        $dia = Data_Calc::diasInMonth($mes,$ano);

        return Data_Calc::dateFormat($dia,$mes,$ano,$format);

    } // end func endOfPrevMonth

    /**
     * Returns date of the next weekdia of given date,
     * skipping from Friday to Monday.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function nextWeekdia($dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $dias = Data_Calc::dataParaDias($dia,$mes,$ano);

        if(Data_Calc::diaDaSemana($dia,$mes,$ano) == 5)
            $dias += 3;
        elseif(Data_Calc::diaDaSemana($dia,$mes,$ano) == 6)
            $dias += 2;
        else
            $dias += 1;

        return(Data_Calc::diasParaData($dias,$format));

    } // end func nextWeekdia

    /**
     * Returns date of the previous weekdia,
     * skipping from Monday to Friday.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function prevWeekdia($dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $dias = Data_Calc::dataParaDias($dia,$mes,$ano);

        if(Data_Calc::diaDaSemana($dia,$mes,$ano) == 1)
            $dias -= 3;
        elseif(Data_Calc::diaDaSemana($dia,$mes,$ano) == 0)
            $dias -= 2;
        else
            $dias -= 1;

        return(Data_Calc::diasParaData($dias,$format));

    } // end func prevWeekdia

    /**
     * Returns date of the next specific dia of the week
     * from the given date.
     *
     * @param int dia of week, 0=Sunday
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param boolean onOrAfter if true and dias are same, returns current dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function proxDiaOfWeek($dow,$dia="",$mes="",$ano="",$format="%Y%m%d",$onOrAfter=false)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $dias = Data_Calc::dataParaDias($dia,$mes,$ano);
        $curr_weekdia = Data_Calc::diaDaSemana($dia,$mes,$ano);

        if($curr_weekdia == $dow)
		{
			if(!$onOrAfter)
            	$dias += 7;
		}
        elseif($curr_weekdia > $dow)
            $dias += 7 - ( $curr_weekdia - $dow );
        else
            $dias += $dow - $curr_weekdia;

        return(Data_Calc::diasParaData($dias,$format));

    } // end func proxDiaOfWeek

    /**
     * Returns date of the previous specific dia of the week
     * from the given date.
     *
     * @param int dia of week, 0=Sunday
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param boolean onOrBefore if true and dias are same, returns current dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function diaAnteriorOfWeek($dow,$dia="",$mes="",$ano="",$format="%Y%m%d",$onOrBefore=false)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $dias = Data_Calc::dataParaDias($dia,$mes,$ano);
        $curr_weekdia = Data_Calc::diaDaSemana($dia,$mes,$ano);

        if($curr_weekdia == $dow)
		{
			if(!$onOrBefore)
            	$dias -= 7;
		}
        elseif($curr_weekdia < $dow)
            $dias -= 7 - ( $dow - $curr_weekdia );
        else
            $dias -= $curr_weekdia - $dow;

        return(Data_Calc::diasParaData($dias,$format));

    } // end func diaAnteriorOfWeek

    /**
     * Returns date of the next specific dia of the week
     * on or before the given date.
     *
     * @param int dia of week, 0=Sunday
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function proxDiaOfWeekOnOrAfter($dow,$dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        return(Data_Calc::proxDiaOfWeek($dow,$dia="",$mes="",$ano="",$format="%Y%m%d",true));
    } // end func proxDiaOfWeekOnOrAfter

    /**
     * Returns date of the previous specific dia of the week
     * on or before the given date.
     *
     * @param int dia of week, 0=Sunday
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function diaAnteriorOfWeekOnOrBefore($dow,$dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        return(Data_Calc::diaAnteriorOfWeek($dow,$dia="",$mes="",$ano="",$format="%Y%m%d",true));

    } // end func diaAnteriorOfWeekOnOrAfter

    /**
     * Returns date of dia after given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function proxDia($dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $dias = Data_Calc::dataParaDias($dia,$mes,$ano);

        return(Data_Calc::diasParaData($dias + 1,$format));

    } // end func proxDia

    /**
     * Returns date of dia before given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function diaAnterior($dia="",$mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $dias = Data_Calc::dataParaDias($dia,$mes,$ano);

        return(Data_Calc::diasParaData($dias - 1,$format));

    } // end func diaAnterior

    /**
     * Sets century for 2 digit ano.
     * 51-99 is 19, else 20
     *
     * @param string 2 digit ano
     *
     * @access public
     *
     * @return string 4 digit ano
     */

    static function defaultCentury($ano)
    {
        if(strlen($ano) == 1)
            $ano = "0$ano";
        if($ano > 50)
            return( "19$ano" );
        else
            return( "20$ano" );

    } // end func defaultCentury

    /**
     * Returns number of dias between two given dates.
     *
     * @param string ano in format CCYY
     * @param string mes in format MM
     * @param string dia in format DD
     * @param string ano in format CCYY
     * @param string mes in format MM
     * @param string dia in format DD
     *
     * @access public
     *
     * @return int absolute number of dias between dates,
     *      -1 if there is an error.
     */

    static function dateDiff($dia1,$mes1,$ano1,$dia2,$mes2,$ano2)
    {
        if(!Data_Calc::isValidDate($dia1,$mes1,$ano1))
            return -1;
        if(!Data_Calc::isValidDate($dia2,$mes2,$ano2))
            return -1;

        return(abs((Data_Calc::dataParaDias($dia1,$mes1,$ano1))
                    - (Data_Calc::dataParaDias($dia2,$mes2,$ano2))));

    } // end func dateDiff

    /**
    * Compares two dates
    *
    * @param string $dia1   dia in format DD
    * @param string $mes1 mes in format MM
    * @param string $ano1  ano in format CCYY
    * @param string $dia2   dia in format DD
    * @param string $mes2 mes in format MM
    * @param string $ano2  ano in format CCYY
    *
    * @access public
    * @return int 0 on equality, 1 if date 1 is greater, -1 if smaller
    */
    static function comparfimDatas($dia1,$mes1,$ano1,$dia2,$mes2,$ano2)
    {
        $ndias1 = Data_Calc::dataParaDias($dia1, $mes1, $ano1);
        $ndias2 = Data_Calc::dataParaDias($dia2, $mes2, $ano2);
        if ($ndias1 == $ndias2) {
            return 0;
        }
        return ($ndias1 > $ndias2) ? 1 : -1;
    }

    /**
     * Find the number of dias in the given mes.
     *
     * @param string mes in format MM, default current local mes
     *
     * @access public
     *
     * @return int number of dias
     */

    static function diasInMonth($mes="",$ano="")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");

        if($mes == 2)
        {
            if(Data_Calc::isLeapYear($ano))
                return 29;
            else
                return 28;
        }
        elseif($mes == 4 or $mes == 6 or $mes == 9 or $mes == 11)
            return 30;
        else
            return 31;
    } // end func diasInMonth

    /**
     * Returns the number of rows on a calendario mes. Useful for
     * determining the number of rows when displaying a typical
     * mes calendario.
     *
     * @param string mes in format MM, default current local mes
     * @param string ano in format YYCC, default current local ano
     *
     * @access public
     *
     * @return int number of weeks
     */

    static function weeksInMonth($mes="",$ano="",$fdow=null)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if($fdow === null)
            $fdow = DATE_CALC_BEGIN_WEEKDAY;

        if($fdow == 1)
        {

            if(Data_Calc::firstOfMonthWeekdia($mes,$ano) == 0)
                $first_week_dias = 1;
            else
                $first_week_dias = 7 - (Data_Calc::firstOfMonthWeekdia($mes,$ano) - 1);

        }
        else
            $first_week_dias = 7 - Data_Calc::firstOfMonthWeekdia($mes,$ano);

        return ceil(((Data_Calc::diasInMonth($mes,$ano) - $first_week_dias) / 7) + 1);

    } // end func weeksInMonth

    /**
     * Find the dia of the week for the first of the mes of given date.
     *
     * @param string ano in format CCYY, default to current local ano
     * @param string mes in format MM, default to current local mes
     *
     * @access public
     *
     * @return int number of weekdia for the first dia, 0=Sunday
     */

    static function firstOfMonthWeekdia($mes="",$ano="")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");

        return(Data_Calc::diaDaSemana("01",$mes,$ano));

    } // end func firstOfMonthWeekdia

    /**
     * Return date of first dia of mes of given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function beginOfMonth($mes="",$ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");

        return(Data_Calc::dateFormat("01",$mes,$ano,$format));

    } // end of func beginOfMonth

    /**
     * Find the mes dia of the beginning of week for given date,
     * using DATE_CALC_BEGIN_WEEKDAY. (can return weekdia of prev mes.)
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function beginOfWeek($dia="",$mes="",$ano="",$format="%Y%m%d",$fdow=null)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");
        if($fdow === null)
            $fdow = DATE_CALC_BEGIN_WEEKDAY;

        $this_weekdia = Data_Calc::diaDaSemana($dia,$mes,$ano);

        if($fdow == 1)
        {
            if($this_weekdia == 0)
                $beginOfWeek = Data_Calc::dataParaDias($dia,$mes,$ano) - 6;
            else
                $beginOfWeek = Data_Calc::dataParaDias($dia,$mes,$ano)
                    - $this_weekdia + 1;
        }
        else
                $beginOfWeek = (Data_Calc::dataParaDias($dia,$mes,$ano)
                    - $this_weekdia);


       /*  $beginOfWeek = (Data_Calc::dataParaDias($dia,$mes,$ano)
            - ($this_weekdia - $fdow)); */

        return(Data_Calc::diasParaData($beginOfWeek,$format));

    } // end of func beginOfWeek

    /**
     * Find the mes dia of the end of week for given date,
     * using DATE_CALC_BEGIN_WEEKDAY. (can return weekdia
     * of following mes.)
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function endOfWeek($dia="",$mes="",$ano="",$format="%Y%m%d",$fdow=null)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");
        if($fdow === null)
            $fdow = DATE_CALC_BEGIN_WEEKDAY;

        $this_weekdia = Data_Calc::diaDaSemana($dia,$mes,$ano);

        $ultimo_diaDaSemana = (Data_Calc::dataParaDias($dia,$mes,$ano)
            + (6 - $this_weekdia + $fdow));

        return(Data_Calc::diasParaData($ultimo_diaDaSemana,$format));

    } // end func endOfWeek

    /**
     * Find the mes dia of the beginning of week after given date,
     * using DATE_CALC_BEGIN_WEEKDAY. (can return weekdia of prev mes.)
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function beginOfNextWeek($dia="",$mes="",$ano="",$format="%Y%m%d",$fdow=null)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");
        if($fdow === null)
            $fdow = DATE_CALC_BEGIN_WEEKDAY;

        $data = Data_Calc::diasParaData(Data_Calc::dataParaDias($dia+7,$mes,$ano),"%Y%m%d");

        $next_week_ano = substr($data,0,4);
        $next_week_mes = substr($data,4,2);
        $next_week_dia = substr($data,6,2);

        $this_weekdia = Data_Calc::diaDaSemana($next_week_dia,$next_week_mes,$next_week_ano);

        $beginOfWeek = (Data_Calc::dataParaDias($next_week_dia,$next_week_mes,$next_week_ano)
            - ($this_weekdia - $fdow));

        return(Data_Calc::diasParaData($beginOfWeek,$format));

    } // end func beginOfNextWeek

    /**
     * Find the mes dia of the beginning of week before given date,
     * using DATE_CALC_BEGIN_WEEKDAY. (can return weekdia of prev mes.)
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function beginOfPrevWeek($dia="",$mes="",$ano="",$format="%Y%m%d",$fdow=null)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");
        if($fdow === null)
            $fdow = DATE_CALC_BEGIN_WEEKDAY;

        $data = Data_Calc::diasParaData(Data_Calc::dataParaDias($dia-7,$mes,$ano),"%Y%m%d");

        $next_week_ano = substr($data,0,4);
        $next_week_mes = substr($data,4,2);
        $next_week_dia = substr($data,6,2);

        $this_weekdia = Data_Calc::diaDaSemana($next_week_dia,$next_week_mes,$next_week_ano);

        $beginOfWeek = (Data_Calc::dataParaDias($next_week_dia,$next_week_mes,$next_week_ano)
            - ($this_weekdia - $fdow));

        return(Data_Calc::diasParaData($beginOfWeek,$format));

    } // end func beginOfPrevWeek

    /**
     * Return an array with dias in week
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param string format for returned date
     *
     * @access public
     *
     * @return array $week[$weekdia]
     */

    static function getCalendarioSemana($dia="",$mes="",$ano="",$format="%Y%m%d",$fdow=null)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");
        if($fdow === null)
            $fdow = DATE_CALC_BEGIN_WEEKDAY;

        $week_array = array();

        // date for the column of week

        $curr_dia = Data_Calc::beginOfWeek($dia,$mes,$ano,"%E",$fdow);

            for($counter=0; $counter <= 6; $counter++)
            {
                $week_array[$counter] = Data_Calc::diasParaData($curr_dia,$format);
                $curr_dia++;
            }

        return $week_array;

    } // end func getCalendarioSemana

    /**
     * Return a set of arrays to construct a calendario mes for
     * the given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string format for returned date
     *
     * @access public
     *
     * @return array $mes[$row][$col]
     */

    static function getCalendarioMes($mes="",$ano="",$format="%Y%m%d",$fdow=null){
        if(empty($ano)) $ano = Data_Calc::dateNow("%Y");
        if(empty($mes)) $mes = Data_Calc::dateNow("%m");
        if($fdow === null) $fdow = DATE_CALC_BEGIN_WEEKDAY;
        $mes_array = array();
        // date for the first row, first column of calendario mes
        if($fdow == 1) {
            if(Data_Calc::firstOfMonthWeekdia($mes,$ano) == 0) $curr_dia = Data_Calc::dataParaDias("01",$mes,$ano) - 6;
            else $curr_dia = Data_Calc::dataParaDias("01",$mes,$ano) - Data_Calc::firstOfMonthWeekdia($mes,$ano) + 1;
        		}
        else $curr_dia = (Data_Calc::dataParaDias("01",$mes,$ano) - Data_Calc::firstOfMonthWeekdia($mes,$ano));

        // number of dias in this mes
        $diasInMonth = Data_Calc::diasInMonth($mes,$ano);

        $weeksInMonth = Data_Calc::weeksInMonth($mes,$ano,$fdow);
        for($row_counter=0; $row_counter < $weeksInMonth; $row_counter++){
            for($column_counter=0; $column_counter <= 6; $column_counter++){
                $mes_array[$row_counter][$column_counter] = Data_Calc::diasParaData($curr_dia,$format);
                $curr_dia++;
           		 }
        		}

        return $mes_array;

    		} // end func getCalendarioMes

    /**
     * Return a set of arrays to construct a calendario ano for
     * the given date.
     *
     * @param string ano in format CCYY, default current local ano
     * @param string format for returned date
     *
     * @access public
     *
     * @return array $ano[$mes][$row][$col]
     */

    static function getCalendarioAno($ano="",$format="%Y%m%d")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");

        $ano_array = array();

        for($curr_mes=0; $curr_mes <=11; $curr_mes++)
            $ano_array[$curr_mes] = Data_Calc::getCalendarioMes(sprintf("%02d",$curr_mes+1),$ano,$format);

        return $ano_array;

    } // end func getCalendarioAno

    /**
     * Converts a date to number of dias since a
     * distant unspecified epoch.
     *
     * @param string ano in format CCYY
     * @param string mes in format MM
     * @param string dia in format DD
     *
     * @access public
     *
     * @return integer number of dias
     */

    static function dataParaDias($dia,$mes,$ano)
    {

        $century = substr($ano,0,2);
        $ano = substr($ano,2,2);

        if($mes > 2)
            $mes -= 3;
        else
        {
            $mes += 9;
            if($ano)
                $ano--;
            else
            {
                $ano = 99;
                $century --;
            }
        }

        return ( floor((  146097 * $century)    /  4 ) +
                floor(( 1461 * $ano)        /  4 ) +
                floor(( 153 * $mes +  2) /  5 ) +
                    $dia +  1721119);
    } // end func dataParaDias

    /**
     * Converts number of dias to a distant unspecified epoch.
     *
     * @param int number of dias
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in specified format
     */

    static function diasParaData($dias,$format="%Y%m%d")
    {

        $dias     -=   1721119;
        $century  =    floor(( 4 * $dias -  1) /  146097);
        $dias     =    floor(4 * $dias - 1 - 146097 * $century);
        $dia      =    floor($dias /  4);

        $ano     =    floor(( 4 * $dia +  3) /  1461);
        $dia      =    floor(4 * $dia +  3 -  1461 * $ano);
        $dia      =    floor(($dia +  4) /  4);

        $mes    =    floor(( 5 * $dia -  3) /  153);
        $dia      =    floor(5 * $dia -  3 -  153 * $mes);
        $dia      =    floor(($dia +  5) /  5);

        if($mes < 10)
            $mes +=3;
        else
        {
            $mes -=9;
            if($ano++ == 99)
            {
                $ano = 0;
                $century++;
            }
        }

        $century = sprintf("%02d",$century);
        $ano = sprintf("%02d",$ano);
        return(Data_Calc::dateFormat($dia,$mes,$century.$ano,$format));

    } // end func diasParaData

    /**
     * Calculates the date of the Nth weekdia of the mes,
     * such as the segundo Saturday of January 2000.
     *
     * @param string occurance: 1=first, 2=segundo, 3=third, etc.
     * @param string diaDaSemana: 0=Sunday, 1=Monday, etc.
     * @param string ano in format CCYY
     * @param string mes in format MM
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    function NWeekdiaOfMonth($occurance,$diaDaSemana,$mes,$ano,$format="%Y%m%d")
    {
        $ano = sprintf("%04d",$ano);
        $mes = sprintf("%02d",$mes);

        $DOW1dia = sprintf("%02d",(($occurance - 1) * 7 + 1));
        $DOW1 = Data_Calc::diaDaSemana($DOW1dia,$mes,$ano);

        $wdate = ($occurance - 1) * 7 + 1 +
                (7 + $diaDaSemana - $DOW1) % 7;

        if( $wdate > Data_Calc::diasInMonth($mes,$ano)) {
            return -1;
        } else {
            return(Data_Calc::dateFormat($wdate,$mes,$ano,$format));
        }

    } // end func NWeekdiaOfMonth

    /**
     *  Formats the date in the given format, much like
     *  strfmt(). This function is used to alleviate the
     *  problem with 32-bit numbers for dates pre 1970
     *  or post 2038, as strfmt() has on most systems.
     *  Most of the formatting options are compatible.
     *
     *  formatting options:
     *
     *  %a        abbreviated weekdia name (Sun, Mon, Tue)
     *  %A        full weekdia name (Sunday, Monday, Tuesday)
     *  %b        abbreviated mes name (Jan, Feb, Mar)
     *  %B        full mes name (January, February, March)
     *  %d        dia of mes (range 00 to 31)
     *  %e        dia of mes, single digit (range 0 to 31)
     *  %E        number of dias since unspecified epoch (integer)
     *             (%E is useful for passing a date in a URL as
     *             an integer value. Then simply use
     *             diasParaData() to convert back to a date.)
     *  %j        dia of ano (range 001 to 366)
     *  %m        mes as decimal number (range 1 to 12)
     *  %n        newline character (\n)
     *  %t        tab character (\t)
     *  %w        weekdia as decimal (0 = Sunday)
     *  %U        week number of current ano, first Sunday as first week
     *  %y        ano as decimal (range 00 to 99)
     *  %Y        ano as decimal including century (range 0000 to 9999)
     *  %%        literal '%'
     *
     * @param string ano in format CCYY
     * @param string mes in format MM
     * @param string dia in format DD
     * @param string format for returned date
     *
     * @access public
     *
     * @return string date in given format
     */

    static function dateFormat($dia,$mes,$ano,$format)
    {
        if(!Data_Calc::isValidDate($dia,$mes,$ano))
        {
            $ano = Data_Calc::dateNow("%Y");
            $mes = Data_Calc::dateNow("%m");
            $dia = Data_Calc::dateNow("%d");
        }

        $saida = "";

        for($strpos = 0; $strpos < strlen($format); $strpos++)
        {
            $char = substr($format,$strpos,1);
            if($char == "%")
            {
                $prox_caracter = substr($format,$strpos + 1,1);
                switch($prox_caracter)
                {
                    case "a":
                        $saida .= Data_Calc::getSemanaNomeAbrev($dia,$mes,$ano);
                        break;
                    case "A":
                        $saida .= Data_Calc::getSemanaNomeCompl($dia,$mes,$ano);
                        break;
                    case "b":
                        $saida .= Data_Calc::getMesNomeAbrev($mes);
                        break;
                    case "B":
                        $saida .= Data_Calc::getMesNomeCompl($mes);
                        break;
                    case "d":
                        $saida .= sprintf("%02d",$dia);
                        break;
                    case "e":
                        $saida .= $dia;
                        break;
                    case "E":
                        $saida .= Data_Calc::dataParaDias($dia,$mes,$ano);
                        break;
                    case "j":
                        $saida .= Data_Calc::dataJuliana($dia,$mes,$ano);
                        break;
                    case "m":
                        $saida .= sprintf("%02d",$mes);
                        break;
                    case "n":
                        $saida .= "\n";
                        break;
                    case "t":
                        $saida .= "\t";
                        break;
                    case "w":
                        $saida .= Data_Calc::diaDaSemana($dia,$mes,$ano);
                        break;
                    case "U":
                        $saida .= Data_Calc::semanaDoAno($dia,$mes,$ano);
                        break;
                    case "y":
                        $saida .= substr($ano,2,2);
                        break;
                    case "Y":
                        $saida .= $ano;
                        break;
                    case "%":
                        $saida .= "%";
                        break;
                    default:
                        $saida .= $char.$prox_caracter;
                }
                $strpos++;
            }
            else
            {
                $saida .= $char;
            }
        }
        return $saida;

    } // end func dateFormat

    /**
     * Returns the current local ano in format CCYY
     *
     * @access public
     *
     * @return string ano in format CCYY
     */

    static function getYear()
    {
        return Data_Calc::dateNow("%Y");

    } // end func getYear

    /**
     * Returns the current local mes in format MM
     *
     * @access public
     *
     * @return string mes in format MM
     */

    static function getMonth()
    {
        return Data_Calc::dateNow("%m");

    } // end func getMonth

    /**
     * Returns the current local dia in format DD
     *
     * @access public
     *
     * @return string dia in format DD
     */

    static function getDay()
    {
        return Data_Calc::dateNow("%d");

    } // end func getDay

    /**
     * Returns the full mes name for the given mes
     *
     * @param string mes in format MM
     *
     * @access public
     *
     * @return string full mes name
     */

    static function getMesNomeCompl($mes)
    {
        $mes = (int)$mes;

        if(empty($mes))
            $mes = (int) Data_Calc::dateNow("%m");

        $mes_names = Data_Calc::getMonthNames();
        return $mes_names[$mes];
        // getMonthNames returns mess with correct indexes
        //return $mes_names[($mes - 1)];

    } // end func getMesNomeCompl

    /**
     * Returns the abbreviated mes name for the given mes
     *
     * @param string mes in format MM
     * @param int optional length of abbreviation, default is 3
     *
     * @access public
     *
     * @return string abbreviated mes name
     * @see Data_Calc::getMesNomeCompl
     */

    static function getMesNomeAbrev($mes,$length=3)
    {
        $mes = (int)$mes;

        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        return substr(Data_Calc::getMesNomeCompl($mes), 0, $length);
    } // end func getMesNomeAbrev

    /**
     * Returns the full weekdia name for the given date
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     *
     * @access public
     *
     * @return string full mes name
     */

    static function getSemanaNomeCompl($dia="",$mes="",$ano="")
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");

        $weeknome_dias = Data_Calc::getWeekDays();
        $weekdia = Data_Calc::diaDaSemana($dia,$mes,$ano);

        return $weeknome_dias[$weekdia];

    } // end func getSemanaNomeCompl

    /**
     * Returns the abbreviated weekdia name for the given date
     *
     * @param string ano in format CCYY, default current local ano
     * @param string mes in format MM, default current local mes
     * @param string dia in format DD, default current local dia
     * @param int optional length of abbreviation, default is 3
     *
     * @access public
     *
     * @return string full mes name
     * @see Data_Calc::getSemanaNomeCompl
     */

    static function getSemanaNomeAbrev($dia="",$mes="",$ano="",$length=3)
    {
        if(empty($ano))
            $ano = Data_Calc::dateNow("%Y");
        if(empty($mes))
            $mes = Data_Calc::dateNow("%m");
        if(empty($dia))
            $dia = Data_Calc::dateNow("%d");
        return substr(Data_Calc::getSemanaNomeCompl($dia,$mes,$ano),0,$length);
    } // end func getSemanaNomeCompl

    /**
    * Returns the numeric mes from the mes name or an abreviation
    *
    * Both August and Aug would return 8.
    * Month name is case insensitive.
    *
    * @param    string  mes name
    * @return   integer mes number
    */
    function getMonthFromFullName($mes)
    {
        $mes = strtolower($mes);
        $mess = Data_Calc::getMonthNames();
        while(list($id, $name) = each($mess)){
            if(preg_match('/'.$mes.'/', strtolower($name))){
                return($id);
            }
        }
        return(0);
    }

    /**
    * Retunrs an array of mes names
    *
    * Used to take advantage of the setlocale function to return
    * language specific mes names.
    * XXX cache values to some global array to avoid preformace hits when called more than once.
    *
    * @returns array An array of mes names
    */
    static function getMonthNames()
    {
        for($i=1;$i<13;$i++){
            $mess[$i] = strftime('%B', mktime(0, 0, 0, $i, 1, 2001));
        }
        return($mess);
    }

    /**
    * Returns an array of week dias
    *
    * Used to take advantage of the setlocale function to
    * return language specific week dias
    * XXX cache values to some global array to avoid preformace hits when called more than once.
    *
    * @returns array An array of week dia names
    */
    static function getWeekDays()
    {
        for($i=0;$i<7;$i++){
            $weekdias[$i] = strftime('%A', mktime(0, 0, 0, 1, $i, 2001));
        }
        return($weekdias);
    }

} // end class Date_calendario

?>
