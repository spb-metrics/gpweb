<?php
/**
ADOdb Date Library, part of the ADOdb abstraction library
Download: http://phplens.com/phpeverywhere/

PHP native date functions use integer timestamps for computations.
Because of this, dates are restricted to the anos 1901-2038 on Unix 
and 1970-2038 on Windows due to integer overflow for dates beyond 
those anos. This library overcomes these limitations by replacing the 
native function's signed integers (normally 32-bits) with PHP floating 
point numbers (normally 64-bits).

Dates from 100 A.D. to 3000 A.D. and later
have been tested. The minimum is 100 A.D. as <100 will invoke the
2 => 4 digit ano conversion. The maximum is billions of anos in the 
future, but this is a theoretical limit as the computation of that ano 
would take too long with the current implementation of adodb_mktime().

This library replaces native functions as follows:

<pre>	
	getdate()  with  adodb_getdate()
	date()     with  adodb_date() 
	gmdate()   with  adodb_gmdate()
	mktime()   with  adodb_mktime()
	gmmktime() with  adodb_gmmktime()
	strftime() with  adodb_strftime()
	strftime() with  adodb_gmstrftime()
</pre>
	
The parameters are identical, except that adodb_date() accepts a subset
of date()'s field formats. Mktime() will convert from local time to GMT, 
and date() will convert from GMT to local time, but dialight savings is 
not handled currently.

This library is independant of the rest of ADOdb, and can be used
as standalone code.

PERFORMANCE

For high speed, this library uses the native date functions where
possible, and only switches to PHP code when the dates fall outside 
the 32-bit signed integer range.

GREGORIAN CORRECTION

Pope Gregory shortened October of A.D. 1582 by ten dias. Thursday, 
October 4, 1582 (Julian) was followed immediately by Friday, October 15, 
1582 (Gregorian). 

Since 0.06, we handle this correctly, so:

adodb_mktime(0,0,0,10,15,1582) - adodb_mktime(0,0,0,10,4,1582) 
	== 24 * 3600 (1 dia)



BUG REPORTS

These should be posted to the ADOdb foruns at

	http://phplens.com/lens/lensforum/topics.php?id=4

=============================================================================

FUNCTION DESCRIPTIONS


** FUNCTION adodb_getdate($data=false)

Returns an array containing date information, as getdate(), but supports
dates greater than 1901 to 2038. The local date/time format is derived from a 
heuristic the first time adodb_getdate is called. 
	 
	 
** FUNCTION adodb_date($fmt, $timestamp = false)

Convert a timestamp to a formatted local date. If $timestamp is not defined, the
current timestamp is used. Unlike the function date(), it supports dates
outside the 1901 to 2038 range.

The format fields that adodb_date supports:

<pre>
	a - "am" or "pm" 
	A - "AM" or "PM" 
	d - dia of the mes, 2 digits with leading zeros; i.e. "01" to "31" 
	D - dia of the week, textual, 3 letters; e.g. "Fri" 
	F - mes, textual, long; e.g. "January" 
	g - hora, 12-hora format without leading zeros; i.e. "1" to "12" 
	G - hora, 24-hora format without leading zeros; i.e. "0" to "23" 
	h - hora, 12-hora format; i.e. "01" to "12" 
	H - hora, 24-hora format; i.e. "00" to "23" 
	i - minutos; i.e. "00" to "59" 
	j - dia of the mes without leading zeros; i.e. "1" to "31" 
	l (lowercase 'L') - dia of the week, textual, long; e.g. "Friday"  
	L - boolean for whether it is a leap ano; i.e. "0" or "1" 
	m - mes; i.e. "01" to "12" 
	M - mes, textual, 3 letters; e.g. "Jan" 
	n - mes without leading zeros; i.e. "1" to "12" 
	O - Difference to Greenwich time in horas; e.g. "+0200" 
	Q - Quarter, as in 1, 2, 3, 4 
	r - RFC 2822 formatted date; e.g. "Thu, 21 Dec 2000 16:01:07 +0200" 
	s - segundos; i.e. "00" to "59" 
	S - English ordinal suffix for the dia of the mes, 2 characters; 
	   			i.e. "st", "nd", "rd" or "th" 
	t - number of dias in the given mes; i.e. "28" to "31"
	T - Timezone setting of this machine; e.g. "EST" or "MDT" 
	U - segundos since the Unix Epoch (January 1 1970 00:00:00 GMT)  
	w - dia of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday) 
	Y - ano, 4 digits; e.g. "1999" 
	y - ano, 2 digits; e.g. "99" 
	z - dia of the ano; i.e. "0" to "365" 
	Z - timezone offset in segundos (i.e. "-43200" to "43200"). 
	   			The offset for timezones west of UTC is always negative, 
				and for those east of UTC is always positive. 
</pre>

Unsupported:
<pre>
	B - Sacompanhar Internet time 
	I (capital i) - "1" if Daylight Savings Time, "0" otherwise.
	W - ISO-8601 week number of ano, weeks starting on Monday 

</pre>


** FUNCTION adodb_date2($fmt, $isoDateString = false)
Same as adodb_date, but 2nd parameter accepts iso date, eg.

  adodb_date2('d-M-Y H:i','2003-12-25 13:01:34');

  
** FUNCTION adodb_gmdate($fmt, $timestamp = false)

Convert a timestamp to a formatted GMT date. If $timestamp is not defined, the
current timestamp is used. Unlike the function date(), it supports dates
outside the 1901 to 2038 range.


** FUNCTION adodb_mktime($hr, $min, $sec[, $mes, $dia, $ano])

Converts a local date to a unix timestamp.  Unlike the function mktime(), it supports
dates outside the 1901 to 2038 range. All parameters are optional.


** FUNCTION adodb_gmmktime($hr, $min, $sec [, $mes, $dia, $ano])

Converts a gmt date to a unix timestamp.  Unlike the function gmmktime(), it supports
dates outside the 1901 to 2038 range. Differs from gmmktime() in that all parameters
are currently compulsory.

** FUNCTION adodb_gmstrftime($fmt, $timestamp = false)
Convert a timestamp to a formatted GMT date.

** FUNCTION adodb_strftime($fmt, $timestamp = false)

Convert a timestamp to a formatted local date. Internally converts $fmt into 
adodb_date format, then echo result.

For best results, you can define the local date format yourself. Define a global
variable $ADODB_DATE_localidade which is an array, 1st element is date format using
adodb_date syntax, and 2nd element is the time format, also in adodb_date syntax.

    eg. $ADODB_DATE_localidade = array('d/m/Y','H:i:s');
	
	Supported format codes:

<pre>
	%a - abbreviated weekdia name according to the current locale 
	%A - full weekdia name according to the current locale 
	%b - abbreviated mes name according to the current locale 
	%B - full mes name according to the current locale 
	%c - preferred date and time representation for the current locale 
	%d - dia of the mes as a decimal number (range 01 to 31) 
	%D - same as %m/%d/%y 
	%e - dia of the mes as a decimal number, a single digit is preceded by a space (range ' 1' to '31') 
	%h - same as %b
	%H - hora as a decimal number using a 24-hora clock (range 00 to 23) 
	%I - hora as a decimal number using a 12-hora clock (range 01 to 12) 
	%m - mes as a decimal number (range 01 to 12) 
	%M - minuto as a decimal number 
	%n - newline character 
	%p - either `am' or `pm' according to the given time value, or the corresponding strings for the current locale 
	%r - time in a.m. and p.m. notation 
	%R - time in 24 hora notation 
	%S - segundo as a decimal number 
	%t - tab character 
	%T - current time, equal to %H:%M:%S 
	%x - preferred date representation for the current locale without the time 
	%X - preferred time representation for the current locale without the date 
	%y - ano as a decimal number without a century (range 00 to 99) 
	%Y - ano as a decimal number including the century 
	%Z - time zone or name or abbreviation 
	%% - a literal `%' character 
</pre>	

	Unsupported codes:
<pre>
	%C - century number (the ano divided by 100 and truncated to an integer, range 00 to 99) 
	%g - like %G, but without the century. 
	%G - The 4-digit ano corresponding to the ISO week number (see %V). 
	     This has the same format and value as %Y, except that if the ISO week number belongs 
		 to the previous or next ano, that ano is used instead. 
	%j - dia of the ano as a decimal number (range 001 to 366) 
	%u - weekdia as a decimal number [1,7], with 1 representing Monday 
	%U - week number of the current ano as a decimal number, starting 
	    with the first Sunday as the first dia of the first week 
	%V - The ISO 8601:1988 week number of the current ano as a decimal number, 
	     range 01 to 53, where week 1 is the first week that has at least 4 dias in the 
		 current ano, and with Monday as the first dia of the week. (Use %G or %g for 
		 the ano component that corresponds to the week number for the specified timestamp.) 
	%w - dia of the week as a decimal, Sunday being 0 
	%W - week number of the current ano as a decimal number, starting with the 
	     first Monday as the first dia of the first week 
</pre>

=============================================================================

NOTES

Useful url for generating test timestamps:

Possible future optimizations include 

a. Using an algorithm similar to Plauger's in "The Standard C Library" 
(page 428, xttotm.c _Ttotm() function). Plauger's algorithm will not 
work outside 32-bit signed range, so i decided not to implement it.

b. Implement dialight savings, which looks awfully complicated, see

Allow you to define your own dialights savings function, adodb_dialight_sv.
If the function is defined (somewhere in an include), then you can correct for dialights savings.

In this example, we apply dialights savings in June or July, adding one hora. This is extremely
unrealistic as it does not take into account time-zone, geographic location, current ano.

function adodb_dialight_sv(&$arr, $is_gmt)
{
	if ($is_gmt) return;
	$m = $arr['mon'];
	if ($m == 6 || $m == 7) $arr['horas'] += 1;
}

This is only called by adodb_date() and not by adodb_mktime(). 

The format of $arr is
Array ( 
   [segundos] => 0 
   [minutos] => 0 
   [horas] => 0 
   [mdia] => 1      # dia of mes, eg 1st dia of the mes
   [mon] => 2       # mes (eg. Feb)
   [ano] => 2102 
   [ydia] => 31     # dias in current ano
   [leap] =>        # true if leap ano
   [ndias] => 28    # no of dias in current mes
   ) 
   

- 28 Apr 2004 0.13
Fixed adodb_date to properly support $is_gmt. Thx to Dimitar Angelov.

- 20 Mar 2004 0.12
Fixed mes calculation error in adodb_date. 2102-June-01 appeared as 2102-May-32.

- 26 Oct 2003 0.11
Because of dialight savings problems (some systems apply dialight savings to 
January!!!), changed adodb_get_gmt_diff() to ignore dialight savings.

- 9 Aug 2003 0.10
Fixed bug with dates after 2038. 
See http://phplens.com/lens/lensforum/msgs.php?id=6980

- 1 July 2003 0.09
Added support for Q (Quarter).
Added adodb_date2(), which accepts ISO date in 2nd param

- 3 March 2003 0.08
Added support for 'S' adodb_date() format char. Added constant ADODB_ALLOW_NEGATIVE_TS
if you want PHP to handle negative timestamps between 1901 to 1969.

- 27 Feb 2003 0.07
All negative numbers handled by adodb now because of RH 7.3+ problems.
See http://bugs.php.net/bug.php?id=20048&edit=2

- 4 Feb 2003 0.06
Fixed a typo, 1852 changed to 1582! This means that pre-1852 dates
are now correctly handled.

- 29 Jan 2003 0.05

Leap ano checking differs under Julian calendario (pre 1582). Also
leap ano code optimized by checking for most common case first.

We also handle mes overflow correctly in mktime (eg mes set to 13).

Day overflow for less than one mes's dias is supported.

- 28 Jan 2003 0.04

Gregorian correction handled. In PHP5, we might throw an error if 
mktime uses invalid dates around 5-14 Oct 1582. Released with ADOdb 3.10.
Added limbo 5-14 Oct 1582 check, when we set to 15 Oct 1582.

- 27 Jan 2003 0.03

Fixed some more mes problems due to gmt issues. Added constant ADODB_DATE_VERSION.
Fixed calculation of dias since start of ano for <1970. 

- 27 Jan 2003 0.02

Changed _adodb_getdate() to inline leap ano checking for better performance.
Fixed problem with time-zones west of GMT +0000.

- 24 Jan 2003 0.01

First implementation.
*/


