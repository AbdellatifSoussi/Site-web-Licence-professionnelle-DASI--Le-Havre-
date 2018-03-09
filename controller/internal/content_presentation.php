<?php
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
    $filename = "../../view/import-HTML/presentation.php";
    if(isset($_POST['submit']) && !empty($_POST['submit'])){
        $handle = fopen($filename, "w+");
        fwrite($handle, $_POST['textarea']);
        fclose($handle);
        $retour = "Le fichier a été modifié !";
    }
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>LP DASI - Présentation</title>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Gestion du contenu</h1>
                    <?php if(isset($retour)): ?>
                        <div class="error success" style="display: block;">
                            <?php echo $retour; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-o">
                        <form action="" method="POST">
                            <h2>Présentation</h2><br/>
                            <textarea id="textarea" name="textarea"><?php echo $contents; ?></textarea>
                            <input type="submit" class="field-submit" name="submit" value="Enregistrer">
                        </form>
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
