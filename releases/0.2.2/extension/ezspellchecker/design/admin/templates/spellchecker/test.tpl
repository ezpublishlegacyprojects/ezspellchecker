<form action={concat('/spellchecker/test/',$language)|ezurl()} method="POST">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'eZ Spellchecker Demo'|i18n('ezspellchecker/design/test')}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes float-break">

<div class="block">

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


</div>


<div class="block checker">

    <div class="spellchecker">
        <div class="commandbar">
            <button onclick="ezspellchecker.update(); return false;" title="Update"><img src={"spellcheck.png"|ezimage()} alt="{'Update / Rescan'|i18n('ezspellchecker/design/eztext')}" />{'Rescan'|i18n('ezspellchecker/design/eztext')}</button>
            <button onclick="ezspellchecker.toogle(); return false;" title="Toogle"><img src={"toogle.png"|ezimage()} alt="{'Disable Spellchecker'|i18n('ezspellchecker/design/eztext')}" /><span id="toogleSCButtonText" >{'Disable Spellchecker'|i18n('ezspellchecker/design/eztext')}</span></button>
            <button onclick="alert(ezspellchecker.getText()); return false;"><img src={"get.png"|ezimage()} alt="Get Text" />{'Get current input'|i18n('ezspellchecker/design/test')}</button>
            <button onclick="ezspellchecker.setText('{'This is a smaple text string wiht a few mispeled words.'|i18n('ezspellchecker/design/test')}'); return false;"><img src={"set.png"|ezimage} alt="Set Text" />{'Set some text'|i18n('ezspellchecker/design/test')}</button>
        </div>
        <div>
            <textarea name="DemoContent" id="spellchecker_textarea" style="width: 599px; height: 289px;"></textarea>
        </div>
    </div>
    <div class="infobar">
        <div id="infotext">&nbsp;</div>
        <div id="copyright">eZ Spellchecker<br />(c) 2008 eZ Systems</div>
    </div>


</div>
{include uri='design:spellchecker/spellchecker_init.tpl'}


</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input type="submit" name="ShowText" value="{'Show'|i18n('ezspellchecker/design/eztext')}" class="button" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
