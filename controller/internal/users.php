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
    if(isset($_POST['submit']) && !empty($_POST['submit'])){
        if(isset($_FILES['csv']['tmp_name']) && !empty($_FILES['csv']['tmp_name']) && $user['value'] > 10){
            $csvFile = file($_FILES['csv']['tmp_name']);
            if($_POST['type'] == "semicolon"){
                $type = ";";
            }else if($_POST['type'] == "comma"){
                $type = ",";
            }
            $data = [];
            foreach ($csvFile as $line) {
                $data[] = array_map("utf8_encode", str_getcsv($line, $type));
            }
            $retour = "";
            foreach($data as $line){
                $lastName = $bdd->quote(ucwords(strtolower($line[0])));
                $firstName = $bdd->quote(ucwords(strtolower($line[1])));
                $email = $bdd->quote($line[3]);
                $exist = $bdd->query("SELECT * FROM users WHERE mail=$email")->fetch();
                if(!$exist){
                    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                    $password = '';
                    for($i=0; $i<8; $i++){
                        $password .= $chars[rand(0, strlen($chars)-1)];
                    }
                    $bddpassword = $bdd->quote(hash_hmac('sha256', $password, "keyProjetDASI"));
                    $message = '<!doctype html>
                    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
                    <head>
                    <title></title>
                    <!--[if !mso]><!-- -->
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <!--<![endif]-->
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style type="text/css">
                    #outlook a { padding: 0; }
                    .ReadMsgBody { width: 100%; }
                    .ExternalClass { width: 100%; }
                    .ExternalClass * { line-height:100%; }
                    body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                    table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
                    p { display: block; margin: 13px 0; }
                    </style>
                    <!--[if !mso]><!-->
                    <style type="text/css">
                    @media only screen and (max-width:480px) {
                    @-ms-viewport { width:320px; }
                    @viewport { width:320px; }
                    }
                    </style>
                    <!--<![endif]-->
                    <!--[if mso]>
                    <xml>
                    <o:OfficeDocumentSettings>
                    <o:AllowPNG/>
                    <o:PixelsPerInch>96</o:PixelsPerInch>
                    </o:OfficeDocumentSettings>
                    </xml>
                    <![endif]-->
                    <!--[if lte mso 11]>
                    <style type="text/css">
                    .outlook-group-fix {
                    width:100% !important;
                    }
                    </style>
                    <![endif]-->

                    <!--[if !mso]><!-->
                    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet" type="text/css">
                    <style type="text/css">

                    @import url(https://fonts.googleapis.com/css?family=Roboto:300,400,700);

                    </style>
                    <!--<![endif]--><style type="text/css">
                    @media only screen and (min-width:480px) {
                    .mj-column-per-100 { width:100%!important; }
                    }
                    </style>
                    </head>
                    <body>

                    <div class="mj-container"><!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                    <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                    <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="background:linear-gradient(45deg, #1a798f 0%,#722fa0 100%);font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:50px 0px;"><!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td style="vertical-align:top;width:600px;">
                    <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:20px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:200px;"><img alt="" height="auto" src="data:image/png;base64,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" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="200"></td></tr></tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><div style="cursor:auto;color:white;font-family:Roboto;font-size:19px;font-weight:300;line-height:22px;text-align:center;">Licence Pro Développement et Administration des Sites Internet</div></td></tr></tbody></table></div><!--[if mso | IE]>
                    </td></tr></table>
                    <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>
                    </td></tr></table>
                    <![endif]-->
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                    <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                    <![endif]--><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:50px 0px;"><!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td style="vertical-align:top;width:600px;">
                    <![endif]-->
                    <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:100px;"><img alt="" height="auto" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFwmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDIgNzkuMTYwOTI0LCAyMDE3LzA3LzEzLTAxOjA2OjM5ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAxOC0wMi0yMFQxNDoxMzo1OSswMTowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAxOC0wMi0yMFQxNDoxMzo1OSswMTowMCIgeG1wOk1vZGlmeURhdGU9IjIwMTgtMDItMjBUMTQ6MTM6NTkrMDE6MDAiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MGExODM0MjctZmRkYy04ZTRjLTk3MGUtZDY4NDkzM2UzZjUwIiB4bXBNTTpEb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6YTUwYjdhY2MtOWZmYi04MTQ0LWFmMzctNmEz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" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="100"></td></tr></tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><div style="cursor:auto;color:#00C485;font-family:Roboto;font-size:26px;font-weight:300;line-height:1.1;text-align:center;">Votre compte DASI a été créé !</div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:50px 0px;"><p style="font-size:1px;margin:0px auto;border-top:1px solid #dddddd;width:100%;"></p><!--[if mso | IE]><table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:1px;margin:0px auto;border-top:1px solid #dddddd;width:100%;" width="600"><tr><td style="height:0;line-height:0;"> </td></tr></table><![endif]--></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="left"><div style="cursor:auto;color:#000000;font-family:Roboto;font-size:13px;line-height:22px;text-align:left;">
                    Votre identifiant est : <b>'.$line[3].'</b><br>
                    Votre mot de passe est : <b>'.$password.'</b>
                    Pensez à le modifier !</div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:20px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0"><tbody><tr><td style="border:none;border-radius:40px;color:#ffffff;cursor:auto;padding:12px 60px;" align="center" valign="middle" bgcolor="#395F95"><a href="https://lpromp2.alexisjovelin.fr/login" style="text-decoration:none;background:#395F95;color:#ffffff;font-family:Roboto;font-size:16px;font-weight:300;line-height:120%;text-transform:uppercase;margin:0px;" target="_blank">Se connecter</a></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
                    </td></tr></table>
                    <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
                    </td></tr></table>
                    <![endif]--></div>
                    </body>
                    </html>';
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                    mail($line[3], "Votre compte DASI a été créé !", $message, $headers);
                    $bdd->query("INSERT INTO users VALUES (null, $firstName, $lastName, $email, $bddpassword, 1)");
                }else{
                    $retour .= "Le compte ".$line[3]." existe déjà !<br/>";
                }
            }
            if(empty($retour)){
                $retour = "Tous les comptes ont été créés.";
            }
        }
    }
    if(isset($_POST['reinit'])){
        $idToReinit = $bdd->quote($_POST['id_user']);
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $password = '';
        for($i=0; $i<8; $i++){
            $password .= $chars[rand(0, strlen($chars)-1)];
        }
        $bddpassword = $bdd->quote(hash_hmac('sha256', $password, "keyProjetDASI"));
        $getUser = $bdd->query("SELECT mail FROM users WHERE id_users=$idToReinit")->fetch();
        $email = $getUser['mail'];
        $message = '<!doctype html>
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
        <head>
        <title></title>
        <!--[if !mso]><!-- -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style type="text/css">
        #outlook a { padding: 0; }
        .ReadMsgBody { width: 100%; }
        .ExternalClass { width: 100%; }
        .ExternalClass * { line-height:100%; }
        body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        p { display: block; margin: 13px 0; }
        </style>
        <!--[if !mso]><!-->
        <style type="text/css">
        @media only screen and (max-width:480px) {
        @-ms-viewport { width:320px; }
        @viewport { width:320px; }
        }
        </style>
        <!--<![endif]-->
        <!--[if mso]>
        <xml>
        <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
        <!--[if lte mso 11]>
        <style type="text/css">
        .outlook-group-fix {
        width:100% !important;
        }
        </style>
        <![endif]-->

        <!--[if !mso]><!-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet" type="text/css">
        <style type="text/css">

        @import url(https://fonts.googleapis.com/css?family=Roboto:300,400,700);

        </style>
        <!--<![endif]--><style type="text/css">
        @media only screen and (min-width:480px) {
        .mj-column-per-100 { width:100%!important; }
        }
        </style>
        </head>
        <body>

        <div class="mj-container"><!--[if mso | IE]>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
        <tr>
        <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
        <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="background:linear-gradient(45deg, #1a798f 0%,#722fa0 100%);font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:50px 0px;"><!--[if mso | IE]>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td style="vertical-align:top;width:600px;">
        <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:20px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:200px;"><img alt="" height="auto" src="data:image/png;base64,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" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="200"></td></tr></tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><div style="cursor:auto;color:white;font-family:Roboto;font-size:19px;font-weight:300;line-height:22px;text-align:center;">Licence Pro Développement et Administration des Sites Internet</div></td></tr></tbody></table></div><!--[if mso | IE]>
        </td></tr></table>
        <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>
        </td></tr></table>
        <![endif]-->
        <!--[if mso | IE]>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
        <tr>
        <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
        <![endif]--><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:50px 0px;"><!--[if mso | IE]>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td style="vertical-align:top;width:600px;">
        <![endif]-->
        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:100px;"><img alt="" height="auto" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFwmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDIgNzkuMTYwOTI0LCAyMDE3LzA3LzEzLTAxOjA2OjM5ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAxOC0wMi0yMFQxNDoxMzo1OSswMTowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAxOC0wMi0yMFQxNDoxMzo1OSswMTowMCIgeG1wOk1vZGlmeURhdGU9IjIwMTgtMDItMjBUMTQ6MTM6NTkrMDE6MDAiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MGExODM0MjctZmRkYy04ZTRjLTk3MGUtZDY4NDkzM2UzZjUwIiB4bXBNTTpEb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6YTUwYjdhY2MtOWZmYi04MTQ0LWFmMzctNmEz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" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="100"></td></tr></tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><div style="cursor:auto;color:#00C485;font-family:Roboto;font-size:26px;font-weight:300;line-height:1.1;text-align:center;">Votre compte DASI a été réinitialisé !</div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:50px 0px;"><p style="font-size:1px;margin:0px auto;border-top:1px solid #dddddd;width:100%;"></p><!--[if mso | IE]><table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:1px;margin:0px auto;border-top:1px solid #dddddd;width:100%;" width="600"><tr><td style="height:0;line-height:0;"> </td></tr></table><![endif]--></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="left"><div style="cursor:auto;color:#000000;font-family:Roboto;font-size:13px;line-height:22px;text-align:left;">
        Votre identifiant est : <b>'.$email.'</b><br/>
        Votre mot de passe est : <b>'.$password.'</b>
        Pensez à le modifier !</div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:20px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0"><tbody><tr><td style="border:none;border-radius:40px;color:#ffffff;cursor:auto;padding:12px 60px;" align="center" valign="middle" bgcolor="#395F95"><a href="https://lpromp2.alexisjovelin.fr/login" style="text-decoration:none;background:#395F95;color:#ffffff;font-family:Roboto;font-size:16px;font-weight:300;line-height:120%;text-transform:uppercase;margin:0px;" target="_blank">Se connecter</a></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
        </td></tr></table>
        <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
        </td></tr></table>
        <![endif]--></div>
        </body>
        </html>';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        mail($email, "Votre compte DASI a été réinitialisé !", $message, $headers);
        $bdd->query("UPDATE users SET password=$bddpassword WHERE id_users=$idToReinit");
        $retour = "Le compte a été réinitialisé.";

    }
    if(isset($_POST['delete'])){
        $idToDelete = $bdd->quote($_POST['id_user']);
        $bdd->query("DELETE FROM users WHERE id_users=$idToDelete");
        $retour = "Le compte a été supprimé.";
    }

    if(isset($_POST['addUser'])){
        if(isset($_POST['firstName']) && !empty($_POST['firstName']) && isset($_POST['lastName']) && !empty($_POST['lastName']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['role']) && !empty($_POST['role'])){
            $firstName = $bdd->quote(ucwords(strtolower($_POST['firstName'])));
            $lastName = $bdd->quote(ucwords(strtolower($_POST['lastName'])));
            $emailbdd = $bdd->quote($_POST['email']);
            $role = $bdd->quote($_POST['role']);
            $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $password = '';
            for($i=0; $i<8; $i++){
                $password .= $chars[rand(0, strlen($chars)-1)];
            }
            $bddpassword = $bdd->quote(hash_hmac('sha256', $password, "keyProjetDASI"));
            $email = $_POST['email'];
            $message = '<!doctype html>
            <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head>
            <title></title>
            <!--[if !mso]><!-- -->
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <!--<![endif]-->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style type="text/css">
            #outlook a { padding: 0; }
            .ReadMsgBody { width: 100%; }
            .ExternalClass { width: 100%; }
            .ExternalClass * { line-height:100%; }
            body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
            table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
            img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
            p { display: block; margin: 13px 0; }
            </style>
            <!--[if !mso]><!-->
            <style type="text/css">
            @media only screen and (max-width:480px) {
            @-ms-viewport { width:320px; }
            @viewport { width:320px; }
            }
            </style>
            <!--<![endif]-->
            <!--[if mso]>
            <xml>
            <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
            </xml>
            <![endif]-->
            <!--[if lte mso 11]>
            <style type="text/css">
            .outlook-group-fix {
            width:100% !important;
            }
            </style>
            <![endif]-->

            <!--[if !mso]><!-->
            <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet" type="text/css">
            <style type="text/css">

            @import url(https://fonts.googleapis.com/css?family=Roboto:300,400,700);

            </style>
            <!--<![endif]--><style type="text/css">
            @media only screen and (min-width:480px) {
            .mj-column-per-100 { width:100%!important; }
            }
            </style>
            </head>
            <body>

            <div class="mj-container"><!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
            <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="background:linear-gradient(45deg, #1a798f 0%,#722fa0 100%);font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:50px 0px;"><!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td style="vertical-align:top;width:600px;">
            <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:20px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:200px;"><img alt="" height="auto" src="data:image/png;base64,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" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="200"></td></tr></tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><div style="cursor:auto;color:white;font-family:Roboto;font-size:19px;font-weight:300;line-height:22px;text-align:center;">Licence Pro Développement et Administration des Sites Internet</div></td></tr></tbody></table></div><!--[if mso | IE]>
            </td></tr></table>
            <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>
            </td></tr></table>
            <![endif]-->
            <!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
            <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <![endif]--><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:50px 0px;"><!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td style="vertical-align:top;width:600px;">
            <![endif]-->
            <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:100px;"><img alt="" height="auto" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFwmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDIgNzkuMTYwOTI0LCAyMDE3LzA3LzEzLTAxOjA2OjM5ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAxOC0wMi0yMFQxNDoxMzo1OSswMTowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAxOC0wMi0yMFQxNDoxMzo1OSswMTowMCIgeG1wOk1vZGlmeURhdGU9IjIwMTgtMDItMjBUMTQ6MTM6NTkrMDE6MDAiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MGExODM0MjctZmRkYy04ZTRjLTk3MGUtZDY4NDkzM2UzZjUwIiB4bXBNTTpEb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6YTUwYjdhY2MtOWZmYi04MTQ0LWFmMzctNmEz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" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="100"></td></tr></tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><div style="cursor:auto;color:#00C485;font-family:Roboto;font-size:26px;font-weight:300;line-height:1.1;text-align:center;">Votre compte DASI a été créé !</div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:50px 0px;"><p style="font-size:1px;margin:0px auto;border-top:1px solid #dddddd;width:100%;"></p><!--[if mso | IE]><table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:1px;margin:0px auto;border-top:1px solid #dddddd;width:100%;" width="600"><tr><td style="height:0;line-height:0;"> </td></tr></table><![endif]--></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="left"><div style="cursor:auto;color:#000000;font-family:Roboto;font-size:13px;line-height:22px;text-align:left;">
            Votre identifiant est : <b>'.$email.'</b><br/>
            Votre mot de passe est : <b>'.$password.'</b>
            Pensez à le modifier !</div></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:20px 0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0"><tbody><tr><td style="border:none;border-radius:40px;color:#ffffff;cursor:auto;padding:12px 60px;" align="center" valign="middle" bgcolor="#395F95"><a href="https://lpromp2.alexisjovelin.fr/login" style="text-decoration:none;background:#395F95;color:#ffffff;font-family:Roboto;font-size:16px;font-weight:300;line-height:120%;text-transform:uppercase;margin:0px;" target="_blank">Se connecter</a></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
            </td></tr></table>
            <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
            </td></tr></table>
            <![endif]--></div>
            </body>
            </html>';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            mail($email, "Votre compte DASI a été créé !", $message, $headers);
            $bdd->query("INSERT INTO users VALUES (null, $firstName, $lastName, $emailbdd, $bddpassword, $role)");
            $retour = "Le compte a été créé !";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>LP DASI - Utilisateurs</title>
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
                <div class="col-lg-12">
                    <h1>Gestion des utilisateurs</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn left" href="#" id="addUser">Ajouter un utilisateur</a>
                    <a class="btn right" href="#" id="addUsers">Ajouter des utilisateurs</a>
                </div>
            </div>
            <?php if(isset($retour)): ?>
                <div class="error info" style="display: block;">
                    <?php echo $retour; ?>
                </div>
            <?php else: ?>
                <div class="error info">
                </div>
            <?php endif; ?>
            <div class="row" id="addu" style="display: none;">
                <div class="col-xs-12">
                    <h2>Ajouter un utilisateur</h2><br>
                    <div class="card-o">
                        <p>Un mot de passe sera envoyé directement par mail au nouvel utilisateur.</p>
                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="field">
                                        <input type="text" id="firstName" class="field-input" name="firstName" required>
                                        <label for="firstName" class="field-label">Prénom</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="field">
                                        <input type="text" id="lastName" class="field-input" name="lastName" required>
                                        <label for="lastName" class="field-label">Nom</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="field">
                                        <input type="email" id="email" class="field-input" name="email" required>
                                        <label for="email" class="field-label">Adresse Email</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <select style="margin-top:38px;" data-user="<?php echo $getUser['id_users']; ?>" name="role" required>
                                        <option disabled selected>Choisissez un rôle</option>
                                        <?php
                                        $roles = $bdd->query("SELECT * FROM roles ORDER BY value DESC")->fetchAll();
                                        foreach($roles as $role): ?>
                                            <option value="<?php echo $role['id_role']; ?>"><?php echo $role['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <input type="submit" value="Ajouter un utilisateur" name="addUser" class="field-submit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" id="add" style="display: none;">
                <div class="col-xs-12">
                    <h2>Ajouter des utilisateurs</h2><br>
                    <div class="card-o">
                        <p>Format CSV avec séparation par virgule ou point virgule</p>
                        <p>Ordre des colonnes : Nom, Prénom, email.</p>
                        <p>Un mot de passe sera envoyé directement par mail aux nouveaux utilisateurs.</p>
                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="field has-content-ever">
                                <input type="file" id="csv" class="field-input" name="csv" required>
                                <label for="csv" class="field-label">Fichier CSV</label>
                            </div>
                            Séparation du CSV par :
                            <input type="radio" value="comma" id="comma" name="type" required><label for="comma">Virgule</label>
                            <input type="radio" value="semicolon" id="semicolon" name="type" required><label for="semicolon">Point-virgule</label>
                            <input type="submit" value="Ajouter les utilisateurs" name="submit" class="field-submit">
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h2>Utilisateurs</h2><br>
                    <?php
                        $getUsers = $bdd->query("SELECT users.id_users, users.firstname, users.lastname, users.mail, users.role, roles.name, roles.value FROM users INNER JOIN roles ON users.role = roles.id_role WHERE id_users != ".$user['id_users']." ORDER BY roles.value DESC, users.lastname, users.firstname")->fetchAll();
                    ?>
                    <table class="table table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Adresse Email</th>
                                <th>Rôle</th>
                                <th>Réinitialiser le mot de passe</th>
                                <th>Supprimer le compte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($getUsers as $getUser): ?>
                                <tr>
                                    <td><?php echo $getUser['firstname']; ?></td>
                                    <td><?php echo $getUser['lastname']; ?></td>
                                    <td><?php echo $getUser['mail']; ?></td>
                                    <td>
                                        <select class="changeRole" data-user="<?php echo $getUser['id_users']; ?>" style="margin:0; border: 2px solid #0072ff;">
                                            <?php
                                            $roles = $bdd->query("SELECT * FROM roles ORDER BY value DESC")->fetchAll();
                                            foreach($roles as $role): ?>
                                                <option value="<?php echo $role['id_role']; ?>" <?php if($role['id_role'] == $getUser['role']){ echo "selected"; } ?>><?php echo $role['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" value="<?php echo $getUser['id_users']; ?>" name="id_user">
                                            <button class="field-submit" type="submit" name="reinit" style="margin:0;">Réinitialiser</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" value="<?php echo $getUser['id_users']; ?>" name="id_user">
                                            <button class="field-submit red deleteEntry" name="delete" style="margin:0;">Supprimer</button>                                        </form>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</section>
	<script src="framework/jquery/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
            $(".changeRole").change(function(){
                let role = $(this).val();
                let user = $(this).data("user");
                $.ajax({
                    type : "POST",
                    url: "model/request-DB/users/change_role.php",
                    data : {
                        role: role,
                        user: user
                    },
                    success: function(data){
                        if(data == "ok"){
                            $(".error").html("Le rôle a été changé !").slideDown().delay(2000).slideUp();
                        }
                    }
                });
            });
			$("#addUsers").click(function(e){
				e.preventDefault();
                $("#addu").slideUp();
                $("#addUser").removeClass("active");
                $(this).toggleClass("active");
				$("#add").slideToggle();
			});
            $("#addUser").click(function(e){
				e.preventDefault();
                $(this).toggleClass("active");
                $("#add").slideUp();
                $("#addUsers").removeClass("active");
				$("#addu").slideToggle();
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
            $(".field-input").change(function(){
                if($(this).val() != ""){
					$(this).parent().addClass("has-content");
				}else{
                    $(this).parent().removeClass("has-content");
                }
            })

            $("label").click(function(){
                $("#"+$(this).attr("for")).focus();
            });
    });
	</script>
</body>
</html>