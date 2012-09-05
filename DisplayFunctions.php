<?php
/**
 * Some functions to render things that'll come up again and again
 */

function getQuestionTable (
    $tableId,
    $columns,
    $tableData,
    $searchWords = ''
) {
    $tableCode = "";
    $tableCode = "<table id = \"$tableId\">\n";
    $tableCode .= "<tr>";
    foreach ($columns as $header=>$val) {
        $tableCode .= "<th>$header</th>";
    }
    $tableCode .= "</tr>\n";
    foreach ($tableData as $rowData) {
        if($rowData['hide'] != 0) {
            $rowCode = 
                "<tr class = \"emptyRow\" style = \"display: none\">";
        } else {
            $rowCode = "<tr>";
        }

        foreach ($columns as $valKeys) {
            // Do a little special handling
            $rowCode .= "<td>";
            if (!is_array($valKeys)) {
                $valKeys = array($valKeys);
            }
	
            // Loop over everything in valKey. If we can find it in 
            // rowdata, use it at a lookup. Elsewise, use the string
            // in the rowdata
            foreach ($valKeys as $key) {
                if (array_key_exists($key, $rowData)) {
                    // Fix HTML entities... only YOU can prevent
                    // goatse.cx
                    $rowData[$key] = htmlentities($rowData[$key]);

                    // Now special-case format a few things. Want to print
                    // dates in a a nice way...
                    if ($key == 'date_added' || $key == 'question_date') {
                        $rowCode .= date(
                            'n/d/Y<\b\r>g:i A', 
                             strtotime($rowData[$key]));

                    // And make links in questions clickable...
                    } else if ($key == 'question' || $key == 'answer') {
                        $rowCode .= niceQuestionFormat($rowData[$key], $searchWords);
                    } else {
                        $rowCode .= $rowData[$key];
                    }
                }
                else {
                    $rowCode .= "$key";
                }
            }
            $rowCode .= "</td>";
        }
        $rowCode .= "</tr>";
        $tableCode .= "$rowCode\n";
    }
    $tableCode .= "</table>";
    return $tableCode;
}
			
function getSelectPulldown(
    $boxId,
    $data,
    $valueKey,
    $labelKey,
    $selectedValue = null,
    $handlerText = ''
)
{
    return getSelectBox($boxId, $data, $valueKey, $labelKey, $selectedValue, 1, $handlerText);
}

function getSelectBox(
    $boxId,
    $data,
    $valueKey,
    $labelKey,
    $selectedValue = null,
    $size = 1,
    $handlerText = ''
) 
{
    // We *have* to select *something*. If we can't find selectedvalue,
    // just pick the first item. This requires a pass through the data
    // before we start.
    $found = false;
    foreach ($data as $rowIndex => $row) {
        if ($row[$valueKey] == $selectedValue) {
            $found = true;
        }
    }
    
    $marked = false;
    
    $boxCode = "<select name=\"$boxId\" id=\"$boxId\" size=\"$size\" $handlerText>";
    foreach ($data as $rowIndex => $row) {
        $boxCode .= "<option value=\"{$row[$valueKey]}\"";
        if (!$marked && (!$found || $row[$valueKey] == $selectedValue)) {
            $boxCode .= " selected=\"selected\"";
            $marked = true; // flag as "marked" so we don't select everything
        }
        $boxCode .= ">{$row[$labelKey]}</option>";
    }
    $boxCode .= "</select>";
    return $boxCode;
}

function getRadioList(
    $radioName,
    $data,
    $valueKey,
    $labelKey,
    $selectedValue = null
) 
{
    // We *have* to select *something*. If we can't find selectedvalue,
    // just pick the first item. This requires a pass through the data
    // before we start.
    $found = false;
    foreach ($data as $rowIndex => $row) {
        if ($row[$valueKey] == $selectedValue) {
            $found = true;
        }
    }
    $marked = false;

    $listCode = '';
    foreach ($data as $row) {
        $listCode .= "<div><label>";
        $listCode .= "<input type=\"radio\" value=\"{$row[$valueKey]}\"";
        $listCode .= " name=\"$radioName\"";
        // Add Selected if *nothing* matches, or if this is the first match
        if (!$marked && (!$found || $row[$valueKey] == $selectedValue)) {
            $listCode .= " checked=\"checked\"";
            $marked = true;
        }
        $listCode .= ">{$row[$labelKey]}</label></div>";
    }
    return $listCode;
}


