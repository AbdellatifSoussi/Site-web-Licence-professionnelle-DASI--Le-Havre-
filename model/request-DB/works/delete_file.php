<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
  header('location: ../../../../../home');
  include_once("../../../model/ini_DB.php");

  $ret = "";

  session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
  session_start();
  if(!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])){
    $ret .= "<li>Veuillez vous reconnecter.</li>";
  }

  if(empty($ret)){
    if(isset($_POST['id_work']) && !empty($_POST['id_work']) && isset($_POST['file']) && !empty($_POST['file'])){
      $id_work = $_POST['id_work'];
      $file = $_POST['file'];
      $work = $bdd->query("SELECT * FROM works WHERE id_work='$id_work'")->fetch();
      $date = new DateTime($work['date']);
      $now = new DateTime();
      $date_respected = $now->diff($date);
      if($date_respected->invert){
        $ret .= "<li>La date limite est dépassée</li>";
      }
    }else{
      $ret .= "<li>Une erreur est survenue.</li>";
    }
  }

  if(empty($ret)){
    $user = $bdd->query("SELECT * FROM users WHERE id_users='".$_SESSION['id_user']."'")->fetch();
    if(file_exists("../../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname']."/".$file)){
      unlink("../../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname']."/".$file);
    }else{
      $ret .= "<li>Le fichier n'existe pas.</li>";
    }
  }

  if(!empty($ret)){
    $ret = "<ul>".$ret."</ul>";
    echo $ret;
  }

 ?>
