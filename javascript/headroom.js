// if you're using a bundler, first import:
import Headroom from 'headroom.js';
// grab an element
var element = document.querySelector('#masthead');

// options
var options = {
	// vertical offset in px before element is first unpinned
	offset: {
		up: 100,
		down: 50,
	},
	tolerance: 5,
	// css classes to apply
	classes: {
		// when element is initialised
		initial: 'headroom', // classe initiale
		pinned: 'slideDown', // classe quand on scroll vers le haut
		unpinned: 'slideup', // classe quand on scroll vers le bas
	},

};
// construct an instance of Headroom, passing the element
var headroom = new Headroom(element, options);
// initialise
headroom.init();
