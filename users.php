<?php
    /* Authorization and Authentication */
    require_once './include/auth.php';
    authorizedAdminOnly();

    /* Getting list of all users */
    $usersQuery=$db->prepare('SELECT * FROM users;');
    $usersQuery->execute();
    $users=$usersQuery->fetchAll(PDO::FETCH_ASSOC);

    $pageTitle='Users';

    include './include/header.php';
?>
<main>
    <div class="container-sm py-5">
        <h2 class="pb-3">Users</h2>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Edit</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach($users as $user): ?>
                    <tr>
                        <td><?=htmlspecialchars($user['name'])?></td>
                        <td><?=htmlspecialchars($user['email'])?></td>
                        <td><a href="./user/<?=htmlspecialchars($user['user_id'])?>" class="btn btn-warning">Edit user <i class="fa fa-edit"></i></a></td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include './include/footer.php' ?>
