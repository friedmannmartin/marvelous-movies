<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    authorizedUsersOnly();

    /* Connection to Facebook API*/
    require_once './include/fb.php';

    /* Initializing  helper to create facebook login link */
    $fbHelper = $fb->getRedirectLoginHelper();

    /* Setting parameters for permissions request and callback after login */
    $permissions = ['email'];
    $callbackUrl = htmlspecialchars('https://eso.vse.cz/~frim00/marvelous-movies/facebook-callback.php');

    /* Using helper for creating facebook login link */
    $fbLoginUrl = $fbHelper->getLoginUrl($callbackUrl, $permissions);

    /* Identification whether user adjusts settings or admin editing user */
    if(!empty($_GET['user_id'])){
        authorizedAdminOnly();
        $user_id = $_GET['user_id'];
    } else {
        $user_id = $authenticatedUser['user_id'];
    }

    /* Getting a edited user */
    if(!empty($user_id)){
        $userQuery=$db->prepare('SELECT * FROM users WHERE user_id=:user_id LIMIT 1;');
        $userQuery->execute([
            ':user_id'=>$user_id
        ]);

        if($userQuery->rowCount()!=1){
            /* This user id is not in DB */
            header("HTTP/1.1 404 Not Found");
            header("Location: ../error-404");
            exit();
        }

        /* $user - Currently edited user */
        $user = $userQuery->fetch(PDO::FETCH_ASSOC);
    }


    $errors=[];
    if (!empty($_POST)){

        switch ($_POST['act']){
            case 'edit-profile':
                /* Users name validation */
                $name = trim(@$_POST['name']);
                if (empty($name)){
                    $errors['name']='Username can not be empty';
                }

                /* Users email validation */
                $email = trim(@$_POST['email']);
                if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $errors['name']='You need to enter valid email address.';
                }else{
                    $mailQuery=$db->prepare('SELECT * FROM users WHERE email=:email AND NOT name=:name LIMIT 1;');
                    $mailQuery->execute([
                        ':email'=>$email,
                        ':name'=>$user['name']
                    ]);
                    if ($mailQuery->rowCount() > 0){
                        $errors['name']='User account with this email address already exists.';
                    }
                }

                /* Update users profile with new parameters */
                if (empty($errors)){
                    $upadteUserQuery=$db->prepare('UPDATE users SET name=:name,email=:email WHERE user_id=:user_id LIMIT 1;');
                    $upadteUserQuery->execute([
                        ':name'=>$name,
                        ':email'=>$email,
                        ':user_id'=>$user_id,
                    ]);
                    header('Location: '.$_SERVER['REQUEST_URI']);
                    exit();
                }
            break;

            case 'unlick-facebook':
                /* Erase facebook_id from users account */
                $upadteUserQuery=$db->prepare('UPDATE users SET facebook_id="" WHERE user_id=:user_id LIMIT 1;');
                $upadteUserQuery->execute([
                    ':user_id'=>$user_id
                ]);
                header('Location: '.$_SERVER['REQUEST_URI']);
                exit();
            break;

            case 'change-password':
                /* New password validation */
                if (empty($_POST['password']) || (strlen($_POST['password'])<5)){
                    $errors['password']='Password needs to be at least 5 characters long.';
                }
                if ($_POST['password']!=$_POST['password2']){
                    $errors['password2']='Passwords do not match.';
                }

                /* Update users password */
                if (empty($errors)){
                    $password=password_hash($_POST['password'],PASSWORD_DEFAULT);

                    $upadteUserQuery=$db->prepare('UPDATE users SET password=:password WHERE user_id=:user_id LIMIT 1;');
                    $upadteUserQuery->execute([
                        ':password'=>$password,
                        ':user_id'=>$user_id
                    ]);
                    header('Location: '.$_SERVER['REQUEST_URI']);
                    exit();
                }
            break;

            case 'delete-account':
                /* Delete user account from database */
                $deleteUserQuery=$db->prepare('DELETE FROM users WHERE user_id=:user_id LIMIT 1;');
                $deleteUserQuery->execute([
                    ':user_id'=>$user_id
                ]);

                if(!empty($_GET['user_id'])){
                    header('Location: ../users.php');
                    exit();
                } else {
                    header('Location: ./sign-out.php');
                    exit();
                }
            break;
        }
    }

    /* Setting pageTitle based on whether user adjusts settings or admin editing user */
    if(!empty($_GET['user_id'])){
        $pageTitle = 'Editing '.htmlspecialchars($user['name']);
    } else {
        $pageTitle = 'Settings';
    }

    include './include/header.php';
