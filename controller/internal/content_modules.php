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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>LP DASI - Modules</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="view/css/general/general.css">
    <link rel="stylesheet" href="framework/flexbox/flexboxgrid.min.css">
    <link rel="stylesheet" href="framework/fontAwesome/css/font-awesome.min.css">
    <link rel="icon" href="view/images/general/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto:400,700" rel="stylesheet">
    <style>
        .ui-draggable-dragging{
            width: auto;
        }
    </style>
</head>
<body>
  <?php include_once('../../view/import-HTML/header.php'); ?>
	<section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Gestion des modules</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h2>Enseignants</h2>
                    <div class="row">
                        <?php
                        $teachers = $bdd->query("SELECT users.id_users, users.firstname, users.lastname FROM users INNER JOIN roles ON users.role=roles.id_role WHERE roles.value>=10")->fetchAll();
                        foreach($teachers as $teacher): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                <div class="card-o teachers" data-id="<?php echo $teacher['id_users']; ?>">
                                    <?php echo $teacher['firstname']." ".$teacher['lastname']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h2>Modules</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <br>
                    <a class="btn left" id="btn-add-module">Ajouter un module</a>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12" style="margin-top:20px;">
                    <table class="table table-responsive table-striped">
                        <tr>
                            <th>Titre</th>
                            <th>Sous titre</th>
                            <th>Description</th>
                            <th>Coefficient</th>
                            <th>Enseignants</th>
                            <th>Modifier le module</th>
                            <th>Supprimer le module</th>
                        </tr>
                        <?php
                        $modules = $bdd->query("SELECT * FROM modules ORDER BY title")->fetchAll();
                        foreach($modules as $module): ?>
                            <tr class="modules" data-id="<?php echo $module['id_module']; ?>">
                                <td class="title"><?php echo $module['title'] ?></td>
                                <td class="subtitle"><?php echo $module['subtitle'] ?></td>
                                <td class="description"><?php echo $module['description'] ?></td>
                                <td class="coeff"><?php echo $module['coefficient'] ?></td>
                                <td class="teachers_module">                                        
                                    <?php
                                    $modules_teach = $bdd->query("SELECT * FROM modules_teach INNER JOIN users ON modules_teach.id_teach = users.id_users WHERE id_module = ".$module['id_module']."")->fetchAll();
                                    foreach($modules_teach as $teach): ?>
                                        <div class="teacher_module">
                                            <a class="delete_teach" teach-id="<?php echo $teach['id']; ?>">x</a>
                                            <span class="teach_name"><?php echo $teach['firstname']; ?> <?php echo $teach['lastname']; ?></span>
                                            <span class="name_hours"><?php echo $teach['nb_hours']; ?>h</span>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                                <td><a class="btn btn-update-module" idModule="<?php echo $module['id_module']; ?>">Modifier</a></td>
                                <td><a class="btn red btn-delete-module" idModule="<?php echo $module['id_module']; ?>">Supprimer</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
    

    <!-- MODAL ADD MODULE -->
    <div class="modal" id="modal-add-module">
		<div class="modal_content container">
            <i class="fa fa-close"></i>
            
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2 style="text-align:center;">Ajouter un module</h2>
				</div>
            </div>
            
			<div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="field">
						<input type="text" id="add-module-title" class="field-input" required>
						<label for="add-module-title" class="field-label">Titre</label>
                    </div>
                    <div class="field">
						<input type="text" id="add-module-subtitle" class="field-input" required>
						<label for="add-module-subtitle" class="field-label">Sous titre</label>
                    </div>
                    <div class="field">
                        <input type="text" id="add-module-coeff" class="field-input" required>
                        <label for="add-module-coeff" class="field-label">Coefficient</label>
                    </div>
                </div>               
				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="field">
                        <textarea id="add-module-description" rows="10" class="field-input"></textarea>
                        <label for="add-module-description" class="field-label">Description</label>
                    </div>
				</div>
            </div>
            <div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="submit" id="add-module" value="Ajouter le module" class="field-submit">
				</div>
            </div>            
		</div>
    </div>
    <!-- END MODAL ADD MODULE -->
    

    <!-- MODAL UPDATE MODULE -->
    <div class="modal" id="modal-update-module">
		<div class="modal_content container">
            <i class="fa fa-close"></i>
            
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2 style="text-align:center;">Modifier un module</h2>
				</div>
            </div>
            
			<div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="field has-content">
						<input type="text" id="update-module-title" class="field-input" required>
						<label for="update-module-title" class="field-label">Titre</label>
                    </div>
                    <div class="field has-content">
						<input type="text" id="update-module-subtitle" class="field-input" required>
						<label for="update-module-subtitle" class="field-label">Sous titre</label>
                    </div>
                    <div class="field has-content">
                        <input type="text" id="update-module-coeff" class="field-input" required>
                        <label for="update-module-coeff" class="field-label">Coefficient</label>
                    </div>
                </div>              
				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="field has-content">
                        <textarea id="update-module-description" rows="10" class="field-input"></textarea>
                        <label for="update-module-description" class="field-label">Description</label>
                    </div>
				</div>
            </div>
            
            <div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="submit" id="update-module" value="Modifier le module" class="field-submit">
				</div>
            </div>            
		</div>
    </div>
    <!-- END MODAL UPDATE MODULE -->
    

	<script src="framework/jquery/jquery-3.2.1.min.js"></script>
	<script src="framework/jquery-ui/jquery-ui.min.js"></script>
	<script type="text/javascript">
        $(document).ready(function(){
            $(".teachers").draggable({
                helper: "clone",
                cursor: "move",
                cursorAt: {
                    top: 24,
                    left: 10
                },
            });
            $(".modules").droppable({
                drop:function(event, ui){
                    var heure = parseInt(prompt("Combien d'heures d'enseignement pour cet enseignant ?"));
                    if(heure != null && heure != 0 && heure != "" && Number.isInteger(heure)){
                        $(this).children(".teachers_module").append('<div class="teacher_module"><span class="teach_name">'+$(ui.draggable).html()+'</span><span class="name_hours">'+heure+'h</span></div>');
                    }

                    var id_module = $(this).attr("data-id");
                    var id_teach  = $(ui.draggable).attr("data-id");
                    var nb_hours  = heure;


                    $.ajax({
                        type : "POST",
                        url: "model/request-DB/modules_teach/insert_modules_teach.php",
                        data : {
                            id_module :id_module,
                            id_teach  :id_teach,
                            nb_hours  :nb_hours
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                        }
                    });
                }
            });
            $(".modal").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				if($(e.target).attr('class') == "modal"){
					$(this).fadeOut("fast");
				};
			});
			$(".fa-close").click(function(e){
				e.preventDefault();
				$(this).parent().parent().fadeOut();
			});

            $(".field-input").focus(function(){
				$(this).parent().addClass("is-focused has-content");
			});
			$(".field-input").bind("blur", function(){
				$(this).parent().removeClass("is-focused");

				if($(this).val() == ""){
					$(this).parent().removeClass("has-content");
				}
			});
            $("label").click(function(){
                $("#"+$(this).attr("for")).focus();
            });

            $(".btn-update-module").click(function(e){                
				e.preventDefault();
				$("#modal-update-module").css("display", "flex").hide().fadeIn();
                
                $("#update-module-title").val($(this).parent().parent().find(".title").text());
                $("#update-module-subtitle").val($(this).parent().parent().find(".subtitle").text());
                $("#update-module-description").val($(this).parent().parent().find(".description").text());
                $("#update-module-coeff").val($(this).parent().parent().find(".coeff").text());
                $("#update-module").attr('idmodule', $(this).attr('idmodule'));
            });
            $("#update-module").click(function(e){
				e.preventDefault();
                var title       = $("#update-module-title").val();
                var subtitle    = $("#update-module-subtitle").val();
                var description = $("#update-module-description").val();
                var coeff       = $("#update-module-coeff").val();                
                var idModule    = $("#update-module").attr('idmodule');

                $.ajax({
                    type : "POST",
                    url: "model/request-DB/modules/update_module.php",
                    data : {
                        title       :title,
                        subtitle    :subtitle,
                        description :description,
                        coeff       :coeff,
                        idModule    :idModule
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        if(data.codeError == 0)
                            location.reload();
                    }
                });
            });
            $(".btn-delete-module").click(function(e){
                var id_module = $(this).attr("idModule");

                $.ajax({
                    type : "POST",
                    url: "model/request-DB/modules/delete_module.php",
                    data : {
                        id_module :id_module
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.codeError == 0)
                            location.reload();
                    }
                });

            });
            $("#btn-add-module").click(function(e){
				e.preventDefault();
				$("#modal-add-module").css("display", "flex").hide().fadeIn();
            });
            $("#add-module").click(function(e){
				e.preventDefault();
                var title       = $("#add-module-title").val();
                var subtitle    = $("#add-module-subtitle").val();
                var description = $("#add-module-description").val();
                var coeff       = $("#add-module-coeff").val();
                
                $.ajax({
                    type : "POST",
                    url: "model/request-DB/modules/insert_module.php",
                    data : {
                        title       :title,
                        subtitle    :subtitle,
                        description :description,
                        coeff       :coeff
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.codeError == 0)
                            location.reload();
                    }
                });
            });
            $(".delete_teach").click(function(e){
				e.preventDefault();
                
                var id_modules_teach = $(this).attr("teach-id");

                $.ajax({
                    type : "POST",
                    url: "model/request-DB/modules_teach/delete_modules_teach.php",
                    data : {
                        id_modules_teach :id_modules_teach
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                    }
                });
                
                $(this).parent().remove();
            });
        });
	</script>
</body>
</html>
