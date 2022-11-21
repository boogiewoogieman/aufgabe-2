import {Controller} from '@hotwired/stimulus';

const URL = '/ticket/create';

export default class extends Controller {
  static targets = ['firstName', 'lastName', 'eventId', 'error'];

  connect() {
    // fill select element with event ids
    fetch('/event/list').then(r => r.json()).then(r => {
      let selectHtml = '';
      for (const event of r.result) {
        selectHtml += `<option value="${event.id}">${event.title}</option>`;
      }
      this.eventIdTarget.innerHTML = selectHtml;
    });
  }

  displayError(error) {
    this.errorTarget.textContent = `Error: ${error}`;
  }

  submit() {
    this.errorTarget.textContent = '';

    fetch(URL, {
      method: 'POST',
      body: JSON.stringify({
        firstName: this.firstNameTarget.value,
        lastName: this.lastNameTarget.value,
        eventId: this.eventIdTarget.value,
      }),
    }).then(r => r.json()).then(r => {
      if (r.error) {
        this.displayError(r.error);
        return;
      }

      // reload other controllers
      for (const identifier of ['tickets-list']) {
        const controller = this.application.controllers.find(controller => controller.identifier == identifier);
        controller.disconnect();
        controller.connect();
      }

      // reset form
      this.firstNameTarget.value = null;
      this.lastNameTarget.value = null;
      this.eventIdTarget.value = null;
    });
  }
}
