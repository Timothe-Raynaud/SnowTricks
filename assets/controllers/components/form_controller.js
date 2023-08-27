import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['submit', 'inputs', 'form']
    static values = {
        path: String,
        option: String,
        toastBox: {type: String, default: 'toast'},
        toastContainer: {type: String, default: 'toast-container'},
        toastContent: {type: String, default: 'toast-content'},
    }

    send(event) {
        this.submitTarget.disabled = true
        let toast = document.getElementById(this.toastBoxValue)
        let toastContainer = document.getElementById(this.toastContainerValue)
        let toastContent = document.getElementById(this.toastContentValue)

        const form = new FormData()
        const inputsArray = this.inputsTargets
        Array.prototype.forEach.call(inputsArray, function(inputArray) {
            form.append(inputArray.name, inputArray.value)
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
            .then(data => {
                if (this.optionValue === 'hideInputs'){
                    this.formTarget.remove()
                }
                console.log(data.type, data.message)

                toastContainer.classList.add(data.type)
                toast.classList.remove('d-none')
                toastContent.innerText = data.message

                setTimeout(() => {
                    this.dismiss();
                }, 5000);

                toast.addEventListener("mouseenter", this.handleMouseEnter.bind(this));
                toast.addEventListener("mouseleave", this.handleMouseLeave.bind(this));
            })
            .finally(() => {
                setTimeout(() => {
                    this.submitTarget.disabled = false
                }, 1000)
            })
    }

    handleMouseEnter() {
        clearTimeout(this.dismissTimeout);
    }

    handleMouseLeave() {
        this.dismissTimeout = setTimeout(() => {
            this.dismiss();
        }, 1000);
    }

    dismiss() {
        let toast = document.getElementById(this.toastBoxValue)

        if (!toast.matches(':hover')){
            toast.remove();
        }
    }
}