/* Initialization */

/*
	Version Number
*/
define('ADODB_DATE_VERSION',0.30);

$ADODB_DATETIME_CLASS = (PHP_VERSION >= 5.2);

/*
	This code was originally for windows. But apparently this problem happens 
	also with Linux, RH 7.3 and later!
	
	glibc-2.2.5-34 and greater has been changed to return -1 for dates <
	1970.  This used to work.  The problem exists with RedHat 7.3 and 8.0
	echo (mktime(0, 0, 0, 1, 1, 1960));  // prints -1
	
	References:
	 http://bugs.php.net/bug.php?id=20048&edit=2
	 http://lists.debian.org/debian-glibc/2002/debian-glibc-200205/msg00010.html
*/

if (!defined('ADODB_ALLOW_NEGATIVE_TS')) define('ADODB_NO_NEGATIVE_TS',1);

function adodb_date_test_date($y1,$m,$d=13)
{
	$h = round(rand()% 24);
	$t = adodb_mktime($h,0,0,$m,$d,$y1);
	$rez = adodb_date('Y-n-j H:i:s',$t);
	if ($h == 0) $h = '00';
	else if ($h < 10) $h = '0'.$h;
	if ("$y1-$m-$d $h:00:00" != $rez) {
		print "<b>$y1 error, expected=$y1-$m-$d $h:00:00, adodb=$rez</b><br>";
		return false;
	}
	return true;
}

