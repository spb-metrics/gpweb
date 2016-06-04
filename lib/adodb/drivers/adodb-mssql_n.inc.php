<?php

/// $Id $

///////////////////////////////////////////////////////////////////////////
//                                                                       //
//                                          //
//                                                                       //
// ADOdb  - Database Abstraction Library for PHP                         //
//          http://adodb.sourceforge.net/                                //
//                                                                       //

// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.com                                            //

/**
*  MSSQL Driver with auto-prepended "N" for correct unicode storage
*  of SQL literal strings. Intended to be used with MSSQL drivers that
*  are sending UCS-2 data to MSSQL (FreeTDS and ODBTP) in order to get
*  true cross-db compatibility from the application point of ver.
*/

// security - hide paths
if (!defined('ADODB_DIR')) die();

// one useful constant
if (!defined('SINGLEQUOTE')) define('SINGLEQUOTE', "'");

include_once(ADODB_DIR.'/drivers/adodb-mssql.inc.php');

class ADODB_mssql_n extends ADODB_mssql {
	var $databaseType = "mssql_n";
	
	function ADODB_mssqlpo()
	{
		ADODB_mssql::ADODB_mssql();
	}

	function _query($sql,$inputarr)
	{
        $sql = $this->_appendN($sql);
		return ADODB_mssql::_query($sql,$inputarr);
	}

    /**
     * This function will intercept all the literals used in the SQL, prepending the "N" char to them
     * in order to allow mssql to store properly data sent in the correct UCS-2 encoding (by freeTDS
     * and ODBTP) keeping SQL compatibility at ADOdb level (instead of hacking every projeto to add
     * the "N" notation when working against MSSQL.
     *
     * Note that this hack only must be used if ALL the char-based columns in your DB are of type nchar,
     * nvarchar and ntext
     */
    function _appendN($sql) {

        $resultado = $sql;

    /// Check we have some single quote in the query. Exit ok.
        if (strpos($sql, SINGLEQUOTE) === false) {
            return $sql;
        }

    /// Check we haven't an odd number of single quotes (this can cause problems below
    /// and should be considered one wrong SQL). Exit with debug info.
        if ((substr_count($sql, SINGLEQUOTE) & 1)) {
            if ($this->debug) {
                ADOConnection::outp("{$this->databaseType} internal transformation: not converted. Wrong number of quotes (odd)");
            }
            return $sql;
        }

    /// Check we haven't any backslash + single quote combination. It should mean wrong
    /// backslashes use (bad magic_quotes_sybase?). Exit with debug info.
        $regexp = '/(\\\\' . SINGLEQUOTE . '[^' . SINGLEQUOTE . '])/';
        if (preg_match($regexp, $sql)) {
            if ($this->debug) {
                ADOConnection::outp("{$this->databaseType} internal transformation: not converted. Found bad use of backslash + single quote");
            }
            return $sql;
        }

    /// Remove pairs of single-quotes
        $pairs = array();
        $regexp = '/(' . SINGLEQUOTE . SINGLEQUOTE . ')/';
        preg_match_all($regexp, $resultado, $list_of_pairs);
        if ($list_of_pairs) {
            foreach (array_unique($list_of_pairs[0]) as $key=>$valor) {
                $pairs['<@#@#@PAIR-'.$key.'@#@#@>'] = $valor;
            }
            if (!empty($pairs)) {
                $resultado = str_replace($pairs, array_keys($pairs), $resultado);
            }
        }

    /// Remove the rest of literals present in the query
        $literals = array();
        $regexp = '/(N?' . SINGLEQUOTE . '.*?' . SINGLEQUOTE . ')/is';
        preg_match_all($regexp, $resultado, $list_of_literals);
        if ($list_of_literals) {
            foreach (array_unique($list_of_literals[0]) as $key=>$valor) {
                $literals['<#@#@#LITERAL-'.$key.'#@#@#>'] = $valor;
            }
            if (!empty($literals)) {
                $resultado = str_replace($literals, array_keys($literals), $resultado);
            }
        }


    /// Analyse literals to prepend the N char to them if their contents aren't numeric
        if (!empty($literals)) {
            foreach ($literals as $key=>$valor) {
                if (!is_numeric(trim($valor, SINGLEQUOTE))) {
                /// Non numeric string, prepend our dear N
                    $literals[$key] = 'N' . trim($valor, 'N'); //Trimming potentially existing previous "N"
                }
            }
        }

    /// Re-apply literals to the text
        if (!empty($literals)) {
            $resultado = str_replace(array_keys($literals), $literals, $resultado);
        }

    /// Any pairs followed by N' must be switched to N' followed by those pairs
    /// (or strings beginning with single quotes will fail)
        $resultado = preg_replace("/((<@#@#@PAIR-(\d+)@#@#@>)+)N'/", "N'$1", $resultado);

    /// Re-apply pairs of single-quotes to the text
        if (!empty($pairs)) {
            $resultado = str_replace(array_keys($pairs), $pairs, $resultado);
        }

    /// Print transformation if debug = on
        if ($resultado != $sql && $this->debug) {
            ADOConnection::outp("{$this->databaseType} internal transformation:<br>{$sql}<br>to<br>{$resultado}");
        }

        return $resultado;
    }
}

class ADORecordset_mssql_n extends ADORecordset_mssql {
	var $databaseType = "mssql_n";
	function ADORecordset_mssql_n($id,$modo=false)
	{
		$this->ADORecordset_mssql($id,$modo);
	}
}
?>