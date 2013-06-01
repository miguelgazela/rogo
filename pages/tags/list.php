<?php
	// initialize
	include_once('../../common/init.php');

	// include needed database functions
	include_once($BASE_PATH . 'database/tags.php');

	if(!isset($_GET['sort']) || !validSorting($_GET['sort'])) {
		$_GET['sort'] = "popular";
	}
	if(!isset($_GET['page']) || !is_numeric($_GET['page'])) {
		$_GET['page'] = "1";
	}

	$pageNumber = intval($_GET['page']);
	$tags = getTagsWithSorting($_GET['sort'], 10*$pageNumber, 0);
	$counter = getNumberOfTagsWithSorting($_GET['sort']);

	foreach($tags as &$tag) {
		$tag['tagname'] = htmlspecialchars(stripslashes($tag['tagname']));
		$tag['creationdate_p'] = getPrettyDate($tag['creationdate']);
	}

	// send data to smarty
	$smarty->assign('sorted_tags', $tags);
	$smarty->assign('sort_method', $_GET['sort']);
	$smarty->assign('number_tags', count($tags));
	$smarty->assign('total_number_tags', count($counter));
	$smarty->assign('page', $pageNumber);

	// display smarty template
	$smarty->display('tags/list.tpl');

	// sorting available: popular, new, name
	function validSorting($sort) {
		return ($sort == "popular" || $sort == "name" || $sort == "new");
	}
?>
