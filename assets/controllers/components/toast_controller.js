import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static values = {
        type: String,
        message: String,
    }

    displayToast() {
        let toast = document.getElementById('toast')

        setTimeout(() => {
            this.dismiss(toast);
        }, 5000);

        toast.addEventListener("mouseenter", this.handleMouseEnter.bind(this));
        toast.addEventListener("mouseleave", this.handleMouseLeave.bind(this));
    }

    handleMouseEnter() {
        clearTimeout(this.dismissTimeout);
    }

    handleMouseLeave() {
        this.dismissTimeout = setTimeout(() => {
            this.dismiss();
        }, 1000);
    }

    dismiss(toast) {
        if (!toast.matches(':hover')){
            toast.remove();
        }
    }
}