?>

<main>
    <div class="container-sm py-5">
        <div class="row">
            <div class="col">
                <h2 class="pb-3"><?= $pageTitle ?></h2>
                <?php if($user['admin']): ?>
                    <div class="alert alert-info">User is administrator!</div>
                <?php endif ?>

                <!-- User profile (name and email) editation-->
                <div>
                    <hr>
                    <h3>Edit profile</h3>
                    <form method="post">

                        <!-- Act -->
                        <input type="hidden" name="act" value="edit-profile"/>

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text"
                                name="name"
                                id="name"
                                placeholder="Enter new name"
                                required
                                class="form-control <?php echo (!empty($errors['name'])?'is-invalid':''); ?>"
                                value="<?php echo htmlspecialchars($user['name']);?>" />
                            <?php if(!empty($errors['name'])):?>
                                <div class="invalid-feedback"><?= $errors['name'] ?></div>
                            <?php endif ?>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">E-mail:</label>
                            <input type="email"
                                name="email"
                                id="email"
                                placeholder="Enter new e-mail"
                                required
                                class="form-control <?php echo (!empty($errors['email'])?'is-invalid':''); ?>"
                                value="<?php echo htmlspecialchars($user['email']);?>"/>
                            <?php if(!empty($errors['email'])):?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>


                <!-- Actions only for users -->
                <?php if(empty($_GET['user_id'])): ?>

                    <!-- Link/Unlink user from facebook-->
                    <div>
                        <hr>
                        <h3 class="pb-1">Link Facebook accout</h3>
                        <form method="post">
                            <?php if(empty($user['facebook_id'])): ?>
                                <a href="<?=$fbLoginUrl?>" class="btn btn-primary">Link account Facebook</a>
                            <?php else: ?>
                                <!-- Act -->
                                <input type="hidden" name="act" value="unlick-facebook" />
                                <button type="submit" class="btn btn-danger">Unlink Facebook account</button>
                            <?php endif ?>
                        </form>
                    </div>

                    <!-- User password editation-->
                    <div>
                        <hr>
                        <h3 class="pb-1">Password change</h3>
                        <form method="post">

                            <!-- Act -->
                            <input type="hidden" name="act" value="change-password" />

                            <!-- Password -->
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="password">Password:</label>
                                    <input type="password"
                                        name="password"
                                        id="password"
                                        placeholder="Enter new password"
                                        required
                                        class="form-control <?php echo (!empty($errors['password'])?'is-invalid':''); ?>" />
                                    <?php if(!empty($errors['password'])):?>
                                        <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                    <?php endif ?>
                                </div>

                                <div class="form-group col">
                                    <label for="password2">Confirm new password:</label>
                                    <input type="password"
                                        name="password2"
                                        id="password2"
                                        placeholder="Enter new password once more"
                                        required
                                        class="form-control <?php echo (!empty($errors['password2'])?'is-invalid':''); ?>" />
                                    <?php if(!empty($errors['password2'])):?>
                                        <div class="invalid-feedback"><?= $errors['password2'] ?></div>
                                    <?php endif ?>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>


                <?php endif ?>

                <!-- Delete account-->
                <div>
                    <hr>
                    <h3 class="pb-1">Delete account</h3>
                    <form method="post">
                        <!-- Act -->
                        <input type="hidden" name="act" value="delete-account" />
                        <button type="submit" class="btn btn-danger">Delete account</button>
                    </form>
                </div>
            </div>
            <div class="col align-self-center">
                <img src="./img/user-profile.svg" alt="User profile" class="img-fluid" width="600" height="360"/>
            </div>
        </div>


    </div>
</main>

<?php include './include/footer.php'; ?>
