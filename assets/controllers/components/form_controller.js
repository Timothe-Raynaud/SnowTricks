import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['submit', 'inputs']
    static values = {
        path: String,
    }

    send(event) {
        this.submitTarget.disabled = true

        const form = new FormData()
        const inputsArray = this.inputsTargets
        console.log(inputsArray)
        Array.prototype.forEach.call(inputsArray, function(inputArray) {
            form.append(inputArray.name, inputArray.values)
            console.log(inputArray.name, inputArray.values)
        });

        fetch(this.pathValue, {
            method: 'POST',
            body: form
        })
            .then(response => {
                if (response.ok) {
                    return response.json()
                } else {
                    throw new Error('Un problème est survenu')
                }
            })
            .then(data => {})
            .catch(error => console.error(error))
            .finally(() => {
                setTimeout(() => {
                    this.submitTarget.disabled = false
                }, 2000)
            })
    }
}
