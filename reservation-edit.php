<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    authorizedUsersOnly();

    if(!empty($_GET['projection_id'])){

        $projectionQuery=$db->prepare('SELECT projection_id, capacity - COUNT(reservation_id) AS freeCapacity
                                            FROM reservations
                                            RIGHT JOIN projections USING (projection_id)
                                            WHERE projection_id=:projection_id
                                            GROUP BY projection_id;');
        $projectionQuery->execute([
            ':projection_id'=>$_GET['projection_id']
        ]);
        $projection=$projectionQuery->fetch(PDO::FETCH_ASSOC);

        /* Checking whether projection id exists */
        if(empty($projection)){
            header("HTTP/1.1 404 Not Found");
            header("Location: ../error-404");
            exit();
        }

        /* Creating new reservation */
        if($_GET['act']=='add'){
            /* Checking whether projection have availible capacity */
            if($projection['freeCapacity'] <= 0){
                header("HTTP/1.1 400 Bad Request");
                header("Location: ../error-400");
                exit();
            }

            $insertReservationQuery=$db->prepare('INSERT INTO reservations (projection_id, user_id) VALUES (:projection_id, :user_id)');
            $insertReservationQuery->execute([
                ':projection_id'=>$_GET['projection_id'],
                ':user_id'=>$authenticatedUser['user_id']
            ]);

            header('Location: ../../reservations');
            exit();
        }

        /* Removing existing reservation */
        if($_GET['act']=='remove'){
            $deleteReservationQuery=$db->prepare('DELETE FROM reservations WHERE projection_id=:projection_id AND user_id=:user_id LIMIT 1;');
            $deleteReservationQuery->execute([
                ':projection_id'=>$_GET['projection_id'],
                ':user_id'=>$authenticatedUser['user_id']
            ]);

            header('Location: ../../reservations');
            exit();
        }

    }
