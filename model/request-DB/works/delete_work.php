<?php
    function rrmdir($dir) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if (is_dir($dir."/".$object))
                    rrmdir($dir."/".$object);
                    else
                    unlink($dir."/".$object); 
                } 
            }
        }
        rmdir($dir);
    }
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_teacher  = ( isset($_SESSION['id_user']  ) ? $_SESSION['id_user']  : "" );
    $id_work     = ( isset($_POST['id_work']     ) ? $_POST['id_work']     : "" );

    $arr      = array();
    $arrError = array();
    
	/* TEST */
	if(empty($id_work) OR empty($id_teacher))
        array_push($arrError, "Veuillez remplir le nom, le module et le professeur.");
        

	$work = $bdd->prepare('
        SELECT * 
        FROM works
        INNER JOIN modules
        ON works.module=modules.id_module
        WHERE 
            id_work = :id_work
    ');    
    $work->execute(array(
        ':id_work' => $id_work
    ));
    if( !$data = $work->fetch() )
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
	/* END TEST */

    /* REQUETE */
	if(empty($arrError)) {
        $work = $bdd->prepare('
            SELECT * 
            FROM works
            INNER JOIN modules
            ON works.module=modules.id_module
            WHERE 
                id_work = :id_work
        ');    
        $work->execute(array(
            ':id_work' => $id_work
        ));
        $work = $work->fetch();
        $dir = realpath("../../../works_files/$id_work/");
        rrmdir($dir);
        if(file_exists('../../../works_files/'.$id_work.'_'.$work['title']." - ".$work['name'].'.zip')){
            unlink('../../../works_files/'.$id_work.'_'.$work['title']." - ".$work['name'].'.zip');
        }
		$insertWork = $bdd->prepare("
            DELETE FROM works 
            WHERE id_work = :id_work
        ");

        $insertWork->execute(array(
            ":id_work" => $id_work
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
