// Import necessary CSS files
import './bootstrap.js';
import './styles/app.css';
import '@symfony/ux-live-component';
import { Application } from '@hotwired/stimulus';
import CartController from './controllers/cart_controller';



console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

import { Application } from 'stimulus';


// Start the Stimulus application
const application = Application.start();
application.register('cart', CartController);

