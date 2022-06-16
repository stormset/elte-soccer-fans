const indicator = document.querySelector('.selection-indicator');
const items = document.querySelectorAll('.selection-item');

function handleIndicator(el) {
    items.forEach(item => {
        item.classList.remove('is-active');
        item.removeAttribute('style');
    });

    indicator.style.width = `${el.offsetWidth / 2}px`;
    indicator.style.left = `${el.offsetLeft + el.offsetWidth / 4}px`;
    el.classList.add('is-active');
}

window.onload = function () {
    items.forEach((item) => {
        item.addEventListener('click', (e) => {
            handleIndicator(e.target);
        });
        item.classList.contains('is-active') && handleIndicator(item);
    });
};
