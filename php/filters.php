<?php

    function createContinentFilter($action){
        global $continentFilter;

        if (!isset($_SESSION['query']['cont']) || $_SESSION['query']['cont'] == "" ){
            $_SESSION['query']['cont'] = $action['cont'];
        } else {
            $contArr = explode("-",$_SESSION['query']['cont']);

            if (is_numeric(array_search($action['cont'], $contArr))){
                unset($contArr[array_search($action['cont'], $contArr)]);
                $_SESSION['query']['cont'] = implode("-", $contArr);
            } else {
                $_SESSION['query']['cont'] = implode("-", $contArr)."-".$action['cont'];
            }
        }

        return  $_SESSION['query'];
    }

    function checkContinentFilter($str){
        if (is_numeric(array_search($str, explode("-",$_GET['cont'])))){
            return true;
        } else {
            return false;
        }
    }
?>