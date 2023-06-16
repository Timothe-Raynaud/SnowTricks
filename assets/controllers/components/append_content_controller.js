import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static values = {
        path: String,
    }

    append(event) {
        const path = this.pathValue;
        let element = event.currentTarget;

        fetch(path)
            .then(response => response.json())
            .then(data => {
                element.append(data)
            })
    }

}