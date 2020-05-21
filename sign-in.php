<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    unauthorizedUsersOnly();

    /* Connection to Facebook API*/
    require_once './include/fb.php';

    /* Initializing  helper to create facebook login link */
    $fbHelper = $fb->getRedirectLoginHelper();

    /* Setting parameters for permissions request and callback after login */
    $permissions = ['email'];
    $callbackUrl = htmlspecialchars('https://eso.vse.cz/~frim00/marvelous-movies/facebook-callback.php');

    /* Using helper for creating facebook login link */
    $fbLoginUrl = $fbHelper->getLoginUrl($callbackUrl, $permissions);

    /* If there is posted form try sign in user */
    $errors=false;
    if (!empty($_POST)){
        /* Searching for user in database by posted email */
        $userQuery=$db->prepare('SELECT * FROM users WHERE email=:email LIMIT 1;');
        $userQuery->execute([
            ':email'=>trim($_POST['email'])
        ]);
        if ($user = $userQuery->fetch(PDO::FETCH_ASSOC)){
            /* User found in DB */
            if (password_verify($_POST['password'],$user['password'])){
                /* Password verified, save user to session */
                $_SESSION['user_id'] = $user['user_id'];

                /* Set session expiraton */
                if(!$_POST['remeber']){
                    $_SESSION['expiration'] = strtotime("+10 minutes");
                }

                /* Delete users requests for password reset */
                $forgottenPasswordDeleteQuery=$db->prepare('DELETE FROM forgotten_passwords WHERE user_id=:user_id;');
                $forgottenPasswordDeleteQuery->execute([':user_id'=>$user['user_id']]);
                header('Location: ./');
                exit();
            } else{
                /* Wrong password */
                $errors=true;
            }
        } else{
            /* Unknown email*/
            $errors=true;
        }
    }

    $pageTitle = 'Sign In';

    include './include/header.php';
?>

<main class="d-flex align-items-center">
    <div class="container-sm py-5">
        <div class="row">
            <div class="col">
                <h2 class="pb-3">User login</h2>
                <form method="post">

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email"
                            name="email"
                            id="email"
                            placeholder="Enter your e-mail"
                            autocomplete="email"
                            required
                            class="form-control <?php echo ($errors?'is-invalid':''); ?>"
                            value="<?php echo htmlspecialchars(@$_POST['email'])?>"/>
                        <?php if($errors):?>
                            <div class="invalid-feedback">Username or password is wrong.</div>
                        <?php endif?>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password:</label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   placeholder="Enter your password"
                                   autocomplete="current-password"
                                   data-toggle="password"
                                   required
                                   class="form-control <?php echo ($errors?'is-invalid':''); ?>" />
                        <?php if($errors):?>
                            <div class="invalid-feedback">Username or password is wrong.</div>
                        <?php endif?>
                    </div>

                    <!-- Remember me -->
                    <div class="form-group custom-control custom-switch">
                        <input type="checkbox"
                               class="custom-control-input"
                               name="remeber"
                               id="remeber"
                               value="true">
                        <label class="custom-control-label" for="remeber">Remeber me</label>
                    </div>

                    <button type="submit"      class="btn btn-primary">Sign In</button>
                    <a href="<?=$fbLoginUrl?>" class="btn btn-primary"><i class="fa fa-facebook-official mr-2" style="font-size:19px"></i>Continue with Facebook</a>
                    <hr>
                    <a href="./password-reset" class="btn btn-light">Forgot password?</a>
                    <a href="./sign-up"        class="btn btn-light">Don't have an account? Sign Up!</a>
                    <a href="./"               class="btn btn-light">Cancel</a>
                </form>
            </div>
            <div class="col d-none d-sm-block">
                <img src="./img/sign-in.svg" alt="Sign in" class="img-fluid" width="600" height="360"/>
            </div>
        </div>
    </div>
</main>
<?php include './include/footer.php'; ?>
