window.addEventListener("load", function () {
    document.addEventListener("click", function (event) {
        if (event.target.matches("input[type=checkbox].star") && event.target.dataset["href"] !== undefined) {
            event.preventDefault();
            const checked = event.target.checked;
            const teamId = event.target.dataset["href"];

            try {
                (async function addAsFavorite(teamId, isChecked) {
                    const data = new URLSearchParams();
                    data.append("teamId", teamId);
                    data.append("action", checked ? "add" : "remove");

                    return fetch("index.php?page=change-favorite", {
                        method: "post",
                        body: data
                    });
                }).call(this, teamId, checked)
                    .then(async raw => {
                        try {
                            const response = await raw.json();
                            if (response.success) {
                                event.target.checked = checked;
                            }
                        } catch {} finally {}
                    });
            } catch {} finally {}
        }
     });
});

window.addEventListener('scroll', function() {
    let navbar_height;
    if (window.scrollY > 100) {
        document.getElementById('navbar_top').classList.add('fixed-top');
        // add padding top to show content behind navbar
        navbar_height = document.querySelector('.navbar').offsetHeight;
        document.body.style.paddingTop = navbar_height + 'px';
    } else if (window.scrollY < 60) {
        document.getElementById('navbar_top').classList.remove('fixed-top');
        // remove padding top from body
        document.body.style.paddingTop = '0';
    }
});