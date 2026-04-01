(function () {
	'use strict';

	var STORAGE_VERSION = 2;
	var STORAGE_KEY = 'sgk_calculations_v' + STORAGE_VERSION;
	var APP_STATE_VERSION = 2;
	var DEFAULT_RESULT_MESSAGE = '<div class="sgk-default-state"><div class="sgk-default-price-label">Deine Gage (Netto)</div><div class="sgk-default-price-value">0,00\u00a0€</div><div class="sgk-default-hint">Wähle links Dein Projekt – die Gage wird sofort berechnet.<span>Alle Preise zzgl. MwSt.</span></div></div>';
	var LOADING_RESULT_MESSAGE = '<div class="sgk-default-state"><span class="src-live-dot src-live-dot--pulse"></span><div class="sgk-default-price-label">Fast fertig...</div></div>';
	var ERROR_RESULT_MESSAGE = '<div class="src-result-empty"><strong>Berechnung gerade nicht möglich</strong><p>Bitte prüfe Deine Eingaben und versuche es gleich nochmal.</p></div>';
	var CASE_UI = {
		werbung_mit_bild: { variantOptions: [['online_video_paid_media', 'Online Video Paid Media'], ['atv_ctv_video_spot', 'ATV / CTV Video Spot'], ['linear_tv_spot_national', 'Linear TV Spot national'], ['linear_tv_spot_regional', 'Linear TV Spot regional'], ['tv_patronat', 'TV Patronat'], ['atv_ctv_patronat', 'ATV / CTV Patronat'], ['kino_spot_national', 'Kino Spot national'], ['kino_spot_regional', 'Kino Spot regional'], ['pos_event_messe', 'POS / Event / Messe'], ['layout_animatic_moodfilm_scribble', 'Layout / Animatic / Moodfilm / Scribble']], show: ['variant', 'usage_type', 'duration_term', 'territory', 'medium', 'addon_counts', 'rights_toggles'], scopeCopy: 'Wähle die Spot-Art und passe die Nutzungsrechte an Dein Projekt an.' },
		werbung_ohne_bild: { variantOptions: [['online_audio_paid_media', 'Online Audio Paid Media'], ['funk_spot_national', 'Funkspot national'], ['funk_spot_regional', 'Funkspot regional'], ['funk_spot_lokal', 'Funkspot lokal'], ['ladenfunk', 'Ladenfunk'], ['ladenfunk_regional', 'Ladenfunk regional', 'Die Nutzung in nur einer Handelskette wird als regional kalkuliert.'], ['telefon_werbespot', 'Telefon-Werbespot']], show: ['variant', 'usage_type', 'duration_term', 'territory', 'medium', 'addon_counts', 'rights_toggles'], scopeCopy: 'Wähle den Audio-Typ und ergänze nur die Rechte, die Du wirklich brauchst.' },
		webvideo_imagefilm_praesentation_unpaid: { variantOptions: [['imagefilm_webvideo_praesentation', 'Imagefilm / Webvideo / Präsentation', 'Ideal für klassische Unternehmensfilme, Webvideos und Präsentationen ohne Paid-Kampagne.'], ['awardfilm', 'Awardfilm', 'Für Einreichungen, Festivalfilme und Award-Kommunikation.'], ['casefilm', 'Casefilm', 'Für Referenzfilme und Cases mit dokumentierter Projektwirkung.'], ['mitarbeiterfilm', 'Mitarbeiterfilm', 'Für interne Kommunikation und Employer-Branding-Formate.']], show: ['variant', 'duration_minutes', 'media_toggles'], scopeCopy: 'Wähle zuerst die passende Filmvariante und ergänze danach nur Kanäle, die wirklich zusätzlich genutzt werden.' },
		app: { show: ['duration_minutes', 'duration_term'], scopeCopy: 'Bei App-Projekten zählen vor allem Dauer und Einsatzrahmen.' },
		telefonansage: { show: ['module_count'], scopeCopy: 'Für Telefonansagen reicht die Anzahl der gewünschten Ansagen.' },
		elearning_audioguide: { variantOptions: [['elearning_intern', 'E-Learning intern'], ['audioguide', 'Audioguide']], show: ['variant', 'duration_minutes'], scopeCopy: 'Wähle die passende Art und gib die Länge in Minuten an.' },
		podcast: { variantOptions: [['podcast_inhalte', 'Podcast-Inhalt', 'Redaktioneller Audio-Podcast-Inhalt ohne Werberouting.'], ['non_commercial_3', 'Verpackung nicht-kommerziell · bis 3 Folgen', 'Intro/Outro/Ansagen für nicht-kommerzielle Podcasts.'], ['non_commercial_unlim', 'Verpackung nicht-kommerziell · Serienlizenz', 'Nicht-kommerzielle Verpackung für alle Folgen einer Serie.'], ['marketing_3', 'Verpackung kommerziell · bis 3 Folgen', 'Kommerzielle Verpackung bleibt Podcast, solange kein Sponsor- oder Werbespot vorliegt.'], ['marketing_unlim', 'Verpackung kommerziell · Serienlizenz', 'Kommerzielle Serienverpackung. Für echte Sponsorings bitte den Werbefall im Podcast-Kontext wählen.']], show: ['variant'], scopeCopy: 'Audio-Podcast: Inhalt bleibt redaktionell, Verpackung bleibt Podcast – echte Sponsorings/Werbespots laufen separat über Werbelogik.' },
		hoerbuch: { show: ['fah'], scopeCopy: 'Beim Hörbuch richten sich die Werte nach den fertigen Hörbuch-Stunden.' },
		games: { show: ['recording_hours', 'recording_days', 'same_day_projects'], scopeCopy: 'Bei Games sind Studiozeit, Tage und weitere Projekte am selben Tag wichtig.' },
		redaktionell_doku_tv_reportage: { variantOptions: [['kommentarstimme', 'Kommentarstimme'], ['overvoice', 'Overvoice']], show: ['variant', 'net_minutes'], scopeCopy: 'Für Doku und TV zählen vor allem Sendeminuten und die genaue Rolle.' },
		audiodeskription: { variantOptions: [['audiodeskription', 'Audiodeskription']], show: ['variant', 'net_minutes'], scopeCopy: 'Audiodeskription wird fair nach Sendeminuten berechnet.' },
		kleinraeumig: { variantOptions: [['funk_spot_lokal', 'Lokaler Funkspot'], ['kleinraeumiger_online_video_paid', 'Kleinräumiges Online Video Paid']], show: ['variant', 'addon_counts'], scopeCopy: 'Für lokale Nutzung reichen wenige Angaben zu Region und Erweiterungen.' },
		session_fee: { show: ['session_hours'], scopeCopy: 'Hier buchst Du reine Studiozeit ohne Nutzungsrechte.' }
	};
	var SCENARIO_TO_CASE = { online_audio_spot_unpaid: 'webvideo_imagefilm_praesentation_unpaid', online_video_spot_unpaid: 'webvideo_imagefilm_praesentation_unpaid', in_app_ads: 'werbung_mit_bild', telefon_werbespot: 'werbung_ohne_bild', marketing_elearning: 'webvideo_imagefilm_praesentation_unpaid', oeffentliches_elearning: 'webvideo_imagefilm_praesentation_unpaid', video_podcast: 'webvideo_imagefilm_praesentation_unpaid', podcast_sponsoring_audio: 'werbung_ohne_bild', podcast_sponsoring_video: 'werbung_mit_bild', werbliche_podcast_verpackung_audio: 'werbung_ohne_bild', werbliche_podcast_verpackung_video: 'werbung_mit_bild', lokaler_funkspot: 'kleinraeumig', werbliche_games_zusatznutzung: 'werbung_mit_bild' };
	var FIELD_DEFAULTS = { manual_offer_total: '', case_key: '', case_variant: '', usage_type: 'organic_branding', duration_term: '', territory: '', medium: '', package_key: '', duration_minutes: '', net_minutes: '', module_count: '', fah: '', recording_hours: '', recording_days: '1', same_day_projects: '1', additional_year: '0', additional_territory: '0', additional_motif: '0', prior_layout_fee: '0', session_hours: '0', project_title: '', customer_name: '', internal_notes: '', needs_cutdown: '0', archivgage: '0', layout_fee: '0', follow_up_usage: '0', is_nachgage: '0', is_paid_media: '0', usage_social_media: '0', usage_praesentation: '0', unlimited_time: '0', unlimited_territory: '0', unlimited_media: '0', reminder: '0', allongen: '0' };
	var NUMERIC_FIELDS = { duration_minutes: { min: 1, step: 0.1 }, net_minutes: { min: 1, step: 0.1 }, module_count: { min: 1, step: 1 }, fah: { min: 1, step: 0.5 }, recording_hours: { min: 1, step: 0.5 }, recording_days: { min: 1, step: 1 }, same_day_projects: { min: 1, step: 1 }, additional_year: { min: 0, step: 1 }, additional_territory: { min: 0, step: 1 }, additional_motif: { min: 0, step: 1 }, prior_layout_fee: { min: 0, step: 0.01 }, session_hours: { min: 1, step: 0.5 }, manual_offer_total: { min: 0, step: 0.01 } };
	var FIELD_LABELS = { case_key: 'Projektart', case_variant: 'Genauere Art', duration_minutes: 'Wie viele Minuten?', net_minutes: 'Sendeminuten (netto)', module_count: 'Anzahl Ansagen', fah: 'Fertige Hörbuch-Stunden', recording_hours: 'Stunden im Studio', recording_days: 'Tage im Studio', same_day_projects: 'Weitere Projekte am selben Tag', additional_year: 'Weitere Jahre dazu', additional_territory: 'Weitere Regionen dazu', additional_motif: 'Weitere Versionen dazu', duration_term: 'Wie lange soll es laufen?', territory: 'Wo wird es eingesetzt?', medium: 'Auf welchem Kanal?', session_hours: 'Stunden Studiozeit', archivgage: 'Später nochmal verwenden (Archiv)', follow_up_usage: 'Für spätere Wiederverwendung', is_nachgage: 'Laufzeit verlängern', prior_layout_fee: 'Bereits gezahltes Honorar', unlimited_time: 'Zeitlich unbegrenzt nutzen', unlimited_territory: 'Weltweit nutzen', unlimited_media: 'Auf allen Kanälen nutzen' };
	var BLOCK_FIELD_MAP = { variant: ['case_variant'], usage_type: ['usage_type'], media_toggles: ['is_paid_media', 'usage_social_media', 'usage_praesentation'], duration_term: ['duration_term'], territory: ['territory'], medium: ['medium'], duration_minutes: ['duration_minutes'], net_minutes: ['net_minutes'], module_count: ['module_count'], fah: ['fah'], recording_hours: ['recording_hours'], recording_days: ['recording_days'], same_day_projects: ['same_day_projects'], addon_counts: ['additional_year', 'additional_territory', 'additional_motif'], rights_toggles: ['archivgage', 'reminder', 'allongen', 'follow_up_usage', 'is_nachgage'], session_hours: ['session_hours'], prior_layout_fee: ['prior_layout_fee'], unlimited_usage: ['unlimited_time', 'unlimited_territory', 'unlimited_media'] };

	function htmlEscape(value) { return String(value == null ? '' : value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); }
	function parseJsonAttribute(node, attribute) { try { return JSON.parse(node.getAttribute(attribute) || '{}'); } catch (error) { return {}; } }
	function labelFromKey(value) { return String(value || '').replace(/_/g, ' ').replace(/\b\w/g, function (m) { return m.toUpperCase(); }); }
	function clone(obj) { return JSON.parse(JSON.stringify(obj || {})); }
	function storageAvailable() { try { localStorage.setItem('__sgk_test__', '1'); localStorage.removeItem('__sgk_test__'); return true; } catch (error) { return false; } }
	function currency(value) { return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(Number(value || 0)); }
	function parseCurrencyToNumber(value) {
		if (!value) { return null; }
		var normalized = String(value).replace(/[^\d,.-]/g, '').replace(/\./g, '').replace(',', '.');
		var parsed = parseFloat(normalized);
		return isNaN(parsed) ? null : parsed;
	}
	function normalizeNumber(value) { var parsed = parseFloat(String(value || '').replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, '')); return isNaN(parsed) ? null : parsed; }
	function todayIso() { return new Date().toISOString().slice(0, 10); }
	function formatDate(value) { if (!value) { return ''; } return new Date(value).toLocaleDateString('de-DE'); }
	function isTruthy(value) { return value === true || value === 1 || value === '1'; }
	function matchesAny(value, list) { return list.indexOf(value) !== -1; }
	function caseConfig(cases, selectedCase) { return cases[selectedCase] || cases[SCENARIO_TO_CASE[selectedCase]] || null; }
	function effectiveCaseKey(cases, selectedCase) { return cases[selectedCase] ? selectedCase : (SCENARIO_TO_CASE[selectedCase] || selectedCase); }
	function fieldNode(form, name) { return form.querySelector('[name="' + name + '"]'); }
	function setFieldValue(field, value) { if (!field) { return; } if (field.type === 'checkbox') { field.checked = isTruthy(value); } else { field.value = value == null ? '' : String(value); } }
	function getFieldValue(field) { if (!field) { return ''; } return field.type === 'checkbox' ? (field.checked ? '1' : '0') : field.value; }

	function serializeForm(form) {
		var payload = {};
		Object.keys(FIELD_DEFAULTS).forEach(function (key) {
			var field = fieldNode(form, key);
			payload[key] = field ? getFieldValue(field) : FIELD_DEFAULTS[key];
		});
		return payload;
	}

	function resetFields(form, fields) {
		fields.forEach(function (name) {
			var field = fieldNode(form, name);
			if (!field) { return; }
			setFieldValue(field, FIELD_DEFAULTS.hasOwnProperty(name) ? FIELD_DEFAULTS[name] : '');
		});
	}

	function fillForm(form, data) {
		Object.keys(FIELD_DEFAULTS).forEach(function (key) {
			var nextValue = data && data.hasOwnProperty(key) ? data[key] : FIELD_DEFAULTS[key];
			setFieldValue(fieldNode(form, key), nextValue);
		});
	}

	function optionLabel(value) {
		var labels = { organic_branding: 'Eigene Kanäle (Website, Social Media)', paid_advertising: 'Bezahlte Werbung (Ads, Kampagnen)', tv: 'TV', ctv: 'CTV', online_video: 'Online Video', kino: 'Kino', pos: 'POS', event: 'Event', messe: 'Messe', radio: 'Radio', online_audio: 'Online Audio', ladenfunk: 'Ladenfunk', ladenfunk_regional: 'Ladenfunk regional', telefon: 'Telefon', regional: 'Regional', lokal: 'Lokal', de: 'Deutschland', dach: 'Deutschland, Österreich, Schweiz', eu: 'Europa', weltweit: 'Weltweit', '1_jahr': '1 Jahr', '2_jahre': '2 Jahre', archiv: 'Archiv', unbegrenzt: 'Unbegrenzt', zeitlich_unbegrenzt: 'Zeitlich unbegrenzt' };
		return labels[value] || labelFromKey(value);
	}

	function buildSelectOptions(select, values, placeholder) {
		if (!select) { return; }
		var current = select.value;
		select.innerHTML = '<option value="">' + htmlEscape(placeholder || 'Bitte auswählen') + '</option>' + (values || []).map(function (value) { return '<option value="' + htmlEscape(value) + '">' + htmlEscape(optionLabel(value)) + '</option>'; }).join('');
		if (current && matchesAny(current, values || [])) { select.value = current; }
	}

	function summarizeSelection(value) { return value ? optionLabel(value) : 'Noch offen'; }
	function buildScopeGuidance(formData, ui) {
		var effectiveCase = ui.effectiveCase;
		var scopeBlocks = ['duration_minutes', 'net_minutes', 'module_count', 'fah', 'recording_hours', 'recording_days', 'same_day_projects', 'session_hours'];
		var hasConcreteScope = scopeBlocks.some(function (block) { return ui.visibleBlocks.indexOf(block) !== -1; });
		if (hasConcreteScope) { return ((CASE_UI[effectiveCase] && CASE_UI[effectiveCase].scopeCopy) || 'Die passenden Angaben hängen von Deiner Projektart ab.') + ' Trage nur die Punkte ein, die für Dein Projekt wirklich relevant sind.'; }
		switch (effectiveCase) {
			case 'werbung_mit_bild':
			case 'werbung_ohne_bild':
			case 'kleinraeumig':
				return 'Hier brauchst Du keine zusätzliche Mengenangabe. Die Empfehlung ergibt sich aus Art, Region, Laufzeit und Kanal.';
			case 'podcast':
				return 'Für diesen Podcast-Fall brauchst Du hier keine weiteren Mengenangaben.';
			default:
				return ((CASE_UI[effectiveCase] && CASE_UI[effectiveCase].scopeCopy) || 'Für diesen Fall brauchst Du aktuell keine weiteren Umfangsangaben.') + ' Die Empfehlung basiert auf Deinen bisherigen Projekt- und Rechteangaben.';
		}
	}

	function updateGuidanceSummary(app, formData, ui) {
		var rightsNode = app.querySelector('[data-sgk-rights-summary]');
		var journeyNode = app.querySelector('[data-sgk-journey-summary]');
		var caseLabel = formData.case_key ? labelFromKey(formData.case_key) : 'Noch nicht gewählt';
		var rightsParts = [];
		var activeRightsToggles = [];
		if (ui.caseConfig && (ui.caseConfig.allowed_territories || []).length) { rightsParts.push('Gebiet: ' + summarizeSelection(formData.territory)); }
		if (ui.caseConfig && (ui.caseConfig.allowed_durations || []).length) { rightsParts.push('Laufzeit: ' + summarizeSelection(formData.duration_term)); }
		if (ui.caseConfig && (ui.caseConfig.allowed_media || []).length) { rightsParts.push('Medium: ' + summarizeSelection(formData.medium)); }
		if (isTruthy(formData.unlimited_time) || isTruthy(formData.unlimited_territory) || isTruthy(formData.unlimited_media)) { rightsParts.push('Unlimited-Option aktiv'); }
		if ((normalizeNumber(formData.additional_year) || 0) > 0 || (normalizeNumber(formData.additional_territory) || 0) > 0 || (normalizeNumber(formData.additional_motif) || 0) > 0) {
			rightsParts.push('Erweiterungen aktiv');
		}
		[['archivgage','Archivnutzung'],['reminder','Reminder'],['allongen','Allongen'],['follow_up_usage','Nachnutzung'],['is_nachgage','Nachbuchung / Lizenzverlängerung']].forEach(function (item) {
			if (isTruthy(formData[item[0]])) { activeRightsToggles.push(item[1]); }
		});
		if (activeRightsToggles.length) { rightsParts.push(activeRightsToggles.join(' · ')); }
		if (rightsNode) {
			rightsNode.innerHTML = '<h4 class="sgk-guidance-title">Rechtekompass</h4><div class="src-rights-summary-list">' +
				'<div class="src-rights-summary-row"><span>Gebiet:</span><strong>' + htmlEscape(summarizeSelection(formData.territory)) + '</strong></div>' +
				'<div class="src-rights-summary-row"><span>Laufzeit:</span><strong>' + htmlEscape(summarizeSelection(formData.duration_term)) + '</strong></div>' +
				'<div class="src-rights-summary-row"><span>Medium:</span><strong>' + htmlEscape(summarizeSelection(formData.medium)) + '</strong></div>' +
				'<div class="src-rights-summary-row"><span>Aktiv:</span><strong>' + htmlEscape(rightsParts.slice(3).join(' · ') || '—') + '</strong></div>' +
				'</div>';
		}
		if (journeyNode) {
			var scopeSummary = 'Noch offen';
			['duration_minutes', 'net_minutes', 'module_count', 'fah', 'recording_hours', 'session_hours'].some(function (field) {
				var value = formData[field];
				if (value && value !== '0') {
					scopeSummary = (FIELD_LABELS[field] || labelFromKey(field)) + ': ' + value;
					return true;
				}
				return false;
			});
			journeyNode.innerHTML = '<h4 class="sgk-guidance-title">Projektstatus</h4>' +
				'<div class="src-tower-journey-item"><span>Projekt:</span><strong>' + htmlEscape(caseLabel) + '</strong></div>' +
				'<div class="src-tower-journey-item"><span>Rechte:</span><strong>' + htmlEscape(rightsParts.length ? rightsParts.slice(0, 3).join(' · ') : 'Wird nach den Rechteeingaben ergänzt') + '</strong></div>' +
				'<div class="src-tower-journey-item"><span>Umfang:</span><strong>' + htmlEscape(scopeSummary) + '</strong></div>';
		}
	}

	function normalizePersistedEntry(entry, cases) {
		if (!entry || entry.version !== STORAGE_VERSION || !entry.formData) { return null; }
		var data = clone(FIELD_DEFAULTS);
		Object.keys(FIELD_DEFAULTS).forEach(function (key) { if (entry.formData.hasOwnProperty(key)) { data[key] = entry.formData[key]; } });
		if (!caseConfig(cases, data.case_key)) { return null; }
		return { id: entry.id, version: STORAGE_VERSION, savedAt: entry.savedAt, projectTitle: entry.projectTitle, formData: data, result: entry.result || null, exportPayload: entry.exportPayload || null };
	}

	function getSavedCalculations(cases) {
		if (!storageAvailable()) { return []; }
		try {
			var entries = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
			entries = Array.isArray(entries) ? entries : [];
			return entries.map(function (entry) { return normalizePersistedEntry(entry, cases); }).filter(Boolean);
		} catch (error) { return []; }
	}
	function setSavedCalculations(entries) { if (!storageAvailable()) { return false; } localStorage.setItem(STORAGE_KEY, JSON.stringify(entries)); return true; }
	function buildSavedLabel(entry) { return (entry.projectTitle || 'Gespeicherte Kalkulation') + ' · ' + new Date(entry.savedAt).toLocaleString('de-DE'); }

	function validateManualOffer(value, result) { if (value == null || value === '') { return { valid: false, message: 'Für ein finales Angebot hinterlege bitte eine konkrete Angebotssumme.' }; } if (value <= 0) { return { valid: false, message: 'Bitte trage eine positive Angebotssumme ein.' }; } if (result && result.totals && value < result.totals.lower * 0.25) { return { valid: false, message: 'Die eingetragene Angebotssumme liegt deutlich unter der empfohlenen Spanne.' }; } return { valid: true, message: 'Die Angebotssumme wird separat übernommen, ohne die Empfehlung zu verändern.' }; }
	function setButtonFeedback(trigger, successLabel, fallbackLabel, duration) { if (!trigger) { return; } var label = successLabel || trigger.getAttribute('data-feedback-label') || 'Erledigt'; var resetDelay = duration || 1800; if (!trigger.hasAttribute('data-original-label')) { trigger.setAttribute('data-original-label', trigger.textContent.trim()); } trigger.textContent = label; trigger.classList.add('is-feedback'); window.clearTimeout(trigger.__sgkFeedbackTimer); trigger.__sgkFeedbackTimer = window.setTimeout(function () { trigger.textContent = fallbackLabel || trigger.getAttribute('data-original-label') || trigger.getAttribute('data-label') || trigger.textContent; trigger.classList.remove('is-feedback'); }, resetDelay); }
	function setStatusMessage(node, message, tone) { if (!node) { return; } node.textContent = message || ''; node.classList.remove('is-success', 'is-error'); if (tone === 'success') { node.classList.add('is-success'); }
		if (tone === 'error') { node.classList.add('is-error'); } }
	function copyText(text, trigger) { if (!text) { return Promise.reject(new Error('empty')); } var promise = navigator.clipboard && navigator.clipboard.writeText ? navigator.clipboard.writeText(text) : Promise.reject(new Error('clipboard-unavailable')); return promise.then(function () { setButtonFeedback(trigger, trigger && trigger.getAttribute('data-feedback-label') || 'Kopiert'); return true; }); }
	function buildExportPayload(result, formData, offerMeta) { var payload = clone(result.export_payload || {}); payload.summary = payload.summary || {}; payload.summary.project_title = formData.project_title || ''; payload.summary.customer_name = formData.customer_name || ''; payload.summary.display_title = result.display_title || ''; payload.summary.generated_at = new Date().toISOString(); payload.calculation_meta = payload.calculation_meta || {}; payload.calculation_meta.internal_notes = formData.internal_notes || ''; payload.calculation_meta.source_form = formData; payload.calculation_meta.offer_meta = offerMeta || {}; return payload; }
	function prettifyRouteMessage(message) { var raw = String(message || ''); if (!raw) { return 'Die Auswahl wurde passend für die Kalkulation eingeordnet.'; } return raw.replace(/Resolver/gi, 'Auswahl').replace(/Berechnungsengine/gi, 'Kalkulation').replace(/normalisiert/gi, 'geordnet').replace(/suppressed invalid paths/gi, 'nicht passende Varianten').replace(/Redirect aktiv/gi, 'Diese Auswahl wird').replace(/fachlich sauber/gi, 'passend').replace(/Berechnungspfad/gi, 'Einordnung').replace(/route trace/gi, 'Einordnung').replace(/aktivierte Regeln/gi, 'berücksichtigte Auswahl').replace(/Resolver-Logik/gi, 'Zuordnung').trim(); }
	function routeLabel(step, label) { var map = { resolver: 'Auswahl', normalization: 'Auswahl', redirect: 'Einordnung', suppressed_invalid_path: 'Bereinigt', case: 'Projektart' }; return map[step] || label || 'Schritt'; }

	function buildCopyBlocks(result, formData, offerMeta) {
		var exportPayload = buildExportPayload(result, formData, offerMeta);
		var texts = exportPayload.export_text_blocks || {};
		var projectLine = offerMeta && offerMeta.offer_number ? ('Angebot ' + offerMeta.offer_number) : 'Angebot Sprecherhonorar';
		return { summary: [projectLine, texts.offer_headline || ('Angebot Sprecherhonorar – ' + (formData.project_title || result.display_title || 'Projekt')), texts.copy_summary || '', formData.customer_name ? ('Kunde: ' + formData.customer_name) : '', offerMeta && offerMeta.offer_date ? ('Datum: ' + formatDate(offerMeta.offer_date)) : '', texts.manual_offer_notice || ''].filter(Boolean).join('\n'), positions: texts.positions_block || '', rights: texts.rights_block || '', json: JSON.stringify(exportPayload, null, 2), mail: [offerMeta && offerMeta.intro_text ? offerMeta.intro_text : 'Vielen Dank für Deine Anfrage. Nachfolgend erhältst Du Dein Angebot.', '', texts.offer_headline || '', texts.copy_summary || '', texts.positions_block || '', '', texts.rights_block || '', '', texts.notes_block || '', '', texts.legal_notice_block || ''].filter(Boolean).join('\n') };
	}
	function getOfferMeta(app, formData) { var meta = { offer_date: todayIso() }; app.querySelectorAll('[data-sgk-offer-meta]').forEach(function (field) { meta[field.getAttribute('data-sgk-offer-meta')] = field.value || ''; }); if (!meta.contact_name) { meta.contact_name = formData.customer_name || ''; } if (!meta.offer_date) { meta.offer_date = todayIso(); } return meta; }
	function documentStyles() { return ['body{font-family:"Rubik", sans-serif;background:#eef4fb;margin:0;color:#0f172a;}', '.doc{max-width:980px;margin:0 auto;padding:32px;}', '.sheet{background:#fff;border-radius:28px;padding:40px;box-shadow:0 24px 60px rgba(15,23,42,.12);}', '.header,.meta-grid,.summary-grid,.positions,.rights,.notes,.footer{display:grid;gap:16px;}', '.header-top{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;border-bottom:1px solid #dbe6f1;padding-bottom:20px;}', '.logo{width:60px;height:60px;border-radius:18px;background:linear-gradient(180deg,#1a93ee,#0f141a);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;}', '.eyebrow{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#1a93ee;font-weight:700;margin:0 0 8px;}', 'h1,h2,h3,h4,p{margin:0;}', '.headline h1{font-size:30px;line-height:1.05;margin-bottom:8px;}', '.meta-grid{grid-template-columns:repeat(4,minmax(0,1fr));margin-top:24px;}', '.meta-card,.summary-card,.section{border:1px solid #dbe6f1;border-radius:20px;padding:18px;background:#f9fbff;}', '.summary-grid{grid-template-columns:2fr 1fr 1fr; margin:24px 0;}', '.summary-card--total{background:linear-gradient(180deg,#0f141a,#162131);color:#fff;border-color:#0f141a;}', '.section-title{font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-bottom:10px;font-weight:700;}', '.position-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:14px;padding:14px 0;border-top:1px solid #dbe6f1;}', '.position-row:first-child{border-top:0;padding-top:0;}', '.position-row small,.muted{color:#64748b;display:block;line-height:1.5;}', '.badge{display:inline-block;padding:6px 10px;border-radius:999px;background:#eaf4fe;color:#116fb3;font-size:12px;font-weight:600;}', '.footer{margin-top:28px;padding-top:18px;border-top:1px solid #dbe6f1;font-size:13px;color:#64748b;}', '@media print{body{background:#fff}.doc{max-width:none;padding:0}.sheet{box-shadow:none;border-radius:0;padding:24px}}'].join(''); }
	function buildOfferDocumentData(result, formData, offerMeta) { var exportPayload = buildExportPayload(result, formData, offerMeta); var documentPayload = clone(exportPayload.document_payload || {}); documentPayload.meta = offerMeta; documentPayload.form = formData; documentPayload.exportPayload = exportPayload; return documentPayload; }

	function renderOfferPreview(result, formData, offerMeta) { var documentData = buildOfferDocumentData(result, formData, offerMeta); var exportPayload = documentData.exportPayload; var summary = exportPayload.summary || {}; var positions = Array.isArray(exportPayload.positions) ? exportPayload.positions : []; var rights = Array.isArray(exportPayload.rights_overview) ? exportPayload.rights_overview : []; var notes = Array.isArray(exportPayload.notes_for_offer) ? exportPayload.notes_for_offer : []; var legal = Array.isArray(exportPayload.legal_notice) ? exportPayload.legal_notice : []; var alternatives = Array.isArray(exportPayload.alternative_packages) ? exportPayload.alternative_packages : []; var breakdown = Array.isArray(exportPayload.breakdown_sections) ? exportPayload.breakdown_sections : []; var totalText = exportPayload.manual_offer_total != null ? safeCurrency(exportPayload.manual_offer_total, 'Noch offen') : 'Noch offen'; var midText = exportPayload.recommended_mid != null ? safeCurrency(exportPayload.recommended_mid, '—') : '—'; var rangeText = exportPayload.recommended_range ? safeCurrency(exportPayload.recommended_range.lower, '—') + ' – ' + safeCurrency(exportPayload.recommended_range.upper, '—') : '—'; return { html: '<style>' + documentStyles() + '</style>' + '<div class="doc"><div class="sheet">' + '<div class="header"><div class="header-top"><div style="display:flex;gap:16px;align-items:flex-start;"><div class="logo">SGK</div><div class="headline"><p class="eyebrow">Professionelles Angebotsdokument</p><h1>Angebot Sprecherhonorar</h1><p>' + htmlEscape(formData.project_title || summary.display_title || summary.title || 'Projektangebot') + '</p></div></div><div style="text-align:right;"><span class="badge">' + htmlEscape(offerMeta.offer_number || 'Angebot') + '</span><p class="muted" style="margin-top:10px;">' + htmlEscape(formatDate(offerMeta.offer_date)) + '</p></div></div><div class="meta-grid"><div class="meta-card"><div class="section-title">Kunde</div><strong>' + htmlEscape(formData.customer_name || 'Noch nicht angegeben') + '</strong><small class="muted">' + htmlEscape(offerMeta.contact_name || 'Ansprechpartner optional') + '</small></div><div class="meta-card"><div class="section-title">Projekt</div><strong>' + htmlEscape(formData.project_title || summary.display_title || summary.title || 'Berechnung') + '</strong><small class="muted">Projektart: ' + htmlEscape(summary.case_label || summary.display_title || summary.title || 'Projekt') + '</small></div><div class="meta-card"><div class="section-title">Absender</div><strong>' + htmlEscape(offerMeta.sender_company || 'Studio / Absender') + '</strong><small class="muted">' + htmlEscape([offerMeta.sender_email, offerMeta.sender_phone].filter(Boolean).join(' · ')) + '</small></div><div class="meta-card"><div class="section-title">Dokument</div><strong>' + htmlEscape(offerMeta.offer_number || 'Ohne Nummer') + '</strong><small class="muted">Stand ' + htmlEscape(formatDate(offerMeta.offer_date)) + '</small></div></div></div>' + '<div class="summary-grid"><div class="summary-card"><div class="section-title">Angebotsbasis</div><p>' + htmlEscape(offerMeta.intro_text || 'Vielen Dank für Deine Anfrage. Nachfolgend erhältst Du Dein Angebot auf Basis der abgestimmten Nutzung und der aktuellen Preisermittlung.') + '</p><small class="muted" style="margin-top:10px;">Untervariante: ' + htmlEscape(summary.sub_variant || 'Standard') + '</small></div><div class="summary-card"><div class="section-title">Errechnete Spanne</div><strong>' + htmlEscape(rangeText) + '</strong><small class="muted">Mittelwert ' + htmlEscape(midText) + '</small></div><div class="summary-card summary-card--total"><div class="section-title" style="color:rgba(255,255,255,.72)">Finale Angebotssumme</div><strong style="font-size:28px;display:block;">' + htmlEscape(totalText) + '</strong><small>' + htmlEscape(exportPayload.manual_offer_total != null ? 'Manuell gesetzt und als Angebotswert übernommen.' : 'Bitte vor PDF-Ausgabe final festlegen.') + '</small></div></div>' + '<div class="section"><div class="section-title">Rechenweg</div>' + (breakdown.length ? breakdown.map(function (section) { var items = Array.isArray(section.items) ? section.items : []; return '<div style="padding:12px 0;border-top:1px solid #dbe6f1;"><strong>' + htmlEscape(section.label || 'Abschnitt') + '</strong><small class="muted">' + htmlEscape(section.description || '') + '</small>' + (items.length ? items.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.label || 'Position') + '</strong><small>' + htmlEscape(item.quantity_label || '') + (item.note ? ' · ' + htmlEscape(item.note) : '') + '</small></div><div><strong>' + htmlEscape((item.formatted && item.formatted.low_mid_high) || '—') + '</strong></div></div>'; }).join('') : '<p class="muted">Keine Einträge.</p>') + '</div>'; }).join('') : '<p class="muted">Der Rechenweg ist aktuell nicht verfügbar.</p>') + '</div>' + '<div class="section positions"><div class="section-title">Angebotspositionen</div>' + positions.map(function (item) { var price = item.manuell_uebernommener_preis != null ? safeCurrency(item.manuell_uebernommener_preis, '—') : safeCurrency(item.empfohlener_preis, '—'); return '<div class="position-row"><div><strong>' + htmlEscape(item.position_number + '. ' + item.titel) + '</strong><small>' + htmlEscape(item.beschreibung || '') + '</small><small>' + htmlEscape((item.kategorie || '') + (item.lizenzbezug ? ' · ' + item.lizenzbezug : '')) + '</small></div><div><strong>' + htmlEscape(price) + '</strong></div></div>'; }).join('') + '</div>' + '<div class="section rights"><div class="section-title">Nutzungsrechte & Lizenzen</div>' + (rights.length ? rights.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.title + (item.variant ? ' · ' + item.variant : '')) + '</strong><small>Laufzeit: ' + htmlEscape(item.duration) + ' · Territorium: ' + htmlEscape(item.territory) + ' · Medien: ' + htmlEscape(item.media) + '</small></div><div><span class="badge">Rechteblock</span></div></div>'; }).join('') : '<p class="muted">Keine zusätzlichen Rechteinformationen vorhanden.</p>') + '</div>' + '<div class="section notes"><div class="section-title">Hinweise & Anmerkungen</div>' + (notes.length ? notes.map(function (item, index) { return '<p style="padding:10px 0;' + (index ? 'border-top:1px solid #dbe6f1;' : '') + '">' + htmlEscape(item) + '</p>'; }).join('') : '<p class="muted">Keine zusätzlichen Hinweise.</p>') + '</div>' + (legal.length ? '<div class="section"><div class="section-title">Rechtlicher Hinweis</div>' + legal.map(function (item) { return '<p>' + htmlEscape(item) + '</p>'; }).join('') + '</div>' : '') + (alternatives.length ? '<div class="section"><div class="section-title">Optionale Paket-Alternativen</div>' + alternatives.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.label || 'Alternative') + '</strong></div><div><strong>' + htmlEscape(item.formatted_totals ? (item.formatted_totals.low_mid_high || item.formatted_totals.mid) : '') + '</strong></div></div>'; }).join('') + '</div>' : '') + '<div class="footer"><div>' + htmlEscape(offerMeta.footer_text || [offerMeta.sender_company, offerMeta.sender_email, offerMeta.sender_phone].filter(Boolean).join(' · ')) + '</div><div>Dieses Dokument basiert auf der aktuellen Preisermittlung im Sprecher-Gagenrechner. Interne Angaben werden im Kundendokument nicht ausgegeben.</div></div>' + '</div></div>', text: buildCopyBlocks(result, formData, offerMeta).mail, validation: validateManualOffer(exportPayload.manual_offer_total, result) }; }
	function openPrintDocument(previewHtml) { var printWindow = window.open('', '_blank', 'noopener,noreferrer,width=1280,height=900'); if (!printWindow) { return false; } printWindow.document.open(); printWindow.document.write('<!doctype html><html><head><meta charset="utf-8"><title>Angebot Sprecherhonorar</title></head><body>' + previewHtml + '</body></html>'); printWindow.document.close(); printWindow.focus(); setTimeout(function () { printWindow.print(); }, 250); return true; }

	function activeVariantHint(effectiveCase, value, caseConfig) {
		var visualConfig = CASE_UI[effectiveCase] || {};
		var fromUi = (visualConfig.variantOptions || []).find(function (item) { return item[0] === value && item[2]; });
		if (fromUi) { return fromUi[2]; }
		var rules = (caseConfig && caseConfig.variant_visibility_rules) || {};
		return rules[value] && rules[value].ui_hint ? rules[value].ui_hint : '';
	}

	function populateVariants(form, effectiveCase, caseConfig) {
		var select = fieldNode(form, 'case_variant');
		if (!select) { return; }
		var hint = form.querySelector('[data-sgk-variant-hint]');
		var config = CASE_UI[effectiveCase] || {};
		var options = config.variantOptions || [];
		var current = select.value;
		select.innerHTML = '';
		if (!options.length) {
			select.innerHTML = '<option value="">Automatisch passend auswählen</option>';
			buildVariantButtons(form, []);
			if (hint) { hint.textContent = 'Für diese Projektart ist keine zusätzliche Auswahl nötig.'; }
			return;
		}
		select.innerHTML = '<option value="">Bitte Variante wählen</option>' + options.map(function (item) { return '<option value="' + htmlEscape(item[0]) + '">' + htmlEscape(item[1]) + '</option>'; }).join('');
		if (current && options.some(function (item) { return item[0] === current; })) { select.value = current; }
		buildVariantButtons(form, options);
		syncSegmentedControl(form.querySelector('[data-sgk-variant-control]'), select.value);
		if (hint) { hint.textContent = activeVariantHint(effectiveCase, select.value || (options[0] && options[0][0]) || '', caseConfig) || 'Die Varianten werden passend zur Projektart angeboten.'; }
	}
	function buildVariantButtons(form, options) { var control = form.querySelector('[data-sgk-variant-control]'); if (!control) { return; } control.innerHTML = options.map(function (item, index) { return '<button type="button" class="src-segment-btn" data-sgk-segment-value="' + htmlEscape(item[0]) + '" title="' + htmlEscape(item[2] || '') + '">' + htmlEscape(item[1] || ('Variante ' + (index + 1))) + '</button>'; }).join(''); }
	function syncSegmentedControl(control, value) { if (!control) { return; } control.querySelectorAll('[data-sgk-segment-value]').forEach(function (button) { button.classList.toggle('is-active', button.getAttribute('data-sgk-segment-value') === value); }); }
	function updateCaseContext(app, selectedCase, cases) { var node = app.querySelector('[data-sgk-case-context]'); app.querySelectorAll('[data-sgk-quick-case]').forEach(function (button) { button.classList.toggle('is-active', button.getAttribute('data-sgk-quick-case') === selectedCase); }); if (!node) { return; } if (!selectedCase) { node.hidden = true; node.innerHTML = ''; return; } var effectiveCase = effectiveCaseKey(cases, selectedCase); var caseData = cases[effectiveCase] || {}; node.hidden = false; node.innerHTML = '<strong>' + htmlEscape(caseData.label || labelFromKey(selectedCase)) + '</strong><p>' + htmlEscape(caseData.description || 'Die Eingaben wurden passend zu deiner Auswahl zusammengestellt.') + '</p>'; }
	function updateExpertBadges(app, uiState) { var container = app.querySelector('[data-sgk-expert-badges]'); var flags = (uiState && uiState.available_expert_options) || []; if (!container) { return; } container.innerHTML = !flags.length ? '<span class="src-inline-badge is-muted">Noch keine zusätzlichen Optionen aktiv</span>' : flags.map(function (flag) { return '<span class="src-inline-badge">' + htmlEscape(labelFromKey(flag)) + '</span>'; }).join(''); }
	function refreshSavedList(container, cases) { var select = container.querySelector('[data-sgk-saved-list]'); if (!select) { return; } var entries = getSavedCalculations(cases); select.innerHTML = '<option value="">Bitte auswählen</option>' + entries.map(function (entry) { return '<option value="' + htmlEscape(entry.id) + '">' + htmlEscape(buildSavedLabel(entry)) + '</option>'; }).join(''); }
	function updateRedirectBanner(app, payload) { var banner = app.querySelector('[data-sgk-redirect-banner]'); if (!banner) { return; } var uiState = (payload && payload.ui_state) || {}; var result = (payload && payload.result) || {}; var warnings = Array.isArray(result.warnings) ? result.warnings : []; var message = ''; if (uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) { message = 'Smart Match: Automatisch zugeordnet – ' + labelFromKey(uiState.resolved_case) + '.'; } else if (warnings.length) { message = 'Smart Match: ' + warnings.join(' · '); } if (!message) { banner.hidden = true; banner.innerHTML = ''; return; } banner.hidden = false; banner.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i><div><strong>Smart Match: Automatisch zugeordnet</strong><p>' + htmlEscape(message) + '</p></div>'; }

	function deriveUiState(formData, cases) {
		var selectedCase = formData.case_key;
		var effectiveCase = effectiveCaseKey(cases, selectedCase);
		var resolvedCaseConfig = caseConfig(cases, selectedCase) || {};
		var visualConfig = CASE_UI[effectiveCase] || {};
		var visibleBlocks = (visualConfig.show || []).concat(['scope_note']);
		var variantVisibilityRules = resolvedCaseConfig.variant_visibility_rules || {};
		var activeVariant = formData.case_variant || (resolvedCaseConfig.allowed_variants || [])[0] || '';
		if (effectiveCase === 'webvideo_imagefilm_praesentation_unpaid' && !formData.case_variant) {
			activeVariant = 'imagefilm_webvideo_praesentation';
		}
		var variantRule = variantVisibilityRules[activeVariant] || null;
		if (variantRule && Array.isArray(variantRule.show_blocks)) {
			variantRule.show_blocks.forEach(function (block) { if (visibleBlocks.indexOf(block) === -1) { visibleBlocks.push(block); } });
		}
		var requiredFields = ['case_key'];
		(visibleBlocks || []).forEach(function (block) { (BLOCK_FIELD_MAP[block] || []).forEach(function (field) { if (requiredFields.indexOf(field) === -1) { requiredFields.push(field); } }); });
		requiredFields = requiredFields.filter(function (field) {
			if (field === 'usage_type') { return false; }
			if (field.indexOf('usage_') === 0 || field === 'is_paid_media' || field === 'follow_up_usage' || field === 'archivgage' || field === 'reminder' || field === 'allongen') { return false; }
			if (field === 'additional_year' || field === 'additional_territory' || field === 'additional_motif' || field === 'prior_layout_fee') { return false; }
			if (field === 'duration_term' && !(resolvedCaseConfig.allowed_durations || []).length) { return false; }
			if (field === 'territory' && !(resolvedCaseConfig.allowed_territories || []).length) { return false; }
			if (field === 'medium' && !(resolvedCaseConfig.allowed_media || []).length) { return false; }
			if (field === 'case_variant' && !(resolvedCaseConfig.allowed_variants || []).length && !(visualConfig.variantOptions || []).length) { return false; }
			return true;
		});
		if ((resolvedCaseConfig.validation_rules && Array.isArray(resolvedCaseConfig.validation_rules.required))) {
			resolvedCaseConfig.validation_rules.required.forEach(function (field) { if (requiredFields.indexOf(field) === -1) { requiredFields.push(field); } });
		}
		if (variantRule && Array.isArray(variantRule.required)) {
			variantRule.required.forEach(function (field) { if (requiredFields.indexOf(field) === -1) { requiredFields.push(field); } });
		}
		var usageBlocks = ['variant', 'usage_type'];
		var rightsBlocks = ['territory', 'duration_term', 'medium'];
		var visibleUsageBlocks = usageBlocks.filter(function (block) { return visibleBlocks.indexOf(block) !== -1; });
		var visibleRightsBlocks = rightsBlocks.filter(function (block) { return visibleBlocks.indexOf(block) !== -1; }).filter(function (block) {
			if (block === 'territory') { return (resolvedCaseConfig.allowed_territories || []).length > 0; }
			if (block === 'duration_term') { return (resolvedCaseConfig.allowed_durations || []).length > 0; }
			if (block === 'medium') { return (resolvedCaseConfig.allowed_media || []).length > 0; }
			return false;
		});
		return { selectedCase: selectedCase, effectiveCase: effectiveCase, caseConfig: resolvedCaseConfig, visibleBlocks: visibleBlocks, requiredFields: requiredFields, visibleUsageBlocks: visibleUsageBlocks, visibleRightsBlocks: visibleRightsBlocks };
	}

	function isCaseFieldAllowed(ui, fieldName, formData) {
		var caseConfig = ui.caseConfig || {};
		var variant = (formData && formData.case_variant) || (caseConfig.allowed_variants || [])[0] || '';
		if (fieldName === 'follow_up_usage' || fieldName === 'prior_layout_fee') {
			var followUpRules = caseConfig.follow_up_credit_rules || {};
			if (!followUpRules.allowed) { return false; }
			return !Array.isArray(followUpRules.allowed_variants) || !followUpRules.allowed_variants.length || matchesAny(variant, followUpRules.allowed_variants);
		}
		if (fieldName === 'unlimited_time' || fieldName === 'unlimited_territory' || fieldName === 'unlimited_media') {
			var unlimitedRules = caseConfig.unlimited_usage_rules || {};
			if (!unlimitedRules.allowed) { return false; }
			if (String(variant || '').indexOf('patronat') !== -1) { return false; }
			return !Array.isArray(unlimitedRules.allowed_variants) || !unlimitedRules.allowed_variants.length || matchesAny(variant, unlimitedRules.allowed_variants);
		}
		if (caseConfig.additive_rules && caseConfig.additive_rules[fieldName] && Array.isArray(caseConfig.additive_rules[fieldName].allowed_variants) && caseConfig.additive_rules[fieldName].allowed_variants.length) {
			return matchesAny(variant, caseConfig.additive_rules[fieldName].allowed_variants);
		}
		return true;
	}

	function coerceFieldValue(field, value) {
		if (value == null) { return ''; }
		if (field && field.type === 'checkbox') { return isTruthy(value) ? '1' : '0'; }
		return String(value);
	}
	function normalizeFormData(form, cases) {
		var raw = serializeForm(form);
		var ui = deriveUiState(raw, cases);
		var normalized = clone(FIELD_DEFAULTS);
		Object.keys(raw).forEach(function (key) { normalized[key] = coerceFieldValue(fieldNode(form, key), raw[key]); });
		var allowed = ['case_key', 'project_title', 'customer_name', 'internal_notes', 'manual_offer_total'];
		ui.requiredFields.forEach(function (key) { if (allowed.indexOf(key) === -1) { allowed.push(key); } });
		ui.visibleBlocks.forEach(function (block) { (BLOCK_FIELD_MAP[block] || []).forEach(function (key) { if (allowed.indexOf(key) === -1) { allowed.push(key); } }); });
		['prior_layout_fee', 'session_hours', 'unlimited_time', 'unlimited_territory', 'unlimited_media'].forEach(function (key) {
			if (ui.caseConfig && Array.isArray(ui.caseConfig.expert_options) && ui.caseConfig.expert_options.length && allowed.indexOf(key) === -1) { allowed.push(key); }
		});
		Object.keys(normalized).forEach(function (key) {
			if (allowed.indexOf(key) === -1) { normalized[key] = FIELD_DEFAULTS[key]; }
		});
		if (ui.caseConfig.allowed_variants && ui.caseConfig.allowed_variants.length && !matchesAny(normalized.case_variant, ui.caseConfig.allowed_variants)) { normalized.case_variant = ''; }
		if (ui.effectiveCase === 'webvideo_imagefilm_praesentation_unpaid' && !normalized.case_variant) { normalized.case_variant = 'imagefilm_webvideo_praesentation'; }
		if (ui.caseConfig.allowed_durations && ui.caseConfig.allowed_durations.length && !matchesAny(normalized.duration_term, ui.caseConfig.allowed_durations)) { normalized.duration_term = ui.caseConfig.duration_rules && ui.caseConfig.duration_rules.default_term ? ui.caseConfig.duration_rules.default_term : ''; }
		if (ui.caseConfig.allowed_territories && ui.caseConfig.allowed_territories.length && !matchesAny(normalized.territory, ui.caseConfig.allowed_territories)) { normalized.territory = ui.caseConfig.territory_rules && ui.caseConfig.territory_rules.default ? ui.caseConfig.territory_rules.default : ''; }
		if (ui.caseConfig.allowed_media && ui.caseConfig.allowed_media.length && !matchesAny(normalized.medium, ui.caseConfig.allowed_media)) { var mediaDefault = ui.caseConfig.media_rules && Array.isArray(ui.caseConfig.media_rules.default) ? ui.caseConfig.media_rules.default[0] : ''; normalized.medium = mediaDefault; }
		return normalized;
	}

	function applyNormalizedState(form, normalized) {
		Object.keys(normalized).forEach(function (key) { setFieldValue(fieldNode(form, key), normalized[key]); });
	}

	function pushUniqueError(errors, message) { if (message && errors.indexOf(message) === -1) { errors.push(message); } }

	function validateFormData(formData, ui) {
		var errors = [];
		ui.requiredFields.forEach(function (field) {
			var value = formData[field];
			if (value == null || value === '') { pushUniqueError(errors, FIELD_LABELS[field] || labelFromKey(field)); }
		});
		if (errors.length) { return { valid: false, message: 'Bitte ergänze zuerst: ' + errors.join(', ') + '.', missing: errors }; }
		Object.keys(NUMERIC_FIELDS).forEach(function (field) {
			if (formData[field] === '' || FIELD_DEFAULTS[field] === formData[field] && ui.requiredFields.indexOf(field) === -1) { return; }
			var numericValue = normalizeNumber(formData[field]);
			if (numericValue == null) { pushUniqueError(errors, (FIELD_LABELS[field] || labelFromKey(field)) + ' ist keine gültige Zahl'); return; }
			if (NUMERIC_FIELDS[field].min != null && numericValue < NUMERIC_FIELDS[field].min) { pushUniqueError(errors, (FIELD_LABELS[field] || labelFromKey(field)) + ' muss mindestens ' + NUMERIC_FIELDS[field].min + ' sein'); }
		});
		if (ui.effectiveCase === 'telefonansage' && formData.is_paid_media === '1') { pushUniqueError(errors, 'Telefonansagen dürfen nicht als Paid Media kalkuliert werden'); }
		['archivgage', 'reminder', 'allongen', 'follow_up_usage', 'is_nachgage', 'prior_layout_fee', 'unlimited_time', 'unlimited_territory', 'unlimited_media'].forEach(function (field) {
			var active = field === 'prior_layout_fee' ? normalizeNumber(formData[field]) > 0 : isTruthy(formData[field]);
			if (active && !isCaseFieldAllowed(ui, field, formData)) { pushUniqueError(errors, (FIELD_LABELS[field] || labelFromKey(field)) + ' ist für diese Auswahl nicht zulässig'); }
		});
		if (isTruthy(formData.follow_up_usage) && !(normalizeNumber(formData.prior_layout_fee) > 0)) { pushUniqueError(errors, 'Für Nachnutzung muss ein vorheriges Layout-Honorar angegeben werden'); }
		if ((isTruthy(formData.unlimited_time) || isTruthy(formData.unlimited_territory) || isTruthy(formData.unlimited_media)) && String(formData.case_variant || '').indexOf('patronat') !== -1) { pushUniqueError(errors, 'Patronat bleibt für Unlimited-/Buyout-Kombinationen gesperrt'); }
		if (ui.effectiveCase === 'session_fee' && (formData.case_variant || formData.duration_term || formData.territory || formData.medium || isTruthy(formData.archivgage) || isTruthy(formData.reminder) || isTruthy(formData.allongen) || isTruthy(formData.follow_up_usage) || isTruthy(formData.unlimited_time) || isTruthy(formData.unlimited_territory) || isTruthy(formData.unlimited_media) || normalizeNumber(formData.additional_year) > 0 || normalizeNumber(formData.additional_territory) > 0 || normalizeNumber(formData.additional_motif) > 0 || normalizeNumber(formData.prior_layout_fee) > 0)) {
			pushUniqueError(errors, 'Session Fee darf nicht mit Lizenz-, Rechte- oder Unlimited-Optionen kombiniert werden');
		}
		if (ui.caseConfig.allowed_variants && ui.caseConfig.allowed_variants.length && formData.case_variant && !matchesAny(formData.case_variant, ui.caseConfig.allowed_variants)) { pushUniqueError(errors, 'Die gewählte Variante ist für diesen Fall nicht zulässig'); }
		if (ui.caseConfig.allowed_durations && ui.caseConfig.allowed_durations.length && formData.duration_term && !matchesAny(formData.duration_term, ui.caseConfig.allowed_durations)) { pushUniqueError(errors, 'Die gewählte Laufzeit ist für diesen Fall nicht zulässig'); }
		if (ui.caseConfig.allowed_territories && ui.caseConfig.allowed_territories.length && formData.territory && !matchesAny(formData.territory, ui.caseConfig.allowed_territories)) { pushUniqueError(errors, 'Das gewählte Territorium ist für diesen Fall nicht zulässig'); }
		if (ui.caseConfig.allowed_media && ui.caseConfig.allowed_media.length && formData.medium && !matchesAny(formData.medium, ui.caseConfig.allowed_media)) { pushUniqueError(errors, 'Das gewählte Medium ist für diesen Fall nicht zulässig'); }
		return { valid: errors.length === 0, message: errors[0] || 'Die Eingaben sind vollständig und fachlich konsistent.', errors: errors };
	}

	function renderValidation(app, validation) {
		var status = app.querySelector('[data-sgk-validation-status]');
		var button = app.querySelector('[data-sgk-submit]');
		if (!status || !button) { return; }
		status.textContent = validation.message || '';
		status.className = 'sgk-validation-status ' + (validation.valid ? 'is-valid' : 'is-invalid');
		button.disabled = !validation.valid;
		button.setAttribute('aria-disabled', validation.valid ? 'false' : 'true');
		button.textContent = validation.valid ? 'Angebot als PDF erstellen' : 'Bitte fülle noch aus';
	}

	function configureRuleSelects(form, ui) {
		buildSelectOptions(fieldNode(form, 'duration_term'), ui.caseConfig.allowed_durations || [], 'Laufzeit wählen');
		buildSelectOptions(fieldNode(form, 'territory'), ui.caseConfig.allowed_territories || [], 'Territorium wählen');
		buildSelectOptions(fieldNode(form, 'medium'), ui.caseConfig.allowed_media || [], 'Medium wählen');
	}

	function toggleBlocks(form, ui, hasSelection) {
		form.querySelectorAll('[data-sgk-block]').forEach(function (block) {
			var key = block.getAttribute('data-sgk-block');
			var visible = hasSelection && ui.visibleBlocks.indexOf(key) !== -1;
			block.classList.toggle('sgk-hidden', !visible);
			block.hidden = !visible;
		});
		form.querySelectorAll('[data-sgk-dependent-step]').forEach(function (step) { step.classList.toggle('is-disabled', !hasSelection); });
		var expertShell = form.querySelector('[data-sgk-expert-shell]');
		if (expertShell) { expertShell.classList.toggle('is-disabled', !hasSelection); }
		var scopeCopy = form.querySelector('[data-sgk-scope-copy]');
		if (scopeCopy) { scopeCopy.textContent = hasSelection ? buildScopeGuidance(serializeForm(form), ui) : 'Wähle zuerst eine Projektart. Danach zeigen wir Dir die passenden Eingaben.'; }
		var usageStep = form.querySelector('[data-sgk-step-shell="usage"]');
		if (usageStep) {
			var showUsageStep = hasSelection && (ui.visibleUsageBlocks || []).length > 0;
			usageStep.hidden = !showUsageStep;
			usageStep.classList.toggle('sgk-hidden', !showUsageStep);
		}
		var rightsStep = form.querySelector('[data-sgk-step-shell="rights"]');
		if (rightsStep) {
			var showRightsStep = hasSelection && (ui.visibleRightsBlocks || []).length > 0;
			rightsStep.hidden = !showRightsStep;
			rightsStep.classList.toggle('sgk-hidden', !showRightsStep);
		}
		var rightsCore = form.querySelector('[data-sgk-rights-core]');
		if (rightsCore) {
			var rightsCount = (ui.visibleRightsBlocks || []).length;
			rightsCore.classList.toggle('is-cols-1', rightsCount <= 1);
			rightsCore.classList.toggle('is-cols-2', rightsCount === 2);
			rightsCore.classList.toggle('is-cols-3', rightsCount >= 3);
		}
		var rightsIntro = form.querySelector('[data-sgk-rights-intro]');
		if (rightsIntro) { rightsIntro.hidden = !hasSelection || !(ui.visibleRightsBlocks || []).length; }
	}

	function resetInvisibleBlockFields(form, visibleBlocks) {
		Object.keys(BLOCK_FIELD_MAP).forEach(function (block) {
			if (visibleBlocks.indexOf(block) !== -1) { return; }
			resetFields(form, BLOCK_FIELD_MAP[block]);
		});
	}

	function updateFieldAttributes(form, ui) {
		Object.keys(NUMERIC_FIELDS).forEach(function (name) {
			var field = fieldNode(form, name);
			if (!field) { return; }
			var conf = NUMERIC_FIELDS[name];
			if (conf.min != null) { field.min = conf.min; }
			if (conf.step != null) { field.step = conf.step; }
			if (ui.caseConfig.validation_rules && ui.caseConfig.validation_rules.numeric_ranges && ui.caseConfig.validation_rules.numeric_ranges[name]) {
				var range = ui.caseConfig.validation_rules.numeric_ranges[name];
				if (range.min != null) { field.min = range.min; }
				if (range.max != null) { field.max = range.max; } else { field.removeAttribute('max'); }
			}
		});
	}

	function updateConditionalRows(form, ui, formData) {
		form.querySelectorAll('[data-sgk-conditional-field]').forEach(function (row) {
			var fieldName = row.getAttribute('data-sgk-conditional-field');
			var allowed = !!ui.selectedCase && isCaseFieldAllowed(ui, fieldName, formData);
			var field = fieldNode(form, fieldName);
			row.hidden = !allowed;
			row.classList.toggle('sgk-hidden', !allowed);
			if (!allowed && field) {
				setFieldValue(field, FIELD_DEFAULTS.hasOwnProperty(fieldName) ? FIELD_DEFAULTS[fieldName] : '');
			}
		});
	}


	function safeCurrency(value, fallback) { var number = Number(value); return isFinite(number) ? currency(number) : (fallback || '—'); }
	function uniqueItems(items) { return Array.from(new Set((items || []).filter(Boolean))); }
	function isPositiveValue(value) { var number = normalizeNumber(value); return number != null && number > 0; }
	function tidyHumanLabel(value) {
		var raw = String(value || '').trim();
		if (!raw) { return 'Fallabhängig'; }
		var normalized = raw.toLowerCase();
		if (normalized === 'gemäß fallkonfiguration' || normalized === 'projektbezogen') { return 'Fallabhängig'; }
		if (normalized === 'zeitlich_unbegrenzt') { return 'Unbegrenzt'; }
		if (normalized === 'unbegrenzt') { return 'Unbegrenzt'; }
		return optionLabel(raw);
	}
	function summarizeRights(rights, formData) {
		var first = rights[0] || {};
		var territory = tidyHumanLabel(formData.territory || first.territory || '');
		var duration = tidyHumanLabel(formData.duration_term || first.duration || '');
		var media = tidyHumanLabel(formData.medium || first.media || '');
		if (Array.isArray(first.usage_notes) && first.usage_notes.length) {
			media = media === 'Fallabhängig' ? tidyHumanLabel(first.usage_notes[0]) : media;
		}
		var chips = [];
		if (isTruthy(formData.unlimited_time)) { chips.push('Zeitlich offen'); }
		if (isTruthy(formData.unlimited_territory)) { chips.push('Räumlich offen'); }
		if (isTruthy(formData.unlimited_media)) { chips.push('Medial offen'); }
		return {
			territory: territory,
			duration: duration,
			media: media,
			chips: chips
		};
	}
	function collectExtensions(formData) {
		var items = [];
		var addYear = Math.max(0, parseInt(formData.additional_year || '0', 10) || 0);
		var addTerritory = Math.max(0, parseInt(formData.additional_territory || '0', 10) || 0);
		var addMotif = Math.max(0, parseInt(formData.additional_motif || '0', 10) || 0);
		if (addYear > 0) { items.push('Zusatzjahre: ' + addYear); }
		if (addTerritory > 0) { items.push('Zusatzgebiete: ' + addTerritory); }
		if (addMotif > 0) { items.push('Zusatzmotive: ' + addMotif); }
		[['archivgage', 'Archivnutzung'], ['reminder', 'Reminder'], ['allongen', 'Allongen'], ['follow_up_usage', 'Nachnutzung'], ['is_nachgage', 'Nachbuchung / Lizenzverlängerung'], ['usage_social_media', 'Social Media Zusatznutzung'], ['usage_praesentation', 'Präsentationsnutzung'], ['is_paid_media', 'Paid Media Zusatzkanal']].forEach(function (item) {
			if (isTruthy(formData[item[0]])) { items.push(item[1]); }
		});
		return items;
	}
	function collectBreakdownItems(sections) {
		var simplified = [];
		(sections || []).forEach(function (section) {
			if (!section || !Array.isArray(section.items)) { return; }
			if (section.key === 'context') { return; }
			section.items.forEach(function (item) {
				if (!item || !item.label) { return; }
				var amount = (item.formatted && item.formatted.mid) || (item.formatted && item.formatted.low_mid_high) || '';
				if (!amount || amount === '—') { return; }
				simplified.push({
					label: item.label,
					amount: amount,
					note: item.quantity_label || '',
					is_credit: !!item.is_credit,
					is_minimum: !!item.is_minimum
				});
			});
		});
		return simplified.slice(0, 7);
	}
	function normalizeQuantityLabel(label) {
		var text = String(label || '').trim();
		if (!text) { return ''; }
		return text
			.replace(/(\d)([A-Za-zÄÖÜäöü])/g, '$1 $2')
			.replace(/([A-Za-zÄÖÜäöü])(\d)/g, '$1 $2')
			.replace(/\s+/g, ' ')
			.trim();
	}
	function renderBreakdownRows(items) {
		if (!items.length) { return '<div class="src-result-note">Die Preisaufschlüsselung erscheint, sobald alle Angaben vollständig sind.</div>'; }
		return items.map(function (item) {
			var amountValue = parseCurrencyToNumber(item.amount);
			return '<div class="src-breakdown-row">' +
				'<div class="src-breakdown-main"><strong>' + htmlEscape(item.label) + '</strong>' + (item.note ? '<span>· ' + htmlEscape(normalizeQuantityLabel(item.note)) + '</span>' : '') + '</div>' +
				'<div class="src-breakdown-amount src-count-animate' + (item.is_credit ? ' is-credit' : '') + (item.is_minimum ? ' is-minimum' : '') + '"' + (amountValue !== null ? ' data-sgk-count-value="' + htmlEscape(amountValue) + '"' : '') + '>' + htmlEscape(item.amount) + '</div>' +
			'</div>';
		}).join('');
	}
	function renderPackageAlternatives(alternatives) {
		if (!alternatives.length) { return ''; }
		return '<div class="src-package-card-stack">' + alternatives.slice(0, 3).map(function (item, index) {
			var amount = item.formatted_totals ? (item.formatted_totals.mid || item.formatted_totals.low_mid_high || '—') : '—';
			var amountValue = parseCurrencyToNumber(amount);
			var packageLabel = normalizeQuantityLabel(item.label || ('Paketoption ' + (index + 1)));
			return '<article class="src-package-card">' +
				'<div class="src-package-card-top"><strong>' + htmlEscape(packageLabel) + '</strong><span class="src-package-card-price src-count-animate"' + (amountValue !== null ? ' data-sgk-count-value="' + htmlEscape(amountValue) + '"' : '') + '>' + htmlEscape(amount) + '</span></div>' +
				'<p>Alternative Preisidee für eine andere Nutzungs- oder Paketgröße.</p>' +
			'</article>';
		}).join('') + '</div>';
	}
	function filterRelevantNotes(notes) {
		return (notes || []).filter(function (note) {
			if (!note) { return false; }
			return !/(resolver|system|route|validation|client_request|request_id|debug)/i.test(String(note));
		});
	}
	function animatePriceCounters(container, previousValues) {
		var targets = container.querySelectorAll('[data-sgk-count-value]');
		targets.forEach(function (node) {
			var end = parseFloat(node.getAttribute('data-sgk-count-value') || '0');
			if (isNaN(end)) { return; }
			var key = node.getAttribute('data-sgk-count-key') || node.textContent.trim();
			var start = previousValues && previousValues.hasOwnProperty(key) ? previousValues[key] : end;
			if (Math.abs(end - start) < 0.01) {
				node.textContent = currency(end);
				node.setAttribute('data-sgk-count-current', String(end));
				return;
			}
			var startAt = performance.now();
			var duration = 220;
			if (node.__sgkAnimFrame) {
				cancelAnimationFrame(node.__sgkAnimFrame);
			}
			node.classList.add('is-animating');
			var step = function (now) {
				var progress = Math.min(1, (now - startAt) / duration);
				var eased = progress < 0.5 ? (2 * progress * progress) : (1 - Math.pow(-2 * progress + 2, 2) / 2);
				var current = start + ((end - start) * eased);
				node.textContent = currency(current);
				node.setAttribute('data-sgk-count-current', String(current));
				if (progress < 1) {
					node.__sgkAnimFrame = requestAnimationFrame(step);
				} else {
					node.textContent = currency(end);
					node.setAttribute('data-sgk-count-current', String(end));
					node.classList.remove('is-animating');
					node.__sgkAnimFrame = null;
				}
			};
			node.__sgkAnimFrame = requestAnimationFrame(step);
		});
	}
	function readCurrentCounterValues(container) {
		var values = {};
		container.querySelectorAll('[data-sgk-count-value]').forEach(function (node, index) {
			var key = node.getAttribute('data-sgk-count-key') || ('counter-' + index);
			var value = normalizeNumber(node.getAttribute('data-sgk-count-current'));
			if (value === null) {
				value = parseCurrencyToNumber(node.textContent);
			}
			if (value !== null) { values[key] = value; }
		});
		return values;
	}
	function renderKnowledgeAccordion() { return ''; }
	function filterProjectHints(notes) {
		return (notes || []).filter(function (note) {
			return /(hinweis|sonderfall|zusatz|rechte|lizenz|nutzung|territ|laufzeit|medium)/i.test(String(note || ''));
		});
	}

	function validationLabel(result) {
		if (result && result.totals && Number(result.totals.mid || 0) > 0) { return "Berechnet"; }
		return "Fast fertig...";
	}


	function renderResult(container, payload, formData) {
		var result = payload.result || {};
		var totals = result.formatted_totals || {};
		var positions = Array.isArray(result.offer_positions) ? result.offer_positions : [];
		var breakdownSections = Array.isArray(result.breakdown_sections) ? result.breakdown_sections : [];
		var alternatives = Array.isArray(result.alternatives) ? result.alternatives : [];
		var manualOffer = result.formatted_manual_offer_total || 'Noch nicht gesetzt';
		var manualValidation = validateManualOffer(result.manual_offer_total, result);
		var copyBlocks = buildCopyBlocks(result, formData, {});
		var summaryContext = (result.summary && result.summary.context) || {};
		var rightsSummary = summarizeRights(Array.isArray(result.rights_overview) ? result.rights_overview : [], formData || {});
		var breakdownItems = collectBreakdownItems(breakdownSections);
		var caseLabel = summaryContext.case_label || labelFromKey(result.resolved_case || formData.case_key || 'projekt');
		var variantLabel = summaryContext.variant_label || 'Standard';
		var alternativesMarkup = renderPackageAlternatives(alternatives);
		var positionMarkup = positions.length ? positions.slice(0, 5).map(function (item) {
			var price = item.formatted_prices && item.formatted_prices.manual ? item.formatted_prices.manual : ((item.formatted_prices && item.formatted_prices.mid) || '0,00 €');
			var priceValue = parseCurrencyToNumber(price);
			return '<div class="src-receipt-item"><div><strong>' + htmlEscape(item.titel) + '</strong></div><span class="src-count-animate"' + (priceValue !== null ? ' data-sgk-count-key="position-' + htmlEscape(item.titel) + '" data-sgk-count-value="' + htmlEscape(priceValue) + '"' : '') + '>' + htmlEscape(price) + '</span></div>';
		}).join('') : '<div class="src-receipt-item"><span>Einzelposten folgen nach vollständiger Berechnung.</span><span>' + htmlEscape(totals.mid || '0,00 €') + '</span></div>';

		container.classList.remove('sgk-result-flash');
		container.innerHTML = '' +
			'<div class="src-result-hero src-result-hero--stack">' +
				'<section class="src-result-card src-result-card--price"><div class="src-price-panel-head"><div><strong>LIVE RECHNUNG</strong></div><span class="src-live-badge src-live-badge--panel"><span class="src-live-dot"></span>Live</span></div><div class="src-price-block"><div class="src-price-huge"><span class="src-price-huge-value sgk-price src-count-animate" data-sgk-count-key="price-anchor" data-sgk-count-value="' + htmlEscape(result.totals && result.totals.mid ? result.totals.mid : 0) + '">' + htmlEscape(totals.mid || '0,00 €') + '</span><span class="src-price-netto">Ø Mittelwert</span></div><div class="src-price-range"><div class="src-price-range-row"><span>Preisrange:</span><strong class="src-count-animate" data-sgk-count-key="price-lower" data-sgk-count-value="' + htmlEscape(result.totals && result.totals.lower ? result.totals.lower : 0) + '">' + htmlEscape(totals.lower || '0,00 €') + '</strong><span>–</span><strong class="src-count-animate" data-sgk-count-key="price-upper" data-sgk-count-value="' + htmlEscape(result.totals && result.totals.upper ? result.totals.upper : 0) + '">' + htmlEscape(totals.upper || '0,00 €') + '</strong></div><span>Basis für Dein finales Angebot</span></div></div></section>' +
				'<div class="src-result-meta-grid src-result-meta-grid--stack"><div class="src-result-meta-card"><span class="src-meta-label">Projekt:</span><strong>' + htmlEscape(caseLabel) + '</strong></div><div class="src-result-meta-card"><span class="src-meta-label">Variante:</span><strong>' + htmlEscape(variantLabel) + '</strong></div></div>' +
			'</div>' +
			'<div class="src-result-grid src-result-grid--stack">' +
				'<section class="src-result-card src-result-card--priority"><div class="src-result-card-head"><strong>Nutzungsrechte</strong></div><div class="src-keyvalue-list"><div class="src-keyvalue-row"><span>Region</span><strong>' + htmlEscape(rightsSummary.territory) + '</strong></div><div class="src-keyvalue-row"><span>Laufzeit</span><strong>' + htmlEscape(rightsSummary.duration) + '</strong></div><div class="src-keyvalue-row"><span>Kanal</span><strong>' + htmlEscape(rightsSummary.media) + '</strong></div></div></section>' +
				'<section class="src-result-card"><div class="src-result-card-head"><strong>So setzt sich der Preis zusammen</strong></div><div class="src-breakdown-section">' + renderBreakdownRows(breakdownItems) + '</div>' + (alternativesMarkup ? '<div class="src-result-subsection"><strong>Alternative Pakete</strong>' + alternativesMarkup + '</div>' : '') + '</section>' +
				'<section class="src-manual-offer"><strong>Dein Angebot</strong><div class="src-manual-offer-row"><input type="number" min="0" step="0.01" value="' + htmlEscape(result.manual_offer_total || '') + '" placeholder="z. B. 2450.00" data-sgk-manual-offer /><button type="button" class="src-btn-secondary" data-sgk-sync-manual-offer>Betrag übernehmen</button></div><div class="src-manual-offer-status ' + (manualValidation.valid ? 'is-valid' : 'is-invalid') + '">' + htmlEscape(manualValidation.message) + '</div><div class="src-storage-status">Aktuell: ' + htmlEscape(manualOffer) + '</div><div class="src-receipt-list src-receipt-list--detailed">' + positionMarkup + '</div></section>' +
				'<section class="src-result-actions"><button type="button" class="src-btn-primary" data-sgk-action="open-pdf"><i class="fa-solid fa-file-signature" aria-hidden="true"></i> Angebot erstellen</button><div class="src-result-btn-grid"><button type="button" class="src-btn-secondary" data-label="PDF Export" data-feedback-label="PDF erstellt" data-sgk-action="open-pdf"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i> PDF Export</button><button type="button" class="src-btn-secondary" data-label="Zusammenfassung kopieren" data-feedback-label="Kopiert" data-sgk-action="copy-summary"><i class="fa-solid fa-copy" aria-hidden="true"></i> Kopieren</button><button type="button" class="src-btn-secondary" data-label="Einzelposten kopieren" data-feedback-label="Einzelposten kopiert" data-sgk-action="copy-positions"><i class="fa-solid fa-list-check" aria-hidden="true"></i> Angebotspositionen</button><button type="button" class="src-btn-secondary" data-label="Nutzungsrechte kopieren" data-feedback-label="Nutzungsrechte kopiert" data-sgk-action="copy-rights"><i class="fa-solid fa-scale-balanced" aria-hidden="true"></i> Nutzungsrechte</button></div></section>' +
				'<section class="src-storage-panel"><label for="sgk-saved-calculations">Gespeicherte Berechnungen</label><select id="sgk-saved-calculations" data-sgk-saved-list><option value="">Bitte auswählen</option></select><div class="src-storage-actions"><button type="button" class="src-btn-secondary" data-label="Berechnung speichern" data-feedback-label="Gespeichert" data-sgk-action="save"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Speichern</button><button type="button" class="src-btn-secondary" data-label="Berechnung laden" data-feedback-label="Geladen" data-sgk-action="load"><i class="fa-solid fa-folder-open" aria-hidden="true"></i> Laden</button><button type="button" class="src-btn-secondary" data-label="Berechnung löschen" data-feedback-label="Gelöscht" data-sgk-action="delete"><i class="fa-solid fa-trash" aria-hidden="true"></i> Löschen</button></div><div class="src-storage-status" data-sgk-storage-status>' + htmlEscape(storageAvailable() ? 'Berechnungen werden lokal in diesem Browser gespeichert.' : 'Lokales Speichern ist in dieser Umgebung nicht verfügbar.') + '</div></section>' +
				'<div class="src-result-accordion"><div class="src-accordion-item is-open"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger aria-expanded="true" aria-controls="sgk-project-compass"><span>Zusammenfassung</span><span class="src-accordion-indicator" aria-hidden="true"></span></button><div class="src-accordion-content" id="sgk-project-compass"><p>' + htmlEscape(copyBlocks.summary) + '</p></div></div></div>' +
				renderKnowledgeAccordion() +
			'</div>';
		window.requestAnimationFrame(function () { container.classList.add('sgk-result-flash'); });
	}

	function hydrateOfferModal(app, result, formData) { var modal = app.querySelector('[data-sgk-offer-modal]'); var preview = modal.querySelector('[data-sgk-offer-preview]'); var status = modal.querySelector('[data-sgk-offer-status]'); var offerDate = modal.querySelector('[data-sgk-offer-meta="offer_date"]'); if (offerDate && !offerDate.value) { offerDate.value = todayIso(); } var meta = getOfferMeta(app, formData); var offerPreview = renderOfferPreview(result, formData, meta); preview.innerHTML = offerPreview.html; status.textContent = offerPreview.validation.message; status.className = 'src-field-hint src-manual-offer-status ' + (offerPreview.validation.valid ? 'is-valid' : 'is-invalid'); app.__sgkOfferPreview = offerPreview; }

	document.addEventListener('DOMContentLoaded', function () {
		var app = document.querySelector('[data-sgk-app]');
		if (!app || typeof fetch !== 'function') { return; }
		var form = app.querySelector('[data-sgk-form]');
		var resultContainer = app.querySelector('[data-sgk-result]');
		var cases = parseJsonAttribute(app, 'data-sgk-cases');
		var modal = app.querySelector('[data-sgk-offer-modal]');
		var debounceTimer = null;
		var lastFocusedElement = null;
		var requestSequence = 0;
		var activeController = null;
		app.__sgkState = { version: APP_STATE_VERSION, rawInput: clone(FIELD_DEFAULTS), ui: null, normalizedPayload: null, result: null, validation: { valid: false, message: 'Bitte wähle zuerst eine Projektart.' }, activeRequestId: 0 };

		function syncUI(options) {
			var opts = options || {};
			var previous = app.__sgkState.ui || {};
			var raw = serializeForm(form);
			var ui = deriveUiState(raw, cases);
			populateVariants(form, ui.effectiveCase, ui.caseConfig);
			configureRuleSelects(form, ui);
			if (!opts.skipResets && previous.selectedCase && previous.selectedCase !== ui.selectedCase) { resetFields(form, Object.keys(FIELD_DEFAULTS).filter(function (name) { return ['project_title', 'customer_name', 'internal_notes'].indexOf(name) === -1; })); setFieldValue(fieldNode(form, 'case_key'), ui.selectedCase); }
			if (!opts.skipResets && previous.effectiveCase && previous.effectiveCase !== ui.effectiveCase) { resetInvisibleBlockFields(form, ui.visibleBlocks); }
			var normalized = normalizeFormData(form, cases);
			applyNormalizedState(form, normalized);
			ui = deriveUiState(normalized, cases);
			populateVariants(form, ui.effectiveCase, ui.caseConfig);
			updateFieldAttributes(form, ui);
			updateCaseContext(app, ui.selectedCase, cases);
			toggleBlocks(form, ui, !!ui.selectedCase);
			resetInvisibleBlockFields(form, ui.visibleBlocks);
			updateConditionalRows(form, ui, normalized);
			updateGuidanceSummary(app, normalized, ui);
			syncSegmentedControl(form.querySelector('[data-sgk-usage-type-control]'), fieldNode(form, 'usage_type').value);
			syncSegmentedControl(form.querySelector('[data-sgk-variant-control]'), fieldNode(form, 'case_variant').value);
			updateRedirectBanner(app, app.__sgkLastPayload || null);
			var validation = validateFormData(normalized, ui);
			renderValidation(app, validation);
			app.__sgkState.rawInput = raw;
			app.__sgkState.ui = ui;
			app.__sgkState.normalizedPayload = normalized;
			app.__sgkState.validation = validation;
			updateProgressIndicator();
		}
		function currentState() { return { payload: app.__sgkLastPayload, formData: clone(app.__sgkState.normalizedPayload || serializeForm(form)) }; }
		function setModalState(isOpen) { var dialog = modal.querySelector('[role="dialog"]'); var closeButton = modal.querySelector('[data-sgk-offer-close]'); modal.hidden = !isOpen; modal.classList.toggle('is-open', isOpen); modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true'); document.body.classList.toggle('sgk-modal-open', isOpen); if (isOpen) { lastFocusedElement = document.activeElement; window.requestAnimationFrame(function () { if (dialog) { dialog.focus(); } if (closeButton) { closeButton.focus(); } }); return; } if (lastFocusedElement && typeof lastFocusedElement.focus === 'function' && document.contains(lastFocusedElement)) { lastFocusedElement.focus(); } lastFocusedElement = null; }
		function openModal() { setModalState(true); }
		function closeModal() { setModalState(false); }
		function abortPendingRequest() { if (activeController) { activeController.abort(); activeController = null; } }
		function requestCalculation(reason) {
			var state = app.__sgkState;
			if (!state.normalizedPayload || !state.normalizedPayload.case_key) { resultContainer.innerHTML = DEFAULT_RESULT_MESSAGE; return; }
			if (!state.validation.valid) { updateRedirectBanner(app, null); app.__sgkLastPayload = null; resultContainer.classList.remove('is-updating'); resultContainer.innerHTML = '<div class="src-result-state src-result-state--progress"><span class="src-result-state-label">Status</span><strong>Fast fertig...</strong><span class="src-live-dot src-live-dot--pulse" aria-hidden="true"></span><p>Projekt: ' + htmlEscape(summarizeSelection(state.normalizedPayload.case_key)) + '</p><p><strong>Bitte fülle noch aus:</strong> ' + htmlEscape(state.validation.message) + '</p></div>'; updateProgressIndicator(); return; }
			abortPendingRequest();
			requestSequence += 1;
			state.activeRequestId = requestSequence;
			activeController = typeof AbortController === 'function' ? new AbortController() : null;
			if (!app.__sgkLastPayload) {
				resultContainer.innerHTML = LOADING_RESULT_MESSAGE;
			} else {
				resultContainer.classList.add('is-updating');
			}
			var payload = clone(state.normalizedPayload);
			payload.client_request_id = String(requestSequence);
			payload.client_reason = reason || 'change';
			fetch(sgkFrontend.restUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': sgkFrontend.nonce }, body: JSON.stringify(payload), signal: activeController ? activeController.signal : undefined })
				.then(function (response) { return response.json().catch(function () { return null; }).then(function (json) { if (!response.ok) { var error = new Error('request-failed'); error.payload = json; throw error; } return json; }); })
				.then(function (json) {
					if (String(state.activeRequestId) !== String(payload.client_request_id)) { return; }
					if (!json || !json.result) { throw new Error('invalid-payload'); }
					var previousCounterValues = readCurrentCounterValues(resultContainer);
					app.__sgkLastPayload = json;
					state.result = json.result;
					renderResult(resultContainer, json, payload);
					resultContainer.classList.remove('is-updating');
					animatePriceCounters(resultContainer, previousCounterValues);
					updateExpertBadges(app, json.ui_state || {});
					updateRedirectBanner(app, json);
					refreshSavedList(resultContainer, cases);
					updateProgressIndicator();
				})
				.catch(function (error) {
					if (error && error.name === 'AbortError') { return; }
					if (String(state.activeRequestId) !== String(payload.client_request_id)) { return; }
					updateRedirectBanner(app, error && error.payload ? error.payload : null);
					resultContainer.classList.remove('is-updating');
					resultContainer.innerHTML = ERROR_RESULT_MESSAGE;
				});
		}
		function scheduleCalculation(reason, delay) { clearTimeout(debounceTimer); debounceTimer = setTimeout(function () { syncUI(); requestCalculation(reason); }, delay); }

		app.querySelectorAll('[data-sgk-quick-case]').forEach(function (button) {
			button.addEventListener('click', function () {
				var demoPayload = parseJsonAttribute(button, 'data-sgk-demo');
				if (demoPayload && typeof demoPayload === 'object' && Object.keys(demoPayload).length) {
					fillForm(form, demoPayload);
					if (!demoPayload.case_key && button.getAttribute('data-sgk-quick-case')) {
						setFieldValue(fieldNode(form, 'case_key'), button.getAttribute('data-sgk-quick-case'));
					}
					syncUI();
					requestCalculation('demo');
					return;
				}
				var quickCase = button.getAttribute('data-sgk-quick-case');
				if (quickCase) {
					setFieldValue(fieldNode(form, 'case_key'), quickCase);
					syncUI();
					requestCalculation('quick-case');
				}
			});
		});
		app.querySelectorAll('[data-sgk-currency]').forEach(function (button) {
			button.addEventListener('click', function () {
				app.querySelectorAll('[data-sgk-currency]').forEach(function (chip) { chip.classList.remove('is-active'); });
				button.classList.add('is-active');
			});
		});
		var resetButton = app.querySelector('[data-sgk-reset-calculator]');
		if (resetButton) {
			resetButton.addEventListener('click', function () {
				fillForm(form, FIELD_DEFAULTS);
				setFieldValue(fieldNode(form, 'recording_days'), '1');
				setFieldValue(fieldNode(form, 'same_day_projects'), '1');
				syncUI({ skipResets: true });
				app.__sgkLastPayload = null;
				resultContainer.innerHTML = DEFAULT_RESULT_MESSAGE;
			});
		}
		app.addEventListener('click', function (event) {
			var segment = event.target.closest('[data-sgk-segment-value]');
			var accordion = event.target.closest('[data-sgk-accordion-trigger]');
			var foldable = event.target.closest('[data-sgk-foldable-trigger]');
			var stepButton = event.target.closest('[data-sgk-step]');
			var stepperButton = event.target.closest('[data-sgk-stepper-direction]');
			if (segment) { var control = segment.parentElement; var select = control.hasAttribute('data-sgk-variant-control') ? fieldNode(form, 'case_variant') : fieldNode(form, 'usage_type'); if (select) { select.value = segment.getAttribute('data-sgk-segment-value'); syncSegmentedControl(control, select.value); syncUI(); requestCalculation('segment'); } return; }
			if (accordion) {
				var item = accordion.closest('.src-accordion-item');
				var content = item && item.querySelector('.src-accordion-content');
				var parent = item && item.parentElement;
				if (parent) {
					parent.querySelectorAll('.src-accordion-item').forEach(function (node) {
						if (node === item) { return; }
						node.classList.remove('is-open');
						var otherButton = node.querySelector('[data-sgk-accordion-trigger]');
						var otherContent = node.querySelector('.src-accordion-content');
						if (otherButton) { otherButton.setAttribute('aria-expanded', 'false'); }
						if (otherContent) { otherContent.hidden = true; }
					});
				}
				var willOpen = !item.classList.contains('is-open');
				item.classList.toggle('is-open', willOpen);
				accordion.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
				if (content) { content.hidden = !willOpen; }
				return;
			}
			if (foldable) { foldable.closest('.src-foldable-panel').classList.toggle('is-open'); return; }
			if (stepperButton) { var stepper = stepperButton.closest('[data-sgk-stepper]'); var input = stepper && stepper.querySelector('input'); if (!input) { return; } var step = parseFloat(input.getAttribute('step') || '1'); var min = parseFloat(input.getAttribute('min') || '0'); var current = parseFloat(input.value || '0'); if (isNaN(current)) { current = min || 0; } current += stepperButton.getAttribute('data-sgk-stepper-direction') === 'up' ? step : -step; if (!isNaN(min)) { current = Math.max(min, current); } input.value = String(Math.round(current * 100) / 100); input.dispatchEvent(new Event('input', { bubbles: true })); return; }
			if (stepButton) { return; }
		});
		form.addEventListener('change', function () { syncUI(); scheduleCalculation('change', 160); });
		form.addEventListener('input', function () { syncUI(); scheduleCalculation('input', 260); });
		form.addEventListener('submit', function (event) { event.preventDefault(); syncUI(); requestCalculation('submit'); });
		resultContainer.addEventListener('click', function (event) {
			var actionButton = event.target.closest('[data-sgk-action]');
			var action = actionButton && actionButton.getAttribute('data-sgk-action');
			var state = currentState();
			if (!action || !state.payload || !state.payload.result) { return; }
			var copyBlocks = buildCopyBlocks(state.payload.result, state.formData, getOfferMeta(app, state.formData));
			var statusNode = resultContainer.querySelector('[data-sgk-storage-status]');
			var entries, selectedId, entry;
			if (action === 'open-pdf') { hydrateOfferModal(app, state.payload.result, state.formData); openModal(); return; }
			if (action === 'copy-summary') { copyText(copyBlocks.summary, actionButton); return; }
			if (action === 'copy-positions') { copyText(copyBlocks.positions, actionButton); return; }
			if (action === 'copy-rights') { copyText(copyBlocks.rights, actionButton); return; }
			if (action === 'copy-json') { copyText(copyBlocks.json, actionButton); return; }
			if (action === 'save') { entries = getSavedCalculations(cases); entry = { id: 'sgk-' + Date.now(), version: STORAGE_VERSION, savedAt: new Date().toISOString(), projectTitle: state.formData.project_title || state.payload.result.display_title || 'Kalkulation', formData: state.formData, result: state.payload.result, exportPayload: buildExportPayload(state.payload.result, state.formData, getOfferMeta(app, state.formData)) }; setSavedCalculations([entry].concat(entries).slice(0, 15)); refreshSavedList(resultContainer, cases); setButtonFeedback(actionButton, 'Gespeichert'); setStatusMessage(statusNode, 'Lokal gespeichert: ' + buildSavedLabel(entry), 'success'); return; }
			selectedId = (resultContainer.querySelector('[data-sgk-saved-list]') || {}).value;
			if (!selectedId) { setStatusMessage(statusNode, 'Bitte wähle zuerst eine gespeicherte Kalkulation aus.', 'error'); return; }
			entries = getSavedCalculations(cases);
			entry = entries.find(function (item) { return item.id === selectedId; });
			if (!entry) { setStatusMessage(statusNode, 'Die gespeicherte Kalkulation konnte nicht geladen werden.', 'error'); return; }
			if (action === 'load') { fillForm(form, entry.formData || {}); syncUI(); requestCalculation('load'); setButtonFeedback(actionButton, 'Geladen'); setStatusMessage(statusNode, 'Geladen: ' + buildSavedLabel(entry), 'success'); return; }
			if (action === 'delete') { setSavedCalculations(entries.filter(function (item) { return item.id !== selectedId; })); refreshSavedList(resultContainer, cases); setButtonFeedback(actionButton, 'Gelöscht'); setStatusMessage(statusNode, 'Die gespeicherte Kalkulation wurde entfernt.', 'success'); }
		});
		resultContainer.addEventListener('click', function (event) { var syncButton = event.target.closest('[data-sgk-sync-manual-offer]'); if (!syncButton) { return; } var input = resultContainer.querySelector('[data-sgk-manual-offer]'); var value = normalizeNumber(input && input.value); if (value == null) { input.focus(); return; } setFieldValue(fieldNode(form, 'manual_offer_total'), String(value)); setButtonFeedback(syncButton, 'Übernommen'); syncUI(); requestCalculation('manual-offer'); });
		modal.querySelectorAll('[data-sgk-offer-close]').forEach(function (button) { button.addEventListener('click', closeModal); });
		modal.addEventListener('input', function () { var state = currentState(); if (state.payload && state.payload.result && !modal.hidden) { hydrateOfferModal(app, state.payload.result, state.formData); } });
		modal.addEventListener('click', function (event) { if (event.target === modal) { closeModal(); return; } var actionButton = event.target.closest('[data-sgk-offer-action]'); var action = actionButton && actionButton.getAttribute('data-sgk-offer-action'); if (!action) { return; } var state = currentState(); if (!state.payload || !state.payload.result) { return; } hydrateOfferModal(app, state.payload.result, state.formData); if (action === 'copy-mail') { copyText(app.__sgkOfferPreview.text, actionButton); return; } if (action === 'print') { setButtonFeedback(actionButton, 'Druckdialog geöffnet'); openPrintDocument(app.__sgkOfferPreview.html); } });
		document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && !modal.hidden) { closeModal(); } });
		setModalState(false);
		syncUI();
		refreshSavedList(resultContainer, cases);

		/* =====================================================
		   NEW UI HANDLERS FOR TWO-COLUMN LAYOUT
		   ===================================================== */

		/* Helper: Show step section by number */
		function showStep(n) {
			var section = app.querySelector('[data-sgk-step="' + n + '"]');
			if (section) { section.hidden = false; }
		}

		/* Helper: Alias for visibility management (defined later in code) */
		var rebuildFieldVisibility;

		/* Project Card Handler (Step 1) */
		app.querySelectorAll('[data-sgk-case]').forEach(function (button) {
			button.addEventListener('click', function () {
				var caseKey = button.getAttribute('data-sgk-case');
				setFieldValue(fieldNode(form, 'case_key'), caseKey);
				app.querySelectorAll('[data-sgk-case]').forEach(function (card) {
					card.classList.toggle('is-active', card === button);
				});
				// Trigger UI rebuild for Steps 2-3
				rebuildVariantPills();
				rebuildTerritoryPills();
				rebuildDurationPills();
				rebuildMediumPills();
				rebuildUsagePills();
				rebuildFieldVisibility();
				showStep(3);
				syncUI();
				requestCalculation('project-select');
			});
		});

		/* Update variant pills and territories dynamically */
		function rebuildVariantPills() {
			var caseKey = fieldNode(form, 'case_key').value;
			var variantControl = app.querySelector('[data-sgk-variant-pills]');
			if (!variantControl || !caseKey) { return; }
			var config = CASE_UI[caseKey];
			if (!config || !config.variantOptions) {
				// No variant options: hide Step 2
				var step2 = app.querySelector('[data-sgk-step="2"]');
				if (step2) { step2.hidden = true; }
				return;
			}
			// Has variant options: show Step 2
			showStep(2);
			variantControl.innerHTML = '';
			config.variantOptions.forEach(function (option) {
				var variantKey = option[0];
				var variantLabel = option[1];
				var button = document.createElement('button');
				button.type = 'button';
				button.className = 'sgk-pill';
				button.setAttribute('data-sgk-variant-value', variantKey);
				button.textContent = variantLabel;
				button.addEventListener('click', function () {
					setFieldValue(fieldNode(form, 'case_variant'), variantKey);
					updateVariantHelp(caseKey, variantKey);
					syncUI();
					requestCalculation('variant-select');
				});
				variantControl.appendChild(button);
			});
			updateVariantHelp(caseKey, fieldNode(form, 'case_variant').value);
			syncVariantPills(caseKey);
		}

		function updateVariantHelp(caseKey, variantKey) {
			var config = CASE_UI[caseKey];
			var helpText = '';
			if (config && config.variantOptions) {
				var option = config.variantOptions.find(function (opt) { return opt[0] === variantKey; });
				helpText = option && option[2] ? option[2] : '';
			}
			var helpNode = app.querySelector('[data-sgk-variant-help]');
			if (helpNode) { helpNode.textContent = helpText; }
		}

		function syncVariantPills(caseKey) {
			var variantValue = fieldNode(form, 'case_variant').value;
			app.querySelectorAll('[data-sgk-variant-value]').forEach(function (pill) {
				pill.classList.toggle('is-active', pill.getAttribute('data-sgk-variant-value') === variantValue);
			});
		}

		/* Territory Pills */
		function rebuildTerritoryPills() {
			var caseKey = fieldNode(form, 'case_key').value;
			var territoryControl = app.querySelector('[data-sgk-territory-pills]');
			var territoryGroup = app.querySelector('[data-sgk-block="territory"]');
			if (!territoryControl || !caseKey) { return; }
			var config = CASE_UI[caseKey];
			var showTerritory = config && config.show && config.show.indexOf('territory') !== -1;
			if (territoryGroup) { territoryGroup.hidden = !showTerritory; }
			if (!showTerritory) { return; }
			var territories = ['lokal', 'regional', 'de', 'dach', 'eu', 'weltweit'];
			territoryControl.innerHTML = '';
			territories.forEach(function (territory) {
				var button = document.createElement('button');
				button.type = 'button';
				button.className = 'sgk-pill';
				button.setAttribute('data-sgk-territory-value', territory);
				button.textContent = optionLabel(territory);
				button.addEventListener('click', function () {
					setFieldValue(fieldNode(form, 'territory'), territory);
					syncTerritorPills();
					syncUI();
					requestCalculation('territory-select');
				});
				territoryControl.appendChild(button);
			});
			syncTerritorPills();
		}

		function syncTerritorPills() {
			var territoryValue = fieldNode(form, 'territory').value;
			app.querySelectorAll('[data-sgk-territory-value]').forEach(function (pill) {
				pill.classList.toggle('is-active', pill.getAttribute('data-sgk-territory-value') === territoryValue);
			});
		}

		/* Duration Term Pills */
		function rebuildDurationPills() {
			var caseKey = fieldNode(form, 'case_key').value;
			var durationControl = app.querySelector('[data-sgk-duration-pills]');
			var durationGroup = app.querySelector('[data-sgk-block="duration_term"]');
			if (!durationControl || !caseKey) { return; }
			var config = CASE_UI[caseKey];
			var showDuration = config && config.show && config.show.indexOf('duration_term') !== -1;
			if (durationGroup) { durationGroup.hidden = !showDuration; }
			if (!showDuration) { return; }
			var durations = ['1_jahr', '2_jahre', 'archiv', 'unbegrenzt'];
			durationControl.innerHTML = '';
			durations.forEach(function (duration) {
				var button = document.createElement('button');
				button.type = 'button';
				button.className = 'sgk-pill';
				button.setAttribute('data-sgk-duration-value', duration);
				button.textContent = optionLabel(duration);
				button.addEventListener('click', function () {
					setFieldValue(fieldNode(form, 'duration_term'), duration);
					syncDurationPills();
					syncUI();
					requestCalculation('duration-select');
				});
				durationControl.appendChild(button);
			});
			syncDurationPills();
		}

		function syncDurationPills() {
			var durationValue = fieldNode(form, 'duration_term').value;
			app.querySelectorAll('[data-sgk-duration-value]').forEach(function (pill) {
				pill.classList.toggle('is-active', pill.getAttribute('data-sgk-duration-value') === durationValue);
			});
		}

		/* Medium Pills */
		function rebuildMediumPills() {
			var caseKey = fieldNode(form, 'case_key').value;
			var mediumControl = app.querySelector('[data-sgk-medium-pills]');
			var mediumGroup = app.querySelector('[data-sgk-block="medium"]');
			if (!mediumControl || !caseKey) { return; }
			var config = CASE_UI[caseKey];
			var showMedium = config && config.show && config.show.indexOf('medium') !== -1;
			if (mediumGroup) { mediumGroup.hidden = !showMedium; }
			if (!showMedium) { return; }
			var mediums = ['tv', 'ctv', 'online_video', 'kino', 'pos', 'event', 'messe', 'radio', 'online_audio', 'ladenfunk', 'telefon'];
			mediumControl.innerHTML = '';
			mediums.forEach(function (medium) {
				var button = document.createElement('button');
				button.type = 'button';
				button.className = 'sgk-pill';
				button.setAttribute('data-sgk-medium-value', medium);
				button.textContent = optionLabel(medium);
				button.addEventListener('click', function () {
					setFieldValue(fieldNode(form, 'medium'), medium);
					syncMediumPills();
					syncUI();
					requestCalculation('medium-select');
				});
				mediumControl.appendChild(button);
			});
			syncMediumPills();
		}

		function syncMediumPills() {
			var mediumValue = fieldNode(form, 'medium').value;
			app.querySelectorAll('[data-sgk-medium-value]').forEach(function (pill) {
				pill.classList.toggle('is-active', pill.getAttribute('data-sgk-medium-value') === mediumValue);
			});
		}

		/* Usage Type Pills */
		function rebuildUsagePills() {
			var caseKey = fieldNode(form, 'case_key').value;
			var usageControl = app.querySelector('[data-sgk-usage-pills]');
			var usageGroup = app.querySelector('[data-sgk-block="usage_type"]');
			if (!usageControl || !caseKey) { return; }
			var config = CASE_UI[caseKey];
			var showUsage = config && config.show && config.show.indexOf('usage_type') !== -1;
			if (usageGroup) { usageGroup.hidden = !showUsage; }
			if (!showUsage) { return; }
			var usages = ['organic_branding', 'paid_advertising'];
			usageControl.innerHTML = '';
			usages.forEach(function (usage) {
				var button = document.createElement('button');
				button.type = 'button';
				button.className = 'sgk-pill';
				button.setAttribute('data-sgk-usage-value', usage);
				button.textContent = optionLabel(usage);
				button.addEventListener('click', function () {
					setFieldValue(fieldNode(form, 'usage_type'), usage);
					syncUsagePills();
					syncUI();
					requestCalculation('usage-select');
				});
				usageControl.appendChild(button);
			});
			syncUsagePills();
		}

		function syncUsagePills() {
			var usageValue = fieldNode(form, 'usage_type').value;
			app.querySelectorAll('[data-sgk-usage-value]').forEach(function (pill) {
				pill.classList.toggle('is-active', pill.getAttribute('data-sgk-usage-value') === usageValue);
			});
		}

		/* Range Slider Display Updates */
		function updateRangeDisplays() {
			app.querySelectorAll('[data-sgk-range]').forEach(function (slider) {
				var fieldName = slider.getAttribute('data-sgk-range');
				var displayNode = app.querySelector('[data-sgk-range-display="' + fieldName + '"]');
				if (!displayNode) { return; }
				var value = parseFloat(slider.value || 0);
				var min = parseFloat(slider.getAttribute('min') || 1);
				var max = parseFloat(slider.getAttribute('max') || 100);
				var percentage = ((value - min) / (max - min)) * 100;
				slider.style.setProperty('--slider-percentage', percentage + '%');
				var displayValue = value;
				if (fieldName === 'duration_minutes') {
					if (value === 1) { displayValue = '1 Minute'; }
					else { displayValue = displayValue.toString().replace('.', ',') + ' Minuten'; }
				}
				displayNode.textContent = displayValue;
			});
		}

		/* Stepper Handlers */
		app.querySelectorAll('[data-sgk-stepper]').forEach(function (stepper) {
			var minusBtn = stepper.querySelector('[data-sgk-stepper-direction="down"]');
			var plusBtn = stepper.querySelector('[data-sgk-stepper-direction="up"]');
			var input = stepper.querySelector('input[data-sgk-stepper-input]');
			if (!minusBtn || !plusBtn || !input) { return; }
			function updateValue(direction) {
				var step = parseFloat(input.getAttribute('step') || '1');
				var min = parseFloat(input.getAttribute('min') || '0');
				var current = parseFloat(input.value || min);
				current += direction === 'up' ? step : -step;
				current = Math.max(min, current);
				input.value = String(Math.round(current * 100) / 100);
				input.dispatchEvent(new Event('input', { bubbles: true }));
			}
			minusBtn.addEventListener('click', function () { updateValue('down'); });
			plusBtn.addEventListener('click', function () { updateValue('up'); });
		});

		/* Progress Indicator Updates */
		function updateProgressIndicator() {
			var state = app.__sgkState || {};
			var normalized = state.normalizedPayload || serializeForm(form);
			var ui = state.ui || deriveUiState(normalized, cases);
			var caseKey = normalized.case_key || '';
			var variantKey = normalized.case_variant || '';
			var hasResult = !!(app.__sgkLastPayload && app.__sgkLastPayload.result && app.__sgkLastPayload.result.totals);
			var config = CASE_UI[caseKey];
			var step1Complete = !!caseKey;
			var step2Complete = step1Complete && (!config || !config.variantOptions || !config.variantOptions.length || !!variantKey);
			var rightsRequired = ['territory', 'duration_term', 'medium'];
			var step3Complete = step2Complete && rightsRequired.every(function (field) {
				if (ui.visibleBlocks.indexOf(field) === -1) { return true; }
				return !!normalized[field];
			});
			var step4Complete = step3Complete && hasResult;
			var activeStep = !step1Complete ? 1 : (!step2Complete ? 2 : (!step3Complete ? 3 : (!step4Complete ? 4 : 4)));
			app.querySelectorAll('[data-sgk-progress]').forEach(function (dot) {
				var stepNum = parseInt(dot.getAttribute('data-sgk-progress'), 10);
				dot.classList.remove('is-active', 'is-complete');
				if (stepNum === 1 && step1Complete) { dot.classList.add('is-complete'); }
				if (stepNum === 2 && step2Complete) { dot.classList.add('is-complete'); }
				if (stepNum === 3 && step3Complete) { dot.classList.add('is-complete'); }
				if (stepNum === 4 && step4Complete) { dot.classList.add('is-complete'); }
				if (stepNum === activeStep && !dot.classList.contains('is-complete')) { dot.classList.add('is-active'); }
			});
		}

		/* Master UI Rebuild - called whenever case_key changes */
		var originalSyncUI = syncUI;
		syncUI = function () {
			originalSyncUI.apply(this, arguments);
			rebuildVariantPills();
			rebuildTerritoryPills();
			rebuildDurationPills();
			rebuildMediumPills();
			rebuildUsagePills();
			updateRangeDisplays();
			updateProgressIndicator();
			showHideStepSections();
		};

		/* Show/hide step sections based on form state */
		function showHideStepSections() {
			var caseKey = fieldNode(form, 'case_key').value;
			var variantKey = fieldNode(form, 'case_variant').value;
			var config = CASE_UI[caseKey];
			var hasVariants = config && config.variantOptions && config.variantOptions.length > 0;
			var hasUsageFields = config && config.show && (config.show.length > 0 || config.show.some(function (field) {
				return ['territory', 'duration_term', 'duration_minutes', 'net_minutes', 'module_count', 'fah', 'recording_hours', 'recording_days', 'same_day_projects', 'session_hours', 'medium', 'media_toggles', 'usage_type'].indexOf(field) !== -1;
			}));
			var hasExtensions = config && config.show && config.show.some(function (field) {
				return ['addon_counts', 'rights_toggles', 'prior_layout_fee', 'unlimited_usage'].indexOf(field) !== -1;
			});
			var step2 = app.querySelector('[data-sgk-step="2"]');
			var step3 = app.querySelector('[data-sgk-step="3"]');
			var step4 = app.querySelector('[data-sgk-step="4"]');
			if (step2) { step2.hidden = !hasVariants; }
			if (step3) { step3.hidden = !hasUsageFields; }
			if (step4) { step4.hidden = !hasExtensions; }
		}

		/* Alias for rebuildFieldVisibility */
		rebuildFieldVisibility = showHideStepSections;

		/* Range slider input event for live display */
		app.querySelectorAll('[data-sgk-range]').forEach(function (slider) {
			slider.addEventListener('input', function () {
				updateRangeDisplays();
			});
		});

		/* Initialize UI */
		rebuildVariantPills();
		rebuildTerritoryPills();
		rebuildDurationPills();
		rebuildMediumPills();
		rebuildUsagePills();
		updateRangeDisplays();
		updateProgressIndicator();
		showHideStepSections();
	});
})();
