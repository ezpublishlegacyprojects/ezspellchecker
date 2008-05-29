<?php

$Module = $Params["Module"];
//If pspell doesn't exist, then include the pspell wrapper for aspell.
if(!function_exists('pspell_suggest'))
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}




$http = eZHTTPTool::instance();


$ini  = eZINI::instance( 'spellchecker.ini' );
$usePersonalDictionary = ( $ini->variable( 'PersonalDictionarySettings', 'PersonalDictionary' ) == 'enabled' ) ? true : false;


$language = $Params['Language'];
if ( !$language || strlen( $language ) != 2 )
{
    $ini  = eZINI::instance( 'spellchecker.ini' );
    $languageMapping = $ini->variable( 'LanguageMappingSettings', 'LanguageList' );
    $locale = eZLocale::instance();
    $localeCode = $locale->localeCode();

    if ( array_key_exists( $localeCode, $languageMapping ) )
    {
        $language = $languageMapping[$localeCode];

    }
    else
    {
        $language   = ( $ini->variable( 'SpellcheckerSettings', 'DefaultLanguage' ) != '' )
                                ? $ini->variable( 'SpellcheckerSettings', 'DefaultLanguage' )
                                : "en";
    }

}

$getRequest = false;
if ( $http->hasGetVariable( '0' )  )
{
    $getRequest = '0=' . $http->getVariable( '0' );
}
elseif ( $http->hasPostVariable( '0' )  )
{
    $getRequest = '0=' . $http->postVariable( '0' );
}

$requestArray = array();
if ( $getRequest )
{
    $getArray = explode( ';', $getRequest );
    foreach ( $getArray as $getValue )
    {
        $getArray = explode( '=', $getValue);
        if ( count( $getArray ) == 2 )
        {
            $requestArray[$getArray[0]] = $getArray[1];
        }
    }

}

$sc = new eZSpellchecker( $language );
$resultArray = array( );
foreach( $requestArray as $key => $value )
{
    $value = rawurldecode( $value);
    if (@!iconv( "UTF-8", "ISO-8859-1", $value ) )
    $value =  iconv( "UTF-8", "ISO-8859-1", $value );
    if ( $sc->checkWord( $value ) )
    {
        $resultArray[] = array( 1 );
    }
    else
    {
        $resultArray[] = array( 0, $sc->showSuggestions( $value ) );
    }
}




$jsonObj = new JsonContent();
$resultString = $jsonObj->json_encode( $resultArray );

echo "data = " . $resultString . ";";

eZExecution::cleanup();
eZExecution::setCleanExit();
exit();

?>
