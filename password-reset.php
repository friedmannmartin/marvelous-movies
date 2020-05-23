<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    unauthorizedUsersOnly();

    /* Prepare PHPMailer */
    use PHPMailer\PHPMailer\PHPMailer;

    /* If there is posted form try send email to user */
    $errors=false;
    if (!empty($_POST) && !empty($_POST['email'])){
        /* Searching user in DB by posted email */
        $userQuery=$db->prepare('SELECT * FROM users WHERE email=:email LIMIT 1;');
        $userQuery->execute([
            ':email'=>trim($_POST['email'])
        ]);
        if ($user=$userQuery->fetch(PDO::FETCH_ASSOC)){
            /* If email address found generate random code */
            $code='xx'.rand(100000,993952);

            /* Create record in DB for password reset */
            $saveQuery=$db->prepare('INSERT INTO forgotten_passwords (user_id, code) VALUES (:user, :code)');
            $saveQuery->execute([
                ':user'=>$user['user_id'],
                ':code'=>$code
            ]);

            /* Getting record of forgotten password */
            $requestQuery=$db->prepare('SELECT * FROM forgotten_passwords WHERE user_id=:user_id AND code=:code ORDER BY forgotten_password_id DESC LIMIT 1;');
            $requestQuery->execute([
                ':user_id'=>$user['user_id'],
                ':code'=>$code
            ]);
            $request=$requestQuery->fetch(PDO::FETCH_ASSOC);

            /* Creating unique link to renew password  */
            $link='https://eso.vse.cz/~frim00/marvelous-movies/password-renew';
            $link.='?user='.$request['user_id'].'&code='.$request['code'].'&request='.$request['forgotten_password_id'];

            /* Creating email */
            $mailer=new PHPMailer(false);
            $mailer->isSendmail();
            $mailer->addAddress($user['email'],$user['name']);
            $mailer->setFrom('frim00@vse.cz');
            $mailer->CharSet='utf-8';
            $mailer->Subject='Forgotten password reset';
            $mailer->isHTML(true);
            $mailer->Body ='<html>
                                <head><meta charset="utf-8" /></head>
                                <body>
                                    To reset your Marvelous Movies password, click the following link:
                                    <a href="'.htmlspecialchars($link).'">'.htmlspecialchars($link).'</a>
                                </body>
                            </html>';
            $mailer->AltBody='To reset your Marvelous Movies password, click the following link: '.$link;
            $mailer->send();

            header('Location: password-reseted');
        } else{
            /* No account with posted email address found */
            $errors=true;
        }
    }

    $pageTitle = 'Password reset';

    include './include/header.php';
?>

<main class="d-flex align-items-center">
  <div class="container-sm py-5">
    <h2 class="pb-3">Forgotten password reset</h2>
        <?php if(@$_GET['mailed']=='ok'): ?>
            <div class="alert alert-success">
                <p>Check your inbox for e-mail with link, which will allow you to change your password.</p>
                <a href="./" class="btn btn-success">Go back to homepage</a>
            </div>
        <?php else: ?>
        <form method="post">

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your e-mail"
                    required
                    class="form-control <?php echo ($errors?'is-invalid':''); ?>"
                    value="<?php echo htmlspecialchars(@$_POST['email'])?>"/>
                <?php if($errors): ?>
                    <div class="invalid-feedback">You entered invalid email address.</div>
                <?php endif ?>
            </div>

            <button type="submit" class="btn btn-primary">Send me email for password reset</button>
            <hr>
            <a href="./sign-in"   class="btn btn-light">Sign In</a>
            <a href="./"          class="btn btn-light">Cancel</a>
        </form>
        <?php endif ?>
  </div>
</main>

<?php include './include/footer.php'; ?>
