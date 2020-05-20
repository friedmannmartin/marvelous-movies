<?php
    require_once './include/config.php';

    /* Connection to Facebook API */
    $fb = new Facebook\Facebook([
        'app_id' => FB_APP_ID,
        'app_secret' => FB_APP_SECRET,
        'default_graph_version' => 'v4.0',
    ]);
