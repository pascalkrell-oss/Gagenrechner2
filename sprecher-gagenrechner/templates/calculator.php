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
			<p class="sgk-eyebrow"><?php esc_html_e( 'VDS-Gagenrechner', 'sprecher-gagenrechner' ); ?></p>
			<h2 class="sgk-app__title"><?php esc_html_e( 'Finde schnell einen passenden Preisrahmen für dein Sprechprojekt.', 'sprecher-gagenrechner' ); ?></h2>
			<p class="sgk-app__intro"><?php esc_html_e( 'Wähle Projektart, Nutzung und Umfang Schritt für Schritt aus. Rechts siehst du fortlaufend die empfohlene Spanne, die wichtigsten Rechte und eine verständliche Zusammenfassung.', 'sprecher-gagenrechner' ); ?></p>
		</div>

		<div class="sgk-hero-card">
			<span class="sgk-hero-card__badge"><?php esc_html_e( 'Live-Überblick', 'sprecher-gagenrechner' ); ?></span>
			<strong><?php esc_html_e( 'Spanne, Mittelwert und Angebot immer im Blick', 'sprecher-gagenrechner' ); ?></strong>
			<p><?php esc_html_e( 'Die finale Angebotssumme legst du bei Bedarf später manuell fest. Die Berechnungsbasis bleibt dabei unverändert.', 'sprecher-gagenrechner' ); ?></p>
		</div>
	</div>

	<div class="sgk-app__grid">
		<section class="sgk-panel sgk-panel--form" aria-labelledby="sgk-config-title">
			<header class="sgk-panel__header">
				<p class="sgk-eyebrow"><?php esc_html_e( 'Projekt konfigurieren', 'sprecher-gagenrechner' ); ?></p>
				<h3 id="sgk-config-title"><?php esc_html_e( 'Stelle dein Projekt in wenigen klaren Schritten zusammen', 'sprecher-gagenrechner' ); ?></h3>
				<p><?php esc_html_e( 'Du siehst nur die Eingaben, die für deine Auswahl wirklich relevant sind. So bleibt die Kalkulation übersichtlich und schnell erfassbar.', 'sprecher-gagenrechner' ); ?></p>
			</header>

			<form class="sgk-form" data-sgk-form>
				<input type="hidden" name="manual_offer_total" value="" />
				<div class="sgk-step-card" data-step="project">
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">1</span>
						<div>
							<h4><?php esc_html_e( 'Projektart wählen', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Starte mit der Projektform, die deinem Vorhaben am besten entspricht.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-feature-grid">
						<button type="button" class="sgk-quick-case" data-sgk-quick-case="werbung_mit_bild">
							<strong><?php esc_html_e( 'Werbung mit Bild', 'sprecher-gagenrechner' ); ?></strong>
							<span><?php esc_html_e( 'TV, CTV, Online-Video, Kino oder POS', 'sprecher-gagenrechner' ); ?></span>
						</button>
						<button type="button" class="sgk-quick-case" data-sgk-quick-case="werbung_ohne_bild">
							<strong><?php esc_html_e( 'Werbung ohne Bild', 'sprecher-gagenrechner' ); ?></strong>
							<span><?php esc_html_e( 'Funk, Online-Audio, Ladenfunk oder Telefonspot', 'sprecher-gagenrechner' ); ?></span>
						</button>
						<button type="button" class="sgk-quick-case" data-sgk-quick-case="webvideo_imagefilm_praesentation_unpaid">
							<strong><?php esc_html_e( 'Webvideo & Imagefilm', 'sprecher-gagenrechner' ); ?></strong>
							<span><?php esc_html_e( 'Corporate, Social Clips, Präsentation oder unpaid', 'sprecher-gagenrechner' ); ?></span>
						</button>
						<button type="button" class="sgk-quick-case" data-sgk-quick-case="telefonansage">
							<strong><?php esc_html_e( 'Telefonansage', 'sprecher-gagenrechner' ); ?></strong>
							<span><?php esc_html_e( 'IVR, Hotline oder modulare Ansagesysteme', 'sprecher-gagenrechner' ); ?></span>
						</button>
					</div>

					<div class="sgk-field">
						<label for="sgk-case-key"><?php esc_html_e( 'Projektart', 'sprecher-gagenrechner' ); ?></label>
						<select id="sgk-case-key" name="case_key" data-sgk-primary-field>
							<option value=""><?php esc_html_e( 'Bitte auswählen', 'sprecher-gagenrechner' ); ?></option>
							<optgroup label="Häufige Fälle">
								<?php foreach ( $cases as $case_key => $case ) : ?>
									<option value="<?php echo esc_attr( $case_key ); ?>"><?php echo esc_html( $case['label'] ); ?></option>
								<?php endforeach; ?>
							</optgroup>
							<optgroup label="Weitere Einstiegsszenarien">
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
						<p class="sgk-field__hint"><?php esc_html_e( 'Auch spezielle Fälle kannst du direkt auswählen. Falls nötig, ordnen wir deine Auswahl automatisch dem passenden Gagenbereich zu.', 'sprecher-gagenrechner' ); ?></p>
					</div>

					<div class="sgk-context-card" data-sgk-case-context>
						<strong><?php esc_html_e( 'Noch keine Projektart gewählt', 'sprecher-gagenrechner' ); ?></strong>
						<p><?php esc_html_e( 'Sobald du eine Projektart auswählst, zeigen wir dir nur die dazu passenden Eingaben an.', 'sprecher-gagenrechner' ); ?></p>
					</div>
				</div>

				<div class="sgk-step-card is-disabled" data-step="usage" data-sgk-dependent-step>
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">2</span>
						<div>
							<h4><?php esc_html_e( 'Nutzung festlegen', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Wähle die Nutzung, das Medium und den Einsatzzweck deines Projekts.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field" data-sgk-block="variant">
							<label for="sgk-variant"><?php esc_html_e( 'Variante', 'sprecher-gagenrechner' ); ?></label>
							<select id="sgk-variant" name="case_variant"></select>
							<p class="sgk-field__hint" data-sgk-variant-hint><?php esc_html_e( 'Diese Auswahl passt sich automatisch deiner Projektart an.', 'sprecher-gagenrechner' ); ?></p>
						</div>
						<div class="sgk-field" data-sgk-block="usage_type">
							<label for="sgk-usage-type"><?php esc_html_e( 'Art der Nutzung', 'sprecher-gagenrechner' ); ?></label>
							<select id="sgk-usage-type" name="usage_type">
								<option value="organic_branding"><?php esc_html_e( 'Branding / organisch / nicht paid', 'sprecher-gagenrechner' ); ?></option>
								<option value="paid_advertising"><?php esc_html_e( 'Paid Advertising / klassische Werbung', 'sprecher-gagenrechner' ); ?></option>
							</select>
							<p class="sgk-field__hint"><?php esc_html_e( 'Damit wird die Nutzung passend zur gewählten Ausspielung eingeordnet.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-toggle-grid" data-sgk-block="media_toggles">
						<label class="sgk-toggle"><input type="checkbox" name="is_paid_media" value="1" /><span><?php esc_html_e( 'Paid Media', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für bezahlte Ausspielung und Kampagnenreichweite.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="usage_social_media" value="1" /><span><?php esc_html_e( 'Social Media', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Wenn dein Inhalt zusätzlich auf Social Media genutzt wird.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="usage_praesentation" value="1" /><span><?php esc_html_e( 'Präsentation', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für interne, vertriebsnahe oder begleitende Präsentationen.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="usage_awardfilm" value="1" /><span><?php esc_html_e( 'Awardfilm', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Wird nur angezeigt, wenn diese Nutzung hier möglich ist.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="usage_mitarbeiterfilm" value="1" /><span><?php esc_html_e( 'Mitarbeiterfilm', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für interne Filmnutzung im Unternehmen.', 'sprecher-gagenrechner' ); ?></small></label>
					</div>

					<div class="sgk-info-banner" data-sgk-redirect-banner hidden></div>
				</div>

				<div class="sgk-step-card is-disabled" data-step="scope" data-sgk-dependent-step>
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">3</span>
						<div>
							<h4><?php esc_html_e( 'Umfang ergänzen', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Gib Dauer, Menge oder Aufnahmeeinheiten an – passend zu deinem Projekt.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--three">
						<div class="sgk-field" data-sgk-block="duration_minutes"><label for="sgk-duration"><?php esc_html_e( 'Dauer in Minuten', 'sprecher-gagenrechner' ); ?></label><input id="sgk-duration" name="duration_minutes" type="number" min="0" step="0.1" /><p class="sgk-field__hint"><?php esc_html_e( 'Für Projekte mit minutenbasierter Staffelung.', 'sprecher-gagenrechner' ); ?></p></div>
						<div class="sgk-field" data-sgk-block="net_minutes"><label for="sgk-net-minutes"><?php esc_html_e( 'Netto-Sendeminuten', 'sprecher-gagenrechner' ); ?></label><input id="sgk-net-minutes" name="net_minutes" type="number" min="0" step="0.1" /><p class="sgk-field__hint"><?php esc_html_e( 'Relevant für redaktionelle Inhalte und Audiodeskription.', 'sprecher-gagenrechner' ); ?></p></div>
						<div class="sgk-field" data-sgk-block="module_count"><label for="sgk-modules"><?php esc_html_e( 'Anzahl der Module', 'sprecher-gagenrechner' ); ?></label><input id="sgk-modules" name="module_count" type="number" min="0" step="1" /><p class="sgk-field__hint"><?php esc_html_e( 'Zum Beispiel für IVR- oder Telefonansagen.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--three">
						<div class="sgk-field" data-sgk-block="fah"><label for="sgk-fah"><?php esc_html_e( 'Final Audio Hours (FAH)', 'sprecher-gagenrechner' ); ?></label><input id="sgk-fah" name="fah" type="number" min="0" step="0.5" /><p class="sgk-field__hint"><?php esc_html_e( 'Für Hörbuch-Projekte auf Basis der fertigen Audiozeit.', 'sprecher-gagenrechner' ); ?></p></div>
						<div class="sgk-field" data-sgk-block="recording_hours"><label for="sgk-hours"><?php esc_html_e( 'Aufnahmestunden', 'sprecher-gagenrechner' ); ?></label><input id="sgk-hours" name="recording_hours" type="number" min="0" step="0.5" /></div>
						<div class="sgk-field" data-sgk-block="recording_days"><label for="sgk-days"><?php esc_html_e( 'Aufnahmetage', 'sprecher-gagenrechner' ); ?></label><input id="sgk-days" name="recording_days" type="number" min="1" step="1" value="1" /></div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field" data-sgk-block="same_day_projects"><label for="sgk-projects"><?php esc_html_e( 'Weitere Projekte am selben Tag', 'sprecher-gagenrechner' ); ?></label><input id="sgk-projects" name="same_day_projects" type="number" min="1" step="1" value="1" /></div>
						<div class="sgk-field" data-sgk-block="scope_note"><div class="sgk-context-card sgk-context-card--soft"><strong><?php esc_html_e( 'Hinweis zum Umfang', 'sprecher-gagenrechner' ); ?></strong><p data-sgk-scope-copy><?php esc_html_e( 'Die Hinweise passen sich deiner Projektart automatisch an.', 'sprecher-gagenrechner' ); ?></p></div></div>
					</div>
				</div>

				<div class="sgk-step-card is-disabled" data-step="rights" data-sgk-dependent-step>
					<div class="sgk-step-card__header">
						<span class="sgk-step-card__index">4</span>
						<div>
							<h4><?php esc_html_e( 'Rechte & Zusatzoptionen', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Ergänze nur die Optionen, die für dein Projekt zusätzlich relevant sind.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>

					<div class="sgk-field-grid sgk-field-grid--three" data-sgk-block="addon_counts">
						<div class="sgk-field"><label for="sgk-add-year"><?php esc_html_e( 'Zusatzjahre', 'sprecher-gagenrechner' ); ?></label><input id="sgk-add-year" name="additional_year" type="number" min="0" step="1" value="0" /></div>
						<div class="sgk-field"><label for="sgk-add-territory"><?php esc_html_e( 'Zusätzliche Gebiete', 'sprecher-gagenrechner' ); ?></label><input id="sgk-add-territory" name="additional_territory" type="number" min="0" step="1" value="0" /></div>
						<div class="sgk-field"><label for="sgk-add-motif"><?php esc_html_e( 'Zusatzmotive', 'sprecher-gagenrechner' ); ?></label><input id="sgk-add-motif" name="additional_motif" type="number" min="0" step="1" value="0" /></div>
					</div>

					<div class="sgk-toggle-grid" data-sgk-block="rights_toggles">
						<label class="sgk-toggle"><input type="checkbox" name="archivgage" value="1" /><span><?php esc_html_e( 'Archivnutzung', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für passende Paid-Fälle mit separater Archivnutzung.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="reminder" value="1" /><span><?php esc_html_e( 'Reminder', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Zusätzliche Reminder-Nutzung für passende Werbefälle.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="allongen" value="1" /><span><?php esc_html_e( 'Allongen', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Ergänzend für klassische Audio-Werbung.', 'sprecher-gagenrechner' ); ?></small></label>
						<label class="sgk-toggle"><input type="checkbox" name="follow_up_usage" value="1" /><span><?php esc_html_e( 'Nachnutzung', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für spätere weitere Nutzung auf Basis einer bestehenden Kalkulation.', 'sprecher-gagenrechner' ); ?></small></label>
					</div>
				</div>

				<details class="sgk-expert-shell is-disabled" data-sgk-expert-shell>
					<summary>
						<span><?php esc_html_e( 'Experteneinstellungen', 'sprecher-gagenrechner' ); ?></span>
						<small><?php esc_html_e( 'Zusätzliche Optionen für Sonderfälle, Verhandlung und spezielle Pakete', 'sprecher-gagenrechner' ); ?></small>
					</summary>
					<div class="sgk-expert-shell__body">
						<div class="sgk-badge-row" data-sgk-expert-badges>
							<span class="sgk-badge is-muted"><?php esc_html_e( 'Noch keine zusätzlichen Optionen aktiv', 'sprecher-gagenrechner' ); ?></span>
						</div>
						<div class="sgk-field-grid sgk-field-grid--two">
							<div class="sgk-field"><label for="sgk-prior-layout"><?php esc_html_e( 'Vorheriges Layout-Honorar', 'sprecher-gagenrechner' ); ?></label><input id="sgk-prior-layout" name="prior_layout_fee" type="number" min="0" step="0.01" value="0" /><p class="sgk-field__hint"><?php esc_html_e( 'Falls bereits ein früherer Layout-Wert berücksichtigt werden soll.', 'sprecher-gagenrechner' ); ?></p></div>
							<div class="sgk-field"><label for="sgk-session-hours"><?php esc_html_e( 'Session-Stunden', 'sprecher-gagenrechner' ); ?></label><input id="sgk-session-hours" name="session_hours" type="number" min="0" step="0.5" value="0" /><p class="sgk-field__hint"><?php esc_html_e( 'Für Aufnahmesessions ohne direkte öffentliche Nutzung.', 'sprecher-gagenrechner' ); ?></p></div>
						</div>
						<div class="sgk-toggle-grid sgk-toggle-grid--expert">
							<label class="sgk-toggle"><input type="checkbox" name="unlimited_time" value="1" /><span><?php esc_html_e( 'Zeitlich unbegrenzt', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für besondere Fälle mit unbegrenzter Laufzeit.', 'sprecher-gagenrechner' ); ?></small></label>
							<label class="sgk-toggle"><input type="checkbox" name="unlimited_territory" value="1" /><span><?php esc_html_e( 'Räumlich unbegrenzt', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für Einsätze ohne räumliche Begrenzung.', 'sprecher-gagenrechner' ); ?></small></label>
							<label class="sgk-toggle"><input type="checkbox" name="unlimited_media" value="1" /><span><?php esc_html_e( 'Medial unbegrenzt', 'sprecher-gagenrechner' ); ?></span><small><?php esc_html_e( 'Für sehr weitreichende Sondervereinbarungen.', 'sprecher-gagenrechner' ); ?></small></label>
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
							<h4><?php esc_html_e( 'Projektangaben & Notizen', 'sprecher-gagenrechner' ); ?></h4>
							<p><?php esc_html_e( 'Diese Angaben helfen dir bei Speicherung, Angebotstexten und der späteren PDF-Ausgabe.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field"><label for="sgk-project-title"><?php esc_html_e( 'Projektname oder Angebotsüberschrift', 'sprecher-gagenrechner' ); ?></label><input id="sgk-project-title" name="project_title" type="text" placeholder="z. B. Imagefilm Frühjahrskampagne" /><p class="sgk-field__hint"><?php esc_html_e( 'Wird für gespeicherte Kalkulationen und Exporttexte genutzt.', 'sprecher-gagenrechner' ); ?></p></div>
						<div class="sgk-field"><label for="sgk-customer-name"><?php esc_html_e( 'Kunde oder Kontakt', 'sprecher-gagenrechner' ); ?></label><input id="sgk-customer-name" name="customer_name" type="text" placeholder="z. B. Muster GmbH" /><p class="sgk-field__hint"><?php esc_html_e( 'Optional für eine sauber vorbereitete Angebotsansicht.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="sgk-field"><label for="sgk-internal-notes"><?php esc_html_e( 'Interne Notizen', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-internal-notes" name="internal_notes" rows="4" placeholder="Interne Verhandlungsnotizen, Timing oder technische Hinweise"></textarea><p class="sgk-field__hint"><?php esc_html_e( 'Bleibt intern und wird nicht automatisch als Kundentext ausgegeben.', 'sprecher-gagenrechner' ); ?></p></div>
				</div>

				<div class="sgk-actions">
					<button type="submit" class="sgk-button sgk-button--primary"><?php esc_html_e( 'Preisrahmen berechnen', 'sprecher-gagenrechner' ); ?></button>
					<p class="sgk-actions__hint"><?php esc_html_e( 'Die Ansicht aktualisiert sich auch automatisch, sobald du relevante Eingaben änderst.', 'sprecher-gagenrechner' ); ?></p>
				</div>
			</form>
		</section>

		<aside class="sgk-panel sgk-panel--result" aria-labelledby="sgk-result-title">
			<header class="sgk-panel__header">
				<p class="sgk-eyebrow"><?php esc_html_e( 'Dein Ergebnis', 'sprecher-gagenrechner' ); ?></p>
				<h3 id="sgk-result-title"><?php esc_html_e( 'Preisrahmen, Rechte und Angebotsbasis auf einen Blick', 'sprecher-gagenrechner' ); ?></h3>
				<p><?php esc_html_e( 'Diese Seitenleiste fasst die wichtigsten Ergebnisse kompakt zusammen und führt dich direkt zur weiteren Angebotsvorbereitung.', 'sprecher-gagenrechner' ); ?></p>
			</header>
			<div class="sgk-result" data-sgk-result>
				<div class="sgk-result-empty">
					<strong><?php esc_html_e( 'Bereit für deine erste Kalkulation', 'sprecher-gagenrechner' ); ?></strong>
					<p><?php esc_html_e( 'Sobald du links ein Projekt auswählst und die ersten Angaben ergänzt, erscheint hier automatisch deine Ergebnisübersicht.', 'sprecher-gagenrechner' ); ?></p>
				</div>
			</div>
		</aside>
	</div>

	<div class="sgk-offer-modal" data-sgk-offer-modal hidden aria-hidden="true">
		<div class="sgk-offer-modal__backdrop" data-sgk-offer-close></div>
		<div class="sgk-offer-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="sgk-offer-modal-title" tabindex="-1">
			<header class="sgk-offer-modal__header">
				<div>
					<p class="sgk-eyebrow"><?php esc_html_e( 'Angebot vorbereiten', 'sprecher-gagenrechner' ); ?></p>
					<h3 id="sgk-offer-modal-title"><?php esc_html_e( 'Prüfe deine Angebotsangaben vor dem PDF-Export', 'sprecher-gagenrechner' ); ?></h3>
					<p><?php esc_html_e( 'Ergänze Kontaktdaten, Einleitung und Absenderangaben. Rechts siehst du direkt die Vorschau deines Angebotsdokuments.', 'sprecher-gagenrechner' ); ?></p>
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
						<div class="sgk-field"><label for="sgk-offer-company"><?php esc_html_e( 'Absender oder Studio', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-company" type="text" data-sgk-offer-meta="sender_company" placeholder="z. B. Sprecherstudio Mustermann" /></div>
					</div>
					<div class="sgk-field-grid sgk-field-grid--two">
						<div class="sgk-field"><label for="sgk-offer-email"><?php esc_html_e( 'Kontakt-E-Mail', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-email" type="email" data-sgk-offer-meta="sender_email" placeholder="kontakt@studio.de" /></div>
						<div class="sgk-field"><label for="sgk-offer-phone"><?php esc_html_e( 'Telefon', 'sprecher-gagenrechner' ); ?></label><input id="sgk-offer-phone" type="text" data-sgk-offer-meta="sender_phone" placeholder="+49 ..." /></div>
					</div>
					<div class="sgk-field"><label for="sgk-offer-intro"><?php esc_html_e( 'Einleitung', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-intro" rows="4" data-sgk-offer-meta="intro_text" placeholder="Vielen Dank für deine Anfrage. Nachfolgend erhältst du unser Angebot auf Basis der abgestimmten Nutzung."></textarea></div>
					<div class="sgk-field"><label for="sgk-offer-footer"><?php esc_html_e( 'Fußzeile oder Kontaktdaten', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-footer" rows="3" data-sgk-offer-meta="footer_text" placeholder="Sprecherstudio Mustermann · kontakt@studio.de · www.studio.de"></textarea></div>
					<div class="sgk-field"><label for="sgk-offer-internal"><?php esc_html_e( 'Interne Notiz', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-internal" rows="3" data-sgk-offer-meta="internal_note" placeholder="Nur intern sichtbar, nicht im Kundendokument."></textarea><p class="sgk-field__hint"><?php esc_html_e( 'Diese Notiz bleibt intern und erscheint nicht im PDF für Kundinnen und Kunden.', 'sprecher-gagenrechner' ); ?></p></div>
					<div class="sgk-action-grid sgk-action-grid--actions">
						<button type="button" class="sgk-button sgk-button--primary" data-sgk-offer-action="print"><?php esc_html_e( 'PDF drucken oder speichern', 'sprecher-gagenrechner' ); ?></button>
						<button type="button" class="sgk-button sgk-button--secondary" data-sgk-offer-action="copy-mail"><?php esc_html_e( 'Angebotstext kopieren', 'sprecher-gagenrechner' ); ?></button>
					</div>
					<p class="sgk-field__hint" data-sgk-offer-status><?php esc_html_e( 'Am besten hinterlegst du vor dem Export eine finale Angebotssumme.', 'sprecher-gagenrechner' ); ?></p>
				</section>
				<section class="sgk-offer-modal__preview">
					<div class="sgk-offer-preview-shell" data-sgk-offer-preview></div>
				</section>
			</div>
		</div>
	</div>
</div>
