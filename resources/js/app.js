import './bootstrap';

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'jquery-ui/ui/widgets/slider';
import 'jquery-ui/themes/base/theme.css';
import 'jquery-ui/themes/base/slider.css';

import './sliders';

$(document).ready(function() {
    console.log('jQuery and jQuery UI are ready!');
});