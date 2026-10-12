/* global filterPosts */
(function () {
	'use strict';

	function parseSafeHTML(htmlString) {
		var parser = new DOMParser();
		var doc = parser.parseFromString(htmlString, 'text/html');
		var frag = document.createDocumentFragment();
		Array.from(doc.body.childNodes).forEach(function (node) {
			frag.appendChild(node.cloneNode(true));
		});
		return frag;
	}


	function AjaxFilter() {
		this.container = document.querySelector('.js-container-async');
		this.status = document.querySelector('.ajax-posts__status');

		if (this.container) {
			this.init();
		}
	}

	AjaxFilter.prototype = {
		init: function () {
			this.bindEvents();
			this.container.classList.add('js-enabled');
			this.updateFiltersState();
			this.applyActiveStyles();
		},

		// Helper pour récupérer les éléments dynamiquement
		getFilters: function () {
			return document.querySelectorAll('.ajax-posts__filter');
		},

		getLoadMoreButtons: function () {
			return document.querySelectorAll('.js-load-more');
		},

		getShowAllButtons: function () {
			return document.querySelectorAll('.js-show-all-filters');
		},

		getMobileMenus: function () {
			return document.querySelectorAll('.ajax-posts__mobile-menu');
		},

		bindEvents: function () {
			var self = this;

			// Délégation d'événements sur le document
			document.addEventListener('click', function (e) {
				var target = e.target.closest(
					'.ajax-posts__filter:not(.js-show-all-filters)'
				);
				if (target) {
					self.handleFilterClick(e, target);
					return;
				}

				target = e.target.closest('.js-show-all-filters');
				if (target) {
					self.handleShowAllClick(e, target);
					return;
				}

				target = e.target.closest('.js-load-more');
				if (target) {
					self.handleLoadMore(e, target);
					return;
				}

				target = e.target.closest('.js-reset-filters');
				if (target) {
					self.handleReset(e);
					return;
				}

				target = e.target.closest('.js-toggle-filters');
				if (target) {
					self.toggleMobileFilters(e, target);
					return;
				}
			});
		},

		// Gestion du clic sur "Toutes les catégories"
		handleShowAllClick: function (e, showAllButton) {
			e.preventDefault();

			// Retirer la classe active de tous les filtres de la même catégorie
			var taxonomy = showAllButton.dataset.filter;
			document
				.querySelectorAll(
					'.ajax-posts__filter[data-filter="' + taxonomy + '"]'
				)
				.forEach(function (el) {
					el.classList.remove('active');
					el.setAttribute('aria-pressed', 'false');
				});

			// Ajouter la classe active au bouton "Toutes les catégories"
			showAllButton.classList.add('active');
			showAllButton.setAttribute('aria-pressed', 'true');

			// Mettre à jour les articles
			this.updatePosts();

			// Appliquer les styles actifs
			this.applyActiveStyles();
		},

		handleFilterClick: function (e, filter) {
			e.preventDefault();
			var self = this;
			var taxonomy = filter.dataset.filter;

			// Pour garantir que multiselect est à false
			document
				.querySelectorAll(
					'.ajax-posts__filter[data-filter="' + taxonomy + '"]'
				)
				.forEach(function (el) {
					el.classList.remove('active');
					el.setAttribute('aria-pressed', 'false');
				});
			filter.classList.add('active');
			filter.setAttribute('aria-pressed', 'true');

			// Désactiver le bouton "Toutes les catégories" de cette taxonomie
			document
				.querySelectorAll(
					'.js-show-all-filters[data-filter="' + taxonomy + '"]'
				)
				.forEach(function (el) {
					el.classList.remove('active');
					el.setAttribute('aria-pressed', 'false');
				});

			this.updatePosts();

			// Appliquer les styles actifs
			this.applyActiveStyles();

			// Fermer le menu mobile après la sélection
			if (window.innerWidth < 768) {
				this.getMobileMenus().forEach(function (menu) {
					self.slideUp(menu);
				});
				// Mettre à jour aria-expanded
				document
					.querySelectorAll('.js-toggle-filters')
					.forEach(function (el) {
						el.setAttribute('aria-expanded', 'false');
					});
			}
		},

		handleLoadMore: function (e, button) {
			e.preventDefault();
			var page = parseInt(button.dataset.page, 10);
			this.loadMorePosts(page);
		},

		handleReset: function (e) {
			e.preventDefault();
			this.getFilters().forEach(function (filter) {
				if (!filter.classList.contains('js-show-all-filters')) {
					filter.classList.remove('active');
					filter.setAttribute('aria-pressed', 'false');
				}
			});
			this.getShowAllButtons().forEach(function (btn) {
				btn.classList.add('active');
				btn.setAttribute('aria-pressed', 'true');
			});
			this.updatePosts();
			this.applyActiveStyles();
		},

		toggleMobileFilters: function (e, toggle) {
			e.preventDefault();
			var menu = toggle.nextElementSibling;
			if (!menu || !menu.classList.contains('ajax-posts__mobile-menu')) {
				return;
			}
			var isExpanded = toggle.getAttribute('aria-expanded') === 'true';
			var self = this;

			// Fermer tous les autres menus
			this.getMobileMenus().forEach(function (m) {
				if (m !== menu) {
					self.slideUp(m);
				}
			});
			document
				.querySelectorAll('.js-toggle-filters')
				.forEach(function (t) {
					if (t !== toggle) {
						t.setAttribute('aria-expanded', 'false');
					}
				});

			// Basculer le menu actuel
			if (isExpanded) {
				this.slideUp(menu);
			} else {
				this.slideDown(menu);
			}
			toggle.setAttribute('aria-expanded', String(!isExpanded));

			// Rotation de l'icône (si ajoutée)
			var svg = toggle.querySelector('svg');
			if (svg) {
				svg.classList.toggle('rotate-180');
			}
		},

		// Animation slideUp en vanilla JS
		slideUp: function (element, duration) {
			duration = duration || 200;
			element.style.transitionProperty = 'height, margin, padding';
			element.style.transitionDuration = duration + 'ms';
			element.style.boxSizing = 'border-box';
			element.style.height = element.offsetHeight + 'px';
			element.offsetHeight; // Force reflow
			element.style.overflow = 'hidden';
			element.style.height = '0';
			element.style.paddingTop = '0';
			element.style.paddingBottom = '0';
			element.style.marginTop = '0';
			element.style.marginBottom = '0';

			setTimeout(function () {
				element.style.display = 'none';
				element.style.removeProperty('height');
				element.style.removeProperty('padding-top');
				element.style.removeProperty('padding-bottom');
				element.style.removeProperty('margin-top');
				element.style.removeProperty('margin-bottom');
				element.style.removeProperty('overflow');
				element.style.removeProperty('transition-duration');
				element.style.removeProperty('transition-property');
			}, duration);
		},

		// Animation slideDown en vanilla JS
		slideDown: function (element, duration) {
			duration = duration || 200;
			element.style.removeProperty('display');
			var display = window.getComputedStyle(element).display;
			if (display === 'none') {
				display = 'block';
			}
			element.style.display = display;

			var height = element.offsetHeight;
			element.style.overflow = 'hidden';
			element.style.height = '0';
			element.style.paddingTop = '0';
			element.style.paddingBottom = '0';
			element.style.marginTop = '0';
			element.style.marginBottom = '0';
			element.offsetHeight; // Force reflow
			element.style.boxSizing = 'border-box';
			element.style.transitionProperty = 'height, margin, padding';
			element.style.transitionDuration = duration + 'ms';
			element.style.height = height + 'px';
			element.style.removeProperty('padding-top');
			element.style.removeProperty('padding-bottom');
			element.style.removeProperty('margin-top');
			element.style.removeProperty('margin-bottom');

			setTimeout(function () {
				element.style.removeProperty('height');
				element.style.removeProperty('overflow');
				element.style.removeProperty('transition-duration');
				element.style.removeProperty('transition-property');
			}, duration);
		},

		// Méthode pour appliquer les styles Tailwind selon l'état actif
		applyActiveStyles: function () {
			var filters = this.getFilters();
			var showAllButtons = this.getShowAllButtons();

			// Réinitialiser tous les styles
			filters.forEach(function (filter) {
				filter.classList.remove('bg-primary', 'text-background');
				if (!filter.classList.contains('active')) {
					filter.classList.add('bg-slate-100', 'text-slate-700');
				}
			});

			// Appliquer les styles pour les boutons actifs
			filters.forEach(function (filter) {
				if (filter.classList.contains('active')) {
					filter.classList.remove('bg-slate-100', 'text-slate-700');
					filter.classList.add('bg-primary', 'text-background');
				}
			});

			// Style spécial pour "Toutes les catégories"
			showAllButtons.forEach(function (btn) {
				if (!btn.classList.contains('active')) {
					btn.classList.add('font-semibold', 'bg-slate-100');
				} else {
					btn.classList.add(
						'bg-primary',
						'text-background',
						'font-semibold'
					);
				}
			});
		},

		updateFiltersState: function () {
			var urlParams = new URLSearchParams(window.location.search);
			var activeFilters = urlParams.get('filters');

			if (activeFilters) {
				// Désactiver d'abord les boutons "Toutes les catégories"
				this.getShowAllButtons().forEach(function (btn) {
					btn.classList.remove('active');
					btn.setAttribute('aria-pressed', 'false');
				});

				activeFilters.split(',').forEach(function (filter) {
					var el = document.querySelector(
						'.ajax-posts__filter[data-term="' + filter + '"]'
					);
					if (el) {
						el.classList.add('active');
						el.setAttribute('aria-pressed', 'true');
					}
				});
			} else {
				// Si aucun filtre actif, activer les boutons "Toutes les catégories"
				this.getShowAllButtons().forEach(function (btn) {
					btn.classList.add('active');
					btn.setAttribute('aria-pressed', 'true');
				});
			}

			// Appliquer les styles actifs
			this.applyActiveStyles();
		},

		getActiveFilters: function () {
			var filters = {};

			// Ne prendre en compte que les filtres actifs qui ne sont pas "Toutes les catégories"
			this.getFilters().forEach(function (filter) {
				if (
					filter.classList.contains('active') &&
					!filter.classList.contains('js-show-all-filters')
				) {
					var taxonomy = filter.dataset.filter;
					var term = filter.dataset.term;

					if (!filters[taxonomy]) {
						filters[taxonomy] = [];
					}
					filters[taxonomy].push(term);
				}
			});

			return filters;
		},

		updatePosts: function (append) {
			var params = {
				postType: this.container.dataset.postType,
				postStatus: this.container.dataset.postStatus,
				tax: this.getActiveFilters(),
				page: 1,
				orderby: this.container.dataset.orderby,
				order: this.container.dataset.order,
				quantity: this.container.dataset.quantity,
				language: filterPosts.language || '',
				id: this.container.dataset.id || '',
				template: this.container.dataset.template || 'template-parts/content/content',
			};

			this.fetchPosts(params, append);
		},

		loadMorePosts: function (page) {
			var params = {
				postType: this.container.dataset.postType,
				postStatus: this.container.dataset.postStatus,
				tax: this.getActiveFilters(),
				page: page,
				orderby: this.container.dataset.orderby,
				order: this.container.dataset.order,
				quantity: this.container.dataset.quantity,
				language: filterPosts.language || '',
				id: this.container.dataset.id || '',
				template: this.container.dataset.template || 'template-parts/content/content',
			};

			this.fetchPosts(params, true);
		},

		fetchPosts: function (params, append) {
			var self = this;
			this.container.classList.add('loading');

			// Afficher le spinner accessible
			var spinner = document.querySelector('.ajax-posts__spinner');
			if (spinner) {
				spinner.classList.remove('hidden');
				spinner.classList.add('flex');
			}

			// Gérer l'état du bouton "Charger plus"
			var loadMoreButtons = this.getLoadMoreButtons();
			if (append) {
				loadMoreButtons.forEach(function (btn) {
					var buttonText = btn.querySelector('.button-text');
					var loadingText = btn.querySelector('.js-loading-text');
					if (buttonText) buttonText.classList.add('hidden');
					if (loadingText) {
						loadingText.classList.remove('hidden');
						loadingText.setAttribute('aria-hidden', 'false');
					}
					btn.disabled = true;
				});
			}

			// Créer le FormData pour la requête
			var formData = new FormData();
			formData.append('action', 'process_filter_change');
			formData.append('nonce', filterPosts.nonce);

			// Ajouter les params de manière récursive
			this.appendFormData(formData, params, 'params');

			// Utiliser AbortController pour le timeout
			var controller = new AbortController();
			var timeoutId = setTimeout(function () {
				controller.abort();
			}, 20000);

			fetch(filterPosts.ajaxUrl, {
				method: 'POST',
				body: formData,
				signal: controller.signal,
			})
				.then(function (response) {
					clearTimeout(timeoutId);
					return response.json();
				})
				.then(function (response) {
					if (response.success) {
						var postsContainer = self.container.querySelector(
							'.ajax-posts__posts .grid'
						);

						if (postsContainer) {
							if (append) {
								postsContainer.appendChild(parseSafeHTML(response.data.content));
							} else {
								postsContainer.replaceChildren(parseSafeHTML(response.data.content));
							}

							// Let plugins decorate the new markup (e.g. the
							// external link markers of UFO External Links).
							document.dispatchEvent(
								new CustomEvent('ufo:content-added', { detail: { root: postsContainer } })
							);
						}

						loadMoreButtons.forEach(function (btn) {
							if (response.data.has_more) {
								btn.dataset.page = response.data.next_page;
								btn.style.display = '';
							} else {
								btn.style.display = 'none';
							}
						});

						if (self.status) {
							const found = response.data.found;
							self.status.textContent = found + ' résultat' + (found > 1 ? 's' : '') + ' trouvé' + (found > 1 ? 's' : '');
						}

						self.updateURL();
					} else {
						if (self.status) {
							self.status.textContent = response.data || filterPosts.serverErrorMessage;
							self.status.classList.add('error');
						}
					}
				})
				.catch(function (error) {
					clearTimeout(timeoutId);
					if (self.status) {
						if (error.name === 'AbortError') {
							self.status.textContent = filterPosts.timeoutMessage;
						} else {
							self.status.textContent = filterPosts.serverErrorMessage;
						}
						self.status.classList.add('error');
					}
				})
				.finally(function () {
					self.container.classList.remove('loading');

					// Masquer le spinner
					if (spinner) {
						spinner.classList.add('hidden');
						spinner.classList.remove('flex');
					}

					// Restaurer le bouton "Charger plus"
					if (append) {
						loadMoreButtons.forEach(function (btn) {
							var buttonText = btn.querySelector('.button-text');
							var loadingText =
								btn.querySelector('.js-loading-text');
							if (buttonText)
								buttonText.classList.remove('hidden');
							if (loadingText) {
								loadingText.classList.add('hidden');
								loadingText.setAttribute('aria-hidden', 'true');
							}
							btn.disabled = false;
						});
					}
				});
		},

		// Helper pour ajouter des données récursives au FormData
		appendFormData: function (formData, data, parentKey) {
			var self = this;
			if (
				data &&
				typeof data === 'object' &&
				!(data instanceof Date) &&
				!(data instanceof File)
			) {
				Object.keys(data).forEach(function (key) {
					self.appendFormData(
						formData,
						data[key],
						parentKey ? parentKey + '[' + key + ']' : key
					);
				});
			} else {
				var value = data == null ? '' : data;
				formData.append(parentKey, value);
			}
		},

		updateURL: function () {
			var activeFilters = this.getActiveFilters();
			var filterTerms = [];

			for (var taxonomy in activeFilters) {
				filterTerms = filterTerms.concat(activeFilters[taxonomy]);
			}

			var newURL = new URL(window.location);
			if (filterTerms.length > 0) {
				newURL.searchParams.set('filters', filterTerms.join(','));
			} else {
				newURL.searchParams.delete('filters');
			}
			window.history.pushState({}, '', newURL);
		},
	};

	// Initialisation au chargement du DOM
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			window.ajaxFilter = new AjaxFilter();
		});
	} else {
		window.ajaxFilter = new AjaxFilter();
	}
})();
