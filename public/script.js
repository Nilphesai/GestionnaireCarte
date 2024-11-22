
const scrollToTopBtn = document.getElementById('scrollToTopBtn');

document.addEventListener("scroll", () => {
    //le premier pour safari, le second pour les autres navigateur
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollToTopBtn.classList.remove("hidden")
    } else {
        scrollToTopBtn.classList.add("hidden")
    }
})

scrollToTopBtn.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});