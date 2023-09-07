import {Controller} from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'boxToHide',
        "previewImage",
        "deleteButton",
    ]


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

    delete(){
        if (this.previewImageTarget){
            let previewImage = this.previewImageTarget
            previewImage.remove()
        }
    }
}
