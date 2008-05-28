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
        <div id="{$textarea_id|wash()}_infotext" class="infotext">&nbsp;</div>
        <div id="copyright">eZ Spellchecker<br />(c) 2008 eZ Systems</div>
    </div>
</div>
</div>
{include uri='design:spellchecker/spellchecker_init.tpl' attribute_id=$attribute.id}
