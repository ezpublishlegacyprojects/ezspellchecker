<form action={concat('/spellchecker/test/',$language)|ezurl()} method="POST">

<h1>{'eZ Spellchecker Demo'|i18n('ezspellchecker/design/test')}</h1>
<p>
    {'This is a demo of the eZ Publish Spell Checker extension (plain text implementation).'|i18n('ezspellchecker/design/test')}
</p>
<p>
    {'The spelling is checked as you type and misspelled words are highlighted by a wavy red underline.'|i18n('ezspellchecker/design/test')}
    {'If something looks weird you might have found a bug, remember it\'s still a beta, in that case click the \'Update/Rescan\' button to restore things.'|i18n('ezspellchecker/design/test')}
</p>
{if $content}
<p>
    {'The follow content was submitted by this formular.'|i18n('ezspellchecker/design/test')}
</p>
<div class="spellchecker_content">
    {$content|wash()}
</div>
{/if}
<div class="spellchecker">
    <div class="commandbar">
        <button onclick="oSpell.update(); return false;" title="Update"><img src={"spellcheck.png"|ezimage()} alt="Update" />Update/Rescan</button>
        <button onclick="alert(oSpell.getText()); return false;"><img src={"get.png"|ezimage()} alt="Get Text" />alert(oSpell.getText());</button>
        <button onclick="oSpell.setText('This is a smaple text string wiht a few mispeled words.'); return false;"><img src={"set.png"|ezimage} alt="Set Text" />oSpell.setText(...);</button>
    </div>
    <div>
        <textarea name="DemoContent" id="edit" style="width: 600px; height: 289px;"></textarea>
    </div>
</div>
    <div class="infobar">
        <div id="infotext">&nbsp;</div>
        <div id="copyright">(c) 2008 eZ Systems</div>
    </div>

<script type="text/javascript">
    var oSpell;

    function init() {ldelim}
        var elText = document.getElementById('edit');

        webFXSpellCheckHandler.serverURI     = {concat('/spellchecker/check/',$language)|ezurl('single')};
        webFXSpellCheckHandler.addWordURI    = {concat('/spellchecker/add/',$language)|ezurl('single')};
        webFXSpellCheckHandler.invalidWordBg = 'url({'redline.png'|ezimage()}) repeat-x bottom';
        webFXSpellCheckHandler.httpMethod    = 'GET';
        webFXSpellCheckHandler.httpParamSep  = ';';
        webFXSpellCheckHandler.wordsPerReq   = 100;
        webFXSpellCheckHandler.language      = '{$language|wash()}';
        webFXSpellCheckHandler.enablePersDic = {if $enable_pers_dic}true{else}false{/if};
        webFXSpellCheckHandler.debugOutput   = {if $debug_output}true{else}false{/if};


        oSpell = new WebFXLiteSpellChecker(elText);
{if $content}
        oSpell.setText( '{$content|wash()}' );
{/if}

    {rdelim}

    window.onload = init;
</script>

<input type="submit" name="ShowText" value="Show" class="button" />

{if $debug_output}
    <div id="debugField">&nbsp;</div>
{/if}


</form>