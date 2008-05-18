<?php

/*************************************************************
 * showSuggestions($word, $id)
 *
 * The showSuggestions function creates the list of up to 10
 * suggestions to return for the given misspelled word.
 *
 * $word - The misspelled word that was clicked on
 * $id - The id of the span containing the misspelled word.
 *
 *************************************************************/
function showSuggestions( $cp, $pspell_link, $word, $id )
{
    $ini  = eZINI::instance( 'spellchecker.ini' );
    $maxSuggestions = $ini->variable( 'SpellcheckerSettings', 'NumberOfMaxSuggestions' );
    $editablePersonalDictionary = ( $ini->variable( 'PersonalDictionarySettings', 'EditablePersonalDictionary' ) == 'enabled' ) ? true : false;

    $retVal = "";

    $suggestions = pspell_suggest($pspell_link, $word);  //an array of all the suggestions that psepll returns for $word.
eZDebug::writeError($suggestions);
    // If the number of suggestions returned by pspell is less than the maximum
    // number, just use the number of suggestions returned.
    $numSuggestions = count($suggestions);
    $tmpNum = min( $numSuggestions, (int)$maxSuggestions );

    if($tmpNum > 0)
    {
        //this creates the table of suggestions.
        //in the onclick event it has a call to the replaceWord javascript function which does the actual replacing on the page
        for($i=0; $i<$tmpNum; $i++)
        {
            $retVal .= "<div class=\"suggestion\" onclick=\"replaceWord('" . addslashes_custom($id) . "', '" . addslashes($suggestions[$i]) . "'); return false;\">" . $suggestions[$i] . "</div>";
        }

        if( $editablePersonalDictionary )
        {
            $retVal .= "<div class=\"addtoDictionary\" onclick=\"addWord('" . addslashes_custom($id) . "'); return false;\">Add To Dictionary</div>";
        }
    }
    else
    {
        $retVal .= "No Suggestions";
    }

    $cp->set_data($retVal);  //the return value - a string containing the table of suggestions.

} // end showSuggestions


/*************************************************************
 * spellCheck($string)
 *
 * The spellCheck function takes the string of text entered
 * in the text box and spell checks it.  It splits the text
 * on anything inside of < > in order to prevent html from being
 * spell checked.  Then any text is split on spaces so that only
 * one word is spell checked at a time.  This creates a multidimensional
 * array.  The array is flattened.  The array is looped through
 * ignoring the html lines and spell checking the others.  If a word
 * is misspelled, code is wrapped around it to highlight it and to
 * make it clickable to show the user the suggestions for that
 * misspelled word.
 *
 * $string - The string of text from the text box that is to be
 *           spell checked.
 *
 *************************************************************/
