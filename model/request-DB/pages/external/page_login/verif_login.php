<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
        header('location: ../../../../../home');
        
    include_once("../../../../ini_DB.php");

	$email    = ( isset($_POST['email']   ) ? $_POST['email']    : "" );
    $password = ( isset($_POST['password']) ? $_POST['password'] : "" );
    
    $arr      = array();
    $arrError = array();
    
	/* TEST */
	if(empty($email) OR empty($password))
        array_push($arrError, "Veuillez remplir tous les champs.");

	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        array_push($arrError, "Le format de l'adresse mail est non valide.");
    /* END TEST */
    
	if(empty($arrError)) {
		$selectData = $bdd->prepare('
            SELECT * 
            FROM users 
            WHERE 
                mail     = :mail     AND 
                password = :password
        ');    
        $selectData->execute(array(
            ':mail'     => $email,
            ':password' => hash_hmac('sha256', $password, "keyProjetDASI")
        ));
        if($data = $selectData->fetch()) {
			session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
			session_start();
            $_SESSION["id_user"] = $data["id_users"];
        
            $arr['codeError'] = 0;
		}
		else {
            array_push($arrError, "Votre email ou votre mot de passe est incorrect.");

            $arr['codeError'] = 1;
            $arr['messageError'] = $arrError;
        }
    }
    else {
        $arr['codeError'] = 1;
        $arr['messageError'] = $arrError;
    }
    
	/* RETOUR DU MESSAGE EN LISTE */	
        echo json_encode($arr);
    /* END RETOUR DU MESSAGE EN LISTE */
?>
