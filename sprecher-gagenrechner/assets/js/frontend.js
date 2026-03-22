(function () {
	'use strict';

	var CASE_UI = {
		werbung_mit_bild: { variantOptions: [['online_video_paid_media', 'Online Video Paid Media'], ['atv_ctv_video_spot', 'ATV / CTV Video Spot'], ['linear_tv_spot', 'Linear TV Spot'], ['linear_tv_reminder', 'TV Reminder'], ['tv_patronat', 'TV Patronat'], ['atv_ctv_patronat', 'ATV / CTV Patronat'], ['kino_spot', 'Kino Spot'], ['pos_spot', 'POS Spot'], ['animatic_narrative_moodfilm', 'Animatic / Narrative / Moodfilm'], ['layout', 'Layout']], show: ['variant', 'usage_type', 'media_toggles', 'duration_minutes', 'addon_counts', 'rights_toggles'], scopeCopy: 'Bei Werbefällen stehen vor allem Spot-Ausprägung, Rechte-Erweiterungen und Zusatzmotive im Fokus.' },
		werbung_ohne_bild: { variantOptions: [['online_audio_paid_media', 'Online Audio Paid Media'], ['funk_spot_national', 'Funkspot national'], ['funk_spot_regional', 'Funkspot regional'], ['funk_reminder', 'Funk Reminder'], ['funk_allongen', 'Funk Allongen'], ['ladenfunk_national', 'Ladenfunk national'], ['ladenfunk_regional', 'Ladenfunk regional'], ['telefon_werbespot', 'Telefon-Werbespot'], ['layout', 'Layout']], show: ['variant', 'usage_type', 'addon_counts', 'rights_toggles'], scopeCopy: 'Audio-Werbung arbeitet überwiegend mit Varianten, Reminder-/Allongen-Logik und passenden Zusatzrechten.' },
		webvideo_imagefilm_praesentation_unpaid: { show: ['usage_type', 'media_toggles', 'duration_minutes'], scopeCopy: 'Für unpaid Bildfälle wird hauptsächlich die Minutenstaffel inklusive optionaler Zusatzlizenzen geführt.' },
		app: { show: ['duration_minutes'], scopeCopy: 'Apps werden in der Regel über eine minutenbasierte Standardnutzung mit unbegrenzter Laufzeit kalkuliert.' },
		telefonansage: { show: ['module_count'], scopeCopy: 'Telefonansagen werden über die Anzahl der Module erfasst. Andere Medienoptionen spielen hier in der Regel keine Rolle.' },
		elearning_audioguide: { variantOptions: [['elearning_intern', 'E-Learning intern'], ['audioguide', 'Audioguide']], show: ['variant', 'duration_minutes'], scopeCopy: 'E-Learning und Audioguides basieren auf Minutenstaffeln und der passenden Inhaltsart.' },
		podcast: { variantOptions: [['podcast_inhalte', 'Podcast-Inhalte'], ['non_commercial_3', 'Verpackung nicht-kommerziell 3 Jahre'], ['non_commercial_unlim', 'Verpackung nicht-kommerziell unbegrenzt'], ['marketing_3', 'Verpackung Marketing 3 Jahre'], ['marketing_unlim', 'Verpackung Marketing unbegrenzt']], show: ['variant', 'usage_type', 'duration_minutes'], scopeCopy: 'Bei Podcasts unterscheiden wir zwischen Inhalt und Verpackung, damit dein Projekt passend eingeordnet wird.' },
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
	function icon(name) {
		var icons = {
			project: '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Zm2.5-1a1 1 0 0 0-1 1v2.25h13V6.5a1 1 0 0 0-1-1h-11Zm12 4.75h-13v7.25a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-7.25Z"/></svg>',
			amount: '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3a1 1 0 0 1 1 1v1.09a4.75 4.75 0 0 1 3.68 2.2 1 1 0 1 1-1.7 1.06A2.74 2.74 0 0 0 12.6 7h-1.05c-1.19 0-2.05.75-2.05 1.7 0 1 .77 1.52 2.76 1.98 2.53.58 4.74 1.27 4.74 4.05A4.2 4.2 0 0 1 13 18.85V20a1 1 0 1 1-2 0v-1.08a4.83 4.83 0 0 1-4.08-2.72 1 1 0 0 1 1.8-.88A2.95 2.95 0 0 0 11.4 17H12c1.68 0 3-.96 3-2.27 0-1.07-.7-1.66-3.18-2.24-2.2-.5-4.32-1.2-4.32-3.8A3.75 3.75 0 0 1 11 5.09V4a1 1 0 0 1 1-1Z"/></svg>',
			rights: '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2 4 5v6c0 5 3.4 9.53 8 11 4.6-1.47 8-6 8-11V5l-8-3Zm0 2.1 6 2.25V11c0 4.06-2.56 7.8-6 9.14C8.56 18.8 6 15.06 6 11V6.35L12 4.1Zm-1.08 9.9L8.8 11.88a1 1 0 1 0-1.42 1.41l2.83 2.83a1 1 0 0 0 1.42 0l5.66-5.66a1 1 0 0 0-1.41-1.41l-4.96 4.95Z"/></svg>',
			notes: '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M6 4h12a2 2 0 0 1 2 2v14l-4-3H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm0 2v9h10.67L18 16.25V6H6Zm2 2h8v2H8V8Zm0 4h6v2H8v-2Z"/></svg>',
			actions: '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3a1 1 0 0 1 1 1v7.59l2.3-2.3a1 1 0 1 1 1.4 1.42l-4 3.99a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l2.3 2.29V4a1 1 0 0 1 1-1Zm-7 14a1 1 0 0 1 1 1v1h12v-1a1 1 0 1 1 2 0v2a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1Z"/></svg>',
			info: '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 8a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0v-5a1 1 0 0 1 1-1Zm0-4a1.25 1.25 0 1 1 0 2.5A1.25 1.25 0 0 1 12 6Z"/></svg>'
		};
		return icons[name] || icons.info;
	}
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
		if (value == null || value === '') { return { valid: false, message: 'Für ein fertiges Angebot solltest du einen finalen Angebotswert festlegen.' }; }
		if (value <= 0) { return { valid: false, message: 'Bitte trage einen positiven Angebotswert ein.' }; }
		if (result && result.totals && value < result.totals.lower * 0.25) { return { valid: false, message: 'Der eingetragene Angebotswert liegt deutlich unter dem empfohlenen Empfohlene Spanne.' }; }
		return { valid: true, message: 'Der Angebotswert wird separat übernommen, ohne die Empfehlung zu verändern.' };
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

	function prettifyRouteMessage(message) {
		var raw = String(message || '');
		if (!raw) { return 'Die Auswahl wurde passend für die Kalkulation eingeordnet.'; }
		return raw
			.replace(/Resolver/gi, 'Auswahl')
			.replace(/Berechnungsengine/gi, 'Kalkulation')
			.replace(/normalisiert/gi, 'geordnet')
			.replace(/suppressed invalid paths/gi, 'nicht passende Varianten')
			.replace(/Redirect aktiv/gi, 'Diese Auswahl wird')
			.replace(/fachlich sauber/gi, 'passend')
			.replace(/Berechnungspfad/gi, 'Einordnung')
			.replace(/route trace/gi, 'Einordnung')
			.replace(/aktivierte Regeln/gi, 'berücksichtigte Auswahl')
			.replace(/Resolver-Logik/gi, 'Zuordnung')
			.trim();
	}

	function routeLabel(step, label) {
		var map = {
			resolver: 'Auswahl',
			normalization: 'Auswahl',
			redirect: 'Einordnung',
			suppressed_invalid_path: 'Bereinigt',
			case: 'Projektart'
		};
		return map[step] || label || 'Schritt';
	}

	function updateRedirectBanner(app, payload) {
		var banner = app.querySelector('[data-sgk-redirect-banner]');
		if (!banner) { return; }
		var uiState = (payload && payload.ui_state) || {};
		var result = (payload && payload.result) || {};
		var warnings = Array.isArray(result.warnings) ? result.warnings : [];
		var parts = [];
		if (uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) {
			parts.push('Diese Auswahl wird als „' + labelFromKey(uiState.resolved_case) + '“ kalkuliert.');
		}
		if (warnings.length) {
			parts.push('Hinweise & Wissenswertes: ' + warnings.join(' · '));
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
			mail: [offerMeta && offerMeta.intro_text ? offerMeta.intro_text : 'Vielen Dank für deine Anfrage. Nachfolgend erhältst du dein Angebot.', '', texts.offer_headline || '', texts.copy_summary || '', texts.positions_block || '', '', texts.rights_block || '', '', texts.notes_block || '', '', texts.legal_notice_block || ''].filter(Boolean).join('\n')
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
							'<div class="meta-card"><div class="section-title">Projekt</div><strong>' + htmlEscape(formData.project_title || summary.display_title || summary.title || 'Berechnung') + '</strong><small class="muted">Projektart: ' + htmlEscape(summary.display_title || summary.title || 'Projekt') + '</small></div>' +
							'<div class="meta-card"><div class="section-title">Absender</div><strong>' + htmlEscape(offerMeta.sender_company || 'Studio / Absender') + '</strong><small class="muted">' + htmlEscape([offerMeta.sender_email, offerMeta.sender_phone].filter(Boolean).join(' · ')) + '</small></div>' +
							'<div class="meta-card"><div class="section-title">Dokument</div><strong>' + htmlEscape(offerMeta.offer_number || 'Ohne Nummer') + '</strong><small class="muted">Stand ' + htmlEscape(formatDate(offerMeta.offer_date)) + '</small></div>' +
						'</div>' +
					'</div>' +
					'<div class="summary-grid">' +
						'<div class="summary-card"><div class="section-title">Angebotsbasis</div><p>' + htmlEscape(offerMeta.intro_text || 'Vielen Dank für deine Anfrage. Nachfolgend erhältst du dein Angebot auf Basis der abgestimmten Nutzung und der aktuellen Preisermittlung.') + '</p></div>' +
						'<div class="summary-card"><div class="section-title">Errechnete Spanne</div><strong>' + htmlEscape(rangeText) + '</strong><small class="muted">Mittelwert ' + htmlEscape(midText) + '</small></div>' +
						'<div class="summary-card summary-card--total"><div class="section-title" style="color:rgba(255,255,255,.72)">Finale Angebotssumme</div><strong style="font-size:28px;display:block;">' + htmlEscape(totalText) + '</strong><small>' + htmlEscape(exportPayload.manual_offer_total != null ? 'Manuell gesetzt und als Angebotswert übernommen.' : 'Bitte vor PDF-Ausgabe final festlegen.') + '</small></div>' +
					'</div>' +
					'<div class="section positions"><div class="section-title">Angebotspositionen</div>' + positions.map(function (item) { var price = item.manuell_uebernommener_preis != null ? currency(item.manuell_uebernommener_preis) : currency(item.empfohlener_preis); return '<div class="position-row"><div><strong>' + htmlEscape(item.position_number + '. ' + item.titel) + '</strong><small>' + htmlEscape(item.beschreibung || '') + '</small><small>' + htmlEscape((item.kategorie || '') + (item.lizenzbezug ? ' · ' + item.lizenzbezug : '')) + '</small></div><div><strong>' + htmlEscape(price) + '</strong></div></div>'; }).join('') + '</div>' +
					'<div class="section rights"><div class="section-title">Nutzungsrechte & Lizenzen</div>' + (rights.length ? rights.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.title + (item.variant ? ' · ' + item.variant : '')) + '</strong><small>Laufzeit: ' + htmlEscape(item.duration) + ' · Territorium: ' + htmlEscape(item.territory) + ' · Medien: ' + htmlEscape(item.media) + '</small></div><div><span class="badge">Rechteblock</span></div></div>'; }).join('') : '<p class="muted">Keine zusätzlichen Rechteinformationen vorhanden.</p>') + '</div>' +
					'<div class="section notes"><div class="section-title">Hinweise & Anmerkungen</div>' + (notes.length ? notes.map(function (item) { return '<p style="padding:10px 0;border-top:1px solid #dbe6f1;">' + htmlEscape(item) + '</p>'; }).join('') : '<p class="muted">Keine zusätzlichen Hinweise.</p>') + '</div>' +
					(legal.length ? '<div class="section"><div class="section-title">Rechtlicher Hinweis</div>' + legal.map(function (item) { return '<p>' + htmlEscape(item) + '</p>'; }).join('') + '</div>' : '') +
					(alternatives.length ? '<div class="section"><div class="section-title">Optionale Paket-Alternativen</div>' + alternatives.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.label || 'Alternative') + '</strong></div><div><strong>' + htmlEscape(item.formatted_totals ? item.formatted_totals.mid : '') + '</strong></div></div>'; }).join('') + '</div>' : '') +
					'<div class="footer"><div>' + htmlEscape(offerMeta.footer_text || [offerMeta.sender_company, offerMeta.sender_email, offerMeta.sender_phone].filter(Boolean).join(' · ')) + '</div><div>Dieses Dokument basiert auf der aktuellen Preisermittlung im Sprecher-Gagenrechner. Interne Angaben werden im Kundendokument nicht ausgegeben.</div></div>' +
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
		if (!options.length) { select.innerHTML = '<option value="">Automatisch passend auswählen</option>'; hint.textContent = 'Für diese Projektart ist keine zusätzliche Auswahl nötig.'; return; }
		select.innerHTML = '<option value="">Bitte Variante wählen</option>' + options.map(function (item) { return '<option value="' + htmlEscape(item[0]) + '">' + htmlEscape(item[1]) + '</option>'; }).join('');
		if (current) { select.value = current; }
		hint.textContent = 'Die Varianten passen sich automatisch deinem Projekt an.';
	}

	function toggleBlocks(form, effectiveCase, hasSelection) {
		var visible = ((CASE_UI[effectiveCase] && CASE_UI[effectiveCase].show) || []).concat(['scope_note']);
		form.querySelectorAll('[data-sgk-block]').forEach(function (block) { var key = block.getAttribute('data-sgk-block'); block.classList.toggle('sgk-hidden', hasSelection ? visible.indexOf(key) === -1 : true); });
		form.querySelectorAll('[data-sgk-dependent-step]').forEach(function (step) { step.classList.toggle('is-disabled', !hasSelection); });
		var expertShell = form.querySelector('[data-sgk-expert-shell]');
		if (expertShell) { expertShell.classList.toggle('is-disabled', !hasSelection); }
		var scopeCopy = form.querySelector('[data-sgk-scope-copy]');
		if (scopeCopy) { scopeCopy.textContent = hasSelection ? ((CASE_UI[effectiveCase] && CASE_UI[effectiveCase].scopeCopy) || 'Die Angaben werden passend zu deiner Auswahl geführt.') : 'Wähle zuerst eine Projektart, damit wir dir die passenden Umfangsangaben zeigen können.'; }
	}

	function updateCaseContext(app, selectedCase, cases) {
		var node = app.querySelector('[data-sgk-case-context]');
		app.querySelectorAll('[data-sgk-quick-case]').forEach(function (button) { button.classList.toggle('is-active', button.getAttribute('data-sgk-quick-case') === selectedCase); });
		if (!selectedCase) { node.innerHTML = '<strong>Noch keine Projektart gewählt</strong><p>Nach deiner Auswahl zeigen wir dir nur die Eingaben, die für diesen Fall wirklich relevant sind.</p>'; return; }
		var effectiveCase = cases[selectedCase] ? selectedCase : (SCENARIO_TO_CASE[selectedCase] || selectedCase);
		var caseData = cases[effectiveCase] || {};
		node.innerHTML = '<strong>' + htmlEscape(caseData.label || labelFromKey(selectedCase)) + '</strong><p>' + htmlEscape(caseData.description || 'Die Eingaben wurden passend zu deiner Auswahl zusammengestellt.') + '</p>';
	}

	function updateExpertBadges(app, uiState) {
		var container = app.querySelector('[data-sgk-expert-badges]');
		var flags = (uiState && uiState.available_expert_options) || [];
		if (!container) { return; }
		container.innerHTML = !flags.length ? '<span class="sgk-badge is-muted">Noch keine zusätzlichen Optionen aktiv</span>' : flags.map(function (flag) { return '<span class="sgk-badge">' + htmlEscape(labelFromKey(flag)) + '</span>'; }).join('');
	}

	function refreshSavedList(container) {
		var select = container.querySelector('[data-sgk-saved-list]');
		if (!select) { return; }
		var entries = getSavedCalculations();
		select.innerHTML = '<option value="">Bitte auswählen</option>' + entries.map(function (entry) { return '<option value="' + htmlEscape(entry.id) + '">' + htmlEscape(buildSavedLabel(entry)) + '</option>'; }).join('');
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
		var manualOffer = result.formatted_manual_offer_total || 'Noch nicht festgelegt';
		var manualValidation = validateManualOffer(result.manual_offer_total, result);
		var copyBlocks = buildCopyBlocks(result, formData, {});
		var warnings = Array.isArray(result.warnings) ? result.warnings : [];
		var redirectCopy = '';
		if (uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) {
			redirectCopy = 'Diese Auswahl wird als „' + labelFromKey(uiState.resolved_case) + '“ kalkuliert.';
		}

		container.innerHTML = '' +
			'<section class="sgk-result-card sgk-result-card--hero">' +
				'<div class="sgk-result-card__head"><div><span class="sgk-result-pill">' + icon('amount') + ' Preisrahmen</span><h4 class="sgk-result-title">' + htmlEscape(result.display_title || 'Dein Ergebnis erscheint hier') + '</h4><p>' + htmlEscape(redirectCopy || 'Du siehst hier die empfohlene Spanne, den Mittelwert und die aktuelle Basis für dein Angebot.') + '</p></div><span class="sgk-result-icon">' + icon('project') + '</span></div>' +
				'<div class="sgk-totals"><div class="sgk-total-card"><span>Spanne ab</span><strong>' + htmlEscape(totals.lower || '0,00 €') + '</strong><small>untere Orientierung</small></div><div class="sgk-total-card sgk-total-card--featured"><span>Mittelwert</span><strong>' + htmlEscape(totals.mid || '0,00 €') + '</strong><small>empfohlene Mitte</small></div><div class="sgk-total-card"><span>Spanne bis</span><strong>' + htmlEscape(totals.upper || '0,00 €') + '</strong><small>obere Orientierung</small></div></div>' +
				'<div class="sgk-insight-list"><div class="sgk-insight-card"><h4>Angebotssumme</h4><strong>' + htmlEscape(manualOffer) + '</strong><p>Separat für Angebot und PDF.</p></div><div class="sgk-insight-card"><h4>Kalkulationsbasis</h4><strong>' + htmlEscape(String(positions.length)) + '</strong><p>Positionen für Angebot und Abstimmung.</p></div><div class="sgk-insight-card"><h4>Rechteumfang</h4><strong>' + htmlEscape(String(rights.length)) + '</strong><p>Relevante Nutzungsangaben.</p></div></div>' +
			'</section>' +
			'<section class="sgk-result-card"><div class="sgk-result-card__head"><div><span class="sgk-result-kicker">' + icon('amount') + ' Angebot</span><h4 class="sgk-result-title">Finalen Angebotswert festlegen</h4><p>Die Empfehlung bleibt unverändert. Hier hinterlegst du bei Bedarf den finalen Angebotswert.</p></div><span class="sgk-result-icon">' + icon('amount') + '</span></div><div class="sgk-manual-offer"><label for="sgk-manual-offer-input">Finaler Angebotswert</label><div class="sgk-inline-input"><input id="sgk-manual-offer-input" type="number" min="0" step="0.01" value="' + htmlEscape(result.manual_offer_total || '') + '" placeholder="z. B. 2450.00" data-sgk-manual-offer /><button type="button" class="sgk-button sgk-button--secondary" data-sgk-sync-manual-offer>Übernehmen</button></div><div class="sgk-manual-offer__status ' + (manualValidation.valid ? 'is-valid' : 'is-invalid') + '">' + htmlEscape(manualValidation.message) + '</div><div class="sgk-manual-offer__current"><strong>Aktuell hinterlegt:</strong> <span>' + htmlEscape(manualOffer) + '</span></div></div></section>' +
			'<section class="sgk-result-card"><div class="sgk-result-card__head"><div><span class="sgk-result-kicker">' + icon('project') + ' Positionen</span><h4 class="sgk-result-title">Angebotspositionen</h4><p>Diese Positionen kannst du direkt für Angebot oder Abstimmung übernehmen.</p></div><span class="sgk-result-icon">' + icon('project') + '</span></div>' + renderList(positions, function (item) { return '<li><div><strong>' + htmlEscape(item.position_number + '. ' + item.titel) + '</strong><span>' + htmlEscape(item.beschreibung) + '</span><small>' + htmlEscape((item.kategorie || '') + ' · ' + (item.lizenzbezug || '')) + '</small></div><em>' + htmlEscape(item.formatted_prices && item.formatted_prices.manual ? item.formatted_prices.manual : (item.formatted_prices ? item.formatted_prices.mid : '0,00 €')) + '</em></li>'; }, 'Nach der Berechnung erscheinen hier deine Angebotspositionen.', 'sgk-breakdown-list') + '</section>' +
			'<section class="sgk-result-card"><div class="sgk-result-card__head"><div><span class="sgk-result-kicker">' + icon('rights') + ' Rechte</span><h4 class="sgk-result-title">Rechteübersicht</h4><p>Hier findest du die wichtigsten Angaben zu Laufzeit, Gebiet und Medien.</p></div><span class="sgk-result-icon">' + icon('rights') + '</span></div>' + renderList(rights, function (item) { return '<li><div><strong>' + htmlEscape(item.title + (item.variant ? ' · ' + item.variant : '')) + '</strong><span>' + htmlEscape('Laufzeit: ' + item.duration + ' · Gebiet: ' + item.territory + ' · Medien: ' + item.media) + '</span></div></li>'; }, 'Sobald Rechte relevant sind, erscheint hier die Übersicht.', 'sgk-license-list') + '</section>' +
			'<section class="sgk-result-card"><div class="sgk-result-card__head"><div><span class="sgk-result-kicker">' + icon('info') + ' Einordnung</span><h4 class="sgk-result-title">So wurde deine Auswahl eingeordnet</h4><p>So wurde deine Auswahl für die Kalkulation eingeordnet.</p></div><span class="sgk-result-icon">' + icon('info') + '</span></div>' + renderList(routeTrace, function (item) { return '<li><strong>' + htmlEscape(routeLabel(item.step, item.label)) + '</strong><span>' + htmlEscape(prettifyRouteMessage(item.message)) + '</span></li>'; }, 'Sobald Angaben vorliegen, ergänzen wir hier die kurze Einordnung.', 'sgk-note-list') + '</section>' +
			'<section class="sgk-result-card"><div class="sgk-result-card__head"><div><span class="sgk-result-kicker">' + icon('notes') + ' Hinweise</span><h4 class="sgk-result-title">Wichtige Hinweise</h4><p>Besonderheiten zu deinem Projekt werden hier kompakt gesammelt.</p></div><span class="sgk-result-icon">' + icon('notes') + '</span></div>' + renderList(warnings, function (note) { return '<li>' + htmlEscape(note) + '</li>'; }, 'Aktuell gibt es keine zusätzlichen Hinweise zu deiner Auswahl.', 'sgk-note-list') + renderList(notes, function (note) { return '<li>' + htmlEscape(note) + '</li>'; }, 'Noch keine weiteren Anmerkungen vorhanden.', 'sgk-note-list') + '</section>' +
			'<section class="sgk-result-card"><div class="sgk-result-card__head"><div><span class="sgk-result-kicker">' + icon('actions') + ' Weiterarbeiten</span><h4 class="sgk-result-title">Angebot & Export</h4><p>Die wichtigsten nächsten Schritte direkt aus der Übersicht.</p></div><span class="sgk-result-icon">' + icon('actions') + '</span></div><div class="sgk-action-grid sgk-action-grid--actions"><button type="button" class="sgk-button sgk-button--primary" data-label="Angebot öffnen" data-sgk-action="open-pdf">Angebot öffnen</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Zusammenfassung kopieren" data-sgk-action="copy-summary">Zusammenfassung kopieren</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Positionen kopieren" data-sgk-action="copy-positions">Positionen kopieren</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Rechte kopieren" data-sgk-action="copy-rights">Rechte kopieren</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Berechnung speichern" data-sgk-action="save">Lokal speichern</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Exportdaten kopieren" data-sgk-action="copy-json">Exportdaten kopieren</button></div><div class="sgk-storage-panel"><div class="sgk-storage-panel__row"><label for="sgk-saved-calculations">Gespeicherte Kalkulationen</label><select id="sgk-saved-calculations" data-sgk-saved-list><option value="">Bitte auswählen</option></select></div><div class="sgk-action-grid sgk-action-grid--storage"><button type="button" class="sgk-button sgk-button--secondary" data-label="Berechnung laden" data-sgk-action="load">Laden</button><button type="button" class="sgk-button sgk-button--secondary" data-label="Berechnung löschen" data-sgk-action="delete">Löschen</button></div><p class="sgk-storage-panel__meta" data-sgk-storage-status>' + htmlEscape(storageAvailable() ? 'Deine Kalkulationen werden lokal im Browser gespeichert.' : 'Lokales Speichern ist in dieser Umgebung nicht verfügbar.') + '</p></div><div class="sgk-copy-preview"><h5>Zusammenfassung</h5><pre>' + htmlEscape(copyBlocks.summary) + '</pre><h5>Positionen</h5><pre>' + htmlEscape(copyBlocks.positions) + '</pre><h5>Rechte</h5><pre>' + htmlEscape(copyBlocks.rights) + '</pre></div></section>' +
			(alternatives.length ? '<section class="sgk-result-card"><div class="sgk-result-card__head"><div><span class="sgk-result-kicker">' + icon('info') + ' Alternativen</span><h4 class="sgk-result-title">Weitere Preisoptionen</h4><p>Wenn Alternativen vorliegen, erscheinen sie hier im direkten Vergleich.</p></div><span class="sgk-result-icon">' + icon('info') + '</span></div>' + renderList(alternatives, function (item) { return '<li><div><strong>' + htmlEscape(item.label || 'Alternative') + '</strong><span>' + htmlEscape('Empfohlene Mitte: ' + ((item.formatted_totals && item.formatted_totals.mid) || '0,00 €')) + '</span></div></li>'; }, 'Für diese Auswahl sind aktuell keine Alternativen hinterlegt.', 'sgk-license-list') + '</section>' : '');
	}

	function requestCalculation(app, form, resultContainer) {
		var payload = serializeForm(form);
		if (!payload.case_key) { return; }
		resultContainer.innerHTML = '<div class="sgk-result-empty"><strong>Deine Kalkulation wird aktualisiert</strong><p>Preisrahmen, Rechte und Zusammenfassung werden gerade neu aufgebaut.</p></div>';
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
				resultContainer.innerHTML = '<div class="sgk-result-empty"><strong>Die Kalkulation ist gerade nicht verfügbar</strong><p>Bitte versuche es in einem Moment noch einmal.</p></div>';
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
		var lastFocusedElement = null;

		function syncUI() {
			var selectedCase = form.querySelector('[name="case_key"]').value;
			var effectiveCase = cases[selectedCase] ? selectedCase : (SCENARIO_TO_CASE[selectedCase] || selectedCase);
			updateCaseContext(app, selectedCase, cases);
			populateVariants(form, effectiveCase);
			toggleBlocks(form, effectiveCase, !!selectedCase);
			updateRedirectBanner(app, app.__sgkLastPayload || null);
		}
		function currentState() { return { payload: app.__sgkLastPayload, formData: serializeForm(form) }; }
		function setModalState(isOpen) {
			var dialog = modal.querySelector('[role="dialog"]');
			var closeButton = modal.querySelector('[data-sgk-offer-close]');
			modal.hidden = !isOpen;
			modal.classList.toggle('is-open', isOpen);
			modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
			document.body.classList.toggle('sgk-modal-open', isOpen);
			if (isOpen) {
				lastFocusedElement = document.activeElement;
				window.requestAnimationFrame(function () {
					if (dialog) { dialog.focus(); }
					if (closeButton) { closeButton.focus(); }
				});
				return;
			}
			if (lastFocusedElement && typeof lastFocusedElement.focus === 'function' && document.contains(lastFocusedElement)) {
				lastFocusedElement.focus();
			}
			lastFocusedElement = null;
		}
		function openModal() { setModalState(true); }
		function closeModal() { setModalState(false); }

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
				setSavedCalculations([entry].concat(entries).slice(0, 15));
				refreshSavedList(resultContainer);
				if (statusNode) { statusNode.textContent = 'Kalkulation lokal gespeichert: ' + buildSavedLabel(entry); }
				return;
			}
			selectedId = (resultContainer.querySelector('[data-sgk-saved-list]') || {}).value;
			if (!selectedId) { if (statusNode) { statusNode.textContent = 'Bitte wähle zuerst eine gespeicherte Kalkulation aus.'; } return; }
			entries = getSavedCalculations();
			entry = entries.find(function (item) { return item.id === selectedId; });
			if (!entry) { if (statusNode) { statusNode.textContent = 'Die gespeicherte Kalkulation konnte nicht geladen werden.'; } return; }
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

		setModalState(false);
		syncUI();
	});
})();
