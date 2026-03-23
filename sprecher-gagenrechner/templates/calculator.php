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
	<div class="src-layout">
		<main class="src-config-engine" aria-labelledby="sgk-config-title">
			<section class="src-hero-panel src-section--glass" aria-label="Einführung in den Rechner">
				<div class="src-hero-copy">
					<p class="src-page-eyebrow"><?php esc_html_e( 'Sprecher Gagenrechner', 'sprecher-gagenrechner' ); ?></p>
					<h2><?php esc_html_e( 'Mit wenigen Angaben schnell zur realistischen Preisempfehlung.', 'sprecher-gagenrechner' ); ?></h2>
					<p class="src-hero-lead"><?php esc_html_e( 'Führe Dein Projekt Schritt für Schritt durch Projektart, Nutzung, Rechte und Umfang. Besonders wichtig für die Kalkulation sind Gebiet, Laufzeit, Medium und mögliche Rechte-Erweiterungen.', 'sprecher-gagenrechner' ); ?></p>
				</div>
				<div class="src-hero-highlights" aria-label="So ist der Rechner aufgebaut">
					<div class="src-hero-stat"><strong><?php esc_html_e( 'So gehst Du vor', 'sprecher-gagenrechner' ); ?></strong><span><?php esc_html_e( '1. Projekt wählen · 2. Nutzung einordnen · 3. Rechte festlegen · 4. Umfang ergänzen · 5. Extras prüfen.', 'sprecher-gagenrechner' ); ?></span></div>
					<div class="src-hero-stat"><strong><?php esc_html_e( 'Wichtig für die Preisempfehlung', 'sprecher-gagenrechner' ); ?></strong><span><?php esc_html_e( 'Vor allem Rechte und Verwertung beeinflussen die Empfehlung: Wo läuft das Projekt, wie lange und über welche Kanäle?', 'sprecher-gagenrechner' ); ?></span></div>
					<div class="src-hero-stat"><strong><?php esc_html_e( 'Ergebnis', 'sprecher-gagenrechner' ); ?></strong><span><?php esc_html_e( 'Rechtsübersicht, Preisrahmen und Angebotsvorbereitung aktualisieren sich automatisch, sobald die Eingaben vollständig sind.', 'sprecher-gagenrechner' ); ?></span></div>
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
							<p class="src-section-copy"><?php esc_html_e( 'Wähle zuerst die Projektart. Danach zeigen wir Dir nur die Eingaben, die für eine grobe Preisempfehlung wirklich relevant sind.', 'sprecher-gagenrechner' ); ?></p>
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
							<span class="src-card-copy"><strong><?php esc_html_e( 'Imagefilm & PR', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Imagefilm, Awardfilm, Casefilm, unpaid', 'sprecher-gagenrechner' ); ?></small></span>
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
						<button type="button" class="src-card" data-sgk-quick-case="app">
							<span class="src-card-icon"><i data-lucide="smartphone" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'App', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Minutenbasiert für App-Inhalte', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="hoerbuch">
							<span class="src-card-icon"><i data-lucide="book-audio" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Hörbuch', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'FAH-basierte Vorschlagskalkulation', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="games">
							<span class="src-card-icon"><i data-lucide="gamepad-2" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Games', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Session-, Tages- und Projektlogik', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="redaktionell_doku_tv_reportage">
							<span class="src-card-icon"><i data-lucide="captions" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Redaktionell / Doku', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Kommentarstimme, Overvoice, Mindestgage', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="audiodeskription">
							<span class="src-card-icon"><i data-lucide="accessibility" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Audiodeskription', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Minutenpreis mit Mindestgage', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="kleinraeumig">
							<span class="src-card-icon"><i data-lucide="map-pinned" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Kleinräumige Nutzung', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Lokal begrenzte Sonderfälle', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
						<button type="button" class="src-card" data-sgk-quick-case="session_fee">
							<span class="src-card-icon"><i data-lucide="clock-3" width="20" height="20"></i></span>
							<span class="src-card-copy"><strong><?php esc_html_e( 'Session Fee', 'sprecher-gagenrechner' ); ?></strong><small><?php esc_html_e( 'Nur Aufnahmestunden ohne Lizenz', 'sprecher-gagenrechner' ); ?></small></span>
						</button>
					</div>
					<div class="src-context-card" data-sgk-case-context hidden></div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="usage" data-sgk-dependent-step>
					<div class="src-section-header">
						<div class="src-step-badge">02</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title"><?php esc_html_e( 'Wie wird das Projekt eingesetzt?', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Hier ordnest Du Format, Variante und Nutzung ein. So landet Dein Projekt auf der passenden Kalkulationsroute.', 'sprecher-gagenrechner' ); ?></p>
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
								<div class="src-row-desc"><?php esc_html_e( 'Nur dort sichtbar, wo zwischen organischer Nutzung und Paid-Kampagne unterschieden werden muss.', 'sprecher-gagenrechner' ); ?></div>
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
						<i data-lucide="sparkles" width="18" height="18"></i>
						<div>
							<strong><?php esc_html_e( 'Smart Match aktiv', 'sprecher-gagenrechner' ); ?></strong>
							<p><?php esc_html_e( 'Die Auswahl wurde passend zur optimalen Kalkulationsroute eingeordnet.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-panel-group src-toggle-grid" data-sgk-block="media_toggles">
						<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Zusätzliche Nutzungskanäle', 'sprecher-gagenrechner' ); ?></div>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Paid Media', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Aktiviere das nur, wenn die Stimme zusätzlich als bezahlte Ausspielung genutzt wird.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch"><input type="checkbox" name="is_paid_media" value="1" /><span class="src-slider"></span></span>
						</label>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Social Media', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für zusätzliche Social-Nutzung neben der gewählten Hauptnutzung.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch"><input type="checkbox" name="usage_social_media" value="1" /><span class="src-slider"></span></span>
						</label>
						<label class="src-panel-row">
							<div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Präsentation', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Falls die Stimme zusätzlich intern oder auf Präsentationen eingesetzt wird.', 'sprecher-gagenrechner' ); ?></div></div>
							<span class="src-switch"><input type="checkbox" name="usage_praesentation" value="1" /><span class="src-slider"></span></span>
						</label>
					</div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="rights" data-sgk-dependent-step>
					<div class="src-section-header">
						<div class="src-step-badge">03</div>
						<div class="src-section-title-wrap">
							<p class="src-section-title"><?php esc_html_e( 'Nutzungsrechte & Verwertung festlegen', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Dieser Schritt ist ein zentraler Preisfaktor: Wo läuft das Projekt, wie lange gilt die Nutzung und über welche Medien wird die Stimme ausgespielt?', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-rights-intro">
						<div class="src-rights-intro-card src-rights-intro-card--primary">
							<strong><?php esc_html_e( 'Für die grobe Empfehlung besonders wichtig', 'sprecher-gagenrechner' ); ?></strong>
							<p><?php esc_html_e( 'Gebiet, Laufzeit und Medium wirken direkt auf die Nutzungslizenz. Bitte fülle diese Angaben möglichst passend aus.', 'sprecher-gagenrechner' ); ?></p>
						</div>
						<div class="src-rights-intro-card" data-sgk-rights-summary>
							<strong><?php esc_html_e( 'Dein aktueller Rechte-Stand', 'sprecher-gagenrechner' ); ?></strong>
							<p><?php esc_html_e( 'Sobald Du Projekt und Variante gewählt hast, fassen wir hier Gebiet, Laufzeit, Medium und wichtige Rechte-Erweiterungen kompakt zusammen.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-rights-layout src-rights-layout--core">
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
							<p class="src-section-title"><?php esc_html_e( 'Wie groß ist der Umfang?', 'sprecher-gagenrechner' ); ?></p>
							<p class="src-section-copy"><?php esc_html_e( 'Erfasse hier Menge, Minuten, Module oder Sessions. Wir zeigen Dir nur die Werte, die für Deine Projektart benötigt werden.', 'sprecher-gagenrechner' ); ?></p>
						</div>
					</div>
					<div class="src-panel-group">
						<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Projektumfang', 'sprecher-gagenrechner' ); ?></div>
						<div class="src-panel-row" data-sgk-block="duration_minutes"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Minuten Audiomaterial?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für minutengestaffelte Projekte.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-duration" name="duration_minutes" type="number" min="0" step="0.1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="net_minutes"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Netto-Sendeminuten?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Relevant für redaktionelle Inhalte und Audiodeskription.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-net-minutes" name="net_minutes" type="number" min="0" step="0.1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="module_count"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Module oder Ansagen?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Zum Beispiel für IVR- oder Telefonansagen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-modules" name="module_count" type="number" min="0" step="1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="fah"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Final Audio Hours (FAH)?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für Hörbuch-Projekte auf Basis der fertigen Audiozeit.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-fah" name="fah" type="number" min="0" step="0.5" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="recording_hours"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Aufnahmestunden?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für session-basierte Kalkulationen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-hours" name="recording_hours" type="number" min="0" step="0.5" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="recording_days"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele Aufnahmetage?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für mehrtägige oder wiederkehrende Sessions.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-days" name="recording_days" type="number" min="1" step="1" value="1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="same_day_projects"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Wie viele weitere Projekte am selben Tag?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Hilft bei parallelen Sessions oder Paketproduktionen.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-projects" name="same_day_projects" type="number" min="1" step="1" value="1" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						<div class="src-panel-row" data-sgk-block="scope_note"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Hinweis zur Kalkulation', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc" data-sgk-scope-copy><?php esc_html_e( 'Wenn hier nichts weiter abgefragt wird, ist das beabsichtigt: Dann bestimmen vor allem Projektart, Variante und Rechte die Preisempfehlung.', 'sprecher-gagenrechner' ); ?></div></div></div>
					</div>
				</section>

				<section class="src-section src-section--glass is-disabled" data-step="extras" data-sgk-dependent-step>
					<div class="src-section-header">
						<div class="src-step-badge">05</div>
						<div class="src-section-title-wrap"><p class="src-section-title"><?php esc_html_e( 'Zusatzrechte & Sonderfälle', 'sprecher-gagenrechner' ); ?></p><p class="src-section-copy"><?php esc_html_e( 'Hier ergänzt Du Erweiterungen wie Zusatzjahre, weitere Gebiete, weitere Motive oder besondere Rechtefälle. Nur sichtbare Optionen sind für Deine Auswahl relevant.', 'sprecher-gagenrechner' ); ?></p></div>
					</div>
					<div class="src-rights-layout">
						<div class="src-panel-group" data-sgk-block="addon_counts">
							<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Erweiterungen der Nutzung', 'sprecher-gagenrechner' ); ?></div>
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Gibt es zusätzliche Jahre?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Erhöhe diesen Wert, wenn die Nutzung über die gewählte Grundlaufzeit hinaus verlängert wird.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-add-year" name="additional_year" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Gibt es zusätzliche Gebiete?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nutze das für weitere Länder oder zusätzliche Ausspielräume neben der Grundnutzung.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-add-territory" name="additional_territory" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
							<div class="src-panel-row"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Gibt es mehrere Motive oder Versionen?', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nutze das für zusätzliche Motive, Versionen oder klar getrennte Varianten.', 'sprecher-gagenrechner' ); ?></div></div><div class="src-stepper" data-sgk-stepper><button type="button" data-sgk-step="down"><i data-lucide="minus" width="14" height="14"></i></button><input id="sgk-add-motif" name="additional_motif" type="number" min="0" step="1" value="0" /><button type="button" data-sgk-step="up"><i data-lucide="plus" width="14" height="14"></i></button></div></div>
						</div>
						<div class="src-panel-group" data-sgk-block="rights_toggles">
							<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Besondere Rechteoptionen', 'sprecher-gagenrechner' ); ?></div>
							<label class="src-panel-row" data-sgk-conditional-field="archivgage"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Archivnutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Aktiviere das nur, wenn eine eigenständige Archivnutzung vereinbart wird.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="archivgage" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row" data-sgk-conditional-field="reminder"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Reminder', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Aktiviere das nur, wenn der Reminder zusätzlich zur Hauptnutzung gebucht wird.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="reminder" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row" data-sgk-conditional-field="allongen"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Allongen', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Für ergänzende Verlängerungsnutzungen in passenden Audio-Werbefällen.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="allongen" value="1" /><span class="src-slider"></span></span></label>
							<label class="src-panel-row" data-sgk-conditional-field="follow_up_usage"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Spätere Nachnutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Wenn ein bestehendes Layout oder eine Vorstufe später regulär genutzt werden soll.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="follow_up_usage" value="1" /><span class="src-slider"></span></span></label>
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
								<div class="src-panel-row src-panel-row--header"><?php esc_html_e( 'Spezielle Rechte- und Produktionsfälle', 'sprecher-gagenrechner' ); ?></div>
								<div class="src-panel-row" data-sgk-block="prior_layout_fee" data-sgk-conditional-field="prior_layout_fee"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Vorheriges Layout-Honorar', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nur nötig, wenn eine frühere Layout- oder Vorstufenvergütung auf eine spätere Nutzung angerechnet wird.', 'sprecher-gagenrechner' ); ?></div></div><input id="sgk-prior-layout" name="prior_layout_fee" type="number" min="0" step="0.01" value="0" class="src-input-text src-input-text--compact" /></div>
								<div class="src-panel-row" data-sgk-block="session_hours"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Session-Stunden', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Nur für Session-Fee-Fälle ohne öffentliche Lizenz.', 'sprecher-gagenrechner' ); ?></div></div><input id="sgk-session-hours" name="session_hours" type="number" min="0" step="0.5" value="0" class="src-input-text src-input-text--compact" /></div>
								<label class="src-panel-row" data-sgk-block="unlimited_usage" data-sgk-conditional-field="unlimited_time"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Zeitlich unbegrenzte Nutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Aktiviere das nur, wenn die Nutzung ohne zeitliche Begrenzung vereinbart wird.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="unlimited_time" value="1" /><span class="src-slider"></span></span></label>
								<label class="src-panel-row" data-sgk-block="unlimited_usage" data-sgk-conditional-field="unlimited_territory"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Räumlich unbegrenzte Nutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Wenn die Nutzung nicht auf einzelne Gebiete begrenzt ist.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="unlimited_territory" value="1" /><span class="src-slider"></span></span></label>
								<label class="src-panel-row" data-sgk-block="unlimited_usage" data-sgk-conditional-field="unlimited_media"><div class="src-row-content"><div class="src-row-label"><?php esc_html_e( 'Medial unbegrenzte Nutzung', 'sprecher-gagenrechner' ); ?></div><div class="src-row-desc"><?php esc_html_e( 'Wenn die Verwendung nicht auf einzelne Ausspielwege begrenzt ist.', 'sprecher-gagenrechner' ); ?></div></div><span class="src-switch"><input type="checkbox" name="unlimited_media" value="1" /><span class="src-slider"></span></span></label>
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
					<button type="submit" class="src-btn-primary" data-sgk-submit><?php esc_html_e( 'Preisrahmen berechnen', 'sprecher-gagenrechner' ); ?></button>
					<p class="src-actions-hint" data-sgk-validation-status><?php esc_html_e( 'Wähle zuerst eine Projektart und ergänze dann die Pflichtfelder.', 'sprecher-gagenrechner' ); ?></p>
				</div>
			</form>
		</main>

		<aside class="src-control-tower-wrapper" aria-labelledby="sgk-result-title">
			<div class="src-tower-main">
				<div class="src-tower-header">
					<div>
						<p class="src-tower-label" id="sgk-result-title"><?php esc_html_e( 'Empfehlung in Echtzeit', 'sprecher-gagenrechner' ); ?></p>
						<h3 class="src-tower-headline"><?php esc_html_e( 'Preisrahmen, Rechte und Ergebnis an einem Ort.', 'sprecher-gagenrechner' ); ?></h3>
					</div>
					<div class="src-live-badge"><span class="src-live-dot"></span><?php esc_html_e( 'Live', 'sprecher-gagenrechner' ); ?></div>
				</div>
				<div class="src-tower-intro">
					<div class="src-inline-dark-panel"><strong><?php esc_html_e( 'Preisfokus & Ergebnis', 'sprecher-gagenrechner' ); ?></strong><?php esc_html_e( 'Sobald die Eingaben vollständig sind, steht hier zuerst Deine Preisempfehlung. Direkt darunter folgen Projektzusammenfassung, Rechte, Umfang und Exportaktionen in klarer Reihenfolge.', 'sprecher-gagenrechner' ); ?></div>
				</div>
				<div class="src-tower-result" data-sgk-result>
					<div class="src-result-empty"><strong><?php esc_html_e( 'Projekt auswählen', 'sprecher-gagenrechner' ); ?></strong><p><?php esc_html_e( 'Sobald links eine Projektart aktiv ist, erscheint hier die empfohlene Netto-Gage mit Rechteübersicht und Breakdown.', 'sprecher-gagenrechner' ); ?></p></div>
				</div>
				<div class="src-tower-journey-shell">
					<div class="src-inline-dark-panel src-inline-dark-panel--subtle">
						<strong><?php esc_html_e( 'Projektzusammenfassung', 'sprecher-gagenrechner' ); ?></strong>
						<div class="src-tower-journey" data-sgk-journey-summary>
							<div class="src-tower-journey-item"><span>Projekt</span><strong><?php esc_html_e( 'Noch nicht gewählt', 'sprecher-gagenrechner' ); ?></strong></div>
							<div class="src-tower-journey-item"><span>Rechte</span><strong><?php esc_html_e( 'Werden nach Auswahl sichtbar', 'sprecher-gagenrechner' ); ?></strong></div>
							<div class="src-tower-journey-item"><span>Umfang</span><strong><?php esc_html_e( 'Noch offen', 'sprecher-gagenrechner' ); ?></strong></div>
						</div>
					</div>
				</div>
			</div>
			<div class="src-tower-info">
				<div class="src-tower-info-header"><i data-lucide="lightbulb" width="16" height="16"></i><?php esc_html_e( 'Wissenswertes', 'sprecher-gagenrechner' ); ?></div>
				<div class="src-accordion-item"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger><span><?php esc_html_e( 'Warum sind Nutzungsrechte so wichtig?', 'sprecher-gagenrechner' ); ?></span><i data-lucide="chevron-down" width="14" height="14"></i></button><div class="src-accordion-content"><?php esc_html_e( 'Gebiet, Laufzeit und Medium bestimmen maßgeblich den Rechteumfang. Deshalb werden diese Angaben im Rechner als eigener Kernschritt geführt.', 'sprecher-gagenrechner' ); ?></div></div>
				<div class="src-accordion-item"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger><span><?php esc_html_e( 'Gilt die Gage pro Motiv?', 'sprecher-gagenrechner' ); ?></span><i data-lucide="chevron-down" width="14" height="14"></i></button><div class="src-accordion-content"><?php esc_html_e( 'Ja, jede eigenständige Variation gilt laut VDS als eigenes Motiv.', 'sprecher-gagenrechner' ); ?></div></div>
				<div class="src-accordion-item"><button type="button" class="src-accordion-btn" data-sgk-accordion-trigger><span><?php esc_html_e( 'Wann brauche ich Unlimited-Optionen?', 'sprecher-gagenrechner' ); ?></span><i data-lucide="chevron-down" width="14" height="14"></i></button><div class="src-accordion-content"><?php esc_html_e( 'Unlimited-Optionen sind nur für besondere Vereinbarungen gedacht, wenn die Nutzung zeitlich, räumlich oder medial nicht begrenzt wird.', 'sprecher-gagenrechner' ); ?></div></div>
			</div>
		</aside>
	</div>

	<div class="src-modal-backdrop" data-sgk-offer-modal hidden aria-hidden="true">
		<div class="src-modal-content" role="dialog" aria-modal="true" aria-labelledby="sgk-offer-modal-title" tabindex="-1">
			<div class="src-modal-header">
				<div>
					<p class="src-page-eyebrow"><?php esc_html_e( 'Angebot vorbereiten', 'sprecher-gagenrechner' ); ?></p>
					<h3 id="sgk-offer-modal-title"><?php esc_html_e( 'Angebotsangaben vor dem PDF-Export prüfen', 'sprecher-gagenrechner' ); ?></h3>
					<p><?php esc_html_e( 'Ergänze nur die Angaben, die ins Angebot sollen. Die Vorschau aktualisiert sich direkt.', 'sprecher-gagenrechner' ); ?></p>
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
					<div class="src-form-field"><label for="sgk-offer-internal"><?php esc_html_e( 'Interne Notiz', 'sprecher-gagenrechner' ); ?></label><textarea id="sgk-offer-internal" rows="3" class="src-input-text" data-sgk-offer-meta="internal_note"></textarea><p class="src-field-hint" data-sgk-offer-status><?php esc_html_e( 'Für den Export ist am besten bereits eine finale Angebotssumme hinterlegt.', 'sprecher-gagenrechner' ); ?></p></div>
				</section>
				<section class="src-modal-panel"><div class="src-offer-preview-shell" data-sgk-offer-preview></div></section>
			</div>
			<div class="src-modal-footer"><button type="button" class="src-btn-secondary" data-feedback-label="Mailtext kopiert" data-sgk-offer-action="copy-mail"><?php esc_html_e( 'Angebotstext kopieren', 'sprecher-gagenrechner' ); ?></button><button type="button" class="src-btn-primary" data-feedback-label="Druckdialog geöffnet" data-sgk-offer-action="print"><?php esc_html_e( 'PDF drucken oder speichern', 'sprecher-gagenrechner' ); ?></button></div>
		</div>
	</div>
</div>
