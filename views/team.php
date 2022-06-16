<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
    <title>Eötvös Loránd Stadion - <?= $team["name"] ?></title>
    <link type="text/css" rel="stylesheet" href="../static/css/header.css"/>
    <link type="text/css" rel="stylesheet" href="../static/css/main.css"/>
    <link type="text/css" rel="stylesheet" href="../static/css/bootstrap_table.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="../static/js/handler.js"></script>
</head>

<body>
<?php include 'partials/header.php'; ?>
<div id="wrapper">
    <main>
        <section class="about">
            <div class="center">
                <h2><?= $team["name"] ?></h2>
                <?php if ($isLoggedIn) : ?>
                    <input type="checkbox"
                           class="star" <?= $isFavorite ? "checked" : "" ?>
                           data-href="<?= $teamId ?>"/>
                <?php endif ?>
            </div>
            <div class="container-fluid">
                <div class="row justify-content-center item-row">
                    <?php if ($winRatio) : ?>
                        <div class="col-md-3 col-lg-3">
                            <img src="../static/img/trophy.svg" alt="Kupa grafika.">
                            <h1 class="center"><?= $winRatio ?></h1>
                            <h3 class="center">Nyerési arány</h3>
                        </div>
                        <div class="vl"></div>
                    <?php endif ?>
                    <div class="col-md-3 col-lg-3">
                        <img src="../static/img/stars.svg" alt="Követő grafika.">
                        <h1 id="follower-count" class="center"><?= $followers ?></h1>
                        <h3 class="center">Követők</h3>
                    </div>
                    <?php if ($upcoming) : ?>
                        <div class="vl"></div>
                        <div class="col-md-3 col-lg-3">
                            <img src="../static/img/calendar.svg">
                            <h1 class="center"><?= $upcoming["date"] ?></h1>
                            <h3 class="center">Közelgő meccs</h3>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </section>

        <?php if ($upcoming) : ?>
            <section class="table-container">
                <h2>Közelgő meccsek</h2>
                <table class="table paged-table table-striped table-hover" data-rows="5">
                    <thead>
                    <tr>
                        <th class="text-center">Hazai</th>
                        <th class="text-center">Vendég</th>
                        <th class="text-center sortbyrev">Időpont</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($upcoming_matches as $m) : ?>
                        <tr>
                            <td class="text-center">
                                <?php if ($m["home"]["id"] == $teamId) : ?>
                                    <?= $m["home"]["name"] ?>
                                <?php else : ?>
                                    <a href="<?= url('team&id=' . $m["home"]["id"]) ?>" ><?= $m["home"]["name"] ?></a>
                                <?php endif ?>
                            </td>
                            <td class="text-center">
                                <?php if ($m["away"]["id"] == $teamId) : ?>
                                    <?= $m["away"]["name"] ?>
                                <?php else : ?>
                                    <a href="<?= url('team&id=' . $m["away"]["id"]) ?>" ><?= $m["away"]["name"] ?></a>
                                <?php endif ?>
                            </td>
                            <td class="text-center"><?= $m["date"] ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </section>
        <?php endif ?>

        <section class="table-container <?= count($played_matches) !== 0 ? "min-height" : "" ?>" >
            <h2>Meccsek</h2>
            <table class="table paged-table table-striped table-hover" data-rows="10">
                <thead>
                <tr>
                    <th class="text-center">Hazai</th>
                    <th class="text-center">Vendég</th>
                    <th class="text-center">Eredmény</th>
                    <th class="text-center sortbyrev">Időpont</th>
                    <?php if ($canEdit) : ?>
                        <th class="text-center disable-sort">Módosítás</th>
                    <?php endif ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($played_matches as $m) : ?>
                    <tr>
                        <td class="text-center">
                            <?php if ($m["home"]["id"] == $teamId) : ?>
                                <?= $m["home"]["name"] ?>
                            <?php else : ?>
                                <a href="<?= url('team&id=' . $m["home"]["id"]) ?>" ><?= $m["home"]["name"] ?></a>
                            <?php endif ?>
                        </td>
                        <td class="text-center">
                            <?php if ($m["away"]["id"] == $teamId) : ?>
                                <?= $m["away"]["name"] ?>
                            <?php else : ?>
                                <a href="<?= url('team&id=' . $m["away"]["id"]) ?>" ><?= $m["away"]["name"] ?></a>
                            <?php endif ?>
                        </td>
                        <td class="text-center">
                            <p class="score <?= $m["team_won"] == 1 ? "won" : ($m["team_won"] == -1 ? "lost" : "draw") ?>">
                                <?= $m["home"]["score"] . " - " . $m["away"]["score"] ?>
                            </p>
                        </td>
                        <td class="text-center"><?= $m["date"] ?></td>
                        <?php if ($canEdit) : ?>
                            <td class="text-center">
                                <a href="<?= url('match', ["id" => $m["id"], "ref" => $teamId]) ?>"><i class="fa fa-edit"></i></a>
                            </td>
                        <?php endif ?>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
            <?php if (count($played_matches) == 0) : ?>
                <h2 class="text-center">Nincsenek adatok.</h2>
            <?php endif ?>
        </section>

        <section class="table-container">
            <h2>Kommentek</h2>
            <div class="container mt-3 d-flex justify-content-center">
                <div class="row d-flex justify-content-center col-md-12" >
                    <div class="col-md-10">
                        <div class="text-left">
                            <h3>Összesen ( <small id="comment_count" style="font-size: 1.3rem"><?= count($comments) ?></small> )</h3>
                        </div>
                        <div id="comment_container">
                            <?php if (count($comments) == 0) : ?>
                                <h2 class="text-center">Nincsenek hozzászólások.</h2>
                            <?php else : ?>
                                <?php foreach ($comments as $c) : ?>
                                    <div class="card p-3 mb-2">
                                        <?php if ($canEdit) : ?>
                                            <div class="d-flex justify-content-between">
                                                <div></div>
                                                <div>
                                                    <a class="text-muted fw-normal fs-10"
                                                       href="<?= url('comment', ["id" => $c["id"], "ref" => $teamId]) ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif ?>
                                        <div class="d-flex flex-row">
                                            <div class="d-flex flex-column ms-2">
                                                <h6 class="mb-1 fs-l primary"><?= $c["username"] ?></h6>
                                                <p class="fs-m"><?= $c["comment"] ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div></div>
                                            <div>
                                                <span class="text-muted fw-normal fs-10"><?= $c["date"] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-7 d-flex justify-content-center write-comment">
                <div class="row d-flex justify-content-center col-md-12" >
                    <div class="col-md-10">
                        <div class="text-left">
                            <h3>Hozzászólok...</h3>
                        </div>
                        <?php if (!$isLoggedIn) : ?>
                            <h2 class="text-center p-3">Jelentkezz be, a hozzászóláshoz!</h2>
                        <?php else: ?>
                            <div class="d-flex flex-column ms-3">
                                <?php if (isset($errors['comment'])) : ?>
                                    <div class="highlight"><?= $errors['comment'] ?></div>
                                <?php endif ?>
                                <form id="dynamic" method="post" novalidate>
                                <textarea class="form-control" name="comment" rows="5"
                                <?= (isset($errors['comment'])) ? "autofocus" : "" ?>></textarea>
                                    <button class="action inline min-margin" type="submit" tabindex="3">Hozzászólok</button>
                                    <small id="comment_error" class="check"></small>
                                </form>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
