import {Controller} from '@hotwired/stimulus';

const URL = '/event/create';

export default class extends Controller {
  static targets = ['title', 'date', 'city', 'error'];

  displayError(error) {
    this.errorTarget.textContent = `Error: ${error}`;
  }

  submit() {
    this.errorTarget.textContent = '';

    fetch(URL, {
      method: 'POST',
      body: JSON.stringify({
        title: this.titleTarget.value,
        date: this.dateTarget.value,
        city: this.cityTarget.value,
      }),
    }).then(r => r.json()).then(r => {
      if (r.error) {
        this.displayError(r.error);
        return;
      }

      // reload other controllers
      for (const identifier of ['events-list', 'ticket-creation']) {
        const controller = this.application.controllers.find(controller => controller.identifier == identifier);
        controller.disconnect();
        controller.connect();
      }

      // reset form
      this.titleTarget.value = null;
      this.dateTarget.value = null;
      this.cityTarget.value = null;
    });
  }
}
