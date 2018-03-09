<?php
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
		header('location: ../../../home');

	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../ini_DB.php");

    if(isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])){
        $reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON roles.id_role = users.role WHERE id_users = ".$_SESSION["id_user"]);
        $user = $reqUser->fetch();
        if($user['value'] > 10){
            if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['role']) && !empty($_POST['role'])){
                $userid = $bdd->quote($_POST['user']);
                $role = $bdd->quote($_POST['role']);
                $bdd->query("UPDATE users SET role=$role WHERE id_users=$userid");
                echo "ok";
            }
        }
    }
?>
