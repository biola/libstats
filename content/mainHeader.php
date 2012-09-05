<div id = "header">
    <div id = "locationInfo">
         <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) { ?>
        <div>
        <?php echo($rInfo['user']['library_full_name']);
		if($rInfo['user']['admin'] == 1){
			echo(' | <a href="libraryAdminForm.do">Admin</a>');
		}				
		?>
         | <?php echo( implode(' | ', $rInfo['taskLinks']) ); ?>
		</div>
		 <form name = "searchForm" action = "search.do" method = "get">
            <div>Quick Search:
            <input type = "text" size = "20" name = "criteria">
            <input type = "submit" name = "searchButton" value = "Go">
			<input type = "hidden" name = "origin"
				value = "<?php echo($rInfo['origin']); ?>" /> |
            <a href = "advancedSearchForm.do<?php if (isset($rInfo['advanced_search_string'])) { echo "?".$rInfo['advanced_search_string']; } ?>">
            Advanced Search</a>
            </div>
         </form>
         <?php } ?>
    </div>
	<h1><?php echo($rInfo['pageTitle']); ?></h1>
    <div style = "clear: both;"> </div>
</div>

