<?php


class eZSpellchecker
{

    public function __construct( $language )
    {
        $ini  = eZINI::instance( 'spellchecker.ini' );

        $this->MaxSuggestions   = (int)$ini->variable( 'SpellcheckerSettings', 'NumberOfMaxSuggestions' );
        $this->PersDictionary   = ( $ini->variable( 'PersonalDictionarySettings', 'PersonalDictionary' ) == 'enabled' ) ? true : false;

        if ( $this->PersDictionary )
        {
            $this->PersDictionaryEditable = ( $ini->variable( 'PersonalDictionarySettings', 'EditablePersonalDictionary' ) == 'enabled' ) ? true : false;
            $this->PersDictionaryPerUser  = ( $ini->variable( 'PersonalDictionarySettings', 'PersonalDictionaryPerUser' ) == 'enabled' ) ? true : false;
            $location = $ini->variable( 'PersonalDictionarySettings', 'Location' );

            if ( $this->PersDictionaryPerUser )
            {
                $user = eZUser::currentUser();
                $userID = $user->attribute( 'contentobject_id' );
                $location = str_replace( '${user}', $userID, $location );
            }
            else
            {
                $location = str_replace( '${user}', '', $location );
            }

            $location = str_replace( '${lang}', $language, $location );

            $siteINI = eZINI::instance( );
            $varDir = $siteINI->variable( 'FileSettings', 'VarDir' );
            $location = str_replace( '${var}',  $varDir, $location );

            $location = eZSys::rootDir() . '/' . $location;
            if ( !file_exists( $location ) )
            {
                eZDir::mkdir( dirname($location), eZDir::directoryPermission(), true );
            }
            $this->PersDictionaryLocation = $location;
        }
        else
        {
            $this->PersDictionaryEditable = false;
            $this->PersDictionaryPerUser  = false;
        }

        $this->PSpellConfig = pspell_config_create( $language );
        pspell_config_mode( $this->PSpellConfig, PSPELL_FAST );

        if ( $this->PersDictionary )
        {
            pspell_config_personal( $this->PSpellConfig, $location );
        }

        $this->PSpellLink = pspell_new_config( $this->PSpellConfig );
    }

    public function checkWord( $word )
    {
        return pspell_check( $this->PSpellLink, $word );
    }

    public function showSuggestions( $word )
    {
        $suggestions = pspell_suggest( $this->PSpellLink, $word );
        $tmpArray = array();

        foreach ( $suggestions as $value )
        {
            $tmpArray[] = iconv( "ISO-8859-1", "UTF-8", $value );
            if ( count( $tmpArray ) > $this->MaxSuggestions )
                break;
        }
        $suggestions = $tmpArray;
/*        if ( count( $suggestions ) > $this->MaxSuggestions )
            return array_slice( $suggestions, 0, $this->MaxSuggestions );
        else*/
            return $suggestions;
    }

    public function addWord( $word )
    {
        if ( $this->PersDictionary && $this->PersDictionaryEditable )
        {
            if ( !pspell_add_to_personal( $this->PSpellLink, $word ) )
            {
                return false;
            }
            return pspell_save_wordlist( $this->PSpellLink );
        }
        return false;
    }

    public function isUserBasedDictionary( )
    {
        return $this->PersDictionaryPerUser;
    }

    private $PSpellLink;
    private $PSpellConfig;
    private $PersDictionary;
    private $PersDictionaryLocation;
    private $PersDictionaryEditable;
    private $PersDictionaryPerUser;
    private $MaxSuggestions;
}

?>