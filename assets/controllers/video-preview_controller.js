import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [ "embed" ]

    preview(event)
    {
        let input = event.currentTarget
        let urlValue = input.value
        if (urlValue.startsWith('https://www.')){
            let embed = this.embedTarget
            embed.setAttribute('src', urlValue)
        }
    }
}
