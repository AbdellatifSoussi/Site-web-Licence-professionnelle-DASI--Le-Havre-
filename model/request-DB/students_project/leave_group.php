<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_project = ( isset($_POST['id_project'] ) ? $_POST['id_project'] : "" );
    $id_student = ( isset($_SESSION['id_user'] ) ? $_SESSION['id_user'] : "" );
    $is_boss = false;

	$ret = "";

    /* TEST */
    $reqProject = $bdd->query("SELECT id_student_project FROM students_project WHERE project = ".$id_project." AND student = ".$id_student." AND is_boss = 1");
    if( $testProject = $reqProject->fetch() )
        $is_boss = true;
	/* END TEST */

	/* REQUETE */
	if(empty($ret)) {
		$bdd->exec("DELETE FROM students_project WHERE project = ".$id_project.((!$is_boss) ? " AND student = ".$id_student : "" ));
	}
	/* END REQUETE */

	/* RETOUR DU MESSAGE EN LISTE */
	if(!empty($ret))
        $arr = array('error' => 1, 'message' => '<ul>'.$ret.'</ul>');
    else
        $arr = array('error' => 0);

	echo json_encode($arr);
	/* END RETOUR DU MESSAGE EN LISTE */
?>
