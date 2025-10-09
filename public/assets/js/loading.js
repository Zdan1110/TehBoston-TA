window.addEventListener("load", function () {
    const loader = document.getElementById("loading-screen");
    if (loader) loader.style.display = "none";
});

document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function (e) {
        const href = link.getAttribute('href');
        if (href && !href.startsWith('#') && !href.startsWith('javascript:') && !link.hasAttribute('target')) {
            const loader = document.getElementById('loading-screen');
            if (loader) loader.style.display = 'flex';
        }
    });
});
