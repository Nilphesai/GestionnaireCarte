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
        this.form = element.querySelector('.js-filter-form')
        console.log('je me construit')
        this.bindEvents()
    }
    //ajoute les comportement au différents éléments
    bindEvents () {
        
        this.content.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', e => {
                e.preventDefault()
                this.loadUrl(button.getAttribute('href'))
            })
        })
        console.log('into bindevents')
    }
    async loadUrl (url){
        console.log(url)
        const response = await fetch(url, {
            headers : {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        if (response.status >= 200 && response.status < 300){
            const data = await response.json()
            this.content.innerHTML = data.content
            this.form.innerHTML = data.form

        }
        else{
            console.log('erreur')
            console.error(response)
        }
    }
}