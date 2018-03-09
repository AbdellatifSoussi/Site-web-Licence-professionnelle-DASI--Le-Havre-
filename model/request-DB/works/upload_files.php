<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
  header('location: ../../../../../home');
  include_once("../../ini_DB.php");

$ret = "";

session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
session_start();
if(!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])){
  $ret .= "<li>Veuillez vous reconnecter.</li>";
}

if(empty($ret)){
  if(isset($_POST['id_work']) && !empty($_POST['id_work'])){
    $id_work = $_POST['id_work'];
    $work = $bdd->query("SELECT * FROM works WHERE id_work='$id_work'")->fetch();
    $date = new DateTime($work['date']);
    $now = new DateTime();
    $date_respected = $now->diff($date);
    if($date_respected->invert){
      $ret .= "<li>La date limite est dépassée</li>";
    }
  }else{
    $ret .= "<li>Une erreur est survenue.";
  }
}


if(empty($ret)){
  if(isset($_FILES['file']) && !empty($_FILES['file'])){
    $user = $bdd->query("SELECT * FROM users WHERE id_users='".$_SESSION['id_user']."'")->fetch();
    if(!file_exists("../../../works_files/".$id_work)){
      mkdir("../../../works_files/".$id_work);
    }
    if(!file_exists("../../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname'])){
      mkdir("../../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname']);
    }
    for($i = 0; $i < count($_FILES['file']['tmp_name']); $i++){
      if($_FILES['file']['size'][$i] <= 52428800){ //50Mo
        move_uploaded_file($_FILES['file']['tmp_name'][$i], "../../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname']."/".$_FILES['file']['name'][$i]);
      }else{
        $ret .= "<li>Le fichier ".$_FILES['file']['name'][$i]." est trop lourd";
      }
    }
  }else{
    $ret .= "<li>Une erreur est survenue lors de l'upload.</li>";
  }
}

echo $ret;

?>
