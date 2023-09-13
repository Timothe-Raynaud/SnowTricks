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
        recurrence: {type: Number, default: 0},
        lastIndex: {type: Number, default: 0},
        initialLoad: {type: Number, default: 10},
        anchorTrigger: {type: Number, default: 2},
        elementsPerLoad: {type: Number, default: 5},
    }

    connect() {
        let bodyContent = JSON.stringify({
            'elementNumber': (this.initialLoadValue + 1),
            'startingId': null,
        })

        this.request(bodyContent)
    }

    loadMore() {
        let bodyContent = JSON.stringify({
            'elementNumber': (this.elementsPerLoadValue + 1),
            'startingId': this.lastIndexValue,
        })

        this.request(bodyContent)
    }

    loadMoreElements() {
        // Load all element if there is no more element, else load all element less one
        let elementPerLoad = this.recurrenceValue === 1 ? this.initialLoadValue : this.elementsPerLoadValue
        console.log(elementPerLoad, this.elementHtmlValue.length)
        for (let i = 0; (this.elementHtmlValue.length < elementPerLoad ? i : (i + 1)) < this.elementHtmlValue.length; i++) {
            this.containerTarget.innerHTML += this.elementHtmlValue[i]
        }

        // If all is load, remove load button
        if ((this.elementHtmlValue.length <= this.initialLoadValue && this.recurrenceValue === 1)
            || (this.elementHtmlValue.length <= this.elementsPerLoadValue && this.recurrenceValue > 1)) {
            this.loadButtonTarget.classList.add('d-none')
        }

        //
        if (this.elementAnchorValue) {
            if (this.anchorTriggerValue === this.recurrenceValue) {
                let anchor = document.getElementById(this.elementAnchorValue)
                anchor.classList.remove('d-none')
            }
        }
    }

    request(bodyContent) {
        fetch(this.pathValue, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: bodyContent,
        })
            .then(response => response.json())
            .then(data => {
                this.elementHtmlValue = data.html
                this.lastIndexValue = data.lastIndex
                this.recurrenceValue = this.recurrenceValue + 1
                this.loadMoreElements()
            })
    }
}