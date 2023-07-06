import {Controller} from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        "formFilename",
        "preview",
        "box",
        "formInputLabel",
        "formInput"
    ]

    upload(event) {
        let displayBox = this.previewTarget
        let box = this.boxTarget
        let formFilename = this.formFilenameTarget
        let formInputLabel = this.formInputLabelTarget

        let input = event.currentTarget
        let file = input.files[0];
        let formData = new FormData();
        formData.append('image', file);

        fetch("/upload-image", {
            method: "POST",
            body: formData,
        }).then(response => {
            return response.json();
        }).then(data => {
            formInputLabel.classList.add('d-none')
            formFilename.value = data
            displayBox.setAttribute('src', './uploads/tmp/' + data)
            box.classList.remove('d-none')
        });
    }

    delete(event) {
        let box = this.boxTarget
        let formInputLabel = this.formInputLabelTarget
        let formInput = this.formInputTarget
        let formFilename = this.formFilenameTarget

        box.classList.add('d-none')
        formFilename.setAttribute('src', '')
        formInputLabel.classList.remove('d-none')
        formInput.value = ''
    }
}
