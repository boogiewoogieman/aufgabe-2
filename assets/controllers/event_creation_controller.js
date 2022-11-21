import {Controller} from '@hotwired/stimulus';

const EVENT_CREATION_URL = '/event/create';

export default class extends Controller {
  static targets = ['title', 'date', 'city', 'error'];

  displayError(error) {
    this.errorTarget.textContent = error;
  }

  submit() {
    this.errorTarget.textContent = '';

    fetch(EVENT_CREATION_URL, {
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

      // reload events list
      const eventsListController = this.application.controllers.find(
          controller => controller.identifier == 'events-list');
      eventsListController.disconnect();
      eventsListController.connect();

      // reset form
      this.titleTarget.value = null;
      this.dateTarget.value = null;
      this.cityTarget.value = null;
    });
  }
}
