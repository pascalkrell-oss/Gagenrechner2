(function () {
	'use strict';

	var CASE_UI = {
		werbung_mit_bild: {
			variantOptions: [
				['online_video_paid_media', 'Online Video Paid Media'],
				['atv_ctv_video_spot', 'ATV / CTV Video Spot'],
				['linear_tv_spot', 'Linear TV Spot'],
				['linear_tv_reminder', 'TV Reminder'],
				['tv_patronat', 'TV Patronat'],
				['atv_ctv_patronat', 'ATV / CTV Patronat'],
				['kino_spot', 'Kino Spot'],
				['pos_spot', 'POS Spot'],
				['animatic_narrative_moodfilm', 'Animatic / Narrative / Moodfilm'],
				['layout', 'Layout']
			],
			show: ['variant', 'usage_type', 'media_toggles', 'duration_minutes', 'addon_counts', 'rights_toggles'],
			scopeCopy: 'Bei Werbefällen stehen vor allem Spot-Ausprägung, Rechte-Erweiterungen und Zusatzmotive im Fokus.'
		},
		werbung_ohne_bild: {
			variantOptions: [
				['online_audio_paid_media', 'Online Audio Paid Media'],
				['funk_spot_national', 'Funkspot national'],
				['funk_spot_regional', 'Funkspot regional'],
				['funk_reminder', 'Funk Reminder'],
				['funk_allongen', 'Funk Allongen'],
				['ladenfunk_national', 'Ladenfunk national'],
				['ladenfunk_regional', 'Ladenfunk regional'],
				['telefon_werbespot', 'Telefon-Werbespot'],
				['layout', 'Layout']
			],
			show: ['variant', 'usage_type', 'addon_counts', 'rights_toggles'],
			scopeCopy: 'Audio-Werbung arbeitet überwiegend mit Varianten, Reminder-/Allongen-Logik und passenden Zusatzrechten.'
		},
		webvideo_imagefilm_praesentation_unpaid: { show: ['usage_type', 'media_toggles', 'duration_minutes'], scopeCopy: 'Für unpaid Bildfälle wird hauptsächlich die Minutenstaffel inklusive optionaler Zusatzlizenzen geführt.' },
		app: { show: ['duration_minutes'], scopeCopy: 'Apps werden in der Regel über eine minutenbasierte Standardnutzung mit unbegrenzter Laufzeit kalkuliert.' },
		telefonansage: { show: ['module_count'], scopeCopy: 'Telefonansagen rechnen modulbasiert. TV-, Paid- oder Social-Logiken sind hier fachlich nicht relevant.' },
		elearning_audioguide: {
			variantOptions: [['elearning_intern', 'E-Learning intern'], ['audioguide', 'Audioguide']],
			show: ['variant', 'duration_minutes'],
			scopeCopy: 'E-Learning und Audioguides basieren auf Minutenstaffeln und der passenden Inhaltsart.'
		},
		podcast: {
			variantOptions: [['podcast_inhalte', 'Podcast-Inhalte'], ['non_commercial_3', 'Verpackung nicht-kommerziell 3 Jahre'], ['non_commercial_unlim', 'Verpackung nicht-kommerziell unbegrenzt'], ['marketing_3', 'Verpackung Marketing 3 Jahre'], ['marketing_unlim', 'Verpackung Marketing unbegrenzt']],
			show: ['variant', 'usage_type', 'duration_minutes'],
			scopeCopy: 'Bei Podcasts trennt der Rechner zwischen Inhalten und Verpackung – inklusive Redirects für Video- oder Werbefälle.'
		},
		hoerbuch: { show: ['fah'], scopeCopy: 'Hörbücher bleiben bewusst als Vorschlagskalkulation mit Expertenergänzungen und Hinweisen aufgebaut.' },
		games: { show: ['recording_hours', 'recording_days', 'same_day_projects', 'usage_type'], scopeCopy: 'Games berücksichtigen Session-Logik, Wiederholungen an Folgetagen und parallele Projekte.' },
		redaktionell_doku_tv_reportage: { variantOptions: [['kommentarstimme', 'Kommentarstimme'], ['overvoice', 'Overvoice']], show: ['variant', 'net_minutes'], scopeCopy: 'Redaktionelle Inhalte kombinieren Minutensatz und Mindestgage transparent.' },
		audiodeskription: { variantOptions: [['audiodeskription', 'Audiodeskription']], show: ['variant', 'net_minutes'], scopeCopy: 'Audiodeskription nutzt einen klaren Minutensatz mit Mindestgage.' },
		kleinraeumig: { variantOptions: [['lokaler_funkspot', 'Lokaler Funkspot'], ['kleinraeumiger_online_video_paid', 'Kleinräumiges Online Video Paid']], show: ['variant', 'addon_counts'], scopeCopy: 'Kleinräumige Nutzung reduziert das Feld auf lokale Ausspielungen und passende Rechte-Erweiterungen.' }
	};

	var SCENARIO_TO_CASE = {
		online_audio_spot_unpaid: 'webvideo_imagefilm_praesentation_unpaid',
		online_video_spot_unpaid: 'webvideo_imagefilm_praesentation_unpaid',
		in_app_ads: 'werbung_mit_bild',
		telefon_werbespot: 'werbung_ohne_bild',
		marketing_elearning: 'webvideo_imagefilm_praesentation_unpaid',
		oeffentliches_elearning: 'webvideo_imagefilm_praesentation_unpaid',
		video_podcast: 'webvideo_imagefilm_praesentation_unpaid',
		podcast_sponsoring_audio: 'werbung_ohne_bild',
		podcast_sponsoring_video: 'werbung_mit_bild',
		werbliche_podcast_verpackung_audio: 'werbung_ohne_bild',
		werbliche_podcast_verpackung_video: 'werbung_mit_bild',
		lokaler_funkspot: 'kleinraeumig',
		werbliche_games_zusatznutzung: 'werbung_mit_bild'
	};

	function htmlEscape(value) {
		return String(value == null ? '' : value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

	function parseJsonAttribute(node, attribute) {
		try {
			return JSON.parse(node.getAttribute(attribute) || '{}');
		} catch (error) {
			return {};
		}
	}

	function labelFromKey(value) {
		return String(value || '').replace(/_/g, ' ').replace(/\b\w/g, function (match) { return match.toUpperCase(); });
	}

	function renderList(items, renderer, emptyText, className) {
		if (!items || !items.length) {
			return '<p>' + htmlEscape(emptyText) + '</p>';
		}
		return '<ul class="' + className + '">' + items.map(renderer).join('') + '</ul>';
	}

	function renderResult(container, payload) {
		var result = payload.result || {};
		var uiState = payload.ui_state || {};
		var totals = result.formatted_totals || {};
		var lineItems = Array.isArray(result.line_items) ? result.line_items : [];
		var licenses = Array.isArray(result.licenses) ? result.licenses : [];
		var notes = (Array.isArray(result.notes) ? result.notes : []).concat(Array.isArray(result.legal_texts) ? result.legal_texts : []);
		var warnings = Array.isArray(result.warnings) ? result.warnings : [];
		var alternatives = Array.isArray(result.alternatives) ? result.alternatives : [];
		var routeTrace = Array.isArray(result.route_trace) ? result.route_trace : [];
		var redirectCopy = '';

		if (uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) {
			redirectCopy = 'Dieser Fall wird fachlich als „' + labelFromKey(uiState.resolved_case) + '“ berechnet.';
		}

		container.innerHTML = '' +
			'<section class="sgk-result-card sgk-result-card--dark">' +
				'<span class="sgk-result-kicker">Errechnete Spanne</span>' +
				'<h4>' + htmlEscape(result.display_title || 'Noch kein Fall aufgelöst') + '</h4>' +
				'<p>' + htmlEscape(redirectCopy || 'Die Empfehlung zeigt Preisrahmen und fachlich passenden Mittelwert. Die endgültige Angebotssumme setzen Sie später bewusst manuell.') + '</p>' +
				'<div class="sgk-totals">' +
					'<div class="sgk-total-card"><span>Von</span><strong>' + htmlEscape(totals.lower || '0,00 €') + '</strong><small>untere Orientierung</small></div>' +
					'<div class="sgk-total-card sgk-total-card--featured"><span>Mittelwert</span><strong>' + htmlEscape(totals.mid || '0,00 €') + '</strong><small>Empfehlung für die Angebotsverhandlung</small></div>' +
					'<div class="sgk-total-card"><span>Bis</span><strong>' + htmlEscape(totals.upper || '0,00 €') + '</strong><small>obere Orientierung</small></div>' +
				'</div>' +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Mittelwert / Empfehlung</span>' +
				'<h4>Angebotsreifer Richtwert</h4>' +
				'<p>' + htmlEscape((result.result_meta && result.result_meta.manual_final_offer_required) ? 'Die finale Angebotssumme wird nicht automatisch festgeschrieben. Nutzen Sie den Mittelwert als begründete Ausgangsbasis für Ihr finales Angebot.' : 'Die Berechnung liefert bereits einen finalen Richtwert.') + '</p>' +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Nutzungsrechte & Lizenzen</span>' +
				'<h4>Lizenzübersicht</h4>' +
				renderList(licenses, function (item) {
					return '<li><strong>' + htmlEscape(labelFromKey(item.case_key || 'lizenz')) + '</strong><span>' + htmlEscape((item.variant ? labelFromKey(item.variant) + ' · ' : '') + 'Laufzeit: ' + labelFromKey((item.duration_rules && item.duration_rules.default_term) || 'projektbezogen')) + '</span></li>';
				}, 'Die Lizenzübersicht wird nach der Berechnung ergänzt.', 'sgk-license-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Breakdown / Rechenweg</span>' +
				'<h4>Strukturierte Kalkulation</h4>' +
				renderList(lineItems, function (item) {
					return '<li><div><strong>' + htmlEscape(item.label || 'Position') + '</strong><span>' + htmlEscape((item.quantity || 0) + ' ' + (item.unit_label || '') + ' · ' + (item.calculation_note || '')) + '</span></div><em>' + htmlEscape((item.formatted && item.formatted.mid) || '0,00 €') + '</em></li>';
				}, 'Sobald gerechnet wurde, erscheinen hier Basispositionen, Add-ons und Ausgleichswerte.', 'sgk-breakdown-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Hinweise / Fachinfos</span>' +
				'<h4>Einordnung für Angebot und Kommunikation</h4>' +
				renderList(notes.concat(warnings), function (note) { return '<li>' + htmlEscape(note) + '</li>'; }, 'Noch keine zusätzlichen Hinweise.', 'sgk-note-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Alternativen</span>' +
				'<h4>Paket- und Vergleichsoptionen</h4>' +
				renderList(alternatives, function (item) { return '<li><strong>' + htmlEscape(item.label || 'Alternative') + '</strong><span>' + htmlEscape((item.formatted_totals && item.formatted_totals.mid) || '0,00 €') + '</span></li>'; }, 'Für diesen Fall sind aktuell keine Paket-Alternativen hinterlegt.', 'sgk-license-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Resolver-Hinweise</span>' +
				'<h4>Fachlich übersetzter Rechenweg</h4>' +
				renderList(routeTrace, function (item) { return '<li><strong>' + htmlEscape(labelFromKey(item.step || 'Schritt')) + '</strong><span>' + htmlEscape(item.message || '') + '</span></li>'; }, 'Noch keine Resolver-Hinweise.', 'sgk-note-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Aktionen</span>' +
				'<h4>Export / Kopieren / Speichern</h4>' +
				'<div class="sgk-action-grid">' +
					'<div class="sgk-action-chip"><strong>Export</strong><span>Phase 4 vorbereitet</span></div>' +
					'<div class="sgk-action-chip"><strong>Kopieren</strong><span>für Angebotstext</span></div>' +
					'<div class="sgk-action-chip"><strong>Speichern</strong><span>für spätere Übergabe</span></div>' +
				'</div>' +
			'</section>';
	}

	function populateVariants(form, effectiveCase) {
		var select = form.querySelector('[name="case_variant"]');
		var hint = form.querySelector('[data-sgk-variant-hint]');
		var config = CASE_UI[effectiveCase] || {};
		var options = config.variantOptions || [];
		var current = select.value;
		select.innerHTML = '';
		if (!options.length) {
			select.innerHTML = '<option value="">Automatisch aus der Fachlogik ableiten</option>';
			hint.textContent = 'Für diesen Fall ist keine separate Unterauswahl erforderlich.';
			return;
		}
		select.innerHTML = '<option value="">Bitte Ausprägung wählen</option>' + options.map(function (item) {
			return '<option value="' + htmlEscape(item[0]) + '">' + htmlEscape(item[1]) + '</option>';
		}).join('');
		if (current) {
			select.value = current;
		}
		hint.textContent = 'Der Unterfall wird passend zum gewählten Projekt angeboten.';
	}

	function toggleBlocks(form, effectiveCase, hasSelection) {
		var visible = ((CASE_UI[effectiveCase] && CASE_UI[effectiveCase].show) || []).concat(['scope_note']);
		form.querySelectorAll('[data-sgk-block]').forEach(function (block) {
			var key = block.getAttribute('data-sgk-block');
			block.classList.toggle('sgk-hidden', hasSelection ? visible.indexOf(key) === -1 : true);
		});
		form.querySelectorAll('[data-sgk-dependent-step]').forEach(function (step) {
			step.classList.toggle('is-disabled', !hasSelection);
		});
		var expertShell = form.querySelector('[data-sgk-expert-shell]');
		if (expertShell) {
			expertShell.classList.toggle('is-disabled', !hasSelection);
		}
		var scopeCopy = form.querySelector('[data-sgk-scope-copy]');
		if (scopeCopy) {
			scopeCopy.textContent = hasSelection ? ((CASE_UI[effectiveCase] && CASE_UI[effectiveCase].scopeCopy) || 'Die Angaben werden an den gewählten Fachfall angepasst.') : 'Wählen Sie zunächst ein Projekt, damit der Umfang fachlich passend eingeordnet wird.';
		}
	}

	function updateCaseContext(app, selectedCase, cases) {
		var node = app.querySelector('[data-sgk-case-context]');
		var quickButtons = app.querySelectorAll('[data-sgk-quick-case]');
		quickButtons.forEach(function (button) {
			button.classList.toggle('is-active', button.getAttribute('data-sgk-quick-case') === selectedCase);
		});
		if (!selectedCase) {
			node.innerHTML = '<strong>Noch kein Fall ausgewählt</strong><p>Sobald ein Projektfall gewählt ist, zeigt der Rechner die passende Eingabeführung und blendet irrelevante Felder aus.</p>';
			return;
		}
		var effectiveCase = cases[selectedCase] ? selectedCase : (SCENARIO_TO_CASE[selectedCase] || selectedCase);
		var caseData = cases[effectiveCase] || {};
		node.innerHTML = '<strong>' + htmlEscape(caseData.label || labelFromKey(selectedCase)) + '</strong><p>' + htmlEscape(caseData.description || 'Die Eingabeführung wurde auf den gewählten Fachfall umgestellt.') + '</p>';
	}

	function serializeForm(form) {
		var formData = new FormData(form);
		var payload = {};
		formData.forEach(function (value, key) {
			payload[key] = value;
		});
		form.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
			payload[checkbox.name] = checkbox.checked ? '1' : '0';
		});
		return payload;
	}

	function updateExpertBadges(app, uiState) {
		var container = app.querySelector('[data-sgk-expert-badges]');
		var flags = (uiState && uiState.available_expert_options) || [];
		if (!container) {
			return;
		}
		if (!flags.length) {
			container.innerHTML = '<span class="sgk-badge is-muted">Noch keine Expertenoptionen aktiv</span>';
			return;
		}
		container.innerHTML = flags.map(function (flag) {
			return '<span class="sgk-badge">' + htmlEscape(labelFromKey(flag)) + '</span>';
		}).join('');
	}

	function updateRedirectBanner(app, uiState) {
		var banner = app.querySelector('[data-sgk-redirect-banner]');
		if (!banner) {
			return;
		}
		if (uiState && uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) {
			banner.hidden = false;
			banner.textContent = 'Dieser Fall wird gemäß Nutzungsart fachlich als „' + labelFromKey(uiState.resolved_case) + '“ geführt.';
			return;
		}
		banner.hidden = true;
		banner.textContent = '';
	}

	function requestCalculation(app, form, resultContainer) {
		var payload = serializeForm(form);
		if (!payload.case_key) {
			return;
		}
		resultContainer.innerHTML = '<div class="sgk-result-empty"><strong>Berechnung läuft</strong><p>Resolver, Rechte-Logik und Kalkulationsspanne werden gerade aktualisiert.</p></div>';
		fetch(sgkFrontend.restUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': sgkFrontend.nonce },
			body: JSON.stringify(payload)
		})
			.then(function (response) { return response.json(); })
			.then(function (json) {
				renderResult(resultContainer, json);
				updateExpertBadges(app, json.ui_state || {});
				updateRedirectBanner(app, json.ui_state || {});
			})
			.catch(function () {
				resultContainer.innerHTML = '<div class="sgk-result-empty"><strong>Berechnung momentan nicht verfügbar</strong><p>Die REST-Berechnung konnte nicht geladen werden.</p></div>';
			});
	}

	document.addEventListener('DOMContentLoaded', function () {
		var app = document.querySelector('[data-sgk-app]');
		if (!app || typeof fetch !== 'function') {
			return;
		}
		var form = app.querySelector('[data-sgk-form]');
		var resultContainer = app.querySelector('[data-sgk-result]');
		var cases = parseJsonAttribute(app, 'data-sgk-cases');
		var debounceTimer = null;

		function syncUI() {
			var selectedCase = form.querySelector('[name="case_key"]').value;
			var effectiveCase = cases[selectedCase] ? selectedCase : (SCENARIO_TO_CASE[selectedCase] || selectedCase);
			updateCaseContext(app, selectedCase, cases);
			populateVariants(form, effectiveCase);
			toggleBlocks(form, effectiveCase, !!selectedCase);
		}

		app.querySelectorAll('[data-sgk-quick-case]').forEach(function (button) {
			button.addEventListener('click', function () {
				form.querySelector('[name="case_key"]').value = button.getAttribute('data-sgk-quick-case');
				syncUI();
				requestCalculation(app, form, resultContainer);
			});
		});

		app.querySelectorAll('[data-sgk-demo]').forEach(function (button) {
			button.addEventListener('click', function () {
				var data = JSON.parse(button.getAttribute('data-sgk-demo') || '{}');
				Object.keys(data).forEach(function (key) {
					var field = form.querySelector('[name="' + key + '"]');
					if (!field) {
						return;
					}
					if (field.type === 'checkbox') {
						field.checked = String(data[key]) === '1' || data[key] === 1 || data[key] === true;
					} else {
						field.value = data[key];
					}
				});
				syncUI();
				requestCalculation(app, form, resultContainer);
			});
		});

		form.addEventListener('change', function () {
			syncUI();
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function () {
				requestCalculation(app, form, resultContainer);
			}, 220);
		});

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			syncUI();
			requestCalculation(app, form, resultContainer);
		});

		syncUI();
	});
}());
