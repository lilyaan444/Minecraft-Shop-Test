import './bootstrap.js';
import './styles/app.css';
import './styles/credit-card.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Démarrer Stimulus
import { startStimulusApp } from '@symfony/stimulus-bridge';
import { registerVueControllerComponents } from '@symfony/ux-vue';
import '@symfony/ux-live-component/dist/style.css';

import '@symfony/ux-live-component';
import { startStimulusApp } from '@symfony/stimulus-bridge';
import { LiveController } from '@symfony/ux-live-component';
startStimulusApp().register('live', LiveController);

import LiveController from '@symfony/ux-live-component';
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

app.register('live', LiveController);