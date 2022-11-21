import {Controller} from '@hotwired/stimulus';

const TICKETS_LIST_URL = '/ticket/list';

export default class extends Controller {

  connect() {
    // load and display tickets list
    fetch(TICKETS_LIST_URL).then(r => r.json()).then(r => {
      this.element.innerHTML =
          `<ul class="tickets-list">${r.result.map(ticket => {
            return `
              <li>
                <p><strong>First name:</strong> ${ticket.firstName}</p>
                <p><strong>Last name:</strong> ${ticket.lastName}</p>
                <p class="ticket--barcode">
                    <img class="ticket--barcode--image" src="data:image/png;base64,${ticket.barcodeImage}" alt="${ticket.barcodeString}"/>
                    <div class="ticket--barcode--string">${ticket.barcodeString}</div>
                </p>
              </li>`;
          }).join('')}
          </ul>`;
    });
  }
}
