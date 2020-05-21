<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    unauthorizedUsersOnly();

    /*  */
    if(empty($_REQUEST['code'])){
        header("HTTP/1.1 400 Bad Request");
        header("Location: ./error-400");
        exit();
    }

    /* If there is posted form try renew password */
    $invalidCode=false;
    $invalidPassword=false;
    if (!empty($_REQUEST) && !empty($_REQUEST['code'])){

        /* Searching for record of forgotten password request */
        $query=$db->prepare('SELECT * FROM forgotten_passwords WHERE forgotten_password_id=:id AND code=:code AND user_id=:user LIMIT 1;');
        $query->execute([
            ':user'=>$_REQUEST['user'],
            ':code'=>$_REQUEST['code'],
            ':id'=>$_REQUEST['request'],
        ]);
        if ($existingRequest=$query->fetch(PDO::FETCH_ASSOC)){
            /* Checking whether request is stil valid */
            if (strtotime($existingRequest['created'])<(time()-24*3600)){
                /* Request has expired */
                $invalidCode=true;
            }
        } else{
            /* Request not found */
            $invalidCode=true;
        }

        if (!empty($_POST) && !$invalidCode){

            /* New password validation */
            if (empty($_POST['password']) || (strlen($_POST['password'])<5)){
                $invalidPassword='Password needs to be at least 5 characters long.';
            }
            if ($_POST['password']!=$_POST['password2']){
                $invalidPassword='Passwords do not match.';
            }

            /* Updating new password in DB */
            if (!$invalidPassword){
                $saveQuery=$db->prepare('UPDATE users SET password=:password WHERE user_id=:user LIMIT 1;');
                $saveQuery->execute([
                    ':user'=>$existingRequest['user_id'],
                    ':password'=>password_hash($_POST['password'], PASSWORD_DEFAULT)
                ]);

                /* Delete requests for password reset */
                $forgottenDeleteQuery=$db->prepare('DELETE FROM forgotten_passwords WHERE user_id=:user;');
                $forgottenDeleteQuery->execute([':user'=>$existingRequest['user_id']]);

                /* Getting currend user */
                $userQuery=$db->prepare('SELECT * FROM users WHERE user_id=:user LIMIT 1;');
                $userQuery->execute([
                    ':user'=>$existingRequest['user_id']
                ]);
                $user=$userQuery->fetch(PDO::FETCH_ASSOC);

                /* Signing in current user */
                $_SESSION['user_id']=$user['user_id'];
                $_SESSION['user_name']=$user['name'];

                header('Location: ./');
                exit();
            }
        }
    }

    $pageTitle = 'Password renew';

    include './include/header.php';
?>

<main class="d-flex align-items-center">
    <div class="container-sm py-5">
        <h2 class="pb-3">Password renew</h2>
        <?php if($invalidCode): ?>
            <p class="alert alert-danger">Password renew request expired!</p>
            <a href="./" class="btn btn-primary">Return to Homepage</a>
        <?php else: ?>
        <form method="post">

            <!-- Password -->
            <div class="form-row">

                <div class="form-group col-sm">
                    <label for="password">New password:</label>
                    <input type="password"
                           name="password"
                           id="password"
                           placeholder="Enter your new password"
                           autocomplete="new-password"
                           data-toggle="password"
                           required
                           class="form-control <?php echo ($invalidPassword ? 'is-invalid':'') ?>"/>
                <?php if ($invalidPassword):?>
                    <div class="invalid-feedback"><?=$invalidPassword?></div>
                <?php endif ?>
                </div>

                <div class="form-group col-sm">
                    <label for="password2">Password confirmation:</label>
                    <input type="password"
                           name="password2"
                           id="password2"
                           placeholder="Enter your new password once more"
                           autocomplete="new-password"
                           data-toggle="password"
                           required
                           class="form-control <?php echo ($invalidPassword?'is-invalid':'') ?>" />
                </div>
                <?php if ($invalidPassword):?>
                    <div class="invalid-feedback"><?=$invalidPassword?></div>
                <?php endif ?>
            </div>


            <input type="hidden" name="code"    value="<?= htmlspecialchars($_REQUEST['code']) ?>" />
            <input type="hidden" name="user"    value="<?= htmlspecialchars($_REQUEST['user']) ?>" />
            <input type="hidden" name="request" value="<?= htmlspecialchars($_REQUEST['request']) ?>" />

            <button type="submit" class="btn btn-primary">Change password</button>
            <hr>
            <a href="./sign-in"   class="btn btn-light">Sign In</a>
            <a href="./" class="btn btn-light">Cancel</a>
        </form>
        <?php endif ?>
    </div>
</main>

<?php include './include/footer.php'; ?>
