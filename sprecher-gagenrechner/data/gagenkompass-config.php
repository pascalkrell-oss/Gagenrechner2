<?php
/**
 * Central business config for Phase 2.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$range = static function ( $lower, $mid, $upper ) {
	return array(
		'lower' => (float) $lower,
		'mid'   => (float) $mid,
		'upper' => (float) $upper,
	);
};

$export_schema = array(
	'summary_fields' => array( 'title', 'case_key', 'totals', 'manual_fee', 'recommendation_type' ),
	'positions'      => array( 'key', 'label', 'category', 'quantity', 'unit_label', 'lower', 'mid', 'upper', 'source_case', 'calculation_note', 'is_addon', 'is_redirected_logic', 'is_credit', 'export_label' ),
	'rights'         => array( 'territory_rules', 'media_rules', 'duration_rules', 'usage_notes' ),
	'alternatives'   => array( 'key', 'label', 'totals', 'line_items', 'notes' ),
	'credits'        => array( 'key', 'label', 'amounts', 'calculation_note' ),
);

$case_builder = static function ( array $overrides ) use ( $export_schema ) {
	return array_merge(
		array(
			'id'                    => '',
			'family'                => '',
			'case_key'              => '',
			'label'                 => '',
			'description'           => '',
			'pricing_mode'          => 'range',
			'unit_type'             => 'project',
			'units_definition'      => array(),
			'range_values'          => array(),
			'duration_rules'        => array(),
			'territory_rules'       => array(),
			'media_rules'           => array(),
			'volume_rules'          => array(),
			'addon_rules'           => array(),
			'redirect_rules'        => array(),
			'expert_mode_available' => true,
			'expert_options'        => array(),
			'notes'                 => array(),
			'legal_notes'           => array(),
			'breakdown_schema'      => array(),
			'export_schema'         => $export_schema,
			'calculation'           => array(),
			'package_rules'         => array(),
			'demo_inputs'           => array(),
		),
		$overrides
	);
};

return array(
	'cases' => array(
		'werbung_mit_bild' => $case_builder(
			array(
				'id'           => 'case_werbung_mit_bild',
				'family'       => 'werbung',
				'case_key'     => 'werbung_mit_bild',
				'label'        => 'Werbung mit Bild',
				'description'  => 'Paid-Media- und Werbeformen mit Bildträger, Reminder-, Archiv- und Layoutlogik.',
				'pricing_mode' => 'variant_range_with_addons',
				'unit_type'    => 'spot',
				'units_definition' => array(
					'default_unit_label' => 'Spot / Motiv',
					'supported_units'    => array( 'spot', 'patronat', 'layout' ),
				),
				'range_values' => array(
					'variants' => array(
						'online_video_paid_media' => $range( 850, 1150, 1450 ),
						'atv_ctv_video_spot'      => $range( 1050, 1450, 1800 ),
						'linear_tv_spot'          => $range( 1400, 1850, 2300 ),
						'linear_tv_reminder'      => $range( 450, 650, 850 ),
						'tv_patronat'             => $range( 620, 850, 1100 ),
						'atv_ctv_patronat'        => $range( 560, 780, 1020 ),
						'kino_spot'               => $range( 1250, 1700, 2200 ),
						'pos_spot'                => $range( 520, 760, 980 ),
						'animatic_narrative_moodfilm' => $range( 600, 900, 1200 ),
						'layout'                  => $range( 220, 330, 480 ),
					),
				),
				'duration_rules' => array( 'default_term' => '1_jahr', 'options' => array( '1_jahr', '2_jahre', 'archiv', 'unbegrenzt' ) ),
				'territory_rules' => array( 'default' => 'de', 'options' => array( 'de', 'dach', 'eu', 'weltweit' ) ),
				'media_rules' => array( 'default' => array( 'tv', 'ctv', 'online_video', 'kino', 'pos' ) ),
				'addon_rules' => array(
					'allow_additional_year'      => true,
					'allow_additional_territory' => true,
					'allow_additional_motif'     => true,
					'reminder_percentage'        => array( 'lower' => 0.30, 'mid' => 0.35, 'upper' => 0.40 ),
					'allow_archivgage'           => true,
					'archivgage_percentage'      => array( 'lower' => 0.35, 'mid' => 0.40, 'upper' => 0.50 ),
					'layout_credit_allowed'      => true,
				),
				'package_rules' => array(
					'dach_paket'       => array( 'label' => 'DACH Paket', 'multiplier' => 1.70, 'variants' => array( 'linear_tv_spot', 'online_video_paid_media' ) ),
					'online_atv_ctv'   => array( 'label' => 'Online & ATV/CTV', 'multiplier' => 1.80, 'variants' => array( 'online_video_paid_media', 'atv_ctv_video_spot' ) ),
					'funk_online'      => array( 'label' => 'Funk & Online', 'multiplier' => 1.65, 'variants' => array( 'online_video_paid_media' ) ),
				),
				'expert_options' => array( 'sonderverhandlung', 'rabatte', 'pakete', 'unbegrenzte_nutzung', 'session_fee', 'layout_nachgage' ),
				'notes' => array(
					'Archivgage ist keine normale Jahresverlängerung, sondern eine separate Lizenz für passende Paid-Media-Fälle.',
					'Layoutgagen sind intern und können später einmalig angerechnet werden.',
				),
				'legal_notes' => array( 'Bei Paid-Media-Werbung sind Rechteumfang, Laufzeit, Medium und Gebiet gesondert im Angebot auszuweisen.' ),
				'breakdown_schema' => array( 'base_license', 'addon_licenses', 'archiv', 'credits', 'alternatives' ),
				'demo_inputs' => array(
					array( 'label' => 'Online Video Spot Paid Media mit Zusatzjahr + Zusatzmotiv', 'input' => array( 'case_key' => 'werbung_mit_bild', 'case_variant' => 'online_video_paid_media', 'additional_year' => 1, 'additional_motif' => 1 ) ),
					array( 'label' => 'Paid Spot mit Archivgage', 'input' => array( 'case_key' => 'werbung_mit_bild', 'case_variant' => 'linear_tv_spot', 'archivgage' => 1 ) ),
				),
			)
		),
		'werbung_ohne_bild' => $case_builder(
			array(
				'id'           => 'case_werbung_ohne_bild',
				'family'       => 'werbung',
				'case_key'     => 'werbung_ohne_bild',
				'label'        => 'Werbung ohne Bild',
				'description'  => 'Paid-Audio-Werbung mit Reminder-, Allongen- und Layoutlogik.',
				'pricing_mode' => 'variant_range_with_addons',
				'unit_type'    => 'spot',
				'range_values' => array(
					'variants' => array(
						'online_audio_paid_media' => $range( 520, 760, 980 ),
						'funk_spot_national'      => $range( 720, 980, 1260 ),
						'funk_spot_regional'      => $range( 380, 520, 720 ),
						'funk_reminder'           => $range( 180, 240, 320 ),
						'funk_allongen'           => $range( 210, 290, 390 ),
						'ladenfunk_national'      => $range( 430, 600, 810 ),
						'ladenfunk_regional'      => $range( 260, 360, 480 ),
						'telefon_werbespot'       => $range( 260, 380, 520 ),
						'layout'                  => $range( 180, 250, 340 ),
					),
				),
				'duration_rules' => array( 'default_term' => '1_jahr', 'options' => array( '1_jahr', '2_jahre', 'unbegrenzt' ) ),
				'territory_rules' => array( 'default' => 'de', 'options' => array( 'regional', 'de', 'dach' ) ),
				'media_rules' => array( 'default' => array( 'radio', 'online_audio', 'ladenfunk', 'telefon' ) ),
				'addon_rules' => array(
					'allow_additional_year'      => true,
					'allow_additional_territory' => true,
					'allow_additional_motif'     => true,
					'reminder_percentage'        => array( 'lower' => 0.25, 'mid' => 0.30, 'upper' => 0.35 ),
					'allongen_percentage'        => array( 'lower' => 0.30, 'mid' => 0.35, 'upper' => 0.40 ),
					'layout_credit_allowed'      => true,
				),
				'package_rules' => array(
					'funk_online' => array( 'label' => 'Funk & Online', 'multiplier' => 1.55, 'variants' => array( 'funk_spot_national', 'online_audio_paid_media' ) ),
				),
				'notes' => array( 'Reine Telefonansagen gehören nicht in diesen Block und werden gesondert behandelt.' ),
				'breakdown_schema' => array( 'base_license', 'audio_addons', 'credits', 'alternatives' ),
			)
		),
		'webvideo_imagefilm_praesentation_unpaid' => $case_builder(
			array(
				'id'           => 'case_webvideo_imagefilm',
				'family'       => 'corporate_unpaid',
				'case_key'     => 'webvideo_imagefilm_praesentation_unpaid',
				'label'        => 'Webvideo / Imagefilm / Präsentation (unpaid)',
				'description'  => 'Minutenbasierte Unpaid-Lizenzlogik mit Zusatzlizenzen für Social Media und Präsentation.',
				'pricing_mode' => 'tiered_minutes',
				'unit_type'    => 'minute',
				'range_values' => array(
					'tiers' => array(
						'bis_2_min'     => array( 'up_to' => 2, 'amount' => $range( 320, 480, 680 ) ),
						'bis_5_min'     => array( 'up_to' => 5, 'amount' => $range( 520, 760, 980 ) ),
						'je_weitere_5'  => array( 'block' => 5, 'amount' => $range( 190, 280, 380 ) ),
					),
					'usage_addons' => array(
						'social_media'   => $range( 120, 180, 260 ),
						'praesentation'  => $range( 90, 140, 210 ),
						'awardfilm'      => $range( 160, 220, 300 ),
						'casefilm'       => $range( 160, 220, 300 ),
						'mitarbeiterfilm'=> $range( 100, 150, 220 ),
					),
				),
				'notes' => array( 'Sobald Paid Media, Sponsoring, POS oder echte Werbenutzung vorliegt, muss der Resolver in Werbelogik umleiten.' ),
				'breakdown_schema' => array( 'minute_tiers', 'usage_addons' ),
				'demo_inputs' => array(
					array( 'label' => 'Imagefilm 7 Minuten + Social Media', 'input' => array( 'case_key' => 'webvideo_imagefilm_praesentation_unpaid', 'duration_minutes' => 7, 'usage_social_media' => 1 ) ),
				),
			)
		),
		'app' => $case_builder(
			array(
				'id' => 'case_app', 'family' => 'digital_product', 'case_key' => 'app', 'label' => 'App',
				'description' => 'Minutenstaffel pro App mit typischer zeitlich unbegrenzter Standardnutzung.',
				'pricing_mode' => 'tiered_minutes',
				'unit_type' => 'minute',
				'range_values' => array(
					'tiers' => array(
						'bis_2_min'    => array( 'up_to' => 2, 'amount' => $range( 240, 340, 460 ) ),
						'bis_5_min'    => array( 'up_to' => 5, 'amount' => $range( 390, 540, 720 ) ),
						'je_weitere_5' => array( 'block' => 5, 'amount' => $range( 150, 220, 320 ) ),
					),
				),
				'duration_rules' => array( 'default_term' => 'zeitlich_unbegrenzt', 'options' => array( 'zeitlich_unbegrenzt' ) ),
				'legal_notes' => array( 'Hinweis: Die App-Kalkulation beinhaltet keine Freigabe für TTS-/synthetische Weiterverwertung.' ),
			)
		),
		'telefonansage' => $case_builder(
			array(
				'id' => 'case_telefonansage', 'family' => 'service_audio', 'case_key' => 'telefonansage', 'label' => 'Telefonansage',
				'description' => 'Modulbasierte IVR-/Hotline-Logik.',
				'pricing_mode' => 'modules',
				'unit_type' => 'module',
				'range_values' => array(
					'bis_3_module'  => $range( 180, 260, 360 ),
					'je_weiteres'   => $range( 40, 60, 90 ),
				),
				'notes' => array( 'Maximal 30 Sekunden pro Modul als fachlicher Richtwert vorbereitend berücksichtigt.' ),
				'demo_inputs' => array(
					array( 'label' => 'Telefonanlage 5 Module', 'input' => array( 'case_key' => 'telefonansage', 'module_count' => 5 ) ),
				),
			)
		),
		'elearning_audioguide' => $case_builder(
			array(
				'id' => 'case_elearning', 'family' => 'education', 'case_key' => 'elearning_audioguide', 'label' => 'E-Learning / Audioguide',
				'description' => 'Minutenstaffeln für interne E-Learnings und Audioguides.',
				'pricing_mode' => 'variant_tiered_minutes',
				'unit_type' => 'minute',
				'range_values' => array(
					'variants' => array(
						'elearning_intern' => array(
							'bis_5_min'    => $range( 240, 340, 460 ),
							'je_weitere_5' => $range( 90, 130, 180 ),
						),
						'audioguide' => array(
							'bis_5_min'    => $range( 260, 370, 500 ),
							'je_weitere_5' => $range( 100, 145, 200 ),
						),
					),
				),
				'notes' => array( 'Anzahl Filme oder Module kann zusätzlich relevant werden und wird als Hinweis im Ergebnis aufgeführt.' ),
			)
		),
		'podcast' => $case_builder(
			array(
				'id' => 'case_podcast', 'family' => 'podcast', 'case_key' => 'podcast', 'label' => 'Podcast',
				'description' => 'Podcast-Inhalte und nicht-/marketingkommerzielle Verpackung.',
				'pricing_mode' => 'podcast',
				'unit_type' => 'episode',
				'range_values' => array(
					'content' => array(
						'bis_5_min'    => $range( 180, 260, 360 ),
						'je_weitere_5' => $range( 70, 100, 150 ),
					),
					'packaging' => array(
						'non_commercial_3'      => $range( 280, 390, 520 ),
						'non_commercial_unlim'  => $range( 420, 580, 760 ),
						'marketing_3'           => $range( 420, 620, 840 ),
						'marketing_unlim'       => $range( 680, 940, 1240 ),
					),
				),
				'notes' => array( 'Video-Podcasts werden fachlich in den unpaid Bewegtbildbereich umgeleitet, solange keine aktive Werbenutzung vorliegt.' ),
			)
		),
		'hoerbuch' => $case_builder(
			array(
				'id' => 'case_hoerbuch', 'family' => 'longform', 'case_key' => 'hoerbuch', 'label' => 'Hörbuch',
				'description' => 'Vorschlagskalkulation je Final Audio Hour (FAH), finale Konditionen bleiben verhandlungs- und lizenzabhängig.',
				'pricing_mode' => 'per_fah_suggestive',
				'unit_type' => 'fah',
				'range_values' => array( 'per_fah' => $range( 260, 340, 460 ) ),
				'expert_options' => array( 'sonderverhandlung', 'session_fee', 'individuelle_einschaetzung' ),
				'notes' => array( 'Hörbuchergebnisse sind als Vorschlagskalkulation zu verstehen.' ),
				'legal_notes' => array( 'Lizenzumfang, Gewinnbeteiligung, Buyout-Regelungen und Exklusivität können den finalen Preis erheblich beeinflussen.' ),
				'demo_inputs' => array(
					array( 'label' => 'Hörbuch 8 FAH', 'input' => array( 'case_key' => 'hoerbuch', 'fah' => 8 ) ),
				),
			)
		),
		'games' => $case_builder(
			array(
				'id' => 'case_games', 'family' => 'interactive', 'case_key' => 'games', 'label' => 'Games',
				'description' => 'Session- und Projektlogik für Games mit wiederkehrender erster Stunde.',
				'pricing_mode' => 'games_sessions',
				'unit_type' => 'hour',
				'range_values' => array(
					'erste_stunde' => $range( 320, 450, 620 ),
					'folgestunde'  => $range( 180, 250, 340 ),
				),
				'notes' => array( 'Die erste Stunde fällt an jedem weiteren Aufnahmetag und bei jedem weiteren Projekt am selben Tag erneut an.' ),
				'demo_inputs' => array(
					array( 'label' => 'Games 2 Tage, 2 Projekte am 2. Tag', 'input' => array( 'case_key' => 'games', 'recording_hours' => 4, 'recording_days' => 2, 'same_day_projects' => 2 ) ),
				),
			)
		),
		'redaktionell_doku_tv_reportage' => $case_builder(
			array(
				'id' => 'case_redaktionell', 'family' => 'editorial', 'case_key' => 'redaktionell_doku_tv_reportage', 'label' => 'Redaktionelle Inhalte / Doku / TV-Reportage',
				'description' => 'Minutensatz mit transparenter Mindestgage.',
				'pricing_mode' => 'per_minute_with_minimum',
				'unit_type' => 'netto_sendeminute',
				'range_values' => array(
					'variants' => array(
						'kommentarstimme' => array( 'per_minute' => $range( 26, 34, 46 ), 'minimum' => $range( 180, 220, 280 ) ),
						'overvoice'       => array( 'per_minute' => $range( 20, 28, 36 ), 'minimum' => $range( 160, 200, 250 ) ),
					),
				),
				'demo_inputs' => array(
					array( 'label' => 'Doku kurze Netto-Sendeminute unter Mindestgage', 'input' => array( 'case_key' => 'redaktionell_doku_tv_reportage', 'case_variant' => 'kommentarstimme', 'net_minutes' => 3 ) ),
				),
			)
		),
		'audiodeskription' => $case_builder(
			array(
				'id' => 'case_audiodeskription', 'family' => 'barrierefreiheit', 'case_key' => 'audiodeskription', 'label' => 'Audiodeskription',
				'description' => 'Minutensatz mit Mindestgage für AD-Produktionen.',
				'pricing_mode' => 'per_minute_with_minimum',
				'unit_type' => 'netto_sendeminute',
				'range_values' => array(
					'variants' => array(
						'audiodeskription' => array( 'per_minute' => $range( 24, 32, 42 ), 'minimum' => $range( 170, 210, 270 ) ),
					),
				),
				'notes' => array( 'Overvoice-Einsätze innerhalb einer AD-Produktion können fachlich bereits von der Mindestgage abgedeckt sein.' ),
			)
		),
		'kleinraeumig' => $case_builder(
			array(
				'id' => 'case_kleinraeumig', 'family' => 'local_usage', 'case_key' => 'kleinraeumig', 'label' => 'Kleinräumige Nutzung',
				'description' => 'Lokale/kleinräumige Auswertung für Funk und Paid-Video.',
				'pricing_mode' => 'variant_range_with_addons',
				'unit_type' => 'spot',
				'range_values' => array(
					'variants' => array(
						'lokaler_funkspot'                 => $range( 160, 240, 320 ),
						'kleinraeumiger_online_video_paid' => $range( 260, 360, 520 ),
					),
				),
				'addon_rules' => array(
					'allow_additional_year'      => true,
					'allow_additional_territory' => false,
					'allow_additional_motif'     => true,
				),
			)
		),
		'session_fee' => $case_builder(
			array(
				'id' => 'case_session_fee', 'family' => 'expert_module', 'case_key' => 'session_fee', 'label' => 'Session Fee',
				'description' => 'Stundenpauschale ohne öffentliche Lizenz; spätere Auswertung separat.',
				'pricing_mode' => 'session_fee',
				'unit_type' => 'hour',
				'range_values' => array( 'per_hour' => $range( 180, 260, 360 ) ),
			)
		),
		'unbegrenzte_nutzung' => $case_builder(
			array(
				'id' => 'case_unbegrenzte_nutzung', 'family' => 'expert_module', 'case_key' => 'unbegrenzte_nutzung', 'label' => 'Unbegrenzte Nutzung',
				'description' => 'Multiplikatorpfad für zeitlich/räumlich/medial unbegrenzte Nutzung.',
				'pricing_mode' => 'unlimited_multiplier',
				'unit_type' => 'override',
			)
		),
		'pakete' => $case_builder(
			array(
				'id' => 'case_pakete', 'family' => 'expert_module', 'case_key' => 'pakete', 'label' => 'Pakete',
				'description' => 'Alternativberechnung über definierte Paketpfade.',
				'pricing_mode' => 'package_alternative',
			)
		),
		'rabatte' => $case_builder(
			array(
				'id' => 'case_rabatte', 'family' => 'expert_module', 'case_key' => 'rabatte', 'label' => 'Rabatte',
				'description' => 'Vorbereitete Mengen- und Verhandlungsrabatte für Expertenmodus.',
				'pricing_mode' => 'discount_module',
			)
		),
	),
	'redirect_rules' => array(
		array( 'id' => 'redirect_online_audio_spot_unpaid', 'source_case' => 'online_audio_spot_unpaid', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Online Audio Spot unpaid wird in den unpaid Webvideo-/Imagefilm-Fall überführt.', 'conditions' => array() ),
		array( 'id' => 'redirect_online_video_spot_unpaid', 'source_case' => 'online_video_spot_unpaid', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Online Video Spot unpaid wird dem unpaid Webvideo-/Imagefilm-Fall zugeordnet.', 'conditions' => array() ),
		array( 'id' => 'redirect_in_app_ads', 'source_case' => 'in_app_ads', 'target_case' => 'werbung_mit_bild', 'description' => 'In-App-Ads sind Werbung mit Bild.', 'conditions' => array() ),
		array( 'id' => 'redirect_telefon_werbespot', 'source_case' => 'telefon_werbespot', 'target_case' => 'werbung_ohne_bild', 'description' => 'Telefon-Werbespots laufen über Werbung ohne Bild.', 'conditions' => array() ),
		array( 'id' => 'redirect_marketing_elearning', 'source_case' => 'marketing_elearning', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Marketing-E-Learning wird in Phase 2 in den unpaid Bewegtbildfall gelenkt, solange keine Paid-Media-Werbung aktiv ist.', 'conditions' => array( array( 'field' => 'is_paid_media', 'value' => '0' ) ) ),
		array( 'id' => 'redirect_oeffentliches_elearning', 'source_case' => 'oeffentliches_elearning', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Öffentliches E-Learning wird als unpaid Webvideo-/Präsentationslogik behandelt.', 'conditions' => array() ),
		array( 'id' => 'redirect_video_podcast', 'source_case' => 'video_podcast', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Video-Podcast ohne aktive Werbenutzung wird in 1.3 aufgelöst.', 'conditions' => array( array( 'field' => 'is_paid_media', 'value' => '0' ) ) ),
		array( 'id' => 'redirect_podcast_sponsoring_audio', 'source_case' => 'podcast_sponsoring_audio', 'target_case' => 'werbung_ohne_bild', 'description' => 'Audio-Sponsoring im Podcast ist Werbung ohne Bild.', 'conditions' => array() ),
		array( 'id' => 'redirect_podcast_sponsoring_video_ad', 'source_case' => 'podcast_sponsoring_video', 'target_case' => 'werbung_mit_bild', 'description' => 'Video-Sponsoring mit aktiver Werbenutzung ist Werbung mit Bild.', 'conditions' => array( array( 'field' => 'usage_type', 'value' => 'paid_advertising' ) ) ),
		array( 'id' => 'redirect_podcast_sponsoring_video_unpaid', 'source_case' => 'podcast_sponsoring_video', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Video-Sponsoring ohne aktive Paid-Werbung fällt in 1.3.', 'conditions' => array( array( 'field' => 'usage_type', 'value' => 'organic_branding' ) ) ),
		array( 'id' => 'redirect_podcast_packaging_audio', 'source_case' => 'werbliche_podcast_verpackung_audio', 'target_case' => 'werbung_ohne_bild', 'description' => 'Werbliche Audio-Podcast-Verpackung ist Werbung ohne Bild.', 'conditions' => array() ),
		array( 'id' => 'redirect_podcast_packaging_video_ad', 'source_case' => 'werbliche_podcast_verpackung_video', 'target_case' => 'werbung_mit_bild', 'description' => 'Werbliche Video-Podcast-Verpackung mit aktiver Werbenutzung ist Werbung mit Bild.', 'conditions' => array( array( 'field' => 'is_paid_media', 'value' => '1' ) ) ),
		array( 'id' => 'redirect_podcast_packaging_video_unpaid', 'source_case' => 'werbliche_podcast_verpackung_video', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Video-Verpackung ohne aktive Werbenutzung wird in 1.3 geführt.', 'conditions' => array( array( 'field' => 'is_paid_media', 'value' => '0' ) ) ),
		array( 'id' => 'redirect_lokaler_funkspot', 'source_case' => 'lokaler_funkspot', 'target_case' => 'kleinraeumig', 'description' => 'Lokaler Funkspot wird in den kleinräumigen Nutzungsfall umgeleitet.', 'conditions' => array() ),
		array( 'id' => 'redirect_werbliche_games_zusatznutzung', 'source_case' => 'werbliche_games_zusatznutzung', 'target_case' => 'werbung_mit_bild', 'description' => 'Werbliche Games-Zusatznutzung wird als Werbung mit Bild behandelt.', 'conditions' => array() ),
	),
	'ui_schema' => array(
		'core_sections' => array(
			'case_selector'   => array( 'label' => 'Nutzungsauswahl', 'description' => 'Bestimmt den fachlichen Ausgangsfall.' ),
			'core_parameters' => array( 'label' => 'Kernparameter', 'description' => 'Dauer, Varianten, Motive, Tage, Module, Rechte.' ),
			'expert_mode'     => array( 'label' => 'Expertenmodus', 'description' => 'Sonderverhandlung, Layout-/Nachgage, Pakete, Unlimited.' ),
			'result_sidebar'  => array( 'label' => 'Ergebnis', 'description' => 'Breakdown, Alternative Pakete, Credits und Exportdaten.' ),
		),
	),
	'export_defaults' => array(
		'notes_for_offer' => array(
			'Die finale Angebotssumme wird bewusst nicht automatisch festgesetzt und ist manuell innerhalb oder außerhalb der Empfehlung zu bestimmen.',
		),
		'legal_notice' => array(
			'Dieses Ergebnis ist eine fachliche Kalkulationshilfe und ersetzt keine vertragliche oder juristische Prüfung.',
		),
	),
	'demo_cases' => array(
		array( 'label' => 'Online Video Spot Paid Media mit Zusatzjahr + Zusatzmotiv', 'input' => array( 'case_key' => 'werbung_mit_bild', 'case_variant' => 'online_video_paid_media', 'additional_year' => 1, 'additional_motif' => 1 ) ),
		array( 'label' => 'Imagefilm 7 Minuten + Social Media', 'input' => array( 'case_key' => 'webvideo_imagefilm_praesentation_unpaid', 'duration_minutes' => 7, 'usage_social_media' => 1 ) ),
		array( 'label' => 'Telefonanlage 5 Module', 'input' => array( 'case_key' => 'telefonansage', 'module_count' => 5 ) ),
		array( 'label' => 'Hörbuch 8 FAH', 'input' => array( 'case_key' => 'hoerbuch', 'fah' => 8 ) ),
		array( 'label' => 'Games 2 Tage, 2 Projekte am 2. Tag', 'input' => array( 'case_key' => 'games', 'recording_hours' => 4, 'recording_days' => 2, 'same_day_projects' => 2 ) ),
		array( 'label' => 'Doku kurze Netto-Sendeminute unter Mindestgage', 'input' => array( 'case_key' => 'redaktionell_doku_tv_reportage', 'case_variant' => 'kommentarstimme', 'net_minutes' => 3 ) ),
		array( 'label' => 'Paid Spot mit Archivgage', 'input' => array( 'case_key' => 'werbung_mit_bild', 'case_variant' => 'linear_tv_spot', 'archivgage' => 1 ) ),
		array( 'label' => 'Layout -> spätere Nachverwertung', 'input' => array( 'case_key' => 'werbung_mit_bild', 'case_variant' => 'layout', 'follow_up_usage' => 1, 'prior_layout_fee' => 330 ) ),
	),
);
