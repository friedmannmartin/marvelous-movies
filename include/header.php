<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <base href="https://eso.vse.cz/~frim00/marvelous-movies/">

    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">

    <!-- Font Awesome 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="shortcut icon" href="./favicon/favicon.ico">

    <!-- SEO -->
    <meta name="description" content="Only Marvelous Movies offer you the best films from Marvel Cinematic Universe. You have unique chance to watch these masterpieces on projections screen in our cinema.">
    <meta name="keywords" content="Marvelous Movies,Cinema,Movies,Marvel,Films,Multiplex">
    <meta name="author" content="Martin Friedmann">

    <title><?= htmlspecialchars($pageTitle) ?> - Marvelous Movies</title>
</head>
<body>
<header class="bg-primary">
    <nav class="navbar navbar-expand-sm navbar-dark container-sm">
        <!-- Web aplication name -->
        <a class="navbar-brand" href="./">MARVELOUS MOVIES</a>

        <!-- Menu opening button on small displays -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Main navigation menu -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item <?php echo ($pageTitle != 'Home') ?: 'active' ?>">
                    <a class="nav-link" href="./">Home</a>
                </li>
                <li class="nav-item <?php echo ($pageTitle != 'Program') ?: 'active' ?>">
                    <a class="nav-link" href="./program/today">Program</a>
                </li>
                <li class="nav-item <?php echo ($pageTitle != 'Movies') ?: 'active' ?>">
                    <a class="nav-link" href="./movies">Movies</a>
                </li>

                <!-- User related links -->
                <?php if(empty($authenticatedUser)): ?>
                <!-- Show if user is not authenticated -->
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pageTitle != 'Sign In') ?: 'active' ?>" href="./sign-in">Sign in</a>
                </li>

                <?php else: ?>
                <!-- Show if user is authenticated -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="#"
                       id="navbarDropdown"
                       role="button"
                       data-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <?= htmlspecialchars($authenticatedUser['name']) ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                        <?php if($authenticatedUser['admin']): ?>
                        <!-- Show if authenticated user is admin -->
                        <a class="dropdown-item <?php echo ($pageTitle != 'Users') ?: 'active' ?>" href="./users">Users</a>
                        <?php endif ?>

                        <a class="dropdown-item <?php echo ($pageTitle != 'Reservations') ?: 'active' ?>" href="./reservations">Reservations</a>
                        <a class="dropdown-item <?php echo ($pageTitle != 'Settings') ?: 'active' ?>" href="./settings">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="./sign-out">Sign out</a>
                    </div>
                </li>
                <?php endif ?>
            </ul>
        </div>
    </nav>
</header>