function spellCheck( $cp, $pspell_link, $string, $varName )
{
    $retVal = "";


    //$string = stripslashes_custom($string); //we only need to strip slashes if magic quotes are on

    $string = remove_word_junk($string);

    //make all the returns in the text look the same
    $string = preg_replace("/\r?\n/", "\n", $string);
   
    //splits the string on any html tags, preserving the tags and putting them in the $words array
    $words = preg_split("/(<[^<>]*>)/", $string, -1, PREG_SPLIT_DELIM_CAPTURE);
   
    $numResults = count($words); //the number of elements in the array.

    $misspelledCount = 0;   
   
    //this loop looks through the words array and splits any lines of text that aren't html tags on space, preserving the spaces.
    for($i=0; $i<$numResults; $i++){
        // Words alternate between real words and html tags, starting with words.
        if(($i & 1) == 0) // Even-numbered entries are word sets.
        {
            $words[$i] = preg_split("/(\s+)/", $words[$i], -1, PREG_SPLIT_DELIM_CAPTURE); //then split it on the spaces

            // Now go through each word and link up the misspelled ones.
            $numWords = count($words[$i]);
            for($j=0; $j<$numWords; $j++)
            {
                preg_match("/[A-Z']{1,16}/i", $words[$i][$j], $tmp); //get the word that is in the array slot $i
                $tmpWord = $tmp[0]; //should only have one element in the array anyway, so it's just assign it to $tmpWord
    
                //And we replace the word in the array with the span that highlights it and gives it an onClick parameter to show the suggestions.
                if(!pspell_check($pspell_link, $tmpWord))
                {
                    $onClick = "onclick=\"setCurrentObject(" . $varName . "); showSuggestions('" . addslashes($tmpWord) . "', '" . $varName . "_" . $misspelledCount . "_" . addslashes($tmpWord) . "'); return false;\"";
                    $words[$i][$j] = str_replace($tmpWord, "<span " . $onClick . " id=\"" . $varName . "_" . $misspelledCount . "_" . $tmpWord . "\" class=\"highlight\">" . stripslashes($tmpWord) . "</span>", $words[$i][$j]); 
                    $misspelledCount++;
                }
    
                $words[$i][$j] = str_replace("\n", "<br />", $words[$i][$j]); //replace any breaks with <br />'s, for html display
            }//end for $j
        }//end if

        else //otherwise, we wrap all the html tags in comments to make them not displayed
        {
            $words[$i] = str_replace("<", "<!--<", $words[$i]);
            $words[$i] = str_replace(">", ">-->", $words[$i]);
        }
    }//end for $i

    $words = flattenArray($words); //flatten the array to be one dimensional.
    $numResults = count($words); //the number of elements in the array after it's been flattened.

    $string = ""; //return string  
   
    //if there were no misspellings, start the string with a 0.
    if($misspelledCount == 0)
    {
        $string = "0";
    }

    else //else, there were misspellings, start the string with a 1.
    {
        $string = "1";
    }

    // Concatenate all the words/tags/etc. back into a string and append it to the result.
    $string .= implode('', $words);

    //remove comments from around all html tags except for <a> because we don't want the links to be clickable
    //but we want the html to be rendered in the div for preview purposes.
    $string = preg_replace("/<!--<br( [^>]*)?>-->/i", "<br />", $string);
    $string = preg_replace("/<!--<p( [^>]*)?>-->/i", "<p>", $string);
    $string = preg_replace("/<!--<\/p>-->/i", "</p>", $string);
    $string = preg_replace("/<!--<b( [^>]*)?>-->/i", "<b>", $string);
    $string = preg_replace("/<!--<\/b>-->/i", "</b>", $string);
    $string = preg_replace("/<!--<strong( [^>]*)?>-->/i", "<strong>", $string);
    $string = preg_replace("/<!--<\/strong>-->/i", "</strong>", $string);
    $string = preg_replace("/<!--<i( [^>]*)?>-->/i", "<i>", $string);
    $string = preg_replace("/<!--<\/i>-->/i", "</i>", $string);
    $string = preg_replace("/<!--<small( [^>]*)?>-->/i", "<small>", $string);
    $string = preg_replace("/<!--<\/small>-->/i", "</small>", $string);
    $string = preg_replace("/<!--<ul( [^>]*)?>-->/i", "<ul>", $string);
    $string = preg_replace("/<!--<\/ul>-->/i", "</ul>", $string);
    $string = preg_replace("/<!--<li( [^>]*)?>-->/i", "<li>", $string);
    $string = preg_replace("/<!--<\/li>-->/i", "</li>", $string);
    $string = preg_replace("/<!--<img (?:[^>]+ )?src=\"?([^\"]*)\"?[^>]*>-->/i", "<img src=\"\\1\" />", $string);

    $cp->set_data($string);  //return value - string containing all the markup for the misspelled words.

} // end spellCheck


/*************************************************************
 * addWord($str)
 *
 * This function adds a word to the custom dictionary
 *
 * @param $str The word to be added
 *************************************************************/
function addWord( $cp, $pspell_link, $str )
{
    $ini  = eZINI::instance( 'spellchecker.ini' );
    $editablePersonalDictionary = ( $ini->variable( 'SpellcheckerSettings', 'EditablePersonalDictionary' ) == 'enabled' ) ? true : false;

    $retVal = "";
    pspell_add_to_personal($pspell_link, $str);
    if( $editablePersonalDictionary && pspell_save_wordlist($pspell_link) )
    {
        $retVal = "Save successful!";
    }

    else
    {
        $retVal = "Save Failed!";
    }

    $cp->set_data($retVal);
} // end addWord



