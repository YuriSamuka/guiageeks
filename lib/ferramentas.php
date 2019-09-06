<?php
/**
 * Created by PhpStorm.
 * User: Yuri
 * Date: 08/11/2017
 * Time: 14:32
 */

namespace Guiageeks\lib;


class ferramentas
{
    public static function SetConfigPath(){

        /*Diretorio raiz do servidor*/
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
        /*Raiz do projeto */
//        define('ROOT_APP', '');
        define('ROOT_APP', 'guiageeks');

        /*Caminhos absolutos*
        /
        /*Caminho absoluto da aplicação*/
        define('SERVER', 'http://' . $_SERVER["HTTP_HOST"]);
        /*Caminho absuluto da pasta que contem os arquivos web: html, css, js e etc*/
        define('WEB_URL', SERVER . '/' . ROOT_APP . '/public');

        /*Caminhos absolutos diretorio*/
        /*Caminho absuluto da pasta que contem os arquivos web: html, css, js e etc*/
        define('WEB', ROOT . '/' . ROOT_APP . '/public');
        /*Caminho absuluto da pasta que contem as bibliotecas*/
        define('LIB', ROOT . '/' . ROOT_APP . '/lib');
        /*Caminho absuluto da pasta que contem as aplicações (paginas, lending, dashboard e etc)*/
        define('APP', ROOT . '/' . ROOT_APP . '/app');
        /*Caminho absuluto da pasta que contem as sqls*/
        define('SQL', ROOT . '/' . ROOT_APP . '/sql');
    }

    /*Tenta remover codigos maliciosos da string*/
    public static function remover_coding_injection($str)
    {
        $str = str_replace(" union ", "", $str);
        $str = str_replace(" all ", "", $str);
        $str = str_replace(" select ", "", $str);
        $str = str_replace(" -- ", "", $str);
        $str = str_replace(" OR 1=1", "", $str);
        $str = str_replace(" and ", "", $str);
        $str = str_replace(" ascii ", "", $str);
        $str = str_replace(" information_schema ", "", $str);
        $str = str_replace(" database ", "", $str);
        $str = str_replace(" DATABASE ", "", $str);
        $str = str_replace(" if ", "", $str);
        $str = str_replace(" Length ", "", $str);
        $str = str_replace(" IF ", "", $str);
        $str = str_replace(" waitfor ", "", $str);
        $str = str_replace(" delay ", "", $str);
        $str = str_replace(" + ", "", $str);
        $str = str_replace(" table ", "", $str);
        $str = str_replace(" tables ", "", $str);
        $str = str_replace(" char ", "", $str);
        $str = str_replace(" CHAR ", "", $str);

        $search=array("\\","\0","\n","\r","\x1a","'",'"');
        $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');

        return str_replace($search,$replace,$str);
    }

    /*Torna o array $_POST seguro*/
    public static function safe_posts()
    {
        foreach ($_POST as $key => $value)
        {
            $_POST[$key] = self::remover_coding_injection($value);
        }
    }

    /*Torna o array $_GET seguro*/
    public static function safe_gets()
    {
        foreach ($_GET as $key => $value)
        {
            $_GET[$key] = self::remover_coding_injection($value);
        }
    }

    /*Torna o array $_REQUEST seguro*/
    public static function safe_requests()
    {
        foreach ($_REQUEST as $key => $value)
        {
            $_REQUEST[$key] = self::ScapeString($value);
        }
    }

    /**
     * Retorna o valor passado em extenso
     * @param int $valor
     * @param bool $bolExibirMoeda
     * @param bool $bolPalavraFeminina
     * @return string
     */
    public static function valorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
    {

        $valor = self::removerFormatacaoNumero($valor);

        $singular = null;
        $plural = null;

        if($bolExibirMoeda)
        {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
        else
        {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");


        if($bolPalavraFeminina)
        {
            if ($valor == 1)
            {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            else
            {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }


        $z = 0;

        $valor = number_format( $valor, 2, ".", "." );
        $inteiro = explode( ".", $valor );

        for ($i = 0; $i < count( $inteiro ); $i++)
        {
            for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ )
            {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for($i = 0; $i < count($inteiro); $i++)
        {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $inteiro ) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ( $valor == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;

            if(($t == 1) && ($z > 0) && ($inteiro[0] > 0))
            {
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
            }

            if($r)
            {
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;


            }
        }

        $rt = mb_substr($rt, 1);

        return($rt ? trim($rt) : "zero");
    }

    /**
     * Retira toda a formatação do número
     *
     * @access public/static
     * @param string $strNumero
     * @return string
     */
    public static function removerFormatacaoNumero($strNumero)
    {

        $strNumero = trim( str_replace( "R$", null, $strNumero ) );

        $vetVirgula = explode( ",", $strNumero );
        if ( count( $vetVirgula ) == 1 )
        {
            $acentos = array(".");
            $resultado = str_replace( $acentos, "", $strNumero );
            return $resultado;
        }
        else if ( count( $vetVirgula ) != 2 )
        {
            return $strNumero;
        }

        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr( $vetVirgula[1], 0, 2 );

        $acentos = array(".");
        $resultado = str_replace( $acentos, "", $strNumero );
        $resultado = $resultado . "." . $strDecimal;

        return $resultado;
    }
}