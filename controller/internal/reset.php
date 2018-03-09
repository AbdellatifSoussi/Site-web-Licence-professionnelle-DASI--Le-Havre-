<?php
     function rrmdir($dir) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != ".." && $object != ".htaccess") { 
                    if (is_dir($dir."/".$object))
                    rrmdir($dir."/".$object);
                    else
                    unlink($dir."/".$object); 
                } 
            }
        }
        @rmdir($dir);
    }
	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../model/ini_DB.php");

	if(!isset($_SESSION["id_user"]))
		header('location: login');

	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON roles.id_role = users.role WHERE id_users = ".$_SESSION["id_user"]);
    $user = $reqUser->fetch();
    if($user['value'] < 100){
        header('Location: dashboard');
    }
    setlocale(LC_TIME, "fr_FR");
    if(isset($_POST['submit'])){
        $retour = "";
        if(isset($_POST['groups'])){
            $bdd->query("TRUNCATE students_projects");
            $retour .= "Les groupes de projets ont été supprimés !<br/>";
        }
        if(isset($_POST['projects'])){
            $bdd->query("TRUNCATE projects");
            $retour .= "Les projets ont été supprimés !<br/>";
        }
        if(isset($_POST['works'])){
            echo rrmdir(realpath("../../works_files"));
            $bdd->query("TRUNCATE works");
            $retour .= "Les devoirs ont été supprimés !<br/>";
        }
        if(isset($_POST['students'])){
            $bdd->query("DELETE FROM users WHERE role='1'");
            $retour .= "Les comptes des étudiants ont été supprimés !";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>LP DASI - Remise à zéro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="view/css/general/general.css">
    <link rel="stylesheet" href="framework/flexbox/flexboxgrid.min.css">
    <link rel="stylesheet" href="framework/fontAwesome/css/font-awesome.min.css">
    <link rel="icon" href="view/images/general/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto:400,700" rel="stylesheet">
</head>
<body>
  <?php include_once('../../view/import-HTML/header.php'); ?>
	<section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Remise à zéro</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-o">
                        <form action="" method="post">
                            <input type="checkbox" name="groups" id="groups">
                            <label for="groups">Supprimer tous les groupes de travail</label><br/>
                            <input type="checkbox" name="projects" id="projects">
                            <label for="projects">Supprimer tous les projets</label><br/>
                            <input type="checkbox" name="works" id="works">
                            <label for="works">Supprimer tous les devoirs et tous les fichiers</label><br/>
                            <input type="checkbox" name="students" id="students">
                            <label for="students">Supprimer tous les comptes étudiants</label><br/>
                            <input type="submit" value="Remise à zéro" name="submit" class="field-submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</section>
	<script src="framework/jquery/jquery-3.2.1.min.js"></script>
</body>
</html>
