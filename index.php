<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';

    $pageTitle = 'Home';

    include './include/header.php';
?>

<main class="d-flex align-items-center">
    <div class="container-sm py-5">
        <div class="row">
            <div class="col-sm-7">
                <img src="./img/superhero.svg" alt="Superhero" class="img-fluid mb-5 mw-100" width="600" height="360"/>
            </div>
            <div class="col-sm-5 align-self-center">
                <h2>Come and see marvels</h2>
                <p>Only Marvelous Movies offer you the best films from Marvel Cinematic Universe. You have unique chance to watch these masterpieces on projections screen in our cinema.</p>
                <!-- Call to action button -->
                <a class="btn btn-primary" href="./program/today">Watch Now</a>
            </div>
        </div>
    </div>
</main>

<?php include './include/footer.php'; ?>
