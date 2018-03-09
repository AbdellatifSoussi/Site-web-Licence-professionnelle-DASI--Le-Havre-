<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_project = ( isset($_POST['idProject'])  ? $_POST['idProject']  : "" );
    $id_user    = ( isset($_SESSION["id_user"]) ? $_SESSION["id_user"] : "" );

	$ret = "";

	/* TEST */
	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON users.role = roles.id_role WHERE id_users = ".$id_user);
	$user = $reqUser->fetch();
	
	$reqProject = $bdd->query("SELECT * FROM projects WHERE id_project = ".$id_project);
    $project = $reqProject->fetch();
		
	if($user['value'] < 10 AND $project["user"] != $id_user)
		$ret .= "<li>Vous n'avez pas l'autorisation.</li>";
	/* END TEST */

	/* REQUETE */
	if(empty($ret)) {
		$bdd->exec("DELETE FROM projects WHERE id_project = ".$id_project);
	}
	/* END REQUETE */

	/* RETOUR DU MESSAGE EN LISTE */
	if(!empty($ret))
		$ret = '<ul>'.$ret.'</ul>';

	echo $ret;
	/* END RETOUR DU MESSAGE EN LISTE */
?>
