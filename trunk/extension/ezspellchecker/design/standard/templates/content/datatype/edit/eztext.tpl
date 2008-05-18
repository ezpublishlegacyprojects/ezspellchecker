{if is_set( $attribute_base )|not}
    {def $attribute_base='ContentObjectAttribute'}
{/if}
{if is_set( $html_class )|not}
    {def $html_class='full'}
{/if}

{def $enable_pers_dic = and( cond( eq( ezini( "PersonalDictionarySettings", "PersonalDictionary", "spellchecker.ini" ), "enabled" ), true(), false() ),
                             cond( eq( ezini( "PersonalDictionarySettings", "EditablePersonalDictionary", "spellchecker.ini" ), "enabled" ), true(), false() ) )
     $debug_output    = cond( eq( ezini( "SpellcheckerSettings", "DebugOutput", "spellchecker.ini" ), "enabled" ), true(), false() )
     $language        = spellchecker_language($attribute.language_code)}

{def $spellcheck_enabled = ezpreference( 'ezspellchecker_enabled' )
     $textarea_id        = concat( 'ezcoa-',
                                   cond( ne( $attribute_base, 'ContentObjectAttribute' ), concat($attribute_base,'-') ), 
                                   $attribute.contentclassattribute_id,
                                   '_',
                                   $attribute.contentclass_attribute_identifier )
     $spellchecker_id    = concat( 'oSpell',$attribute.id )}


<div class="float-break">
<div class="checker">
    <div class="spellchecker">
        <div class="commandbar">
            <button onclick="{$spellchecker_id|wash()}.update(); return false;" title="Update"><img src={"spellcheck.png"|ezimage()} alt="{'Update / Rescan'|i18n('ezspellchecker/design/eztext')}" />{'Update / Rescan'|i18n('ezspellchecker/design/eztext')}</button>
            <button onclick="{$spellchecker_id|wash()}.toogle(); return false;" title="Toogle"><img src={"toogle.png"|ezimage()} alt="{'Disable Spellchecker'|i18n('ezspellchecker/design/eztext')}" /><span id="toogleSCButtonText" >{'Disable Spellchecker'|i18n('ezspellchecker/design/eztext')}</span></button>
        </div>
        <div>
            <textarea   id="{$textarea_id|wash()}"
                        class="{eq( $html_class, 'half' )|choose( 'box', 'halfbox' )} ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" 
                        name="{$attribute_base}_data_text_{$attribute.id}"
                        cols="70"
                        style="width: 599px; height: 289px;"
                        rows="{$attribute.contentclass_attribute.data_int1}">{$attribute.content|wash}</textarea>
        </div>
    </div>
    <div class="infobar">
        <div id="infotext">&nbsp;</div>
        <div id="copyright">eZ Spellchecker<br />(c) 2008 eZ Systems</div>
    </div>
</div>
{if $debug_output}
    <script language="JavaScript" type="text/javascript">
        window.setTimeout('initDebug();', 100 );
    </script>
{/if}
</div>
<script type="text/javascript">
    var {$spellchecker_id|wash()};
    function initSpellchecker_{$spellchecker_id|wash()}() {ldelim}
        var elText{$attribute.id} = document.getElementById('{$textarea_id|wash()}');

        if ( webFXSpellCheckHandler.isConfigured == false )
        {ldelim}
            webFXSpellCheckHandler.serverURI     = {concat('/spellchecker/check/',$language)|ezurl('single')};
            webFXSpellCheckHandler.addWordURI    = {concat('/spellchecker/add/',$language)|ezurl('single')};
            webFXSpellCheckHandler.invalidWordBg = 'url({'redline.png'|ezimage()}) repeat-x bottom';
            webFXSpellCheckHandler.httpMethod    = 'GET';
            webFXSpellCheckHandler.httpParamSep  = ';';
            webFXSpellCheckHandler.wordsPerReq   = 100;
            webFXSpellCheckHandler.language      = '{$language|wash()}';
            webFXSpellCheckHandler.enablePersDic = {if $enable_pers_dic}true{else}false{/if};
            webFXSpellCheckHandler.debugOutput   = {if $debug_output}true{else}false{/if};
            webFXSpellCheckHandler.isConfigured  = true;

            webFXSpellCheckHandler.textStrings=new Array();
            webFXSpellCheckHandler.textStrings["DisableSpellchecker"] = "{'Disable Spellchecker'|i18n('ezspellchecker/design/eztext')}";
            webFXSpellCheckHandler.textStrings["EnableSpellchecker"] = "{'Enable Spellchecker'|i18n('ezspellchecker/design/eztext')}";
            webFXSpellCheckHandler.textStrings["Ignore"] = "{'Ignore'|i18n('ezspellchecker/design/eztext')}";
            webFXSpellCheckHandler.textStrings["Nosuggestions"] = "{'No suggestions'|i18n('ezspellchecker/design/eztext')}";
            webFXSpellCheckHandler.textStrings["AddtoDictionary"] = "{'Add to Dictionary'|i18n('ezspellchecker/design/eztext')}";

        {rdelim}

        {$spellchecker_id|wash()} = new WebFXLiteSpellChecker( elText{$attribute.id} );
{if $content}
        {$spellchecker_id|wash()}.setText( "{$content}" );
{/if}

    {rdelim}

    window.setTimeout('initSpellchecker_{$spellchecker_id|wash()}()', 200 );
</script>
