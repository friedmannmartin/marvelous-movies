<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';


    if(!empty($_GET['url'])){
        $movieQuery=$db->prepare('SELECT * FROM movies WHERE url=:url LIMIT 1;');
        $movieQuery->execute([
            ':url'=>$_GET['url']
        ]);

        if($movieQuery->rowCount()!=1){
            header("HTTP/1.1 404 Not Found");
            header("Location: ../error-404");
            exit();
        }

        /* $authenticatedUser - Currently logged in user */
        $movie = $movieQuery->fetch(PDO::FETCH_ASSOC);
    }

    $pageTitle = htmlspecialchars($movie['name']);

    include './include/header.php';
?>

<main>
    <div class="container-sm py-5">

        <?php if(@$authenticatedUser['admin']): ?>
        <!-- Show if authenticated user is admin -->
            <a href="./movie/edit/<?= $movie['movie_id']?>" class="btn btn-primary float-right">Edit movie</a>
        <?php endif ?>

        <h2 class="pb-3"><?= htmlspecialchars($movie['name']) ?></h2>
        <div class="row d-flex align-items-center">
            <div class="col-sm order-sm-2">
                <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item"
                        src="https://www.youtube.com/embed/<?= htmlspecialchars($movie['trailer']) ?>"
                        allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-sm">
                <ul class="list-group list-group-horizontal-sm justify-content-center text-center my-3">
                    <li class="list-group-item">Rating: <?= htmlspecialchars($movie['age_restriction'])?></li>
                    <li class="list-group-item">Year: <?= htmlspecialchars($movie['year'])?></li>
                    <li class="list-group-item">Length: <?= htmlspecialchars($movie['length'])?> min</li>
                </ul>
                <p class="text-justify"><?= htmlspecialchars($movie['description'])?></p>
            </div>
        </div>
        <div class="row">
            <!-- TODO seznam promítání-->
        </div>
    </div>
</main>

<?php include './include/footer.php'; ?>
