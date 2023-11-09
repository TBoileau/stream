import {Controller} from '@hotwired/stimulus';
import tmi from 'tmi.js';

export default class extends Controller {
    connect() {
        const client = new tmi.Client({
            channels: ['toham']
        });

        client.connect();

        client.on('message', (channel, tags, message) => {
            this.element.innerHTML += `<article class="message">
                <span class="username">${tags['display-name']}</span>
                <p class="content">${message}</p>
            </article>`;
        });
    }
}