<!-- scripts only used by bootstrap's navbar and table -->
<script type="module" src="../static/js/bootstrap_table.js"></script>
<script>
    /* Follower count updater */
    async function updateFollowerCount() {
        const data = new URLSearchParams();
        data.append("teamId", "<?= $teamId ?>");

        await fetch("index.php?page=followers", {
            method: "post",
            body: data
        }).then(async r => {
            try {
                const response = await r.json();
                if (response.success) {
                    const e = document.querySelector("#follower-count");
                    if (e !== undefined) {
                        e.innerHTML = response.followerCount;
                    }
                }
            } catch {} finally {}
        });
    }
    <?php if ($isLoggedIn) : ?>
    /* Ajax comment sender */
    async function onCommentSubmitted(event) {
        event.preventDefault();

        const data = new URLSearchParams();
        data.append("teamId", "<?= $teamId ?>");

        for (const pair of new FormData(this)) {
            data.append(pair[0], pair[1]);
        }

        fetch("<?= url("add-comment") ?>", {
            method: 'post',
            body: data,
        }).then(async raw => {
            try {
                const response = await raw.json();
                const textarea = this.querySelector("textarea");
                const errorField = this.querySelector("#comment_error");
                const comments = document.querySelector("#comment_container");
                const commentCounter = document.querySelector("#comment_count");

                if (response.success) {
                    textarea.value = "";
                    errorField.innerHTML = "";
                    comments.innerHTML = "";

                    const elements = response.comments.map(c => `
                            <div class="card p-3 mb-2">
                                <?php if ($canEdit) : ?>
                                    <div class="d-flex justify-content-between">
                                        <div></div>
                                        <div>
                                            <a class="text-muted fw-normal fs-10"
                                               href="<?= url('comment') ?>&id=${c.id}&ref=<?= $teamId ?>">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <div class="d-flex flex-row">
                                    <div class="d-flex flex-column ms-2">
                                        <h6 class="mb-1 fs-l primary">${c.username}</h6>
                                        <p class="fs-m">${c.comment}</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div></div>
                                    <div>
                                        <span class="text-muted fw-normal fs-10">${c.date}</span>
                                    </div>
                                </div>
                            </div>
                    `);
                    comments.innerHTML = elements.join("");

                    commentCounter.innerHTML = response.comments.length;
                } else {
                    errorField.innerHTML = response.errors[0];
                }
            } catch {} finally {}
        });
    }

    window.addEventListener("load", function () {
        setInterval(updateFollowerCount, 2000);
        const commentForm = document.querySelector("form#dynamic");
        if (commentForm !== undefined)
            commentForm.addEventListener("submit", onCommentSubmitted);
    });
    <?php endif ?>
</script>
</body>
</html>