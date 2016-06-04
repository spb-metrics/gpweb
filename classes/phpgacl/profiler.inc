<?php
/********************************************************************************\
 * Copyright (C) Carl Taylor (cjtaylor@adepteo.com)                             *
 * Copyright (C) Torben Nehmer (torben@nehmer.net) for Code Cleanup             *
 *                                                                              *
 * This program is free software; you can redistribute it and/or                *
 * modify it under the terms of the GNU General Public License                  *
 * as published by the Free Software Foundation; either version 2               *
 * of the License, or (at your option) any later version.                       *
 *                                                                              *
 * This program is distributed in the hope that it will be useful,              *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of               *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                *
 * GNU General Public License for more details.                                 *
 *                                                                              *
 * You should have received a copy of the GNU General Public License            *
 * along with this program; if not, write to the Free Software                  *
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.  *
\********************************************************************************/

/// Enable multiple timers to aid profiling of performance over secoes of code
class Profiler {
    var $description;
    var $startTime;
    var $fimTime;
    var $initTime;
    var $cur_timer;
    var $stack;
    var $trail;
    var $trace;
    var $contagem;
    var $running;

    /**
    * Initialise the timer. with the current micro time
    */
    function Profiler( $saida_habilitado=false, $trace_habilitado=false)
    {
        $this->description = array();
        $this->startTime = array();
        $this->endTime = array();
        $this->initTime = 0;
        $this->cur_timer = "";
        $this->stack = array();
        $this->trail = "";
        $this->trace = "";
        $this->contagem = array();
        $this->running = array();
        $this->initTime = $this->getMicroTime();
        $this->output_habilitado = $saida_habilitado;
        $this->trace_habilitado = $trace_habilitado;
        $this->startTimer('unprofiled');
    }

    // Public Methods

    /**
    *   Start an individual timer
    *   This will pause the running timer and place it on a stack.
    *   @param string $nome name of the timer
    *   @param string optional $desc description of the timer
    */
    function startTimer($nome, $desc="" ){
        $this->trace.="start   $nome\n";
        $n=array_push( $this->stack, $this->cur_timer );
        $this->__suspendTimer( $this->stack[$n-1] );
        $this->startTime[$nome] = $this->getMicroTime();
        $this->cur_timer=$nome;
        $this->description[$nome] = $desc;
        if (!array_key_exists($nome,$this->contagem))
            $this->count[$nome] = 1;
        else
            $this->count[$nome]++;
    }

    /**
    *   Stop an individual timer
    *   Restart the timer that was running before this one
    *   @param string $nome name of the timer
    */
    function stopTimer($nome){
        $this->trace.="stop    $nome\n";
        $this->endTime[$nome] = $this->getMicroTime();
        if (!array_key_exists($nome, $this->running))
            $this->running[$nome] = $this->elapsedTime($nome);
        else
            $this->running[$nome] += $this->elapsedTime($nome);
        $this->cur_timer=array_pop($this->stack);
        $this->__resumeTimer($this->cur_timer);
    }

    /**
    *   measure the elapsed time of a timer without stoping the timer if
    *   it is still running
    */
    function elapsedTime($nome){
        // This shouldn't happen, but it does once.
        if (!array_key_exists($nome,$this->startTime))
            return 0;

        if(array_key_exists($nome,$this->endTime)){
            return ($this->endTime[$nome] - $this->startTime[$nome]);
        } else {
            $agora=$this->getMicroTime();
            return ($agora - $this->startTime[$nome]);
        }
    }//end start_time

    /**
    *   Measure the elapsed time since the profile class was initialised
    *
    */
    function elapsedOverall(){
        $oaTime = $this->getMicroTime() - $this->initTime;
        return($oaTime);
    }//end start_time

    /**
    *   print out a log of all the timers that were registered
    *
    */
    function printTimers($habilitado=false)
    {
        if($this->output_habilitado||$habilitado){
            $TimedTotal = 0;
            $tot_perc = 0;
            ksort($this->description);
            print("<pre>\n");
            $oaTime = $this->getMicroTime() - $this->initTime;
            echo"======\n";
            echo "                              PROFILER OUTPUT\n";
            echo"======\n";
            print( "Calls                    Time  Routine\n");
            echo"-----------------------------------------------------------------------------\n";
            while (list ($chave, $val) = each ($this->description)) {
                $t = $this->elapsedTime($chave);
                $total = $this->running[$chave];
                $contagem = $this->count[$chave];
                $TimedTotal += $total;
                $perc = ($total/$oaTime)*100;
                $tot_perc+=$perc;
                printf( "%3d    %3.4f ms (%3.2f %%)  %s\n", $contagem, $total*1000, $perc, $chave);
            }

            echo "\n";

            $missed=$oaTime-$TimedTotal;
            $perc = ($missed/$oaTime)*100;
            $tot_perc+=$perc;
               printf( "       %3.4f ms (%3.2f %%)  %s\n", $missed*1000,$perc, "Missed");

            echo"======\n";

            printf( "       %3.4f ms (%3.2f %%)  %s\n", $oaTime*1000,$tot_perc, "OVERALL TIME");

            echo"======\n";

            print("</pre>");
        }
    }

    function printTrace( $habilitado=false )
    {
        if($this->trace_habilitado||$habilitado){
            print("<pre>");
            print("Trace\n $this->trace\n\n");
            print("</pre>");
        }
    }

    /// Internal Use Only Functions

    /**
    * Get the current time as accuratly as possible
    *
    */
    function getMicroTime(){
        $tmp=split(" ",microtime());
        $rt=$tmp[0]+$tmp[1];
        return $rt;
    }

    /**
    * resume  an individual timer
    *
    */
    function __resumeTimer($nome){
        $this->trace.="resume  $nome\n";
        $this->startTime[$nome] = $this->getMicroTime();
    }

    /**
    *   suspend  an individual timer
    *
    */
    function __suspendTimer($nome){
        $this->trace.="suspend $nome\n";
        $this->endTime[$nome] = $this->getMicroTime();
        if (!array_key_exists($nome, $this->running))
            $this->running[$nome] = $this->elapsedTime($nome);
        else
            $this->running[$nome] += $this->elapsedTime($nome);
    }
}

function profiler_start($nome) {
    if (array_key_exists("midcom_profiler",$GLOBALS))
      $GLOBALS["midcom_profiler"]->startTimer ($nome);
}

function profiler_stop($nome) {
    if (array_key_exists("midcom_profiler",$GLOBALS))
      $GLOBALS["midcom_profiler"]->stopTimer ($nome);
}

?>
