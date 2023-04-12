import {Controller} from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['tricksContainer']
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
        if (this.displayRecurrenceValue === 3){
            const anchor = document.getElementById('tricks-arrow-anchor-up')
            anchor.classList.remove('d-none')
        }
    }

    generateTricksHtml() {
        let tricksHtml = ''
        const maxIterations = Math.min(this.displayRecurrenceValue, this.tricksByRowValue.length)

        tricksHtml += '<div class="row g-4 px-4 justify-content-center">'
        for (let i = 0; i < maxIterations; i++){

            for (const trick of this.tricksByRowValue[i]) {
                tricksHtml += `
                    <div class="card border-0 col-xl-2 col-lg-3 col-md-5 col-10 mx-2 p-0 tricks-card">
                        <h2 class="card-header bg-primary text-uppercase">${trick.name}</h2>
                        <div class="card-img-top">
                            ${trick.image ? 
                                `<img src="${this.assetPath(trick.image)}" alt="${trick.image.name}" height="220px" width="100%">` : 
                                `<img src="${this.assetPath('images/tricks/default.jpg')}" alt="default" height="220px" width="100%">
                            `}
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

    assetPath(path) {
        const assetHost = this.assetHost()
        if (!assetHost) {
            return path
        }
        return new URL(path, assetHost).toString()
    }

    assetHost() {
        const assetHostMeta = document.head.querySelector('meta[name="asset-host"]');
        return assetHostMeta ? assetHostMeta.getAttribute('content') || '' : '';
    }

}