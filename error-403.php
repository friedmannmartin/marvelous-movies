<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';

    $pageTitle='Forbidden access';

    include './include/header.php';
?>
<main class="d-flex align-items-center">
    <div class="container-sm py-5 text-center">
        <img src="./img/error-403.svg" alt="Error 403 - Forbidden access" class="img-fluid" width="600" height="360"/>
        <h2 class="py-3"><span class="badge badge-danger">Error 403</span> Forbidden access</h2>
        <a class="btn btn-primary" href="./">Return to Homepage</a>
    </div>
</main>
<?php include './include/footer.php' ?>
