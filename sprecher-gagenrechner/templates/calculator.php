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
<div class="sgk-app src-app-shell" data-sgk-app data-sgk-cases="<?php echo esc_attr( wp_json_encode( $cases ) ); ?>" data-sgk-ui-state="<?php echo esc_attr( wp_json_encode( $ui_state ) ); ?>">
	<div class="src-app-backdrop"></div>
	<div class="src-top-toolbar" aria-label="<?php esc_attr_e( 'Werkzeugleiste', 'sprecher-gagenrechner' ); ?>">
		<div class="src-toolbar-left">
			<button type="button" class="src-toolbar-btn"><i class="fa-solid fa-circle-play" aria-hidden="true"></i><span><?php esc_html_e( 'Tutorial starten', 'sprecher-gagenrechner' ); ?></span></button>
			<button type="button" class="src-toolbar-btn"><i class="fa-solid fa-book-open" aria-hidden="true"></i><span><?php esc_html_e( 'Anleitung', 'sprecher-gagenrechner' ); ?></span></button>
			<div class="src-toolbar-divider" aria-hidden="true"></div>
			<span class="src-toolbar-label"><i class="fa-solid fa-coins" aria-hidden="true"></i><?php esc_html_e( 'Währung wählen', 'sprecher-gagenrechner' ); ?></span>
			<div class="src-toolbar-currency" aria-label="<?php esc_attr_e( 'Währungsauswahl', 'sprecher-gagenrechner' ); ?>">
				<button type="button" class="src-toolbar-chip is-active" data-sgk-currency="EUR">EUR</button>
				<button type="button" class="src-toolbar-chip" data-sgk-currency="CHF">CHF</button>
				<button type="button" class="src-toolbar-chip" data-sgk-currency="USD">USD</button>
			</div>
		</div>
		<div class="src-toolbar-right">
			<button type="button" class="src-toolbar-btn src-toolbar-btn--ghost" data-sgk-reset-calculator><i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i><span><?php esc_html_e( 'Gagenrechner zurücksetzen', 'sprecher-gagenrechner' ); ?></span></button>
		</div>
	</div>
	<div class="src-layout">
		<main class="src-config-engine" aria-labelledby="sgk-config-title">
			<section class="src-hero-panel src-section--glass" aria-label="Einführung in den Rechner">
				<div class="src-hero-copy">
					<p class="src-page-eyebrow"><?php esc_html_e( 'Sprecher Gagenrechner', 'sprecher-gagenrechner' ); ?></p>
					<h2><?php esc_html_e( 'Berechne Dein Sprecherhonorar in wenigen Schritten.', 'sprecher-gagenrechner' ); ?></h2>
					<p class="src-hero-lead"><?php esc_html_e( 'Projekt wählen, Nutzung festlegen, Preis live sehen.', 'sprecher-gagenrechner' ); ?></p>
				</div>
				<div class="sgk-steps" aria-label="Kompakter Ablauf">
					<div class="sgk-step is-active"><?php esc_html_e( '01 Projekt wählen', 'sprecher-gagenrechner' ); ?></div>
					<div class="sgk-step"><?php esc_html_e( '02 Nutzung festlegen', 'sprecher-gagenrechner' ); ?></div>
					<div class="sgk-step"><?php esc_html_e( '03 Preis live sehen', 'sprecher-gagenrechner' ); ?></div>
				</div>
			</section>

			<form class="sgk-form" data-sgk-form>
				<input type="hidden" name="manual_offer_total" value="" />
				<input type="hidden" id="sgk-case-key" name="case_key" data-sgk-primary-field value="" />

				<section class="src-section src-section--glass" data-step="project">
					<div class="src-section-header">
						<div class="src-step-badge">01</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title" id="sgk-config-title"><?php esc_html_e( 'Was ist das für ein Projekt?', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Projektart wählen – wir zeigen nur relevante Felder.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-grid-cards sgk-project-grid" data-sgk-quick-case-grid>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="werbung_mit_bild">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-photo-film" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Werbung mit Bild', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'TV, CTV, Online Video, Kino', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="werbung_ohne_bild">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-waveform-lines" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Werbung ohne Bild', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Radio, Audio Ads, Funk, Ladenfunk', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="webvideo_imagefilm_praesentation_unpaid">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-clapperboard" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Imagefilm & PR', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Imagefilm, Awardfilm, Casefilm, unpaid', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="telefonansage">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-phone-volume" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Telefonie', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'IVR, Ansagen, Module', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="elearning_audioguide">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'E-Learning', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Minutenbasiert', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="podcast">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-podcast" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Podcast', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Inhalt oder Verpackung', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="app">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-mobile-screen-button" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'App', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Minutenbasiert', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="hoerbuch">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-book-open-reader" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Hörbuch', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'FAH-basiert', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="games">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-gamepad" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Games', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Session- & Tageslogik', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="redaktionell_doku_tv_reportage">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-newspaper" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Redaktionell / Doku', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Kommentar & Overvoice', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="audiodeskription">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-audio-description" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Audiodeskription', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Minutenpreis mit Mindestgage', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="kleinraeumig">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Kleinräumige Nutzung', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Lokale Sonderfälle', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card sgk-project-card" data-sgk-quick-case="session_fee">
							<span class="src-card-icon sgk-project-card__icon"><i class="fa-solid fa-stopwatch" aria-hidden="true"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Session Fee', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Aufnahmestunden ohne Lizenz', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
					</div>
					<div class="src-context-card" data-sgk-case-context hidden></div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="usage" data-sgk-dependent-step data-sgk-step-shell="usage">
					<div class="src-section-header">
						<div class="src-step-badge">02</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title"><?php esc_html_e( 'Wie wird das Projekt genutzt?', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Nur die passenden Optionen für Deinen Fall.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-panel-group" data-sgk-block="variant">
						<div class="src-panel-row src-panel-row--stack">
							<div class="src-row-content">
								<div class="src-row-label"><?php esc_html_e( 'Welche Variante passt am besten?', 'sprecher-gagenrechner' ); ?></div>
								<div class="src-row-desc" data-sgk-variant-hint><?php esc_html_e( 'Diese Auswahl passt sich automatisch Deinem Projekt an.', 'sprecher-gagenrechner' ); ?></div>
							</div>
							<select id="sgk-variant" name="case_variant" class="src-native-select src-hidden-select"></select>
							<div class="src-segmented-control src-segmented-control--wrap" data-sgk-variant-control></div>
						</div>
					</div>
					<div class="src-panel-group" data-sgk-block="usage_type">
						<div class="src-panel-row src-panel-row--stack">
							<div class="src-row-content">
								<div class="src-row-label"><?php esc_html_e( 'Welche Nutzung liegt vor?', 'sprecher-gagenrechner' ); ?></div>
								<div class="src-row-desc"><?php esc_html_e( 'Diese Auswahl erscheint nur, wenn zwischen organischer Nutzung und Paid-Kampagne unterschieden wird.', 'sprecher-gagenrechner' ); ?></div>
							</div>
							<select id="sgk-usage-type" name="usage_type" class="src-native-select src-hidden-select">
								<option value="organic_branding"><?php esc_html_e( 'Branding / organisch / nicht paid', 'sprecher-gagenrechner' ); ?></option>
								<option value="paid_advertising"><?php esc_html_e( 'Paid Advertising / klassische Werbung', 'sprecher-gagenrechner' ); ?></option>
							</select>
							<div class="src-segmented-control" data-sgk-usage-type-control>
								<button type="button" class="src-segment-btn" data-sgk-segment-value="organic_branding"><?php esc_html_e( 'Organisch / Branding', 'sprecher-gagenrechner' ); ?></button>
								<button type="button" class="src-segment-btn" data-sgk-segment-value="paid_advertising"><?php esc_html_e( 'Paid Kampagne', 'sprecher-gagenrechner' ); ?></button>
							</div>
						</div>
					</div>
					<div class="src-smart-hint" data-sgk-redirect-banner hidden>
						<i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i>
						<div>
							<strong><?php esc_html_e( 'Smart Match aktiv', 'sprecher-gagenrechner' ); ?></strong>
							<p><?php esc_html_e( 'Die Auswahl wurde passend zur optimalen Kalkulationsroute eingeordnet.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="rights" data-sgk-dependent-step data-sgk-step-shell="rights">
					<div class="src-section-header">
						<div class="src-step-badge">03</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title"><?php esc_html_e( 'Nutzungsrechte & Verwertung festlegen', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Lege nur die benötigten Rechte fest.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-rights-intro" data-sgk-rights-intro>
						<div class="src-rights-intro-card" data-sgk-rights-summary>
							<strong><?php esc_html_e( 'Rechtekompass', 'sprecher-gagenrechner' ); ?></strong>
							<p><?php esc_html_e( 'Gebiet, Laufzeit, Medium und aktive Erweiterungen.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-rights-layout src-rights-layout--core" data-sgk-rights-core>
						<div class="src-panel-group src-panel-group--highlight" data-sgk-block="territory">
							<div class="src-panel-row src-panel-row--stack">
								<div class="src-row-content">
									<div class="src-row-label"><?php esc_html_e( 'In welchem Gebiet wird die Stimme verwendet?', 'sprecher-gagenrechner' ); ?></div>
									<div class="src-row-desc"><?php esc_html_e( 'Wähle das Gebiet, in dem die Nutzung tatsächlich stattfinden soll.', 'sprecher-gagenrechner' ); ?></div>
								</div>
								<select id="sgk-territory" name="territory" class="src-native-select src-input-text">
									<option value=""><?php esc_html_e( 'Bitte Gebiet wählen', 'sprecher-gagenrechner' ); ?></option>
								</select>
							</div>
						</div>
						<div class="src-panel-group src-panel-group--highlight" data-sgk-block="duration_term">
							<div class="src-panel-row src-panel-row--stack">
								<div class="src-row-content">
									<div class="src-row-label"><?php esc_html_e( 'Wie lange sollen die Nutzungsrechte gelten?', 'sprecher-gagenrechner' ); ?></div>
									<div class="src-row-desc"><?php esc_html_e( 'Wähle die vereinbarte Laufzeit der Nutzung.', 'sprecher-gagenrechner' ); ?></div>
								</div>
								<select id="sgk-duration-term" name="duration_term" class="src-native-select src-input-text">
									<option value=""><?php esc_html_e( 'Bitte Laufzeit wählen', 'sprecher-gagenrechner' ); ?></option>
								</select>
							</div>
						</div>
						<div class="src-panel-group src-panel-group--highlight" data-sgk-block="medium">
							<div class="src-panel-row src-panel-row--stack">
								<div class="src-row-content">
									<div class="src-row-label"><?php esc_html_e( 'Über welche Medien läuft die Nutzung?', 'sprecher-gagenrechner' ); ?></div>
									<div class="src-row-desc"><?php esc_html_e( 'Wähle den Kanal, über den die Nutzung hauptsächlich ausgespielt wird.', 'sprecher-gagenrechner' ); ?></div>
								</div>
								<select id="sgk-medium" name="medium" class="src-native-select src-input-text">
									<option value=""><?php esc_html_e( 'Bitte Medium wählen', 'sprecher-gagenrechner' ); ?></option>
								</select>
							</div>
						</div>
					</div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="scope" data-sgk-dependent-step>
					<div class="src-section-header">
						<div class="src-step-badge">04</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title"><?php esc_html_e( 'Wie groß ist der Projektumfang?', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Trage nur die benötigten Umfangswerte ein.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-panel-group">
						<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Projektumfang', 'sprecher-gagenrechner' ); ?></div>
						<div class="src-panel-row" data-sgk-block="duration_minutes"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Minuten Audiomaterial?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für minutengestaffelte Projekte.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-duration" name="duration_minutes" type="number" min="0" step="0.1" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="net_minutes"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Netto-Sendeminuten?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Relevant für redaktionelle Inhalte und Audiodeskription.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-net-minutes" name="net_minutes" type="number" min="0" step="0.1" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="module_count"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Module oder Ansagen?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Zum Beispiel für IVR- oder Telefonansagen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-modules" name="module_count" type="number" min="0" step="1" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="fah"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Final Audio Hours (FAH)?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für Hörbuch-Projekte auf Basis der fertigen Audiozeit.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-fah" name="fah" type="number" min="0" step="0.5" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="recording_hours"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Aufnahmestunden?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für session-basierte Kalkulationen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-hours" name="recording_hours" type="number" min="0" step="0.5" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="recording_days"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Aufnahmetage?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für mehrtägige oder wiederkehrende Sessions.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-days" name="recording_days" type="number" min="1" step="1" value="1" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="same_day_projects"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele weitere Projekte am selben Tag?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Hilft bei parallelen Sessions oder Paketproduktionen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-projects" name="same_day_projects" type="number" min="1" step="1" value="1" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="scope_note"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Hinweis zur Kalkulation', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc" data-sgk-scope-copy><?php esc_html_e( 'Wenn hier keine weiteren Felder erscheinen, ist das beabsichtigt: Dann steuern vor allem Projektart, Variante und Rechte die Empfehlung.', 'sprecher-gagenrechner' ); ?></div></div></div>
					</div>
					<div class="src-panel-group src-toggle-grid" data-sgk-block="media_toggles">
						<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Zusätzliche Nutzungskanäle', 'sprecher-gagenrechner' ); ?></div>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Paid Media', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nur aktivieren, wenn die Stimme zusätzlich als bezahlte Kampagne ausgespielt wird.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch sgk-switch"><input type="checkbox" name="is_paid_media" value="1" /><span class="src-slider"></span></span>
						</label>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Social Media', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für zusätzliche Social-Ausspielung über die Hauptnutzung hinaus.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch sgk-switch"><input type="checkbox" name="usage_social_media" value="1" /><span class="src-slider"></span></span>
						</label>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Präsentation', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Wenn die Stimme zusätzlich für interne Präsentationen eingesetzt wird.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch sgk-switch"><input type="checkbox" name="usage_praesentation" value="1" /><span class="src-slider"></span></span>
						</label>
					</div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="extras" data-sgk-dependent-step>
					<div class="src-section-header">
						<div class="src-step-badge">05</div>
						<div class="src-section-title-wrap"><p class="src-section-title"><?php esc_html_e( 'Zusatzrechte & Sonderfälle', 'sprecher-gagenrechner' ); ?></p><p class="src-section-copy"><?php esc_html_e( 'Nur aktive Zusatzrechte und Sonderfälle.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="src-rights-layout src-rights-layout--vertical">
						<div class="src-panel-group" data-sgk-block="addon_counts">
							<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Erweiterungen der Nutzung', 'sprecher-gagenrechner' ); ?></div>
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Gibt es zusätzliche Jahre?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Erhöhe diesen Wert, wenn die Nutzung über die gewählte Grundlaufzeit hinaus verlängert wird.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-add-year" name="additional_year" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Gibt es zusätzliche Gebiete?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nutze das für weitere Länder oder zusätzliche Ausspielräume neben der Grundnutzung.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-add-territory" name="additional_territory" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Gibt es mehrere Motive oder Versionen?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nutze das für zusätzliche Motive, Versionen oder klar getrennte Varianten.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-add-motif" name="additional_motif" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
						</div>
						<div class="src-panel-group" data-sgk-block="rights_toggles">
							<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Besondere Rechteoptionen', 'sprecher-gagenrechner' ); ?></div>
							<label class="src-panel-row" data-sgk-conditional-field="archivgage"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Archivnutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Aktiviere das nur, wenn eine eigenständige Archivnutzung vereinbart wird.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch sgk-switch"><input type="checkbox" name="archivgage" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row" data-sgk-conditional-field="reminder"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Reminder', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Aktiviere das nur, wenn der Reminder zusätzlich zur Hauptnutzung gebucht wird.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch sgk-switch"><input type="checkbox" name="reminder" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row" data-sgk-conditional-field="allongen"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Allongen', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für ergänzende Verlängerungsnutzungen in passenden Audio-Werbefällen.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch sgk-switch"><input type="checkbox" name="allongen" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row" data-sgk-conditional-field="follow_up_usage"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Spätere Nachnutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Wenn ein bestehendes Layout oder eine Vorstufe später regulär genutzt werden soll.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch sgk-switch"><input type="checkbox" name="follow_up_usage" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row" data-sgk-conditional-field="is_nachgage"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Nachbuchung / Lizenzverlängerung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Setzt eine volle Verwertungsgage ohne Anrechnung bereits geleisteter Zahlungen an.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch sgk-switch"><input type="checkbox" name="is_nachgage" value="1" /><span class="src-slider"></span></span></label>
						</div>
					</div>
				</section>

				<section class="src-section src-section--glass">
					<div class="src-foldable-panel is-disabled" id="sgk-foldable-notes" data-sgk-dependent-step>
						<button type="button" class="src-foldable-header" data-sgk-foldable-trigger><span class="src-foldable-title"><i class="fa-solid fa-folder-open" aria-hidden="true"></i><?php esc_html_e( 'Projekt- & Kundendaten', 'sprecher-gagenrechner' ); ?></span><i class="fa-solid fa-plus src-foldable-icon" aria-hidden="true"></i></button>
						<div class="src-foldable-content">
							<div class="src-form-grid"><div class="src-form-field"><label for="sgk-project-title"><?php esc_html_e( 'Projektname', 'sprecher-gagenrechner' ); ?></label><input id="sgk-project-title" name="project_title" type="text" class="src-input-text" placeholder="z. B. Frühjahrskampagne 2026" /></div><div class="src-form-field"><label for="sgk-customer-name"><?php esc_html_e( 'Kunde oder Kontakt', 'sprecher-gagenrechner' ); ?></label><input id="sgk-customer-name" name="customer_name" type="text" class="src-input-text" placeholder="z. B. Muster GmbH" /></div></div>
							<div class="src-form-field"><label for="sgk-internal-notes"><?php esc_html_e( 'Interne Notiz', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-internal-notes" name="internal_notes" rows="4" class="src-input-text" placeholder="Verhandlung, Timing oder interne Hinweise"></textarea></div>
						</div>
					</div>
				</section>

				<section class="src-section src-section--glass">
					<div class="src-foldable-panel src-foldable-panel--expert is-disabled" id="sgk-foldable-expert" data-sgk-expert-shell>
						<button type="button" class="src-foldable-header" data-sgk-foldable-trigger><span class="src-foldable-title"><i class="fa-solid fa-sliders" aria-hidden="true"></i><?php esc_html_e( 'Expertenmodus & Sonderfälle', 'sprecher-gagenrechner' ); ?></span><i class="fa-solid fa-plus src-foldable-icon" aria-hidden="true"></i></button>
						<div class="src-foldable-content">
							<div class="src-badge-row" data-sgk-expert-badges><span class="src-inline-badge is-muted"><?php esc_html_e( 'Noch keine zusätzlichen Optionen aktiv', 'sprecher-gagenrechner' ); ?></span></div>
							<div class="src-panel-group">
								<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Spezielle Rechte- und Produktionsfälle', 'sprecher-gagenrechner' ); ?></div>
								<div class="src-panel-row" data-sgk-block="prior_layout_fee" data-sgk-conditional-field="prior_layout_fee"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Vorheriges Layout-Honorar', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nur nötig, wenn eine frühere Layout- oder Vorstufenvergütung auf eine spätere Nutzung angerechnet wird.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-prior-layout" name="prior_layout_fee" type="number" min="0" step="0.01" value="0" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
								<div class="src-panel-row" data-sgk-block="session_hours"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Session-Stunden', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nur für Session-Fee-Fälle ohne öffentliche Lizenz.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i class="fa-solid fa-minus" aria-hidden="true"></i></button><input id="sgk-session-hours" name="session_hours" type="number" min="0" step="0.5" value="0" /><button type="button" data-sgk-step="up"><i class="fa-solid fa-plus" aria-hidden="true"></i></button></div></div>
								<label class="src-panel-row" data-sgk-block="unlimited_usage" data-sgk-conditional-field="unlimited_time"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Zeitlich unbegrenzte Nutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Aktiviere das nur, wenn die Nutzung ohne zeitliche Begrenzung vereinbart wird.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch sgk-switch"><input type="checkbox" name="unlimited_time" value="1" /><span class="src-slider"></span></span></label>
								<label class="src-panel-row" data-sgk-block="unlimited_usage" data-sgk-conditional-field="unlimited_territory"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Räumlich unbegrenzte Nutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Wenn die Nutzung nicht auf einzelne Gebiete begrenzt ist.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch sgk-switch"><input type="checkbox" name="unlimited_territory" value="1" /><span class="src-slider"></span></span></label>
								<label class="src-panel-row" data-sgk-block="unlimited_usage" data-sgk-conditional-field="unlimited_media"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Medial unbegrenzte Nutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Wenn die Verwendung nicht auf einzelne Ausspielwege begrenzt ist.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch sgk-switch"><input type="checkbox" name="unlimited_media" value="1" /><span class="src-slider"></span></span></label>
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
					<button type="submit" class="src-btn-primary" data-sgk-submit><?php esc_html_e( 'Angebot erstellen', 'sprecher-gagenrechner' ); ?></button>
					<p class="src-actions-hint" data-sgk-validation-status><?php esc_html_e( 'Bitte starte mit einer Projektart und ergänze danach die benötigten Pflichtfelder.', 'sprecher-gagenrechner' ); ?></p>
				</div>
			</form>
		</main>

		<aside class="src-control-tower-wrapper" aria-labelledby="sgk-result-title">
			<div class="src-tower-main">
				<div class="src-tower-header">
					<div>
						<p class="src-tower-label" id="sgk-result-title"><?php esc_html_e( 'Ergebnis', 'sprecher-gagenrechner' ); ?></p>
						<h3 class="src-tower-headline"><?php esc_html_e( 'Dein Honorar live im Blick.', 'sprecher-gagenrechner' ); ?></h3>
					</div>
					<div class="src-live-badge"><span class="src-live-dot"></span><?php esc_html_e( 'Live', 'sprecher-gagenrechner' ); ?></div>
				</div>
				<div class="src-tower-result" data-sgk-result>
					<div class="src-result-state src-result-state--empty">
						<span class="src-result-state-label"><?php esc_html_e( 'Status', 'sprecher-gagenrechner' ); ?></span>
						<strong><?php esc_html_e( 'Noch keine Auswahl', 'sprecher-gagenrechner' ); ?></strong>
						<p><?php esc_html_e( 'Starte mit einem Projekt, um den Preis live zu berechnen.', 'sprecher-gagenrechner' ); ?></p>
					</div>
				</div>
				<div class="src-tower-journey-shell">
					<div class="src-inline-dark-panel src-inline-dark-panel--subtle">
						<strong><?php esc_html_e( 'Aktueller Projektstatus', 'sprecher-gagenrechner' ); ?></strong>
						<div class="src-tower-journey" data-sgk-journey-summary>
							<div class="src-tower-journey-item"><span>Projekt</span><strong><?php esc_html_e( 'Noch nicht gewählt', 'sprecher-gagenrechner' ); ?></strong></div>
							<div class="src-tower-journey-item"><span>Rechte</span><strong><?php esc_html_e( 'Folgt nach Projektwahl', 'sprecher-gagenrechner' ); ?></strong></div>
							<div class="src-tower-journey-item"><span>Umfang</span><strong><?php esc_html_e( 'Noch offen', 'sprecher-gagenrechner' ); ?></strong></div>
						</div>
					</div>
				</div>
			</div>
			<div class="src-tower-info">
				<div class="src-tower-info-header"><i class="fa-solid fa-circle-info" aria-hidden="true"></i><?php esc_html_e( 'Wissenswertes', 'sprecher-gagenrechner' ); ?></div>
				<div class="src-knowledge-accordion" data-sgk-knowledge-accordion>
					<div class="src-accordion-item is-open">
						<button type="button" class="src-accordion-btn" data-sgk-accordion-trigger aria-expanded="true" aria-controls="sgk-knowledge-basics"><span><?php esc_html_e( 'Berechnungsgrundlage', 'sprecher-gagenrechner' ); ?></span><span class="src-accordion-indicator" aria-hidden="true"></span></button>
						<div class="src-accordion-content" id="sgk-knowledge-basics"><p><?php esc_html_e( 'Projektart, Nutzungsrechte und Umfang bilden gemeinsam den Preisrahmen.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="src-accordion-item">
						<button type="button" class="src-accordion-btn" data-sgk-accordion-trigger aria-expanded="false" aria-controls="sgk-knowledge-length"><span><?php esc_html_e( 'Textlänge & Dauer', 'sprecher-gagenrechner' ); ?></span><span class="src-accordion-indicator" aria-hidden="true"></span></button>
						<div class="src-accordion-content" id="sgk-knowledge-length" hidden><p><?php esc_html_e( 'Minuten, Module oder Sessions beeinflussen die Produktionsbasis direkt.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="src-accordion-item">
						<button type="button" class="src-accordion-btn" data-sgk-accordion-trigger aria-expanded="false" aria-controls="sgk-knowledge-buyout"><span><?php esc_html_e( 'Buyouts & Unlimited', 'sprecher-gagenrechner' ); ?></span><span class="src-accordion-indicator" aria-hidden="true"></span></button>
						<div class="src-accordion-content" id="sgk-knowledge-buyout" hidden><p><?php esc_html_e( 'Unbegrenzte Nutzung muss klar vereinbart sein und wird gesondert bewertet.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="src-accordion-item">
						<button type="button" class="src-accordion-btn" data-sgk-accordion-trigger aria-expanded="false" aria-controls="sgk-knowledge-studio"><span><?php esc_html_e( 'Studio & Technik', 'sprecher-gagenrechner' ); ?></span><span class="src-accordion-indicator" aria-hidden="true"></span></button>
						<div class="src-accordion-content" id="sgk-knowledge-studio" hidden><p><?php esc_html_e( 'Aufnahmebedingungen und Technikaufwand können den Endpreis beeinflussen.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="src-accordion-item">
						<button type="button" class="src-accordion-btn" data-sgk-accordion-trigger aria-expanded="false" aria-controls="sgk-knowledge-revisions"><span><?php esc_html_e( 'Korrekturen & Revisionen', 'sprecher-gagenrechner' ); ?></span><span class="src-accordion-indicator" aria-hidden="true"></span></button>
						<div class="src-accordion-content" id="sgk-knowledge-revisions" hidden><p><?php esc_html_e( 'Korrekturschleifen sollten klar abgestimmt werden, damit der Aufwand planbar bleibt.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="src-accordion-item">
						<button type="button" class="src-accordion-btn" data-sgk-accordion-trigger aria-expanded="false" aria-controls="sgk-knowledge-exclusive"><span><?php esc_html_e( 'Exklusivität & Konkurrenzschutz', 'sprecher-gagenrechner' ); ?></span><span class="src-accordion-indicator" aria-hidden="true"></span></button>
						<div class="src-accordion-content" id="sgk-knowledge-exclusive" hidden><p><?php esc_html_e( 'Exklusivitätsklauseln brauchen eine klare Dauer und ein klar definiertes Einsatzgebiet.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
				</div>
			</div>
		</aside>
	</div>

	<div class="src-modal-backdrop" data-sgk-offer-modal hidden aria-hidden="true">
		<div class="src-modal-content" role="dialog" aria-modal="true" aria-labelledby="sgk-offer-modal-title" tabindex="-1">
			<div class="src-modal-header">
				<div>
					<p class="src-page-eyebrow"><?php esc_html_e( 'Angebot vorbereiten', 'sprecher-gagenrechner' ); ?></p>
					<h3 id="sgk-offer-modal-title"><?php esc_html_e( 'Angebot vor Versand final abstimmen', 'sprecher-gagenrechner' ); ?></h3>
					<p><?php esc_html_e( 'Ergänze nur Angaben für das Kundendokument. Die Vorschau rechts zeigt sofort die finale Wirkung.', 'sprecher-gagenrechner' ); ?></p>
				</div>
				<button type="button" class="src-btn-secondary" data-sgk-offer-close><?php esc_html_e( 'Schließen', 'sprecher-gagenrechner' ); ?></button>
			</div>
			<div class="src-modal-body src-modal-grid">
				<section class="src-modal-panel">
					<div class="src-form-grid"><div class="src-form-field"><label for="sgk-offer-number"><?php esc_html_e( 'Angebotsnummer', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-number" type="text" class="src-input-text" data-sgk-offer-meta="offer_number" placeholder="z. B. ANG-2026-001" /></div><div class="src-form-field"><label for="sgk-offer-date"><?php esc_html_e( 'Angebotsdatum', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-date" type="date" class="src-input-text" data-sgk-offer-meta="offer_date" /></div></div>
					<div class="src-form-grid"><div class="src-form-field"><label for="sgk-offer-contact"><?php esc_html_e( 'Ansprechpartner', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-contact" type="text" class="src-input-text" data-sgk-offer-meta="contact_name" /></div><div class="src-form-field"><label for="sgk-offer-company"><?php esc_html_e( 'Absender oder Studio', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-company" type="text" class="src-input-text" data-sgk-offer-meta="sender_company" /></div></div>
					<div class="src-form-grid"><div class="src-form-field"><label for="sgk-offer-email"><?php esc_html_e( 'Kontakt-E-Mail', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-email" type="email" class="src-input-text" data-sgk-offer-meta="sender_email" /></div><div class="src-form-field"><label for="sgk-offer-phone"><?php esc_html_e( 'Telefon', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-phone" type="text" class="src-input-text" data-sgk-offer-meta="sender_phone" /></div></div>
					<div class="src-form-field"><label for="sgk-offer-intro"><?php esc_html_e( 'Einleitungstext', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-intro" rows="4" class="src-input-text" data-sgk-offer-meta="intro_text"></textarea></div>
					<div class="src-form-field"><label for="sgk-offer-footer"><?php esc_html_e( 'Fußzeile / Kontaktdaten', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-footer" rows="3" class="src-input-text" data-sgk-offer-meta="footer_text"></textarea></div>
					<div class="src-form-field"><label for="sgk-offer-internal"><?php esc_html_e( 'Interne Notiz', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-internal" rows="3" class="src-input-text" data-sgk-offer-meta="internal_note"></textarea><p class="src-field-hint" data-sgk-offer-status><?php esc_html_e( 'Für den Export sollte eine finale Angebotssumme hinterlegt sein.', 'sprecher-gagenrechner' ); ?></p></div>
				</section>
				<section class="src-modal-panel"><div class="src-offer-preview-shell" data-sgk-offer-preview></div></section>
			</div>
			<div class="src-modal-footer"><button type="button" class="src-btn-secondary" data-feedback-label="Mailtext kopiert" data-sgk-offer-action="copy-mail"><?php esc_html_e( 'Mailtext kopieren', 'sprecher-gagenrechner' ); ?></button><button type="button" class="src-btn-primary" data-feedback-label="Druckdialog geöffnet" data-sgk-offer-action="print"><?php esc_html_e( 'PDF erstellen', 'sprecher-gagenrechner' ); ?></button></div>
		</div>
	</div>
</div>
