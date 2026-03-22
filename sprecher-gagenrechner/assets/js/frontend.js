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

	function htmlEscape(value) { return String(value == null ? '' : value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); }
	function parseJsonAttribute(node, attribute) { try { return JSON.parse(node.getAttribute(attribute) || '{}'); } catch (error) { return {}; } }
	function labelFromKey(value) { return String(value || '').replace(/_/g, ' ').replace(/\b\w/g, function (m) { return m.toUpperCase(); }); }
	function renderList(items, renderer, emptyText, className) { return !items || !items.length ? '<p>' + htmlEscape(emptyText) + '</p>' : '<ul class="' + className + '">' + items.map(renderer).join('') + '</ul>'; }
	function clone(obj) { return JSON.parse(JSON.stringify(obj || {})); }
	function storageAvailable() { try { localStorage.setItem('__sgk_test__', '1'); localStorage.removeItem('__sgk_test__'); return true; } catch (error) { return false; } }
	function currency(value) { return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(Number(value || 0)); }
	function normalizeNumber(value) { var parsed = parseFloat(String(value || '').replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, '')); return isNaN(parsed) ? null : parsed; }
	function todayIso() { return new Date().toISOString().slice(0, 10); }
	function formatDate(value) { if (!value) { return ''; } return new Date(value).toLocaleDateString('de-DE'); }

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
			if (field.type === 'checkbox') { field.checked = data[key] === '1' || data[key] === 1 || data[key] === true; } else { field.value = data[key]; }
		});
	}

	function getSavedCalculations() {
		if (!storageAvailable()) { return []; }
		try { var entries = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); return Array.isArray(entries) ? entries : []; } catch (error) { return []; }
	}
	function setSavedCalculations(entries) { if (!storageAvailable()) { return false; } localStorage.setItem(STORAGE_KEY, JSON.stringify(entries)); return true; }
	function buildSavedLabel(entry) { return (entry.projectTitle || 'Gespeicherte Kalkulation') + ' · ' + new Date(entry.savedAt).toLocaleString('de-DE'); }

	function validateManualOffer(value, result) {
		if (value == null || value === '') { return { valid: false, message: 'Für ein endgültiges Angebotsdokument sollte eine finale Angebotssumme gesetzt werden.' }; }
		if (value <= 0) { return { valid: false, message: 'Bitte eine positive finale Angebotssumme eintragen.' }; }
		if (result && result.totals && value < result.totals.lower * 0.25) { return { valid: false, message: 'Die manuelle Angebotssumme liegt ungewöhnlich weit unter der Empfehlung.' }; }
		return { valid: true, message: 'Die finale Angebotssumme wird separat zur Empfehlung übernommen.' };
	}

	function copyText(text, trigger) {
		if (!text) { return Promise.reject(new Error('empty')); }
		var promise = navigator.clipboard && navigator.clipboard.writeText ? navigator.clipboard.writeText(text) : Promise.reject(new Error('clipboard-unavailable'));
		return promise.then(function () { if (trigger) { trigger.textContent = 'Kopiert'; setTimeout(function () { trigger.textContent = trigger.getAttribute('data-label') || trigger.textContent; }, 1600); } });
	}

	function buildExportPayload(result, formData, offerMeta) {
		var payload = clone(result.export_payload || {});
		payload.summary = payload.summary || {};
		payload.summary.project_title = formData.project_title || '';
		payload.summary.customer_name = formData.customer_name || '';
		payload.summary.display_title = result.display_title || '';
		payload.summary.generated_at = new Date().toISOString();
		payload.calculation_meta = payload.calculation_meta || {};
		payload.calculation_meta.internal_notes = formData.internal_notes || '';
		payload.calculation_meta.source_form = formData;
		payload.calculation_meta.offer_meta = offerMeta || {};
		return payload;
	}

	function updateRedirectBanner(app, payload) {
		var banner = app.querySelector('[data-sgk-redirect-banner]');
		if (!banner) { return; }
		var uiState = (payload && payload.ui_state) || {};
		var result = (payload && payload.result) || {};
		var warnings = Array.isArray(result.warnings) ? result.warnings : [];
		var parts = [];
		if (uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) {
			parts.push('Redirect aktiv: „' + labelFromKey(uiState.selected_case) + '“ wird fachlich als „' + labelFromKey(uiState.resolved_case) + '“ berechnet.');
		}
		if (warnings.length) {
			parts.push('Hinweise: ' + warnings.join(' · '));
		}
		if (!parts.length) {
			banner.hidden = true;
			banner.textContent = '';
			return;
		}
		banner.hidden = false;
		banner.textContent = parts.join(' ');
	}

	function buildCopyBlocks(result, formData, offerMeta) {
		var exportPayload = buildExportPayload(result, formData, offerMeta);
		var texts = exportPayload.export_text_blocks || {};
		var projectLine = offerMeta && offerMeta.offer_number ? ('Angebot ' + offerMeta.offer_number) : 'Angebot Sprecherhonorar';
		return {
			summary: [projectLine, texts.offer_headline || ('Angebot Sprecherhonorar – ' + (formData.project_title || result.display_title || 'Projekt')), texts.copy_summary || '', formData.customer_name ? ('Kunde: ' + formData.customer_name) : '', offerMeta && offerMeta.offer_date ? ('Datum: ' + formatDate(offerMeta.offer_date)) : '', texts.manual_offer_notice || ''].filter(Boolean).join('\n'),
			positions: texts.positions_block || '',
			rights: texts.rights_block || '',
			json: JSON.stringify(exportPayload, null, 2),
			mail: [offerMeta && offerMeta.intro_text ? offerMeta.intro_text : 'Vielen Dank für Ihre Anfrage. Nachfolgend erhalten Sie unser Angebot.', '', texts.offer_headline || '', texts.copy_summary || '', texts.positions_block || '', '', texts.rights_block || '', '', texts.notes_block || '', '', texts.legal_notice_block || ''].filter(Boolean).join('\n')
		};
	}

	function getOfferMeta(app, formData) {
		var meta = { offer_date: todayIso() };
		app.querySelectorAll('[data-sgk-offer-meta]').forEach(function (field) { meta[field.getAttribute('data-sgk-offer-meta')] = field.value || ''; });
		if (!meta.contact_name) { meta.contact_name = formData.customer_name || ''; }
		if (!meta.offer_date) { meta.offer_date = todayIso(); }
		return meta;
	}

	function documentStyles() {
		return [
			'body{font-family:Rubik,Arial,sans-serif;background:#eef4fb;margin:0;color:#0f172a;}',
			'.doc{max-width:980px;margin:0 auto;padding:32px;}',
			'.sheet{background:#fff;border-radius:28px;padding:40px;box-shadow:0 24px 60px rgba(15,23,42,.12);}',
			'.header,.meta-grid,.summary-grid,.positions,.rights,.notes,.footer{display:grid;gap:16px;}',
			'.header-top{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;border-bottom:1px solid #dbe6f1;padding-bottom:20px;}',
			'.logo{width:60px;height:60px;border-radius:18px;background:linear-gradient(180deg,#1a93ee,#0f141a);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;}',
			'.eyebrow{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#1a93ee;font-weight:700;margin:0 0 8px;}',
			'h1,h2,h3,h4,p{margin:0;}',
			'.headline h1{font-size:30px;line-height:1.05;margin-bottom:8px;}',
			'.meta-grid{grid-template-columns:repeat(4,minmax(0,1fr));margin-top:24px;}',
			'.meta-card,.summary-card,.section{border:1px solid #dbe6f1;border-radius:20px;padding:18px;background:#f9fbff;}',
			'.summary-grid{grid-template-columns:2fr 1fr 1fr; margin:24px 0;}',
			'.summary-card--total{background:linear-gradient(180deg,#0f141a,#162131);color:#fff;border-color:#0f141a;}',
			'.section-title{font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-bottom:10px;font-weight:700;}',
			'.position-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:14px;padding:14px 0;border-top:1px solid #dbe6f1;}',
			'.position-row:first-child{border-top:0;padding-top:0;}',
			'.position-row small,.muted{color:#64748b;display:block;line-height:1.5;}',
			'.badge{display:inline-block;padding:6px 10px;border-radius:999px;background:#eaf4fe;color:#116fb3;font-size:12px;font-weight:600;}',
			'.footer{margin-top:28px;padding-top:18px;border-top:1px solid #dbe6f1;font-size:13px;color:#64748b;}',
			'@media print{body{background:#fff}.doc{max-width:none;padding:0}.sheet{box-shadow:none;border-radius:0;padding:24px}}'
		].join('');
	}

	function buildOfferDocumentData(result, formData, offerMeta) {
		var exportPayload = buildExportPayload(result, formData, offerMeta);
		var documentPayload = clone(exportPayload.document_payload || {});
		documentPayload.meta = offerMeta;
		documentPayload.form = formData;
		documentPayload.exportPayload = exportPayload;
		return documentPayload;
	}

	function renderOfferPreview(result, formData, offerMeta) {
		var documentData = buildOfferDocumentData(result, formData, offerMeta);
		var exportPayload = documentData.exportPayload;
		var summary = exportPayload.summary || {};
		var positions = exportPayload.positions || [];
		var rights = exportPayload.rights_overview || [];
		var notes = exportPayload.notes_for_offer || [];
		var legal = exportPayload.legal_notice || [];
		var alternatives = exportPayload.alternative_packages || [];
		var totalText = exportPayload.manual_offer_total != null ? currency(exportPayload.manual_offer_total) : 'Noch offen';
		var midText = exportPayload.recommended_mid != null ? currency(exportPayload.recommended_mid) : '—';
		var rangeText = exportPayload.recommended_range ? currency(exportPayload.recommended_range.lower) + ' – ' + currency(exportPayload.recommended_range.upper) : '—';

		return {
			html: '<style>' + documentStyles() + '</style>' +
				'<div class="doc"><div class="sheet">' +
					'<div class="header">' +
						'<div class="header-top">' +
							'<div style="display:flex;gap:16px;align-items:flex-start;">' +
								'<div class="logo">SGK</div>' +
								'<div class="headline"><p class="eyebrow">Professionelles Angebotsdokument</p><h1>Angebot Sprecherhonorar</h1><p>' + htmlEscape(formData.project_title || summary.display_title || summary.title || 'Projektangebot') + '</p></div>' +
							'</div>' +
							'<div style="text-align:right;"><span class="badge">' + htmlEscape(offerMeta.offer_number || 'Angebot') + '</span><p class="muted" style="margin-top:10px;">' + htmlEscape(formatDate(offerMeta.offer_date)) + '</p></div>' +
						'</div>' +
						'<div class="meta-grid">' +
							'<div class="meta-card"><div class="section-title">Kunde</div><strong>' + htmlEscape(formData.customer_name || 'Nicht angegeben') + '</strong><small class="muted">' + htmlEscape(offerMeta.contact_name || 'Ansprechpartner optional') + '</small></div>' +
							'<div class="meta-card"><div class="section-title">Projekt</div><strong>' + htmlEscape(formData.project_title || summary.display_title || summary.title || 'Kalkulation') + '</strong><small class="muted">Fachfall: ' + htmlEscape(summary.display_title || summary.title || 'Projekt') + '</small></div>' +
							'<div class="meta-card"><div class="section-title">Absender</div><strong>' + htmlEscape(offerMeta.sender_company || 'Studio / Absender') + '</strong><small class="muted">' + htmlEscape([offerMeta.sender_email, offerMeta.sender_phone].filter(Boolean).join(' · ')) + '</small></div>' +
							'<div class="meta-card"><div class="section-title">Dokument</div><strong>' + htmlEscape(offerMeta.offer_number || 'Ohne Nummer') + '</strong><small class="muted">Stand ' + htmlEscape(formatDate(offerMeta.offer_date)) + '</small></div>' +
						'</div>' +
					'</div>' +
					'<div class="summary-grid">' +
						'<div class="summary-card"><div class="section-title">Angebotsbasis</div><p>' + htmlEscape(offerMeta.intro_text || 'Vielen Dank für Ihre Anfrage. Nachfolgend erhalten Sie unser Angebot auf Basis der abgestimmten Nutzung und der aktuellen Kalkulation.') + '</p></div>' +
						'<div class="summary-card"><div class="section-title">Errechnete Spanne</div><strong>' + htmlEscape(rangeText) + '</strong><small class="muted">Mittelwert ' + htmlEscape(midText) + '</small></div>' +
						'<div class="summary-card summary-card--total"><div class="section-title" style="color:rgba(255,255,255,.72)">Finale Angebotssumme</div><strong style="font-size:28px;display:block;">' + htmlEscape(totalText) + '</strong><small>' + htmlEscape(exportPayload.manual_offer_total != null ? 'Manuell gesetzt und als Angebotswert übernommen.' : 'Bitte vor PDF-Ausgabe final festlegen.') + '</small></div>' +
					'</div>' +
					'<div class="section positions"><div class="section-title">Angebotspositionen</div>' + positions.map(function (item) { var price = item.manuell_uebernommener_preis != null ? currency(item.manuell_uebernommener_preis) : currency(item.empfohlener_preis); return '<div class="position-row"><div><strong>' + htmlEscape(item.position_number + '. ' + item.titel) + '</strong><small>' + htmlEscape(item.beschreibung || '') + '</small><small>' + htmlEscape((item.kategorie || '') + (item.lizenzbezug ? ' · ' + item.lizenzbezug : '')) + '</small></div><div><strong>' + htmlEscape(price) + '</strong></div></div>'; }).join('') + '</div>' +
					'<div class="section rights"><div class="section-title">Nutzungsrechte & Lizenzen</div>' + (rights.length ? rights.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.title + (item.variant ? ' · ' + item.variant : '')) + '</strong><small>Laufzeit: ' + htmlEscape(item.duration) + ' · Territorium: ' + htmlEscape(item.territory) + ' · Medien: ' + htmlEscape(item.media) + '</small></div><div><span class="badge">Rechteblock</span></div></div>'; }).join('') : '<p class="muted">Keine zusätzlichen Rechteinformationen vorhanden.</p>') + '</div>' +
					'<div class="section notes"><div class="section-title">Hinweise & Anmerkungen</div>' + (notes.length ? notes.map(function (item) { return '<p style="padding:10px 0;border-top:1px solid #dbe6f1;">' + htmlEscape(item) + '</p>'; }).join('') : '<p class="muted">Keine zusätzlichen Hinweise.</p>') + '</div>' +
					(legal.length ? '<div class="section"><div class="section-title">Rechtlicher Hinweis</div>' + legal.map(function (item) { return '<p>' + htmlEscape(item) + '</p>'; }).join('') + '</div>' : '') +
					(alternatives.length ? '<div class="section"><div class="section-title">Optionale Paket-Alternativen</div>' + alternatives.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.label || 'Alternative') + '</strong></div><div><strong>' + htmlEscape(item.formatted_totals ? item.formatted_totals.mid : '') + '</strong></div></div>'; }).join('') + '</div>' : '') +
					'<div class="footer"><div>' + htmlEscape(offerMeta.footer_text || [offerMeta.sender_company, offerMeta.sender_email, offerMeta.sender_phone].filter(Boolean).join(' · ')) + '</div><div>Dieses Dokument basiert auf der fachlichen Kalkulation des Sprecher-Gagenrechners. Interne Metadaten werden im Kundendokument nicht ausgegeben.</div></div>' +
				'</div></div>',
				text: buildCopyBlocks(result, formData, offerMeta).mail,
				validation: validateManualOffer(exportPayload.manual_offer_total, result)
		};
	}

	function openPrintDocument(previewHtml) {
		var printWindow = window.open('', '_blank', 'noopener,noreferrer,width=1280,height=900');
		if (!printWindow) { return false; }
		printWindow.document.open();
		printWindow.document.write('<!doctype html><html><head><meta charset="utf-8"><title>Angebot Sprecherhonorar</title></head><body>' + previewHtml + '</body></html>');
		printWindow.document.close();
		printWindow.focus();
		setTimeout(function () { printWindow.print(); }, 250);
		return true;
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

	function refreshSavedList(container) {
		var select = container.querySelector('[data-sgk-saved-list]');
		if (!select) { return; }
		var entries = getSavedCalculations();
		select.innerHTML = '<option value="">Bitte wählen</option>' + entries.map(function (entry) { return '<option value="' + htmlEscape(entry.id) + '">' + htmlEscape(buildSavedLabel(entry)) + '</option>'; }).join('');
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
		var copyBlocks = buildCopyBlocks(result, formData, {});
		var warnings = Array.isArray(result.warnings) ? result.warnings : [];
		var redirectCopy = '';
		if (uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) { redirectCopy = 'Dieser Fall wird fachlich als „' + labelFromKey(uiState.resolved_case) + '“ berechnet.'; }

		container.innerHTML = '' +
			'<section class="sgk-result-card sgk-result-card--dark">' +
				'<span class="sgk-result-kicker">Errechnete Spanne</span><h4>' + htmlEscape(result.display_title || 'Noch kein Fall aufgelöst') + '</h4><p>' + htmlEscape(redirectCopy || 'Die Empfehlung zeigt Preisrahmen und fachlich passenden Mittelwert. Die endgültige Angebotssumme setzen Sie später bewusst manuell.') + '</p>' +
				'<div class="sgk-totals"><div class="sgk-total-card"><span>Von</span><strong>' + htmlEscape(totals.lower || '0,00 €') + '</strong><small>untere Orientierung</small></div><div class="sgk-total-card sgk-total-card--featured"><span>Mittelwert</span><strong>' + htmlEscape(totals.mid || '0,00 €') + '</strong><small>Empfehlung für die Angebotsverhandlung</small></div><div class="sgk-total-card"><span>Bis</span><strong>' + htmlEscape(totals.upper || '0,00 €') + '</strong><small>obere Orientierung</small></div></div>' +
			'</section>' +
			'<section class="sgk-result-card"><span class="sgk-result-kicker">Finale Angebotssumme</span><h4>Manuell gesetzter Angebotswert</h4><div class="sgk-manual-offer"><label for="sgk-manual-offer-input">Finale Angebotssumme</label><div class="sgk-inline-input"><input id="sgk-manual-offer-input" type="number" min="0" step="0.01" value="' + htmlEscape(result.manual_offer_total || '') + '" placeholder="z. B. 2450.00" data-sgk-manual-offer /><button type="button" class="sgk-button sgk-button--secondary" data-sgk-sync-manual-offer>Übernehmen</button></div><p class="sgk-field__hint">Berechnete Spanne und Mittelwert bleiben unverändert. Dieser Wert wird separat für Angebot, Export und PDF geführt.</p><div class="sgk-manual-offer__status ' + (manualValidation.valid ? 'is-valid' : 'is-invalid') + '">' + htmlEscape(manualValidation.message) + '</div><div class="sgk-manual-offer__current"><strong>Aktuell:</strong> <span>' + htmlEscape(manualOffer) + '</span></div></div></section>' +
			'<section class="sgk-result-card"><span class="sgk-result-kicker">Angebotspositionen</span><h4>Exportierbare Positionen</h4>' + renderList(positions, function (item) { return '<li><div><strong>' + htmlEscape(item.position_number + '. ' + item.titel) + '</strong><span>' + htmlEscape(item.beschreibung) + '</span><small>' + htmlEscape((item.kategorie || '') + ' · ' + (item.lizenzbezug || '')) + '</small></div><em>' + htmlEscape(item.formatted_prices && item.formatted_prices.manual ? item.formatted_prices.manual : (item.formatted_prices ? item.formatted_prices.mid : '0,00 €')) + '</em></li>'; }, 'Nach der Berechnung erscheinen hier übertragbare Angebotspositionen.', 'sgk-breakdown-list') + '</section>' +
			'<section class="sgk-result-card"><span class="sgk-result-kicker">Nutzungsrechte & Lizenzen</span><h4>Rechteübersicht</h4>' + renderList(rights, function (item) { return '<li><strong>' + htmlEscape(item.title + (item.variant ? ' · ' + item.variant : '')) + '</strong><span>' + htmlEscape('Laufzeit: ' + item.duration + ' · Territorium: ' + item.territory + ' · Medien: ' + item.media) + '</span></li>'; }, 'Die Lizenzübersicht wird nach der Berechnung ergänzt.', 'sgk-license-list') + '</section>' +
			'<section class="sgk-result-card"><span class="sgk-result-kicker">Validierung & Warnungen</span><h4>Fachliche Hinweise</h4>' + renderList(warnings, function (note) { return '<li>' + htmlEscape(note) + '</li>'; }, 'Aktuell keine zusätzlichen Warnungen oder Guard-Hinweise.', 'sgk-note-list') + '</section>' +
			'<section class="sgk-result-card"><span class="sgk-result-kicker">Angebotslogik</span><h4>Rechenweg in Angebotssprache</h4>' + renderList(routeTrace, function (item) { return '<li><strong>' + htmlEscape(item.label || labelFromKey(item.step || 'Schritt')) + '</strong><span>' + htmlEscape(item.message || '') + '</span></li>'; }, 'Noch keine Resolver-Hinweise.', 'sgk-note-list') + '</section>' +
			'<section class="sgk-result-card"><span class="sgk-result-kicker">Hinweise</span><h4>Anmerkungen, Expertenhinweise und Angebotsnotizen</h4>' + renderList(notes, function (note) { return '<li>' + htmlEscape(note) + '</li>'; }, 'Noch keine zusätzlichen Hinweise.', 'sgk-note-list') + '</section>' +
			'<section class="sgk-result-card"><span class="sgk-result-kicker">Paket-Alternativen</span><h4>Vergleichsoptionen</h4>' + renderList(alternatives, function (item) { return '<li><strong>' + htmlEscape(item.label || 'Alternative') + '</strong><span>' + htmlEscape((item.formatted_totals && item.formatted_totals.mid) || '0,00 €') + '</span></li>'; }, 'Für diesen Fall sind aktuell keine Paket-Alternativen hinterlegt.', 'sgk-license-list') + '</section>' +
			'<section class="sgk-result-card"><span class="sgk-result-kicker">Aktionen</span><h4>Speichern, laden, kopieren, exportieren</h4><div class="sgk-action-grid sgk-action-grid--actions"><button type="button" class="sgk-button sgk-button--primary" data-label="Angebot / PDF öffnen" data-sgk-action="open-pdf">Angebot / PDF öffnen</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Zusammenfassung kopieren" data-sgk-action="copy-summary">Zusammenfassung kopieren</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Angebotspositionen kopieren" data-sgk-action="copy-positions">Angebotspositionen kopieren</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Rechteübersicht kopieren" data-sgk-action="copy-rights">Rechteübersicht kopieren</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Exportdaten kopieren" data-sgk-action="copy-json">Exportdaten kopieren</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Kalkulation speichern" data-sgk-action="save">Kalkulation speichern</button></div><div class="sgk-storage-panel"><div class="sgk-storage-panel__row"><label for="sgk-saved-calculations">Gespeicherte Kalkulationen</label><select id="sgk-saved-calculations" data-sgk-saved-list><option value="">Bitte wählen</option></select></div><div class="sgk-action-grid sgk-action-grid--storage"><button type="button" class="sgk-button sgk-button--secondary" data-label="Kalkulation laden" data-sgk-action="load">Kalkulation laden</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Kalkulation löschen" data-sgk-action="delete">Kalkulation löschen</button></div><p class="sgk-field__hint" data-sgk-storage-status>' + htmlEscape(storageAvailable() ? 'Speicherung lokal im Browser aktiv. Exportstruktur bleibt versionsfähig und PDF-ready.' : 'localStorage ist in dieser Umgebung nicht verfügbar.') + '</p></div><div class="sgk-copy-preview"><h5>Copy Summary</h5><pre>' + htmlEscape(copyBlocks.summary) + '</pre><h5>Copy Angebotspositionen</h5><pre>' + htmlEscape(copyBlocks.positions) + '</pre><h5>Copy Rechteübersicht</h5><pre>' + htmlEscape(copyBlocks.rights) + '</pre></div></section>';
	}

	function requestCalculation(app, form, resultContainer) {
		var payload = serializeForm(form);
		if (!payload.case_key) { return; }
		resultContainer.innerHTML = '<div class="sgk-result-empty"><strong>Berechnung läuft</strong><p>Resolver, Rechte-Logik und Kalkulationsspanne werden gerade aktualisiert.</p></div>';
		fetch(sgkFrontend.restUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': sgkFrontend.nonce }, body: JSON.stringify(payload) })
			.then(function (response) {
				if (!response.ok) { throw new Error('request-failed'); }
				return response.json();
			})
			.then(function (json) {
				if (!json || !json.result) { throw new Error('invalid-payload'); }
				app.__sgkLastPayload = json;
				renderResult(resultContainer, json, payload);
				updateExpertBadges(app, json.ui_state || {});
				updateRedirectBanner(app, json);
				refreshSavedList(resultContainer);
			})
			.catch(function () {
				updateRedirectBanner(app, null);
				resultContainer.innerHTML = '<div class="sgk-result-empty"><strong>Berechnung momentan nicht verfügbar</strong><p>Die REST-Berechnung konnte nicht geladen werden.</p></div>';
			});
	}

	function hydrateOfferModal(app, result, formData) {
		var modal = app.querySelector('[data-sgk-offer-modal]');
		var preview = modal.querySelector('[data-sgk-offer-preview]');
		var status = modal.querySelector('[data-sgk-offer-status]');
		var offerDate = modal.querySelector('[data-sgk-offer-meta="offer_date"]');
		if (offerDate && !offerDate.value) { offerDate.value = todayIso(); }
		var meta = getOfferMeta(app, formData);
		var offerPreview = renderOfferPreview(result, formData, meta);
		preview.innerHTML = offerPreview.html;
		status.textContent = offerPreview.validation.message;
		status.className = 'sgk-field__hint ' + (offerPreview.validation.valid ? 'is-valid' : 'is-invalid');
		app.__sgkOfferPreview = offerPreview;
	}

	document.addEventListener('DOMContentLoaded', function () {
		var app = document.querySelector('[data-sgk-app]');
		if (!app || typeof fetch !== 'function') { return; }
		var form = app.querySelector('[data-sgk-form]');
		var resultContainer = app.querySelector('[data-sgk-result]');
		var cases = parseJsonAttribute(app, 'data-sgk-cases');
		var modal = app.querySelector('[data-sgk-offer-modal]');
		var debounceTimer = null;

		function syncUI() {
			var selectedCase = form.querySelector('[name="case_key"]').value;
			var effectiveCase = cases[selectedCase] ? selectedCase : (SCENARIO_TO_CASE[selectedCase] || selectedCase);
			updateCaseContext(app, selectedCase, cases);
			populateVariants(form, effectiveCase);
			toggleBlocks(form, effectiveCase, !!selectedCase);
			updateRedirectBanner(app, app.__sgkLastPayload || null);
		}
		function currentState() { return { payload: app.__sgkLastPayload, formData: serializeForm(form) }; }
		function openModal() { modal.hidden = false; document.body.classList.add('sgk-modal-open'); }
		function closeModal() { modal.hidden = true; document.body.classList.remove('sgk-modal-open'); }

		app.querySelectorAll('[data-sgk-quick-case]').forEach(function (button) { button.addEventListener('click', function () { form.querySelector('[name="case_key"]').value = button.getAttribute('data-sgk-quick-case'); syncUI(); requestCalculation(app, form, resultContainer); }); });
		app.querySelectorAll('[data-sgk-demo]').forEach(function (button) { button.addEventListener('click', function () { fillForm(form, JSON.parse(button.getAttribute('data-sgk-demo') || '{}')); syncUI(); requestCalculation(app, form, resultContainer); }); });
		form.addEventListener('change', function () { syncUI(); clearTimeout(debounceTimer); debounceTimer = setTimeout(function () { requestCalculation(app, form, resultContainer); }, 180); });
		form.addEventListener('input', function () { clearTimeout(debounceTimer); debounceTimer = setTimeout(function () { requestCalculation(app, form, resultContainer); }, 260); });
		form.addEventListener('submit', function (event) { event.preventDefault(); syncUI(); requestCalculation(app, form, resultContainer); });

		resultContainer.addEventListener('click', function (event) {
			var action = event.target.getAttribute('data-sgk-action');
			var state = currentState();
			if (!action || !state.payload || !state.payload.result) { return; }
			var copyBlocks = buildCopyBlocks(state.payload.result, state.formData, getOfferMeta(app, state.formData));
			var statusNode = resultContainer.querySelector('[data-sgk-storage-status]');
			var entries, selectedId, entry;

			if (action === 'open-pdf') { hydrateOfferModal(app, state.payload.result, state.formData); openModal(); return; }
			if (action === 'copy-summary') { copyText(copyBlocks.summary, event.target); return; }
			if (action === 'copy-positions') { copyText(copyBlocks.positions, event.target); return; }
			if (action === 'copy-rights') { copyText(copyBlocks.rights, event.target); return; }
			if (action === 'copy-json') { copyText(copyBlocks.json, event.target); return; }
			if (action === 'save') {
				entries = getSavedCalculations();
				entry = { id: 'sgk-' + Date.now(), version: 2, savedAt: new Date().toISOString(), projectTitle: state.formData.project_title || state.payload.result.display_title || 'Kalkulation', formData: state.formData, result: state.payload.result, exportPayload: buildExportPayload(state.payload.result, state.formData, getOfferMeta(app, state.formData)) };
				setSavedCalculations([entry].concat(entries).slice(0, 15)); refreshSavedList(resultContainer); if (statusNode) { statusNode.textContent = 'Kalkulation lokal gespeichert: ' + buildSavedLabel(entry); } return;
			}
			selectedId = (resultContainer.querySelector('[data-sgk-saved-list]') || {}).value;
			if (!selectedId) { if (statusNode) { statusNode.textContent = 'Bitte zuerst eine gespeicherte Kalkulation auswählen.'; } return; }
			entries = getSavedCalculations(); entry = entries.find(function (item) { return item.id === selectedId; }); if (!entry) { if (statusNode) { statusNode.textContent = 'Gespeicherte Kalkulation konnte nicht geladen werden.'; } return; }
			if (action === 'load') { fillForm(form, entry.formData || {}); syncUI(); requestCalculation(app, form, resultContainer); if (statusNode) { statusNode.textContent = 'Kalkulation geladen: ' + buildSavedLabel(entry); } return; }
			if (action === 'delete') { setSavedCalculations(entries.filter(function (item) { return item.id !== selectedId; })); refreshSavedList(resultContainer); if (statusNode) { statusNode.textContent = 'Kalkulation gelöscht.'; } }
		});

		resultContainer.addEventListener('click', function (event) {
			if (!event.target.hasAttribute('data-sgk-sync-manual-offer')) { return; }
			var input = resultContainer.querySelector('[data-sgk-manual-offer]');
			var value = normalizeNumber(input && input.value);
			if (value == null) { input.focus(); return; }
			form.querySelector('[name="manual_offer_total"]').value = String(value);
			requestCalculation(app, form, resultContainer);
		});

		modal.querySelectorAll('[data-sgk-offer-close]').forEach(function (button) { button.addEventListener('click', closeModal); });
		modal.addEventListener('input', function () { var state = currentState(); if (state.payload && state.payload.result && !modal.hidden) { hydrateOfferModal(app, state.payload.result, state.formData); } });
		modal.addEventListener('click', function (event) {
			var action = event.target.getAttribute('data-sgk-offer-action');
			if (!action) { return; }
			var state = currentState();
			if (!state.payload || !state.payload.result) { return; }
			hydrateOfferModal(app, state.payload.result, state.formData);
			if (action === 'copy-mail') { copyText(app.__sgkOfferPreview.text, event.target); return; }
			if (action === 'print') { openPrintDocument(app.__sgkOfferPreview.html); }
		});
		document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && !modal.hidden) { closeModal(); } });

		syncUI();
	});
})();
