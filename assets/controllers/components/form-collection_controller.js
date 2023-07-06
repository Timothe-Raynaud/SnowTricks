import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        "collectionContainer",
        "addButton"
    ]

    static values = {
        index    : Number,
        prototype: String,
        maxIteration: {type :Number, default: 5},
    }

    addCollectionElement(event)
    {
        if (this.indexValue > this.maxIterationValue){
            return
        }

        const item = document.createElement('li')
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue)
        this.collectionContainerTarget.appendChild(item)
        this.indexValue++

        if (this.indexValue === this.maxIterationValue){
            this.addButtonTarget.remove()
        }
    }

}
