import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["path"]

    moveTo(event) {
        event.preventDefault()

        let target = this.pathTarget

        if (target) {
            target.scrollIntoView({ behavior: "smooth" })
        }
    }
}
