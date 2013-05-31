<?php
  // initialize
  include_once('../../common/init.php');

  // include needed database functions
  include_once($BASE_PATH . 'database/users.php');

  if(!isset($_GET['sort']) || !validSorting($_GET['sort'])) {
  	$_GET['sort'] = "reputation";
  }

  $users = getUsersWithSorting($_GET['sort'], null, null);

 	foreach($users as &$user) {
 		$user['username'] = htmlspecialchars(stripslashes($user['username']));
 		$user['registrationdate_p'] = getNormalDate($user['registrationdate']);
 		$user['lastaccess_p'] = getNormalDate($user['lastaccess']);
 		$user['gravatar'] = "http://www.gravatar.com/avatar/".md5(strtolower(trim($user['email'])))."?s=60&r=pg&d=identicon";
 	}

 	// send data to smarty
 	$smarty->assign('sorted_users', $users);
	$smarty->assign('sort_method', $_GET['sort']);
	$smarty->assign('number_users', count($users));

	// display smarty template
	$smarty->display('users/list.tpl');

	// sorting available: reputation, new, voters, active
	function validSorting($sort) {
		return ($sort == "reputation" || $sort == "new" || $sort == "active" || $sort == "voters" || $sort == "popular");
	}
?>
