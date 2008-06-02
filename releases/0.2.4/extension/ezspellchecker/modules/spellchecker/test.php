<?php

require_once( "kernel/common/template.php" );

$http = eZHTTPTool::instance();

$Module = $Params["Module"];
$Module->setTitle( "Spellchecker test" );

//If pspell doesn't exist, then include the pspell wrapper for aspell.
if(!function_exists('pspell_suggest'))
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
$content = '';

if ( $http->hasPostVariable( 'ShowText' ) &&
     $http->hasPostVariable( 'DemoContent' )  )
{
    $content = $http->postVariable( 'DemoContent' );
}


$ini  = eZINI::instance( 'spellchecker.ini' );
$language = $Params['Language'];
if ( !$language || strlen( $language ) != 2 )
{
    $languageMapping = $ini->variable( 'LanguageMappingSettings', 'LanguageList' );
    $locale = eZLocale::instance();
    $localeCode = $locale->localeCode();

    if ( array_key_exists( $localeCode, $languageMapping ) )
    {
        $language = $languageMapping[$localeCode];
        return $Module->redirectToView( 'test', array( $language ) );

    }
    else
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

}


$enablePersDic = ( ( $ini->variable( 'PersonalDictionarySettings', 'PersonalDictionary' ) == 'enabled' ) ? true : false )
                 && ( ( $ini->variable( 'PersonalDictionarySettings', 'EditablePersonalDictionary' ) == 'enabled' ) ? true : false );

$debugOutput = ( $ini->variable( 'SpellcheckerSettings', 'DebugOutput' ) == 'enabled' ) ? true : false;

$tpl = templateInit();
$content = str_replace("\n\r","<br />", $content );
$content = str_replace("\r\n","<br />", $content );
$content = str_replace("\r","<br />", $content );
$content = str_replace("\n","<br />", $content );
$tpl->setVariable( 'content', $content  );

$tpl->setVariable( 'language', $language );
$tpl->setVariable( 'debug_output', $debugOutput  );
$tpl->setVariable( 'enable_pers_dic',  $enablePersDic );

$Result = array();

$Result['content'] = $tpl->fetch( "design:spellchecker/test.tpl" );
$Result['path'] = array( array( 'url' => '/spellchecker/test/',
                                'text' => 'Spellcheker test' ) );
?>
