import {Controller} from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        path: String,
        param: String,
        target: String,
    }

    pushContent(content){
        let target = document.getElementById(this.targetValue)
        target.innerHTML = content
    }

    request() {
        let path = this.pathValue
        let param = this.paramValue

        fetch(path, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'pushModule=' + param,
        })
            .then(response => response.json())
            .then(data => {
                this.pushContent(data)
            });
    }
}