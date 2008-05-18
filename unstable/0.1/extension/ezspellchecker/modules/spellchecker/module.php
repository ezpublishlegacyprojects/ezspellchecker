<?php


$Module = array( "name" => "ezspellchecker" );

$ViewList = array();
$ViewList["check"] = array(
    "functions" => array( 'use' ),
    "script"    => "check.php" );

$ViewList["test"] = array(
    "functions" => array( 'test' ),
    "script"    => "test.php" );


$FunctionList = array();

$FunctionList['test'] = array();
$FunctionList['use'] = array();

?>
