(function () {
	'use strict';

	var STORAGE_VERSION = 2;
	var STORAGE_KEY = 'sgk_calculations_v' + STORAGE_VERSION;
	var APP_STATE_VERSION = 2;
	var DEFAULT_RESULT_MESSAGE = '<div class="src-result-empty"><strong>Projekt auswählen</strong><p>Sobald links eine Projektart aktiv ist, erscheint hier die empfohlene Netto-Gage mit Aufschlüsselung.</p></div>';
	var LOADING_RESULT_MESSAGE = '<div class="src-result-empty"><strong>Deine Kalkulation wird aktualisiert</strong><p>Preisrahmen, Rechte und Zusammenfassung werden gerade neu aufgebaut.</p></div>';
	var ERROR_RESULT_MESSAGE = '<div class="src-result-empty"><strong>Die Kalkulation ist gerade nicht verfügbar</strong><p>Bitte prüfe die Eingaben oder versuche es in einem Moment noch einmal.</p></div>';
	var CASE_UI = {
		werbung_mit_bild: { variantOptions: [['online_video_paid_media', 'Online Video Paid Media'], ['atv_ctv_video_spot', 'ATV / CTV Video Spot'], ['linear_tv_spot_national', 'Linear TV Spot national'], ['linear_tv_spot_regional', 'Linear TV Spot regional'], ['tv_patronat', 'TV Patronat'], ['atv_ctv_patronat', 'ATV / CTV Patronat'], ['kino_spot_national', 'Kino Spot national'], ['kino_spot_regional', 'Kino Spot regional'], ['pos_event_messe', 'POS / Event / Messe'], ['reminder', 'Reminder'], ['layout_animatic_moodfilm_scribble', 'Layout / Animatic / Moodfilm / Scribble']], show: ['variant', 'usage_type', 'duration_term', 'territory', 'medium', 'addon_counts', 'rights_toggles'], scopeCopy: 'Bei Werbefällen stehen Spot-Ausprägung, Rechte-Erweiterungen und Zusatzmotive im Fokus.' },
		werbung_ohne_bild: { variantOptions: [['online_audio_paid_media', 'Online Audio Paid Media'], ['funk_spot_national', 'Funkspot national'], ['funk_spot_regional', 'Funkspot regional'], ['funk_spot_lokal', 'Funkspot lokal'], ['ladenfunk', 'Ladenfunk'], ['telefon_werbespot', 'Telefon-Werbespot'], ['reminder', 'Reminder']], show: ['variant', 'usage_type', 'duration_term', 'territory', 'medium', 'addon_counts', 'rights_toggles'], scopeCopy: 'Audio-Werbung arbeitet mit Varianten, Reminder-/Allongen-Logik und passenden Zusatzrechten.' },
		webvideo_imagefilm_praesentation_unpaid: { show: ['usage_type', 'duration_minutes', 'media_toggles'], scopeCopy: 'Für unpaid Bildfälle wird hauptsächlich die Minutenstaffel inklusive optionaler Zusatzlizenzen geführt.' },
		app: { show: ['duration_minutes', 'duration_term'], scopeCopy: 'Apps werden über eine minutenbasierte Standardnutzung mit unbegrenzter Laufzeit kalkuliert.' },
		telefonansage: { show: ['module_count'], scopeCopy: 'Telefonansagen werden über die Anzahl der Module erfasst.' },
		elearning_audioguide: { variantOptions: [['elearning_intern', 'E-Learning intern'], ['audioguide', 'Audioguide']], show: ['variant', 'duration_minutes'], scopeCopy: 'E-Learning und Audioguides basieren auf Minutenstaffeln und der passenden Inhaltsart.' },
		podcast: { variantOptions: [['podcast_inhalte', 'Podcast-Inhalt', 'Redaktioneller Audio-Podcast-Inhalt ohne Werberouting.'], ['non_commercial_3', 'Verpackung nicht-kommerziell · bis 3 Folgen', 'Intro/Outro/Ansagen für nicht-kommerzielle Podcasts.'], ['non_commercial_unlim', 'Verpackung nicht-kommerziell · Serienlizenz', 'Nicht-kommerzielle Verpackung für alle Folgen einer Serie.'], ['marketing_3', 'Verpackung kommerziell · bis 3 Folgen', 'Kommerzielle Verpackung bleibt Podcast, solange kein Sponsor- oder Werbespot vorliegt.'], ['marketing_unlim', 'Verpackung kommerziell · Serienlizenz', 'Kommerzielle Serienverpackung. Für echte Sponsorings bitte den Werbefall im Podcast-Kontext wählen.']], show: ['variant'], scopeCopy: 'Audio-Podcast: Inhalt bleibt redaktionell, Verpackung bleibt Podcast – echte Sponsorings/Werbespots laufen separat über Werbelogik.' },
		hoerbuch: { show: ['fah'], scopeCopy: 'Hörbücher bleiben als Vorschlagskalkulation mit Expertenergänzungen aufgebaut.' },
		games: { show: ['recording_hours', 'recording_days', 'same_day_projects'], scopeCopy: 'Games berücksichtigen Session-Logik, Wiederholungen an Folgetagen und parallele Projekte.' },
		redaktionell_doku_tv_reportage: { variantOptions: [['kommentarstimme', 'Kommentarstimme'], ['overvoice', 'Overvoice']], show: ['variant', 'net_minutes'], scopeCopy: 'Redaktionelle Inhalte kombinieren Minutensatz und Mindestgage transparent.' },
		audiodeskription: { variantOptions: [['audiodeskription', 'Audiodeskription']], show: ['variant', 'net_minutes'], scopeCopy: 'Audiodeskription nutzt einen klaren Minutensatz mit Mindestgage.' },
		kleinraeumig: { variantOptions: [['funk_spot_lokal', 'Lokaler Funkspot'], ['kleinraeumiger_online_video_paid', 'Kleinräumiges Online Video Paid']], show: ['variant', 'addon_counts'], scopeCopy: 'Kleinräumige Nutzung reduziert das Feld auf lokale Ausspielungen und passende Rechte-Erweiterungen.' },
		session_fee: { show: ['session_hours'], scopeCopy: 'Session Fee erfasst ausschließlich die Aufnahmestunden ohne öffentliche Lizenz.' }
	};
	var SCENARIO_TO_CASE = { online_audio_spot_unpaid: 'webvideo_imagefilm_praesentation_unpaid', online_video_spot_unpaid: 'webvideo_imagefilm_praesentation_unpaid', in_app_ads: 'werbung_mit_bild', telefon_werbespot: 'werbung_ohne_bild', marketing_elearning: 'webvideo_imagefilm_praesentation_unpaid', oeffentliches_elearning: 'webvideo_imagefilm_praesentation_unpaid', video_podcast: 'webvideo_imagefilm_praesentation_unpaid', podcast_sponsoring_audio: 'werbung_ohne_bild', podcast_sponsoring_video: 'werbung_mit_bild', werbliche_podcast_verpackung_audio: 'werbung_ohne_bild', werbliche_podcast_verpackung_video: 'werbung_mit_bild', lokaler_funkspot: 'kleinraeumig', werbliche_games_zusatznutzung: 'werbung_mit_bild' };
	var FIELD_DEFAULTS = { manual_offer_total: '', case_key: '', case_variant: '', usage_type: 'organic_branding', duration_term: '', territory: '', medium: '', package_key: '', duration_minutes: '', net_minutes: '', module_count: '', fah: '', recording_hours: '', recording_days: '1', same_day_projects: '1', additional_year: '0', additional_territory: '0', additional_motif: '0', prior_layout_fee: '0', session_hours: '0', project_title: '', customer_name: '', internal_notes: '', needs_cutdown: '0', archivgage: '0', layout_fee: '0', follow_up_usage: '0', is_paid_media: '0', usage_social_media: '0', usage_praesentation: '0', usage_awardfilm: '0', usage_casefilm: '0', usage_mitarbeiterfilm: '0', unlimited_time: '0', unlimited_territory: '0', unlimited_media: '0', reminder: '0', allongen: '0' };
	var NUMERIC_FIELDS = { duration_minutes: { min: 1, step: 0.1 }, net_minutes: { min: 1, step: 0.1 }, module_count: { min: 1, step: 1 }, fah: { min: 1, step: 0.5 }, recording_hours: { min: 1, step: 0.5 }, recording_days: { min: 1, step: 1 }, same_day_projects: { min: 1, step: 1 }, additional_year: { min: 0, step: 1 }, additional_territory: { min: 0, step: 1 }, additional_motif: { min: 0, step: 1 }, prior_layout_fee: { min: 0, step: 0.01 }, session_hours: { min: 1, step: 0.5 }, manual_offer_total: { min: 0, step: 0.01 } };
	var FIELD_LABELS = { case_key: 'Projektart', case_variant: 'Variante', duration_minutes: 'Dauer in Minuten', net_minutes: 'Netto-Sendeminuten', module_count: 'Anzahl der Module', fah: 'FAH', recording_hours: 'Aufnahmestunden', recording_days: 'Aufnahmetage', same_day_projects: 'Weitere Projekte am selben Tag', additional_year: 'Zusatzjahre', additional_territory: 'Zusätzliche Gebiete', additional_motif: 'Zusatzmotive', duration_term: 'Laufzeit', territory: 'Territorium', medium: 'Medium', session_hours: 'Session-Stunden' };
	var BLOCK_FIELD_MAP = { variant: ['case_variant'], usage_type: ['usage_type'], media_toggles: ['is_paid_media', 'usage_social_media', 'usage_praesentation', 'usage_awardfilm', 'usage_casefilm', 'usage_mitarbeiterfilm'], duration_term: ['duration_term'], territory: ['territory'], medium: ['medium'], duration_minutes: ['duration_minutes'], net_minutes: ['net_minutes'], module_count: ['module_count'], fah: ['fah'], recording_hours: ['recording_hours'], recording_days: ['recording_days'], same_day_projects: ['same_day_projects'], addon_counts: ['additional_year', 'additional_territory', 'additional_motif'], rights_toggles: ['archivgage', 'reminder', 'allongen', 'follow_up_usage'], session_hours: ['session_hours'], prior_layout_fee: ['prior_layout_fee'], unlimited_usage: ['unlimited_time', 'unlimited_territory', 'unlimited_media'] };

	function htmlEscape(value) { return String(value == null ? '' : value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); }
	function parseJsonAttribute(node, attribute) { try { return JSON.parse(node.getAttribute(attribute) || '{}'); } catch (error) { return {}; } }
	function labelFromKey(value) { return String(value || '').replace(/_/g, ' ').replace(/\b\w/g, function (m) { return m.toUpperCase(); }); }
	function clone(obj) { return JSON.parse(JSON.stringify(obj || {})); }
	function storageAvailable() { try { localStorage.setItem('__sgk_test__', '1'); localStorage.removeItem('__sgk_test__'); return true; } catch (error) { return false; } }
	function currency(value) { return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(Number(value || 0)); }
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
		var labels = { organic_branding: 'Branding / organisch', paid_advertising: 'Paid Advertising', tv: 'TV', ctv: 'CTV', online_video: 'Online Video', kino: 'Kino', pos: 'POS', event: 'Event', messe: 'Messe', radio: 'Radio', online_audio: 'Online Audio', ladenfunk: 'Ladenfunk', telefon: 'Telefon', regional: 'Regional', lokal: 'Lokal', de: 'Deutschland', dach: 'DACH', eu: 'EU', weltweit: 'Weltweit', '1_jahr': '1 Jahr', '2_jahre': '2 Jahre', archiv: 'Archiv', unbegrenzt: 'Unbegrenzt', zeitlich_unbegrenzt: 'Zeitlich unbegrenzt' };
		return labels[value] || labelFromKey(value);
	}

	function buildSelectOptions(select, values, placeholder) {
		if (!select) { return; }
		var current = select.value;
		select.innerHTML = '<option value="">' + htmlEscape(placeholder || 'Bitte auswählen') + '</option>' + (values || []).map(function (value) { return '<option value="' + htmlEscape(value) + '">' + htmlEscape(optionLabel(value)) + '</option>'; }).join('');
		if (current && matchesAny(current, values || [])) { select.value = current; }
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

	function validateManualOffer(value, result) { if (value == null || value === '') { return { valid: false, message: 'Für ein fertiges Angebot solltest du einen finalen Angebotswert festlegen.' }; } if (value <= 0) { return { valid: false, message: 'Bitte trage einen positiven Angebotswert ein.' }; } if (result && result.totals && value < result.totals.lower * 0.25) { return { valid: false, message: 'Der eingetragene Angebotswert liegt deutlich unter der empfohlenen Spanne.' }; } return { valid: true, message: 'Der Angebotswert wird separat übernommen, ohne die Empfehlung zu verändern.' }; }
	function copyText(text, trigger) { if (!text) { return Promise.reject(new Error('empty')); } var promise = navigator.clipboard && navigator.clipboard.writeText ? navigator.clipboard.writeText(text) : Promise.reject(new Error('clipboard-unavailable')); return promise.then(function () { if (trigger) { var original = trigger.getAttribute('data-label') || trigger.textContent; trigger.textContent = 'Kopiert'; setTimeout(function () { trigger.textContent = original; }, 1600); } }); }
	function buildExportPayload(result, formData, offerMeta) { var payload = clone(result.export_payload || {}); payload.summary = payload.summary || {}; payload.summary.project_title = formData.project_title || ''; payload.summary.customer_name = formData.customer_name || ''; payload.summary.display_title = result.display_title || ''; payload.summary.generated_at = new Date().toISOString(); payload.calculation_meta = payload.calculation_meta || {}; payload.calculation_meta.internal_notes = formData.internal_notes || ''; payload.calculation_meta.source_form = formData; payload.calculation_meta.offer_meta = offerMeta || {}; return payload; }
	function prettifyRouteMessage(message) { var raw = String(message || ''); if (!raw) { return 'Die Auswahl wurde passend für die Kalkulation eingeordnet.'; } return raw.replace(/Resolver/gi, 'Auswahl').replace(/Berechnungsengine/gi, 'Kalkulation').replace(/normalisiert/gi, 'geordnet').replace(/suppressed invalid paths/gi, 'nicht passende Varianten').replace(/Redirect aktiv/gi, 'Diese Auswahl wird').replace(/fachlich sauber/gi, 'passend').replace(/Berechnungspfad/gi, 'Einordnung').replace(/route trace/gi, 'Einordnung').replace(/aktivierte Regeln/gi, 'berücksichtigte Auswahl').replace(/Resolver-Logik/gi, 'Zuordnung').trim(); }
	function routeLabel(step, label) { var map = { resolver: 'Auswahl', normalization: 'Auswahl', redirect: 'Einordnung', suppressed_invalid_path: 'Bereinigt', case: 'Projektart' }; return map[step] || label || 'Schritt'; }

	function buildCopyBlocks(result, formData, offerMeta) {
		var exportPayload = buildExportPayload(result, formData, offerMeta);
		var texts = exportPayload.export_text_blocks || {};
		var projectLine = offerMeta && offerMeta.offer_number ? ('Angebot ' + offerMeta.offer_number) : 'Angebot Sprecherhonorar';
		return { summary: [projectLine, texts.offer_headline || ('Angebot Sprecherhonorar – ' + (formData.project_title || result.display_title || 'Projekt')), texts.copy_summary || '', formData.customer_name ? ('Kunde: ' + formData.customer_name) : '', offerMeta && offerMeta.offer_date ? ('Datum: ' + formatDate(offerMeta.offer_date)) : '', texts.manual_offer_notice || ''].filter(Boolean).join('\n'), positions: texts.positions_block || '', rights: texts.rights_block || '', json: JSON.stringify(exportPayload, null, 2), mail: [offerMeta && offerMeta.intro_text ? offerMeta.intro_text : 'Vielen Dank für deine Anfrage. Nachfolgend erhältst du dein Angebot.', '', texts.offer_headline || '', texts.copy_summary || '', texts.positions_block || '', '', texts.rights_block || '', '', texts.notes_block || '', '', texts.legal_notice_block || ''].filter(Boolean).join('\n') };
	}
	function getOfferMeta(app, formData) { var meta = { offer_date: todayIso() }; app.querySelectorAll('[data-sgk-offer-meta]').forEach(function (field) { meta[field.getAttribute('data-sgk-offer-meta')] = field.value || ''; }); if (!meta.contact_name) { meta.contact_name = formData.customer_name || ''; } if (!meta.offer_date) { meta.offer_date = todayIso(); } return meta; }
	function documentStyles() { return ['body{font-family:"Rubik Local","Rubik",Arial,sans-serif;background:#eef4fb;margin:0;color:#0f172a;}', '.doc{max-width:980px;margin:0 auto;padding:32px;}', '.sheet{background:#fff;border-radius:28px;padding:40px;box-shadow:0 24px 60px rgba(15,23,42,.12);}', '.header,.meta-grid,.summary-grid,.positions,.rights,.notes,.footer{display:grid;gap:16px;}', '.header-top{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;border-bottom:1px solid #dbe6f1;padding-bottom:20px;}', '.logo{width:60px;height:60px;border-radius:18px;background:linear-gradient(180deg,#1a93ee,#0f141a);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;}', '.eyebrow{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#1a93ee;font-weight:700;margin:0 0 8px;}', 'h1,h2,h3,h4,p{margin:0;}', '.headline h1{font-size:30px;line-height:1.05;margin-bottom:8px;}', '.meta-grid{grid-template-columns:repeat(4,minmax(0,1fr));margin-top:24px;}', '.meta-card,.summary-card,.section{border:1px solid #dbe6f1;border-radius:20px;padding:18px;background:#f9fbff;}', '.summary-grid{grid-template-columns:2fr 1fr 1fr; margin:24px 0;}', '.summary-card--total{background:linear-gradient(180deg,#0f141a,#162131);color:#fff;border-color:#0f141a;}', '.section-title{font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-bottom:10px;font-weight:700;}', '.position-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:14px;padding:14px 0;border-top:1px solid #dbe6f1;}', '.position-row:first-child{border-top:0;padding-top:0;}', '.position-row small,.muted{color:#64748b;display:block;line-height:1.5;}', '.badge{display:inline-block;padding:6px 10px;border-radius:999px;background:#eaf4fe;color:#116fb3;font-size:12px;font-weight:600;}', '.footer{margin-top:28px;padding-top:18px;border-top:1px solid #dbe6f1;font-size:13px;color:#64748b;}', '@media print{body{background:#fff}.doc{max-width:none;padding:0}.sheet{box-shadow:none;border-radius:0;padding:24px}}'].join(''); }
	function buildOfferDocumentData(result, formData, offerMeta) { var exportPayload = buildExportPayload(result, formData, offerMeta); var documentPayload = clone(exportPayload.document_payload || {}); documentPayload.meta = offerMeta; documentPayload.form = formData; documentPayload.exportPayload = exportPayload; return documentPayload; }

	function renderOfferPreview(result, formData, offerMeta) { var documentData = buildOfferDocumentData(result, formData, offerMeta); var exportPayload = documentData.exportPayload; var summary = exportPayload.summary || {}; var positions = Array.isArray(exportPayload.positions) ? exportPayload.positions : []; var rights = Array.isArray(exportPayload.rights_overview) ? exportPayload.rights_overview : []; var notes = Array.isArray(exportPayload.notes_for_offer) ? exportPayload.notes_for_offer : []; var legal = Array.isArray(exportPayload.legal_notice) ? exportPayload.legal_notice : []; var alternatives = Array.isArray(exportPayload.alternative_packages) ? exportPayload.alternative_packages : []; var breakdown = Array.isArray(exportPayload.breakdown_sections) ? exportPayload.breakdown_sections : []; var totalText = exportPayload.manual_offer_total != null ? safeCurrency(exportPayload.manual_offer_total, 'Noch offen') : 'Noch offen'; var midText = exportPayload.recommended_mid != null ? safeCurrency(exportPayload.recommended_mid, '—') : '—'; var rangeText = exportPayload.recommended_range ? safeCurrency(exportPayload.recommended_range.lower, '—') + ' – ' + safeCurrency(exportPayload.recommended_range.upper, '—') : '—'; return { html: '<style>' + documentStyles() + '</style>' + '<div class="doc"><div class="sheet">' + '<div class="header"><div class="header-top"><div style="display:flex;gap:16px;align-items:flex-start;"><div class="logo">SGK</div><div class="headline"><p class="eyebrow">Professionelles Angebotsdokument</p><h1>Angebot Sprecherhonorar</h1><p>' + htmlEscape(formData.project_title || summary.display_title || summary.title || 'Projektangebot') + '</p></div></div><div style="text-align:right;"><span class="badge">' + htmlEscape(offerMeta.offer_number || 'Angebot') + '</span><p class="muted" style="margin-top:10px;">' + htmlEscape(formatDate(offerMeta.offer_date)) + '</p></div></div><div class="meta-grid"><div class="meta-card"><div class="section-title">Kunde</div><strong>' + htmlEscape(formData.customer_name || 'Nicht angegeben') + '</strong><small class="muted">' + htmlEscape(offerMeta.contact_name || 'Ansprechpartner optional') + '</small></div><div class="meta-card"><div class="section-title">Projekt</div><strong>' + htmlEscape(formData.project_title || summary.display_title || summary.title || 'Berechnung') + '</strong><small class="muted">Projektart: ' + htmlEscape(summary.case_label || summary.display_title || summary.title || 'Projekt') + '</small></div><div class="meta-card"><div class="section-title">Absender</div><strong>' + htmlEscape(offerMeta.sender_company || 'Studio / Absender') + '</strong><small class="muted">' + htmlEscape([offerMeta.sender_email, offerMeta.sender_phone].filter(Boolean).join(' · ')) + '</small></div><div class="meta-card"><div class="section-title">Dokument</div><strong>' + htmlEscape(offerMeta.offer_number || 'Ohne Nummer') + '</strong><small class="muted">Stand ' + htmlEscape(formatDate(offerMeta.offer_date)) + '</small></div></div></div>' + '<div class="summary-grid"><div class="summary-card"><div class="section-title">Angebotsbasis</div><p>' + htmlEscape(offerMeta.intro_text || 'Vielen Dank für deine Anfrage. Nachfolgend erhältst du dein Angebot auf Basis der abgestimmten Nutzung und der aktuellen Preisermittlung.') + '</p><small class="muted" style="margin-top:10px;">Untervariante: ' + htmlEscape(summary.sub_variant || 'Standard') + '</small></div><div class="summary-card"><div class="section-title">Errechnete Spanne</div><strong>' + htmlEscape(rangeText) + '</strong><small class="muted">Mittelwert ' + htmlEscape(midText) + '</small></div><div class="summary-card summary-card--total"><div class="section-title" style="color:rgba(255,255,255,.72)">Finale Angebotssumme</div><strong style="font-size:28px;display:block;">' + htmlEscape(totalText) + '</strong><small>' + htmlEscape(exportPayload.manual_offer_total != null ? 'Manuell gesetzt und als Angebotswert übernommen.' : 'Bitte vor PDF-Ausgabe final festlegen.') + '</small></div></div>' + '<div class="section"><div class="section-title">Rechenweg</div>' + (breakdown.length ? breakdown.map(function (section) { var items = Array.isArray(section.items) ? section.items : []; return '<div style="padding:12px 0;border-top:1px solid #dbe6f1;"><strong>' + htmlEscape(section.label || 'Abschnitt') + '</strong><small class="muted">' + htmlEscape(section.description || '') + '</small>' + (items.length ? items.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.label || 'Position') + '</strong><small>' + htmlEscape(item.quantity_label || '') + (item.note ? ' · ' + htmlEscape(item.note) : '') + '</small></div><div><strong>' + htmlEscape((item.formatted && item.formatted.low_mid_high) || '—') + '</strong></div></div>'; }).join('') : '<p class="muted">Keine Einträge.</p>') + '</div>'; }).join('') : '<p class="muted">Der Rechenweg ist aktuell nicht verfügbar.</p>') + '</div>' + '<div class="section positions"><div class="section-title">Angebotspositionen</div>' + positions.map(function (item) { var price = item.manuell_uebernommener_preis != null ? safeCurrency(item.manuell_uebernommener_preis, '—') : safeCurrency(item.empfohlener_preis, '—'); return '<div class="position-row"><div><strong>' + htmlEscape(item.position_number + '. ' + item.titel) + '</strong><small>' + htmlEscape(item.beschreibung || '') + '</small><small>' + htmlEscape((item.kategorie || '') + (item.lizenzbezug ? ' · ' + item.lizenzbezug : '')) + '</small></div><div><strong>' + htmlEscape(price) + '</strong></div></div>'; }).join('') + '</div>' + '<div class="section rights"><div class="section-title">Nutzungsrechte & Lizenzen</div>' + (rights.length ? rights.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.title + (item.variant ? ' · ' + item.variant : '')) + '</strong><small>Laufzeit: ' + htmlEscape(item.duration) + ' · Territorium: ' + htmlEscape(item.territory) + ' · Medien: ' + htmlEscape(item.media) + '</small></div><div><span class="badge">Rechteblock</span></div></div>'; }).join('') : '<p class="muted">Keine zusätzlichen Rechteinformationen vorhanden.</p>') + '</div>' + '<div class="section notes"><div class="section-title">Hinweise & Anmerkungen</div>' + (notes.length ? notes.map(function (item, index) { return '<p style="padding:10px 0;' + (index ? 'border-top:1px solid #dbe6f1;' : '') + '">' + htmlEscape(item) + '</p>'; }).join('') : '<p class="muted">Keine zusätzlichen Hinweise.</p>') + '</div>' + (legal.length ? '<div class="section"><div class="section-title">Rechtlicher Hinweis</div>' + legal.map(function (item) { return '<p>' + htmlEscape(item) + '</p>'; }).join('') + '</div>' : '') + (alternatives.length ? '<div class="section"><div class="section-title">Optionale Paket-Alternativen</div>' + alternatives.map(function (item) { return '<div class="position-row"><div><strong>' + htmlEscape(item.label || 'Alternative') + '</strong></div><div><strong>' + htmlEscape(item.formatted_totals ? (item.formatted_totals.low_mid_high || item.formatted_totals.mid) : '') + '</strong></div></div>'; }).join('') + '</div>' : '') + '<div class="footer"><div>' + htmlEscape(offerMeta.footer_text || [offerMeta.sender_company, offerMeta.sender_email, offerMeta.sender_phone].filter(Boolean).join(' · ')) + '</div><div>Dieses Dokument basiert auf der aktuellen Preisermittlung im Sprecher-Gagenrechner. Interne Angaben werden im Kundendokument nicht ausgegeben.</div></div>' + '</div></div>', text: buildCopyBlocks(result, formData, offerMeta).mail, validation: validateManualOffer(exportPayload.manual_offer_total, result) }; }
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
		if (hint) { hint.textContent = activeVariantHint(effectiveCase, select.value || (options[0] && options[0][0]) || '', caseConfig) || 'Die Varianten passen sich automatisch deinem Projekt an.'; }
	}
	function buildVariantButtons(form, options) { var control = form.querySelector('[data-sgk-variant-control]'); if (!control) { return; } control.innerHTML = options.map(function (item, index) { return '<button type="button" class="src-segment-btn" data-sgk-segment-value="' + htmlEscape(item[0]) + '" title="' + htmlEscape(item[2] || '') + '">' + htmlEscape(item[1] || ('Variante ' + (index + 1))) + '</button>'; }).join(''); }
	function syncSegmentedControl(control, value) { if (!control) { return; } control.querySelectorAll('[data-sgk-segment-value]').forEach(function (button) { button.classList.toggle('is-active', button.getAttribute('data-sgk-segment-value') === value); }); }
	function updateCaseContext(app, selectedCase, cases) { var node = app.querySelector('[data-sgk-case-context]'); app.querySelectorAll('[data-sgk-quick-case]').forEach(function (button) { button.classList.toggle('is-active', button.getAttribute('data-sgk-quick-case') === selectedCase); }); if (!node) { return; } if (!selectedCase) { node.hidden = true; node.innerHTML = ''; return; } var effectiveCase = effectiveCaseKey(cases, selectedCase); var caseData = cases[effectiveCase] || {}; node.hidden = false; node.innerHTML = '<strong>' + htmlEscape(caseData.label || labelFromKey(selectedCase)) + '</strong><p>' + htmlEscape(caseData.description || 'Die Eingaben wurden passend zu deiner Auswahl zusammengestellt.') + '</p>'; }
	function updateExpertBadges(app, uiState) { var container = app.querySelector('[data-sgk-expert-badges]'); var flags = (uiState && uiState.available_expert_options) || []; if (!container) { return; } container.innerHTML = !flags.length ? '<span class="src-inline-badge is-muted">Noch keine zusätzlichen Optionen aktiv</span>' : flags.map(function (flag) { return '<span class="src-inline-badge">' + htmlEscape(labelFromKey(flag)) + '</span>'; }).join(''); }
	function refreshSavedList(container, cases) { var select = container.querySelector('[data-sgk-saved-list]'); if (!select) { return; } var entries = getSavedCalculations(cases); select.innerHTML = '<option value="">Bitte auswählen</option>' + entries.map(function (entry) { return '<option value="' + htmlEscape(entry.id) + '">' + htmlEscape(buildSavedLabel(entry)) + '</option>'; }).join(''); }
	function updateRedirectBanner(app, payload) { var banner = app.querySelector('[data-sgk-redirect-banner]'); if (!banner) { return; } var uiState = (payload && payload.ui_state) || {}; var result = (payload && payload.result) || {}; var warnings = Array.isArray(result.warnings) ? result.warnings : []; var message = ''; if (uiState.selected_case && uiState.resolved_case && uiState.selected_case !== uiState.resolved_case) { message = 'Smart Match: Automatisch zugeordnet – ' + labelFromKey(uiState.resolved_case) + '.'; } else if (warnings.length) { message = 'Smart Match: ' + warnings.join(' · '); } if (!message) { banner.hidden = true; banner.innerHTML = ''; return; } banner.hidden = false; banner.innerHTML = '<i data-lucide="sparkles" width="18" height="18"></i><div><strong>Smart Match: Automatisch zugeordnet</strong><p>' + htmlEscape(message) + '</p></div>'; if (window.lucide && window.lucide.createIcons) { window.lucide.createIcons({ attrs: { 'stroke-width': 1.8 } }); } }

	function deriveUiState(formData, cases) {
		var selectedCase = formData.case_key;
		var effectiveCase = effectiveCaseKey(cases, selectedCase);
		var resolvedCaseConfig = caseConfig(cases, selectedCase) || {};
		var visualConfig = CASE_UI[effectiveCase] || {};
		var visibleBlocks = (visualConfig.show || []).concat(['scope_note']);
		var variantVisibilityRules = resolvedCaseConfig.variant_visibility_rules || {};
		var activeVariant = formData.case_variant || (resolvedCaseConfig.allowed_variants || [])[0] || '';
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
		return { selectedCase: selectedCase, effectiveCase: effectiveCase, caseConfig: resolvedCaseConfig, visibleBlocks: visibleBlocks, requiredFields: requiredFields };
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
		if (ui.caseConfig.allowed_durations && ui.caseConfig.allowed_durations.length && !matchesAny(normalized.duration_term, ui.caseConfig.allowed_durations)) { normalized.duration_term = ui.caseConfig.duration_rules && ui.caseConfig.duration_rules.default_term ? ui.caseConfig.duration_rules.default_term : ''; }
		if (ui.caseConfig.allowed_territories && ui.caseConfig.allowed_territories.length && !matchesAny(normalized.territory, ui.caseConfig.allowed_territories)) { normalized.territory = ui.caseConfig.territory_rules && ui.caseConfig.territory_rules.default ? ui.caseConfig.territory_rules.default : ''; }
		if (ui.caseConfig.allowed_media && ui.caseConfig.allowed_media.length && !matchesAny(normalized.medium, ui.caseConfig.allowed_media)) { var mediaDefault = ui.caseConfig.media_rules && Array.isArray(ui.caseConfig.media_rules.default) ? ui.caseConfig.media_rules.default[0] : ''; normalized.medium = mediaDefault; }
		return normalized;
	}

	function applyNormalizedState(form, normalized) {
		Object.keys(normalized).forEach(function (key) { setFieldValue(fieldNode(form, key), normalized[key]); });
	}

	function validateFormData(formData, ui) {
		var errors = [];
		ui.requiredFields.forEach(function (field) {
			var value = formData[field];
			if (value == null || value === '') { errors.push(FIELD_LABELS[field] || labelFromKey(field)); }
		});
		if (errors.length) { return { valid: false, message: 'Bitte ergänze zuerst: ' + errors.join(', ') + '.', missing: errors }; }
		Object.keys(NUMERIC_FIELDS).forEach(function (field) {
			if (formData[field] === '' || FIELD_DEFAULTS[field] === formData[field] && ui.requiredFields.indexOf(field) === -1) { return; }
			var numericValue = normalizeNumber(formData[field]);
			if (numericValue == null) { errors.push((FIELD_LABELS[field] || labelFromKey(field)) + ' ist keine gültige Zahl'); return; }
			if (NUMERIC_FIELDS[field].min != null && numericValue < NUMERIC_FIELDS[field].min) { errors.push((FIELD_LABELS[field] || labelFromKey(field)) + ' muss mindestens ' + NUMERIC_FIELDS[field].min + ' sein'); }
		});
		if (ui.effectiveCase === 'telefonansage' && formData.is_paid_media === '1') { errors.push('Telefonansagen dürfen nicht als Paid Media kalkuliert werden'); }
		['archivgage', 'reminder', 'allongen', 'follow_up_usage', 'prior_layout_fee', 'unlimited_time', 'unlimited_territory', 'unlimited_media'].forEach(function (field) {
			var active = field === 'prior_layout_fee' ? normalizeNumber(formData[field]) > 0 : isTruthy(formData[field]);
			if (active && !isCaseFieldAllowed(ui, field, formData)) { errors.push((FIELD_LABELS[field] || labelFromKey(field)) + ' ist für diese Auswahl nicht zulässig'); }
		});
		if (isTruthy(formData.follow_up_usage) && !(normalizeNumber(formData.prior_layout_fee) > 0)) { errors.push('Für Nachnutzung muss ein vorheriges Layout-Honorar angegeben werden'); }
		if ((isTruthy(formData.unlimited_time) || isTruthy(formData.unlimited_territory) || isTruthy(formData.unlimited_media)) && String(formData.case_variant || '').indexOf('patronat') !== -1) { errors.push('Patronat bleibt für Unlimited-/Buyout-Kombinationen gesperrt'); }
		if (ui.effectiveCase === 'session_fee' && (formData.case_variant || formData.duration_term || formData.territory || formData.medium || isTruthy(formData.archivgage) || isTruthy(formData.reminder) || isTruthy(formData.allongen) || isTruthy(formData.follow_up_usage) || isTruthy(formData.unlimited_time) || isTruthy(formData.unlimited_territory) || isTruthy(formData.unlimited_media) || normalizeNumber(formData.additional_year) > 0 || normalizeNumber(formData.additional_territory) > 0 || normalizeNumber(formData.additional_motif) > 0 || normalizeNumber(formData.prior_layout_fee) > 0)) {
			errors.push('Session Fee darf nicht mit Lizenz-, Rechte- oder Unlimited-Optionen kombiniert werden');
		}
		if (ui.caseConfig.allowed_variants && ui.caseConfig.allowed_variants.length && formData.case_variant && !matchesAny(formData.case_variant, ui.caseConfig.allowed_variants)) { errors.push('Die gewählte Variante ist für diesen Fall nicht zulässig'); }
		if (ui.caseConfig.allowed_durations && ui.caseConfig.allowed_durations.length && formData.duration_term && !matchesAny(formData.duration_term, ui.caseConfig.allowed_durations)) { errors.push('Die gewählte Laufzeit ist für diesen Fall nicht zulässig'); }
		if (ui.caseConfig.allowed_territories && ui.caseConfig.allowed_territories.length && formData.territory && !matchesAny(formData.territory, ui.caseConfig.allowed_territories)) { errors.push('Das gewählte Territorium ist für diesen Fall nicht zulässig'); }
		if (ui.caseConfig.allowed_media && ui.caseConfig.allowed_media.length && formData.medium && !matchesAny(formData.medium, ui.caseConfig.allowed_media)) { errors.push('Das gewählte Medium ist für diesen Fall nicht zulässig'); }
		return { valid: errors.length === 0, message: errors[0] || 'Die Eingaben sind vollständig und fachlich konsistent.', errors: errors };
	}

	function renderValidation(app, validation) {
		var status = app.querySelector('[data-sgk-validation-status]');
		var button = app.querySelector('[data-sgk-submit]');
		if (!status || !button) { return; }
		status.textContent = validation.message || '';
		status.className = 'src-actions-hint ' + (validation.valid ? 'is-valid' : 'is-invalid');
		button.disabled = !validation.valid;
		button.setAttribute('aria-disabled', validation.valid ? 'false' : 'true');
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
		if (scopeCopy) { scopeCopy.textContent = hasSelection ? ((CASE_UI[ui.effectiveCase] && CASE_UI[ui.effectiveCase].scopeCopy) || 'Die Angaben werden passend zu deiner Auswahl geführt.') : 'Wähle zuerst eine Projektart, damit wir dir die passenden Umfangsangaben zeigen können.'; }
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
	function renderBreakdownSections(sections) {
		if (!Array.isArray(sections) || !sections.length) { return '<div class="src-result-note">Der Rechenweg wird nach der ersten erfolgreichen Kalkulation aufgebaut.</div>'; }
		return sections.map(function (section) {
			var items = Array.isArray(section.items) ? section.items : [];
			var body = items.length ? items.map(function (item) {
				var formatted = item.formatted || {};
				var amounts = formatted.low_mid_high || [formatted.lower, formatted.mid, formatted.upper].filter(Boolean).join(' / ') || '—';
				return '<div class="src-breakdown-row">' +
					'<div class="src-breakdown-main"><strong>' + htmlEscape(item.label || 'Position') + '</strong>' +
					(item.quantity_label ? '<span>' + htmlEscape(item.quantity_label) + '</span>' : '') +
					(item.note ? '<small>' + htmlEscape(item.note) + '</small>' : '') + '</div>' +
					'<div class="src-breakdown-amount' + (item.is_credit ? ' is-credit' : '') + (item.is_minimum ? ' is-minimum' : '') + '">' + htmlEscape(amounts) + '</div>' +
				'</div>';
			}).join('') : '<div class="src-result-note">Keine zusätzlichen Positionen.</div>';
			return '<div class="src-breakdown-section"><div class="src-breakdown-head"><strong>' + htmlEscape(section.label || 'Abschnitt') + '</strong>' + (section.description ? '<p>' + htmlEscape(section.description) + '</p>' : '') + '</div>' + body + '</div>';
		}).join('');
	}
	function renderRouteSummary(routeTrace) {
		return routeTrace.length ? '<ul class="src-route-list">' + routeTrace.map(function (item) { return '<li><strong>' + htmlEscape(routeLabel(item.step, item.label)) + ':</strong> ' + htmlEscape(prettifyRouteMessage(item.message)) + '</li>'; }).join('') + '</ul>' : '<div class="src-result-note">Die fachliche Einordnung erscheint nach der Berechnung.</div>';
	}
	function renderSimpleList(items, emptyText) {
		return items.length ? '<ul class="src-result-list">' + items.map(function (item) { return '<li>' + htmlEscape(item) + '</li>'; }).join('') + '</ul>' : '<div class="src-result-note">' + htmlEscape(emptyText) + '</div>';
	}

	function renderResult(container, payload, formData) {
		var result = payload.result || {};
		var totals = result.formatted_totals || {};
		var rights = Array.isArray(result.rights_overview) ? result.rights_overview : [];
		var positions = Array.isArray(result.offer_positions) ? result.offer_positions : [];
		var warnings = Array.isArray(result.warnings) ? result.warnings : [];
		var notes = Array.isArray(result.offer_notes) ? result.offer_notes : [];
		var routeTrace = Array.isArray(result.route_summary_offer) ? result.route_summary_offer : [];
		var breakdownSections = Array.isArray(result.breakdown_sections) ? result.breakdown_sections : [];
		var alternatives = Array.isArray(result.alternatives) ? result.alternatives : [];
		var manualOffer = result.formatted_manual_offer_total || 'Noch nicht festgelegt';
		var manualValidation = validateManualOffer(result.manual_offer_total, result);
		var copyBlocks = buildCopyBlocks(result, formData, {});
		var rightsMarkup = rights.length ? '<ul class="src-rights-list">' + rights.map(function (item) { return '<li><strong>' + htmlEscape(item.title + (item.variant ? ' · ' + item.variant : '')) + '</strong><span>Laufzeit: ' + htmlEscape(item.duration || '—') + ' · Territorium: ' + htmlEscape(item.territory || '—') + ' · Medien: ' + htmlEscape(item.media || '—') + '</span></li>'; }).join('') + '</ul>' : '<div class="src-result-note">Die Rechteübersicht wird nach der ersten Berechnung ergänzt.</div>';
		var positionMarkup = positions.length ? positions.map(function (item) { var price = item.formatted_prices && item.formatted_prices.manual ? item.formatted_prices.manual : ((item.formatted_prices && item.formatted_prices.mid) || '0,00 €'); return '<div class="src-receipt-item"><div><strong>' + htmlEscape(item.titel) + '</strong><small>' + htmlEscape(item.beschreibung || '') + '</small></div><span>' + htmlEscape(price) + '</span></div>'; }).join('') : '<div class="src-receipt-item"><span>Basis</span><span>' + htmlEscape(totals.mid || '0,00 €') + '</span></div>';
		var packageMarkup = alternatives.length ? '<ul class="src-result-list">' + alternatives.map(function (item) { return '<li><strong>' + htmlEscape(item.label || 'Paket') + ':</strong> ' + htmlEscape(item.formatted_totals ? item.formatted_totals.low_mid_high || item.formatted_totals.mid : '—'); }).join('') + '</ul>' : '<div class="src-result-note">Keine alternativen Paketpreise verfügbar.</div>';
		container.innerHTML = '' +
			'<div class="src-result-hero">' +
				'<div class="src-price-block"><div class="src-price-huge">' + htmlEscape(totals.mid || '0,00 €') + '<span>netto</span></div><div class="src-price-range">Finale Spanne: ' + htmlEscape((totals.lower || '0,00 €') + ' – ' + (totals.upper || '0,00 €')) + '</div></div>' +
				'<div class="src-result-meta-grid">' +
					'<div class="src-result-meta-card"><span>Hauptfall</span><strong>' + htmlEscape((result.summary && result.summary.context && result.summary.context.case_label) || labelFromKey(result.resolved_case)) + '</strong></div>' +
					'<div class="src-result-meta-card"><span>Untervariante</span><strong>' + htmlEscape((result.summary && result.summary.context && result.summary.context.variant_label) || 'Standard') + '</strong></div>' +
					'<div class="src-result-meta-card"><span>Final low / mid / high</span><strong>' + htmlEscape((totals.lower || '0,00 €') + ' / ' + (totals.mid || '0,00 €') + ' / ' + (totals.upper || '0,00 €')) + '</strong></div>' +
				'</div>' +
			'</div>' +
			'<div class="src-result-grid">' +
				'<section class="src-result-card"><div class="src-result-card-head"><strong>Rechenweg & Breakdown</strong><p>Alle Basis-, Zusatz-, Credit- und Mindestgage-Komponenten werden separat ausgewiesen.</p></div>' + renderBreakdownSections(breakdownSections) + '</section>' +
				'<section class="src-result-card"><div class="src-result-card-head"><strong>Angebotspositionen</strong><p>Diese Positionen werden auch für Export und Angebotsvorschau verwendet.</p></div><div class="src-receipt-list src-receipt-list--detailed">' + positionMarkup + '<div class="src-receipt-total"><span>Kalkulationsbasis</span><span>' + htmlEscape(totals.mid || '0,00 €') + '</span></div></div></section>' +
				'<section class="src-result-card"><div class="src-result-card-head"><strong>Zusatzrechte & Nutzung</strong><p>Rechteumfang, Sonderfälle und automatische Zuordnung bleiben nachvollziehbar.</p></div>' + rightsMarkup + '<div class="src-result-subsection"><strong>Sonderfälle & Einordnung</strong>' + renderRouteSummary(routeTrace) + '</div></section>' +
				'<section class="src-result-card"><div class="src-result-card-head"><strong>Hinweise, Credits & Pakete</strong><p>Mindestgage, Sonderlogik und Paket-Alternativen getrennt von der Basis.</p></div><div class="src-result-subsection"><strong>Hinweise</strong>' + renderSimpleList(warnings.concat(notes), 'Aktuell liegen keine zusätzlichen Hinweise vor.') + '</div><div class="src-result-subsection"><strong>Paket-Alternativen</strong>' + packageMarkup + '</div></section>' +
			'</div>' +
			'<div class="src-result-actions"><button type="button" class="src-btn-primary" data-sgk-action="open-pdf">Angebot vorbereiten <span aria-hidden="true">→</span></button><div class="src-result-btn-grid"><button type="button" class="src-btn-secondary src-btn-secondary--dark" data-label="Zusammenfassung kopieren" data-sgk-action="copy-summary">Zusammenfassung</button><button type="button" class="src-btn-secondary src-btn-secondary--dark" data-label="Positionen kopieren" data-sgk-action="copy-positions">Positionen</button><button type="button" class="src-btn-secondary src-btn-secondary--dark" data-label="Rechte kopieren" data-sgk-action="copy-rights">Rechte</button><button type="button" class="src-btn-secondary src-btn-secondary--dark" data-label="Exportdaten kopieren" data-sgk-action="copy-json">Exportdaten</button></div></div>' +
			'<div class="src-inline-dark-panel src-manual-offer"><strong>Finale Angebotssumme</strong><div class="src-manual-offer-row"><input type="number" min="0" step="0.01" value="' + htmlEscape(result.manual_offer_total || '') + '" placeholder="z. B. 2450.00" data-sgk-manual-offer /><button type="button" class="src-btn-secondary src-btn-secondary--dark" data-sgk-sync-manual-offer>Übernehmen</button></div><div class="src-manual-offer-status ' + (manualValidation.valid ? 'is-valid' : 'is-invalid') + '">' + htmlEscape(manualValidation.message) + '</div><div class="src-storage-status">Aktuell hinterlegt: ' + htmlEscape(manualOffer) + '</div></div>' +
			'<div class="src-storage-panel"><label for="sgk-saved-calculations">Gespeicherte Kalkulationen</label><select id="sgk-saved-calculations" data-sgk-saved-list><option value="">Bitte auswählen</option></select><div class="src-storage-actions"><button type="button" class="src-btn-secondary src-btn-secondary--dark" data-label="Berechnung speichern" data-sgk-action="save">Speichern</button><button type="button" class="src-btn-secondary src-btn-secondary--dark" data-label="Berechnung laden" data-sgk-action="load">Laden</button><button type="button" class="src-btn-secondary src-btn-secondary--dark" data-label="Berechnung löschen" data-sgk-action="delete">Löschen</button></div><div class="src-storage-status" data-sgk-storage-status>' + htmlEscape(storageAvailable() ? 'Deine Kalkulationen werden lokal im Browser gespeichert.' : 'Lokales Speichern ist in dieser Umgebung nicht verfügbar.') + '</div></div>' +
			'<div class="src-result-accordion"><div class="src-accordion-item is-open"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger><span>Zusammenfassung</span></button><div class="src-accordion-content"><p>' + htmlEscape(copyBlocks.summary) + '</p></div></div><div class="src-accordion-item"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger><span>Breakdown für Export</span></button><div class="src-accordion-content"><p>' + htmlEscape(((result.export_text_blocks && result.export_text_blocks.breakdown_block) || 'Der Breakdown wird nach der Berechnung ergänzt.')) + '</p></div></div></div>';
		if (window.lucide && window.lucide.createIcons) { window.lucide.createIcons({ attrs: { 'stroke-width': 1.8 } }); }
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
			syncSegmentedControl(form.querySelector('[data-sgk-usage-type-control]'), fieldNode(form, 'usage_type').value);
			syncSegmentedControl(form.querySelector('[data-sgk-variant-control]'), fieldNode(form, 'case_variant').value);
			updateRedirectBanner(app, app.__sgkLastPayload || null);
			var validation = validateFormData(normalized, ui);
			renderValidation(app, validation);
			app.__sgkState.rawInput = raw;
			app.__sgkState.ui = ui;
			app.__sgkState.normalizedPayload = normalized;
			app.__sgkState.validation = validation;
			if (window.lucide && window.lucide.createIcons) { window.lucide.createIcons({ attrs: { 'stroke-width': 1.8 } }); }
		}
		function currentState() { return { payload: app.__sgkLastPayload, formData: clone(app.__sgkState.normalizedPayload || serializeForm(form)) }; }
		function setModalState(isOpen) { var dialog = modal.querySelector('[role="dialog"]'); var closeButton = modal.querySelector('[data-sgk-offer-close]'); modal.hidden = !isOpen; modal.classList.toggle('is-open', isOpen); modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true'); document.body.classList.toggle('sgk-modal-open', isOpen); if (isOpen) { lastFocusedElement = document.activeElement; window.requestAnimationFrame(function () { if (dialog) { dialog.focus(); } if (closeButton) { closeButton.focus(); } }); return; } if (lastFocusedElement && typeof lastFocusedElement.focus === 'function' && document.contains(lastFocusedElement)) { lastFocusedElement.focus(); } lastFocusedElement = null; }
		function openModal() { setModalState(true); }
		function closeModal() { setModalState(false); }
		function abortPendingRequest() { if (activeController) { activeController.abort(); activeController = null; } }
		function requestCalculation(reason) {
			var state = app.__sgkState;
			if (!state.normalizedPayload || !state.normalizedPayload.case_key) { resultContainer.innerHTML = DEFAULT_RESULT_MESSAGE; return; }
			if (!state.validation.valid) { updateRedirectBanner(app, null); app.__sgkLastPayload = null; resultContainer.innerHTML = '<div class="src-result-empty"><strong>Noch nicht berechnungsbereit</strong><p>' + htmlEscape(state.validation.message) + '</p></div>'; return; }
			abortPendingRequest();
			requestSequence += 1;
			state.activeRequestId = requestSequence;
			activeController = typeof AbortController === 'function' ? new AbortController() : null;
			resultContainer.innerHTML = LOADING_RESULT_MESSAGE;
			var payload = clone(state.normalizedPayload);
			payload.client_request_id = String(requestSequence);
			payload.client_reason = reason || 'change';
			fetch(sgkFrontend.restUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': sgkFrontend.nonce }, body: JSON.stringify(payload), signal: activeController ? activeController.signal : undefined })
				.then(function (response) { return response.json().catch(function () { return null; }).then(function (json) { if (!response.ok) { var error = new Error('request-failed'); error.payload = json; throw error; } return json; }); })
				.then(function (json) {
					if (String(state.activeRequestId) !== String(payload.client_request_id)) { return; }
					if (!json || !json.result) { throw new Error('invalid-payload'); }
					app.__sgkLastPayload = json;
					state.result = json.result;
					renderResult(resultContainer, json, payload);
					updateExpertBadges(app, json.ui_state || {});
					updateRedirectBanner(app, json);
					refreshSavedList(resultContainer, cases);
				})
				.catch(function (error) {
					if (error && error.name === 'AbortError') { return; }
					if (String(state.activeRequestId) !== String(payload.client_request_id)) { return; }
					updateRedirectBanner(app, error && error.payload ? error.payload : null);
					resultContainer.innerHTML = ERROR_RESULT_MESSAGE;
				});
		}
		function scheduleCalculation(reason, delay) { clearTimeout(debounceTimer); debounceTimer = setTimeout(function () { syncUI(); requestCalculation(reason); }, delay); }

		app.querySelectorAll('[data-sgk-quick-case]').forEach(function (button) { button.addEventListener('click', function () { setFieldValue(fieldNode(form, 'case_key'), button.getAttribute('data-sgk-quick-case')); syncUI(); requestCalculation('quick-case'); }); });
		app.querySelectorAll('[data-sgk-demo]').forEach(function (button) { button.addEventListener('click', function () { fillForm(form, JSON.parse(button.getAttribute('data-sgk-demo') || '{}')); syncUI(); requestCalculation('demo'); }); });
		app.addEventListener('click', function (event) {
			var segment = event.target.closest('[data-sgk-segment-value]');
			var accordion = event.target.closest('[data-sgk-accordion-trigger]');
			var foldable = event.target.closest('[data-sgk-foldable-trigger]');
			var stepButton = event.target.closest('[data-sgk-step]');
			if (segment) { var control = segment.parentElement; var select = control.hasAttribute('data-sgk-variant-control') ? fieldNode(form, 'case_variant') : fieldNode(form, 'usage_type'); if (select) { select.value = segment.getAttribute('data-sgk-segment-value'); syncSegmentedControl(control, select.value); syncUI(); requestCalculation('segment'); } return; }
			if (accordion) { var item = accordion.closest('.src-accordion-item'); var parent = item.parentElement; if (parent) { parent.querySelectorAll('.src-accordion-item').forEach(function (node) { if (node !== item) { node.classList.remove('is-open'); } }); } item.classList.toggle('is-open'); return; }
			if (foldable) { foldable.closest('.src-foldable-panel').classList.toggle('is-open'); return; }
			if (stepButton) { var stepper = stepButton.closest('[data-sgk-stepper]'); var input = stepper && stepper.querySelector('input'); if (!input) { return; } var step = parseFloat(input.getAttribute('step') || '1'); var min = parseFloat(input.getAttribute('min') || '0'); var current = parseFloat(input.value || '0'); if (isNaN(current)) { current = min || 0; } current += stepButton.getAttribute('data-sgk-step') === 'up' ? step : -step; if (!isNaN(min)) { current = Math.max(min, current); } input.value = String(Math.round(current * 100) / 100); input.dispatchEvent(new Event('input', { bubbles: true })); }
		});
		form.addEventListener('change', function () { syncUI(); scheduleCalculation('change', 160); });
		form.addEventListener('input', function () { syncUI(); scheduleCalculation('input', 260); });
		form.addEventListener('submit', function (event) { event.preventDefault(); syncUI(); requestCalculation('submit'); });
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
			if (action === 'save') { entries = getSavedCalculations(cases); entry = { id: 'sgk-' + Date.now(), version: STORAGE_VERSION, savedAt: new Date().toISOString(), projectTitle: state.formData.project_title || state.payload.result.display_title || 'Kalkulation', formData: state.formData, result: state.payload.result, exportPayload: buildExportPayload(state.payload.result, state.formData, getOfferMeta(app, state.formData)) }; setSavedCalculations([entry].concat(entries).slice(0, 15)); refreshSavedList(resultContainer, cases); if (statusNode) { statusNode.textContent = 'Kalkulation lokal gespeichert: ' + buildSavedLabel(entry); } return; }
			selectedId = (resultContainer.querySelector('[data-sgk-saved-list]') || {}).value;
			if (!selectedId) { if (statusNode) { statusNode.textContent = 'Bitte wähle zuerst eine gespeicherte Kalkulation aus.'; } return; }
			entries = getSavedCalculations(cases);
			entry = entries.find(function (item) { return item.id === selectedId; });
			if (!entry) { if (statusNode) { statusNode.textContent = 'Die gespeicherte Kalkulation konnte nicht geladen werden.'; } return; }
			if (action === 'load') { fillForm(form, entry.formData || {}); syncUI(); requestCalculation('load'); if (statusNode) { statusNode.textContent = 'Kalkulation geladen: ' + buildSavedLabel(entry); } return; }
			if (action === 'delete') { setSavedCalculations(entries.filter(function (item) { return item.id !== selectedId; })); refreshSavedList(resultContainer, cases); if (statusNode) { statusNode.textContent = 'Kalkulation gelöscht.'; } }
		});
		resultContainer.addEventListener('click', function (event) { if (!event.target.hasAttribute('data-sgk-sync-manual-offer')) { return; } var input = resultContainer.querySelector('[data-sgk-manual-offer]'); var value = normalizeNumber(input && input.value); if (value == null) { input.focus(); return; } setFieldValue(fieldNode(form, 'manual_offer_total'), String(value)); syncUI(); requestCalculation('manual-offer'); });
		modal.querySelectorAll('[data-sgk-offer-close]').forEach(function (button) { button.addEventListener('click', closeModal); });
		if (window.lucide && window.lucide.createIcons) { window.lucide.createIcons({ attrs: { 'stroke-width': 1.8 } }); }
		modal.addEventListener('input', function () { var state = currentState(); if (state.payload && state.payload.result && !modal.hidden) { hydrateOfferModal(app, state.payload.result, state.formData); } });
		modal.addEventListener('click', function (event) { var action = event.target.getAttribute('data-sgk-offer-action'); if (!action) { return; } var state = currentState(); if (!state.payload || !state.payload.result) { return; } hydrateOfferModal(app, state.payload.result, state.formData); if (action === 'copy-mail') { copyText(app.__sgkOfferPreview.text, event.target); return; } if (action === 'print') { openPrintDocument(app.__sgkOfferPreview.html); } });
		document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && !modal.hidden) { closeModal(); } });
		setModalState(false);
		syncUI();
		refreshSavedList(resultContainer, cases);
	});
})();
