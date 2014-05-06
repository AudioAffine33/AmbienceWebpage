<?php

    function createCatFilter($action){
        if (!isset($_SESSION['query']['cat']) || $_SESSION['query']['cat'] == "" ){
            $_SESSION['query']['cat'] = $action['cat'];
            $_SESSION['query']['page'] = 1;
        } else {
            $contArr = explode("-",$_SESSION['query']['cat']);

            if (is_numeric(array_search($action['cat'], $contArr))){
                unset($contArr[array_search($action['cat'], $contArr)]);
                $_SESSION['query']['cat'] = implode("-", $contArr);
                $_SESSION['query']['page'] = 1;
            } else {
                $_SESSION['query']['cat'] = implode("-", $contArr)."-".$action['cat'];
                $_SESSION['query']['page'] = 1;
            }
        }

        return  $_SESSION['query'];
    }

    function checkCatFilter($str){
        if (is_numeric(array_search($str, explode("-",$_GET['cat'])))){
            return true;
        } else {
            return false;
        }
    }

    function createContinentFilter($action){
        if (!isset($_SESSION['query']['cont']) || $_SESSION['query']['cont'] == "" ){
            $_SESSION['query']['cont'] = $action['cont'];
            $_SESSION['query']['page'] = 1;
        } else {
            $contArr = explode("-",$_SESSION['query']['cont']);

            if (is_numeric(array_search($action['cont'], $contArr))){
                unset($contArr[array_search($action['cont'], $contArr)]);
                $_SESSION['query']['cont'] = implode("-", $contArr);
                $_SESSION['query']['page'] = 1;
            } else {
                $_SESSION['query']['cont'] = implode("-", $contArr)."-".$action['cont'];
                $_SESSION['query']['page'] = 1;
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
            $_SESSION['query']['page'] = 1;
        } else {
            $lgtArr = explode("-",$_SESSION['query']['cdc']);

            if (is_numeric(array_search($action['cdc'], $lgtArr))){
                unset($lgtArr[array_search($action['cdc'], $lgtArr)]);
                $_SESSION['query']['cdc'] = implode("-", $lgtArr);
                $_SESSION['query']['page'] = 1;
            } else {
                $_SESSION['query']['cdc'] = implode("-", $lgtArr)."-".$action['cdc'];
                $_SESSION['query']['page'] = 1;
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
            $_SESSION['query']['page'] = 1;
        } else {
            $lgtArr = explode("-",$_SESSION['query']['bd']);

            if (is_numeric(array_search($action['bd'], $lgtArr))){
                unset($lgtArr[array_search($action['bd'], $lgtArr)]);
                $_SESSION['query']['bd'] = implode("-", $lgtArr);
                $_SESSION['query']['page'] = 1;
            } else {
                $_SESSION['query']['bd'] = implode("-", $lgtArr)."-".$action['bd'];
                $_SESSION['query']['page'] = 1;
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
            $_SESSION['query']['page'] = 1;
        } else {
            $lgtArr = explode("-",$_SESSION['query']['sf']);

            if (is_numeric(array_search($action['sf'], $lgtArr))){
                unset($lgtArr[array_search($action['sf'], $lgtArr)]);
                $_SESSION['query']['sf'] = implode("-", $lgtArr);
                $_SESSION['query']['page'] = 1;
            } else {
                $_SESSION['query']['sf'] = implode("-", $lgtArr)."-".$action['sf'];
                $_SESSION['query']['page'] = 1;
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