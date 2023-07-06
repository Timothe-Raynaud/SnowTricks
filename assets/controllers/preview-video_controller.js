import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [ "embed" ]

    preview(event)
    {
        let input = event.currentTarget
        let urlValue = input.value
        let embed = this.embedTarget
        embed.setAttribute('src', urlValue)
    }

}
