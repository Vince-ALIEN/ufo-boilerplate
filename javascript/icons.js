/**
 * Lucide Icons initialization
 *
 * This module handles the initialization of Lucide Icons throughout the theme.
 * Icons are replaced dynamically using data attributes.
 *
 * @package ufo-agency-boilerplate
 */

import {
	createIcons,
	Airplay,
	Archive,
	ArrowLeft,
	ArrowRight,
	BarChart3,
	Bell,
	BookOpen,
	Briefcase,
	Calendar,
	Check,
	ChessKing,
	ChevronDown,
	ChevronLeft,
	ChevronRight,
	Code,
	Command,
	Crown,
	ExternalLink,
	Folder,
	Globe,
	Grid3x3,
	Key,
	Languages,
	Menu,
	MessageCircle,
	Monitor,
	Moon,
	PackageOpen,
	Phone,
	PlayCircle,
	RefreshCw,
	Rocket,
	Search,
	Server,
	Send,
	Shield,
	ShoppingCart,
	SportShoe,
	Sun,
	SunMoon,
	User,
	UserRoundPlus,
	X,
	Zap,
} from 'lucide';

// Icon library for the theme
const iconLibrary = {
	Airplay,
	Archive,
	ArrowLeft,
	ArrowRight,
	BarChart3,
	Bell,
	BookOpen,
	Briefcase,
	Calendar,
	Check,
	ChessKing,
	ChevronDown,
	ChevronLeft,
	ChevronRight,
	Code,
	Command,
	Crown,
	ExternalLink,
	Folder,
	Globe,
	Grid3x3,
	Key,
	Languages,
	Menu,
	MessageCircle,
	Monitor,
	Moon,
	PackageOpen,
	Phone,
	PlayCircle,
	RefreshCw,
	Rocket,
	Search,
	Server,
	Send,
	Shield,
	ShoppingCart,
	SportShoe,
	Sun,
	SunMoon,
	User,
	UserRoundPlus,
	X,
	Zap,
};

/**
 * Initialize all Lucide icons in the document
 */
export function initIcons() {
	createIcons({
		icons: iconLibrary,
		attrs: {
			class: 'lucide-icon',
			'stroke-width': 1.5,
		},
		nameAttr: 'data-lucide',
	});
}

/**
 * Re-initialize icons after dynamic content is added
 * Useful for AJAX-loaded content
 *
 * @param {HTMLElement} container - The container element to search for icons
 */
export function refreshIcons(_container = document.body) {
	createIcons({
		icons: iconLibrary,
		attrs: {
			class: 'lucide-icon',
			'stroke-width': 1.5,
		},
		nameAttr: 'data-lucide',
	});
}

/**
 * Initialize icons when DOM is ready
 */
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initIcons);
} else {
	initIcons();
}
