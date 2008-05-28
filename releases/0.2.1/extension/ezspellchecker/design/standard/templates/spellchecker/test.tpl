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
            <button onclick="ezspellchecker.update(); return false;" title="Update"><img src={"spellcheck.png"|ezimage()} alt="{'Update / Rescan'|i18n('ezspellchecker/design/eztext')}" />{'Rescan'|i18n('ezspellchecker/design/eztext')}</button>
            <button onclick="ezspellchecker.toogle(); return false;" title="Toogle"><img src={"toogle.png"|ezimage()} alt="{'Disable Spellchecker'|i18n('ezspellchecker/design/eztext')}" /><span id="toogleSCButtonText" >{'Disable Spellchecker'|i18n('ezspellchecker/design/eztext')}</span></button>
            <button onclick="alert(ezspellchecker.getText()); return false;"><img src={"get.png"|ezimage()} alt="Get Text" />{'Get current input'|i18n('ezspellchecker/design/test')}</button>
            <button onclick="ezspellchecker.setText('{'This is a smaple text string wiht a few mispeled words.'|i18n('ezspellchecker/design/test')}'); return false;"><img src={"set.png"|ezimage} alt="Set Text" />{'Set some text'|i18n('ezspellchecker/design/test')}</button>
    </div>
    <div>
        <textarea name="DemoContent" id="spellchecker_textarea" style="width: 600px; height: 289px;"></textarea>
    </div>
</div>
    <div class="infobar">
        <div id="infotext">&nbsp;</div>
        <div id="copyright">(c) 2008 eZ Systems</div>
    </div>

{include uri='design:spellchecker/spellchecker_init.tpl'}


<input type="submit" name="ShowText" value="Show" class="button" />



</form>