// Shamelessly stolen from a user comment on php.net's site.
function insertLinks ( $text )
{
    //Ê First match things beginning with http:// (or other protocols)
    $notAnchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
    $protocol = '(http|ftp|https):\/\/';
    $domain = '[\w]+(.[\w]+)';
    $subdir = '(\S*)?';
    $expr = '/' . $notAnchor . $protocol . $domain . $subdir . '/i';

    $result = preg_replace( $expr, "<a href=\"$0\" title=\"$0\">$0</a>", $text );

    /*
    if ($result != $text) { 
        return $result; 
    }
    */

    //Ê Now match things beginning with www.
    $notAnchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
    $notHTTP = '(?<!:\/\/)';
    $domain = 'www(\.[\w])+';
    // $domain = '[A-Za-z0-9\-.]+(?:com|org|edu|uk)';
    $subdir = '(\S*)?';
    $expr = '/' . $notAnchor . $notHTTP . $domain . $subdir . '/i';

    $result = preg_replace( $expr, "<a href=\"http://$0\" title=\"http://$0\">$0</a>", $result );


    return $result; 
} 

function getCountPulldown($currentCount) {
    $countOptions = array(
        array('data' => '100', 'label' => '100'),
        array('data' => '75', 'label' => '75'),
        array('data' => '50', 'label' => '50'),
        array('data' => '25', 'label' => '25'),
        array('data' => '10', 'label' => '10'));
    
    return getSelectPulldown(
        'count', $countOptions, 'data', 'label', $currentCount
    );
}

function getLayoutPulldown($currentLayout) {
    $layoutOptions = array(
        array('data' => 'pulldown', 'label' => 'Pulldowns'),
        array('data' => 'radio', 'label' => 'Radio Buttons'),
        array('data' => 'menus', 'label' => 'Menus'));
    
    return getSelectPulldown(
        'layout', $layoutOptions, 'data', 'label', $currentLayout
    );
}

function highlightWords($text, $words = '') {
    if (trim($words) == '') return $text;

    $words = trim($words);
	$words = str_replace('"', '', $words);
    $searchWords = '('.preg_replace("/\s+/", "|", $words).')';
	$searchWords = str_replace("*", "\w*", $searchWords); 
	$expr = '/'.$searchWords.'(?!\S*["])/i';

    $result = preg_replace($expr, '<strong>$1</strong>', $text);
    return $result;
}

function niceQuestionFormat($text, $searchWords = '') {
    $result = insertLinks($text);
    $result = highlightWords($result, $searchWords);
    return $result;
}

function getPageNav(
    $baseUrl, 
    $nonemptyQuestionCount, 
    $questionsPerPage, 
    $currentPage,
    $formVars = array()
) {
    $output = 
        '<form action = "'.$baseUrl.'" method = "get" class = "pageNav"><div>';
    foreach ($formVars as $key => $val) {
        $output .= '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
    }
    $output .= "Go to page: " . _getQuestionPageLinks(
        $baseUrl, $nonemptyQuestionCount, $questionsPerPage, $currentPage, 3);
    $output .= '<span class = "countChanger"> Show '. 
        getCountPulldown($questionsPerPage) . 
        " questions per page</span>";
    $output .= ' <input type = "submit" name = "go" value = "Go" /></div></form>';
    return $output;
}

