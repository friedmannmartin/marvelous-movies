<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';

    /* Getting list of all movies */
    $moviesQuery=$db->prepare('SELECT * FROM movies;');
    $moviesQuery->execute();
    $movies = $moviesQuery->fetchAll(PDO::FETCH_ASSOC);

    $pageTitle = 'Movies';

    include './include/header.php';
?>

<main>
    <div class="container-sm py-5">
        <h2 class="pb-3">Movies</h2>
        <div class="card-deck d-flex flex-wrap justify-content-center">
        <?php foreach ($movies as $movie): ?>
                <a href="./movie/<?= $movie['url']?>">
                    <div class="card m-1">
                        <img class="card-img-left"
                             src="<?= htmlspecialchars($movie['poster']) ?>"
                             alt="<?= htmlspecialchars($movie['name']) ?>"
                             width="172" height="255"/>
                    </div>
                </a>
        <?php endforeach?>

        <?php if(@$authenticatedUser['admin']): ?>
        <!-- Show only to admin -->
            <a href="./movie/new">
                <div class="card m-1 bg-primary d-flex justify-content-center align-items-center" style="width:172px; height:255px;" width="172" height="255">
                    <span>
                        <i class="fa fa-plus-circle text-white" style="font-size:48px"></i>
                    </span>
                </div>
            </a>
        <?php endif ?>
        </div>
    </div>
</main>

<?php include './include/footer.php'; ?>