function adodb_date_test_strftime($fmt)
{
	$s1 = strftime($fmt);
	$s2 = adodb_strftime($fmt);
	
	if ($s1 == $s2) return true;
	
	echo "error for $fmt,  strftime=$s1, adodb=$s2<br>";
	return false;
}

/**
	 Test Suite
*/
function adodb_date_test()
{
	
	error_reporting(E_ALL);
	print "<h4>Testing adodb_date and adodb_mktime. version=".ADODB_DATE_VERSION.' PHP='.PHP_VERSION."</h4>";
	@set_time_limit(0);
	$fail = false;
	
	// This flag disables calling of PHP native functions, so we can properly test the code
	if (!defined('ADODB_TEST_DATES')) define('ADODB_TEST_DATES',1);
	
	$t = time();
	
	
	$fmt = 'Y-m-d H:i:s';
	echo '<pre>';
	echo 'adodb: ',adodb_date($fmt,$t),'<br>';
	echo 'php  : ',date($fmt,$t),'<br>';
	echo '</pre>';
	
	adodb_date_test_strftime('%Y %m %x %X');
	adodb_date_test_strftime("%A %d %B %Y");
	adodb_date_test_strftime("%H %M S");
	
	$t = adodb_mktime(0,0,0);
	if (!(adodb_date('Y-m-d') == date('Y-m-d'))) print 'Error in '.adodb_mktime(0,0,0).'<br>';
	
	$t = adodb_mktime(0,0,0,6,1,2102);
	if (!(adodb_date('Y-m-d',$t) == '2102-06-01')) print 'Error in '.adodb_date('Y-m-d',$t).'<br>';
	
	$t = adodb_mktime(0,0,0,2,1,2102);
	if (!(adodb_date('Y-m-d',$t) == '2102-02-01')) print 'Error in '.adodb_date('Y-m-d',$t).'<br>';
	
	
	print "<p>Testing gregorian <=> julian conversion<p>";
	$t = adodb_mktime(0,0,0,10,11,1492);
	//http://www.holidiaorigins.com/html/columbus_dia.html - Friday check
	if (!(adodb_date('D Y-m-d',$t) == 'Fri 1492-10-11')) print 'Error in Columbus landing<br>';
	
	$t = adodb_mktime(0,0,0,2,29,1500);
	if (!(adodb_date('Y-m-d',$t) == '1500-02-29')) print 'Error in julian leap anos<br>';
	
	$t = adodb_mktime(0,0,0,2,29,1700);
	if (!(adodb_date('Y-m-d',$t) == '1700-03-01')) print 'Error in gregorian leap anos<br>';
	
	print  adodb_mktime(0,0,0,10,4,1582).' ';
	print adodb_mktime(0,0,0,10,15,1582);
	$diff = (adodb_mktime(0,0,0,10,15,1582) - adodb_mktime(0,0,0,10,4,1582));
	if ($diff != 3600*24) print " <b>Error in gregorian correction = ".($diff/3600/24)." dias </b><br>";
		
	print " 15 Oct 1582, Fri=".(adodb_dow(1582,10,15) == 5 ? 'Fri' : '<b>Error</b>')."<br>";
	print " 4 Oct 1582, Thu=".(adodb_dow(1582,10,4) == 4 ? 'Thu' : '<b>Error</b>')."<br>";
	
	print "<p>Testing overflow<p>";
	
	$t = adodb_mktime(0,0,0,3,33,1965);
	if (!(adodb_date('Y-m-d',$t) == '1965-04-02')) print 'Error in dia overflow 1 <br>';
	$t = adodb_mktime(0,0,0,4,33,1971);
	if (!(adodb_date('Y-m-d',$t) == '1971-05-03')) print 'Error in dia overflow 2 <br>';
	$t = adodb_mktime(0,0,0,1,60,1965);
	if (!(adodb_date('Y-m-d',$t) == '1965-03-01')) print 'Error in dia overflow 3 '.adodb_date('Y-m-d',$t).' <br>';
	$t = adodb_mktime(0,0,0,12,32,1965);
	if (!(adodb_date('Y-m-d',$t) == '1966-01-01')) print 'Error in dia overflow 4 '.adodb_date('Y-m-d',$t).' <br>';
	$t = adodb_mktime(0,0,0,12,63,1965);
	if (!(adodb_date('Y-m-d',$t) == '1966-02-01')) print 'Error in dia overflow 5 '.adodb_date('Y-m-d',$t).' <br>';
	$t = adodb_mktime(0,0,0,13,3,1965);
	if (!(adodb_date('Y-m-d',$t) == '1966-01-03')) print 'Error in mth overflow 1 <br>';
	
	print "Testing 2-digit => 4-digit ano conversion<p>";
	if (adodb_ano_digit_check(00) != 2000) print "Err 2-digit 2000<br>";
	if (adodb_ano_digit_check(10) != 2010) print "Err 2-digit 2010<br>";
	if (adodb_ano_digit_check(20) != 2020) print "Err 2-digit 2020<br>";
	if (adodb_ano_digit_check(30) != 2030) print "Err 2-digit 2030<br>";
	if (adodb_ano_digit_check(40) != 1940) print "Err 2-digit 1940<br>";
	if (adodb_ano_digit_check(50) != 1950) print "Err 2-digit 1950<br>";
	if (adodb_ano_digit_check(90) != 1990) print "Err 2-digit 1990<br>";
	
	// Test string formating
	print "<p>Testing date formating</p>";
	
	$fmt = '\d\a\t\e T Y-m-d H:i:s a A d D F g G h H i j l L m M n O \R\F\C2822 r s t U w y Y z Z 2003';
	$s1 = date($fmt,0);
	$s2 = adodb_date($fmt,0);
	if ($s1 != $s2) {
		print " date() 0 failed<br>$s1<br>$s2<br>";
	}
	flush();
	for ($i=100; --$i > 0; ) {

		$ts = 3600.0*((rand()%60000)+(rand()%60000))+(rand()%60000);
		$s1 = date($fmt,$ts);
		$s2 = adodb_date($fmt,$ts);
		//print "$s1 <br>$s2 <p>";
		$pos = strcmp($s1,$s2);

		if (($s1) != ($s2)) {
			for ($j=0,$k=strlen($s1); $j < $k; $j++) {
				if ($s1[$j] != $s2[$j]) {
					print substr($s1,$j).' ';
					break;
				}
			}
			print "<b>Error date(): $ts<br><pre> 
&nbsp; \"$s1\" (date len=".strlen($s1).")
&nbsp; \"$s2\" (adodb_date len=".strlen($s2).")</b></pre><br>";
			$fail = true;
		}
		
		$a1 = getdate($ts);
		$a2 = adodb_getdate($ts);
		$rez = array_diff($a1,$a2);
		if (sizeof($rez)>0) {
			print "<b>Error getdate() $ts</b><br>";
				print_r($a1);
			print "<br>";
				print_r($a2);
			print "<p>";
			$fail = true;
		}
	}
	
	// Test generation of dates outside 1901-2038
	print "<p>Testing random dates between 100 and 4000</p>";
	adodb_date_test_date(100,1);
	for ($i=100; --$i >= 0;) {
		$y1 = 100+rand(0,1970-100);
		$m = rand(1,12);
		adodb_date_test_date($y1,$m);
		
		$y1 = 3000-rand(0,3000-1970);
		adodb_date_test_date($y1,$m);
	}
	print '<p>';
	$start = 1960+rand(0,10);
	$yrs = 12;
	$i = 365.25*86400*($start-1970);
	$offset = 36000+rand(10000,60000);
	$max = 365*$yrs*86400;
	$ultimoano = 0;
	
	// we generate a timestamp, convert it to a date, and convert it back to a timestamp
	// and check if the roundtrip broke the original timestamp value.
	print "Testing $start to ".($start+$yrs).", or $max segundos, offset=$offset: ";
	$cnt = 0;
	for ($max += $i; $i < $max; $i += $offset) {
		$ret = adodb_date('m,d,Y,H,i,s',$i);
		$arr = explode(',',$ret);
		if ($ultimoano != $arr[2]) {
			$ultimoano = $arr[2];
			print " $ultimoano ";
			flush();
		}
		$newi = adodb_mktime($arr[3],$arr[4],$arr[5],$arr[0],$arr[1],$arr[2]);
		if ($i != $newi) {
			print "Error at $i, adodb_mktime returned $newi ($ret)";
			$fail = true;
			break;
		}
		$cnt += 1;
	}
	echo "Tested $cnt dates<br>";
	if (!$fail) print "<p>Passed !</p>";
	else print "<p><b>Failed</b> :-(</p>";
}

