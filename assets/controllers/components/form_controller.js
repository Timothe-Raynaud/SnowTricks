import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['submit', 'form']
    static values = {
        path: String,
        option: Object,
        toastBox: {type: String, default: 'toast'},
        toastContainer: {type: String, default: 'toast-container'},
        toastContent: {type: String, default: 'toast-content'},
    }

    send() {
        this.submitTarget.disabled = true
        let toast = document.getElementById(this.toastBoxValue)
        let toastContainer = document.getElementById(this.toastContainerValue)
        let toastContent = document.getElementById(this.toastContentValue)
        let id = ''

        const form = new FormData(this.formTarget)

        if ('id' in this.optionValue){
             id = '/' + this.optionValue.id
        }

        fetch(this.pathValue + id, {
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
                // Hide the input box if option is set
                if ('hideInputs' in this.optionValue && data.type ==='success'){
                    this.formTarget.remove()
                }

                if ('redirectUrl' in this.optionValue && data.type === 'success'){
                    data.message = data.message + "<br> Vous allez être rediriger vers la page d'acceuil."
                    setTimeout(() => {
                        window.location.href = this.optionValue.redirectUrl
                    }, 3000)
                }

                toastContainer.className = data.type
                toast.classList.remove('d-none')
                toastContent.innerHTML = data.message

                setTimeout(() => {
                    this.dismiss();
                }, 4000);

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
            toast.classList.add('d-none');
        }
    }
}
