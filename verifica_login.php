<?php

ini_set('display_errors', 0 );
error_reporting(0);

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    window.location.replace('painel.php')
    </script>";
    exit();
 }

session_start();

if(!$_SESSION['email']){
    header('Location: painel.php');
    exit();
}