<?php
    session_start();

    /* Connection to database */
    require_once './include/db.php';

    /* Load Composer packages */
    require_once './vendor/autoload.php';

    /* Authenticates users */
    if(!empty($_SESSION['user_id'])){

        /* Checking whether session have expiration set  */
        if(isset($_SESSION['expiration'])){

            if($_SESSION['expiration']<time()){
                /* Session expired, destroy old session */
                session_destroy();
            } else {
                /* Valid session, extend validity by 10 minutes */
                $_SESSION['expiration']=strtotime("+10 minutes");
            }
        }

        $userQuery=$db->prepare('SELECT * FROM users WHERE user_id=:user_id LIMIT 1;');
        $userQuery->execute([
            ':user_id'=>$_SESSION['user_id']
        ]);

        /* User needs to be logged out if is not found in DB */
        if($userQuery->rowCount()!=1){
            session_destroy();
            header('Location: https://eso.vse.cz/~frim00/marvelous-movies/');
            exit();
        }

        /* $authenticatedUser - Currently logged in user */
        $authenticatedUser=$userQuery->fetch(PDO::FETCH_ASSOC);
    }

    /* Restrict access to the site to logged in users only */
    function authorizedUsersOnly(){
        global $authenticatedUser;
        if(empty($authenticatedUser)){
            header("HTTP/1.1 401 Unauthorized");
            header("Location: https://eso.vse.cz/~frim00/marvelous-movies/error-401");
            exit();
        }
    }

    /* Restrict access to the site to not logged in users only */
    function unauthorizedUsersOnly(){
        global $authenticatedUser;
        if(!empty($authenticatedUser)){
            header("HTTP/1.1 403 Forbidden");
            header('Location: https://eso.vse.cz/~frim00/marvelous-movies/');
            exit();
        }
    }

    /* Restrict access to the site to admin only */
    function authorizedAdminOnly(){
        global $authenticatedUser;
        if(!$authenticatedUser['admin']){
            header("HTTP/1.1 403 Forbidden");
            header("Location: https://eso.vse.cz/~frim00/marvelous-movies/error-403");
            exit();
        }
    }
