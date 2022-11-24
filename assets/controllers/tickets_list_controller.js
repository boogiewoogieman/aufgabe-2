import {Controller} from '@hotwired/stimulus';

const URL = '/ticket/list';

export default class extends Controller {

  connect() {
    // load and display tickets list
    fetch(URL).then(r => r.json()).then(r => {
      if(!r.result.length) {
        this.element.innerHTML = 'No tickets yet';
        return;
      }

      this.element.innerHTML =
          `<ul class="tickets-list">${r.result.map(ticket => {
            return `
              <li>
                <p><strong>Event:</strong> ${ticket.event.title} (${ticket.event.date}), ${ticket.event.city}</p>
                <p><strong>First name:</strong> ${ticket.firstName}</p>
                <p><strong>Last name:</strong> ${ticket.lastName}</p>
                <div class="ticket--barcode">
                    <img class="ticket--barcode--image" src="data:image/png;base64,${ticket.barcodeImage}" alt="${ticket.barcodeString}"/>
                    <div class="ticket--barcode--string">${ticket.barcodeString}</div>
                </p>
              </li>`;
          }).join('')}
          </ul>`;
    });
  }
}
