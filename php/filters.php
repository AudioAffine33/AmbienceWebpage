<?php

    function createContinentFilter($action){
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

    function createFormatFilter($action){
        if (!isset($_SESSION['query']['cdc']) || $_SESSION['query']['cdc'] == "" ){
            $_SESSION['query']['cdc'] = $action['cdc'];
        } else {
            $lgtArr = explode("-",$_SESSION['query']['cdc']);

            if (is_numeric(array_search($action['cdc'], $lgtArr))){
                unset($lgtArr[array_search($action['cdc'], $lgtArr)]);
                $_SESSION['query']['cdc'] = implode("-", $lgtArr);
            } else {
                $_SESSION['query']['cdc'] = implode("-", $lgtArr)."-".$action['cdc'];
            }
        }

        return  $_SESSION['query'];
    }

    function checkFormatFilter($str){
        if (is_numeric(array_search($str, explode("-",$_GET['cdc'])))){
            return true;
        } else {
            return false;
        }
    }

    function createDepthFilter($action){
        if (!isset($_SESSION['query']['bd']) || $_SESSION['query']['bd'] == "" ){
            $_SESSION['query']['bd'] = $action['bd'];
        } else {
            $lgtArr = explode("-",$_SESSION['query']['bd']);

            if (is_numeric(array_search($action['bd'], $lgtArr))){
                unset($lgtArr[array_search($action['bd'], $lgtArr)]);
                $_SESSION['query']['bd'] = implode("-", $lgtArr);
            } else {
                $_SESSION['query']['bd'] = implode("-", $lgtArr)."-".$action['bd'];
            }
        }

        return  $_SESSION['query'];
    }

    function checkDepthFilter($str){
        if (is_numeric(array_search($str, explode("-",$_GET['bd'])))){
            return true;
        } else {
            return false;
        }
    }

    function createFreqFilter($action){
        if (!isset($_SESSION['query']['sf']) || $_SESSION['query']['sf'] == "" ){
            $_SESSION['query']['sf'] = $action['sf'];
        } else {
            $lgtArr = explode("-",$_SESSION['query']['sf']);

            if (is_numeric(array_search($action['sf'], $lgtArr))){
                unset($lgtArr[array_search($action['sf'], $lgtArr)]);
                $_SESSION['query']['sf'] = implode("-", $lgtArr);
            } else {
                $_SESSION['query']['sf'] = implode("-", $lgtArr)."-".$action['sf'];
            }
        }

        return  $_SESSION['query'];
    }

    function checkFreqFilter($str){
        if (is_numeric(array_search($str, explode("-",$_GET['sf'])))){
            return true;
        } else {
            return false;
        }
    }
?>