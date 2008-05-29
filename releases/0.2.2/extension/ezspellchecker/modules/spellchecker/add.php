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

$error = false;

if ( !$usePersonalDictionary )
{
    $error =  ezi18n( 'ezspellchecker/add', "Personal dictionary is not enabled." );
}

$word = false;
if ( $http->hasGetVariable( '0' )  )
{
    $word = $http->getVariable( '0' );
}
elseif ( $http->hasPostVariable( '0' )  )
{
    $word = $http->postVariable( '0' );
}

if ( !$word )
{
    $error =  ezi18n( 'ezspellchecker/add', "Not word is given" );
}

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

$sc = new eZSpellchecker( $language );
$resultArray = array();

if (@!iconv( "UTF-8", "ISO-8859-1", $word ) )
    $word = iconv( "UTF-8", "ISO-8859-1", $word );

if ( $sc && !$error )
{
    if ( $sc->addWord( $word ) )
    {
        if (@!iconv( "UTF-8", "ISO-8859-1", $word ) )
            $word = iconv( "ISO-8859-1", "UTF-8", $word );
        $type = $sc->isUserBasedDictionary() ? 'your' : 'the';
        $resultArray[] = array( ezi18n( 'ezspellchecker/add', "'%word' added to %type dictionary", false, array( '%word' => $word, '%type' => $type ) ) );
    }
    else
    {
        $resultArray[] = array( ezi18n( 'ezspellchecker/add', "Cannot add word" ) );
    }
}
else
{
    $resultArray[] = array( $error );
}




$jsonObj = new JsonContent();
$resultString = $jsonObj->json_encode( $resultArray );

echo "data = " . $resultString . ";";

eZExecution::cleanup();
eZExecution::setCleanExit();
exit();

?>
