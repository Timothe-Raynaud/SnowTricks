import {Controller} from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'loadButton',
        'container'
    ]

    static values = {
        path: String,
        elementHtml: Array,
        elementsPerLoad: { type: Number, default: 5},
        firstLoad: { type : Number, default: 10},
        currentElementsIndex: { type: Number, default: 0},
    }

    connect() {
        const endIndex = this.currentElementsIndexValue + this.firstLoadValue

        fetch(this.pathValue, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'loaderModule=1',
        })
            .then(response => response.json())
            .then(data => {
                this.elementHtmlValue = data
                this.loadMoreElements(endIndex)
            })
    }

    load(){
        const endIndex = this.currentElementsIndexValue + this.elementsPerLoadValue
        this.loadMoreElements(endIndex)
    }

    loadMoreElements(endIndex) {
        for (let i = this.currentElementsIndexValue; i < endIndex && i < this.elementHtmlValue.length; i++) {
            this.containerTarget.innerHTML += this.elementHtmlValue[i]
        }

        this.currentElementsIndexValue = endIndex

        if (this.currentElementsIndexValue >= this.elementHtmlValue.length) {
            this.loadButtonTarget.classList.add('d-none')
        }
    }
}