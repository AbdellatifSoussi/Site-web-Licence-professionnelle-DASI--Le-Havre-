<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_modules_teach = ( isset($_POST['id_modules_teach'] ) ? $_POST['id_modules_teach']  : "" );
    $id_user   = ( isset($_SESSION['id_user']) ? $_SESSION['id_user'] : "" );

    $arr      = array();
    $arrError = array();

    /* TEST */
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
            DELETE FROM modules_teach 
            WHERE id = :id
        ");

        $insertModule->execute(array(
            ":id" => $id_modules_teach
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
