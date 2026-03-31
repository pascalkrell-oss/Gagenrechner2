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
	</div>

	<!-- Main Two-Column Layout -->
	<div class="sgk-main-container">

		<!-- LEFT COLUMN: Setup Wizard -->
		<aside class="sgk-setup-column">
			<form class="sgk-form" data-sgk-form>
				<!-- Hidden Fields -->
				<input type="hidden" name="case_key" data-sgk-primary-field value="" />
				<input type="hidden" name="manual_offer_total" value="" />

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
				<!-- Default Message -->
				<div class="sgk-result-empty">
					<div class="sgk-empty-icon"><i class="fa-solid fa-calculator" aria-hidden="true"></i></div>
					<h3><?php esc_html_e( 'Bereit zum Berechnen?', 'sprecher-gagenrechner' ); ?></h3>
					<p><?php esc_html_e( 'Wähle ein Projekt, um Dein Honorar live zu berechnen.', 'sprecher-gagenrechner' ); ?></p>
				</div>

				<!-- Loading State -->
				<div class="sgk-result-loading" hidden>
					<div class="sgk-spinner"></div>
					<p><?php esc_html_e( 'Berechnung läuft...', 'sprecher-gagenrechner' ); ?></p>
				</div>

				<!-- Result Content -->
				<div class="sgk-result-content" hidden>
					<!-- Price Range -->
					<div class="sgk-result-header">
						<div class="sgk-result-badge" data-sgk-result-badge></div>
						<div class="sgk-price-range">
							<div class="sgk-price-item">
								<span class="sgk-price-label"><?php esc_html_e( 'Von', 'sprecher-gagenrechner' ); ?></span>
								<span class="sgk-price-value" data-sgk-price-lower>— EUR</span>
							</div>
							<div class="sgk-price-sep">–</div>
							<div class="sgk-price-item">
								<span class="sgk-price-label"><?php esc_html_e( 'Bis', 'sprecher-gagenrechner' ); ?></span>
								<span class="sgk-price-value" data-sgk-price-upper>— EUR</span>
							</div>
						</div>
						<div class="sgk-price-average">
							<span class="sgk-avg-label"><?php esc_html_e( 'Empfohlener Richtwert', 'sprecher-gagenrechner' ); ?></span>
							<span class="sgk-avg-value" data-sgk-price-average>— EUR</span>
						</div>
					</div>

					<!-- Breakdown Accordion -->
					<div class="sgk-breakdown">
						<details class="sgk-breakdown-item">
							<summary class="sgk-breakdown-title"><?php esc_html_e( 'Basisgage', 'sprecher-gagenrechner' ); ?></summary>
							<div class="sgk-breakdown-content" data-sgk-breakdown-base></div>
						</details>
						<details class="sgk-breakdown-item">
							<summary class="sgk-breakdown-title"><?php esc_html_e( 'Zuschläge', 'sprecher-gagenrechner' ); ?></summary>
							<div class="sgk-breakdown-content" data-sgk-breakdown-addons></div>
						</details>
						<details class="sgk-breakdown-item">
							<summary class="sgk-breakdown-title"><?php esc_html_e( 'Erweiterungen', 'sprecher-gagenrechner' ); ?></summary>
							<div class="sgk-breakdown-content" data-sgk-breakdown-extensions></div>
						</details>
						<details class="sgk-breakdown-item">
							<summary class="sgk-breakdown-title"><?php esc_html_e( 'Credits', 'sprecher-gagenrechner' ); ?></summary>
							<div class="sgk-breakdown-content" data-sgk-breakdown-credits></div>
						</details>
					</div>

					<!-- Rights Overview -->
					<div class="sgk-rights-overview">
						<h4 class="sgk-overview-title"><?php esc_html_e( 'Deine Rechte', 'sprecher-gagenrechner' ); ?></h4>
						<div class="sgk-rights-badges" data-sgk-rights-badges></div>
					</div>

					<!-- Manual Offer Total -->
					<div class="sgk-manual-offer">
						<label class="sgk-field-label"><?php esc_html_e( 'Angebotssumme anpassen', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-manual-offer-input">
							<input type="number" name="manual_offer_override" class="sgk-input" placeholder="z.B. 5000 EUR" step="0.01" />
							<button type="button" class="sgk-btn sgk-btn-secondary" data-sgk-apply-manual-offer><?php esc_html_e( 'Übernehmen', 'sprecher-gagenrechner' ); ?></button>
						</div>
					</div>

					<!-- Action Buttons -->
					<div class="sgk-result-actions">
						<button type="button" class="sgk-btn sgk-btn-primary" data-sgk-action="save">
							<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
							<span><?php esc_html_e( 'Speichern', 'sprecher-gagenrechner' ); ?></span>
						</button>
						<button type="button" class="sgk-btn sgk-btn-secondary" data-sgk-action="copy">
							<i class="fa-solid fa-copy" aria-hidden="true"></i>
							<span><?php esc_html_e( 'Kopieren', 'sprecher-gagenrechner' ); ?></span>
						</button>
						<button type="button" class="sgk-btn sgk-btn-secondary" data-sgk-action="print">
							<i class="fa-solid fa-print" aria-hidden="true"></i>
							<span><?php esc_html_e( 'Drucken', 'sprecher-gagenrechner' ); ?></span>
						</button>
					</div>
				</div>

				<!-- Error State -->
				<div class="sgk-result-error" hidden>
					<div class="sgk-error-icon"><i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i></div>
					<h3><?php esc_html_e( 'Berechnung nicht möglich', 'sprecher-gagenrechner' ); ?></h3>
					<p><?php esc_html_e( 'Bitte prüfe Deine Angaben und versuche es erneut.', 'sprecher-gagenrechner' ); ?></p>
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