/*************************************************************
 * flattenArray($array)
 *
 * The flattenArray function is a recursive function that takes a
 * multidimensional array and flattens it to be a one-dimensional
 * array.  The one-dimensional flattened array is returned.
 *
 * $array - The array to be flattened.
 *
 *************************************************************/
function flattenArray($array)
{
    $flatArray = array();
    foreach($array as $subElement)
    {
        if(is_array($subElement))
        {
            $flatArray = array_merge($flatArray, flattenArray($subElement));
        }
        else
        {
            $flatArray[] = $subElement;
        }
    }

    return $flatArray;
} // end flattenArray


/*************************************************************
 * stripslashes_custom($string)
 *
 * This is a custom stripslashes function that only strips
 * the slashes if magic quotes are on.  This is written for
 * compatibility with other servers in the event someone doesn't
 * have magic quotes on.
 *
 * $string - The string that might need the slashes stripped.
 *
 *************************************************************/
function stripslashes_custom($string)
{
    if(get_magic_quotes_gpc())
    {
        return stripslashes($string);
    }
    else
    {
        return $string;
    }
} // end stripslashes_custom

/*************************************************************
 * addslashes_custom($string)
 *
 * This is a custom addslashes function that only adds
 * the slashes if magic quotes are off.  This is written for
 * compatibility with other servers in the event someone doesn't
 * have magic quotes on.
 *
 * $string - The string that might need the slashes added.
 *
 *************************************************************/
function addslashes_custom($string)
{
    if(!get_magic_quotes_gpc())
    {
        return addslashes($string);
    }
    else
    {
        return $string;
    }
} // end addslashes_custom


/*************************************************************
 * remove_word_junk($t)
 *
 * This function strips out all the crap that Word tries to
 * add to it's text in the even someone pastes in code from
 * Word.
 *
 * $t - The text to be cleaned
 *
 *************************************************************/
function remove_word_junk($t)
{
    $a=array(
    "\xe2\x80\x9c"=>'"',
    "\xe2\x80\x9d"=>'"',
    "\xe2\x80\x99"=>"'",
    "\xe2\x80\xa6"=>"...",
    "\xe2\x80\x98"=>"'",
    "\xe2\x80\x94"=>"---",
    "\xe2\x80\x93"=>"--",
    "\x85"=>"...",
    "\221"=>"'",
    "\222"=>"'",
    "\223"=>'"',
    "\224"=>'"',
    "\x97"=>"---",
    "\x96"=>"--"
    );

    foreach($a as $k=>$v){
        $oa[]=$k;
        $ra[]=$v;
    }

    $t=trim(str_replace($oa,$ra,$t));
    return $t;

} // end remove_word_junk


/*************************************************************
 * switchText($string)
 *
 * This function prepares the text to be sent back to the text
 * box from the div.  The comments are removed and breaks are
 * converted back into \n's.  All the html tags that the user
 * might have entered that aren't on the approved list:
 * <p><br><a><b><strong><i><small><ul><li> are stripped out.
 * The user-entered returns have already been replaced with
 * $u2026 so that they can be preserved.  I replace all the 
 * \n's that might have been added by the browser (Firefox does
 * this in trying to pretty up the HTML) with " " so that 
 * everything will look the way it did when the user typed it
 * in the box the first time.
 *
 * $string - The string of html from the div that will be sent
 *           back to the text box.
 *
 *************************************************************/
function switchText( $cp, $pspell_link, $string )
{
    $ini  = eZINI::instance( 'spellchecker.ini' );
    $allowedTagList = $ini->variable( 'SpellcheckerSettings', 'AllowedHTMLTags' );
    $allowed_html = '';

    foreach ( $allowedTagList as $tag )
    {
        if ( $tag != '' )
        {
            $allowed_html .= '<' . $tag . '>';
        }
    }

    $string = remove_word_junk($string);
    $string = preg_replace("/<!--/", "", $string);
    $string = preg_replace("/-->/", "", $string);
    $string = preg_replace("/\r?\n/", " ", $string);
    $string = stripslashes_custom($string); //we only need to strip slashes if magic quotes are on
    $string = strip_tags($string, $allowed_html);
    $string = preg_replace('{&lt;/?span.*?&gt;}i', '', $string);
    $string = html_entity_decode($string);
    $cp->set_data($string); //the return value

} // end switchText

?>