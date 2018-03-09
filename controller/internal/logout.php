<?php
    session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
    session_start();
    session_destroy();
    header('location: login?logout');
    exit;
?>
