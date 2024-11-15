// Import necessary CSS files
import './bootstrap.js';
import './styles/app.css';
import '@symfony/ux-live-component';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

import { Application } from 'stimulus';
import { definitionsFromContext } from 'stimulus/webpack-helpers';

import creditCardFormController from './controllers/collection-type_controller';
import cardNumberFormatterController from './controllers/credit-card-format_controller';
import expirationDateFormatterController from './controllers/expiration-date-format_controller';

// Start the Stimulus application
const application = Application.start();

// Load controllers dynamically
application.register('credit-card-form', creditCardFormController);
application.register('card-number-formatter', cardNumberFormatterController);
application.register('expiration-date-formatter', expirationDateFormatterController);
