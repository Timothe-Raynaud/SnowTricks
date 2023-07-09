import {Controller} from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['tricksContainer', 'buttonDisplayMore']
    static values = {
        displayRecurrence: {type: Number, default: 2},
        tricksByRow: Array,
        maxIteration: Number,
    }

    connect() {
        fetch('/get_tricks')
            .then(response => response.json())
            .then(data => {
                this.tricksByRowValue = data
                this.maxIterationValue = Math.min(this.displayRecurrenceValue, this.tricksByRowValue.length)
                this.tricksContainerTarget.innerHTML = this.generateTricksHtml()
                this.getDisplayMore()
            })
    }

    // Generate the base of
    generateTricksHtml() {
        let tricksHtml = ''

        for (let i = 0; i < this.maxIterationValue; i++) {
            tricksHtml += this.getTricksCardHtml(i)
        }

        return tricksHtml
    }

    // Load one more row
    loadMore() {
        this.displayRecurrenceValue = this.displayRecurrenceValue + 1
        this.maxIterationValue = Math.min(this.displayRecurrenceValue, this.tricksByRowValue.length)
        this.tricksContainerTarget.innerHTML += this.getTricksCardHtml(this.displayRecurrenceValue - 1)
        this.getDisplayMore()
        this.displayUpAnchor()
    }

    // Set html for displaying card tricks
    getTricksCardHtml(i) {
        let tricksHtml = ''

        for (const trick of this.tricksByRowValue[i]) {
            tricksHtml += `
                <a class="card border-0 col-xl-2 col-lg-3 col-md-5 col-10 mx-2 p-0 tricks-card" href="{{ path('home') }}">
                    <h2 class="card-header bg-primary text-uppercase">${trick.name}</h2>
                    <div class="card-img-top">
                        ${trick.image ?
                `<img src="/uploads/images/tricks/${trick.image}" alt="${trick.image}" height="220px" width="100%">` :
                `<img src="/uploads/images/tricks/default.jpg" alt="default" height="220px" width="100%">
                        `}
                    </div>
                    <div class="card-body">
                        <p class="card-text">${trick.description}</p>
                    </div>
                    <div class="card-footer bg-secondary">
                        ${trick.type}
                    </div>
                </a>
            `
        }

        return tricksHtml
    }

    // Display the button for load more
    getDisplayMore() {
        if (this.maxIterationValue !== this.tricksByRowValue.length) {
            let tricksHtml = ''
            tricksHtml += `
                <a href="javascript:void(0)" data-action="click->loader#loadMore">
                    <span>Afficher plus</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </a>     
            `
            this.buttonDisplayMoreTarget.innerHTML = tricksHtml
        } else {
            this.buttonDisplayMoreTarget.innerHTML = '<div class="mb-5"></div>'
        }
    }

    // Display anchor when 3 row is display
    displayUpAnchor() {
        if (this.displayRecurrenceValue === 3) {
            const anchor = document.getElementById('tricks-arrow-anchor-up')
            anchor.classList.remove('d-none')
        }
    }

}