import { Controller } from '@hotwired/stimulus';
import { toast } from '../../js/components/toast.js';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['submit', 'form']
    static values = {
        path: String,
        option: Object,
    }

    send() {
        this.submitTarget.disabled = true

        const form = new FormData(this.formTarget)

        fetch(this.pathValue, {
            method: 'POST',
            body: form
        })
            .then(response => {
                if (response.ok) {
                    return response.json()
                } else {
                    toast('error', 'Une erreur est survenue.')
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

                setTimeout(() => {
                    this.dismiss();
                }, 4000);

                if (data.type === 'error'){
                    setTimeout(() => {
                        this.submitTarget.disabled = false
                    }, 1000)
                }

                toast(data.type, data.message)
            })
    }
}
