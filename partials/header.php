<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php
    $loggedInUser = LoginController::loggedInUser();
?>

<header>
    <nav id="navbar_top" class="navbar navbar-expand-md navbar-custom">
        <div class="navbar-header">
            <a href="<?= url('') ?>"><img src="../static/img/logo.svg" alt="Logo."></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <?php if ($loggedInUser) : ?>
                    <li class="nav-item">
                        <form action="<?= url("logout") ?>" method="post">
                            <button class="nav-link primary">Kijelentkezés</button>
                        </form>
                    </li>

                    <a class="nav-link secondary border-only"><?= $loggedInUser["username"] ?></a>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link secondary" href="<?= url('register') ?>">Regisztráció</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link primary" href="<?= url('login') ?>">Belépés</a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </nav>
</header>
