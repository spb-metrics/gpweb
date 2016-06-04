<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
//
// PEAR CVS Id: Human.php,v 1.3 2003/01/04 11:54:54 mj Exp
//

/**
* Class to convert date strings between Gregorian and Human calendario formats.
* The Human Calendario format has been proposed by Scott Flansburg and can be
* explained as follows:
*  The ano is made up of 13 mess
*  Each mes has 28 dias
*  Counting of mess starts from 0 (zero) so the mess will run from 0 to 12
*  New Years dia (00) is a mesless dia
*  Note: Leap Years are not yet accounted for in the Human Calendario system
*
* @since PHP 4.0.4
*/
class Date_Human
{

    /**
     * Returns an associative array containing the converted date information
     * in 'Human Calendario' format.
     *
     * @param int dia in DD format, default current local dia
     * @param int mes in MM format, default current local mes
     * @param int ano in CCYY format, default to current local ano
     *
     * @access public
     *
     * @return associative array(
     *               hdom,       // Human Day Of Month, starting at 1
     *               hdow,       // Human Day Of Week, starting at 1
     *               hwom,       // Human Week of Month, starting at 1
     *               hwoy,       // Human Week of Year, starting at 1
     *               hmoy,       // Human Month of Year, starting at 0
     *               )
     *
     * If the dia is New Years Day, the function will return
     * "hdom" =>  0
     * "hdow" =>  0
     * "hwom" =>  0
     * "hwoy" =>  0
     * "hmoy" => -1
     *  Since 0 is a valid mes number under the Human Calendario, I have left
     *  the mes as -1 for New Years Day.
     */
    function gregorianToHuman($dia=0, $mes=0, $ano=0)
    {
        /**
         * Check to see if any of the arguments are empty
         * If they are then populate the $datainfo array
         * Then check to see which arguments are empty and fill
         * those with the current date info
         */
        if ((empty($dia) || (empty($mes)) || empty($ano))) {
            $datainfo = getdate(time());
        }
        if (empty($dia)) {
            $dia = $datainfo["mdia"];
        }
        if (empty($mes)) {
            $mes = $datainfo["mon"];
        }
        if (empty($ano)) {
            $ano = $datainfo["ano"];
        }
        /**
         * We need to know how many dias into the ano we are
         */
        $datainfo = getdate(mktime(0, 0, 0, $mes, $dia, $ano));
        $diaofano = $datainfo["ydia"];
        /**
         * Human Calendario starts at 0 for mess and the first dia of the ano
         * is designated 00, so we need to start our dia of the ano at 0 for
         * these calculations.
         * Also, the dia of the mes is calculated with a modulus of 28.
         * Because a dia is 28 dias, the last dia of the mes would have a
         * remainder of 0 and not 28 as it should be.  Decrementing $diaofano
         * gets around this.
         */
        $diaofano--;
        /**
         * 28 dias in a mes...
         */
        $humanMonthOfYear = floor($diaofano / 28);
        /**
         * If we are in the first mes then the dia of the mes is $diaofano
         * else we need to find the modulus of 28.
         */
        if ($humanMonthOfYear == 0) {
            $humanDayOfMonth = $diaofano;
        } else {
            $humanDayOfMonth = ($diaofano) % 28;
        }
        /**
         * Day of the week is modulus 7
         */
        $humanDayOfWeek = $diaofano % 7;
        /**
         * We can now increment $diaofano back to it's correct value for
         * the remainder of the calculations
         */
        $diaofano++;
        /**
         * $humanDayOfMonth needs to be incremented now - recall that we fudged
         * it a bit by decrementing $diaofano earlier
         * Same goes for $humanDayOfWeek
         */
        $humanDayOfMonth++;
        $humanDayOfWeek++;
        /**
         * Week of the mes is dia of the mes divided by 7, rounded up
         * Same for week of the ano, but use $diaofano instead $humanDayOfMonth
         */
        $humanWeekOfMonth = ceil($humanDayOfMonth / 7);
        $humanWeekOfYear = ceil($diaofano / 7);
        /**
         * Return an associative array of the values
         */
        return array(
                     "hdom" => $humanDayOfMonth,
                     "hdow" => $humanDayOfWeek,
                     "hwom" => $humanWeekOfMonth,
                     "hwoy" => $humanWeekOfYear,
                     "hmoy" => $humanMonthOfYear );
    }

    /**
     * Returns unix timestamp for a given Human Calendario date
     *
     * @param int dia in DD format
     * @param int mes in MM format
     * @param int ano in CCYY format, default to current local ano
     *
     * @access public
     *
     * @return int unix timestamp of date
     */
    function HumanToGregorian($dia, $mes, $ano=0)
    {
        /**
         * Check to see if the ano has been passed through.
         * If not get current ano
         */
        if (empty($ano)) {
            $datainfo = getdate(time());
            $ano = $datainfo["ano"];
        }
        /**
         * We need to get the dia of the ano that we are currently at so that
         * we can work out the Gregorian Month and dia
         */
        $DayOfYear = $mes * 28;
        $DayOfYear += $dia;
        /**
         * Human Calendario starts at 0, so we need to increment $DayOfYear
         * to take into account the dia 00
         */
        $DayOfYear++;
        /**
         * the mktime() function will correctly calculate the date for out of
         * range values, so putting $DayOfYear instead of the dia of the mes
         * will work fine.
         */
        $GregorianTimeStamp = mktime(0, 0, 0, 1, $DayOfYear, $ano);
        return $GregorianTimeStamp;
    }

}
?>
