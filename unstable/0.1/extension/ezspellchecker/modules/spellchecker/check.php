<?php


require_once( "extension/ezspellchecker/classes/ezspellchecker.php" );

$Module = $Params["Module"];

$http = eZHTTPTool::instance();


$ini  = eZINI::instance( 'spellchecker.ini' );


$usePersonalDictionary = ( $ini->variable( 'PersonalDictionarySettings', 'PersonalDictionary' ) == 'enabled' ) ? true : false;

$language   = ( $ini->variable( 'SpellcheckerSettings', 'DefaultLanguage' ) != '' )
                        ? $ini->variable( 'SpellcheckerSettings', 'DefaultLanguage' )
                        : "en";

//If pspell doesn't exist, then include the pspell wrapper for aspell.
if(!function_exists('pspell_suggest'))
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

// Create and configure a link to the pspell module.

if ( $http->hasPostVariable( 'cpaint_argument' ) )
{
    $arguments = $http->postVariable( 'cpaint_argument' );
    if( is_array( $arguments ) && count ( $arguments ) >= 3 )
    {
        $language = str_replace( "\"", "", $arguments[2] );
    }
}

// $pspell_config = pspell_config_create( $language );

// pspell_config_mode($pspell_config, PSPELL_FAST);

if( $usePersonalDictionary )
{
    // Allows the use of a custom dictionary (Thanks to Dylan Thurston for this addition).
    pspell_config_personal($pspell_config, $path_to_personal_dictionary);
}

// $pspell_link = @pspell_new_config($pspell_config);

$pspell_link = pspell_new( $language, '', '', 'utf-8', PSPELL_FAST );

if ( $pspell_link )
{
    $cp = new cpaint( $pspell_link );

    if ( !$cp->register('showSuggestions') )
    {
        $error = " Cannot register 'showSuggestions' function";
        $cp->set_data($error);
    }
    if ( !$cp->register('spellCheck') )
    {
        $error = " Cannot register 'spellCheck' function";
        $cp->set_data($error);
    }
    if ( !$cp->register('switchText') )
    {
        $error = " Cannot register 'switchText' function";
        $cp->set_data($error);
    }
    if ( !$cp->register('addWord') )
    {
        $error = " Cannot register 'addWord' function";
        $cp->set_data($error);
    }

    $cp->start();
    $cp->return_data();

}
else
{
    $cp = new cpaint( false );
    $error = " Cannot create spell checker instance";
    $cp->set_data($error);
    $cp->return_data();
}

eZExecution::cleanup();
eZExecution::setCleanExit();
exit();

?>
