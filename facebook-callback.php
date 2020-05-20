<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';

    /* Connection to Facebook API*/
    require_once './include/fb.php';

    /* Initializing  helper to create facebook login link */
    $fbHelper = $fb->getRedirectLoginHelper();

    /* Initializing  OAuth 2.0 client to manage access tokens */
    $oAuth2Client = $fb->getOAuth2Client();

    /* Getting access token from facebook login */
    try {
        $accessToken = $fbHelper->getAccessToken();
    } catch(Exception $e) {
        echo 'Facebook login failed. Error: ' . $e->getMessage();
        exit();
    }

    if (!$accessToken){
        /* Access token was not returned */
        exit('Facebook login failed. Try it again.');
    }

    /* Getting access token metadata */
    $accessTokenMetadata = $oAuth2Client->debugToken($accessToken);

    /* Getting users facebook_id */
    $fbUser['id'] = $accessTokenMetadata->getUserId();

    /* Getting users facebook name and facebook email */
    $response=$fb->get('/me?fields=name,email', $accessToken);
    $graphUser=$response->getGraphUser();
    $fbUser['name']=$graphUser->getName();
    $fbUser['email']=$graphUser->getEmail();


    /* Searching user in database by his facebook_id */
    $query=$db->prepare('SELECT * FROM users WHERE facebook_id=:facebook_id LIMIT 1;');
    $query->execute([
        ':facebook_id'=>$fbUser['id']
    ]);

    if ($query->rowCount()>0){
        /* User found in database by his facebook_id */
        $user = $query->fetch(PDO::FETCH_ASSOC);
    }else{
        /* User not found in database by his facebook_id. Searching user in database by his facebook email*/
        $query = $db->prepare('SELECT * FROM users WHERE email=:email LIMIT 1;');
        $query->execute([
            ':email'=>$fbUser['email']
        ]);

        if ($query->rowCount()>0){
            /* User found in database by his facebook email. Updating user record with his newly acquired facebook_id. */
            $user = $query->fetch(PDO::FETCH_ASSOC);

            $updateQuery = $db->prepare('UPDATE users SET facebook_id=:facebook_id WHERE user_id=:user_id LIMIT 1;');
            $updateQuery->execute([
                ':facebook_id'=>$fbUser['id'],
                ':user_id'=>$user['user_id']
            ]);

        }else{
            /* User not found in database by his facebook_id or his facebook email. Creating new user. */
            $insertQuery = $db->prepare('INSERT INTO users (name, email, facebook_id) VALUES (:name, :email, :facebook_id);');
            $insertQuery->execute([
                ':name'=>$fbUser['name'],
                ':email'=>$fbUser['email'],
                ':facebook_id'=>$fbUser['id']
            ]);

            /* Getting user_id of newly created user */
            $query=$db->prepare('SELECT * FROM users WHERE facebook_id=:facebook_id LIMIT 1;');
            $query->execute([
                ':facebook_id'=>$fbUser['id']
            ]);
            $user=$query->fetch(PDO::FETCH_ASSOC);
        }
    }


    if (!empty($user)){
        /* Save authenticated user to Session */
        $_SESSION['user_id']=$user['user_id'];
    }

    /* Redirect user to homepage */
    header('Location: ./');
