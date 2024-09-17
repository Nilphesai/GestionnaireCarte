

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import Filter from './modules/Filter.js'

document.addEventListener('DOMContentLoaded', function () {
 
    new Filter(document.querySelector('.js-filter'))

    })