function _getQuestionPageLinks($baseUrl, $nonemptyQuestionCount, $questionsPerPage, $currentPage, $viewBy = 3) {
	if ($questionsPerPage <= 0){
		return null;
	}
	
	// Set this up to append page info properly
	// Use === for strpos, it may return 0.
	if ((strpos($baseUrl, '?') === false)) { // No URL parameters
        $baseUrl .= '?';
    }
    else {
        $baseUrl .= '&amp;';
    }
    
    $nonemptyQuestionCount = $nonemptyQuestionCount + 0.0;
    $questionsPerPage = $questionsPerPage + 0.0;
    
	$qPages = ceil($nonemptyQuestionCount / $questionsPerPage);
	$linkstr = "";
	$loopLeftValueStart = 1;
	$loopLeftValueStop = $viewBy;
	$loopCenterValueStart = ($currentPage - ($viewBy - 1));
	$loopCenterValueStop = ($currentPage + ($viewBy - 1)); 
	$loopRightValueStart = ($qPages - ($viewBy - 1));
	$loopRightValueStop = $qPages;
	$navPrevious = ($currentPage - 1);
	$navNext = ($currentPage + 1);

	//define "Previous" and "Next" buttons
	if ($navPrevious <= 0) {
		$linkNavPreviousStr = "Previous ";
	}
		else {
			$linkNavPreviousStr = '<a href="' . $baseUrl. 'page=' . $navPrevious . '&amp;count=' . $questionsPerPage . '">' . "Previous" . '</a> ';
		}
	
	if ($navNext > $qPages) {
		$linkNavNextStr = " Next";
	}
		else {
			$linkNavNextStr = '<a href="' . $baseUrl. 'page=' . $navNext . '&amp;count=' . $questionsPerPage .'">' . "Next" . '</a> ';
		}
		
	//1st stacking of $linkstr = Left ---> Right
	$linkstr .= $linkNavPreviousStr;
		
	if ($loopLeftValueStart < 1) {
		$loopLeftValueStart = 1;
	}
		
	if ($loopLeftValueStop > $qPages) {
		$loopLeftValueStop = $qPages;
	}
	
	//2nd stack
	$linkstr .= _loopQuestionPageLinks($baseUrl, $loopLeftValueStart, $loopLeftValueStop, $currentPage, $questionsPerPage, $viewBy);
	
	if ($loopCenterValueStart <= $loopLeftValueStop) {
		$loopCenterValueStart = ($loopLeftValueStop + 1);
	}
	
	if ($loopCenterValueStop >= $loopRightValueStart) {
		$loopCenterValueStop = $loopRightValueStart;
	}
	
	if ($loopCenterValueStart > ($viewBy +1)){
		$linkstr .= "... ";	
	}
	
	//3rd stack
	$linkstr .= _loopQuestionPageLinks($baseUrl, $loopCenterValueStart, $loopCenterValueStop, $currentPage, $questionsPerPage, $viewBy);
		
	if ($loopCenterValueStop < ($loopRightValueStart - 1)) {
		$linkstr .= "... ";
	}

	if ($loopRightValueStart <= $loopCenterValueStop) {
		$loopRightValueStart = $loopCenterValueStop + 1;
	}
	
	if ($loopRightValueStart <= $loopLeftValueStop) {
		$loopRightValueStart = ($loopRightValueStop + 1);
	}

	//4th stack
	$linkstr .= _loopQuestionPageLinks($baseUrl, $loopRightValueStart, $loopRightValueStop, $currentPage, $questionsPerPage, $viewBy);
	
	//5th and final stack
	$linkstr .= $linkNavNextStr;
	return $linkstr;
}

function _loopQuestionPageLinks($baseUrl, $start, $stop, $currentPage, $questionsPerPage, $viewBy) {
	//loop to build $linkstr, 1st leftloop, ..., 2nd centerloop, ..., 3rd rightloop
	$linkstr = "";
	
	for ($i = $start; $i <= $stop; $i++) {
		if ($i == $currentPage) {
			$linkstr .= " $i ";
		}
		else {
			$linkstr .=	'<a href="' . $baseUrl. 'page=' . $i . '&amp;count=' . $questionsPerPage .'">' . $i . '</a> ';
		}
	}
	
	return $linkstr;
}

?>