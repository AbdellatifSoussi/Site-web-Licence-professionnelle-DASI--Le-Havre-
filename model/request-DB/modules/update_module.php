<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $title       = ( isset($_POST['title']      ) ? $_POST['title']         : "" );
    $subtitle    = ( isset($_POST['subtitle']   ) ? $_POST['subtitle']      : "" );
    $description = ( isset($_POST['description']) ? $_POST['description']   : "" );
    $coeff       = ( isset($_POST['coeff']      ) ? intval($_POST['coeff']) : "" );
    $id_user     = ( isset($_SESSION['id_user'] ) ? $_SESSION['id_user']    : "" );
    $idModule    = ( isset($_POST['idModule']   ) ? $_POST['idModule']      : "" );

    $arr      = array();
    $arrError = array();

	/* TEST */
	if(empty($title) AND empty($subtitle) AND empty($coeff))
        array_push($arrError, "Veuillez remplire les champs obligatoires.");

    $selectData = $bdd->prepare('
        SELECT id_module 
        FROM modules 
        WHERE 
            title = :title AND
            id_module != :id_module
    ');    
    $selectData->execute(array(
        ':title'=> $title,
        ':id_module'=> $idModule
    ));
    if( $testTitle = $selectData->fetch() )
        array_push($arrError, "Un module dispose déjà de ce nom.");

    if(!is_int($coeff) OR $coeff <= 0)
        array_push($arrError, "Veuillez rentré un coefficient correct.");

    
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


    $selectData = $bdd->prepare('
        SELECT id_module 
        FROM modules 
        WHERE 
            id_module = :id_module
    ');    
    $selectData->execute(array(
        ':id_module'=> $idModule
    ));
    if( !$data = $selectData->fetch() )
        array_push($arrError, "Le module n'existe pas.");
	/* END TEST */

	/* REQUETE */
	if(empty($arrError)) {
        $insertModule = $bdd->prepare("
            UPDATE modules 
            SET 
                title = :title, 
                subtitle = :subtitle, 
                description = :description, 
                coefficient = :coefficient
            WHERE id_module = :id_module
        ");

        $insertModule->execute(array(
            ":title"       => $title,
            ":subtitle"    => $subtitle,
            ":description" => $description,
            ":coefficient" => $coeff,
            ":id_module"   => $idModule
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
