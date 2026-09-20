/**
 * UI interactions — Burger menu, Mega menu, Loader
 * Driven by VanJS reactive state. Alpine.js remains only for promo-banner.
 */

import van from 'vanjs-core';

// ── Helpers ────────────────────────────────────────────────────────────────

/**
 * Whether the user asked the OS to reduce motion. Read at call time so it
 * tracks the setting if it changes during the session.
 *
 * @return {boolean}
 */
function prefersReducedMotion() {
	return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Move keyboard focus to an element that isn't normally focusable (a landmark,
 * a section wrapper…), adding a temporary `tabindex="-1"` if needed, so
 * screen-reader and keyboard users land there — not just the scroll position.
 *
 * @param {HTMLElement} target
 */
function moveFocusTo(target) {
	if (!target.hasAttribute('tabindex')) {
		target.setAttribute('tabindex', '-1');
	}
	target.focus({ preventScroll: true });
}

/**
 * Visible, focusable descendants of a container, in DOM order.
 *
 * @param {HTMLElement} container
 * @return {HTMLElement[]}
 */
function getFocusable(container) {
	const selector = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
	return [...container.querySelectorAll(selector)].filter(el => el.offsetParent !== null);
}

/**
 * Show an element with a CSS-transition-based enter animation.
 * The element must have `hidden` class to start hidden.
 * Transition classes are driven by data-enter-from / data-enter-to attributes
 * OR passed explicitly.
 *
 * @param {HTMLElement} el
 * @param {string} enterFrom  space-separated Tailwind classes for start state
 * @param {string} enterTo    space-separated Tailwind classes for end state
 * @param {number} duration   ms (must match CSS transition duration)
 */
function showEl(el, enterFrom = 'opacity-0 -translate-y-2', enterTo = 'opacity-100 translate-y-0') {
	el.classList.remove('hidden');
	// Apply start state instantly
	enterFrom.split(' ').forEach(c => el.classList.add(c));
	// Force reflow so the browser registers the start state
	void el.offsetHeight;
	// Transition to end state
	enterFrom.split(' ').forEach(c => el.classList.remove(c));
	enterTo.split(' ').forEach(c => el.classList.add(c));
}

/**
 * Hide an element with a CSS-transition-based leave animation.
 *
 * @param {HTMLElement} el
 * @param {string} leaveTo    space-separated Tailwind classes for end state
 * @param {string} leaveFrom  space-separated Tailwind classes for start state (currently active)
 * @param {number} duration   ms (must match CSS transition duration)
 */
function hideEl(el, leaveTo = 'opacity-0 -translate-y-2', leaveFrom = 'opacity-100 translate-y-0', duration = 150) {
	// Apply leave-end classes
	leaveFrom.split(' ').forEach(c => el.classList.remove(c));
	leaveTo.split(' ').forEach(c => el.classList.add(c));
	setTimeout(() => {
		el.classList.add('hidden');
		leaveTo.split(' ').forEach(c => el.classList.remove(c));
	}, duration);
}

// ── Burger / Mobile menu ───────────────────────────────────────────────────

/**
 * Controls the burger button and mobile menu panel.
 *
 * HTML hooks:
 *   header[data-burger]          — root scope (keyboard handler)
 *   button[data-burger-btn]      — toggle button
 *   span[data-bar="top"]         — top bar of burger icon
 *   span[data-bar="mid"]         — middle bar
 *   span[data-bar="bot"]         — bottom bar
 *   div[data-mobile-menu]        — collapsible panel
 */
