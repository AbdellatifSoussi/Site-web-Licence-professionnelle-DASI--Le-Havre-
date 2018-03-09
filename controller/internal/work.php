<?php
  session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
  session_start();
  include_once("../../model/ini_DB.php");

  if(!isset($_SESSION["id_user"]))
    header('location: login');

  if(!isset($_GET['work']) || empty($_GET['work']))
    header('Location: works');

  $id_work = $_GET['work'];

 	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON roles.id_role = users.role WHERE id_users = ".$_SESSION["id_user"]);
 	$user = $reqUser->fetch();

  $work = $bdd->query("SELECT * FROM works INNER JOIN users ON works.teacher = users.id_users INNER JOIN modules ON works.module = modules.id_module WHERE id_work='$id_work'")->fetch();
  if(!$work){
    header('Location: works');
  }
  $date = new DateTime($work['date']);
  $now = new DateTime();
  $date_respected = $now->diff($date);
  if($date_respected->invert && $work['teacher'] != $_SESSION["id_user"]){
    header('location: works');
  }else if($date_respected->invert && $work['teacher'] == $_SESSION["id_user"]){
        //CRéATION DU ZIP AVEC TOUS LES FICHIERS à L'INTERIEUR
        $rootPath = realpath('../../works_files/'.$id_work.'/');
        if(is_dir($rootPath)){
            $zip = new ZipArchive();
            $zip->open('../../works_files/'.$id_work.'_'.$work['title']." - ".$work['name'].'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file){
                if (!$file->isDir()){
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }
    }
    setlocale(LC_TIME, "fr_FR");
?>

 <!DOCTYPE html>
 <html>
 <head>
    <meta charset="utf-8">
    <title>LP DASI - Devoirs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="view/css/general/general.css">
    <link rel="stylesheet" href="framework/flexbox/flexboxgrid.min.css">
    <link rel="stylesheet" href="framework/fontAwesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="framework/fileicon/fileicon.css">
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
                <h1><?php echo $work['title']." - ".$work['name']; ?></h1>
                </div>
            </div>
            <?php if($user['value'] < 10): // ETUDIANT ?>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="card-o">
                        Par <?php echo $work["firstname"]." ".$work['lastname']; ?>
                    </div>
                    </div>
                    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="card-o">
                        Date limite : <?php echo utf8_encode(strftime('%A %d %B %Y', $date->getTimestamp())); ?> à <?php echo utf8_encode(strftime('%H:%M', $date->getTimestamp())); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-o">
                        <?php echo $work['work_description']; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="card-o">
                        <div class="error">

                        </div>
                        <label for="uploader" id="files"></label>
                        <input type="file" id="uploader" multiple>
                        <input type="hidden" id="work" value="<?php echo $id_work; ?>">
                        <div id="progress">
                            <div id="value">
                                0%
                            </div>
                            <div id="bar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="card-o" id="files_list">
                        <?php
                        if(file_exists("../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname'])){
                            $files = scandir("../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname']);
                            unset($files[0]);
                            unset($files[1]);
                            foreach($files as $file){
                            ?>
                            <br>
                            <div style="display: inline-block; vertical-align:middle;" class="file-icon file-icon-lg" data-type="<?php echo pathinfo($file, PATHINFO_EXTENSION); ?>">
                                <div class="delete" onclick="delete_file('<?php echo $file; ?>');">
                                </div>
                            </div>
                            <p style="display: inline-block; vertical-align:middle;" ><?php echo $file; ?></p><br>
                            <?php }
                            if(count($files) == 0){
                            echo "Aucun fichier n'a été envoyé";
                            }else{}
                        } else {
                        echo "Aucun fichier n'a été envoyé";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php else: //ENSEIGNANT ?>
                <?php if($date_respected->invert): //Pret à être récupérer ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php if(file_exists('../../works_files/'.$id_work.'_'.$work['title']." - ".$work['name'].'.zip')): ?>
                                <a class="btn left" href="<?php echo '../../works_files/'.$id_work.'_'.$work['title']." - ".$work['name'].'.zip'; ?>">Télécharger les fichiers</a>
                            <?php else: ?>
                                Aucun fichier n'a été déposé !
                            <?php endif; ?>
                            <a class="btn red right" id="delete" data-work="<?php echo $id_work; ?>" href="#">Supprimer le devoir et les fichiers</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card-o">
                                <p>Vous pouvez télécharger les fichiers déposés par les étudiants. Une fois cela fait, pensez à supprimer votre devoir afin de supprimer les fichiers du serveur.</p>
                            </div>
                        </div>
                    </div>
                <?php else: //temps pas terminé ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <a class="btn left" href="#" id="editWork">Modifier le devoir</a>
                            <a class="btn red right" id="delete" data-work="<?php echo $id_work; ?>" href="#" >Supprimer le devoir et les fichiers</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card-o">
                                <img src="view/images/general/loader.gif" alt="Loader" style="margin:auto; display:block;"><br/>
                                <p class="center">Il reste <?php echo $date_respected->format("%m Mois %d Jour(s) %h Heure(s) %i Minute(s) %s Seconde(s)"); ?><br/>
                                avant de pouvoir récupérer les fichiers.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
 		</div>
     </section>
     <div class="modal" id="edit">
		<div class="modal_content container">
			<i class="fa fa-close"></i>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2 style="text-align:center;">Modifier un devoir</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field has-content">
						<input type="text" id="subject" class="field-input" required value="<?php echo $work['name']; ?>">
						<label for="subject" class="field-label">Sujet</label>
					</div>
                    <select id="module" value="<?php echo $work['module']; ?>">
						<option disabled>Choisissez un module</option>
						<?php
						$modules = $bdd->query("SELECT * FROM modules")->fetchAll();
						foreach ($modules as $module): ?>
							<option value="<?php echo $module['id_module']; ?>"><?php echo $module['title']." - ".$module['subtitle']; ?></option>
						<?php endforeach; ?>
					</select>
                    <div class="field has-content">
						<input type="text" id="date" class="field-input" required readonly data-field="datetime" value="<?php echo utf8_encode(strftime('%d-%m-%Y %H:%M', $date->getTimestamp())); ?>">
						<label for="date" class="field-label">Date de rendu</label>
					</div>
					<div id="dtBox"></div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="field has-content">
                        <textarea id="description" rows="10" class="field-input"><?php echo $work['work_description']; ?></textarea>
                        <label for="description" class="field-label">Description</label>
                    </div>
				</div>
			</div>
            <div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="submit" id="update-work" value="Modifier le devoir" class="field-submit">
				</div>
			</div>
		</div>
	</div>
    <script src="framework/jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="framework/dateTimePicker/DateTimePicker.min.js"></script>
	<script type="text/javascript" src="framework/dateTimePicker/DateTimePicker-i18n-fr.js"></script>
     <script type="text/javascript">

     function delete_files(){
        var id_work = Number($("#delete").data("work"));
        if(Number.isInteger(id_work)) {
            $.ajax({
                type : "POST",
                url: "model/request-DB/works/delete_work.php",
                data : 'id_work=' + id_work,
                success: function(data) {
                    location.reload();
                }
            });
        }
     }
         

  function upload(files){
    $(".error").slideUp();
    ret = "";
    var formData = new FormData();
    console.log(files);
    for(i = 0; i < files.length; i++){
      var name = files[i].name;
      if(files[i].size <= 52428800){
        formData.append('file[]', files[i], name);
      }else{
        ret = ret + "<li>Le fichier " + name + " est trop lourd (MAX 50Mo)</li>";
      }
    }
    var length = 0;
    for(var entry of formData.entries()){
      length++;
    }
    if(length > 0){
      formData.append('id_work', $("#work").val());
      $.ajax({
          url : "model/request-DB/works/upload_files.php",
          type: "POST",
          data : formData,
          xhr: function() {
                  $("#bar").css("width", "0%");
                  $("#value").html("0%");
                  var myXhr = $.ajaxSettings.xhr();
                  if(myXhr.upload){
                      myXhr.upload.addEventListener('progress',progress, false);
                  }
                  return myXhr;
          },
          processData: false,
          contentType: false,
          success:function(data){
            $("#value").html("Terminé");
            ret = ret+data;
            if(ret != ""){
              $(".error").html("<ul>"+ret+"</ul>").slideDown();
            }

            $.ajax({
                url : "model/request-DB/works/get_files.php",
                type: "POST",
                data : "id_work="+$("#work").val(),
                success:function(data){
                  $("#files_list").html(data);
                }
            });
          }
      });
    }else{
      if(ret != ""){
        $(".error").html("<ul>"+ret+"</ul>").slideDown();
      }
    }
  }

  function progress(e){
      if(e.lengthComputable){
          var max = e.total;
          var current = e.loaded;
          var Percentage = (current * 100)/max;
          $("#bar").css("width", Percentage+"%");
          $("#value").html(Math.ceil(Percentage)+"%");
      }
   }

   function delete_file(name_file){
     $(".error").slideUp();
     $.ajax({
         url : "model/request-DB/works/delete_file.php",
         type: "POST",
         data : "id_work="+$("#work").val()+"&file="+name_file,
         success:function(data){
           if(data == ""){
             $.ajax({
                 url : "model/request-DB/works/get_files.php",
                 type: "POST",
                 data : "id_work="+$("#work").val(),
                 success:function(data){
                   $("#files_list").html(data);
                 }
             });
           }else{
             $(".error").html(data).slideDown();
           }
         }
     });
   }



 		$(document).ready(function(){
             $("#delete").click(function(){
                 delete_files();
            });
             $("#dtBox").DateTimePicker({
				"language":"fr",
                "shortDayNames":["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"]
			});
            $(".modal").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				if($(e.target).attr('class') == "modal"){
					$(this).fadeOut("fast");
				};
			});
			$("#editWork").click(function(e){
				e.preventDefault();
				$("#edit").css("display", "flex").hide().fadeIn();
			})
			$(".fa-close").click(function(e){
				e.preventDefault();
				$(this).parent().parent().fadeOut();
			});
      $("#files").on("dragenter", function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass("drop");
        return false;
      });
      $("#files").on("dragleave", function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass("drop");
        return false;
      });
      $("#files").on("dragover", function(e){
        e.preventDefault();
        e.stopPropagation();
        return false;
      });

      $("#files").on("drop", function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass("drop");
        var files = e.originalEvent.dataTransfer.files;
        upload(files);
        return false;
      });
      $("#uploader").on("change", function(e){
        var files = e.target.files;
        upload(files);
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






        $("#update-work").click(function(){
            var name        = $("#subject").val();
            var description = $("#description").val();
            var date        = $("#date").val();
            var id_module   = $("#module").val();
            var id_work     = parseInt(<?php echo $id_work; ?>);

            $.ajax({
                type : "POST",
                url: "model/request-DB/works/update_work.php",
                data : {
                    name :name,
                    description :description,
                    date :date,
                    id_module :id_module,
                    id_work :id_work
                },
                dataType: "json",
                success: function(data) {
                    location.reload();
                }
            });
        });

        $("#delete-work").click(function(){
            var id_work     = parseInt(<?php echo $id_work; ?>);

            $.ajax({
                type : "POST",
                url: "model/request-DB/works/delete_work.php",
                data : {
                    id_work :id_work
                },
                dataType: "json",
                success: function(data) {
                    location.reload();
                }
            });
        });
     });
 	</script>
 </body>
 </html>
