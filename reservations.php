<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    authorizedUsersOnly();

    /* Getting list of all users reservations */
    $reservationsQuery=$db->prepare('SELECT datetime, url, name, language, subtittles, dimensions, hall, reservations.projection_id, COUNT(reservation_id) AS booked_seats, free_capacity
                                        FROM reservations
                                        JOIN projections ON reservations.projection_id = projections.projection_id
                                        JOIN movies ON projections.movie_id = movies.movie_id
                                        JOIN (SELECT projections.projection_id, capacity - COUNT(reservation_id) AS free_capacity
                                                FROM projections
                                                JOIN reservations ON reservations.projection_id = projections.projection_id
                                                GROUP BY reservations.projection_id) AS capacity_select
                                            ON reservations.projection_id = capacity_select.projection_id
                                        WHERE user_id=:user_id
                                        GROUP BY datetime, url, name, language, subtittles, dimensions, hall, projection_id;');
    $reservationsQuery->execute([
        ':user_id'=>$authenticatedUser['user_id']
    ]);
    $reservations = $reservationsQuery->fetchAll(PDO::FETCH_ASSOC);

    $pageTitle = 'Reservations';

    include './include/header.php';
?>

<main>
    <div class="container-sm py-5">
        <h2 class="pb-3">Reservations</h2>
        <?php if(empty($reservations)): ?>
            <div class="alert alert-info">
                You have no reservation.
            </div>
        <?php else: ?>
            <div class="card-deck justify-content-around">
            <?php foreach ($reservations as $reservation): ?>
                <?php $datetime = new DateTime($reservation['datetime']); ?>
                <div>
                    <div class="card mb-3">
                        <h5 class="card-header bg-primary">
                            <a class="text-light" href="./movie/<?= htmlspecialchars($reservation['url'])?>">
                                <?= htmlspecialchars($reservation['name'])?>
                            </a>
                        </h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span class="badge badge-danger"
                                                data-toggle="tooltip"
                                                data-placement="bottom"
                                                data-html="true"
                                                title='<i class="fa fa-volume-up"></i> <?= htmlspecialchars($reservation['language'])?><br>
                                                        <i class="fa fa-cc"></i> <?= ($reservation['subtittles'] == '')? '<i class="fa fa-ban"></i>' : htmlspecialchars($reservation['subtittles'])?>'>
                                                <?= $reservation['language']?>
                                    </span>
                                <?php if($reservation['dimensions'] =='3D'): ?>
                                    <span class="badge badge-warning">3D</span>
                                <?php endif ?>
                            </li>
                            <li class="list-group-item">Date: <?= $datetime->format('j.n.Y') ?></li>
                            <li class="list-group-item">Time: <?= $datetime->format('H:i') ?></li>
                            <li class="list-group-item">Hall: <?= $reservation['hall'] ?></li>
                        </ul>
                        <div class="card-footer text-white bg-secondary d-flex justify-content-between align-items-center">
                            <span class="mr-2">Your booked seats:</span>
                            <div class="btn-group">
                                <a href="./reservation/remove/<?= $reservation['projection_id']?>" class="btn btn-danger"><i class="fa fa-minus"></i></a>
                                <span class="btn btn-light"><?= $reservation['booked_seats'] ?></span>
                                <a href="./reservation/add/<?= $reservation['projection_id']?>" class="btn btn-success <?= ($reservation['free_capacity'] <= 0)? 'disabled" tabindex="-1' : '' ?>"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</main>

<?php include './include/footer.php'; ?>
