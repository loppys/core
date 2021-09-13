const sideToggle = document.querySelector('.js-side-toggle');
const side = document.querySelector('.js-side');
const mainContent = document.querySelector('.js-main');

sideToggle.addEventListener('click', () => {
    side.classList.toggle('minify');
    mainContent.classList.toggle('wide');
})