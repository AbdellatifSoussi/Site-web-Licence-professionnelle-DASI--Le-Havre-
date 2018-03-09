<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_teach  = ( isset($_POST['id_teach']   ) ? $_POST['id_teach']   : "" );
    $id_module = ( isset($_POST['id_module']  ) ? $_POST['id_module']  : "" );
    $nb_hours  = ( isset($_POST['nb_hours']   ) ? $_POST['nb_hours']   : "" );
    $id_user   = ( isset($_SESSION['id_user'] ) ? $_SESSION['id_user'] : "" );

    $arr      = array();
    $arrError = array();

	/* TEST */
	if(empty($id_teach) AND empty($id_module) AND empty($nb_hours))
        array_push($arrError, "Veuillez remplire les champs obligatoires.");

    $selectData = $bdd->prepare('
        SELECT id_module 
        FROM modules 
        WHERE 
            id_module = :id_module
    ');    
    $selectData->execute(array(
        ':id_module'=> $id_module
    ));
    if( !$testTitle = $selectData->fetch() )
        array_push($arrError, "Le module n'existe pas.");
        
    $selectData = $bdd->prepare('
        SELECT id_users 
        FROM users 
        WHERE 
            id_users = :id_users
    ');    
    $selectData->execute(array(
        ':id_users'=> $id_teach
    ));
    if( !$testTitle = $selectData->fetch() )
        array_push($arrError, "L'enseignant n'existe pas.");

    
    $selectData = $bdd->prepare('
        SELECT id_users 
        FROM users 
        WHERE 
            id_users = :id_users AND
            role = 3
    ');    
    $selectData->execute(array(
        ':id_users'=> $id_user
    ));
    if( !$data = $selectData->fetch() )
        array_push($arrError, "L'utilisateur n'est pas administrateur.");
	/* END TEST */

	/* REQUETE */
	if(empty($arrError)) {
		$insertModule = $bdd->prepare("
            INSERT INTO modules_teach(id_module , id_teach , nb_hours )
            VALUES                   (:id_module, :id_teach, :nb_hours)
        ");

        $insertModule->execute(array(
            ":id_module" => $id_module,
            ":id_teach"  => $id_teach,
            ":nb_hours"  => $nb_hours
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
