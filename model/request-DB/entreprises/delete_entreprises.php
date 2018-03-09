<?php
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
        header('location: ../../../home');
    }
	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
    session_start();
    if(isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])){
        include("../../ini_DB.php");
        if(isset($_POST['id_entreprise']) && !empty($_POST['id_entreprise'])){
            $reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON roles.id_role = users.role WHERE id_users = ".$_SESSION["id_user"]);
            $user = $reqUser->fetch();
            if($user['value'] > 10){
                $id = $bdd->quote($_POST['id_entreprise']);
                $bdd->query("DELETE FROM entreprises WHERE id=$id");
            }
        }
    }
?>