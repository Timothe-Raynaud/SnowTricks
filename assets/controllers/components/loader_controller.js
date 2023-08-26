import {Controller} from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'loadButton',
        'container',
    ]

    static values = {
        path: String,
        elementHtml: Array,
        elementAnchor: String,
        firstLoad: { type : Number, default: 10},
        anchorTrigger: { type: Number, default: 15},
        elementsPerLoad: { type: Number, default: 5},
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

            console.log(this.elementAnchorValue)
        if (this.elementAnchorValue){
            if (this.anchorTriggerValue <= endIndex){
                let anchor = document.getElementById(this.elementAnchorValue)
                anchor.classList.remove('d-none')
            }
        }
    }
}