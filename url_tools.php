<?php
/**
 * Created by PhpStorm.
 * User: joão Madeira
 * Date: 13/12/2017
 * Time: 11:18
 */

define("PREFIXOS_DE_URLS_ABSOLUTOS",
    ["http://", "https://", "ftp://", "ftps://", "telnet://", "mailto;//"]
);

define("ACEITAR_TUDO", null);

/*
 * (c) Pessoa 1, 2017
 * recebe ums string que deverá representar um URL
 * retorna tru se a String recebida for um "url absoluto", false c.c.
 */

function urlAbsoluto(
    $pUrl
){
    $ret = false;
    $bCautela = is_string($pUrl) && strlen($pUrl)>0;
    if($bCautela){
        foreach (PREFIXOS_DE_URLS_ABSOLUTOS as $prefixo){
            $bUrlComecaPeloPrefixoEmAnalise =
                stripos($pUrl, $prefixo) === 0;
            if($bUrlComecaPeloPrefixoEmAnalise)
                return true;
        }//foreach
    }//if
    return $ret;
}//urlAbsoluto

function urlTerminaEm(
    $pUrl,
    $pTeminacoesAceites = ACEITAR_TUDO
){
    $bCautela = is_array($pTeminacoesAceites) && count($pTeminacoesAceites)>0;
    if ($bCautela){
        foreach ($pTeminacoesAceites as $terminacao){
            $iPosUltimaOcorrenciaDaTeminacaoNoUrl =
                stripos($pUrl, $terminacao);
            $bTeminacaoFoiEncontrada =
                $iPosUltimaOcorrenciaDaTeminacaoNoUrl!==false;
            if ($bTeminacaoFoiEncontrada){
                $tamanhoDoUrl = strlen($pUrl);
                $iPosEmQueTeminacaoTemQueEstarParaSerFinilizadorDoUrl =
                    $tamanhoDoUrl - strlen($terminacao);

                $bTeminacaoEstaMesmoNoFim =
                    $iPosUltimaOcorrenciaDaTeminacaoNoUrl
                    ===
                    $iPosEmQueTeminacaoTemQueEstarParaSerFinilizadorDoUrl;

                if ($bTeminacaoEstaMesmoNoFim)
                    return true;
            }//if
        }//foreach
    }//if
    else {
        return true; //nenhum filtro foi imposto, qq URL satisfaz
    }//else
    return false; //nenhuma das terminações aceites terminou o URL
}//urlTeminaEm

function filtrarUrls(
    $pAUrls,
    $pFiltros = ACEITAR_TUDO
){
    $ret = [];

    foreach ($pAUrls as $url){
        if (urlTerminaEm($url,$pFiltros)) $ret[]=$url;
    }//foreach

    return $ret;
}//filtrarUrls