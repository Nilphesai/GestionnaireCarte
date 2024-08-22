/**
 * @property {HTMLElement} content
 * @property {HTMLElement} form
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

        this.bindEvents()
    }
    //ajoute les comportement au différents éléments
    bindEvents () {
        // TODO : améliorer le comportement de l'évènement en chargeant 
        // les cartes en fonction de ce qui est écrit dans le textbox  
        this.form.addEventListener('submit', e => {
                
                
                
                e.preventDefault()
                console.log(e.target.card_name.value)
                this.loadUrl(e.target.getAttribute('action'),e.target.card_name.value)
                
            })
       
    }
    async loadUrl (url,searchedCard){
        console.log(url)
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