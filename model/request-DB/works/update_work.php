<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $name        = ( isset($_POST['name']        ) ? $_POST['name']        : "" );
    $description = ( isset($_POST['description'] ) ? $_POST['description'] : "" );
    $date        = ( isset($_POST['date']        ) ? $_POST['date']        : "" );
    
    $splitDate = explode("-", $date);
    $splitAnneHeure = explode(" ", $splitDate[2]);
    $date = $splitAnneHeure[0].'-'.$splitDate[1].'-'.$splitDate[0].' '.$splitAnneHeure[1];
    
    $id_module   = ( isset($_POST['id_module']   ) ? $_POST['id_module']   : "" );
    $id_teacher  = ( isset($_SESSION['id_user']  ) ? $_SESSION['id_user']  : "" );
    $id_work     = ( isset($_POST['id_work']     ) ? $_POST['id_work']     : "" );

    $arr      = array();
    $arrError = array();
    
	/* TEST */
	if(empty($name) OR empty($id_module) OR empty($id_teacher) OR empty($id_work) OR empty($description) OR empty($date))
        array_push($arrError, "Veuillez remplir le nom, le module et le professeur.");
        

	$selectData = $bdd->prepare('
        SELECT id_work 
        FROM works 
        WHERE 
            id_work    = :id_work    AND 
            teacher = :teacher
    ');    
    $selectData->execute(array(
        ':id_work'    => $id_work,
        ':teacher' => $id_teacher
    ));
    if( !$data = $selectData->fetch() )
        array_push($arrError, "Le travail sélectionné n'existe pas.");


    $selectData = $bdd->prepare('
        SELECT id_users 
        FROM users 
        WHERE id_users = :id_users
    ');    
    $selectData->execute(array(
        ':id_users'=> $id_teacher
    ));
    if( !$data = $selectData->fetch() )
        array_push($arrError, "Le professeur sélectionné n'existe pas.");


    $selectData = $bdd->prepare('
        SELECT id_module 
        FROM modules 
        WHERE id_module = :id_module
    ');    
    $selectData->execute(array(
        ':id_module' => $id_module
    ));
    if( !$data = $selectData->fetch() )
        array_push($arrError, "Le module sélectionné n'existe pas.");
	/* END TEST */

	/* REQUETE */
	if(empty($arrError)) {
		$insertWork = $bdd->prepare("
            UPDATE works 
            SET 
                name = :name, 
                work_description = :work_description, 
                date = :date, 
                module = :module, 
                teacher = :teacher
            WHERE id_work = :id_work
        ");

        $insertWork->execute(array(
            ":name"             => $name,
            ":work_description" => $description,
            ":date"             => $date,
            ":module"           => $id_module,
            ":teacher"          => $id_teacher,
            ":id_work"          => $id_work
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
