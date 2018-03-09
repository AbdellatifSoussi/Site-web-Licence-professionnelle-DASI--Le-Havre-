<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_user   = ( isset($_SESSION['id_user']  ) ? $_SESSION['id_user'] : "" );
    $mail      = ( isset($_POST['mail']        ) ? $_POST['mail']       : "" );

    $arr      = array();
    $arrError = array();

	/* TEST */

	if(empty($id_user))
        array_push($arrError, "Veuillez vous reconnecter.");

	if(empty($mail))
        array_push($arrError, "Veuillez remplir tous les champs.");

	if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
        array_push($arrError, "Le format de l'adresse mail est non valide.");

	if(empty($ret)){
		$selectData = $bdd->prepare('
            SELECT id_users 
            FROM users 
            WHERE 
                mail      = :mail    AND 
                id_users != :id_users
        ');    
        $selectData->execute(array(
            ':mail'     => $mail,
            ':id_users' => $id_user
        ));
        if( $data = $selectData->fetch() )
            array_push($arrError, "L'adresse mail est déjà utilisé.");
	}
	/* END TEST */

	/* REQUETE */
	if(empty($arrError)) {
		$updateUser = $bdd->prepare("
            UPDATE users SET mail = :mail
            WHERE id_users = :id_users
        ");
		$updateUser->execute(array(
            ":mail"     => $mail,
            ":id_users" => $id_user
        ));
        
        $arr['codeError'] = 0;
    }
    else {
        $arr['codeError'] = 1;
        $arr['messageError'] = $arrError;
    }
	/* END REQUETE */

	/* RETOUR DU MESSAGE EN LISTE */
	    echo json_encode($arr);
	/* END RETOUR DU MESSAGE EN LISTE */
?>
