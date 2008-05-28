<?php


$Module = array( 'name' => 'ezspellchecker' );

$ViewList = array();
$ViewList['check'] = array(
    'functions' => array( 'use' ),
    'script'    => 'check.php',
    'params'    => array( 'Language' ) );

$ViewList['test'] = array(
    'functions' => array( 'test' ),
    'script'    => 'test.php',
    'params'    => array( 'Language' ) );

$ViewList['add'] = array(
    'functions' => array( 'use' ),
    'script'    => 'add.php',
    'params'    => array( 'Language' ) );


$FunctionList = array();

$FunctionList['test'] = array();
$FunctionList['use'] = array();

?>
