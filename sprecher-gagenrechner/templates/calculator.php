<?php
/**
 * Calculator template.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cases      = isset( $view_data['cases'] ) ? $view_data['cases'] : array();
$ui_state   = isset( $view_data['ui_state'] ) ? $view_data['ui_state'] : array();
$demo_cases = isset( $view_data['demo_cases'] ) ? $view_data['demo_cases'] : array();
?>
<script src="https://unpkg.com/lucide@latest"></script>
<div class="sgk-app src-app-shell" data-sgk-app data-sgk-cases="<?php echo esc_attr( wp_json_encode( $cases ) ); ?>" data-sgk-ui-state="<?php echo esc_attr( wp_json_encode( $ui_state ) ); ?>">
	<div class="src-app-backdrop"></div>
	<div class="src-layout">
		<main class="src-config-engine" aria-labelledby="sgk-config-title">
			<header class="src-hero-panel">
				<div class="src-hero-copy">
					<p class="src-page-eyebrow"><?php esc_html_e( 'Sprecher Gagenrechner 2026', 'sprecher-gagenrechner' ); ?></p>
					<h2 id="sgk-config-title"><?php esc_html_e( 'Neues, klares Produktdesign für schnellere und bessere Angebotsentscheidungen.', 'sprecher-gagenrechner' ); ?></h2>
					<p class="src-hero-lead"><?php esc_html_e( 'Der Rechner führt dich in wenigen Schritten von der Projektart zur belastbaren Preisempfehlung – mit klarer Struktur, Live-Feedback und sauber vorbereiteter Angebotsübergabe.', 'sprecher-gagenrechner' ); ?></p>
				</div>
				<div class="src-hero-highlights" aria-label="Produktvorteile">
					<div class="src-hero-stat">
						<strong><?php esc_html_e( 'Live', 'sprecher-gagenrechner' ); ?></strong>
						<span><?php esc_html_e( 'Aktualisierung ohne Umwege', 'sprecher-gagenrechner' ); ?></span>
					</div>
					<div class="src-hero-stat">
						<strong><?php esc_html_e( 'Smart Match', 'sprecher-gagenrechner' ); ?></strong>
						<span><?php esc_html_e( 'Projekt wird passend eingeordnet', 'sprecher-gagenrechner' ); ?></span>
					</div>
					<div class="src-hero-stat">
						<strong><?php esc_html_e( 'Export-ready', 'sprecher-gagenrechner' ); ?></strong>
						<span><?php esc_html_e( 'Angebot direkt vorbereiten', 'sprecher-gagenrechner' ); ?></span>
					</div>
				</div>
			</header>

			<form class="sgk-form" data-sgk-form>
				<input type="hidden" name="manual_offer_total" value="" />
				<input type="hidden" id="sgk-case-key" name="case_key" data-sgk-primary-field value="" />

				<section class="src-section src-section--glass" data-step="project">
					<div class="src-section-header">
						<div class="src-step-badge">01</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title"><?php esc_html_e( 'Projektart wählen', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Starte mit dem Use Case. Darauf basieren die sichtbaren Eingaben, Rechteoptionen und die Kalkulationslogik.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-grid-cards" data-sgk-quick-case-grid>
						<button type="button" class="src-card" data-sgk-quick-case="werbung_mit_bild">
							<span class="src-card-icon"><i data-lucide="monitor-play" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Werbung mit Bild', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'TV, CTV, Online Video, Kino', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="werbung_ohne_bild">
							<span class="src-card-icon"><i data-lucide="radio" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Werbung Audio', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Radio, Audio Ads, Reminder', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="webvideo_imagefilm_praesentation_unpaid">
							<span class="src-card-icon"><i data-lucide="clapperboard" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Imagefilm & PR', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Webvideo, Präsentation, unpaid', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="telefonansage">
							<span class="src-card-icon"><i data-lucide="phone-call" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Telefonie', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'IVR, Ansagen, Module', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="elearning_audioguide">
							<span class="src-card-icon"><i data-lucide="graduation-cap" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'E-Learning', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Minutenbasiert mit klarer Staffel', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="podcast">
							<span class="src-card-icon"><i data-lucide="podcast" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Podcast', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Inhalt oder Verpackung', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
					</div>
					<div class="src-context-card" data-sgk-case-context hidden></div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="usage" data-sgk-dependent-step>
					<div class="src-section-header">
						<div class="src-step-badge">02</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title"><?php esc_html_e( 'Nutzung präzisieren', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Definiere Format, Ausspielung und Medienlogik. So bleibt die Preisempfehlung nachvollziehbar und belastbar.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-panel-group" data-sgk-block="variant">
						<div class="src-panel-row src-panel-row--stack">
							<div class="src-row-content">
								<div class="src-row-label"><?php esc_html_e( 'Format-Variante', 'sprecher-gagenrechner' ); ?></div>
								<div class="src-row-desc" data-sgk-variant-hint><?php esc_html_e( 'Diese Auswahl passt sich automatisch deinem Projekt an.', 'sprecher-gagenrechner' ); ?></div>
							</div>
							<select id="sgk-variant" name="case_variant" class="src-native-select src-hidden-select"></select>
							<div class="src-segmented-control src-segmented-control--wrap" data-sgk-variant-control></div>
						</div>
					</div>
					<div class="src-panel-group" data-sgk-block="usage_type">
						<div class="src-panel-row src-panel-row--stack">
							<div class="src-row-content">
								<div class="src-row-label"><?php esc_html_e( 'Nutzungsart', 'sprecher-gagenrechner' ); ?></div>
								<div class="src-row-desc"><?php esc_html_e( 'Organisch oder paid – die Auswahl steuert Rechte- und Routinglogik im Hintergrund.', 'sprecher-gagenrechner' ); ?></div>
							</div>
							<select id="sgk-usage-type" name="usage_type" class="src-native-select src-hidden-select">
								<option value="organic_branding"><?php esc_html_e( 'Branding / organisch / nicht paid', 'sprecher-gagenrechner' ); ?></option>
								<option value="paid_advertising"><?php esc_html_e( 'Paid Advertising / klassische Werbung', 'sprecher-gagenrechner' ); ?></option>
							</select>
							<div class="src-segmented-control" data-sgk-usage-type-control>
								<button type="button" class="src-segment-btn" data-sgk-segment-value="organic_branding"><?php esc_html_e( 'Branding', 'sprecher-gagenrechner' ); ?></button>
								<button type="button" class="src-segment-btn" data-sgk-segment-value="paid_advertising"><?php esc_html_e( 'Paid Kampagne', 'sprecher-gagenrechner' ); ?></button>
							</div>
						</div>
					</div>
					<div class="src-smart-hint" data-sgk-redirect-banner hidden>
						<i data-lucide="sparkles" width="18" height="18"></i>
						<div>
							<strong><?php esc_html_e( 'Smart Match aktiv', 'sprecher-gagenrechner' ); ?></strong>
							<p><?php esc_html_e( 'Die Auswahl wurde passend zur optimalen Kalkulationsroute eingeordnet.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-panel-group src-toggle-grid" data-sgk-block="media_toggles">
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Paid Media', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für bezahlte Ausspielung und Reichweite.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch"><input type="checkbox" name="is_paid_media" value="1" /><span class="src-slider"></span></span>
						</label>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Social Media', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Erweitert die Nutzung um soziale Netzwerke.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch"><input type="checkbox" name="usage_social_media" value="1" /><span class="src-slider"></span></span>
						</label>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Präsentation', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für interne oder vertriebsnahe Nutzung.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch"><input type="checkbox" name="usage_praesentation" value="1" /><span class="src-slider"></span></span>
						</label>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Awardfilm', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Optional für Cases mit Festival- oder Awardnutzung.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch"><input type="checkbox" name="usage_awardfilm" value="1" /><span class="src-slider"></span></span>
						</label>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Mitarbeiterfilm', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für interne Unternehmensfilme.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch"><input type="checkbox" name="usage_mitarbeiterfilm" value="1" /><span class="src-slider"></span></span>
						</label>
					</div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="scope" data-sgk-dependent-step>
					<div class="src-section-header">
						<div class="src-step-badge">03</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title"><?php esc_html_e( 'Umfang definieren', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Alle relevanten Mengen und Zeitwerte sind an einem Ort gebündelt – schnell erfassbar und ohne visuelle Reibung.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-panel-group">
						<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Basisparameter', 'sprecher-gagenrechner' ); ?></div>
						<div class="src-panel-row" data-sgk-block="duration_minutes"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Dauer in Minuten', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für minutengestaffelte Projekte.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-duration" name="duration_minutes" type="number" min="0" step="0.1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="net_minutes"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Netto-Sendeminuten', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Relevant für redaktionelle Inhalte und Audiodeskription.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-net-minutes" name="net_minutes" type="number" min="0" step="0.1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="module_count"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Anzahl der Module', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Zum Beispiel für IVR- oder Telefonansagen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-modules" name="module_count" type="number" min="0" step="1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="fah"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Final Audio Hours (FAH)', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für Hörbuch-Projekte auf Basis der fertigen Audiozeit.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-fah" name="fah" type="number" min="0" step="0.5" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="recording_hours"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Aufnahmestunden', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für session-basierte Kalkulationen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-hours" name="recording_hours" type="number" min="0" step="0.5" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="recording_days"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Aufnahmetage', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für mehrtägige oder wiederkehrende Sessions.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-days" name="recording_days" type="number" min="1" step="1" value="1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="same_day_projects"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Weitere Projekte am selben Tag', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Hilft bei parallelen Sessions oder Paketproduktionen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-projects" name="same_day_projects" type="number" min="1" step="1" value="1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="scope_note"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Smart Scope', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc" data-sgk-scope-copy><?php esc_html_e( 'Die Hinweise passen sich deiner Projektart automatisch an.', 'sprecher-gagenrechner' ); ?></div></div></div>
					</div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="rights" data-sgk-dependent-step>
					<div class="src-section-header">
						<div class="src-step-badge">04</div>
						<div class="src-section-title-wrap"><p class="src-section-title"><?php esc_html_e( 'Rechte & Zusatzoptionen', 'sprecher-gagenrechner' ); ?></p><p class="src-section-copy"><?php esc_html_e( 'Ergänze gezielt Laufzeit, Gebiete und Sonderfälle – ohne das Interface zu überladen.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="src-rights-layout">
						<div class="src-panel-group" data-sgk-block="addon_counts">
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Zusatzjahre', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-add-year" name="additional_year" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Zusätzliche Gebiete', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-add-territory" name="additional_territory" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Zusatzmotive', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-add-motif" name="additional_motif" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						</div>
						<div class="src-panel-group" data-sgk-block="rights_toggles">
							<label class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Archivnutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für passende Paid-Fälle mit separater Archivnutzung.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="archivgage" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Reminder', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Zusätzliche Reminder-Nutzung für passende Werbefälle.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="reminder" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Allongen', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Ergänzend für klassische Audio-Werbung.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="allongen" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Nachnutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für spätere weitere Nutzung auf Basis einer bestehenden Kalkulation.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="follow_up_usage" value="1" /><span class="src-slider"></span></span></label>
						</div>
					</div>
				</section>

				<section class="src-section src-section--glass">
					<div class="src-foldable-panel is-disabled" id="sgk-foldable-notes" data-sgk-dependent-step>
						<button type="button" class="src-foldable-header" data-sgk-foldable-trigger><span class="src-foldable-title"><i data-lucide="folder-pen" width="16" height="16"></i><?php esc_html_e( 'Projekt- & Kundendaten', 'sprecher-gagenrechner' ); ?></span><i data-lucide="chevron-down" class="src-foldable-icon" width="16" height="16"></i></button>
						<div class="src-foldable-content">
							<div class="src-form-grid"><div class="src-form-field"><label for="sgk-project-title"><?php esc_html_e( 'Projektname', 'sprecher-gagenrechner' ); ?></label><input id="sgk-project-title" name="project_title" type="text" class="src-input-text" placeholder="z. B. Frühjahrskampagne 2026" /></div><div class="src-form-field"><label for="sgk-customer-name"><?php esc_html_e( 'Kunde oder Kontakt', 'sprecher-gagenrechner' ); ?></label><input id="sgk-customer-name" name="customer_name" type="text" class="src-input-text" placeholder="z. B. Muster GmbH" /></div></div>
							<div class="src-form-field"><label for="sgk-internal-notes"><?php esc_html_e( 'Interne Notiz', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-internal-notes" name="internal_notes" rows="4" class="src-input-text" placeholder="Verhandlung, Timing oder interne Hinweise"></textarea></div>
						</div>
					</div>
				</section>

				<section class="src-section src-section--glass">
					<div class="src-foldable-panel src-foldable-panel--expert is-disabled" id="sgk-foldable-expert" data-sgk-expert-shell>
						<button type="button" class="src-foldable-header" data-sgk-foldable-trigger><span class="src-foldable-title"><i data-lucide="settings-2" width="16" height="16"></i><?php esc_html_e( 'Expertenmodus & Sonderfälle', 'sprecher-gagenrechner' ); ?></span><i data-lucide="chevron-down" class="src-foldable-icon" width="16" height="16"></i></button>
						<div class="src-foldable-content">
							<div class="src-badge-row" data-sgk-expert-badges><span class="src-inline-badge is-muted"><?php esc_html_e( 'Noch keine zusätzlichen Optionen aktiv', 'sprecher-gagenrechner' ); ?></span></div>
							<div class="src-panel-group">
								<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Vorheriges Layout-Honorar', 'sprecher-gagenrechner' ); ?></div></div><input id="sgk-prior-layout" name="prior_layout_fee" type="number" min="0" step="0.01" value="0" class="src-input-text src-input-text--compact" /></div>
								<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Session-Stunden', 'sprecher-gagenrechner' ); ?></div></div><input id="sgk-session-hours" name="session_hours" type="number" min="0" step="0.5" value="0" class="src-input-text src-input-text--compact" /></div>
								<label class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Zeitlich unbegrenzt', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für besondere Fälle mit unbegrenzter Laufzeit.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="unlimited_time" value="1" /><span class="src-slider"></span></span></label>
								<label class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Räumlich unbegrenzt', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für Einsätze ohne räumliche Begrenzung.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="unlimited_territory" value="1" /><span class="src-slider"></span></span></label>
								<label class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Medial unbegrenzt', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für weitreichende Sondervereinbarungen.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="unlimited_media" value="1" /><span class="src-slider"></span></span></label>
							</div>
							<?php if ( ! empty( $demo_cases ) ) : ?>
								<div class="src-demo-shell">
									<div class="src-row-label"><?php esc_html_e( 'Beispielkonfigurationen', 'sprecher-gagenrechner' ); ?></div>
									<div class="src-grid-cards src-grid-cards--demo">
										<?php foreach ( $demo_cases as $demo ) : ?>
											<button type="button" class="src-card src-card--demo" data-sgk-demo='<?php echo esc_attr( wp_json_encode( $demo['input'] ) ); ?>'><span class="src-card-copy"><strong><?php echo esc_html( $demo['label'] ); ?></strong><small><?php esc_html_e( 'Schnell laden', 'sprecher-gagenrechner' ); ?></small></span></button>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</section>

				<div class="src-actions">
					<button type="submit" class="src-btn-primary"><?php esc_html_e( 'Preisrahmen berechnen', 'sprecher-gagenrechner' ); ?></button>
					<p class="src-actions-hint"><?php esc_html_e( 'Die Live-Kalkulation aktualisiert sich automatisch, sobald du eine relevante Eingabe änderst.', 'sprecher-gagenrechner' ); ?></p>
				</div>
			</form>
		</main>

		<aside class="src-control-tower-wrapper" aria-labelledby="sgk-result-title">
			<div class="src-tower-main">
				<div class="src-tower-header">
					<div>
						<p class="src-tower-label" id="sgk-result-title"><?php esc_html_e( 'Empfehlung in Echtzeit', 'sprecher-gagenrechner' ); ?></p>
						<h3 class="src-tower-headline"><?php esc_html_e( 'Preisrahmen, Rechte und Export an einem Ort.', 'sprecher-gagenrechner' ); ?></h3>
					</div>
					<div class="src-live-badge"><span class="src-live-dot"></span><?php esc_html_e( 'Live', 'sprecher-gagenrechner' ); ?></div>
				</div>
				<div class="src-tower-intro">
					<div class="src-inline-dark-panel"><strong><?php esc_html_e( 'Warum das hilfreich ist', 'sprecher-gagenrechner' ); ?></strong><?php esc_html_e( 'Du siehst die empfohlene Netto-Gage sofort inklusive Aufschlüsselung, Speichern, Copy-Actions und Angebotsvorbereitung.', 'sprecher-gagenrechner' ); ?></div>
				</div>
				<div class="src-tower-result" data-sgk-result>
					<div class="src-result-empty"><strong><?php esc_html_e( 'Projekt auswählen', 'sprecher-gagenrechner' ); ?></strong><p><?php esc_html_e( 'Sobald links eine Projektart aktiv ist, erscheint hier die empfohlene Netto-Gage mit Aufschlüsselung.', 'sprecher-gagenrechner' ); ?></p></div>
				</div>
			</div>
			<div class="src-tower-info">
				<div class="src-tower-info-header"><i data-lucide="lightbulb" width="16" height="16"></i><?php esc_html_e( 'Wissenswertes', 'sprecher-gagenrechner' ); ?></div>
				<div class="src-accordion-item"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger><span><?php esc_html_e( 'Was genau ist ein Buyout?', 'sprecher-gagenrechner' ); ?></span><i data-lucide="chevron-down" width="14" height="14"></i></button><div class="src-accordion-content"><?php esc_html_e( 'Ein Buyout ist die zeitliche, räumliche und mediale Nutzungsrechteabgeltung. Die Basisgage deckt nur die reine Sprachaufnahme ab.', 'sprecher-gagenrechner' ); ?></div></div>
				<div class="src-accordion-item"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger><span><?php esc_html_e( 'Gilt die Gage pro Motiv?', 'sprecher-gagenrechner' ); ?></span><i data-lucide="chevron-down" width="14" height="14"></i></button><div class="src-accordion-content"><?php esc_html_e( 'Ja, jede eigenständige Variation gilt laut VDS als eigenes Motiv.', 'sprecher-gagenrechner' ); ?></div></div>
				<div class="src-accordion-item"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger><span><?php esc_html_e( 'Was ist der Mehrwert des Rechners?', 'sprecher-gagenrechner' ); ?></span><i data-lucide="chevron-down" width="14" height="14"></i></button><div class="src-accordion-content"><?php esc_html_e( 'Er reduziert Abstimmungsschleifen, strukturiert die Erfassung und bringt Preislogik, Rechte und Angebotsvorbereitung in einen einheitlichen Flow.', 'sprecher-gagenrechner' ); ?></div></div>
			</div>
		</aside>
	</div>

	<div class="src-modal-backdrop" data-sgk-offer-modal hidden aria-hidden="true">
		<div class="src-modal-content" role="dialog" aria-modal="true" aria-labelledby="sgk-offer-modal-title" tabindex="-1">
			<div class="src-modal-header">
				<div>
					<p class="src-page-eyebrow"><?php esc_html_e( 'Angebot vorbereiten', 'sprecher-gagenrechner' ); ?></p>
					<h3 id="sgk-offer-modal-title"><?php esc_html_e( 'Angebotsangaben vor dem PDF-Export prüfen', 'sprecher-gagenrechner' ); ?></h3>
					<p><?php esc_html_e( 'Ergänze Kontaktdaten und Einleitung. Die Vorschau aktualisiert sich sofort.', 'sprecher-gagenrechner' ); ?></p>
				</div>
				<button type="button" class="src-btn-secondary" data-sgk-offer-close><?php esc_html_e( 'Schließen', 'sprecher-gagenrechner' ); ?></button>
			</div>
			<div class="src-modal-body src-modal-grid">
				<section class="src-modal-panel">
					<div class="src-form-grid"><div class="src-form-field"><label for="sgk-offer-number"><?php esc_html_e( 'Angebotsnummer', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-number" type="text" class="src-input-text" data-sgk-offer-meta="offer_number" placeholder="z. B. ANG-2026-001" /></div><div class="src-form-field"><label for="sgk-offer-date"><?php esc_html_e( 'Angebotsdatum', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-date" type="date" class="src-input-text" data-sgk-offer-meta="offer_date" /></div></div>
					<div class="src-form-grid"><div class="src-form-field"><label for="sgk-offer-contact"><?php esc_html_e( 'Ansprechpartner', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-contact" type="text" class="src-input-text" data-sgk-offer-meta="contact_name" /></div><div class="src-form-field"><label for="sgk-offer-company"><?php esc_html_e( 'Absender oder Studio', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-company" type="text" class="src-input-text" data-sgk-offer-meta="sender_company" /></div></div>
					<div class="src-form-grid"><div class="src-form-field"><label for="sgk-offer-email"><?php esc_html_e( 'Kontakt-E-Mail', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-email" type="email" class="src-input-text" data-sgk-offer-meta="sender_email" /></div><div class="src-form-field"><label for="sgk-offer-phone"><?php esc_html_e( 'Telefon', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-phone" type="text" class="src-input-text" data-sgk-offer-meta="sender_phone" /></div></div>
					<div class="src-form-field"><label for="sgk-offer-intro"><?php esc_html_e( 'Einleitung', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-intro" rows="4" class="src-input-text" data-sgk-offer-meta="intro_text"></textarea></div>
					<div class="src-form-field"><label for="sgk-offer-footer"><?php esc_html_e( 'Fußzeile oder Kontaktdaten', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-footer" rows="3" class="src-input-text" data-sgk-offer-meta="footer_text"></textarea></div>
					<div class="src-form-field"><label for="sgk-offer-internal"><?php esc_html_e( 'Interne Notiz', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-internal" rows="3" class="src-input-text" data-sgk-offer-meta="internal_note"></textarea><p class="src-field-hint" data-sgk-offer-status><?php esc_html_e( 'Am besten hinterlegst du vor dem Export eine finale Angebotssumme.', 'sprecher-gagenrechner' ); ?></p></div>
				</section>
				<section class="src-modal-panel"><div class="src-offer-preview-shell" data-sgk-offer-preview></div></section>
			</div>
			<div class="src-modal-footer"><button type="button" class="src-btn-secondary" data-sgk-offer-action="copy-mail"><?php esc_html_e( 'Angebotstext kopieren', 'sprecher-gagenrechner' ); ?></button><button type="button" class="src-btn-primary" data-sgk-offer-action="print"><?php esc_html_e( 'PDF drucken oder speichern', 'sprecher-gagenrechner' ); ?></button></div>
		</div>
	</div>
</div>
