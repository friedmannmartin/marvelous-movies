<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    unauthorizedUsersOnly();

    /* If there is posted form try register in user */
    $errors=[];
    if (!empty($_POST)){

        /* Name validation */
        $name = trim(@$_POST['name']);
        if (empty($name)){
            $errors['name']='You need to enter your username.';
        }

        /* Email validation */
        $email = trim(@$_POST['email']);
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errors['name']='You need to enter valid email address.';
        }else{
            $mailQuery=$db->prepare('SELECT * FROM users WHERE email=:email LIMIT 1;');
            $mailQuery->execute([
                ':email'=>$email
            ]);
            if ($mailQuery->rowCount() > 0){
                $errors['name']='User account with this email address already exists.';
            }
        }

        /* Password validation */
        if (empty($_POST['password']) || (strlen($_POST['password'])<5)){
            $errors['password']='Password needs to be at least 5 characters long.';
        }
        if ($_POST['password']!=$_POST['password2']){
            $errors['password2']='Passwords do not match.';
        }

        /* Insertion of new user to database */
        if (empty($errors)){
            /* Hashing password */
            $password=password_hash($_POST['password'],PASSWORD_DEFAULT);

            $queryUser=$db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password);');
            $queryUser->execute([
                ':name'=>$name,
                ':email'=>$email,
                ':password'=>$password
            ]);

            /* Save newly created user to session */
            $_SESSION['user_id']=$db->lastInsertId();

            header('Location: ./');
            exit();
        }
    }

    $pageTitle = 'Registration';

    include './include/header.php';
?>

<main class="d-flex align-items-center">
    <div class="container-sm py-5">
        <div class="row">
            <div class="col">
                <h2 class="pb-3">New user registration</h2>
                <form method="post">

                    <!-- Name-->
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text"
                            name="name"
                            id="name"
                            placeholder="Enter your name"
                            autocomplete="name"
                            required
                            class="form-control <?php echo (!empty($errors['name'])?'is-invalid':''); ?>"
                            value="<?php echo htmlspecialchars(@$name);?>" />
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
                            placeholder="Enter your e-mail"
                            autocomplete="email"
                            required
                            class="form-control <?php echo (!empty($errors['email'])?'is-invalid':''); ?>"
                            value="<?php echo htmlspecialchars(@$email);?>"/>
                        <?php if(!empty($errors['email'])):?>
                            <div class="invalid-feedback"><?= $errors['email'] ?></div>
                        <?php endif ?>
                    </div>

                    <!-- Password -->
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="password">Password:</label>
                            <input type="password"
                                name="password"
                                id="password"
                                placeholder="Enter your password"
                                autocomplete="new-password"
                                data-toggle="password"
                                required
                                class="form-control <?php echo (!empty($errors['password'])?'is-invalid':''); ?>" />
                            <?php if(!empty($errors['password'])):?>
                                <div class="invalid-feedback"><?= $errors['password'] ?></div>
                            <?php endif ?>
                        </div>

                        <div class="form-group col">
                            <label for="password2">Password confirmation:</label>
                            <input type="password"
                                name="password2"
                                id="password2"
                                placeholder="Enter your password again"
                                data-toggle="password"
                                required
                                class="form-control <?php echo (!empty($errors['password2'])?'is-invalid':''); ?>" />
                            <?php if(!empty($errors['password2'])):?>
                                <div class="invalid-feedback"><?= $errors['password2'] ?></div>
                            <?php endif ?>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary">Sign Up</button>
                    <hr>
                    <a href="./sign-in"   class="btn btn-light">Already have an account? Sign In!</a>
                    <a href="./"          class="btn btn-light">Cancel</a>
                </form>
            </div>
            <div class="col d-none d-sm-block">
                <img src="./img/sign-up.svg" alt="Superhero" class="img-fluid" width="600" height="360"/>
            </div>
        </div>
    </div>
</main>

<?php include './include/footer.php'; ?>
