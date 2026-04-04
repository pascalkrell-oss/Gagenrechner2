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
?>
<div class="sgk-app" data-sgk-app data-sgk-cases="<?php echo esc_attr( wp_json_encode( $cases ) ); ?>" data-sgk-ui-state="<?php echo esc_attr( wp_json_encode( $ui_state ) ); ?>">
	<header class="sgk-top-toolbar" aria-label="<?php esc_attr_e( 'Werkzeugleiste', 'sprecher-gagenrechner' ); ?>">
		<div class="sgk-toolbar-brand">
			<strong><?php esc_html_e( 'Sprecher Gagenrechner', 'sprecher-gagenrechner' ); ?></strong>
			<span><?php esc_html_e( 'Premium Pricing Control für Voiceover-Angebote', 'sprecher-gagenrechner' ); ?></span>
		</div>
		<div class="sgk-toolbar-right">
			<button type="button" class="sgk-toolbar-btn" data-sgk-action="save"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span><?php esc_html_e( 'Speichern', 'sprecher-gagenrechner' ); ?></span></button>
			<button type="button" class="sgk-toolbar-btn" data-sgk-action="load"><i class="fa-solid fa-folder-open" aria-hidden="true"></i><span><?php esc_html_e( 'Laden', 'sprecher-gagenrechner' ); ?></span></button>
			<button type="button" class="sgk-toolbar-btn" data-sgk-guide><i class="fa-solid fa-book-open" aria-hidden="true"></i><span><?php esc_html_e( 'Anleitung', 'sprecher-gagenrechner' ); ?></span></button>
			<button type="button" class="sgk-toolbar-btn sgk-toolbar-btn--secondary" data-sgk-reset-calculator><i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i><span><?php esc_html_e( 'Reset', 'sprecher-gagenrechner' ); ?></span></button>
		</div>
	</header>

	<div class="sgk-main-container">
		<aside class="sgk-setup-column">
			<form class="sgk-form" data-sgk-form>
				<input type="hidden" name="case_key" data-sgk-primary-field value="" />
				<input type="hidden" name="case_variant" value="" />
				<input type="hidden" name="usage_type" value="organic_branding" />
				<input type="hidden" name="territory" value="" />
				<input type="hidden" name="duration_term" value="" />
				<input type="hidden" name="medium" value="" />
				<input type="hidden" name="package_key" value="" />
				<input type="hidden" name="manual_offer_total" value="" />
				<input type="hidden" name="project_title" value="" />
				<input type="hidden" name="customer_name" value="" />
				<input type="hidden" name="internal_notes" value="" />
				<input type="hidden" name="needs_cutdown" value="0" />
				<input type="hidden" name="layout_fee" value="0" />

				<section class="sgk-step-section" data-sgk-step="1">
					<div class="sgk-step-header"><h2 class="sgk-step-title"><?php esc_html_e( '1. Projektart', 'sprecher-gagenrechner' ); ?></h2><p class="sgk-step-subtitle"><?php esc_html_e( 'Wähle die passende Projektkategorie', 'sprecher-gagenrechner' ); ?></p></div>
					<div class="sgk-project-grid" data-sgk-project-grid>
						<button type="button" class="sgk-project-card" data-sgk-case="werbung_mit_bild"><span class="sgk-card-icon"><i class="fa-solid fa-photo-film" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Werbung mit Bild', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'TV, Online Video, Kino', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="werbung_ohne_bild"><span class="sgk-card-icon"><i class="fa-solid fa-volume-high" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Werbung ohne Bild', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Radio, Audio Ads', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="webvideo_imagefilm_praesentation_unpaid"><span class="sgk-card-icon"><i class="fa-solid fa-clapperboard" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Imagefilm / Webvideo', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Unpaid & Branding', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="telefonansage"><span class="sgk-card-icon"><i class="fa-solid fa-phone-volume" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Telefonansage', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'IVR, Hotline, Warteschleife', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="elearning_audioguide"><span class="sgk-card-icon"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'E-Learning', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Lernmodule & Audioguide', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="podcast"><span class="sgk-card-icon"><i class="fa-solid fa-podcast" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Podcast', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Inhalt, Intro, Serienlizenz', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="hoerbuch"><span class="sgk-card-icon"><i class="fa-solid fa-book-open-reader" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Hörbuch', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'FAH-basierte Kalkulation', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="games"><span class="sgk-card-icon"><i class="fa-solid fa-gamepad" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Games', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Session- und Studiotage', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="redaktionell_doku_tv_reportage"><span class="sgk-card-icon"><i class="fa-solid fa-newspaper" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Doku / Redaktion', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'TV, Kommentar, Overvoice', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="audiodeskription"><span class="sgk-card-icon"><i class="fa-solid fa-audio-description" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Audiodeskription', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Barrierefreie Auswertung', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="kleinraeumig"><span class="sgk-card-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Kleinräumige Nutzung', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Lokale bis regionale Einsätze', 'sprecher-gagenrechner' ); ?></small></span></button>
						<button type="button" class="sgk-project-card" data-sgk-case="session_fee"><span class="sgk-card-icon"><i class="fa-solid fa-stopwatch" aria-hidden="true"></i></span><span class="sgk-card-text"><strong><?php esc_html_e( 'Session Fee', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Reine Studiozeit', 'sprecher-gagenrechner' ); ?></small></span></button>
					</div>
				</section>

				<section class="sgk-step-section" data-sgk-step="2" data-sgk-dependent-step hidden>
					<div class="sgk-step-header"><h2 class="sgk-step-title"><?php esc_html_e( '2. Variante', 'sprecher-gagenrechner' ); ?></h2></div>
					<div class="sgk-field-group" data-sgk-block="variant">
						<div class="sgk-variant-panel">
							<div class="sgk-variant-panel-head">
								<label class="sgk-field-label"><?php esc_html_e( 'Verfügbare Varianten', 'sprecher-gagenrechner' ); ?></label>
								<p class="sgk-variant-intro"><?php esc_html_e( 'Wähle die passende Ausspiel- oder Projektvariante für Dein Vorhaben.', 'sprecher-gagenrechner' ); ?></p>
							</div>
							<div class="sgk-variant-panel-body">
								<div class="sgk-variants-control" data-sgk-variant-pills data-sgk-variant-control></div>
								<p class="sgk-variant-help" data-sgk-variant-help data-sgk-variant-hint></p>
							</div>
						</div>
					</div>
				</section>

				<section class="sgk-step-section" data-sgk-step="3" data-sgk-step-shell="usage" data-sgk-dependent-step hidden>
					<div class="sgk-step-header"><h2 class="sgk-step-title"><?php esc_html_e( '3–9. Nutzung, Rechte & Umfang', 'sprecher-gagenrechner' ); ?></h2><p class="sgk-step-subtitle"><?php esc_html_e( 'Segmentierte Rechteauswahl, Counter und Zusatzoptionen', 'sprecher-gagenrechner' ); ?></p></div>
					<div class="sgk-field-group" data-sgk-block="usage_type" hidden><label class="sgk-field-label"><?php esc_html_e( 'Nutzungsart', 'sprecher-gagenrechner' ); ?></label><div class="sgk-usage-pills" data-sgk-usage-pills data-sgk-usage-type-control></div></div>
					<div class="sgk-field-group" data-sgk-block="duration_minutes" hidden><label class="sgk-field-label"><?php esc_html_e( 'Minuten', 'sprecher-gagenrechner' ); ?></label><div class="sgk-stepper-container" data-sgk-stepper="duration_minutes"><button type="button" class="sgk-stepper-btn" data-sgk-stepper-direction="down">−</button><input type="number" name="duration_minutes" min="1" max="120" step="0.5" data-sgk-stepper-input="duration_minutes" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper-direction="up">+</button></div></div>
					<div class="sgk-field-group" data-sgk-block="net_minutes" hidden><label class="sgk-field-label"><?php esc_html_e( 'Sendeminuten (netto)', 'sprecher-gagenrechner' ); ?></label><div class="sgk-stepper-container"><button type="button" class="sgk-stepper-btn" data-sgk-stepper="net_minutes" data-sgk-stepper-direction="down">−</button><input type="number" name="net_minutes" data-sgk-stepper-input="net_minutes" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper="net_minutes" data-sgk-stepper-direction="up">+</button></div></div>
					<div class="sgk-field-group" data-sgk-block="module_count" hidden><label class="sgk-field-label"><?php esc_html_e( 'Module', 'sprecher-gagenrechner' ); ?></label><div class="sgk-stepper-container"><button type="button" class="sgk-stepper-btn" data-sgk-stepper="module_count" data-sgk-stepper-direction="down">−</button><input type="number" name="module_count" data-sgk-stepper-input="module_count" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper="module_count" data-sgk-stepper-direction="up">+</button></div></div>
					<div class="sgk-field-group" data-sgk-block="fah" hidden><label class="sgk-field-label"><?php esc_html_e( 'Hörbuchstunden', 'sprecher-gagenrechner' ); ?></label><div class="sgk-stepper-container"><button type="button" class="sgk-stepper-btn" data-sgk-stepper="fah" data-sgk-stepper-direction="down">−</button><input type="number" name="fah" data-sgk-stepper-input="fah" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper="fah" data-sgk-stepper-direction="up">+</button></div></div>
					<div class="sgk-field-group" data-sgk-block="recording_hours" hidden><label class="sgk-field-label"><?php esc_html_e( 'Studiostunden', 'sprecher-gagenrechner' ); ?></label><div class="sgk-stepper-container"><button type="button" class="sgk-stepper-btn" data-sgk-stepper="recording_hours" data-sgk-stepper-direction="down">−</button><input type="number" name="recording_hours" data-sgk-stepper-input="recording_hours" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper="recording_hours" data-sgk-stepper-direction="up">+</button></div></div>
					<div class="sgk-field-group" data-sgk-block="recording_days" hidden><label class="sgk-field-label"><?php esc_html_e( 'Tage', 'sprecher-gagenrechner' ); ?></label><div class="sgk-stepper-container"><button type="button" class="sgk-stepper-btn" data-sgk-stepper="recording_days" data-sgk-stepper-direction="down">−</button><input type="number" name="recording_days" data-sgk-stepper-input="recording_days" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper="recording_days" data-sgk-stepper-direction="up">+</button></div></div>
					<div class="sgk-field-group" data-sgk-block="same_day_projects" hidden><label class="sgk-field-label"><?php esc_html_e( 'Weitere Projekte am selben Tag', 'sprecher-gagenrechner' ); ?></label><div class="sgk-stepper-container"><button type="button" class="sgk-stepper-btn" data-sgk-stepper="same_day_projects" data-sgk-stepper-direction="down">−</button><input type="number" name="same_day_projects" data-sgk-stepper-input="same_day_projects" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper="same_day_projects" data-sgk-stepper-direction="up">+</button></div></div>
					<div class="sgk-field-group" data-sgk-block="session_hours" hidden><label class="sgk-field-label"><?php esc_html_e( 'Session-Stunden', 'sprecher-gagenrechner' ); ?></label><div class="sgk-stepper-container"><button type="button" class="sgk-stepper-btn" data-sgk-stepper="session_hours" data-sgk-stepper-direction="down">−</button><input type="number" name="session_hours" data-sgk-stepper-input="session_hours" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper="session_hours" data-sgk-stepper-direction="up">+</button></div></div>
					<div class="sgk-field-group" data-sgk-rights-intro><p class="sgk-scope-copy"></p></div>
				</section>

				<section class="sgk-step-section" data-sgk-step="4" data-sgk-step-shell="rights" data-sgk-dependent-step hidden>
					<div class="sgk-step-header"><h2 class="sgk-step-title"><?php esc_html_e( '10–17. Rechte, Summary, Hinweise', 'sprecher-gagenrechner' ); ?></h2></div>
					<div class="sgk-rights-core" data-sgk-rights-core>
						<div class="sgk-field-group" data-sgk-block="territory" hidden><label class="sgk-field-label"><?php esc_html_e( 'Gebiet', 'sprecher-gagenrechner' ); ?></label><div data-sgk-territory-pills></div></div>
						<div class="sgk-field-group" data-sgk-block="duration_term" hidden><label class="sgk-field-label"><?php esc_html_e( 'Laufzeit', 'sprecher-gagenrechner' ); ?></label><div data-sgk-duration-pills></div></div>
						<div class="sgk-field-group" data-sgk-block="medium" hidden><label class="sgk-field-label"><?php esc_html_e( 'Medien', 'sprecher-gagenrechner' ); ?></label><div data-sgk-medium-pills></div></div>
					</div>
					<div class="sgk-field-group" data-sgk-block="addon_counts" hidden>
						<label class="sgk-field-label"><?php esc_html_e( '6. Add-ons', 'sprecher-gagenrechner' ); ?></label>
						<div class="sgk-addon-grid">
							<div class="sgk-stepper-container" data-sgk-stepper="additional_year"><span class="sgk-stepper-caption"><?php esc_html_e( 'Zusätzliche Jahre', 'sprecher-gagenrechner' ); ?></span><button type="button" class="sgk-stepper-btn" data-sgk-stepper-direction="down">−</button><input type="number" name="additional_year" data-sgk-stepper-input="additional_year" min="0" step="1" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper-direction="up">+</button></div>
							<div class="sgk-stepper-container" data-sgk-stepper="additional_territory"><span class="sgk-stepper-caption"><?php esc_html_e( 'Zusätzliche Regionen', 'sprecher-gagenrechner' ); ?></span><button type="button" class="sgk-stepper-btn" data-sgk-stepper-direction="down">−</button><input type="number" name="additional_territory" data-sgk-stepper-input="additional_territory" min="0" step="1" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper-direction="up">+</button></div>
							<div class="sgk-stepper-container" data-sgk-stepper="additional_motif"><span class="sgk-stepper-caption"><?php esc_html_e( 'Zusätzliche Motive', 'sprecher-gagenrechner' ); ?></span><button type="button" class="sgk-stepper-btn" data-sgk-stepper-direction="down">−</button><input type="number" name="additional_motif" data-sgk-stepper-input="additional_motif" min="0" step="1" /><button type="button" class="sgk-stepper-btn" data-sgk-stepper-direction="up">+</button></div>
						</div>
					</div>
					<div class="sgk-field-group" data-sgk-block="rights_toggles" hidden data-sgk-rights-toggles>
						<label class="sgk-toggle-item"><input type="checkbox" name="archivgage" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Archivgage', 'sprecher-gagenrechner' ); ?></span></label>
						<label class="sgk-toggle-item"><input type="checkbox" name="reminder" value="1" /><span class="sgk-toggle-label">Reminder</span></label>
						<label class="sgk-toggle-item"><input type="checkbox" name="allongen" value="1" /><span class="sgk-toggle-label">Allongen</span></label>
						<label class="sgk-toggle-item"><input type="checkbox" name="follow_up_usage" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Nachnutzung', 'sprecher-gagenrechner' ); ?></span></label>
						<label class="sgk-toggle-item"><input type="checkbox" name="is_nachgage" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Nachgage', 'sprecher-gagenrechner' ); ?></span></label>
					</div>
					<div class="sgk-field-group" data-sgk-block="media_toggles" hidden>
						<label class="sgk-toggle-item"><input type="checkbox" name="is_paid_media" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Paid Advertising', 'sprecher-gagenrechner' ); ?></span></label>
						<label class="sgk-toggle-item"><input type="checkbox" name="usage_social_media" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Social Media', 'sprecher-gagenrechner' ); ?></span></label>
						<label class="sgk-toggle-item"><input type="checkbox" name="usage_praesentation" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Präsentation', 'sprecher-gagenrechner' ); ?></span></label>
					</div>
					<div class="sgk-field-group" data-sgk-block="prior_layout_fee" data-sgk-conditional-field="prior_layout_fee" hidden><input type="number" name="prior_layout_fee" placeholder="Layouthonorar" /></div>
					<div class="sgk-field-group sgk-buyout-box" data-sgk-block="unlimited_usage" hidden data-sgk-unlimited-toggles>
						<label class="sgk-toggle-item"><input type="checkbox" name="unlimited_time" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Zeitlich unbegrenzt', 'sprecher-gagenrechner' ); ?></span></label>
						<label class="sgk-toggle-item"><input type="checkbox" name="unlimited_territory" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Weltweit', 'sprecher-gagenrechner' ); ?></span></label>
						<label class="sgk-toggle-item"><input type="checkbox" name="unlimited_media" value="1" /><span class="sgk-toggle-label"><?php esc_html_e( 'Alle Medien', 'sprecher-gagenrechner' ); ?></span></label>
					</div>
				</section>

				<div class="sgk-case-context" data-sgk-case-context hidden></div>
				<section class="sgk-guidance-panel" data-sgk-expert-shell>
					<div class="sgk-step-header"><h2 class="sgk-step-title"><?php esc_html_e( 'Ergebnisübersicht', 'sprecher-gagenrechner' ); ?></h2></div>
					<div data-sgk-rights-summary></div><div data-sgk-journey-summary></div><div class="sgk-expert-badges" data-sgk-expert-badges></div><div data-sgk-scope-copy class="sgk-scope-copy"></div>
				</section>
				<div class="sgk-actions-bar"><p class="sgk-validation-status" data-sgk-validation-status></p><button type="submit" class="sgk-btn-submit" data-sgk-submit disabled><?php esc_html_e( 'Angebot erstellen', 'sprecher-gagenrechner' ); ?></button></div>
			</form>
		</aside>

		<aside class="sgk-result-column">
			<div class="sgk-result-panel" data-sgk-result>
				<div class="src-redirect-banner" data-sgk-redirect-banner hidden></div>
				<div class="sgk-default-tower">
					<div class="sgk-default-state"><div class="sgk-default-price-label"><?php esc_html_e( 'LIVE Rechnung', 'sprecher-gagenrechner' ); ?></div><div class="sgk-default-price-value">0,00 €</div><div class="sgk-default-hint"><?php esc_html_e( 'Wähle links Dein Projekt – rechts erscheint sofort Dein Control Tower.', 'sprecher-gagenrechner' ); ?><span><?php esc_html_e( 'Alle Preise zzgl. MwSt.', 'sprecher-gagenrechner' ); ?></span></div></div>
					<div class="sgk-default-card">
						<span><?php esc_html_e( 'Finales Angebot', 'sprecher-gagenrechner' ); ?></span>
						<strong><?php esc_html_e( 'Noch nicht gesetzt', 'sprecher-gagenrechner' ); ?></strong>
					</div>
					<div class="sgk-default-card">
						<span><?php esc_html_e( 'Summary', 'sprecher-gagenrechner' ); ?></span>
						<strong><?php esc_html_e( 'Projekt, Rechte und Umfang folgen nach Auswahl.', 'sprecher-gagenrechner' ); ?></strong>
					</div>
					<div class="sgk-default-card sgk-default-card--actions">
						<span><?php esc_html_e( 'Actions', 'sprecher-gagenrechner' ); ?></span>
						<strong><?php esc_html_e( 'Export-, Copy- und Speicherfunktionen werden hier aktiviert.', 'sprecher-gagenrechner' ); ?></strong>
					</div>
				</div>
			</div>
			<select data-sgk-saved-list hidden><option value=""></option></select><div data-sgk-storage-status hidden></div>
		</aside>
	</div>

	<div class="sgk-bottom-progress" data-sgk-bottom-progress aria-live="polite">
		<div class="sgk-bottom-progress-track">
			<div class="sgk-bottom-progress-bar"><span data-sgk-progress-fill></span></div>
			<div class="sgk-bottom-progress-steps">
				<div class="sgk-bottom-progress-step" data-sgk-progress-step="1"><span class="sgk-bottom-progress-label">Projekt</span></div>
				<div class="sgk-bottom-progress-step" data-sgk-progress-step="2"><span class="sgk-bottom-progress-label">Details</span></div>
				<div class="sgk-bottom-progress-step" data-sgk-progress-step="3"><span class="sgk-bottom-progress-label">Preis</span></div>
				<div class="sgk-bottom-progress-step" data-sgk-progress-step="4"><span class="sgk-bottom-progress-label">Angebot</span></div>
			</div>
		</div>
	</div>

	<div class="src-modal-backdrop" data-sgk-offer-modal hidden aria-hidden="true">
		<div class="src-modal-content" role="dialog" aria-modal="true" aria-labelledby="sgk-offer-modal-title" tabindex="-1">
			<div class="src-modal-header"><h3 id="sgk-offer-modal-title"><?php esc_html_e( 'Angebot konfigurieren', 'sprecher-gagenrechner' ); ?></h3><button type="button" class="src-btn-secondary" data-sgk-offer-close><?php esc_html_e( 'Schließen', 'sprecher-gagenrechner' ); ?></button></div>
			<div class="src-modal-body src-modal-grid">
				<section class="src-modal-panel">
					<label class="sgk-field-label"><?php esc_html_e( 'Angebotsnummer', 'sprecher-gagenrechner' ); ?></label><input type="text" class="src-input-text" data-sgk-offer-meta="offer_number" placeholder="ANG-2026-001" />
					<label class="sgk-field-label"><?php esc_html_e( 'Datum', 'sprecher-gagenrechner' ); ?></label><input type="date" class="src-input-text" data-sgk-offer-meta="offer_date" />
					<label class="sgk-field-label"><?php esc_html_e( 'Ansprechpartner', 'sprecher-gagenrechner' ); ?></label><input type="text" class="src-input-text" data-sgk-offer-meta="contact_name" placeholder="Ansprechpartner" />
					<label class="sgk-field-label"><?php esc_html_e( 'Absenderfirma', 'sprecher-gagenrechner' ); ?></label><input type="text" class="src-input-text" data-sgk-offer-meta="sender_company" placeholder="Firma" />
					<label class="sgk-field-label"><?php esc_html_e( 'E-Mail', 'sprecher-gagenrechner' ); ?></label><input type="email" class="src-input-text" data-sgk-offer-meta="sender_email" placeholder="E-Mail" />
					<label class="sgk-field-label"><?php esc_html_e( 'Telefon', 'sprecher-gagenrechner' ); ?></label><input type="text" class="src-input-text" data-sgk-offer-meta="sender_phone" placeholder="Telefon" />
					<label class="sgk-field-label"><?php esc_html_e( 'Einleitung', 'sprecher-gagenrechner' ); ?></label><textarea class="src-input-text" data-sgk-offer-meta="intro_text" placeholder="Einleitung"></textarea>
					<label class="sgk-field-label"><?php esc_html_e( 'Footer', 'sprecher-gagenrechner' ); ?></label><textarea class="src-input-text" data-sgk-offer-meta="footer_text" placeholder="Footer"></textarea>
					<label class="sgk-field-label"><?php esc_html_e( 'Interne Notiz', 'sprecher-gagenrechner' ); ?></label><textarea class="src-input-text" data-sgk-offer-meta="internal_note" placeholder="Interne Notiz"></textarea>
					<p class="src-field-hint" data-sgk-offer-status></p>
				</section>
				<section class="src-modal-panel"><div class="src-offer-preview-shell" data-sgk-offer-preview></div></section>
			</div>
			<div class="src-modal-footer"><button type="button" class="src-btn-secondary" data-sgk-offer-action="copy-mail"><?php esc_html_e( 'Kopieren', 'sprecher-gagenrechner' ); ?></button><button type="button" class="src-btn-primary" data-sgk-offer-action="print"><?php esc_html_e( 'PDF Export', 'sprecher-gagenrechner' ); ?></button></div>
		</div>
	</div>
</div>
