<?php
/**
 * Calculator template – Two-column layout with Setup Wizard and Live Result Panel.
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
<div class="sgk-app" data-sgk-app data-sgk-cases="<?php echo esc_attr( wp_json_encode( $cases ) ); ?>" data-sgk-ui-state="<?php echo esc_attr( wp_json_encode( $ui_state ) ); ?>">

	<!-- Top Toolbar -->
	<div class="sgk-top-toolbar" aria-label="<?php esc_attr_e( 'Werkzeugleiste', 'sprecher-gagenrechner' ); ?>">
		<div class="sgk-toolbar-left">
			<button type="button" class="sgk-toolbar-btn" data-sgk-tutorial>
				<i class="fa-solid fa-circle-play" aria-hidden="true"></i>
				<span><?php esc_html_e( 'Tutorial', 'sprecher-gagenrechner' ); ?></span>
			</button>
			<button type="button" class="sgk-toolbar-btn" data-sgk-guide>
				<i class="fa-solid fa-book-open" aria-hidden="true"></i>
				<span><?php esc_html_e( 'Anleitung', 'sprecher-gagenrechner' ); ?></span>
			</button>
		</div>
		<div class="sgk-toolbar-right">
			<button type="button" class="sgk-toolbar-btn sgk-toolbar-btn--secondary" data-sgk-reset-calculator>
				<i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i>
				<span><?php esc_html_e( 'Zurücksetzen', 'sprecher-gagenrechner' ); ?></span>
			</button>
		</div>

		<!-- Offer Modal -->
		<div class="src-modal-backdrop" data-sgk-offer-modal hidden aria-hidden="true">
			<div class="src-modal-content" role="dialog" aria-modal="true" aria-labelledby="sgk-offer-modal-title" tabindex="-1">
				<div class="src-modal-header">
					<div>
						<p class="src-page-eyebrow"><?php esc_html_e( 'Angebot vorbereiten', 'sprecher-gagenrechner' ); ?></p>
						<h3 id="sgk-offer-modal-title"><?php esc_html_e( 'Angebot vor Versand final abstimmen', 'sprecher-gagenrechner' ); ?></h3>
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

	<!-- Main Two-Column Layout -->
	<div class="sgk-main-container">

		<!-- LEFT COLUMN: Setup Wizard -->
		<aside class="sgk-setup-column">
			<form class="sgk-form" data-sgk-form>
				<!-- Hidden Fields – alle FIELD_DEFAULTS müssen als Input existieren -->
				<input type="hidden" name="case_key" data-sgk-primary-field value="" />
				<input type="hidden" name="manual_offer_total" value="" />
				<input type="hidden" name="case_variant" value="" />
				<input type="hidden" name="usage_type" value="organic_branding" />
				<input type="hidden" name="duration_term" value="" />
				<input type="hidden" name="territory" value="" />
				<input type="hidden" name="medium" value="" />
				<input type="hidden" name="package_key" value="" />
				<input type="hidden" name="project_title" value="" />
				<input type="hidden" name="customer_name" value="" />
				<input type="hidden" name="internal_notes" value="" />
				<input type="hidden" name="needs_cutdown" value="0" />
				<input type="hidden" name="layout_fee" value="0" />

				<!-- STEP 1: Project Selection -->
				<section class="sgk-step-section" data-sgk-step="1">
					<div class="sgk-step-header">
						<h2 class="sgk-step-title"><?php esc_html_e( 'Was ist das für ein Projekt?', 'sprecher-gagenrechner' ); ?></h2>
						<p class="sgk-step-subtitle"><?php esc_html_e( 'Wähle aus 13 Projektarten', 'sprecher-gagenrechner' ); ?></p>
					</div>

					<!-- Project Cards Grid -->
					<div class="sgk-project-grid" data-sgk-project-grid>
						<button type="button" class="sgk-project-card" data-sgk-case="werbung_mit_bild" title="<?php esc_attr_e( 'Werbung mit Bild – TV, Online Video, Kino, CTV', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-photo-film" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Werbung mit Bild', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'TV, Online Video, Kino, CTV', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="werbung_ohne_bild" title="<?php esc_attr_e( 'Werbung ohne Bild – Radio, Audio Ads, Ladenfunk', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-volume-high" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Werbung ohne Bild', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Radio, Audio Ads, Ladenfunk', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="webvideo_imagefilm_praesentation_unpaid" title="<?php esc_attr_e( 'Imagefilm & PR – Imagefilm, Webvideo, Casefilm, unpaid', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-clapperboard" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Imagefilm & PR', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Imagefilm, Webvideo, Casefilm, unpaid', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="telefonansage" title="<?php esc_attr_e( 'Telefonie – IVR, Ansagen, Module', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-phone-volume" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Telefonie', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'IVR, Ansagen, Module', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="elearning_audioguide" title="<?php esc_attr_e( 'E-Learning – Minutenbasiert, intern', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'E-Learning', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Minutenbasiert, intern', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="podcast" title="<?php esc_attr_e( 'Podcast – Inhalt oder Verpackung', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-podcast" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Podcast', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Inhalt oder Verpackung', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="app" title="<?php esc_attr_e( 'App – Zeitlich unbegrenzt, minutenbasiert', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-mobile-screen-button" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'App', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Minutenbasiert', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="hoerbuch" title="<?php esc_attr_e( 'Hörbuch – FAH-basiert, Vorschlagskalkulation', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-book-open-reader" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Hörbuch', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'FAH-basiert', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="games" title="<?php esc_attr_e( 'Games – Session- & Tageslogik', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-gamepad" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Games', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Session- & Tageslogik', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="redaktionell_doku_tv_reportage" title="<?php esc_attr_e( 'Redaktionell / Doku – Kommentar & Overvoice', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-newspaper" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Redaktionell / Doku', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Kommentar & Overvoice', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="audiodeskription" title="<?php esc_attr_e( 'Audiodeskription – Minutensatz mit Mindestgage', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-audio-description" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Audiodeskription', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Minutensatz mit Mindestgage', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="kleinraeumig" title="<?php esc_attr_e( 'Kleinräumige Nutzung – Lokal, KMU, Sonderfall', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Kleinräumige Nutzung', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Lokal, KMU, Sonderfall', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
						<button type="button" class="sgk-project-card" data-sgk-case="session_fee" title="<?php esc_attr_e( 'Session Fee – Stunden ohne Lizenz', 'sprecher-gagenrechner' ); ?>">
							<span class="sgk-card-icon"><i class="fa-solid fa-stopwatch" aria-hidden="true"></i></span>
							<span class="sgk-card-text">
								<strong><?php esc_html_e( 'Session Fee', 'sprecher-gagenrechner' ); ?></strong>
								<small><?php esc_html_e( 'Stunden ohne Lizenz', 'sprecher-gagenrechner' ); ?></small>
							</span>
						</button>
					</div>
				</section>

				<!-- STEP 2: Variants (conditional) -->
				<section class="sgk-step-section" data-sgk-step="2" hidden>
					<div class="sgk-step-header">
						<h2 class="sgk-step-title"><?php esc_html_e( 'Welche Ausprägung?', 'sprecher-gagenrechner' ); ?></h2>
						<p class="sgk-step-subtitle"><?php esc_html_e( 'Passend zu Deinem Projekt', 'sprecher-gagenrechner' ); ?></p>
					</div>

					<div class="sgk-field-group" data-sgk-block="variant">
						<div class="sgk-variants-control" data-sgk-variant-pills></div>
						<div class="sgk-variant-help" data-sgk-variant-help></div>
					</div>
				</section>

				<!-- STEP 3: Usage Parameters (conditional) -->
				<section class="sgk-step-section" data-sgk-step="3" hidden>
					<div class="sgk-step-header">
						<h2 class="sgk-step-title"><?php esc_html_e( 'Wie wird es genutzt?', 'sprecher-gagenrechner' ); ?></h2>
						<p class="sgk-step-subtitle"><?php esc_html_e( 'Kontextsensitive Felder nur für Deinen Fall', 'sprecher-gagenrechner' ); ?></p>
					</div>

					<!-- Field Group: Territory -->
					<div class="sgk-field-group" data-sgk-block="territory" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Gebiet', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-territory-pills" data-sgk-territory-pills></div>
					</div>

					<!-- Field Group: Duration Term (Laufzeit) -->
					<div class="sgk-field-group" data-sgk-block="duration_term" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Laufzeit', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-duration-pills" data-sgk-duration-pills></div>
					</div>

					<!-- Field Group: Duration Minutes (Slider) -->
					<div class="sgk-field-group" data-sgk-block="duration_minutes" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Dauer (Minuten)', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-range-container">
							<input type="range" name="duration_minutes" class="sgk-range-slider" min="1" max="120" step="0.5" data-sgk-range="duration_minutes" />
							<div class="sgk-range-value" data-sgk-range-display="duration_minutes">1 Minute</div>
						</div>
					</div>

					<!-- Field Group: Net Minutes -->
					<div class="sgk-field-group" data-sgk-block="net_minutes" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Netto-Sendeminuten', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-stepper-container">
							<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="net_minutes" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
							<input type="number" name="net_minutes" class="sgk-stepper-input" data-sgk-stepper-input="net_minutes" min="1" step="0.1" />
							<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="net_minutes" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
						</div>
					</div>

					<!-- Field Group: Module Count -->
					<div class="sgk-field-group" data-sgk-block="module_count" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Module', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-stepper-container">
							<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="module_count" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
							<input type="number" name="module_count" class="sgk-stepper-input" data-sgk-stepper-input="module_count" min="1" step="1" />
							<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="module_count" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
						</div>
					</div>

					<!-- Field Group: FAH (Hörbuch) -->
					<div class="sgk-field-group" data-sgk-block="fah" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Final Audio Hours (FAH)', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-stepper-container">
							<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="fah" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
							<input type="number" name="fah" class="sgk-stepper-input" data-sgk-stepper-input="fah" min="1" step="0.5" />
							<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="fah" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
						</div>
					</div>

					<!-- Field Group: Recording Hours (Games) -->
					<div class="sgk-field-group" data-sgk-block="recording_hours" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Aufnahmestunden', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-stepper-container">
							<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="recording_hours" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
							<input type="number" name="recording_hours" class="sgk-stepper-input" data-sgk-stepper-input="recording_hours" min="1" step="0.5" />
							<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="recording_hours" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
						</div>
					</div>

					<!-- Field Group: Recording Days -->
					<div class="sgk-field-group" data-sgk-block="recording_days" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Aufnahmetage', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-stepper-container">
							<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="recording_days" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
							<input type="number" name="recording_days" class="sgk-stepper-input" data-sgk-stepper-input="recording_days" min="1" step="1" />
							<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="recording_days" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
						</div>
					</div>

					<!-- Field Group: Same Day Projects -->
					<div class="sgk-field-group" data-sgk-block="same_day_projects" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Weitere Projekte am selben Tag', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-stepper-container">
							<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="same_day_projects" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
							<input type="number" name="same_day_projects" class="sgk-stepper-input" data-sgk-stepper-input="same_day_projects" min="1" step="1" />
							<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="same_day_projects" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
						</div>
					</div>

					<!-- Field Group: Session Hours -->
					<div class="sgk-field-group" data-sgk-block="session_hours" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Session-Stunden', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-stepper-container">
							<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="session_hours" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
							<input type="number" name="session_hours" class="sgk-stepper-input" data-sgk-stepper-input="session_hours" min="1" step="0.5" />
							<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="session_hours" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
						</div>
					</div>

					<!-- Field Group: Medium -->
					<div class="sgk-field-group" data-sgk-block="medium" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Medium / Ausspielweg', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-medium-pills" data-sgk-medium-pills></div>
					</div>

					<!-- Field Group: Media Toggles (is_paid_media, usage_social_media, usage_praesentation) -->
					<div class="sgk-field-group" data-sgk-block="media_toggles" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Zusätzliche Kanäle', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-toggles-container" data-sgk-media-toggles>
							<label class="sgk-toggle-item">
								<input type="checkbox" name="is_paid_media" value="1" />
								<span class="sgk-toggle-label"><?php esc_html_e( 'Paid Media', 'sprecher-gagenrechner' ); ?></span>
							</label>
							<label class="sgk-toggle-item">
								<input type="checkbox" name="usage_social_media" value="1" />
								<span class="sgk-toggle-label"><?php esc_html_e( 'Social Media', 'sprecher-gagenrechner' ); ?></span>
							</label>
							<label class="sgk-toggle-item">
								<input type="checkbox" name="usage_praesentation" value="1" />
								<span class="sgk-toggle-label"><?php esc_html_e( 'Präsentation', 'sprecher-gagenrechner' ); ?></span>
							</label>
						</div>
					</div>

					<!-- Field Group: Usage Type -->
					<div class="sgk-field-group" data-sgk-block="usage_type" hidden>
						<label class="sgk-field-label"><?php esc_html_e( 'Nutzungsart', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-usage-pills" data-sgk-usage-pills></div>
					</div>
				</section>

				<!-- STEP 4: Extensions (collapsed by default) -->
				<section class="sgk-step-section" data-sgk-step="4" hidden>
					<div class="sgk-step-header">
						<h2 class="sgk-step-title"><?php esc_html_e( 'Erweiterungen & Optionen', 'sprecher-gagenrechner' ); ?></h2>
						<p class="sgk-step-subtitle"><?php esc_html_e( 'Zusatzrechte und Sonderoptionen', 'sprecher-gagenrechner' ); ?></p>
					</div>

					<!-- Sub-section: Additional Counts -->
					<div class="sgk-extension-group" data-sgk-block="addon_counts" hidden>
						<h3 class="sgk-extension-title"><?php esc_html_e( 'Zusätzliche Nutzungen', 'sprecher-gagenrechner' ); ?></h3>

						<div class="sgk-field-group">
							<label class="sgk-field-label"><?php esc_html_e( 'Zusätzliche Jahre', 'sprecher-gagenrechner' ); ?></label>
							<div class="sgk-stepper-container">
								<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="additional_year" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
								<input type="number" name="additional_year" class="sgk-stepper-input" data-sgk-stepper-input="additional_year" min="0" step="1" />
								<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="additional_year" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
							</div>
						</div>

						<div class="sgk-field-group">
							<label class="sgk-field-label"><?php esc_html_e( 'Zusätzliche Gebiete', 'sprecher-gagenrechner' ); ?></label>
							<div class="sgk-stepper-container">
								<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="additional_territory" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
								<input type="number" name="additional_territory" class="sgk-stepper-input" data-sgk-stepper-input="additional_territory" min="0" step="1" />
								<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="additional_territory" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
							</div>
						</div>

						<div class="sgk-field-group">
							<label class="sgk-field-label"><?php esc_html_e( 'Zusätzliche Motive', 'sprecher-gagenrechner' ); ?></label>
							<div class="sgk-stepper-container">
								<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="additional_motif" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
								<input type="number" name="additional_motif" class="sgk-stepper-input" data-sgk-stepper-input="additional_motif" min="0" step="1" />
								<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="additional_motif" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
							</div>
						</div>
					</div>

					<!-- Sub-section: Rights Toggles -->
					<div class="sgk-extension-group" data-sgk-block="rights_toggles" hidden>
						<h3 class="sgk-extension-title"><?php esc_html_e( 'Zusatzrechte', 'sprecher-gagenrechner' ); ?></h3>
						<div class="sgk-toggles-container" data-sgk-rights-toggles>
							<label class="sgk-toggle-item">
								<input type="checkbox" name="archivgage" value="1" />
								<span class="sgk-toggle-label"><?php esc_html_e( 'Archivnutzung', 'sprecher-gagenrechner' ); ?></span>
							</label>
							<label class="sgk-toggle-item">
								<input type="checkbox" name="reminder" value="1" />
								<span class="sgk-toggle-label"><?php esc_html_e( 'Reminder', 'sprecher-gagenrechner' ); ?></span>
							</label>
							<label class="sgk-toggle-item">
								<input type="checkbox" name="allongen" value="1" />
								<span class="sgk-toggle-label"><?php esc_html_e( 'Allongen', 'sprecher-gagenrechner' ); ?></span>
							</label>
							<label class="sgk-toggle-item">
								<input type="checkbox" name="follow_up_usage" value="1" />
								<span class="sgk-toggle-label"><?php esc_html_e( 'Nachnutzung', 'sprecher-gagenrechner' ); ?></span>
							</label>
							<label class="sgk-toggle-item">
								<input type="checkbox" name="is_nachgage" value="1" />
								<span class="sgk-toggle-label"><?php esc_html_e( 'Nachbuchung / Lizenzverlängerung', 'sprecher-gagenrechner' ); ?></span>
							</label>
						</div>
					</div>

					<!-- Sub-section: Prior Layout Fee -->
					<div class="sgk-extension-group" data-sgk-block="prior_layout_fee" hidden>
						<h3 class="sgk-extension-title"><?php esc_html_e( 'Layout-Gebühr', 'sprecher-gagenrechner' ); ?></h3>
						<div class="sgk-field-group">
							<label class="sgk-field-label"><?php esc_html_e( 'Vorheriges Layout-Honorar', 'sprecher-gagenrechner' ); ?></label>
							<div class="sgk-stepper-container">
								<button type="button" class="sgk-stepper-btn sgk-stepper-minus" data-sgk-stepper="prior_layout_fee" data-sgk-stepper-direction="down" aria-label="<?php esc_attr_e( 'Wert verringern', 'sprecher-gagenrechner' ); ?>">−</button>
								<input type="number" name="prior_layout_fee" class="sgk-stepper-input" data-sgk-stepper-input="prior_layout_fee" min="0" step="0.01" />
								<button type="button" class="sgk-stepper-btn sgk-stepper-plus" data-sgk-stepper="prior_layout_fee" data-sgk-stepper-direction="up" aria-label="<?php esc_attr_e( 'Wert erhöhen', 'sprecher-gagenrechner' ); ?>">+</button>
							</div>
						</div>
					</div>

					<!-- Sub-section: Advanced Options (Unlimited) -->
					<details class="sgk-advanced-options">
						<summary class="sgk-advanced-title"><?php esc_html_e( 'Erweiterte Optionen', 'sprecher-gagenrechner' ); ?></summary>
						<div class="sgk-extension-group" data-sgk-block="unlimited_usage" hidden>
							<div class="sgk-toggles-container" data-sgk-unlimited-toggles>
								<label class="sgk-toggle-item">
									<input type="checkbox" name="unlimited_time" value="1" />
									<span class="sgk-toggle-label"><?php esc_html_e( 'Zeitlich unbegrenzt', 'sprecher-gagenrechner' ); ?></span>
								</label>
								<label class="sgk-toggle-item">
									<input type="checkbox" name="unlimited_territory" value="1" />
									<span class="sgk-toggle-label"><?php esc_html_e( 'Räumlich unbegrenzt', 'sprecher-gagenrechner' ); ?></span>
								</label>
								<label class="sgk-toggle-item">
									<input type="checkbox" name="unlimited_media" value="1" />
									<span class="sgk-toggle-label"><?php esc_html_e( 'Medial unbegrenzt', 'sprecher-gagenrechner' ); ?></span>
								</label>
							</div>
						</div>
					</details>
				</section>
			</form>
		</aside>

		<!-- RIGHT COLUMN: Result Panel (sticky) -->
		<aside class="sgk-result-column">
			<div class="sgk-result-panel" data-sgk-result>
				<div class="sgk-default-state">
					<div class="sgk-default-price-label"><?php esc_html_e( 'Empfohlene Gage (Netto)', 'sprecher-gagenrechner' ); ?></div>
					<div class="sgk-default-price-value">0,00 €</div>
					<div class="sgk-default-hint"><?php esc_html_e( 'Bitte Projekt wählen..', 'sprecher-gagenrechner' ); ?><span><?php esc_html_e( 'Alle Preise zzgl. MwSt.', 'sprecher-gagenrechner' ); ?></span></div>
				</div>
			</div>

			<!-- Progress Indicator (Dots) -->
			<div class="sgk-progress-indicator">
				<div class="sgk-progress-dot" data-sgk-progress="1"></div>
				<div class="sgk-progress-dot" data-sgk-progress="2"></div>
				<div class="sgk-progress-dot" data-sgk-progress="3"></div>
				<div class="sgk-progress-dot" data-sgk-progress="4"></div>
			</div>
		</aside>
	</div>
</div>
