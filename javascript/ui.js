/**
 * UI interactions — Burger menu, Mega menu, Loader
 * Driven by VanJS reactive state. Alpine.js remains only for promo-banner.
 */

import van from 'vanjs-core';

// ── Helpers ────────────────────────────────────────────────────────────────

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
		} else {
			if (!menu.classList.contains('hidden')) {
				hideEl(menu,
					'opacity-0 -translate-y-2',
					'opacity-100 translate-y-0',
					150
				);
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

		// Close on Escape
		btn.addEventListener('keydown', e => {
			if (e.key === 'Escape') open.val = false;
		});

		// Close when clicking outside this item
		document.addEventListener('click', e => {
			if (open.val && !li.contains(e.target)) open.val = false;
		});
	});
}

// ── Page loader ────────────────────────────────────────────────────────────

/**
 * Drives the preloader progress bar and hides it when the page is ready.
 *
 * HTML hooks:
 *   div#preloader       — overlay (hidden by removing it from DOM / display)
 *   div#progress-bar    — the fill bar (width animated via JS)
 */
function initLoader() {
	const preloader   = document.getElementById('preloader');
	const progressBar = document.getElementById('progress-bar');

	if (!preloader) return;

	const loading  = van.state(true);
	const progress = van.state(0);

	// Animate progress bar width
	van.derive(() => {
		if (progressBar) progressBar.style.width = progress.val + '%';
	});

	// Hide preloader when loading becomes false
	van.derive(() => {
		if (!loading.val) {
			preloader.style.transition = 'opacity 0.3s ease';
			preloader.style.opacity    = '0';
			setTimeout(() => { preloader.style.display = 'none'; }, 300);
		}
	});

	// Increment progress counter (20 ms steps → ~2 s to reach 100)
	function incrementProgress() {
		if (progress.val < 100) {
			progress.val += 1;
			setTimeout(incrementProgress, 20);
		}
	}
	incrementProgress();

	// Determine when to hide
	const startTime   = Date.now();
	const minLoadTime = 1000; // 1 s minimum display
	const maxLoadTime = 3000; // 3 s hard limit

	function hideLoader() {
		const elapsed       = Date.now() - startTime;
		const remainingTime = Math.max(0, minLoadTime - elapsed);
		setTimeout(() => { loading.val = false; }, remainingTime);
	}

	document.addEventListener('DOMContentLoaded', hideLoader);
	window.addEventListener('load', hideLoader);
	setTimeout(() => { loading.val = false; }, maxLoadTime);
}

// ── Promo popup ────────────────────────────────────────────────────────────

/**
 * Drives the promo popup overlay (optional, only rendered when theme mod 'autorise' is on).
 *
 * HTML hooks:
 *   div#promo-overlay   data-cookie-name, data-delay, data-cookie-duration
 *   div#promo-modal
 *   button#promo-close-btn
 *   a#promo-cta-link    (optional)
 */
function initPromoPopup() {
	const overlay = document.getElementById('promo-overlay');
	const modal   = document.getElementById('promo-modal');

	if (!overlay || !modal) return;

	const cookieName     = overlay.dataset.cookieName     || 'promo_popup_cookie';
	const delay          = parseInt(overlay.dataset.delay          || '2000', 10);
	const cookieDuration = parseInt(overlay.dataset.cookieDuration || '30',   10);

	const showPopup = van.state(false);

	// Sync DOM to state
	van.derive(() => {
		const isVisible = showPopup.val;

		if (isVisible) {
			overlay.classList.remove('hidden');
			void overlay.offsetHeight; // force reflow
			overlay.classList.remove('opacity-0');
			overlay.classList.add('opacity-100');
			modal.classList.remove('opacity-0', 'scale-95');
			modal.classList.add('opacity-100', 'scale-100');
		} else {
			overlay.classList.remove('opacity-100');
			overlay.classList.add('opacity-0');
			modal.classList.remove('opacity-100', 'scale-100');
			modal.classList.add('opacity-0', 'scale-95');
			setTimeout(() => overlay.classList.add('hidden'), 300);
		}
	});

	// Initialize: check cookie
	const cookieManager = window.SecureCookieManager ? new window.SecureCookieManager() : null;
	const cookieExists  = cookieManager && cookieManager.getCookie(cookieName) === 'true';

	if (!cookieExists) {
		setTimeout(() => { showPopup.val = true; }, delay);
	}

	// Close helper
	function closePopup() {
		showPopup.val = false;
		if (cookieManager) {
			cookieManager.setCookie(cookieName, 'true', cookieDuration, {
				secure:   window.location.protocol === 'https:',
				sameSite: window.location.protocol === 'https:' ? 'Strict' : 'Lax',
			});
		}
	}

	// Close button
	const closeBtn = document.getElementById('promo-close-btn');
	if (closeBtn) closeBtn.addEventListener('click', closePopup);

	// Optional CTA link
	const ctaLink = document.getElementById('promo-cta-link');
	if (ctaLink) ctaLink.addEventListener('click', closePopup);

	// Click outside modal closes popup
	overlay.addEventListener('click', e => {
		if (!modal.contains(e.target)) closePopup();
	});
}

// ── Smooth anchors ─────────────────────────────────────────────────────────

/**
 * Smooth-scrolls to a target element on click.
 * Uses Lenis (window.lenis) if available, otherwise native scrollIntoView.
 *
 * HTML hooks:
 *   [data-scroll-to="#id"]   — any clickable element
 *   a[href^="#"]             — standard anchor links
 */
function initSmoothAnchors() {
	document.querySelectorAll('[data-scroll-to], a[href^="#"]').forEach(el => {
		el.addEventListener('click', e => {
			const selector = el.dataset.scrollTo || el.getAttribute('href');
			if (!selector || selector === '#') return;

			const target = document.querySelector(selector);
			if (!target) return;

			e.preventDefault();

			if (window.lenis) {
				window.lenis.scrollTo(target, { duration: 2, easing: t => Math.min(1, 1.001 - Math.pow(2, -10 * t)) });
			} else {
				target.scrollIntoView({ behavior: 'smooth' });
			}
		});
	});
}

// ── Bootstrap ──────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
	initBurgerMenu();
	initMegaMenus();
	initLoader();
	initPromoPopup();
	initSmoothAnchors();
});
