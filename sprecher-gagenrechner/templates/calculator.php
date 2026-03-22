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
<div class="sgk-app" data-sgk-app data-sgk-cases="<?php echo esc_attr( wp_json_encode( $cases ) ); ?>" data-sgk-ui-state="<?php echo esc_attr( wp_json_encode( $ui_state ) ); ?>">
	<div class="sgk-app__hero">
		<div>
			<p class="sgk-eyebrow"><?php esc_html_e( 'Premium Gagenrechner', 'sprecher-gagenrechner' ); ?></p>
			<h2 class="sgk-app__title"><?php esc_html_e( 'Sprecherhonorare sicher konfigurieren – geführt, nachvollziehbar und angebotsreif.', 'sprecher-gagenrechner' ); ?></h2>
			<p class="sgk-app__intro"><?php esc_html_e( 'Wählen Sie Projekt, Nutzung und Umfang Schritt für Schritt. Die Kalkulationsspanne, Lizenzlogik und fachlichen Hinweise werden rechts fortlaufend eingeordnet.', 'sprecher-gagenrechner' ); ?></p>
		</div>


		<div class="sgk-hero-card">
			<span class="sgk-hero-card__badge"><?php esc_html_e( 'Live', 'sprecher-gagenrechner' ); ?></span>
			<strong><?php esc_html_e( 'Von-Bis-Spanne + Mittelwert', 'sprecher-gagenrechner' ); ?></strong>
			<p><?php esc_html_e( 'Die finale Angebotssumme wird bewusst später manuell gesetzt – auf Basis der hier erklärten Empfehlung.', 'sprecher-gagenrechner' ); ?></p>
		</div>
	</div>

	<div class="sgk-app__grid">
		<section class="sgk-panel sgk-panel--form" aria-labelledby="sgk-config-title">
			<header class="sgk-panel__header">
				<p class="sgk-eyebrow"><?php esc_html_e( 'Konfiguration', 'sprecher-gagenrechner' ); ?></p>
				<h3 id="sgk-config-title"><?php esc_html_e( 'Projekt in fachlich sinnvoller Reihenfolge aufbauen', 'sprecher-gagenrechner' ); ?></h3>
				<p><?php esc_html_e( 'Die Oberfläche blendet nur die Eingaben ein, die für den gewählten Fall wirklich benötigt werden.', 'sprecher-gagenrechner' ); ?></p>
			</header>

			<form class="sgk-form" data-sgk-form>
				<input type="hidden" name="manual_offer_total" value="" />
				<div class="sgk-step-card" data-step="project">
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">1</span>
						<div>
							<h4><?php esc_html_e( 'Format / Projekt', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Starten Sie mit dem fachlich passenden Grundszenario.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-feature-grid">
						<button type="button" class="sgk-quick-case" data-sgk-quick-case="werbung_mit_bild">
							<strong><?php esc_html_e( 'Werbung mit Bild', 'sprecher-gagenrechner' ); ?></strong>
							<span><?php esc_html_e( 'TV, CTV, Online Video, Kino, POS', 'sprecher-gagenrechner' ); ?></span>
						</button>
						<button type="button" class="sgk-quick-case" data-sgk-quick-case="werbung_ohne_bild">
							<strong><?php esc_html_e( 'Werbung ohne Bild', 'sprecher-gagenrechner' ); ?></strong>
							<span><?php esc_html_e( 'Funk, Online Audio, Ladenfunk, Telefon-Werbespot', 'sprecher-gagenrechner' ); ?></span>
						</button>
						<button type="button" class="sgk-quick-case" data-sgk-quick-case="webvideo_imagefilm_praesentation_unpaid">
							<strong><?php esc_html_e( 'Webvideo / Imagefilm', 'sprecher-gagenrechner' ); ?></strong>
							<span><?php esc_html_e( 'Corporate, unpaid, Präsentation, Social Clips', 'sprecher-gagenrechner' ); ?></span>
						</button>
						<button type="button" class="sgk-quick-case" data-sgk-quick-case="telefonansage">
							<strong><?php esc_html_e( 'Telefonansage', 'sprecher-gagenrechner' ); ?></strong>
							<span><?php esc_html_e( 'IVR, Hotline, modulbasierte Systeme', 'sprecher-gagenrechner' ); ?></span>
						</button>
					</div>

					<div class="sgk-field">
						<label for="sgk-case-key"><?php esc_html_e( 'Projektfall', 'sprecher-gagenrechner' ); ?></label>
						<select id="sgk-case-key" name="case_key" data-sgk-primary-field>
							<option value=""><?php esc_html_e( 'Bitte Fall auswählen', 'sprecher-gagenrechner' ); ?></option>
							<optgroup label="Standardfälle">
								<?php foreach ( $cases as $case_key => $case ) : ?>
									<option value="<?php echo esc_attr( $case_key ); ?>"><?php echo esc_html( $case['label'] ); ?></option>
								<?php endforeach; ?>
							</optgroup>
							<optgroup label="Spezielle Einstiegsszenarien">
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
							</optgroup>
						</select>
						<p class="sgk-field__hint"><?php esc_html_e( 'Auch Sonderfälle können direkt gewählt werden. Die Resolver-Logik ordnet sie bei Bedarf automatisch fachlich sauber ein.', 'sprecher-gagenrechner' ); ?></p>
					</div>

					<div class="sgk-context-card" data-sgk-case-context>
						<strong><?php esc_html_e( 'Noch kein Fall ausgewählt', 'sprecher-gagenrechner' ); ?></strong>
						<p><?php esc_html_e( 'Sobald ein Projektfall gewählt ist, zeigt der Rechner die passende Eingabeführung und blendet irrelevante Felder aus.', 'sprecher-gagenrechner' ); ?></p>
					</div>
				</div>

				<div class="sgk-step-card is-disabled" data-step="usage" data-sgk-dependent-step>
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">2</span>
						<div>
							<h4><?php esc_html_e( 'Nutzung / Verwertung / Medium', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Der Rechner zeigt nur passende Medien- und Verwertungsoptionen für den gewählten Fall.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field" data-sgk-block="variant">
							<label for="sgk-variant"><?php esc_html_e( 'Ausprägung / Unterfall', 'sprecher-gagenrechner' ); ?></label>
							<select id="sgk-variant" name="case_variant"></select>
							<p class="sgk-field__hint" data-sgk-variant-hint><?php esc_html_e( 'Die Auswahl passt sich dem Projektfall an.', 'sprecher-gagenrechner' ); ?></p>
						</div>
						<div class="sgk-field" data-sgk-block="usage_type">
							<label for="sgk-usage-type"><?php esc_html_e( 'Nutzungscharakter', 'sprecher-gagenrechner' ); ?></label>
							<select id="sgk-usage-type" name="usage_type">
								<option value="organic_branding"><?php esc_html_e( 'Organic / Branding / nicht Paid', 'sprecher-gagenrechner' ); ?></option>
								<option value="paid_advertising"><?php esc_html_e( 'Paid Advertising / klassische Werbung', 'sprecher-gagenrechner' ); ?></option>
							</select>
							<p class="sgk-field__hint"><?php esc_html_e( 'Wichtig für Redirects, z. B. bei Podcast- oder Video-Szenarien.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-toggle-grid" data-sgk-block="media_toggles">
						<label class="sgk-toggle"><input type="checkbox" name="is_paid_media" value="1" /><span><?php esc_html_e( 'Paid Media aktiv', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Aktiviert Werbelogik, falls fachlich erforderlich.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="usage_social_media" value="1" /><span><?php esc_html_e( 'Social Media', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Zusatzlizenz für passende unpaid Bildfälle.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="usage_praesentation" value="1" /><span><?php esc_html_e( 'Präsentation', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für interne oder vertriebsnahe Nutzung.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="usage_awardfilm" value="1" /><span><?php esc_html_e( 'Awardfilm', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Nur sichtbar, wenn im Fall fachlich möglich.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="usage_mitarbeiterfilm" value="1" /><span><?php esc_html_e( 'Mitarbeiterfilm', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Zusatzlizenz für interne Filmnutzung.', 'sprecher-gagenrechner' ); ?></small></label>
					</div>

					<div class="sgk-info-banner" data-sgk-redirect-banner hidden></div>
				</div>

				<div class="sgk-step-card is-disabled" data-step="scope" data-sgk-dependent-step>
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">3</span>
						<div>
							<h4><?php esc_html_e( 'Umfang', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Dauer, Mengen, Module oder Aufnahmestunden werden passend zum Fall eingeblendet.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--three">
						<div class="sgk-field" data-sgk-block="duration_minutes"><label for="sgk-duration"><?php esc_html_e( 'Dauer in Minuten', 'sprecher-gagenrechner' ); ?></label><input id="sgk-duration" name="duration_minutes" type="number" min="0" step="0.1" /><p class="sgk-field__hint"><?php esc_html_e( 'Für minutenbasierte Lizenzstaffeln.', 'sprecher-gagenrechner' ); ?></p></div>
						<div class="sgk-field" data-sgk-block="net_minutes"><label for="sgk-net-minutes"><?php esc_html_e( 'Netto-Sendeminuten', 'sprecher-gagenrechner' ); ?></label><input id="sgk-net-minutes" name="net_minutes" type="number" min="0" step="0.1" /><p class="sgk-field__hint"><?php esc_html_e( 'Relevant für redaktionelle Inhalte und Audiodeskription.', 'sprecher-gagenrechner' ); ?></p></div>
						<div class="sgk-field" data-sgk-block="module_count"><label for="sgk-modules"><?php esc_html_e( 'Anzahl Module', 'sprecher-gagenrechner' ); ?></label><input id="sgk-modules" name="module_count" type="number" min="0" step="1" /><p class="sgk-field__hint"><?php esc_html_e( 'Für IVR- und Telefonansagen.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--three">
						<div class="sgk-field" data-sgk-block="fah"><label for="sgk-fah"><?php esc_html_e( 'Final Audio Hours (FAH)', 'sprecher-gagenrechner' ); ?></label><input id="sgk-fah" name="fah" type="number" min="0" step="0.5" /><p class="sgk-field__hint"><?php esc_html_e( 'Für Hörbuch-Vorschlagskalkulationen.', 'sprecher-gagenrechner' ); ?></p></div>
						<div class="sgk-field" data-sgk-block="recording_hours"><label for="sgk-hours"><?php esc_html_e( 'Recording Hours', 'sprecher-gagenrechner' ); ?></label><input id="sgk-hours" name="recording_hours" type="number" min="0" step="0.5" /></div>
						<div class="sgk-field" data-sgk-block="recording_days"><label for="sgk-days"><?php esc_html_e( 'Recording Days', 'sprecher-gagenrechner' ); ?></label><input id="sgk-days" name="recording_days" type="number" min="1" step="1" value="1" /></div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field" data-sgk-block="same_day_projects"><label for="sgk-projects"><?php esc_html_e( 'Weitere Projekte am selben Tag', 'sprecher-gagenrechner' ); ?></label><input id="sgk-projects" name="same_day_projects" type="number" min="1" step="1" value="1" /></div>
						<div class="sgk-field" data-sgk-block="scope_note"><div class="sgk-context-card sgk-context-card--soft"><strong><?php esc_html_e( 'Live-Hinweis', 'sprecher-gagenrechner' ); ?></strong><p data-sgk-scope-copy><?php esc_html_e( 'Die Hinweise zum Umfang passen sich dem Projektfall an.', 'sprecher-gagenrechner' ); ?></p></div></div>
					</div>
				</div>

				<div class="sgk-step-card is-disabled" data-step="rights" data-sgk-dependent-step>
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">4</span>
						<div>
							<h4><?php esc_html_e( 'Zusatzrechte / Sonderfälle', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Nur sinnvolle Rechte-Erweiterungen und Sonderlogiken werden eingeblendet.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--three" data-sgk-block="addon_counts">
						<div class="sgk-field"><label for="sgk-add-year"><?php esc_html_e( 'Zusatzjahre', 'sprecher-gagenrechner' ); ?></label><input id="sgk-add-year" name="additional_year" type="number" min="0" step="1" value="0" /></div>
						<div class="sgk-field"><label for="sgk-add-territory"><?php esc_html_e( 'Zusatzterritorien', 'sprecher-gagenrechner' ); ?></label><input id="sgk-add-territory" name="additional_territory" type="number" min="0" step="1" value="0" /></div>
						<div class="sgk-field"><label for="sgk-add-motif"><?php esc_html_e( 'Zusatzmotive', 'sprecher-gagenrechner' ); ?></label><input id="sgk-add-motif" name="additional_motif" type="number" min="0" step="1" value="0" /></div>
					</div>

					<div class="sgk-toggle-grid" data-sgk-block="rights_toggles">
						<label class="sgk-toggle"><input type="checkbox" name="archivgage" value="1" /><span><?php esc_html_e( 'Archivgage', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Separate Archivlizenz für passende Paid-Fälle.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="reminder" value="1" /><span><?php esc_html_e( 'Reminder', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Zusätzlicher Reminder-Pfad für Werbefälle.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="allongen" value="1" /><span><?php esc_html_e( 'Allongen', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Ergänzt klassische Audio-Werbung.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="follow_up_usage" value="1" /><span><?php esc_html_e( 'Nachgage / Folgeauswertung', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für spätere Nutzung nach vorherigem Layout.', 'sprecher-gagenrechner' ); ?></small></label>
					</div>
				</div>

				<details class="sgk-expert-shell is-disabled" data-sgk-expert-shell>
					<summary>
						<span><?php esc_html_e( 'Expertenmodus', 'sprecher-gagenrechner' ); ?></span>
						<small><?php esc_html_e( 'Sekundäre Sonderlogiken für Verhandlung, Pakete und Spezialfälle', 'sprecher-gagenrechner' ); ?></small>
					</summary>
					<div class="sgk-expert-shell__body">
						<div class="sgk-badge-row" data-sgk-expert-badges>
							<span class="sgk-badge is-muted"><?php esc_html_e( 'Noch keine Expertenoptionen aktiv', 'sprecher-gagenrechner' ); ?></span>
						</div>
						<div class="sgk-field-grid sgk-field-grid--two">
							<div class="sgk-field"><label for="sgk-prior-layout"><?php esc_html_e( 'Vorheriges Layout-Honorar', 'sprecher-gagenrechner' ); ?></label><input id="sgk-prior-layout" name="prior_layout_fee" type="number" min="0" step="0.01" value="0" /><p class="sgk-field__hint"><?php esc_html_e( 'Vorbereitung für Layout-/Nachgage-Logik.', 'sprecher-gagenrechner' ); ?></p></div>
							<div class="sgk-field"><label for="sgk-session-hours"><?php esc_html_e( 'Session Fee Stunden', 'sprecher-gagenrechner' ); ?></label><input id="sgk-session-hours" name="session_hours" type="number" min="0" step="0.5" value="0" /><p class="sgk-field__hint"><?php esc_html_e( 'Für Aufnahme-Sessions ohne unmittelbare öffentliche Lizenz.', 'sprecher-gagenrechner' ); ?></p></div>
						</div>
						<div class="sgk-toggle-grid sgk-toggle-grid--expert">
							<label class="sgk-toggle"><input type="checkbox" name="unlimited_time" value="1" /><span><?php esc_html_e( 'Zeitlich unbegrenzt', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Multiplikatorpfad vorbereiten', 'sprecher-gagenrechner' ); ?></small></label>
							<label class="sgk-toggle"><input type="checkbox" name="unlimited_territory" value="1" /><span><?php esc_html_e( 'Räumlich unbegrenzt', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für globale Sonderfälle', 'sprecher-gagenrechner' ); ?></small></label>
							<label class="sgk-toggle"><input type="checkbox" name="unlimited_media" value="1" /><span><?php esc_html_e( 'Medial unbegrenzt', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Buyout-nahe Sondersituation', 'sprecher-gagenrechner' ); ?></small></label>
						</div>
						<?php if ( ! empty( $demo_cases ) ) : ?>
							<div class="sgk-subsection">
								<h4><?php esc_html_e( 'Beispielkonfigurationen', 'sprecher-gagenrechner' ); ?></h4>
								<ul class="sgk-demo-list">
									<?php foreach ( $demo_cases as $demo ) : ?>
										<li><button type="button" class="sgk-demo-button" data-sgk-demo='<?php echo esc_attr( wp_json_encode( $demo['input'] ) ); ?>'><?php echo esc_html( $demo['label'] ); ?></button></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
				</details>

				<div class="sgk-step-card is-disabled" data-sgk-dependent-step>
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">5</span>
						<div>
							<h4><?php esc_html_e( 'Projektbezug & Angebotsnotizen', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Diese Angaben fließen in Speicherstände, Copy-Texte und die spätere PDF-Basis ein.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field"><label for="sgk-project-title"><?php esc_html_e( 'Projektbezug / Angebotsüberschrift', 'sprecher-gagenrechner' ); ?></label><input id="sgk-project-title" name="project_title" type="text" placeholder="z. B. Imagefilm Frühjahrskampagne" /><p class="sgk-field__hint"><?php esc_html_e( 'Wird für Exporttexte und Speicherstände genutzt.', 'sprecher-gagenrechner' ); ?></p></div>
						<div class="sgk-field"><label for="sgk-customer-name"><?php esc_html_e( 'Kunde / Ansprechpartner', 'sprecher-gagenrechner' ); ?></label><input id="sgk-customer-name" name="customer_name" type="text" placeholder="z. B. Muster GmbH" /><p class="sgk-field__hint"><?php esc_html_e( 'Optional für eine saubere Angebotsvorbereitung.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="sgk-field"><label for="sgk-internal-notes"><?php esc_html_e( 'Interne Notizen / technische Metadaten', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-internal-notes" name="internal_notes" rows="4" placeholder="Interne Verhandlungsnotizen, Timing, technische Hinweise"></textarea><p class="sgk-field__hint"><?php esc_html_e( 'Bleibt getrennt von den kundenfähigen Copy-Blöcken und dient nur der internen Weiterverarbeitung.', 'sprecher-gagenrechner' ); ?></p></div>
				</div>

				<div class="sgk-actions">
					<button type="submit" class="sgk-button sgk-button--primary"><?php esc_html_e( 'Jetzt live berechnen', 'sprecher-gagenrechner' ); ?></button>
					<p class="sgk-actions__hint"><?php esc_html_e( 'Die Berechnung aktualisiert sich zusätzlich automatisch bei Änderungen relevanter Felder.', 'sprecher-gagenrechner' ); ?></p>
				</div>
			</form>
		</section>

		<aside class="sgk-panel sgk-panel--result" aria-labelledby="sgk-result-title">
			<header class="sgk-panel__header">
				<p class="sgk-eyebrow"><?php esc_html_e( 'Live-Ergebnis', 'sprecher-gagenrechner' ); ?></p>
				<h3 id="sgk-result-title"><?php esc_html_e( 'Kalkulationsspanne, Lizenzen und fachliche Einordnung', 'sprecher-gagenrechner' ); ?></h3>
				<p><?php esc_html_e( 'Die rechte Spalte fungiert als stabiler Control Tower für Preisrahmen, Rechte und Rechenweg.', 'sprecher-gagenrechner' ); ?></p>
			</header>
			<div class="sgk-result" data-sgk-result>
				<div class="sgk-result-empty">
					<strong><?php esc_html_e( 'Bereit für Ihre erste Konfiguration', 'sprecher-gagenrechner' ); ?></strong>
					<p><?php esc_html_e( 'Wählen Sie links einen Fall aus. Sobald genügend Angaben vorliegen, erscheint hier automatisch die fachlich aufbereitete Live-Rechnung.', 'sprecher-gagenrechner' ); ?></p>
				</div>
			</div>
		</aside>
	</div>

	<div class="sgk-offer-modal" data-sgk-offer-modal hidden>
		<div class="sgk-offer-modal__backdrop" data-sgk-offer-close></div>
		<div class="sgk-offer-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="sgk-offer-modal-title">
			<header class="sgk-offer-modal__header">
				<div>
					<p class="sgk-eyebrow"><?php esc_html_e( 'Angebot & PDF', 'sprecher-gagenrechner' ); ?></p>
					<h3 id="sgk-offer-modal-title"><?php esc_html_e( 'Professionelles Angebotsdokument vorbereiten', 'sprecher-gagenrechner' ); ?></h3>
					<p><?php esc_html_e( 'Überprüfen Sie Angebotskopf, Projektdaten und Vorschau, bevor Sie das Dokument als PDF ausgeben.', 'sprecher-gagenrechner' ); ?></p>
				</div>
				<button type="button" class="sgk-button sgk-button--ghost" data-sgk-offer-close><?php esc_html_e( 'Schließen', 'sprecher-gagenrechner' ); ?></button>
			</header>
			<div class="sgk-offer-modal__grid">
				<section class="sgk-offer-modal__panel">
					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field"><label for="sgk-offer-number"><?php esc_html_e( 'Angebotsnummer', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-number" type="text" data-sgk-offer-meta="offer_number" placeholder="z. B. ANG-2026-001" /></div>
						<div class="sgk-field"><label for="sgk-offer-date"><?php esc_html_e( 'Angebotsdatum', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-date" type="date" data-sgk-offer-meta="offer_date" /></div>
					</div>
					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field"><label for="sgk-offer-contact"><?php esc_html_e( 'Ansprechpartner', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-contact" type="text" data-sgk-offer-meta="contact_name" placeholder="z. B. Julia Muster" /></div>
						<div class="sgk-field"><label for="sgk-offer-company"><?php esc_html_e( 'Absender / Studio', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-company" type="text" data-sgk-offer-meta="sender_company" placeholder="z. B. Sprecherstudio Mustermann" /></div>
					</div>
					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field"><label for="sgk-offer-email"><?php esc_html_e( 'Kontakt E-Mail', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-email" type="email" data-sgk-offer-meta="sender_email" placeholder="kontakt@studio.de" /></div>
						<div class="sgk-field"><label for="sgk-offer-phone"><?php esc_html_e( 'Telefon / Kontakt', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-phone" type="text" data-sgk-offer-meta="sender_phone" placeholder="+49 ..." /></div>
					</div>
					<div class="sgk-field"><label for="sgk-offer-intro"><?php esc_html_e( 'Einleitung / Begleittext', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-intro" rows="4" data-sgk-offer-meta="intro_text" placeholder="Vielen Dank für die Anfrage. Nachfolgend erhalten Sie unser Angebot auf Basis der abgestimmten Nutzung."></textarea></div>
					<div class="sgk-field"><label for="sgk-offer-footer"><?php esc_html_e( 'Footer / Kontaktdaten', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-footer" rows="3" data-sgk-offer-meta="footer_text" placeholder="Sprecherstudio Mustermann · kontakt@studio.de · www.studio.de"></textarea></div>
					<div class="sgk-field"><label for="sgk-offer-internal"><?php esc_html_e( 'Interne Notiz (nicht im PDF)', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-internal" rows="3" data-sgk-offer-meta="internal_note" placeholder="Nur intern sichtbar, nicht im Kundendokument."></textarea><p class="sgk-field__hint"><?php esc_html_e( 'Diese Notiz bleibt getrennt vom finalen PDF und dient nur der internen Angebotsvorbereitung.', 'sprecher-gagenrechner' ); ?></p></div>
					<div class="sgk-action-grid sgk-action-grid--actions">
						<button type="button" class="sgk-button sgk-button--primary" data-sgk-offer-action="print"><?php esc_html_e( 'Als PDF drucken / speichern', 'sprecher-gagenrechner' ); ?></button>
						<button type="button" class="sgk-button sgk-button--secondary" data-sgk-offer-action="copy-mail"><?php esc_html_e( 'Angebotstext kopieren', 'sprecher-gagenrechner' ); ?></button>
					</div>
					<p class="sgk-field__hint" data-sgk-offer-status><?php esc_html_e( 'Setzen Sie möglichst eine finale Angebotssumme, bevor das Dokument als PDF ausgegeben wird.', 'sprecher-gagenrechner' ); ?></p>
				</section>
				<section class="sgk-offer-modal__preview">
					<div class="sgk-offer-preview-shell" data-sgk-offer-preview></div>
				</section>
			</div>
		</div>
	</div>
</div>
