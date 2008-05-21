{if is_set($spellchecker_id)|not}
    {def $spellchecker_id='ezspellchecker'}
{/if}
{if is_set($language)|not}
    {def $language='en'}
{/if}
{if is_set($attribute_id)|not}
    {def $attribute_id=''}
{/if}
{if is_set($textarea_id)|not}
    {def $textarea_id='spellchecker_textarea'}
{/if}
{if is_set($enable_pers_dic)|not}
    {def $enable_pers_dic=true()}
{/if}
{if is_set($debug_output)|not}
     {def $debug_output=cond( eq( ezini( "SpellcheckerSettings", "DebugOutput", "spellchecker.ini" ), "enabled" ), true(), false() )}
{/if}
{if is_set($content)|not}
    {def $content=''}
{/if}


<script type="text/javascript">
    var {$spellchecker_id|wash()};
    function initSpellchecker_{$spellchecker_id|wash()}() {ldelim}
        var elText{$attribute_id} = document.getElementById('{$textarea_id|wash()}');

        if ( webFXSpellCheckHandler.isConfigured == false )
        {ldelim}
            webFXSpellCheckHandler.textStrings=new Array();
            webFXSpellCheckHandler.textStrings["DisableSpellchecker"] = "{'Disable Spellchecker'|i18n('ezspellchecker/design/eztext')}";
            webFXSpellCheckHandler.textStrings["EnableSpellchecker"] = "{'Enable Spellchecker'|i18n('ezspellchecker/design/eztext')}";
            webFXSpellCheckHandler.textStrings["Ignore"] = "{'Ignore'|i18n('ezspellchecker/design/eztext')}";
            webFXSpellCheckHandler.textStrings["Nosuggestions"] = "{'No suggestions'|i18n('ezspellchecker/design/eztext')}";
            webFXSpellCheckHandler.textStrings["AddtoDictionary"] = "{'Add to Dictionary'|i18n('ezspellchecker/design/eztext')}";


            webFXSpellCheckHandler.serverURI     = {concat('/spellchecker/check/',$language)|ezurl('single')};
            webFXSpellCheckHandler.addWordURI    = {concat('/spellchecker/add/',$language)|ezurl('single')};
            webFXSpellCheckHandler.invalidWordBg = 'url({'redline.gif'|ezimage()}) repeat-x bottom';
            webFXSpellCheckHandler.httpMethod    = 'GET';
            webFXSpellCheckHandler.httpParamSep  = ';';
            webFXSpellCheckHandler.wordsPerReq   = 100;
            webFXSpellCheckHandler.language      = '{$language|wash()}';
            webFXSpellCheckHandler.enablePersDic = {if $enable_pers_dic}true{else}false{/if};
            webFXSpellCheckHandler.debugOutput   = {if $debug_output}true{else}false{/if};
            webFXSpellCheckHandler.isConfigured  = true;

        {rdelim}

        {$spellchecker_id|wash()} = new WebFXLiteSpellChecker( elText{$attribute_id} );
{if $content}
        {$spellchecker_id|wash()}.setText( "{$content}" );
{/if}

    {rdelim}

    window.setTimeout('initSpellchecker_{$spellchecker_id|wash()}()', 200 );
</script>

{if $debug_output}
    <script language="JavaScript" type="text/javascript">
        window.setTimeout('initDebug();', 100 );
    </script>
{/if}
