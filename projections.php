<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';

    if(empty($_GET['date'])){
        $date = date('Y-m-d');
    } else {
        $date = $_GET['date'];
    }

    $day = new DateTime($date);
    $previousDay = clone($day);
    $previousDay->modify('-1 day');
    $nextDay = clone($day);
    $nextDay->modify('+1 day');

    $projectionTypesQuery=$db->prepare('SELECT DISTINCT movies.movie_id, name, url, language, subtittles, dimensions
                                        FROM projections
                                        JOIN movies ON movies.movie_id = projections.movie_id
                                        WHERE datetime LIKE :date');
    $projectionTypesQuery->execute([
        ':date'=>$date.'%'
    ]);

    $projectionTypes = $projectionTypesQuery->fetchAll(PDO::FETCH_ASSOC);



    $pageTitle = 'Program';

    include './include/header.php';
?>

<main>
    <div class="container-sm py-5">
        <h2 class="pb-3">Program</h2>

        <?php if(@$authenticatedUser['admin']): ?>
            <a href="./projection/new" class="btn btn-primary float-right">
                Add projection
            </a>
        <?php endif ?>

        <div class="btn-toolbar pb-3" role="toolbar">
            <div class="btn-group mr-2">
                <a href="./program/today" class="btn btn-primary">Today</a>
            </div>

            <div class="btn-group mr-2">
                <a href="./program/<?= $previousDay->format('Y-m-d') ?>"
                   class="btn btn-secondary <?= ($date > date('Y-m-d'))?: 'disabled" tabindex="-1" role="button" aria-disabled="true' ?>" >
                    Previous day
                </a>
                <a href="./program/<?= $day->format('Y-m-d')?>" class="btn btn-secondary"><?= $day->format('j.n.Y')?></a>
                <a href="./program/<?= $nextDay->format('Y-m-d') ?>" class="btn btn-secondary">Next day</a>
            </div>
        </div>

        <?php if(empty($projectionTypes)): ?>
            <hr>
            <div class="alert alert-info">
                There is no projection on <?= $day->format('j.n.Y') ?>
            </div>
        <?php else: ?>
            <?php foreach($projectionTypes as $projectionType): ?>
                <hr>
                <h5>
                    <a class="text-dark" href="./movie/<?= htmlspecialchars($projectionType['url'])?>"><?= htmlspecialchars($projectionType['name'])?></a>
                    <span class="badge badge-danger"
                          data-toggle="tooltip"
                          data-placement="bottom"
                          data-html="true"
                          title='<i class="fa fa-volume-up"></i> <?= htmlspecialchars($projectionType['language'])?><br><i class="fa fa-cc"></i> <?= ($projectionType['subtittles'] == '')? '<i class="fa fa-ban"></i>' : htmlspecialchars($projectionType['subtittles'])?>'>
                        <?= $projectionType['language']?>
                    </span>
                    <?php if($projectionType['dimensions'] =='3D'): ?>
                        <span class="badge badge-warning">3D</span>
                    <?php endif ?>
                </h5>

                <?php
                    $projectionsQuery=$db->prepare('SELECT projections.projection_id, datetime, capacity, capacity - COUNT(reservations.reservation_id) AS freeCapacity
                                                            FROM projections
                                                            JOIN movies ON projections.movie_id = movies.movie_id
                                                            LEFT JOIN reservations ON projections.projection_id = reservations.projection_id
                                                            WHERE datetime LIKE :date
                                                                AND name=:name
                                                                AND language=:language
                                                                AND subtittles=:subtittles
                                                                AND dimensions=:dimensions
                                                            GROUP BY projections.projection_id, datetime, capacity
                                                            ORDER BY datetime;');
                        $projectionsQuery->execute([
                            ':date'=>$date.'%',
                            ':name'=>$projectionType['name'],
                            ':language'=>$projectionType['language'],
                            ':subtittles'=>$projectionType['subtittles'],
                            ':dimensions'=>$projectionType['dimensions']
                        ]);

                    $projections = $projectionsQuery->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php foreach($projections as $projection): ?>
                    <?php
                            $time = new DateTime($projection['datetime']);
                            $now = new DateTime();

                            if($projection['freeCapacity'] == 0){
                                $capacity = 'Full capacity';
                            } else{
                                $capacity = 'Capacity: '.$projection['capacity'].'<br>Booked: '.$projection['freeCapacity'];
                            }

                            if(!empty($authenticatedUser)):
                    ?>

                    <div class="btn-group">
                        <a href="./projection/<?= $projection['projection_id']?>"
                           data-toggle="tooltip"
                           data-placement="bottom"
                           data-html="true"
                           title="<?= $capacity ?>"
                           class="btn btn-outline-primary <?= ($time < $now || $projection['freeCapacity'] == '0')? 'disabled" tabindex="-1' : ''?>">
                            <?= $time->format('G:i');?>
                        </a>
                        <?php if(@$authenticatedUser['admin']): ?>
                        <a href="./projection/edit/<?= $projection['projection_id']?>"
                           class="btn btn-outline-secondary <?= ($time > $now)?: 'disabled" tabindex="-1" role="button" aria-disabled="true' ?>">
                           <i class="fa fa-edit"></i>
                        </a>
                        <?php endif ?>
                    </div>
                    <?php else: ?>
                        <a class="btn btn-outline-primary disabled" tabindex="-1" role="button" aria-disabled="true">
                            <?= $time->format('G:i');?>
                        </a>
                    <?php endif ?>

                <?php endforeach ?>
            <?php endforeach ?>
        <?php endif ?>

    </div>
</main>
<?php include './include/footer.php'; ?>
