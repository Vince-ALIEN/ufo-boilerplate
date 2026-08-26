// if you're using a bundler, first import:
import Headroom from 'headroom.js';
// grab an element
var element = document.querySelector('#masthead');

// The pin/unpin background lives on the `nav`, not on `#masthead` itself:
// on the home page `#masthead`'s own box height is `0` (see
// `tailwind/custom/utilities.css`), and a `background-color` on a
// zero-height box has no area to paint, so it stays invisible there no
// matter the color/opacity used — the `nav` keeps its real height everywhere.
var nav = element ? element.querySelector('header') : null;

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
