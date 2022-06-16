<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
    <title>Eötvös Loránd Stadion - Fiók létrehozása</title>
    <link type="text/css" rel="stylesheet" href="../static/css/header-minimal.css"/>
    <link type="text/css" rel="stylesheet" href="../static/css/main.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<?php include 'partials/header-minimal.php'; ?>

<div class="modal-wrapper">
    <div class="modal-selection">
        <nav id="menu">
            <ul>
                <li><a href="<?= url("register") ?>" class="selection-item is-active">Regisztráció</a></li>
                <li><a href="<?= url("login") ?>" class="selection-item">Belépés</a></li>
                <li><span class="selection-indicator"></span></li>
            </ul>
        </nav>
    </div>
    <div class="modal-content">
        <h1 class="center">Regisztráció</h1>
        <div>
            <form method="post" novalidate>
                <?php if (isset($errors['global'])) : ?>
                    <div class="highlight"><?= $errors['global'] ?></div>
                <?php endif ?>

                <div class="input-holder">
                    <input class="input" id="email" name="email" type="email"
                           value="<?= $old['email'] ?? '' ?>"
                           placeholder=" "
                           tabindex="1"
                        <?= isset($errors['email']) ? "autofocus" : "" ?> >
                    <label for="email" class="placeholder">Email</label>
                    <div class="check">
                        <?php if (isset($errors['email'])) : ?>
                            <small><?= $errors['email'] ?></small>
                        <?php endif ?>
                    </div>
                </div>

                <div class="input-holder">
                    <input class="input" id="username" name="username" type="text"
                           value="<?= $old['username'] ?? '' ?>"
                           placeholder=" "
                           tabindex="2"
                        <?= isset($errors['username']) ? "autofocus" : "" ?> >
                    <label for="username" class="placeholder">Felhasználónév</label>
                    <div class="check">
                        <?php if (isset($errors['username'])) : ?>
                            <small><?= $errors['username'] ?></small>
                        <?php endif ?>
                    </div>
                </div>

                <div class="input-holder">
                    <input class="input" id="password" name="password" type="password" placeholder=" " tabindex="3"
                        <?= (isset($errors['password']) || isset($errors['password_again'])) ? "autofocus" : "" ?> >
                    <label for="password" class="placeholder">Jelszó</label>
                    <div class="check">
                        <?php if (isset($errors['password'])) : ?>
                            <small><?= $errors['password'] ?></small>
                        <?php endif ?>
                    </div>
                </div>

                <div class="input-holder">
                    <input class="input" id="password_again" name="password_again" type="password" placeholder=" " tabindex="4"
                        <?= isset($errors['password_again']) ? "autofocus" : "" ?> >
                    <label for="password_again" class="placeholder">Jelszó újra</label>
                    <div class="check">
                        <?php if (isset($errors['password_again'])) : ?>
                            <small><?= $errors['password_again'] ?></small>
                        <?php endif ?>
                    </div>
                </div>

                <button class="action" type="submit" tabindex="5">Regisztráció</button>
            </form>
        </div>
    </div>
</div>
<script src="../static/js/tabbed_modal.js"></script>
</body>
</html>