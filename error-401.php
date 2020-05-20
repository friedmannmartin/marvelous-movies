<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';

    $pageTitle = 'Unauthorized access';

    include './include/header.php';
?>

<main class="container-sm py-5 text-center">
    <img src="./img/error/401.svg" alt="Error 401 - Unauthorized access" width="300" height="180"/>
    <h2 class="py-3"><span class="badge badge-danger">Error 401</span> Unauthorized access</h2>
    <a class="btn btn-primary" href="./">Return to Homepage</a>
</main>

<?php include './include/footer.php'; ?>
