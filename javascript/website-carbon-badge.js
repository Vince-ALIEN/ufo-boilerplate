/*!
 * Website Carbon badge — vendored copy of website-carbon-badges@1.1.3
 * Source: https://unpkg.com/website-carbon-badges@1.1.3/b.min.js
 * License: MIT (https://github.com/wcb-io/wcb)
 *
 * Self-hosted rather than loaded from a CDN. Renders into the `#wcb` element
 * in the footer and calls https://api.websitecarbon.com at runtime.
 *
 * Three changes on top of the upstream file:
 *   1. The upstream b.min.js body is wrapped in `wcbRender()` instead of
 *      running at parse time.
 *   2. A bootstrap runs it only once `#wcb` is about to scroll into view
 *      (IntersectionObserver). This keeps the third-party
 *      api.websitecarbon.com request — whose latency is highly variable —
 *      out of the initial page load, so it never competes with
 *      above-the-fold work and stops adding run-to-run noise to Lighthouse
 *      / PageSpeed scores. `#wcb` carries a reserved `min-height` in
 *      `tailwind/custom/utilities.css` so the injected markup causes no CLS.
 *   3. Once rendered, it dispatches `ufo:content-added` so ui.js's
 *      initExternalLinks() marks the badge's "Website Carbon" link as
 *      external / opening a new tab — the badge is inserted after that
 *      script's own pass on DOMContentLoaded, so it would miss it otherwise.
 *
 * This file is excluded from eslint/prettier (see `.prettierignore`,
 * `eslint.config.js`) so the upstream body stays byte-for-byte verbatim.
 * To update: replace the single `const …` line inside `wcbRender()` with a
 * newer upstream b.min.js and bump the version in
 * `theme/inc/enqueue-scripts.php`.
 */

function wcbRender() {
	// --- begin upstream website-carbon-badges@1.1.3 b.min.js ---
	const wcID=e=>document.getElementById(e),wcU=encodeURIComponent(window.location.href),newRequest=function(e=!0){fetch("https://api.websitecarbon.com/b?url="+wcU).then((function(e){if(!e.ok)throw Error(e);return e.json()})).then((function(n){e&&renderResult(n),n.t=(new Date).getTime(),localStorage.setItem("wcb_"+wcU,JSON.stringify(n))})).catch((function(e){wcID("wcb_g").innerHTML="No Result",console.log(e),localStorage.removeItem("wcb_"+wcU)}))},renderResult=function(e){wcID("wcb_g").innerHTML=e.c+"g of CO<sub>2</sub>/view",wcID("wcb_2").insertAdjacentHTML("beforeEnd","Cleaner than "+e.p+"% of pages tested")},wcC="<style>#wcb.carbonbadge{--b1:#0e11a8;--b2:#00ffbc;font-size:15px;text-align:center;color:var(--b1);line-height:1.15}#wcb.carbonbadge sub{vertical-align:middle;position:relative;top:.3em;font-size:.7em}#wcb #wcb_2,#wcb #wcb_a,#wcb #wcb_g{display:inline-flex;justify-content:center;align-items:center;text-align:center;font-size:1em;line-height:1.15;font-family:-apple-system,BlinkMacSystemFont,sans-serif;text-decoration:none;margin:.2em 0}#wcb #wcb_a,#wcb #wcb_g{padding:.3em .5em;border:.13em solid var(--b2)}#wcb #wcb_g{border-radius:.3em 0 0 .3em;background:#fff;border-right:0;min-width:8.2em}#wcb #wcb_a{border-radius:0 .3em .3em 0;border-left:0;background:var(--b1);color:#fff;font-weight:700;border-color:var(--b1)}#wcb.wcb-d #wcb_a{color:var(--b1);background:var(--b2);border-color:var(--b2)}#wcb.wcb-d #wcb_2{color:#fff}</style>",wcB=wcID("wcb");if("fetch"in window){wcB.insertAdjacentHTML("beforeEnd",wcC),wcB.insertAdjacentHTML("beforeEnd",'<div id="wcb_p"><span id="wcb_g">Measuring CO<sub>2</sub>&hellip;</span><a id="wcb_a" target="_blank" rel="noopener" href="https://websitecarbon.com">Website Carbon</a></div><span id="wcb_2">&nbsp;</span>');let e=localStorage.getItem("wcb_"+wcU);const n=(new Date).getTime();if(e){const t=JSON.parse(e);renderResult(t),n-t.t>864e5&&newRequest(!1)}else newRequest()}
	// --- end upstream ---
}

(function () {
	var el = document.getElementById("wcb");

	if (!el) {
		return;
	}

	function render() {
		wcbRender();
		document.dispatchEvent(new CustomEvent("ufo:content-added", { detail: { root: el } }));
	}

	// No IntersectionObserver support: render immediately, as upstream does.
	if (!("IntersectionObserver" in window)) {
		render();
		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			for (var i = 0; i < entries.length; i++) {
				if (entries[i].isIntersecting) {
					observer.disconnect();
					render();
					return;
				}
			}
		},
		{ rootMargin: "200px" }
	);

	observer.observe(el);
})();
