<?php

class eZSpellcheckerTemplateOperator
{
    public function __construct()
    {
    }

    public function operatorList()
    {
        return array( 'spellchecker_language' );
    }

    public function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'spellchecker_language' => array( 'language_code' => array( 'type'     => 'string',
                                                                                  'required' => true,
                                                                                  'default'  => '' )
                                                )
                    );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace,
                     $currentNamespace, &$operatorValue, $namedParameters
                   )
    {
        $languageCode       = $namedParameters['language_code'];

        switch ( $operatorName )
        {
            case 'spellchecker_language':
            {
                trim( $languageCode );
                $ini  = eZINI::instance( 'spellchecker.ini' );
                $languageMapping = $ini->variable( 'LanguageMappingSettings', 'LanguageList' );

                if ( array_key_exists( $languageCode, $languageMapping ) )
                {
                    $operatorValue = $languageMapping[$languageCode];
                }
                else
                {
                    $operatorValue = 'en';
                }
            }
            break;
        }
    }
}

?>
