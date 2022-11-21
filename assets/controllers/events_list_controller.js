import {Controller} from '@hotwired/stimulus';

const URL = '/event/list';

export default class extends Controller {

  connect() {
    // load and display events list
    fetch(URL).then(r => r.json()).then(r => {
      this.element.innerHTML =
          `<ul class="events-list">${r.result.map(event => {
            const date = new Date(event.date);
            const formattedDate = `${date.getFullYear()}-${date.getMonth() + 1}--${date.getDate()}`;

            return `
              <li>
                <h3>${event.title}</h3>
                <p><strong>Date:</strong> ${formattedDate}</p>
                <p><strong>City:</strong> ${event.city}</p>
              </li>`;
          }).join('')}
          </ul>`;
    });
  }
}
