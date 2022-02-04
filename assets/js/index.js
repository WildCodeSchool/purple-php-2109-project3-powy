const acc = document.getElementsByClassName('accordion-howto');
let i;
for (i = 0; i < acc.length; i += 1) {
    acc[i].addEventListener('click', () => {
        this.classList.toggle('active');
        const panel = this.nextElementSibling;
        if (panel.style.display === 'block') {
            panel.style.display = 'none';
        } else {
            panel.style.display = 'block';
        }
    });
}
