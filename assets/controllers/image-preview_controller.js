import {Controller} from "@hotwired/stimulus";


export default class extends Controller {
    static targets = [
        'box',
        'inputFilename',
        'boxToHide',
        'previewImage',
        'deleteButton',
    ]

    connect() {
        const inputFilename = this.inputFilenameTarget;
        let previewImage = this.previewImageTarget
        let deleteButton = this.deleteButtonTarget
        let boxToHide = this.boxToHideTarget

        if (inputFilename.value !== '') {
            previewImage.style.display = 'block';
            boxToHide.style.display = 'none';
            deleteButton.setAttribute('data-action', 'click->image-preview#delete')
        }
    }

    upload(event) {
        let previewImage = this.previewImageTarget
        let deleteButton = this.deleteButtonTarget
        let boxToHide = this.boxToHideTarget

        let input = event.currentTarget
        let file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.addEventListener('load', function () {
                previewImage.src = reader.result;
                previewImage.style.display = 'block';
                boxToHide.style.display = 'none';
                deleteButton.setAttribute('data-action', 'click->image-preview#delete')
            });

            reader.readAsDataURL(file);
        } else {
            previewImage.src = '#';
            previewImage.style.display = 'none';
        }
    }

    delete() {
        let box = this.boxTarget
        box.remove()
    }
}
