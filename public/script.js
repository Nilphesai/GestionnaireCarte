
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

/*
        const piechart = document.getElementById('piechart');
        const nbSpell = document.getElementsById('stat-spell');
        const nbTrap = document.getElementsById('stat-trap');
        const nbMonster = document.getElementsById('stat-monster');



document.addEventListener("DOMContentLoaded", () =>{
    console.log("DOM fully loaded and parsed");
        if (nbSpell > 0){
            piechart.style.backgroundImage = "conic-gradient(green 70deg, lightblue 0 235deg,orange 0)";
        }
    })
*/