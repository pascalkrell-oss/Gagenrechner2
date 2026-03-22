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
<div class="sgk-app" data-sgk-app>
	<div class="sgk-app__grid">
		<section class="sgk-panel sgk-panel--form" aria-labelledby="sgk-config-title">
			<header class="sgk-panel__header">
				<p class="sgk-eyebrow"><?php esc_html_e( 'Phase 2 Engine', 'sprecher-gagenrechner' ); ?></p>
				<h2 id="sgk-config-title"><?php esc_html_e( 'Regelbasierte Nutzungskonfiguration', 'sprecher-gagenrechner' ); ?></h2>
				<p><?php esc_html_e( 'Das Formular bleibt bewusst technisch, stellt aber nun echte Resolver-, Staffelungs-, Addon- und Paketlogik bereit.', 'sprecher-gagenrechner' ); ?></p>
			</header>

			<form class="sgk-form" data-sgk-form>
				<div class="sgk-field">
					<label for="sgk-case-key"><?php esc_html_e( 'Ausgangsfall', 'sprecher-gagenrechner' ); ?></label>
					<select id="sgk-case-key" name="case_key">
						<option value=""><?php esc_html_e( 'Bitte fachlichen Fall wählen', 'sprecher-gagenrechner' ); ?></option>
						<?php foreach ( $cases as $case_key => $case ) : ?>
							<option value="<?php echo esc_attr( $case_key ); ?>"><?php echo esc_html( $case['label'] ); ?></option>
						<?php endforeach; ?>
						<option value="online_audio_spot_unpaid">Online Audio Spot unpaid</option>
						<option value="online_video_spot_unpaid">Online Video Spot unpaid</option>
						<option value="in_app_ads">In-App Ads</option>
						<option value="telefon_werbespot">Telefon-Werbespot</option>
						<option value="marketing_elearning">Marketing E-Learning</option>
						<option value="oeffentliches_elearning">Öffentliches E-Learning</option>
						<option value="video_podcast">Video-Podcast</option>
						<option value="podcast_sponsoring_audio">Podcast Sponsoring Audio</option>
						<option value="podcast_sponsoring_video">Podcast Sponsoring Video</option>
						<option value="werbliche_podcast_verpackung_audio">Werbliche Podcast-Verpackung Audio</option>
						<option value="werbliche_podcast_verpackung_video">Werbliche Podcast-Verpackung Video</option>
						<option value="lokaler_funkspot">Lokaler Funkspot</option>
						<option value="werbliche_games_zusatznutzung">Werbliche Games-Zusatznutzung</option>
					</select>
				</div>

				<div class="sgk-field-grid sgk-field-grid--three">
					<div class="sgk-field"><label for="sgk-variant">Variante</label><input id="sgk-variant" name="case_variant" type="text" placeholder="z. B. linear_tv_spot" /></div>
					<div class="sgk-field"><label for="sgk-duration">Dauer / Minuten</label><input id="sgk-duration" name="duration_minutes" type="number" min="0" step="0.1" /></div>
					<div class="sgk-field"><label for="sgk-net-minutes">Netto-Sendeminuten</label><input id="sgk-net-minutes" name="net_minutes" type="number" min="0" step="0.1" /></div>
				</div>

				<div class="sgk-field-grid sgk-field-grid--three">
					<div class="sgk-field"><label for="sgk-modules">Module</label><input id="sgk-modules" name="module_count" type="number" min="0" step="1" /></div>
					<div class="sgk-field"><label for="sgk-fah">FAH</label><input id="sgk-fah" name="fah" type="number" min="0" step="0.5" /></div>
					<div class="sgk-field"><label for="sgk-hours">Recording Hours</label><input id="sgk-hours" name="recording_hours" type="number" min="0" step="0.5" /></div>
				</div>

				<div class="sgk-field-grid sgk-field-grid--three">
					<div class="sgk-field"><label for="sgk-days">Recording Days</label><input id="sgk-days" name="recording_days" type="number" min="1" step="1" value="1" /></div>
					<div class="sgk-field"><label for="sgk-projects">Projekte am selben Tag</label><input id="sgk-projects" name="same_day_projects" type="number" min="1" step="1" value="1" /></div>
					<div class="sgk-field"><label for="sgk-usage-type">Nutzungsart</label><select id="sgk-usage-type" name="usage_type"><option value="organic_branding">Organic Branding</option><option value="paid_advertising">Paid Advertising</option></select></div>
				</div>

				<div class="sgk-field-grid sgk-field-grid--three">
					<div class="sgk-field"><label for="sgk-add-year">Zusatzjahre</label><input id="sgk-add-year" name="additional_year" type="number" min="0" step="1" value="0" /></div>
					<div class="sgk-field"><label for="sgk-add-territory">Zusatzterritorien</label><input id="sgk-add-territory" name="additional_territory" type="number" min="0" step="1" value="0" /></div>
					<div class="sgk-field"><label for="sgk-add-motif">Zusatzmotive</label><input id="sgk-add-motif" name="additional_motif" type="number" min="0" step="1" value="0" /></div>
				</div>

				<div class="sgk-check-grid">
					<label><input type="checkbox" name="is_paid_media" value="1" /> Paid Media aktiv</label>
					<label><input type="checkbox" name="archivgage" value="1" /> Archivgage</label>
					<label><input type="checkbox" name="reminder" value="1" /> Reminder</label>
					<label><input type="checkbox" name="allongen" value="1" /> Allongen</label>
					<label><input type="checkbox" name="usage_social_media" value="1" /> Social Media</label>
					<label><input type="checkbox" name="usage_praesentation" value="1" /> Präsentation</label>
					<label><input type="checkbox" name="usage_awardfilm" value="1" /> Awardfilm</label>
					<label><input type="checkbox" name="usage_mitarbeiterfilm" value="1" /> Mitarbeiterfilm</label>
					<label><input type="checkbox" name="unlimited_time" value="1" /> Zeitlich unbegrenzt</label>
					<label><input type="checkbox" name="unlimited_territory" value="1" /> Räumlich unbegrenzt</label>
					<label><input type="checkbox" name="unlimited_media" value="1" /> Medial unbegrenzt</label>
					<label><input type="checkbox" name="follow_up_usage" value="1" /> Nachgage / Folgeauswertung</label>
				</div>

				<div class="sgk-field-grid sgk-field-grid--two">
					<div class="sgk-field"><label for="sgk-prior-layout">Vorheriges Layout-Honorar</label><input id="sgk-prior-layout" name="prior_layout_fee" type="number" min="0" step="0.01" value="0" /></div>
					<div class="sgk-field"><label for="sgk-session-hours">Session Fee Stunden</label><input id="sgk-session-hours" name="session_hours" type="number" min="0" step="0.5" value="0" /></div>
				</div>

				<details class="sgk-expert-shell">
					<summary><?php esc_html_e( 'Expertenmodus / Demo-Fälle', 'sprecher-gagenrechner' ); ?></summary>
					<div class="sgk-expert-flags" data-sgk-expert-state><?php echo esc_html( wp_json_encode( $ui_state['expert_flags'] ) ); ?></div>
					<?php if ( ! empty( $demo_cases ) ) : ?>
						<ul class="sgk-demo-list">
							<?php foreach ( $demo_cases as $demo ) : ?>
								<li><button type="button" class="sgk-demo-button" data-sgk-demo='<?php echo esc_attr( wp_json_encode( $demo['input'] ) ); ?>'><?php echo esc_html( $demo['label'] ); ?></button></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</details>

				<div class="sgk-actions">
					<button type="submit" class="sgk-button">Regelbasiert berechnen</button>
				</div>
			</form>
		</section>

		<aside class="sgk-panel sgk-panel--result" aria-labelledby="sgk-result-title">
			<header class="sgk-panel__header">
				<p class="sgk-eyebrow"><?php esc_html_e( 'Breakdown & Export', 'sprecher-gagenrechner' ); ?></p>
				<h2 id="sgk-result-title"><?php esc_html_e( 'Spanne, Paket-Alternativen, Credits, Route Trace', 'sprecher-gagenrechner' ); ?></h2>
			</header>
			<div class="sgk-result" data-sgk-result>
				<p class="sgk-result__placeholder"><?php esc_html_e( 'Nach der Berechnung erscheinen hier strukturierte Line Items, Addons, Mindestgagen, Alternativen und Exportdaten.', 'sprecher-gagenrechner' ); ?></p>
			</div>
		</aside>
	</div>
</div>
