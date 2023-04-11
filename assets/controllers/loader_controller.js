import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['loader', 'tricksContainer']
    static values = {
        displayRecurrence : {type : Number, default : 2},
        tricksByRow : Array,
    }

    connect() {
        fetch('/getTricks')
            .then(response => response.json())
            .then(data => {
                this.tricksByRowValue = data
                this.displayTricks()
            })
    }

    displayTricks(){
        this.tricksContainerTarget.innerHTML = this.generateTricksHtml()
    }

    loadMore(){
        this.displayRecurrenceValue = this.displayRecurrenceValue + 1
        this.displayTricks()
    }

    generateTricksHtml() {
        let tricksHtml = ''
        const maxIterations = Math.min(this.displayRecurrenceValue, this.tricksByRowValue.length)

        tricksHtml += '<div class="row g-4 px-4 justify-content-center">'
        for (let i = 0; i < maxIterations; i++){

            for (const trick of this.tricksByRowValue[i]) {
                tricksHtml += `
                    <div class="card shadow col-xl-2 col-lg-3 col-md-5 col-10 mx-2 p-0 tricks-card">
                        <h2 class="card-header text-white bg-primary ">${trick.name}</h2>
                        <div class="card-img-top">
                            ${trick.image ? `<img src="${trick.image}" alt="${trick.image.name}" class="img-fluid">` : ''}
                        </div>
                        <div class="card-body">
                            <p class="card-text">${trick.description}</p>
                        </div>
                        <div class="card-footer bg-secondary">
                            ${trick.type.name}
                        </div>
                    </div>
                `
            }


            if (maxIterations !== this.tricksByRowValue.length && i === (maxIterations - 1) ){
                tricksHtml += `
                    <div id="load-more" class="col-10 mt-4" >
                        <a href="javascript:void(0)" data-action="click->loader#loadMore">
                            <span>Afficher plus</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </a>
                    </div>
                `
            }
        }
        tricksHtml += '</div>'

        return tricksHtml
    }

}