function initBurgerMenu() {
	const header = document.querySelector('[data-burger]');
	const btn    = document.querySelector('[data-burger-btn]');
	const menu   = document.querySelector('[data-mobile-menu]');

	if (!header || !btn || !menu) return;

	const barTop = header.querySelector('[data-bar="top"]');
	const barMid = header.querySelector('[data-bar="mid"]');
	const barBot = header.querySelector('[data-bar="bot"]');

	const open = van.state(false);

	// Side effect: sync DOM to state
	van.derive(() => {
		const isOpen = open.val;

		// aria
		btn.setAttribute('aria-expanded', String(isOpen));

		// Burger icon animation
		if (barTop) {
			barTop.classList.toggle('rotate-45',    isOpen);
			barTop.classList.toggle('translate-y-0', isOpen);
			barTop.classList.toggle('-translate-y-2', !isOpen);
		}
		if (barMid) {
			barMid.classList.toggle('opacity-0',   isOpen);
			barMid.classList.toggle('opacity-100', !isOpen);
		}
		if (barBot) {
			barBot.classList.toggle('-rotate-45',   isOpen);
			barBot.classList.toggle('translate-y-0', isOpen);
			barBot.classList.toggle('translate-y-2', !isOpen);
		}

		// Mobile panel
		if (isOpen) {
			showEl(menu,
				'opacity-0 -translate-y-2',
				'opacity-100 translate-y-0'
			);
			// The panel visually overlays the page, so send focus into it.
			const first = getFocusable(menu)[0];
			if (first) first.focus();
		} else {
			if (!menu.classList.contains('hidden')) {
				hideEl(menu,
					'opacity-0 -translate-y-2',
					'opacity-100 translate-y-0',
					150
				);
				// Return focus to the toggle if it was left inside the panel.
				if (menu.contains(document.activeElement)) btn.focus();
			}
		}
	});

	// Toggle on click
	btn.addEventListener('click', () => { open.val = !open.val; });

	// Close on Escape
	document.addEventListener('keydown', e => {
		if (e.key === 'Escape' && open.val) open.val = false;
	});

	// Close on outside click
	document.addEventListener('click', e => {
		if (open.val && !header.contains(e.target)) open.val = false;
	});

	// While the panel is open it covers the page, so keep Tab focus cycling
	// between the toggle (still visible as an "X") and the panel's items.
	// Escape and the toggle still close it — this is not a WCAG 2.1.2 trap.
	header.addEventListener('keydown', e => {
		if (e.key !== 'Tab' || !open.val) return;

		const items = getFocusable(menu);
		if (!items.length) return;

		const cycle  = [btn, ...items];
		const first  = cycle[0];
		const last   = cycle[cycle.length - 1];
		const active = document.activeElement;

		if (e.shiftKey && active === first) {
			e.preventDefault();
			last.focus();
		} else if (!e.shiftKey && active === last) {
			e.preventDefault();
			first.focus();
		}
	});
}

// ── Mega / dropdown menu ───────────────────────────────────────────────────

/**
 * Controls each dropdown item in the desktop nav.
 *
 * HTML hooks (one set per top-level item with children):
 *   li[data-dropdown-item]       — container (scope)
 *   button[data-dropdown-btn]    — toggle button
 *   i[data-chevron]              — chevron icon (rotated when open)
 *   div[data-dropdown-panel]     — the flyout panel (hidden by default)
 */
function initMegaMenus() {
	document.querySelectorAll('[data-dropdown-item]').forEach(li => {
		const btn    = li.querySelector('[data-dropdown-btn]');
		const panel  = li.querySelector('[data-dropdown-panel]');

		if (!btn || !panel) return;

		const open = van.state(false);

		van.derive(() => {
			const isOpen = open.val;

			btn.setAttribute('aria-expanded', String(isOpen));

			// Query lazily so we get the <svg> replaced by Lucide, not the original <i>
			const chevron = li.querySelector('[data-chevron]');
			if (chevron) {
				chevron.classList.toggle('rotate-180', isOpen);
			}

			if (isOpen) {
				showEl(panel,
					'opacity-0 translate-y-1',
					'opacity-100 translate-y-0'
				);
			} else {
				if (!panel.classList.contains('hidden')) {
					hideEl(panel,
						'opacity-0 translate-y-1',
						'opacity-100 translate-y-0',
						150
					);
				}
			}
		});

		btn.addEventListener('click', () => { open.val = !open.val; });

		// Close on Escape from anywhere within the item (button or panel) and
		// send focus back to the trigger.
		li.addEventListener('keydown', e => {
			if (e.key === 'Escape' && open.val) {
				open.val = false;
				btn.focus();
			}
		});

		// Close when clicking outside this item
		document.addEventListener('click', e => {
			if (open.val && !li.contains(e.target)) open.val = false;
		});
	});
}

// ── Smooth anchors ─────────────────────────────────────────────────────────

/**
 * Smooth-scrolls to a target element on click.
 *
 * HTML hooks:
 *   a[href^="#"]             — standard anchor links (e.g. the skip link)
 */
function initSmoothAnchors() {
	document.querySelectorAll('a[href^="#"]').forEach(el => {
		el.addEventListener('click', e => {
			const selector = el.getAttribute('href');
			if (!selector || selector === '#') return;

			const target = document.querySelector(selector);
			if (!target) return;

			e.preventDefault();

			const reduce = prefersReducedMotion();
			target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth' });

			// preventDefault() cancels the browser's native "focus the fragment
			// target" step, so do it ourselves — this is what makes the skip
			// link actually work for keyboard / screen-reader users.
			moveFocusTo(target);
		});
	});
}

// ── Bootstrap ──────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
	initBurgerMenu();
	initMegaMenus();
	initSmoothAnchors();
});
