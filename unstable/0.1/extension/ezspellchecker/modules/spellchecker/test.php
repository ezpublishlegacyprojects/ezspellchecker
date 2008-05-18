<?php

require_once( "kernel/common/template.php" );


$Module = $Params["Module"];

$http = eZHTTPTool::instance();
$Module->setTitle( "Spellchecker test" );

$tpl = templateInit();
// $tpl->setVariable( "module", $Module );

$Result = array();

$Result['content'] = $tpl->fetch( "design:spellchecker/test.tpl" );

$Result['path'] = array( array( 'url' => '/spellchecker/test/',
                                'text' => 'Spellcheker test' ) );
?>
