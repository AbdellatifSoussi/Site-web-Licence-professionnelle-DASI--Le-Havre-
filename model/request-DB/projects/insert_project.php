<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_module   = ( isset($_POST['module']     ) ? $_POST['module']      : "" );
    $name        = ( isset($_POST['subject']    ) ? $_POST['subject']     : "" );
    $description = ( isset($_POST['description']) ? $_POST['description'] : "" );
    $id_user     = ( isset($_SESSION["id_user"] ) ? $_SESSION["id_user"]  : "" );
	
	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON users.role = roles.id_role WHERE id_users = ".$id_user);
	$user = $reqUser->fetch();
	
    $is_validate = ( ($user['value'] >= 10) ? 1 : 0 );

	$ret = "";

	/* TEST */
	if(empty($id_module) OR empty($name) OR empty($id_user))
        $ret .= "<li>Veuillez remplire le nom, le module et l'utilisateur.</li>";
    
	$reqUsers = $bdd->query("SELECT id_users FROM users WHERE id_users = ".$id_user);
    if( !$testUsers = $reqUsers->fetch() )
		$ret .= "<li>L'utilisateur sélectionné n'existe pas.</li>";

	$reqModule = $bdd->query("SELECT id_module FROM modules WHERE id_module = ".$id_module);
    if( !$testModule = $reqModule->fetch() )
		$ret .= "<li>Le module sélectionné n'existe pas.</li>";
	/* END TEST */

	/* REQUETE */
	if(empty($ret)) {
		$insertProject = $bdd->prepare("
            INSERT INTO projects(module , name , description , user , is_validate )
		    VALUES              (:module, :name, :description, :user, :is_validate)");

        $insertProject->execute(array(
            ":module"      => $id_module,
            ":name"        => $name,
            ":description" => $description,
            ":user"        => $id_user,
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
