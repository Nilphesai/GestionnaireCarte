
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

const panels = document.querySelectorAll('.openPanel');
const opens = document.querySelectorAll('.open');
  
panels.forEach(function (panel, index){
    panel.addEventListener("click", () => {
        if (opens[index].style.display == "none"){
            opens[index].style.display = "flex";
        }
        else{
            opens[index].style.display == "none";
        }
    })
});
//navbar
document.addEventListener('DOMContentLoaded', function () {
    var navbar = document.getElementById('navbar');
    var toggle = document.getElementById('nav-toggle');
        
    toggle.addEventListener('click', function () {
        navbar.classList.toggle('active');
    });
});