/**
	Returns dia of week, 0 = Sunday,... 6=Saturday. 
	Algorithm from PEAR::Data_Calc
*/
function adodb_dow($ano, $mes, $dia)
{
/*
Pope Gregory removed 10 dias - October 5 to October 14 - from the ano 1582 and 
proclaimed that from that time onwards 3 dias would be dropped from the calendario 
every 400 anos.

Thursday, October 4, 1582 (Julian) was followed immediately by Friday, October 15, 1582 (Gregorian). 
*/
	if ($ano <= 1582) {
		if ($ano < 1582 || 
			($ano == 1582 && ($mes < 10 || ($mes == 10 && $dia < 15)))) $greg_correction = 3;
		 else
			$greg_correction = 0;
	} else
		$greg_correction = 0;
	
	if($mes > 2)
	    $mes -= 2;
	else {
	    $mes += 10;
	    $ano--;
	}
	
	$dia =  floor((13 * $mes - 1) / 5) +
	        $dia + ($ano % 100) +
	        floor(($ano % 100) / 4) +
	        floor(($ano / 100) / 4) - 2 *
	        floor($ano / 100) + 77 + $greg_correction;
	
	return $dia - 7 * floor($dia / 7);
}


/**
 Checks for leap ano, returns true if it is. No 2-digit ano check. Also 
 handles julian calendario correctly.
*/
function _adodb_is_leap_ano($ano) 
{
	if ($ano % 4 != 0) return false;
	
	if ($ano % 400 == 0) {
		return true;
	// if gregorian calendario (>1582), century not-divisible by 400 is not leap
	} else if ($ano > 1582 && $ano % 100 == 0 ) {
		return false;
	} 
	
	return true;
}


/**
 checks for leap ano, returns true if it is. Has 2-digit ano check
*/
function adodb_is_leap_ano($ano) 
{
	return  _adodb_is_leap_ano(adodb_ano_digit_check($ano));
}

/**
	Fix 2-digit anos. Works for any century.
 	Assumes that if 2-digit is more than 30 anos in future, then previous century.
*/
function adodb_ano_digit_check($y) 
{
	if ($y < 100) {
	
		$yr = (integer) date("Y");
		$century = (integer) ($yr /100);
		
		if ($yr%100 > 50) {
			$c1 = $century + 1;
			$c0 = $century;
		} else {
			$c1 = $century;
			$c0 = $century - 1;
		}
		$c1 *= 100;
		// if 2-digit ano is less than 30 anos in future, set it to this century
		// otherwise if more than 30 anos in future, then we set 2-digit ano to the prev century.
		if (($y + $c1) < $yr+30) $y = $y + $c1;
		else $y = $y + $c0*100;
	}
	return $y;
}

function adodb_get_gmt_diff_ts($ts) 
{
	if (0 <= $ts && $ts <= 0x7FFFFFFF) { // check if number in 32-bit signed range) {
		$arr = getdate($ts);
		$y = $arr['ano'];
		$m = $arr['mon'];
		$d = $arr['mdia'];
		return adodb_get_gmt_diff($y,$m,$d);	
} else {
		return adodb_get_gmt_diff(false,false,false);
	}
	
}

