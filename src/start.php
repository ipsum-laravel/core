<?php

/**
* Formate une date pour un affichage
* @param string $date (format sql DATE ou DATETIME)
* @param string $format de sortie (DATE, DATETIME, IDENTIQUE)
* @return string
*/
function formateDate($date, $format = 'IDENTIQUE')
{
    if ($date == '0000-00-00 00:00:00' or $date == '0000-00-00') return false;
    $timestamp = strtotime($date);
    if (!$timestamp or $timestamp  == -1)
        return false;
    if ((strlen($date) == 10 and $format == 'IDENTIQUE') or $format == 'DATE')
        $date =  date('d/m/Y', $timestamp);
    elseif  ((strlen($date) == 19 and $format == 'IDENTIQUE') or $format == 'DATETIME')
        $date = date('d/m/Y H:i:s', $timestamp);
    else return false;
    return $date;
}

/**
* Formate une date pour un stockage
* Renvoi YYYY-mm-jj HH:ii:ss a partir de jj/mm/aaaa HH:MM:SS ou  jj/mm/aaaa
* @param string $date
* @return string  (format sql DATE ou DATETIME)
*/
function formateDateStocke($date, $format = 'Y-m-d H:i:s')
{
    $date = trim($date);
    try {
        $date = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format($format);
    } catch (\InvalidArgumentException $e) {
        return false;
    }
    return $date;
}

