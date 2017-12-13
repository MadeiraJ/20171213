<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/12/2017
 * Time: 11:57
 *
 * Aprender PHP e CURL usando o site
 * apod.nasa.gov
 * Imagem de referência, disponível em:
 * https://apod.nasa.gov/apod/ap171210.html
 *
 * o arquivo de todas as imagens alguma vez publicadas está em:
 * https://apod.nasa.gov/apod/archivepix.html
 *
 * Oportunidades para aprender:
 * parsing de strings: straps, strrpos, strripos
 * operações com vetores de strings: explode
 * operações de procura em arrays: array_search
 * objetos CURL em PHP
 * download de binários usando CURL
 *
 * check: confirmar em PHP.INI que php_curl.dll está ativo
 */

define(
    "URL_DE_TESTE_PARA_CONSUMO",
    "https://apod.nasa.gov/apod/ap171210.html"
    //"http://arturmarques.com/"
);

function downloaderInseguro($pUrl){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pUrl);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //inseguro
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

    $resultadoFalseSeFracassoOuSeqBytesSeSucesso =
        curl_exec($ch);
    return $resultadoFalseSeFracassoOuSeqBytesSeSucesso;
}//downloaderInseguro

function gravadorAutomaticoDeDownloadParaFicheiro(
    $pBytesParaGravar,
    $pNomeDoFicheiro = null
){
    //exemplo de nome para ficheiro
    //2017-12-11-12-38-00.BIN
    $nomeDoFicheiro =
        ($pNomeDoFicheiro === null) ?
            date("Y-m-d-G-i-s").".BIN"
            :
            $pNomeDoFicheiro;

    $ret = file_put_contents(
        $nomeDoFicheiro,
        $pBytesParaGravar
    );

    return $ret? $nomeDoFicheiro : false;
}//gravadorAutomaticoDeDownloadParaFicheiro

function urlsPresentesNoURL(
    $pUrl //e.g. "http://arturmarques.com/"
){
    $htmlSourceCode = downloaderInseguro($pUrl);
    $urlsDescobertosNoHTML =
        urlsPresentesNoHTML($htmlSourceCode);

    return $urlsDescobertosNoHTML;
}//urlsPresentesNoURL

define("MARCADOR_DE_HREFS", "<a href=\"");

function urlsPresentesNoHTML(
    $pSourceCodeHTML
){
    $urls = [];

    /*
     * exemplo de explode
     * $s = "bla\tble\tbli"
     * explode ("\t", $s) ------> ["bla", "ble", "bli"]
     *
     */

    $partesExigindoMaisParsingParaIsolarUrls =
        explode(MARCADOR_DE_HREFS, $pSourceCodeHTML);

    $extensoesDeInteresse = [".jpg", ".png"];

    $parteNumero = 0;
    foreach (
        $partesExigindoMaisParsingParaIsolarUrls
        as
        $parte
    ){
        //rejeitar a primeira parte, porque é "lixo"
        if ($parteNumero>0){
            /*
            * cada parte tem o URL que interessa desde
            * a sua posição 0 até à posição em que ocorra
            * a primeira aspa (que simboliza o fim do valor
            * do valor href
            * exemplos;
            * $parte <------- "<a href=\"arturmarques.com/\">..."
            */
            /*
             * exemplos
             * strpos("ABC, "BC") --> 1
             * strpos("ABC, "bc") --> false
             * stripos("ABC, "bc") --> 1 (procura case INsensive)
             * strripos("ABCC, "c") --> 3 (r rightmost)
             * strripos("ABCCC, "c") --> 4
             */
            $posicaoDaAspaDeEncerramento =
                stripos($parte, "\"");

            $aspaExiste =
                $posicaoDaAspaDeEncerramento!==false;

            /*
             * substr ($frase, $posDePartida, $quantidade)
             */
            if($aspaExiste){
                $url = substr(
                    $parte,
                    0,
                    $posicaoDaAspaDeEncerramento
                );
                $urls[] = $url;
            }
        }//if
        $parteNumero++;
    }//for
    return $urls;
}//urlsPresentesNoHTML

var_dump(urlsPresentesNoURL(
    URL_DE_TESTE_PARA_CONSUMO
));
//echo downloaderInseguro(URL_DE_TESTE_PARA_CONSUMO);
