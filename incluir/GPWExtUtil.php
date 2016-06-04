<?php

/*********************************************************************************************************************
 ** Copyright (C) 2008 Sistema GP-Web Ltda - ME.
 ** Contato: http://www.sistemagpweb.com.br
 **          sac@sistemagpweb.com.br
 **
 ** Este arquivo é parte do sistema GPWeb Profissional.
 ** Este software esta registrado no INPI sob o número RS 11802-5 e protegido pelo direito de autor.
 **
 ** É expressamente proibido utilizar este código em parte ou integralmente sem o expresso consentimento do autor.
 **
 ** Usuário: Evandro
 **    Data: 03/09/2015
 * /********************************************************************************************************************/

require_once(BASE_DIR.'/incluir/funcoes_principais.php');

class GPWExtUtil {
    static public function getParam($container, $nomeParametro, $valorPadrao){
        return getParam($container, $nomeParametro, $valorPadrao);
        }

    static public function toUtf8( $data ) {
        if( is_array( $data ) ) {
            $d = array();
            foreach( $data as $k => $v ) {
                if( is_string( $k ) ) {
                    $k = utf8_encode( $k );
                    }
                if( is_string( $v ) ) {
                    $v = utf8_encode( $v );
                    }
                elseif( is_array( $v ) ) {
                    $v = toUtf8( $v );
                    }
                $d[ $k ] = $v;
                }

            return $d;
            }
        if( is_string( $data ) ) {
            return utf8_encode( $data );
            }

        return $data;
        }

    static public function fromUtf8( $data ) {
        if( is_array( $data ) ) {
            $d = array();
            foreach( $data as $k => $v ) {
                if( is_string( $k ) ) {
                    $k = utf8_decode( $k );
                    }
                if( is_string( $v ) ) {
                    $v = utf8_decode( $v );
                    }
                elseif( is_array( $v ) ) {
                    $v = fromUtf8( $v );
                    }
                $d[ $k ] = $v;
                }

            return $d;
            }
        if( is_string( $data ) ) {
            return utf8_decode( $data );
            }

        return $data;
        }

    static public function sendError( $msg, $encode = true ) {
        ob_clean();
        echo json_encode( array( 'success' => false, 'msg' => $encode ? self::toUtf8( $msg ) : $msg ) );
        ob_flush();
        }

    static public function sendSuccess() {
        ob_clean();
        echo json_encode( array( 'success' => true ) );
        ob_flush();
        }

    static public function sendData( $root, $data, $encode = true ) {
        ob_clean();
        echo json_encode( array( 'success' => true, $root => $encode ? self::toUtf8( $data ) : $data ) );
        ob_flush();
        }

    static public function sendList( $countField, $count, $root, $data, $encode = true ) {
        ob_clean();
        echo json_encode( array( 'success' => true, $countField => $count,
                                 $root => $encode ? self::toUtf8( $data ) : $data ) );
        ob_flush();
        }

    static public function jsonDecode( $data, $toArray = false ) {
        if( !isset( $data ) ) {
            return $toArray ? '[]' : '{}';
            }

        return json_decode( get_magic_quotes_gpc() ? stripslashes( $data ) : $data, $toArray );
        }

    static public function extRoute($metodos = null) {
        @header( 'Content-Type: application/json; charset=utf-8' );

        $acao = self::getParam( $_REQUEST, 'f', null );

        if( !$acao || !function_exists( $acao ) ) {
            self::sendError( 'Parâmetros inválidos.' );
            exit();
            }

        if(is_array($metodos)){
            if(!in_array($acao, $metodos)){
                self::sendError( 'Parâmetros inválidos.' );
                exit();
                }
            }

        try {
            call_user_func( $acao );
            }
        catch(\Exception $e){
            self::sendError( $e->getMessage() );
            exit();
            }
        }
    }