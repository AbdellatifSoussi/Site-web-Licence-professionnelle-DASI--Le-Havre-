<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_user         = ( isset($_SESSION['id_user']     ) ? $_SESSION['id_user']      : "" );
    $old_password    = ( isset($_POST['old_password']   ) ? $_POST['old_password']    : "" );
    $new_password    = ( isset($_POST['new_password']   ) ? $_POST['new_password']    : "" );
    $re_new_password = ( isset($_POST['re_new_password']) ? $_POST['re_new_password'] : "" );

    $arr      = array();
    $arrError = array();

	/* TEST */
	if(empty($id_user))
		array_push($arrError, "Veuillez vous reconnecter.");

	if(strcmp($old_password, "") == 0 OR strcmp($new_password, "") == 0 OR strcmp($re_new_password, "") == 0)
        array_push($arrError, "Veuillez remplir tous les champs.");

	if(strlen($new_password) < 8)
        array_push($arrError, "Votre mot de passe doit faire au minimum 8 caractères.");

	if(strcmp($new_password , $re_new_password) != 0)
        array_push($arrError, "Vos mots de passe ne sont pas identiques.");

    if(empty($arrError)){
        $selectData = $bdd->prepare('
            SELECT id_users 
            FROM users 
            WHERE password = :password
        ');    
        $selectData->execute(array(
            ':password' => hash_hmac('sha256', $old_password, "keyProjetDASI")
        ));
        if(!$data = $selectData->fetch())
            array_push($arrError, "Votre mot de passe est incorrect.");
    }

	/* END TEST */

	/* REQUETE */
	if(empty($arrError)) {
		$updateUser = $bdd->prepare("
            UPDATE users SET password = :password
            WHERE id_users = :id_users
        ");
		$updateUser->execute(array(
            ":password" => hash_hmac('sha256', $new_password, "keyProjetDASI"),
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