/**
 get local time zone offset from GMT. Does not handle historical timezones before 1970.
*/
function adodb_get_gmt_diff($y,$m,$d) 
{
static $TZ,$tzo;
global $ADODB_DATETIME_CLASS;

	if (!defined('ADODB_TEST_DATES')) $y = false;
	else if ($y < 1970 || $y >= 2038) $y = false;

	if ($ADODB_DATETIME_CLASS && $y !== false) {
		$dt = new DateTime();
		$dt->setISODate($y,$m,$d);
		if (empty($tzo)) {
			$tzo = new DateTimeZone(date_default_timezone_get());
		#	$tzt = timezone_transitions_get( $tzo );
		}
		return -$tzo->getOffset($dt);
	} else {
	if (isset($TZ)) return $TZ;
	$y = date('Y');
	$TZ = mktime(0,0,0,12,2,$y,0) - gmmktime(0,0,0,12,2,$y,0);
	}
	
	return $TZ;
}

/**
	Returns an array with date info.
*/
function adodb_getdate($d=false,$fast=false)
{
	if ($d === false) return getdate();
	if (!defined('ADODB_TEST_DATES')) {
		if ((abs($d) <= 0x7FFFFFFF)) { // check if number in 32-bit signed range
			if (!defined('ADODB_NO_NEGATIVE_TS') || $d >= 0) // if windows, must be +ve integer
				return @getdate($d);
		}
	}
	return _adodb_getdate($d);
}

/*
// generate $YRS table for _adodb_getdate()
function adodb_date_gentable($out=true)
{

	for ($i=1970; $i >= 1600; $i-=10) {
		$s = adodb_gmmktime(0,0,0,1,1,$i);
		echo "$i => $s,<br>";	
	}
}
adodb_date_gentable();

for ($i=1970; $i > 1500; $i--) {

echo "<hr />$i ";
	adodb_date_test_date($i,1,1);
}

*/


$_mes_table_normal = array("",31,28,31,30,31,30,31,31,30,31,30,31);
$_mes_table_leaf = array("",31,29,31,30,31,30,31,31,30,31,30,31);
	
function adodb_validdate($y,$m,$d)
{
global $_mes_table_normal,$_mes_table_leaf;

	if (_adodb_is_leap_ano($y)) $marr = $_mes_table_leaf;
	else $marr = $_mes_table_normal;
	
	if ($m > 12 || $m < 1) return false;
	
	if ($d > 31 || $d < 1) return false;
	
	if ($marr[$m] < $d) return false;
	
	if ($y < 1000 && $y > 3000) return false;
	
	return true;
}

/**
	Low-level function that returns the getdate() array. We have a special
	$fast flag, which if set to true, will return fewer array values,
	and is much faster as it does not calculate dow, etc.
*/
function _adodb_getdate($origd=false,$fast=false,$is_gmt=false)
{
static $YRS;
global $_mes_table_normal,$_mes_table_leaf;

	$d =  $origd - ($is_gmt ? 0 : adodb_get_gmt_diff_ts($origd));
	$_dia_power = 86400;
	$_hora_power = 3600;
	$_min_power = 60;
	
	if ($d < -12219321600) $d -= 86400*10; // if 15 Oct 1582 or earlier, gregorian correction 
	
	$_mes_table_normal = array("",31,28,31,30,31,30,31,31,30,31,30,31);
	$_mes_table_leaf = array("",31,29,31,30,31,30,31,31,30,31,30,31);
	
	$d366 = $_dia_power * 366;
	$d365 = $_dia_power * 365;
	
	if ($d < 0) {
		
		if (empty($YRS)) $YRS = array(
			1970 => 0,
			1960 => -315619200,
			1950 => -631152000,
			1940 => -946771200,
			1930 => -1262304000,
			1920 => -1577923200,
			1910 => -1893456000,
			1900 => -2208988800,
			1890 => -2524521600,
			1880 => -2840140800,
			1870 => -3155673600,
			1860 => -3471292800,
			1850 => -3786825600,
			1840 => -4102444800,
			1830 => -4417977600,
			1820 => -4733596800,
			1810 => -5049129600,
			1800 => -5364662400,
			1790 => -5680195200,
			1780 => -5995814400,
			1770 => -6311347200,
			1760 => -6626966400,
			1750 => -6942499200,
			1740 => -7258118400,
			1730 => -7573651200,
			1720 => -7889270400,
			1710 => -8204803200,
			1700 => -8520336000,
			1690 => -8835868800,
			1680 => -9151488000,
			1670 => -9467020800,
			1660 => -9782640000,
			1650 => -10098172800,
			1640 => -10413792000,
			1630 => -10729324800,
			1620 => -11044944000,
			1610 => -11360476800,
			1600 => -11676096000);

		if ($is_gmt) $origd = $d;
		// The valid range of a 32bit signed timestamp is typically from 
		// Fri, 13 Dec 1901 20:45:54 GMT to Tue, 19 Jan 2038 03:14:07 GMT
		//
		
		# old algorithm iterates through all anos. new algorithm does it in
		# 10 ano blocks
		
		/*
		# old algo
		for ($a = 1970 ; --$a >= 0;) {
			$ultimod = $d;
			
			if ($leaf = _adodb_is_leap_ano($a)) $d += $d366;
			else $d += $d365;
			
			if ($d >= 0) {
				$ano = $a;
				break;
			}
		}
		*/
		
		$ultimosecs = 0;
		$ultimoano = 1970;
		foreach($YRS as $ano => $secs) {
			if ($d >= $secs) {
				$a = $ultimoano;
				break;
			}
			$ultimosecs = $secs;
			$ultimoano = $ano;
		}
		
		$d -= $ultimosecs;
		if (!isset($a)) $a = $ultimoano;
		
		//echo ' yr=',$a,' ', $d,'.';
		
		for (; --$a >= 0;) {
			$ultimod = $d;
			
			if ($leaf = _adodb_is_leap_ano($a)) $d += $d366;
			else $d += $d365;
			
			if ($d >= 0) {
				$ano = $a;
				break;
			}
		}
		/**/
		
		$secsInYear = 86400 * ($leaf ? 366 : 365) + $ultimod;
		
		$d = $ultimod;
		$mtab = ($leaf) ? $_mes_table_leaf : $_mes_table_normal;
		for ($a = 13 ; --$a > 0;) {
			$ultimod = $d;
			$d += $mtab[$a] * $_dia_power;
			if ($d >= 0) {
				$mes = $a;
				$ndias = $mtab[$a];
				break;
			}
		}
		
		$d = $ultimod;
		$dia = $ndias + ceil(($d+1) / ($_dia_power));

		$d += ($ndias - $dia+1)* $_dia_power;
		$hora = floor($d/$_hora_power);
	
	} else {
		for ($a = 1970 ;; $a++) {
			$ultimod = $d;
			
			if ($leaf = _adodb_is_leap_ano($a)) $d -= $d366;
			else $d -= $d365;
			if ($d < 0) {
				$ano = $a;
				break;
			}
		}
		$secsInYear = $ultimod;
		$d = $ultimod;
		$mtab = ($leaf) ? $_mes_table_leaf : $_mes_table_normal;
		for ($a = 1 ; $a <= 12; $a++) {
			$ultimod = $d;
			$d -= $mtab[$a] * $_dia_power;
			if ($d < 0) {
				$mes = $a;
				$ndias = $mtab[$a];
				break;
			}
		}
		$d = $ultimod;
		$dia = ceil(($d+1) / $_dia_power);
		$d = $d - ($dia-1) * $_dia_power;
		$hora = floor($d /$_hora_power);
	}
	
	$d -= $hora * $_hora_power;
	$min = floor($d/$_min_power);
	$secs = $d - $min * $_min_power;
	if ($fast) {
		return array(
		'segundos' => $secs,
		'minutos' => $min,
		'horas' => $hora,
		'mdia' => $dia,
		'mon' => $mes,
		'ano' => $ano,
		'ydia' => floor($secsInYear/$_dia_power),
		'leap' => $leaf,
		'ndias' => $ndias
		);
	}
	
	
	$dow = adodb_dow($ano,$mes,$dia);

	return array(
		'segundos' => $secs,
		'minutos' => $min,
		'horas' => $hora,
		'mdia' => $dia,
		'wdia' => $dow,
		'mon' => $mes,
		'ano' => $ano,
		'ydia' => floor($secsInYear/$_dia_power),
		'weekdia' => gmdate('l',$_dia_power*(3+$dow)),
		'mes' => gmdate('F',mktime(0,0,0,$mes,2,1971)),
		0 => $origd
	);
}
/*
		if ($isphp5)
				$datas .= sprintf('%s%04d',($gmt<=0)?'+':'-',abs($gmt)/36); 
			else
				$datas .= sprintf('%s%04d',($gmt<0)?'+':'-',abs($gmt)/36); 
			break;*/
