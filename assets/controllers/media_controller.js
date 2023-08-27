import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {

    select(event) {
        let activeElements = document.getElementsByClassName('media-item active')
        let activeBoxElements = document.getElementsByClassName('media-box active')
        Array.prototype.forEach.call(activeElements, function(activeElement) {
            activeElement.classList.remove('active')
        });
        Array.prototype.forEach.call(activeBoxElements, function(activeBoxElement) {
            activeBoxElement.classList.remove('active')
        });

        let id = event.params['id']
        let MediaToDisplay = document.getElementById('media-' + id)
        let boxToActive = document.getElementById('media-box-' + id)
        MediaToDisplay.classList.add('active')
        boxToActive.classList.add('active')
    }
}
