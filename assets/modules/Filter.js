/**
 * @property {HTMLElement} content
 * @property {HTMLElement} form
 * 
 */
export default class Filter {
    /**
     * 
     * @param {HTMLElement|null} element 
     * 
     */
    constructor (element) {
        if (element === null) {
            return
        }

        this.content = element.querySelector('.js-filter-content')
        this.form = element.querySelector('#card-search-filter')
        
        
        console.log(this.form)
        console.log(document.getElementById("openFormSearch"))

        this.bindEvents()
    }
    //ajoute les comportement au différents éléments
    bindEvents () {
        //déroulé le form SearchCard
        document.getElementById("openFormSearch").addEventListener("click", function() {
            if (document.getElementById("card-search-filter").style.display == "none"){
                document.getElementById("card-search-filter").style.display = "flex";
            }
            else{
                document.getElementById("card-search-filter").style.display = "none";
            }
          });


        // TODO : améliorer le comportement de l'évènement en chargeant 
        // les cartes en fonction de ce qui est écrit dans le textbox  

        document.getElementById("search_card_typecard").addEventListener('change', e => {
            console.log(e.target.value)
            if(e.target.value === "Spell Card" || e.target.value === "Trap Card"){
                document.getElementById("typeMonster").style.display = "none";
                document.getElementById("search_card_attribute").value = null;
                document.getElementById("search_card_level").value = null;
                document.getElementById("search_card_race").value = null;
                document.getElementById("search_card_att").value = null;
                document.getElementById("search_card_def").value = null;
                document.getElementById("search_card_link").value = null;
                document.getElementById("search_card_scale").value = null;

                const linkMark = document.querySelectorAll('.searchLinkMarkers')
                linkMark.forEach(element => {
                    if(element.checked){
                        element.checked = false;
                    }
                })
            }else{
                document.getElementById("typeMonster").style.display = "inline";
            }
        })

        this.form.addEventListener('submit', e => {    

                e.preventDefault()
                console.log(e.target.search_card_name.value)
                console.log(e.target.search_card_typecard.value)
                console.log(e.target.search_card_attribute.value)
                console.log(e.target.search_card_level.value)
                console.log(e.target.search_card_race.value)
                console.log(e.target.search_card_att.value)
                console.log(e.target.search_card_def.value)
                console.log(e.target.search_card_link.value)
                console.log(e.target.search_card_scale.value)
                const linkMark = e.target.querySelectorAll('.searchLinkMarkers')
                const listLinkMark = []
                linkMark.forEach(element => {
                    if(element.checked){
                        listLinkMark.push(element.defaultValue)
                    }
                });
                const listLinkMarkString = listLinkMark.join();
                console.log(listLinkMarkString)
                const searchedCard = [e.target.search_card_name.value,e.target.search_card_typecard.value,e.target.search_card_attribute.value,
                    e.target.search_card_level.value,e.target.search_card_race.value,
                    e.target.search_card_att.value,e.target.search_card_def.value,
                    e.target.search_card_link.value,e.target.search_card_scale.value,
                    listLinkMarkString]
                this.loadUrl(e.target.getAttribute('action'),searchedCard)
                
            })
        /*  appuis n'importe où sur le cardre d'une carte
        let isDragging = false;

        this.content.addEventListener('mousedown', function(event) {
            isDragging = false;
        });
            
        this.content.addEventListener('mousemove', function(event) {
            isDragging = true;
        });
            
        this.content.addEventListener('mouseup', function(event) {
            if (!isDragging) {
                // Ouvrir la page ici
                console.log('vers détail carte !');
                console.log(this.getAttribute);
                this.loadUrl(this.target.getAttribute('action'),searchedCard)
            }
        });
        */
        
        

        document.getElementById("pagingcard").addEventListener('submit', e => {
            e.preventDefault()
            const url = e.target.urlNextPage.value;
            console.log(url);
            
            this.loadUrl(e.target.getAttribute('action'),url)
        })
        
    }
    
    async loadUrl (url,searchedCard){
        console.log(url)
        console.log(searchedCard)
        const response = await fetch(url, {
            method: "POST",
            headers : {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ cardName: searchedCard })
        })

        if (response.status >= 200 && response.status < 300){
            
            const data = await response.json()
            console.log(data)
            const cards = Object.values(data)
            console.log(cards)
            this.content.innerHTML = cards

        }
        else{
            console.log('erreur')
            console.error(response)
        }
    }
}