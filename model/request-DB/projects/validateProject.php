<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_project  = ( isset($_POST['idProject'] ) ? $_POST['idProject']  : "" );
    $id_user     = ( isset($_SESSION["id_user"] ) ? $_SESSION["id_user"]  : "" );
	
	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON users.role = roles.id_role WHERE id_users = ".$id_user);
	$user = $reqUser->fetch();

	$ret = "";

	/* TEST */
	if(empty($id_project) OR empty($id_user))
		$ret .= "<li>Veuillez remplire le projet et l'utilisateur.</li>";

	$reqUsers = $bdd->query("SELECT id_users FROM users WHERE id_users = ".$id_user);
    if( !$testUsers = $reqUsers->fetch() )
		$ret .= "<li>L'utilisateur sélectionné n'existe pas.</li>";

	$reqProject = $bdd->query("SELECT * FROM projects WHERE id_project = ".$id_project);
    if( !$testProject = $reqProject->fetch() )
		$ret .= "<li>Le projet sélectionné n'existe pas.</li>";
	else
		$is_validate = ( ($user['value'] >= 10) ? 1 : $testProject["is_validate"] );
	/* END TEST */

	/* REQUETE */
	if(empty($ret)) {
		$updateProjects = $bdd->prepare("UPDATE projects SET is_validate  = :is_validate
															 WHERE id_project = ".$id_project."");

        $updateProjects->execute(array(
            ":is_validate" => $is_validate
        ));
	}
	/* END REQUETE */

	/* RETOUR DU MESSAGE EN LISTE */
	if(!empty($ret))
		$ret = '<ul>'.$ret.'</ul>';

	echo $ret;
	/* END RETOUR DU MESSAGE EN LISTE */
?>
