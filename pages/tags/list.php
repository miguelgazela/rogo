<?php
  // initialize
  include_once('../../common/init.php');

  // include needed database functions
  include_once($BASE_PATH . 'database/tags.php');

  if(!isset($_GET['sort']) || !validSorting($_GET['sort'])) {
  	$_GET['sort'] = "popular";
  }

  $tags = getTagsWithSorting($_GET['sort'], null, null);

 	foreach($tags as &$tag) {
 		$tag['tagname'] = htmlspecialchars(stripslashes($tag['tagname']));
 		$tag['creationdate_p'] = getPrettyDate($tag['creationdate']);
 	}

 	// send data to smarty
 	$smarty->assign('sorted_tags', $tags);
	$smarty->assign('sort_method', $_GET['sort']);
	$smarty->assign('number_tags', count($tags));

	// display smarty template
	$smarty->display('tags/list.tpl');

	// sorting available: popular, new, name
	function validSorting($sort) {
		return ($sort == "popular" || $sort == "name" || $sort == "new");
	}
?>