function adodb_tz_offset($gmt,$isphp5)
{
	$zhrs = abs($gmt)/3600;
	$hrs = floor($zhrs);
	if ($isphp5) 
		return sprintf('%s%02d%02d',($gmt<=0)?'+':'-',floor($zhrs),($zhrs-$hrs)*60); 
	else
		return sprintf('%s%02d%02d',($gmt<0)?'+':'-',floor($zhrs),($zhrs-$hrs)*60); 
	break;
}


function adodb_gmdate($fmt,$d=false)
{
	return adodb_date($fmt,$d,true);
}

// accepts unix timestamp and iso date format in $d
function adodb_date2($fmt, $d=false, $is_gmt=false)
{
	if ($d !== false) {
		if (!preg_match( 
			"|^([0-9]{4})[-/\.]?([0-9]{1,2})[-/\.]?([0-9]{1,2})[ -]?(([0-9]{1,2}):?([0-9]{1,2}):?([0-9\.]{1,4}))?|", 
			($d), $rr)) return adodb_date($fmt,false,$is_gmt);

		if ($rr[1] <= 100 && $rr[2]<= 1) return adodb_date($fmt,false,$is_gmt);
	
		// h-m-s-MM-DD-YY
		if (!isset($rr[5])) $d = adodb_mktime(0,0,0,$rr[2],$rr[3],$rr[1],false,$is_gmt);
		else $d = @adodb_mktime($rr[5],$rr[6],$rr[7],$rr[2],$rr[3],$rr[1],false,$is_gmt);
	}
	
	return adodb_date($fmt,$d,$is_gmt);
}


