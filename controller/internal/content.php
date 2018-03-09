<?php
	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../model/ini_DB.php");

	if(!isset($_SESSION["id_user"]))
		header('location: login');

	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON roles.id_role = users.role WHERE id_users = ".$_SESSION["id_user"]);
	$user = $reqUser->fetch();
    setlocale(LC_TIME, "fr_FR");
    if($user['value'] < 100){
        header('Location: dashboard');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>LP DASI - Contenu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="view/css/general/general.css">
    <link rel="stylesheet" href="framework/flexbox/flexboxgrid.min.css">
    <link rel="stylesheet" href="framework/fontAwesome/css/font-awesome.min.css">
    <link rel="icon" href="view/images/general/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto:400,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="framework/dateTimePicker/DateTimePicker.min.css" />
</head>
<body>
  <?php include_once('../../view/import-HTML/header.php'); ?>
	<section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Gestion du contenu</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-o">
                        Vous pouvez modifier le contenu du site vitrine via les différents liens !
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="card-o">
                        <h2>Présentation</h2>
                        <a href="content-presentation" class="btn">
                            Modifier
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="card-o">
                        <h2>Admission</h2>
                        <a href="content-admission" class="btn">
                            Modifier
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="card-o">
                        <h2>Modules</h2>
                        <a href="content-modules" class="btn">
                            Modifier
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="card-o">
                        <h2>Informations pratiques</h2>
                        <a href="content-infos" class="btn">
                            Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>
	</section>
	<script src="framework/jquery/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
    <script src="framework/aceEditor/textarea-as-ace-editor.min.js"></script>
	<script type="text/javascript">
        $(document).ready(function(){
            $("#textarea").asAceEditor();
            editor = $("#textarea").data('ace-editor');
            editor.setOptions({
                useWrapMode: false,
                highlightActiveLine: true,
                showPrintMargin: false,
                theme: 'ace/theme/monokai',
                mode: 'ace/mode/html'
            });
            editor.getSession().setValue($("#textarea").val());
        });
	</script>
</body>
</html>
