<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
    <title>Eötvös Loránd Stadion - Kezdőlap</title>
    <link type="text/css" rel="stylesheet" href="../static/css/header.css"/>
    <link type="text/css" rel="stylesheet" href="../static/css/main.css"/>
    <link type="text/css" rel="stylesheet" href="../static/css/bootstrap_table.css"/>
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
            <h2>Rólunk</h2>
            <div class="container-fluid">
                <div class="row justify-content-center item-row">
                    <div class="col-md-5 col-lg-4">
                        <p class="justify">Egyetemünk évről évre <b>egyre több</b> <em>mérkőzést</em> rendez. A mérkőzések utáni érdeklődés
                            is <b>ütemesen növekszik</b>, egyre több érdeklődőben felmerült az igény a mérkőzések eredményének
                        nyomonkövetésére. Ugyanankkor a <b>játékosokban</b> is megfogant a vágy visszajelzések, buzdítások
                        virtuális fogadására.</p>
                        <p class="justify">Stadionunk kiemelkedő hangsúlyt fektet a csapatok <b>egyensúlyára</b>, a mérközések
                            <b>komplikációmentes lebonyolítására</b>. Emellett azonban kiemelkedően fontosnak tartjuk
                        a külső visszajelzéseket is.</p>
                    </div>
                    <div class="vl"></div>
                    <div class="col-md-5 col-lg-4 justify">
                        <h3>Az oldal adta lehetőségek:</h3>
                        <ul>
                            <li><b>Eredmények nyomonkövetése:</b><br>
                                Az oldal tartalmazza minden megrendezett meccs eredményét, melyet még az eredmény
                                <b>megismerésének órájában</b> feltöltünk.
                            </li>
                            <li><b>Kedvenc csapatok:</b><br>
                                Lehetőséget adunk kedvenc csapatok kiválasztására. <b>Eredményüket kiemelve</b>
                                láthatod a kezdőlapon.
                            </li>
                            <li><b>Csapatok buzdítása:</b><br>
                                Lehetőség <b>kommenet írni</b> egy csapatnak, ezáltal buzdítva őket a
                                következő megmérettetésre.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="table-container min-height">
            <h2>Csapataink</h2>
            <table class="table paged-table table-striped table-hover" data-rows="10">
                <thead>
                <tr>
                    <th class="text-center">Név</th>
                    <th class="text-center">Város</th>
                    <th class="text-center">Utolsó meccs</th>
                    <?php if ($isLoggedIn) : ?>
                        <th class="text-center sortby">Kedvenc</th>
                    <?php endif ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($teams as $t) : ?>
                    <tr>
                        <td class="text-center"><a href="<?= url('team&id=' . $t["id"]) ?>" ><?= $t["name"] ?></a></td>
                        <td class="text-center"><?= $t["city"] ?></td>
                        <td class="text-center"><?= $t["recently_played"] ?></td>
                        <?php if ($isLoggedIn) : ?>
                            <td class="text-center">
                                <input type="checkbox"
                                       class="star" <?= $t["is_favorite"] ? "checked" : "" ?>
                                       data-href="<?= $t["id"] ?>"/>
                            </td>
                        <?php endif ?>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </section>

        <section id="dynamic" class="table-container min-height">
            <h2>Legutóbbi eredmények</h2>
            <table class="table table-striped table-hover" data-rows="100">
                <thead>
                <tr>
                    <th class="text-center">Hazai</th>
                    <th class="text-center">Vendég</th>
                    <th class="text-center">Eredmény</th>
                    <th class="text-center sortbyrev">Időpont</th>
                    <?php if ($isLoggedIn) : ?>
                        <th class="text-center sortby">Kedvenc</th>
                    <?php endif ?>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="table-footer">
                <nav class="table-nav">
                    <ul class="pagination justify-content-center pagination-sm">
                        <li class="active clickable page-item"><a class="load-more page-link">Továbbiak...</a></li>
                    </ul>
                </nav>
            </div>
        </section>
    </main>
</div>
<!-- scripts only used by bootstrap's navbar and table -->

<script type="module" src="../static/js/bootstrap_table.js"></script>
<script>
    const tableBody = document.querySelector("#dynamic table tbody");
    const spinner = document.querySelector("#dynamic .spinner-border");
    const loadButton = document.querySelector("#dynamic a.load-more");

    async function loadLatestResults(count) {
        if (tableBody !== undefined) {
            const data = new URLSearchParams();
            data.append("startIndex", String(tableBody.childElementCount));
            data.append("count", count);

            await fetch("index.php?page=latest", {
                method: "post",
                body: data
            }).then(async r => {
                try {
                    const response = await r.json();
                    if (response.success) {
                        if (response.results.length === 0){
                            loadButton.style.display = "none";
                            spinner.style.display = "none";
                            return;
                        }

                        response.results.forEach(r => {
                            const newRow = document.createElement("tr");
                            newRow.innerHTML = `
                            <td class="text-center">
                                <a href="${r.home.url}">${r.home.name}</a>
                            </td>
                            <td class="text-center">
                                <a href="${r.away.url}">${r.away.name}</a>
                            </td>
                            <td class="text-center">
                                <p class="score draw">${r.home.score + " - " + r.away.score}</p>
                            </td>
                            <td class="text-center">
                                ${r.date}
                            </td>`
                            if (r.is_favorite !== undefined) {
                                newRow.innerHTML += `
                            <td class="text-center">
                                <input type="checkbox" class="star no-interact"  ${r.is_favorite ? "checked" : ""} />
                            </td>`
                            }
                            tableBody.appendChild(newRow);
                        });
                        spinner.style.display = "none";
                    }
                } catch {} finally {}
            });
        }
    }

    window.addEventListener("load", function () {
        loadLatestResults(10);

        loadButton.addEventListener("click", function () {
            spinner.style.display = "block";
            loadLatestResults(10);
        });
    });
</script>
</body>
</html>