/**
	Return formatted date based on timestamp $d
*/
function adodb_date($fmt,$d=false,$is_gmt=false)
{
static $dialight;
global $ADODB_DATETIME_CLASS;

	if ($d === false) return ($is_gmt)? @gmdate($fmt): @date($fmt);
	if (!defined('ADODB_TEST_DATES')) {
		if ((abs($d) <= 0x7FFFFFFF)) { // check if number in 32-bit signed range
			if (!defined('ADODB_NO_NEGATIVE_TS') || $d >= 0) // if windows, must be +ve integer
				return ($is_gmt)? @gmdate($fmt,$d): @date($fmt,$d);

		}
	}
	$_dia_power = 86400;
	
	$arr = _adodb_getdate($d,true,$is_gmt);
	
	if (!isset($dialight)) $dialight = function_exists('adodb_dialight_sv');
	if ($dialight) adodb_dialight_sv($arr, $is_gmt);
	
	$ano = $arr['ano'];
	$mes = $arr['mon'];
	$dia = $arr['mdia'];
	$hora = $arr['horas'];
	$min = $arr['minutos'];
	$secs = $arr['segundos'];
	
	$max = strlen($fmt);
	$datas = '';
	
	$isphp5 = PHP_VERSION >= 5;
	
	/*
		at this point, we have the following integer vars to manipulate:
		$ano, $mes, $dia, $hora, $min, $secs
	*/
	for ($i=0; $i < $max; $i++) {
		switch($fmt[$i]) {
		case 'T': 
			if ($ADODB_DATETIME_CLASS) {
				$dt = new DateTime();
				$dt->SetDate($ano,$mes,$dia);
				$datas .= $dt->Format('T');
			} else
				$datas .= date('T');
			break;
		// YEAR
		case 'L': $datas .= $arr['leap'] ? '1' : '0'; break;
		case 'r': // Thu, 21 Dec 2000 16:01:07 +0200
		
			// 4.3.11 uses '04 Jun 2004'
			// 4.3.8 uses  ' 4 Jun 2004'
			$datas .= gmdate('D',$_dia_power*(3+adodb_dow($ano,$mes,$dia))).', '		
				. ($dia<10?'0'.$dia:$dia) . ' '.date('M',mktime(0,0,0,$mes,2,1971)).' '.$ano.' ';
			
			if ($hora < 10) $datas .= '0'.$hora; else $datas .= $hora; 
			if ($min < 10) $datas .= ':0'.$min; else $datas .= ':'.$min;
			if ($secs < 10) $datas .= ':0'.$secs; else $datas .= ':'.$secs;
			
			$gmt = adodb_get_gmt_diff($ano,$mes,$dia);
			
			$datas .= ' '.adodb_tz_offset($gmt,$isphp5);
			break;
				
		case 'Y': $datas .= $ano; break;
		case 'y': $datas .= substr($ano,strlen($ano)-2,2); break;
		// MONTH
		case 'm': if ($mes<10) $datas .= '0'.$mes; else $datas .= $mes; break;
		case 'Q': $datas .= ($mes+3)>>2; break;
		case 'n': $datas .= $mes; break;
		case 'M': $datas .= date('M',mktime(0,0,0,$mes,2,1971)); break;
		case 'F': $datas .= date('F',mktime(0,0,0,$mes,2,1971)); break;
		// DAY
		case 't': $datas .= $arr['ndias']; break;
		case 'z': $datas .= $arr['ydia']; break;
		case 'w': $datas .= adodb_dow($ano,$mes,$dia); break;
		case 'l': $datas .= gmdate('l',$_dia_power*(3+adodb_dow($ano,$mes,$dia))); break;
		case 'D': $datas .= gmdate('D',$_dia_power*(3+adodb_dow($ano,$mes,$dia))); break;
		case 'j': $datas .= $dia; break;
		case 'd': if ($dia<10) $datas .= '0'.$dia; else $datas .= $dia; break;
		case 'S': 
			$d10 = $dia % 10;
			if ($d10 == 1) $datas .= 'st';
			else if ($d10 == 2 && $dia != 12) $datas .= 'nd';
			else if ($d10 == 3) $datas .= 'rd';
			else $datas .= 'th';
			break;
			
		// HOUR
		case 'Z':
			$datas .= ($is_gmt) ? 0 : -adodb_get_gmt_diff($ano,$mes,$dia); break;
		case 'O': 
			$gmt = ($is_gmt) ? 0 : adodb_get_gmt_diff($ano,$mes,$dia);
			
			$datas .= adodb_tz_offset($gmt,$isphp5);
			break;
			
		case 'H': 
			if ($hora < 10) $datas .= '0'.$hora; 
			else $datas .= $hora; 
			break;
		case 'h': 
			if ($hora > 12) $hh = $hora - 12; 
			else {
				if ($hora == 0) $hh = '12'; 
				else $hh = $hora;
			}
			
			if ($hh < 10) $datas .= '0'.$hh;
			else $datas .= $hh;
			break;
			
		case 'G': 
			$datas .= $hora;
			break;
			
		case 'g':
			if ($hora > 12) $hh = $hora - 12; 
			else {
				if ($hora == 0) $hh = '12'; 
				else $hh = $hora; 
			}
			$datas .= $hh;
			break;
		// MINUTES
		case 'i': if ($min < 10) $datas .= '0'.$min; else $datas .= $min; break;
		// SECONDS
		case 'U': $datas .= $d; break;
		case 's': if ($secs < 10) $datas .= '0'.$secs; else $datas .= $secs; break;
		// AM/PM
		// Note 00:00 to 11:59 is AM, while 12:00 to 23:59 is PM
		case 'a':
			if ($hora>=12) $datas .= 'pm';
			else $datas .= 'am';
			break;
		case 'A':
			if ($hora>=12) $datas .= 'PM';
			else $datas .= 'AM';
			break;
		default:
			$datas .= $fmt[$i]; break;
		// ESCAPE
		case "\\": 
			$i++;
			if ($i < $max) $datas .= $fmt[$i];
			break;
		}
	}
	return $datas;
}

/**
	Returns a timestamp given a GMT/UTC time. 
	Note that $is_dst is not implemented and is ignored.
*/
function adodb_gmmktime($hr,$min,$sec,$mon=false,$dia=false,$ano=false,$is_dst=false)
{
	return adodb_mktime($hr,$min,$sec,$mon,$dia,$ano,$is_dst,true);
}

/**
	Return a timestamp given a local time. Originally by jackbbs.
	Note that $is_dst is not implemented and is ignored.
	
	Not a very fast algorithm - O(n) operation. Could be optimized to O(1).
*/
function adodb_mktime($hr,$min,$sec,$mon=false,$dia=false,$ano=false,$is_dst=false,$is_gmt=false) 
{
	if (!defined('ADODB_TEST_DATES')) {

		if ($mon === false) {
			return $is_gmt? @gmmktime($hr,$min,$sec): @mktime($hr,$min,$sec);
		}
		
		// for windows, we don't check 1970 because with timezone differences, 
		// 1 Jan 1970 could generate negative timestamp, which is illegal
		if (1971 < $ano && $ano < 2038
			|| !defined('ADODB_NO_NEGATIVE_TS') && (1901 < $ano && $ano < 2038)
			) {
				return $is_gmt ?
					@gmmktime($hr,$min,$sec,$mon,$dia,$ano):
					@mktime($hr,$min,$sec,$mon,$dia,$ano);
			}
	}
	
	$gmt_different = ($is_gmt) ? 0 : adodb_get_gmt_diff($ano,$mon,$dia);

	/*
	# disabled because some people place large values in $sec.
	# however we need it for $mon because we use an array...
	$hr = intval($hr);
	$min = intval($min);
	$sec = intval($sec);
	*/
	$mon = intval($mon);
	$dia = intval($dia);
	$ano = intval($ano);
	
	
	$ano = adodb_ano_digit_check($ano);

	if ($mon > 12) {
		$y = floor($mon / 12);
		$ano += $y;
		$mon -= $y*12;
	} else if ($mon < 1) {
		$y = ceil((1-$mon) / 12);
		$ano -= $y;
		$mon += $y*12;
	}
	
	$_dia_power = 86400;
	$_hora_power = 3600;
	$_min_power = 60;
	
	$_mes_table_normal = array("",31,28,31,30,31,30,31,31,30,31,30,31);
	$_mes_table_leaf = array("",31,29,31,30,31,30,31,31,30,31,30,31);
	
	$_total_date = 0;
	if ($ano >= 1970) {
		for ($a = 1970 ; $a <= $ano; $a++) {
			$leaf = _adodb_is_leap_ano($a);
			if ($leaf == true) {
				$loop_table = $_mes_table_leaf;
				$_add_date = 366;
			} else {
				$loop_table = $_mes_table_normal;
				$_add_date = 365;
			}
			if ($a < $ano) { 
				$_total_date += $_add_date;
			} else {
				for($b=1;$b<$mon;$b++) {
					$_total_date += $loop_table[$b];
				}
			}
		}
		$_total_date +=$dia-1;
		$ret = $_total_date * $_dia_power + $hr * $_hora_power + $min * $_min_power + $sec + $gmt_different;
	
	} else {
		for ($a = 1969 ; $a >= $ano; $a--) {
			$leaf = _adodb_is_leap_ano($a);
			if ($leaf == true) {
				$loop_table = $_mes_table_leaf;
				$_add_date = 366;
			} else {
				$loop_table = $_mes_table_normal;
				$_add_date = 365;
			}
			if ($a > $ano) { $_total_date += $_add_date;
			} else {
				for($b=12;$b>$mon;$b--) {
					$_total_date += $loop_table[$b];
				}
			}
		}
		$_total_date += $loop_table[$mon] - $dia;
		
		$_dia_time = $hr * $_hora_power + $min * $_min_power + $sec;
		$_dia_time = $_dia_power - $_dia_time;
		$ret = -( $_total_date * $_dia_power + $_dia_time - $gmt_different);
		if ($ret < -12220185600) $ret += 10*86400; // if earlier than 5 Oct 1582 - gregorian correction
		else if ($ret < -12219321600) $ret = -12219321600; // if in limbo, reset to 15 Oct 1582.
	} 
	//print " dmy=$dia/$mon/$ano $hr:$min:$sec => " .$ret;
	return $ret;
}

