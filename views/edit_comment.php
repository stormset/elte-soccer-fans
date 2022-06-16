<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
    <title>Eötvös Loránd Stadion - Komment módosítása</title>
    <link type="text/css" rel="stylesheet" href="../static/css/header-minimal.css"/>
    <link type="text/css" rel="stylesheet" href="../static/css/main.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<?php include 'partials/header-minimal.php'; ?>

<div class="modal-wrapper">
    <div class="modal-content">
        <h1 class="center">Módosítás</h1>
        <div>
            <form method="post" novalidate>
                <?php if (isset($errors['global'])) : ?>
                    <div class="highlight"><?= $errors['global'] ?></div>
                <?php endif ?>

                <fieldset>
                    <legend>Komment</legend>
                    <textarea class="form-control" name="comment" rows="5"
                        <?= (isset($errors['comment'])) ? "autofocus" : "" ?>><?= $old['comment'] ?? $comment["comment"] ?? '' ?></textarea>
                    <div class="check">
                        <?php if (isset($errors['comment'])) : ?>
                            <small><?= $errors['comment'] ?></small>
                        <?php endif ?>
                    </div>
                </fieldset>

                <button class="action" type="submit" tabindex="3">Mentés</button>
            </form>

            <button class="action submit inline" onclick="location.href='<?= $prevUrl ?>'" type="button">Mégsem</button>
            <form action="<?= url("delete-comment") ?>" method="post">
                <input type="hidden" name="id" value="<?= $comment["id"] ?>">
                <?php if (isset($prevTeamId)) : ?>
                    <input type="hidden" name="ref" value="<?= $prevTeamId ?>">
                <?php endif ?>
                <button class="action inline" type="submit">Törlés</button>
            </form>
        </div>
    </div>
</div>

<script type="module" src="../static/js/tabbed_modal.js"></script>
</body>
</html>