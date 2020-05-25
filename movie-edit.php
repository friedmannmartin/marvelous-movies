<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    authorizedAdminOnly();

    /* Getting a edited movie */
    if(!empty($_GET['movie_id'])){
        $movieQuery=$db->prepare('SELECT * FROM movies WHERE movie_id=:movie_id LIMIT 1;');
        $movieQuery->execute([
            ':movie_id'=>$_GET['movie_id']
        ]);

        if($movieQuery->rowCount()!=1){
            header("HTTP/1.1 404 Not Found");
            header("Location: ../error-404");
            exit();
        }

        /* $movie - Currently edited movie */
        $movie=$movieQuery->fetch(PDO::FETCH_ASSOC);


        /* $deleteLock - Prevention from deleting movie with scheduled projections (true=locked, false=unlocked) */
        $deleteLock=true;
        $deleteLockQuery=$db->prepare('SELECT * FROM movies JOIN projections USING (movie_id) WHERE movie_id=:movie_id');
        $deleteLockQuery->execute([
            ':movie_id'=>$movie['movie_id']
        ]);
        if(empty($deleteLockQuery->fetchAll(PDO::FETCH_ASSOC))){
            $deleteLock=false;
        }
    }

    /* Delete movie from database */
    if((@$_POST['delete-movie'])&&($deleteLock==false)){

        $deleteMovieQuery=$db->prepare('DELETE FROM movies WHERE movie_id=:movie_id LIMIT 1;');
        $deleteMovieQuery->execute([
            ':movie_id'=>$_GET['movie_id']
        ]);

        header('Location: ../../movies');
        exit();

    }

    /* If there is posted form try update/create movie */
    $errors=[];
    if(!empty($_POST)){

        /* Name validation */
        $name=trim(@$_POST['name']);
        if(empty($name)){
            $errors['name']='You need to enter name of the movie.';
        }

        /* URL validation */
        $url=trim(@$_POST['url']);
        if(!empty($url)){
            $urlQuery=$db->prepare('SELECT * FROM movies WHERE url=:url LIMIT 1;');
            $urlQuery->execute([
                ':url'=>$url
            ]);
            $movieUrlValidation=$urlQuery->fetch(PDO::FETCH_ASSOC);

            if(!empty($movieUrlValidation)){
                if($_GET['movie_id'] != $movieUrlValidation['movie_id']){
                    /* URL already exists */
                    $errors['url']='You need to enter unique URL for the movie.';
                }
            }
        } else {
            /* URL needs to be set */
            $errors['url']='You need to enter unique URL for the movie.';
        }

        /* Trailer validation */
        $trailer=trim(@$_POST['trailer']);
        if(empty($trailer)){
            $errors['trailer']='You need to enter YouTube video ID for the movie trailer.';
        }

        /* Poster validation */
        $poster=trim(@$_POST['poster']);
        if(!filter_var($poster,FILTER_VALIDATE_URL)){
            $errors['poster']='You need to enter valid URL.';
        }

        /* Age restriction validation */
        $ageRestriction=trim(@$_POST['age-restriction']);
        if(!in_array($ageRestriction, array('G', 'PG', 'PG-13', 'R', 'NC-17'))){
            $errors['age-restriction']='You need to select age restriction rating.';
        }

        /* Year validation */
        $year=trim(@$_POST['year']);
        if($year<1970 && date("Y")<$year){
            $errors['year']='You need to enter valid movie release date.';
        }

        /* Length validation */
        $length=trim(@$_POST['length']);
        if($length < 1){
            $errors['length']='You need to enter valid movie length.';
        }

        /* Description validation */
        $description=trim(@$_POST['description']);
        if(empty($description)){
            $errors['description']='You need to enter movie description.';
        }

        if(empty($errors)){
            if(empty($_GET['movie_id'])){
            /* Insertion of new movie to database */
                $insertMovie=$db->prepare('INSERT INTO movies ( url,  name,  age_restriction,  year,  description,  length,  trailer,  poster)
                                                       VALUES (:url, :name, :age_restriction, :year, :description, :length, :trailer, :poster);');
                $insertMovie->execute([
                    ':url'=>$url,
                    ':name'=>$name,
                    ':age_restriction'=>$ageRestriction,
                    ':year'=>$year,
                    ':description'=>$description,
                    ':length'=>$length,
                    ':trailer'=>$trailer,
                    ':poster'=>$poster
                ]);

                $lastInsertedMovie=$db->lastInsertId();
                header("Location: ./edit/$lastInsertedMovie");
                exit();
            } else {
                /* Update movie in database */
                if(empty($errors)){
                    $upadteMovie=$db->prepare('UPDATE movies SET url=:url, name=:name, age_restriction=:age_restriction, year=:year, description=:description, length=:length, trailer=:trailer, poster=:poster WHERE movie_id=:movie_id LIMIT 1;');
                    $upadteMovie->execute([
                        ':movie_id'=>$_GET['movie_id'],
                        ':url'=>$url,
                        ':name'=>$name,
                        ':age_restriction'=>$ageRestriction,
                        ':year'=>$year,
                        ':description'=>$description,
                        ':length'=>$length,
                        ':trailer'=>$trailer,
                        ':poster'=>$poster
                    ]);

                    header('Location: '.$_SERVER['REQUEST_URI']);
                    exit();
                }
            }
        }

    }

    /* Setting pageTitle based on whether editing movie or creating new one */
    if(!empty($_GET['movie_id'])){
        $pageTitle='Editing '.htmlspecialchars($movie['name']);
    } else {
        $pageTitle='New movie';
    }

    include './include/header.php';
?>
<main class="d-flex align-items-center">
    <div class="container-sm py-5">
        <?php if(!empty($_GET['movie_id'])): ?>
        <form method="post" class="d-inline">
            <input type="hidden" name="delete-movie" value="true">
            <input type="submit" class="btn btn-danger float-right"  <?=($deleteLock)?'disabled':''?> value="Delete movie">
        </form>
        <?php endif ?>

        <h2 class="pb-3"><?=$pageTitle?></h2>

        <form method="post">

                <!-- Name -->
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text"
                           name="name"
                           id="name"
                           placeholder="Enter name of the movie"
                           required
                           class="form-control <?=(!empty($errors['name']))?'is-invalid':''?>"
                           value="<?=htmlspecialchars(@$movie['name'])?>" />
                    <?php if(!empty($errors['name'])): ?>
                        <div class="invalid-feedback"><?=$errors['name']?></div>
                    <?php endif ?>
                </div>

                <!-- URL -->
                <div class="form-group">
                    <label for="url">URL:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="url">https://marvelous-movies.com/movie/</span>
                        </div>
                        <input type="text"
                               name="url"
                               id="url"
                               placeholder="Enter uniqie URL for the movie"
                               required
                               class="form-control <?=(!empty($errors['url'])?'is-invalid':'')?>"
                               value="<?=htmlspecialchars(@$movie['url'])?>" />
                        <?php if(!empty($errors['url'])): ?>
                            <div class="invalid-feedback"><?=$errors['url']?></div>
                        <?php endif ?>
                    </div>
                </div>

                <!-- Trailer -->
                <div class="form-group">
                    <label for="url">Trailer:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="url">https://www.youtube.com/watch?v=</span>
                        </div>
                        <input type="text"
                               name="trailer"
                               id="trailer"
                               placeholder="Enter YouTube video ID for the movie trailer"
                               required
                               class="form-control <?=(!empty($errors['trailer']))?'is-invalid':''?>"
                               value="<?=htmlspecialchars(@$movie['trailer'])?>" />
                        <?php if(!empty($errors['trailer'])): ?>
                            <div class="invalid-feedback"><?=$errors['trailer']?></div>
                        <?php endif ?>
                    </div>
                </div>

                <!-- Poster -->
                <div class="form-group">
                    <label for="name">Poster:</label>
                    <input type="url"
                           name="poster"
                           id="poster"
                           placeholder="Enter URL of the movie poster"
                           required
                           class="form-control <?=(!empty($errors['poster']))?'is-invalid':''?>"
                           value="<?=htmlspecialchars(@$movie['poster'])?>" />
                    <?php if(!empty($errors['poster'])): ?>
                        <div class="invalid-feedback"><?=$errors['poster']?></div>
                    <?php endif ?>
                </div>

                <div class="form-row">

                    <!-- Age restriction -->
                    <div class="form-group col-sm">
                        <label for="age-restriction">Select age restriction:</label>
                        <select name="age-restriction"
                                id="age-restriction"
                                required
                                class="form-control <?=(!empty($errors['age-restriction']))?'is-invalid':''?>">
                            <option value="" disabled selected>Select age restriction</option>
                            <option <?=(@$movie['age_restriction']=="G")?'selected':''?> value="G">G</option>
                            <option <?=(@$movie['age_restriction']=="PG")?'selected':''?> value="PG">PG</option>
                            <option <?=(@$movie['age_restriction']=="PG-13")?'selected':''?> value="PG-13">PG-13</option>
                            <option <?=(@$movie['age_restriction']=="R")?'selected':''?> value="R">R</option>
                            <option <?=(@$movie['age_restriction']=="NC-17")?'selected':''?> value="NC-17">NC-17</option>

                        </select>
                        <?php if(!empty($errors['age-restriction'])): ?>
                            <div class="invalid-feedback"><?=$errors['age-restriction']?></div>
                        <?php endif ?>
                    </div>

                    <!-- Year-->
                    <div class="form-group col-sm">
                        <label for="year">Year:</label>
                        <input type="number"
                               name="year"
                               id="year"
                               placeholder="Enter year when was movie released"
                               min="1970"
                               max="2020"
                               required
                               class="form-control <?=(!empty($errors['year']))?'is-invalid':''?>"
                               value="<?=htmlspecialchars(@$movie['year'])?>" />
                        <?php if(!empty($errors['year'])): ?>
                            <div class="invalid-feedback"><?=$errors['year']?></div>
                        <?php endif ?>
                    </div>

                    <!-- Length-->
                    <div class="form-group col-sm">
                        <label for="length">Length:</label>
                        <div class="input-group">
                            <input type="number"
                                   name="length"
                                   id="length"
                                   placeholder="Enter length of the movie"
                                   min="1"
                                   required
                                   class="form-control <?=(!empty($errors['length']))?'is-invalid':''?>"
                                   value="<?=htmlspecialchars(@$movie['length'])?>" />
                            <div class="input-group-append">
                                <span class="input-group-text" id="length">min</span>
                            </div>
                        </div>
                        <?php if(!empty($errors['length'])): ?>
                            <div class="invalid-feedback"><?=$errors['length']?></div>
                        <?php endif ?>
                    </div>

                </div>

                <!-- Description-->
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description"
                            id="description"
                            placeholder="Type movie description"
                            rows="4"
                            required
                            class="form-control <?=(!empty($errors['description']))?'is-invalid':''?>"><?=htmlspecialchars(@$movie['description'])?></textarea>
                    <?php if(!empty($errors['description'])): ?>
                        <div class="invalid-feedback"><?=$errors['description']?></div>
                    <?php endif ?>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="./movies"    class="btn btn-light">Cancel</a>
        </form>
    </div>
</main>
<?php include './include/footer.php' ?>
