<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    $id_student = ( isset($_SESSION['id_user'] ) ? $_SESSION['id_user'] : "" );
    $id_project = ( isset($_POST['id_project'] ) ? $_POST['id_project'] : "" );
    $is_boss    = 1;

	$ret = "";

	/* TEST */
	if(empty($id_student) OR empty($id_project) OR strcmp($is_boss, "") == 0)
		$ret .= "<li>Veuillez remplire tous les champs.</li>";

	$reqUserGroupe = $bdd->query("SELECT id_student_project FROM students_project WHERE student = ".$id_student." AND project = ".$id_project);
    if( $testUserGroupe = $reqUserGroupe->fetch() )
		$ret .= "<li>L'utilisateur sélectionné est déjà inscrit dans ce projet.</li>";

	$reqUsers = $bdd->query("SELECT id_users FROM users WHERE id_users = ".$id_student);
    if( !$testUsers = $reqUsers->fetch() )
		$ret .= "<li>L'utilisateur sélectionné n'existe pas.</li>";

	$reqProject = $bdd->query("SELECT id_project FROM projects WHERE id_project = ".$id_project);
    if( !$testProject = $reqProject->fetch() )
		$ret .= "<li>Le projet sélectionné n'existe pas.</li>";
	/* END TEST */

	/* REQUETE */
	if(empty($ret)) {
		$insertStudentProject = $bdd->prepare("INSERT INTO students_project(student , project , is_boss )
										VALUES                             (:student, :project, :is_boss)");

        $insertStudentProject->execute(array(
            ":student" => $id_student,
            ":project" => $id_project,
            ":is_boss" => $is_boss
        ));
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