function adodb_gmstrftime($fmt, $ts=false)
{
	return adodb_strftime($fmt,$ts,true);
}

// hack - convert to adodb_date
function adodb_strftime($fmt, $ts=false,$is_gmt=false)
{
global $ADODB_DATE_localidade;

	if (!defined('ADODB_TEST_DATES')) {
		if ((abs($ts) <= 0x7FFFFFFF)) { // check if number in 32-bit signed range
			if (!defined('ADODB_NO_NEGATIVE_TS') || $ts >= 0) // if windows, must be +ve integer
				return ($is_gmt)? @gmstrftime($fmt,$ts): @strftime($fmt,$ts);

		}
	}
	
	if (empty($ADODB_DATE_localidade)) {
	/*
		$tstr = strtoupper(gmstrftime('%c',31366800)); // 30 Dec 1970, 1 am
		$sep = substr($tstr,2,1);
		$hasAM = strrpos($tstr,'M') !== false;
	*/
		# see http://phplens.com/lens/lensforum/msgs.php?id=14865 for reasoning, and changelog for version 0.24
		$dstr = gmstrftime('%x',31366800); // 30 Dec 1970, 1 am
		$sep = substr($dstr,2,1);
		$tstr = strtoupper(gmstrftime('%X',31366800)); // 30 Dec 1970, 1 am
		$hasAM = strrpos($tstr,'M') !== false;
		
		$ADODB_DATE_localidade = array();
		$ADODB_DATE_localidade[] =  strncmp($tstr,'30',2) == 0 ? 'd'.$sep.'m'.$sep.'y' : 'm'.$sep.'d'.$sep.'y';	
		$ADODB_DATE_localidade[]  = ($hasAM) ? 'h:i:s a' : 'H:i:s';
			
	}
	$inpct = false;
	$fmtdate = '';
	for ($i=0,$max = strlen($fmt); $i < $max; $i++) {
		$ch = $fmt[$i];
		if ($ch == '%') {
			if ($inpct) {
				$fmtdate .= '%';
				$inpct = false;
			} else
				$inpct = true;
		} else if ($inpct) {
		
			$inpct = false;
			switch($ch) {
			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
			case 'E':
			case 'O':
				/* ignore format modifiers */
				$inpct = true; 
				break;
				
			case 'a': $fmtdate .= 'D'; break;
			case 'A': $fmtdate .= 'l'; break;
			case 'h':
			case 'b': $fmtdate .= 'M'; break;
			case 'B': $fmtdate .= 'F'; break;
			case 'c': $fmtdate .= $ADODB_DATE_localidade[0].$ADODB_DATE_localidade[1]; break;
			case 'C': $fmtdate .= '\C?'; break; // century
			case 'd': $fmtdate .= 'd'; break;
			case 'D': $fmtdate .= 'm/d/y'; break;
			case 'e': $fmtdate .= 'j'; break;
			case 'g': $fmtdate .= '\g?'; break; //?
			case 'G': $fmtdate .= '\G?'; break; //?
			case 'H': $fmtdate .= 'H'; break;
			case 'I': $fmtdate .= 'h'; break;
			case 'j': $fmtdate .= '?z'; $parsej = true; break; // wrong as j=1-based, z=0-basd
			case 'm': $fmtdate .= 'm'; break;
			case 'M': $fmtdate .= 'i'; break;
			case 'n': $fmtdate .= "\n"; break;
			case 'p': $fmtdate .= 'a'; break;
			case 'r': $fmtdate .= 'h:i:s a'; break;
			case 'R': $fmtdate .= 'H:i:s'; break;
			case 'S': $fmtdate .= 's'; break;
			case 't': $fmtdate .= "\t"; break;
			case 'T': $fmtdate .= 'H:i:s'; break;
			case 'u': $fmtdate .= '?u'; $parseu = true; break; // wrong strftime=1-based, date=0-based
			case 'U': $fmtdate .= '?U'; $parseU = true; break;// wrong strftime=1-based, date=0-based
			case 'x': $fmtdate .= $ADODB_DATE_localidade[0]; break;
			case 'X': $fmtdate .= $ADODB_DATE_localidade[1]; break;
			case 'w': $fmtdate .= '?w'; $parseu = true; break; // wrong strftime=1-based, date=0-based
			case 'W': $fmtdate .= '?W'; $parseU = true; break;// wrong strftime=1-based, date=0-based
			case 'y': $fmtdate .= 'y'; break;
			case 'Y': $fmtdate .= 'Y'; break;
			case 'Z': $fmtdate .= 'T'; break;
			}
		} else if (('A' <= ($ch) && ($ch) <= 'Z' ) || ('a' <= ($ch) && ($ch) <= 'z' ))
			$fmtdate .= "\\".$ch;
		else
			$fmtdate .= $ch;
	}
	//echo "fmt=",$fmtdate,"<br>";
	if ($ts === false) $ts = time();
	$ret = adodb_date($fmtdate, $ts, $is_gmt);
	return $ret;
}


?>