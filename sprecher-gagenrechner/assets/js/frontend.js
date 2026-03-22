(function () {
	'use strict';

	var CASE_UI = {
		werbung_mit_bild: { variantOptions: [['online_video_paid_media', 'Online Video Paid Media'], ['atv_ctv_video_spot', 'ATV / CTV Video Spot'], ['linear_tv_spot', 'Linear TV Spot'], ['linear_tv_reminder', 'TV Reminder'], ['tv_patronat', 'TV Patronat'], ['atv_ctv_patronat', 'ATV / CTV Patronat'], ['kino_spot', 'Kino Spot'], ['pos_spot', 'POS Spot'], ['animatic_narrative_moodfilm', 'Animatic / Narrative / Moodfilm'], ['layout', 'Layout']], show: ['variant', 'usage_type', 'media_toggles', 'duration_minutes', 'addon_counts', 'rights_toggles'], scopeCopy: 'Bei Werbefällen stehen vor allem Spot-Ausprägung, Rechte-Erweiterungen und Zusatzmotive im Fokus.' },
		werbung_ohne_bild: { variantOptions: [['online_audio_paid_media', 'Online Audio Paid Media'], ['funk_spot_national', 'Funkspot national'], ['funk_spot_regional', 'Funkspot regional'], ['funk_reminder', 'Funk Reminder'], ['funk_allongen', 'Funk Allongen'], ['ladenfunk_national', 'Ladenfunk national'], ['ladenfunk_regional', 'Ladenfunk regional'], ['telefon_werbespot', 'Telefon-Werbespot'], ['layout', 'Layout']], show: ['variant', 'usage_type', 'addon_counts', 'rights_toggles'], scopeCopy: 'Audio-Werbung arbeitet überwiegend mit Varianten, Reminder-/Allongen-Logik und passenden Zusatzrechten.' },
		webvideo_imagefilm_praesentation_unpaid: { show: ['usage_type', 'media_toggles', 'duration_minutes'], scopeCopy: 'Für unpaid Bildfälle wird hauptsächlich die Minutenstaffel inklusive optionaler Zusatzlizenzen geführt.' },
		app: { show: ['duration_minutes'], scopeCopy: 'Apps werden in der Regel über eine minutenbasierte Standardnutzung mit unbegrenzter Laufzeit kalkuliert.' },
		telefonansage: { show: ['module_count'], scopeCopy: 'Telefonansagen rechnen modulbasiert. TV-, Paid- oder Social-Logiken sind hier fachlich nicht relevant.' },
		elearning_audioguide: { variantOptions: [['elearning_intern', 'E-Learning intern'], ['audioguide', 'Audioguide']], show: ['variant', 'duration_minutes'], scopeCopy: 'E-Learning und Audioguides basieren auf Minutenstaffeln und der passenden Inhaltsart.' },
		podcast: { variantOptions: [['podcast_inhalte', 'Podcast-Inhalte'], ['non_commercial_3', 'Verpackung nicht-kommerziell 3 Jahre'], ['non_commercial_unlim', 'Verpackung nicht-kommerziell unbegrenzt'], ['marketing_3', 'Verpackung Marketing 3 Jahre'], ['marketing_unlim', 'Verpackung Marketing unbegrenzt']], show: ['variant', 'usage_type', 'duration_minutes'], scopeCopy: 'Bei Podcasts trennt der Rechner zwischen Inhalten und Verpackung – inklusive Redirects für Video- oder Werbefälle.' },
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

	var STORAGE_KEY = 'sgk_calculations_v1';

	function htmlEscape(value) {
		return String(value == null ? '' : value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
	}
	function parseJsonAttribute(node, attribute) { try { return JSON.parse(node.getAttribute(attribute) || '{}'); } catch (error) { return {}; } }
	function labelFromKey(value) { return String(value || '').replace(/_/g, ' ').replace(/\b\w/g, function (m) { return m.toUpperCase(); }); }
	function renderList(items, renderer, emptyText, className) { return !items || !items.length ? '<p>' + htmlEscape(emptyText) + '</p>' : '<ul class="' + className + '">' + items.map(renderer).join('') + '</ul>'; }
	function moneyInputToNumber(value) { var normalized = String(value || '').replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, ''); var parsed = parseFloat(normalized); return isNaN(parsed) ? null : parsed; }
	function formatCurrencyNumber(value) { if (value == null || value === '') { return '—'; } return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(Number(value)); }
	function storageAvailable() { try { localStorage.setItem('__sgk_test__', '1'); localStorage.removeItem('__sgk_test__'); return true; } catch (error) { return false; } }
	function clone(obj) { return JSON.parse(JSON.stringify(obj)); }

	function getSavedCalculations() {
		if (!storageAvailable()) { return []; }
		try {
			var entries = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
			return Array.isArray(entries) ? entries : [];
		} catch (error) {
			return [];
		}
	}

	function setSavedCalculations(entries) {
		if (!storageAvailable()) { return false; }
		localStorage.setItem(STORAGE_KEY, JSON.stringify(entries));
		return true;
	}

	function serializeForm(form) {
		var formData = new FormData(form);
		var payload = {};
		formData.forEach(function (value, key) { payload[key] = value; });
		form.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) { payload[checkbox.name] = checkbox.checked ? '1' : '0'; });
		return payload;
	}

	function fillForm(form, data) {
		Object.keys(data || {}).forEach(function (key) {
			var field = form.querySelector('[name="' + key + '"]');
			if (!field) { return; }
			if (field.type === 'checkbox') {
				field.checked = data[key] === '1' || data[key] === 1 || data[key] === true;
			} else {
				field.value = data[key];
			}
		});
	}

	function validateManualOffer(value, result) {
		if (value == null || value === '') { return { valid: true, message: '' }; }
		if (value <= 0) { return { valid: false, message: 'Bitte eine positive finale Angebotssumme eintragen.' }; }
		if (result && result.totals && value < result.totals.lower * 0.25) { return { valid: false, message: 'Die manuelle Angebotssumme liegt ungewöhnlich weit unter der Empfehlung.' }; }
		return { valid: true, message: 'Manuelle Angebotssumme wird separat zur Empfehlung gespeichert.' };
	}

	function copyText(text, trigger) {
		if (!text) { return Promise.reject(new Error('empty')); }
		var promise = navigator.clipboard && navigator.clipboard.writeText ? navigator.clipboard.writeText(text) : Promise.reject(new Error('clipboard-unavailable'));
		return promise.then(function () {
			if (trigger) { trigger.textContent = 'Kopiert'; setTimeout(function () { trigger.textContent = trigger.getAttribute('data-label'); }, 1600); }
		});
	}

	function buildSavedLabel(entry) {
		var title = entry.projectTitle || (entry.result && entry.result.display_title) || 'Gespeicherte Kalkulation';
		var stamp = new Date(entry.savedAt).toLocaleString('de-DE');
		return title + ' · ' + stamp;
	}

	function buildExportPayload(result, formData) {
		var payload = clone(result.export_payload || {});
		payload.summary = payload.summary || {};
		payload.summary.project_title = formData.project_title || '';
		payload.summary.customer_name = formData.customer_name || '';
		payload.summary.display_title = result.display_title || '';
		payload.summary.generated_at = new Date().toISOString();
		payload.calculation_meta = payload.calculation_meta || {};
		payload.calculation_meta.internal_notes = formData.internal_notes || '';
		payload.calculation_meta.source_form = formData;
		return payload;
	}

	function buildCopyBlocks(result, formData) {
		var exportPayload = buildExportPayload(result, formData);
		var texts = exportPayload.export_text_blocks || {};
		return {
			summary: [texts.offer_headline || ('Angebot Sprecherhonorar – ' + (formData.project_title || result.display_title || 'Projekt')), texts.copy_summary || '', formData.customer_name ? ('Kunde: ' + formData.customer_name) : '', texts.manual_offer_notice || ''].filter(Boolean).join('\n'),
			positions: texts.positions_block || '',
			rights: texts.rights_block || '',
			json: JSON.stringify(exportPayload, null, 2)
		};
	}

	function renderResult(container, payload, formData) {
		var result = payload.result || {};
		var uiState = payload.ui_state || {};
		var totals = result.formatted_totals || {};
		var notes = Array.isArray(result.offer_notes) ? result.offer_notes : [];
		var alternatives = Array.isArray(result.alternatives) ? result.alternatives : [];
		var routeTrace = Array.isArray(result.route_summary_offer) ? result.route_summary_offer : [];
		var rights = Array.isArray(result.rights_overview) ? result.rights_overview : [];
		var positions = Array.isArray(result.offer_positions) ? result.offer_positions : [];
		var manualOffer = result.formatted_manual_offer_total || 'Noch nicht gesetzt';
		var manualValidation = validateManualOffer(result.manual_offer_total, result);
		var copyBlocks = buildCopyBlocks(result, formData || {});
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
				'<span class="sgk-result-kicker">Finale Angebotssumme</span>' +
				'<h4>Manuell gesetzter Angebotswert</h4>' +
				'<div class="sgk-manual-offer">' +
					'<label for="sgk-manual-offer-input">Finale Angebotssumme</label>' +
					'<div class="sgk-inline-input">' +
						'<input id="sgk-manual-offer-input" type="number" min="0" step="0.01" value="' + htmlEscape(result.manual_offer_total || '') + '" placeholder="z. B. 2.450,00" data-sgk-manual-offer />' +
						'<button type="button" class="sgk-button sgk-button--secondary" data-sgk-sync-manual-offer>Übernehmen</button>' +
					'</div>' +
					'<p class="sgk-field__hint">Berechnete Spanne und Mittelwert bleiben unverändert. Dieser Wert wird separat für Angebot, Export und Speicherung geführt.</p>' +
					'<div class="sgk-manual-offer__status ' + (manualValidation.valid ? 'is-valid' : 'is-invalid') + '">' + htmlEscape(manualValidation.message || ('Aktuell gesetzt: ' + manualOffer)) + '</div>' +
					'<div class="sgk-manual-offer__current"><strong>Aktuell:</strong> <span>' + htmlEscape(manualOffer) + '</span></div>' +
				'</div>' +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Angebotspositionen</span>' +
				'<h4>Exportierbare Positionen</h4>' +
				renderList(positions, function (item) {
					return '<li><div><strong>' + htmlEscape(item.position_number + '. ' + item.titel) + '</strong><span>' + htmlEscape(item.beschreibung) + '</span><small>' + htmlEscape((item.kategorie || '') + ' · ' + (item.lizenzbezug || '')) + '</small></div><em>' + htmlEscape(item.formatted_prices && item.formatted_prices.manual ? item.formatted_prices.manual : (item.formatted_prices ? item.formatted_prices.mid : '0,00 €')) + '</em></li>';
				}, 'Nach der Berechnung erscheinen hier übertragbare Angebotspositionen.', 'sgk-breakdown-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Nutzungsrechte & Lizenzen</span>' +
				'<h4>Rechteübersicht</h4>' +
				renderList(rights, function (item) {
					return '<li><strong>' + htmlEscape(item.title + (item.variant ? ' · ' + item.variant : '')) + '</strong><span>' + htmlEscape('Laufzeit: ' + item.duration + ' · Territorium: ' + item.territory + ' · Medien: ' + item.media) + '</span></li>';
				}, 'Die Lizenzübersicht wird nach der Berechnung ergänzt.', 'sgk-license-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Angebotslogik</span>' +
				'<h4>Rechenweg in Angebotssprache</h4>' +
				renderList(routeTrace, function (item) { return '<li><strong>' + htmlEscape(item.label || labelFromKey(item.step || 'Schritt')) + '</strong><span>' + htmlEscape(item.message || '') + '</span></li>'; }, 'Noch keine Resolver-Hinweise.', 'sgk-note-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Hinweise</span>' +
				'<h4>Anmerkungen, Expertenhinweise und Angebotsnotizen</h4>' +
				renderList(notes, function (note) { return '<li>' + htmlEscape(note) + '</li>'; }, 'Noch keine zusätzlichen Hinweise.', 'sgk-note-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Paket-Alternativen</span>' +
				'<h4>Vergleichsoptionen</h4>' +
				renderList(alternatives, function (item) { return '<li><strong>' + htmlEscape(item.label || 'Alternative') + '</strong><span>' + htmlEscape((item.formatted_totals && item.formatted_totals.mid) || '0,00 €') + '</span></li>'; }, 'Für diesen Fall sind aktuell keine Paket-Alternativen hinterlegt.', 'sgk-license-list') +
			'</section>' +
			'<section class="sgk-result-card">' +
				'<span class="sgk-result-kicker">Aktionen</span>' +
				'<h4>Speichern, laden, kopieren, exportieren</h4>' +
				'<div class="sgk-action-grid sgk-action-grid--actions">' +
					'<button type="button" class="sgk-button sgk-button--secondary" data-label="Kalkulation speichern" data-sgk-action="save">Kalkulation speichern</button>' +
					'<button type="button" class="sgk-button sgk-button--secondary" data-label="Zusammenfassung kopieren" data-sgk-action="copy-summary">Zusammenfassung kopieren</button>' +
					'<button type="button" class="sgk-button sgk-button--secondary" data-label="Angebotspositionen kopieren" data-sgk-action="copy-positions">Angebotspositionen kopieren</button>' +
					'<button type="button" class="sgk-button sgk-button--secondary" data-label="Rechteübersicht kopieren" data-sgk-action="copy-rights">Rechteübersicht kopieren</button>' +
					'<button type="button" class="sgk-button sgk-button--secondary" data-label="Exportdaten kopieren" data-sgk-action="copy-json">Exportdaten kopieren</button>' +
					'<button type="button" class="sgk-button sgk-button--ghost" disabled>PDF folgt in Phase 5</button>' +
				'</div>' +
				'<div class="sgk-storage-panel">' +
					'<div class="sgk-storage-panel__row"><label for="sgk-saved-calculations">Gespeicherte Kalkulationen</label><select id="sgk-saved-calculations" data-sgk-saved-list><option value="">Bitte wählen</option></select></div>' +
					'<div class="sgk-action-grid sgk-action-grid--storage">' +
						'<button type="button" class="sgk-button sgk-button--secondary" data-label="Kalkulation laden" data-sgk-action="load">Kalkulation laden</button>' +
						'<button type="button" class="sgk-button sgk-button--secondary" data-label="Kalkulation löschen" data-sgk-action="delete">Kalkulation löschen</button>' +
					'</div>' +
					'<p class="sgk-field__hint" data-sgk-storage-status>' + htmlEscape(storageAvailable() ? 'Speicherung lokal im Browser aktiv. Exportstruktur bleibt versionsfähig und PDF-ready.' : 'localStorage ist in dieser Umgebung nicht verfügbar.') + '</p>' +
				'</div>' +
				'<div class="sgk-copy-preview">' +
					'<h5>Copy Summary</h5><pre>' + htmlEscape(copyBlocks.summary) + '</pre>' +
					'<h5>Copy Angebotspositionen</h5><pre>' + htmlEscape(copyBlocks.positions) + '</pre>' +
					'<h5>Copy Rechteübersicht</h5><pre>' + htmlEscape(copyBlocks.rights) + '</pre>' +
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
		if (!options.length) { select.innerHTML = '<option value="">Automatisch aus der Fachlogik ableiten</option>'; hint.textContent = 'Für diesen Fall ist keine separate Unterauswahl erforderlich.'; return; }
		select.innerHTML = '<option value="">Bitte Ausprägung wählen</option>' + options.map(function (item) { return '<option value="' + htmlEscape(item[0]) + '">' + htmlEscape(item[1]) + '</option>'; }).join('');
		if (current) { select.value = current; }
		hint.textContent = 'Der Unterfall wird passend zum gewählten Projekt angeboten.';
	}

	function toggleBlocks(form, effectiveCase, hasSelection) {
		var visible = ((CASE_UI[effectiveCase] && CASE_UI[effectiveCase].show) || []).concat(['scope_note']);
		form.querySelectorAll('[data-sgk-block]').forEach(function (block) { var key = block.getAttribute('data-sgk-block'); block.classList.toggle('sgk-hidden', hasSelection ? visible.indexOf(key) === -1 : true); });
		form.querySelectorAll('[data-sgk-dependent-step]').forEach(function (step) { step.classList.toggle('is-disabled', !hasSelection); });
		var expertShell = form.querySelector('[data-sgk-expert-shell]');
		if (expertShell) { expertShell.classList.toggle('is-disabled', !hasSelection); }
		var scopeCopy = form.querySelector('[data-sgk-scope-copy]');
		if (scopeCopy) { scopeCopy.textContent = hasSelection ? ((CASE_UI[effectiveCase] && CASE_UI[effectiveCase].scopeCopy) || 'Die Angaben werden an den gewählten Fachfall angepasst.') : 'Wählen Sie zunächst ein Projekt, damit der Umfang fachlich passend eingeordnet wird.'; }
	}

	function updateCaseContext(app, selectedCase, cases) {
		var node = app.querySelector('[data-sgk-case-context]');
		app.querySelectorAll('[data-sgk-quick-case]').forEach(function (button) { button.classList.toggle('is-active', button.getAttribute('data-sgk-quick-case') === selectedCase); });
		if (!selectedCase) { node.innerHTML = '<strong>Noch kein Fall ausgewählt</strong><p>Sobald ein Projektfall gewählt ist, zeigt der Rechner die passende Eingabeführung und blendet irrelevante Felder aus.</p>'; return; }
		var effectiveCase = cases[selectedCase] ? selectedCase : (SCENARIO_TO_CASE[selectedCase] || selectedCase);
		var caseData = cases[effectiveCase] || {};
		node.innerHTML = '<strong>' + htmlEscape(caseData.label || labelFromKey(selectedCase)) + '</strong><p>' + htmlEscape(caseData.description || 'Die Eingabeführung wurde auf den gewählten Fachfall umgestellt.') + '</p>';
	}

	function updateExpertBadges(app, uiState) {
		var container = app.querySelector('[data-sgk-expert-badges]');
		var flags = (uiState && uiState.available_expert_options) || [];
		if (!container) { return; }
		container.innerHTML = !flags.length ? '<span class="sgk-badge is-muted">Noch keine Expertenoptionen aktiv</span>' : flags.map(function (flag) { return '<span class="sgk-badge">' + htmlEscape(labelFromKey(flag)) + '</span>'; }).join('');
	}

	function updateRedirectBanner(app, uiState) {
		var banner = app.querySelector('[data-sgk-redirect-banner]');
		if (!banner) { return; }
		if (uiState && uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) {
			banner.hidden = false;
			banner.textContent = 'Dieser Fall wird gemäß Nutzungsart fachlich als „' + labelFromKey(uiState.resolved_case) + '“ geführt.';
			return;
		}
		banner.hidden = true;
		banner.textContent = '';
	}

	function refreshSavedList(container) {
		var select = container.querySelector('[data-sgk-saved-list]');
		if (!select) { return; }
		var entries = getSavedCalculations();
		select.innerHTML = '<option value="">Bitte wählen</option>' + entries.map(function (entry) { return '<option value="' + htmlEscape(entry.id) + '">' + htmlEscape(buildSavedLabel(entry)) + '</option>'; }).join('');
	}

	function requestCalculation(app, form, resultContainer) {
		var payload = serializeForm(form);
		if (!payload.case_key) { return; }
		resultContainer.innerHTML = '<div class="sgk-result-empty"><strong>Berechnung läuft</strong><p>Resolver, Rechte-Logik und Kalkulationsspanne werden gerade aktualisiert.</p></div>';
		fetch(sgkFrontend.restUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': sgkFrontend.nonce }, body: JSON.stringify(payload) })
			.then(function (response) { return response.json(); })
			.then(function (json) {
				app.__sgkLastPayload = json;
				renderResult(resultContainer, json, payload);
				updateExpertBadges(app, json.ui_state || {});
				updateRedirectBanner(app, json.ui_state || {});
				refreshSavedList(resultContainer);
			})
			.catch(function () { resultContainer.innerHTML = '<div class="sgk-result-empty"><strong>Berechnung momentan nicht verfügbar</strong><p>Die REST-Berechnung konnte nicht geladen werden.</p></div>'; });
	}

	document.addEventListener('DOMContentLoaded', function () {
		var app = document.querySelector('[data-sgk-app]');
		if (!app || typeof fetch !== 'function') { return; }
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
				fillForm(form, JSON.parse(button.getAttribute('data-sgk-demo') || '{}'));
				syncUI();
				requestCalculation(app, form, resultContainer);
			});
		});

		form.addEventListener('change', function () {
			syncUI();
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function () { requestCalculation(app, form, resultContainer); }, 180);
		});
		form.addEventListener('input', function () {
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function () { requestCalculation(app, form, resultContainer); }, 260);
		});
		form.addEventListener('submit', function (event) { event.preventDefault(); syncUI(); requestCalculation(app, form, resultContainer); });

		resultContainer.addEventListener('click', function (event) {
			var action = event.target.getAttribute('data-sgk-action');
			var payload = app.__sgkLastPayload;
			var formData = serializeForm(form);
			if (!action || !payload || !payload.result) { return; }
			var copyBlocks = buildCopyBlocks(payload.result, formData);
			var statusNode = resultContainer.querySelector('[data-sgk-storage-status]');
			var entries, selectedId, entry;

			if (action === 'copy-summary') { copyText(copyBlocks.summary, event.target); return; }
			if (action === 'copy-positions') { copyText(copyBlocks.positions, event.target); return; }
			if (action === 'copy-rights') { copyText(copyBlocks.rights, event.target); return; }
			if (action === 'copy-json') { copyText(copyBlocks.json, event.target); return; }
			if (action === 'save') {
				if (!storageAvailable()) { if (statusNode) { statusNode.textContent = 'Speichern ist in dieser Umgebung nicht verfügbar.'; } return; }
				entries = getSavedCalculations();
				entry = {
					id: 'sgk-' + Date.now(),
					version: 1,
					savedAt: new Date().toISOString(),
					projectTitle: formData.project_title || payload.result.display_title || 'Kalkulation',
					formData: formData,
					result: payload.result,
					exportPayload: buildExportPayload(payload.result, formData)
				};
				entries.unshift(entry);
				setSavedCalculations(entries.slice(0, 15));
				refreshSavedList(resultContainer);
				if (statusNode) { statusNode.textContent = 'Kalkulation lokal gespeichert: ' + buildSavedLabel(entry); }
				return;
			}
			selectedId = (resultContainer.querySelector('[data-sgk-saved-list]') || {}).value;
			if (!selectedId) { if (statusNode) { statusNode.textContent = 'Bitte zuerst eine gespeicherte Kalkulation auswählen.'; } return; }
			entries = getSavedCalculations();
			entry = entries.find(function (item) { return item.id === selectedId; });
			if (!entry) { if (statusNode) { statusNode.textContent = 'Gespeicherte Kalkulation konnte nicht geladen werden.'; } return; }
			if (action === 'load') {
				fillForm(form, entry.formData || {});
				syncUI();
				requestCalculation(app, form, resultContainer);
				if (statusNode) { statusNode.textContent = 'Kalkulation geladen: ' + buildSavedLabel(entry); }
				return;
			}
			if (action === 'delete') {
				setSavedCalculations(entries.filter(function (item) { return item.id !== selectedId; }));
				refreshSavedList(resultContainer);
				if (statusNode) { statusNode.textContent = 'Kalkulation gelöscht.'; }
			}
		});

		resultContainer.addEventListener('click', function (event) {
			if (!event.target.hasAttribute('data-sgk-sync-manual-offer')) { return; }
			var input = resultContainer.querySelector('[data-sgk-manual-offer]');
			var value = moneyInputToNumber(input && input.value);
			if (value == null) { input.focus(); return; }
			var hidden = form.querySelector('[name="manual_offer_total"]');
			if (!hidden) {
				hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'manual_offer_total'; form.appendChild(hidden);
			}
			hidden.value = String(value);
			requestCalculation(app, form, resultContainer);
		});

		syncUI();
	})();
})();
