<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    authorizedAdminOnly();

    /* Getting a edited projection */
    if(!empty($_GET['projection_id'])){
        $projectionQuery=$db->prepare('SELECT * FROM projections WHERE projection_id=:projection_id LIMIT 1;');
        $projectionQuery->execute([
            ':projection_id'=>$_GET['projection_id']
        ]);

        if($projectionQuery->rowCount()!=1){
            header("HTTP/1.1 404 Not Found");
            header("Location: ../error-404");
            exit();
        }

        /* $projection - Currently edited projection */
        $projection=$projectionQuery->fetch(PDO::FETCH_ASSOC);

        /* $deleteLock - Prevention from deleting projection with existing reservations (true=locked, false=unlocked) */
        $deleteLock=true;
        $deleteLockQuery=$db->prepare('SELECT * FROM projections JOIN reservations USING (projection_id) WHERE projection_id=:projection_id');
        $deleteLockQuery->execute([
            ':projection_id'=>$projection['projection_id']
        ]);
        if(empty($deleteLockQuery->fetchAll(PDO::FETCH_ASSOC))){
            $deleteLock=false;
        }
    }

    /* Delete projection from database */
    if((@$_POST['delete-projection'])&&($deleteLock==false)){
        $deleteProjectionQuery=$db->prepare('DELETE FROM projections WHERE projection_id=:projection_id LIMIT 1;');
        $deleteProjectionQuery->execute([
            ':projection_id'=>$_GET['projection_id']
        ]);

        header('Location: ../../program/today');
        exit();
    }

    /* Getting list of all movies */
    $moviesQuery=$db->prepare('SELECT * FROM movies;');
    $moviesQuery->execute();
    $movies=$moviesQuery->fetchAll(PDO::FETCH_ASSOC);

    /* If there is posted form try update/create movie */
    $errors=[];
    if(!empty($_POST)){

        /* Movie validation*/
        $movieId=trim(@$_POST['movie-id']);
        if(!empty($movieId)){
            if(!in_array($movieId, array_column($movies,'movie_id'))){
                $errors['movie-id']='You need to select valid movie.';
            }
        } else{
            $errors['movie-id']='You need to select movie.';
        }

        /* Datetime validation */
        if(date_create(trim(@$_POST['datetime']))){
            $datetime=new DateTime(trim(@$_POST['datetime']));
            if($datetime<date_create()){
                $errors['datetime']='You need to enter valid date and time.';
            }
        } else {
            $errors['datetime']='You need to enter valid date and time.';
        }

        /* Language */
        $language=trim(@$_POST['language']);
        if(empty($language)){
            $errors['language']='You need to enter language of the projection.';
        }

        /* Subtittles */
        $subtittles=trim(@$_POST['subtittles']);

        /* Dimensions */
        $dimensions=trim(@$_POST['dimensions']);
        if(!empty($dimensions)){
            if($dimensions != '2D' && $dimensions != '3D'){
                $errors['dimensions']='You need to enter whether projection is 2D or 3D.';
            }
        } else {
            $errors['dimensions']='You need to enter whether projection is 2D or 3D.';
        }

        /* Capacity */
        $capacity=trim(@$_POST['capacity']);
        if($capacity < 1){
            $errors['capacity']='You need to capacity of the projection.';
        }

        /* Hall */
        $hall=trim(@$_POST['hall']);
        if(!empty($hall)){
            if(!in_array($hall, array('Main','n.1', 'n.2', 'n.3'))){
                $errors['hall']='You need to enter valid projection hall.';
            }
        } else {
            $errors['hall']='You need to enter where is projection projected.';
        }

        if(empty($errors)){
            if(empty($_GET['projection_id'])){
            /* Insertion of new projection to database */
                $insertProjection=$db->prepare('INSERT INTO projections (  movie_id,   datetime,   language,   subtittles,   dimensions,   capacity,   hall)
                                                                VALUES ( :movie_id,  :datetime,  :language,  :subtittles,  :dimensions,  :capacity,  :hall);');
                $insertProjection->execute([
                    ':movie_id'=>$movieId,
                    ':datetime'=>$datetime->format('Y-m-d H:i:s'),
                    ':language'=>$language,
                    ':subtittles'=>$subtittles,
                    ':dimensions'=>$dimensions,
                    ':capacity'=>$capacity,
                    ':hall'=>$hall
                ]);

                $lastInsertedProjection=$db->lastInsertId();
                header("Location: ./edit/$lastInsertedProjection");
                exit();
            } else {
                /* Update projection in database */
                if(empty($errors)){
                    $upadteMovie=$db->prepare('UPDATE projections SET movie_id=:movie_id,
                                                                     datetime=:datetime,
                                                                     language=:language,
                                                                     subtittles=:subtittles,
                                                                     dimensions=:dimensions,
                                                                     capacity=:capacity,
                                                                     hall=:hall
                                                WHERE projection_id=:projection_id LIMIT 1;');
                    $upadteMovie->execute([
                        ':projection_id'=>$_GET['projection_id'],
                        ':movie_id'=>$movieId,
                        ':datetime'=>$datetime->format('Y-m-d H:i:s'),
                        ':language'=>$language,
                        ':subtittles'=>$subtittles,
                        ':dimensions'=>$dimensions,
                        ':capacity'=>$capacity,
                        ':hall'=>$hall
                    ]);

                    header('Location: '.$_SERVER['REQUEST_URI']);
                    exit();
                }
            }
        }
    }

    /* Setting pageTitle based on whether editing projection or creating new one */
    if(!empty($_GET['projection_id'])){
        $pageTitle='Editing projection n.'.htmlspecialchars($projection['projection_id']);
    } else {
        $pageTitle='New projection';
    }

    include './include/header.php';
?>
<main class="d-flex align-items-center">
    <div class="container-sm py-5">

        <?php if(!empty($_GET['projection_id'])): ?>
            <form method="post" class="d-inline">
                <input type="hidden" name="delete-projection" value="true">
                <input type="submit" class="btn btn-danger float-right"  <?=($deleteLock)?'disabled':''?> value="Delete projection">
            </form>
        <?php endif ?>

        <h2 class="pb-3"><?=$pageTitle?></h2>

        <form method="post">

            <!-- Movie -->
            <div class="form-group">
                <label for="movie-id">Select movie:</label>
                <select name="movie-id"
                        id="movie-id"
                        required
                        class="form-control <?=(!empty($errors['movie-id']))?'is-invalid':''?>">
                    <option value="" disabled selected>Select movie</option>
                <?php foreach($movies as $movie): ?>
                    <option value="<?=$movie['movie_id']?>" <?=(@$projection['movie_id']!=$movie['movie_id'])?:'selected'?>><?=$movie['name']?></option>
                <?php endforeach ?>
                </select>
                <?php if(!empty($errors['movie-id'])): ?>
                    <div class="invalid-feedback"><?=$errors['movie-id']?></div>
                <?php endif ?>
            </div>

            <!-- Datetime -->
            <div class="form-group">
                <label for="datetime">Select date and time of projection:</label>
                <input type="datetime-local"
                       name="datetime"
                       id="datetime"
                       placeholder="Enter name date and time of the projection"
                       min="<?=date('Y-m-d\TH:i')?>"
                       required
                       value="<?=(empty($projection['datetime']))? date('Y-m-d\TH:i'):date('Y-m-d\TH:i', strtotime(@$projection['datetime']))?>"
                       class="form-control <?=(!empty($errors['datetime']))?'is-invalid':''?>"/>
                <?php if(!empty($errors['datetime'])): ?>
                    <div class="invalid-feedback"><?=$errors['datetime']?></div>
                <?php endif ?>
            </div>

            <div class="form-row">

                <!-- Language -->
                <div class="form-group col-sm">
                    <label for="name">Language:</label>
                    <input type="text"
                           name="language"
                           id="language"
                           placeholder="Enter projection language"
                           required
                           class="form-control <?=(!empty($errors['language']))?'is-invalid':''?>"
                           value="<?=htmlspecialchars(@$projection['language'])?>" />
                    <?php if(!empty($errors['language'])): ?>
                        <div class="invalid-feedback"><?=$errors['language']?></div>
                    <?php endif ?>
                </div>

                <!-- Subtittles -->
                <div class="form-group col-sm">
                    <label for="name">Subtittles:</label>
                    <input type="text"
                           name="subtittles"
                           id="subtittles"
                           placeholder="Enter language of projection subtittles"
                           class="form-control <?=(!empty($errors['subtittles']))?'is-invalid':''?>"
                           value="<?=htmlspecialchars(@$projection['subtittles'])?>" />
                    <?php if(!empty($errors['subtittles'])): ?>
                        <div class="invalid-feedback"><?=$errors['subtittles']?></div>
                    <?php endif ?>
                </div>

                <!-- Dimensions -->
                <div class="form-group col-sm">
                    <label for="dimensions">Dimensions:</label>
                    <select name="dimensions"
                            id="dimensions"
                            required
                            class="form-control <?=(!empty($errors['dimensions']))?'is-invalid':''?>">
                        <option value="" disabled selected>Select dimensions</option>
                        <option value="2D" <?=(@$projection['dimensions']=='2D')?'selected':''?>>2D</option>
                        <option value="3D" <?=(@$projection['dimensions']=='3D')?'selected':''?>>3D</option>
                    </select>
                    <?php if(!empty($errors['dimensions'])): ?>
                        <div class="invalid-feedback"><?=$errors['dimensions']?></div>
                    <?php endif ?>
                </div>

            </div>

            <div class="form-row">

                <!-- Capacity -->
                <div class="form-group col-sm">
                    <label for="capacity">Capacity:</label>
                    <input type="number"
                           name="capacity"
                           id="capacity"
                           placeholder="Enter projection capacity"
                           min="1"
                           required
                           class="form-control <?=(!empty($errors['capacity']))?'is-invalid':''?>"
                           value="<?=htmlspecialchars(@$projection['capacity'])?>" />
                    <?php if(!empty($errors['capacity'])): ?>
                        <div class="invalid-feedback"><?=$errors['capacity']?></div>
                    <?php endif ?>
                </div>

                <!-- Hall -->
                <div class="form-group col-sm">
                    <label for="hall">Hall:</label>
                    <select name="hall"
                            id="hall"
                            required
                            class="form-control <?=(!empty($errors['hall']))?'is-invalid':''?>">
                        <option value="" disabled selected>Select hall</option>
                        <option value="Main" <?=(@$projection['hall']=='Main')?'selected':''?>>Main</option>
                        <option value="n.1" <?=(@$projection['hall']=='n.1')?'selected':''?>>n.1</option>
                        <option value="n.2" <?=(@$projection['hall']=='n.2')?'selected':''?>>n.2</option>
                        <option value="n.3" <?=(@$projection['hall']=='n.3')?'selected':''?>>n.3</option>
                    </select>
                    <?php if(!empty($errors['hall'])): ?>
                        <div class="invalid-feedback"><?=$errors['hall']?></div>
                    <?php endif ?>
                </div>

            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="./program/today"    class="btn btn-light">Cancel</a>
        </form>
    </div>
</main>
<?php include './include/footer.php' ?>
