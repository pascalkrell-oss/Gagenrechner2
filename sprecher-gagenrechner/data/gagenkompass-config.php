<?php
/**
 * Central business config.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$money_range = static function ( $lower, $mid, $upper ) {
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
			'group'                 => '',
			'case_key'              => '',
			'label'                 => '',
			'title'                 => '',
			'description'           => '',
			'pricing_mode'          => 'range',
			'unit_type'             => 'project',
			'quantity_mode'         => 'project',
			'units_definition'      => array(),
			'allowed_variants'      => array(),
			'allowed_media'         => array(),
			'allowed_territories'   => array(),
			'allowed_durations'     => array(),
			'allowed_usage_modes'   => array(),
			'pricing'               => array(),
			'range_values'          => array(),
			'duration_rules'        => array(),
			'territory_rules'       => array(),
			'media_rules'           => array(),
			'volume_rules'          => array(),
			'validation_rules'      => array(),
			'surcharge_rules'       => array(),
			'additive_rules'        => array(),
			'multiplier_rules'      => array(),
			'minimum_fee_rules'     => array(),
			'package_rules'         => array(),
			'unlimited_usage_rules' => array(),
			'session_fee_rules'     => array(),
			'addon_rules'           => array(),
			'redirect_rules'        => array(),
			'expert_mode_available' => true,
			'expert_options'        => array(),
			'notes'                 => array(),
			'legal_notes'           => array(),
			'breakdown_schema'      => array(),
			'export_schema'         => $export_schema,
			'calculation'           => array(),
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
				'group'        => 'werbung_mit_bild',
				'case_key'     => 'werbung_mit_bild',
				'label'        => 'Werbung mit Bild',
				'title'        => 'Werbung mit Bild',
				'description'  => 'Paid-Media- und Werbeformen mit Bildträger, Reminder-, Archiv- und Layoutlogik.',
				'pricing_mode' => 'variant_range_with_addons',
				'unit_type'    => 'spot',
				'quantity_mode'=> 'motif',
				'units_definition' => array(
					'default_unit_label' => 'Spot / Motiv',
					'supported_units'    => array( 'spot', 'patronat', 'layout' ),
				),
				'allowed_variants' => array(
					'online_video_paid_media',
					'atv_ctv_video_spot',
					'linear_tv_spot_national',
					'linear_tv_spot_regional',
					'tv_patronat',
					'atv_ctv_patronat',
					'kino_spot_national',
					'kino_spot_regional',
					'pos_event_messe',
					'reminder',
					'layout_animatic_moodfilm_scribble',
				),
				'allowed_media' => array( 'tv', 'ctv', 'online_video', 'kino', 'pos', 'event', 'messe' ),
				'allowed_territories' => array( 'regional', 'de', 'dach', 'eu', 'weltweit' ),
				'allowed_durations' => array( '1_jahr', '2_jahre', 'archiv', 'unbegrenzt' ),
				'allowed_usage_modes' => array( 'paid_advertising', 'patronat', 'layout', 'archive', 'reminder' ),
				'pricing' => array(
					'variants' => array(
						'online_video_paid_media'          => $money_range( 850, 1150, 1450 ),
						'atv_ctv_video_spot'               => $money_range( 1050, 1450, 1800 ),
						'linear_tv_spot_national'          => $money_range( 1400, 1850, 2300 ),
						'linear_tv_spot_regional'          => $money_range( 980, 1320, 1680 ),
						'tv_patronat'                      => $money_range( 620, 850, 1100 ),
						'atv_ctv_patronat'                 => $money_range( 560, 780, 1020 ),
						'kino_spot_national'               => $money_range( 1250, 1700, 2200 ),
						'kino_spot_regional'               => $money_range( 880, 1180, 1480 ),
						'pos_event_messe'                  => $money_range( 520, 760, 980 ),
						'reminder'                         => $money_range( 450, 650, 850 ),
						'layout_animatic_moodfilm_scribble'=> $money_range( 600, 900, 1200 ),
					),
				),
				'range_values' => array(
					'variants' => array(
						'online_video_paid_media'          => $money_range( 850, 1150, 1450 ),
						'atv_ctv_video_spot'               => $money_range( 1050, 1450, 1800 ),
						'linear_tv_spot_national'          => $money_range( 1400, 1850, 2300 ),
						'linear_tv_spot_regional'          => $money_range( 980, 1320, 1680 ),
						'tv_patronat'                      => $money_range( 620, 850, 1100 ),
						'atv_ctv_patronat'                 => $money_range( 560, 780, 1020 ),
						'kino_spot_national'               => $money_range( 1250, 1700, 2200 ),
						'kino_spot_regional'               => $money_range( 880, 1180, 1480 ),
						'pos_event_messe'                  => $money_range( 520, 760, 980 ),
						'reminder'                         => $money_range( 450, 650, 850 ),
						'layout_animatic_moodfilm_scribble'=> $money_range( 600, 900, 1200 ),
					),
				),
				'duration_rules' => array( 'default_term' => '1_jahr', 'options' => array( '1_jahr', '2_jahre', 'archiv', 'unbegrenzt' ) ),
				'territory_rules' => array( 'default' => 'de', 'options' => array( 'regional', 'de', 'dach', 'eu', 'weltweit' ) ),
				'media_rules' => array( 'default' => array( 'tv', 'ctv', 'online_video', 'kino', 'pos' ) ),
				'validation_rules' => array(
					'required' => array( 'case_variant' ),
					'allowed_variant_field' => 'case_variant',
					'numeric_ranges' => array(
						'additional_year'      => array( 'min' => 0, 'max' => 10 ),
						'additional_territory' => array( 'min' => 0, 'max' => 10 ),
						'additional_motif'     => array( 'min' => 0, 'max' => 20 ),
					),
				),
				'additive_rules' => array(
					'additional_year'      => array( 'mode' => 'base_percent', 'percentage' => $money_range( 1, 1, 1 ), 'label' => 'Zusatzjahr' ),
					'additional_territory' => array( 'mode' => 'base_percent', 'percentage' => $money_range( 1, 1, 1 ), 'label' => 'Zusatzterritorium' ),
					'additional_motif'     => array( 'mode' => 'base_percent', 'percentage' => $money_range( 1, 1, 1 ), 'label' => 'Zusatzmotiv' ),
					'reminder'             => array( 'mode' => 'base_percent', 'percentage' => $money_range( 0.30, 0.35, 0.40 ), 'label' => 'Reminder' ),
					'archivgage'           => array( 'mode' => 'base_percent', 'percentage' => $money_range( 0.35, 0.40, 0.50 ), 'label' => 'Archivgage', 'allowed_variants' => array( 'online_video_paid_media', 'atv_ctv_video_spot', 'linear_tv_spot_national', 'linear_tv_spot_regional', 'kino_spot_national', 'kino_spot_regional', 'pos_event_messe' ) ),
				),
				'package_rules' => array(
					'dach_paket'     => array( 'label' => 'DACH Paket', 'multiplier' => 1.70, 'variants' => array( 'linear_tv_spot_national', 'online_video_paid_media' ) ),
					'online_atv_ctv' => array( 'label' => 'Online & ATV/CTV', 'multiplier' => 1.80, 'variants' => array( 'online_video_paid_media', 'atv_ctv_video_spot' ) ),
				),
				'unlimited_usage_rules' => array(
					'allowed' => true,
					'allowed_variants' => array( 'online_video_paid_media', 'atv_ctv_video_spot', 'linear_tv_spot_national', 'linear_tv_spot_regional', 'kino_spot_national', 'kino_spot_regional' ),
					'time_multiplier' => 3,
					'territory_multiplier' => 4,
					'media_multiplier' => 4,
				),
				'session_fee_rules' => array( 'allowed' => true ),
				'follow_up_credit_rules' => array(
					'allowed' => true,
					'allowed_variants' => array( 'online_video_paid_media', 'atv_ctv_video_spot', 'linear_tv_spot_national', 'linear_tv_spot_regional', 'kino_spot_national', 'kino_spot_regional', 'pos_event_messe', 'tv_patronat', 'atv_ctv_patronat' ),
				),
				'expert_options' => array( 'sonderverhandlung', 'rabatte', 'pakete', 'unbegrenzte_nutzung', 'session_fee', 'layout_nachgage' ),
				'notes' => array(
					'Archivgage ist keine normale Jahresverlängerung, sondern eine separate Lizenz für passende Paid-Media-Fälle.',
					'Layoutgagen sind interne Vorstufen und können später einmalig angerechnet werden.',
				),
				'legal_notes' => array( 'Bei Paid-Media-Werbung sind Rechteumfang, Laufzeit, Medium und Gebiet gesondert im Angebot auszuweisen.' ),
				'breakdown_schema' => array( 'base', 'additive', 'credit', 'minimum_fee_adjustment', 'notes' ),
			)
		),
		'werbung_ohne_bild' => $case_builder(
			array(
				'id'           => 'case_werbung_ohne_bild',
				'family'       => 'werbung',
				'group'        => 'werbung_ohne_bild',
				'case_key'     => 'werbung_ohne_bild',
				'label'        => 'Werbung ohne Bild',
				'title'        => 'Werbung ohne Bild',
				'description'  => 'Paid-Audio-Werbung mit Reminder-, Allongen- und Zusatzrechtslogik.',
				'pricing_mode' => 'variant_range_with_addons',
				'unit_type'    => 'spot',
				'quantity_mode'=> 'motif',
				'allowed_variants' => array(
					'online_audio_paid_media',
					'funk_spot_national',
					'funk_spot_regional',
					'funk_spot_lokal',
					'ladenfunk',
					'telefon_werbespot',
					'reminder',
				),
				'allowed_media' => array( 'radio', 'online_audio', 'ladenfunk', 'telefon' ),
				'allowed_territories' => array( 'lokal', 'regional', 'de', 'dach' ),
				'allowed_durations' => array( '1_jahr', '2_jahre', 'unbegrenzt' ),
				'allowed_usage_modes' => array( 'paid_advertising', 'reminder' ),
				'pricing' => array(
					'variants' => array(
						'online_audio_paid_media' => $money_range( 520, 760, 980 ),
						'funk_spot_national'      => $money_range( 720, 980, 1260 ),
						'funk_spot_regional'      => $money_range( 380, 520, 720 ),
						'funk_spot_lokal'         => $money_range( 160, 240, 320 ),
						'ladenfunk'               => $money_range( 430, 600, 810 ),
						'telefon_werbespot'       => $money_range( 260, 380, 520 ),
						'reminder'                => $money_range( 180, 240, 320 ),
					),
				),
				'range_values' => array(
					'variants' => array(
						'online_audio_paid_media' => $money_range( 520, 760, 980 ),
						'funk_spot_national'      => $money_range( 720, 980, 1260 ),
						'funk_spot_regional'      => $money_range( 380, 520, 720 ),
						'funk_spot_lokal'         => $money_range( 160, 240, 320 ),
						'ladenfunk'               => $money_range( 430, 600, 810 ),
						'telefon_werbespot'       => $money_range( 260, 380, 520 ),
						'reminder'                => $money_range( 180, 240, 320 ),
					),
				),
				'duration_rules' => array( 'default_term' => '1_jahr', 'options' => array( '1_jahr', '2_jahre', 'unbegrenzt' ) ),
				'territory_rules' => array( 'default' => 'de', 'options' => array( 'lokal', 'regional', 'de', 'dach' ) ),
				'media_rules' => array( 'default' => array( 'radio', 'online_audio', 'ladenfunk', 'telefon' ) ),
				'validation_rules' => array(
					'required' => array( 'case_variant' ),
					'allowed_variant_field' => 'case_variant',
					'numeric_ranges' => array(
						'additional_year'      => array( 'min' => 0, 'max' => 10 ),
						'additional_territory' => array( 'min' => 0, 'max' => 10 ),
						'additional_motif'     => array( 'min' => 0, 'max' => 20 ),
					),
				),
				'additive_rules' => array(
					'additional_year'      => array( 'mode' => 'base_percent', 'percentage' => $money_range( 1, 1, 1 ), 'label' => 'Zusatzjahr' ),
					'additional_territory' => array( 'mode' => 'base_percent', 'percentage' => $money_range( 1, 1, 1 ), 'label' => 'Zusatzterritorium' ),
					'additional_motif'     => array( 'mode' => 'base_percent', 'percentage' => $money_range( 1, 1, 1 ), 'label' => 'Zusatzmotiv' ),
					'reminder'             => array( 'mode' => 'base_percent', 'percentage' => $money_range( 0.25, 0.30, 0.35 ), 'label' => 'Reminder', 'allowed_variants' => array( 'online_audio_paid_media', 'funk_spot_national', 'funk_spot_regional', 'funk_spot_lokal', 'ladenfunk', 'telefon_werbespot' ) ),
					'allongen'             => array( 'mode' => 'base_percent', 'percentage' => $money_range( 0.30, 0.35, 0.40 ), 'label' => 'Allongen', 'allowed_variants' => array( 'online_audio_paid_media', 'funk_spot_national', 'funk_spot_regional', 'funk_spot_lokal', 'ladenfunk', 'telefon_werbespot' ) ),
				),
				'package_rules' => array(
					'funk_online' => array( 'label' => 'Funk & Online', 'multiplier' => 1.55, 'variants' => array( 'funk_spot_national', 'online_audio_paid_media' ) ),
				),
				'unlimited_usage_rules' => array(
					'allowed' => true,
					'allowed_variants' => array( 'online_audio_paid_media', 'funk_spot_national', 'funk_spot_regional', 'funk_spot_lokal', 'ladenfunk', 'telefon_werbespot' ),
					'time_multiplier' => 3,
					'territory_multiplier' => 4,
					'media_multiplier' => 4,
				),
				'follow_up_credit_rules' => array(
					'allowed' => true,
					'allowed_variants' => array( 'online_audio_paid_media', 'funk_spot_national', 'funk_spot_regional', 'funk_spot_lokal', 'ladenfunk', 'telefon_werbespot' ),
				),
				'notes' => array( 'Reine Telefonansagen gehören nicht in diesen Block und werden gesondert behandelt.' ),
				'breakdown_schema' => array( 'base', 'additive', 'credit', 'notes' ),
			)
		),
		'webvideo_imagefilm_praesentation_unpaid' => $case_builder(
			array(
				'id' => 'case_webvideo_imagefilm',
				'family' => 'corporate_unpaid',
				'group' => 'webvideo_imagefilm',
				'case_key' => 'webvideo_imagefilm_praesentation_unpaid',
				'label' => 'Webvideo / Imagefilm / Präsentation (unpaid)',
				'title' => 'Webvideo / Imagefilm / Präsentation (unpaid)',
				'description' => 'Minutenbasierte Unpaid-Lizenzlogik mit Zusatzlizenzen für Social Media und Präsentation.',
				'pricing_mode' => 'tiered_minutes',
				'unit_type' => 'minute',
				'quantity_mode' => 'minute',
				'allowed_usage_modes' => array( 'corporate_unpaid', 'organic_branding' ),
				'pricing' => array(
					'tiers' => array(
						'bis_2_min' => array( 'up_to' => 2, 'amount' => $money_range( 320, 480, 680 ) ),
						'bis_5_min' => array( 'up_to' => 5, 'amount' => $money_range( 520, 760, 980 ) ),
						'je_weitere_5' => array( 'block' => 5, 'amount' => $money_range( 190, 280, 380 ) ),
					),
					'usage_addons' => array(
						'social_media' => $money_range( 120, 180, 260 ),
						'praesentation' => $money_range( 90, 140, 210 ),
						'awardfilm' => $money_range( 160, 220, 300 ),
						'casefilm' => $money_range( 160, 220, 300 ),
						'mitarbeiterfilm' => $money_range( 100, 150, 220 ),
					),
				),
				'range_values' => array(
					'tiers' => array(
						'bis_2_min' => array( 'up_to' => 2, 'amount' => $money_range( 320, 480, 680 ) ),
						'bis_5_min' => array( 'up_to' => 5, 'amount' => $money_range( 520, 760, 980 ) ),
						'je_weitere_5' => array( 'block' => 5, 'amount' => $money_range( 190, 280, 380 ) ),
					),
					'usage_addons' => array(
						'social_media' => $money_range( 120, 180, 260 ),
						'praesentation' => $money_range( 90, 140, 210 ),
						'awardfilm' => $money_range( 160, 220, 300 ),
						'casefilm' => $money_range( 160, 220, 300 ),
						'mitarbeiterfilm' => $money_range( 100, 150, 220 ),
					),
				),
				'validation_rules' => array(
					'numeric_ranges' => array( 'duration_minutes' => array( 'min' => 1, 'max' => 600 ) ),
				),
				'minimum_fee_rules' => array( 'minimum_totals' => $money_range( 320, 480, 680 ) ),
				'notes' => array( 'Sobald Paid Media, Sponsoring, POS oder echte Werbenutzung vorliegt, muss der Resolver in Werbelogik umleiten.' ),
				'breakdown_schema' => array( 'base', 'additive', 'minimum_fee_adjustment', 'notes' ),
			)
		),
		'app' => $case_builder(
			array(
				'id' => 'case_app', 'family' => 'digital_product', 'group' => 'app', 'case_key' => 'app', 'label' => 'App', 'title' => 'App',
				'description' => 'Minutenstaffel pro App mit typischer zeitlich unbegrenzter Standardnutzung.',
				'pricing_mode' => 'tiered_minutes', 'unit_type' => 'minute', 'quantity_mode' => 'minute',
				'allowed_durations' => array( 'zeitlich_unbegrenzt' ),
				'pricing' => array(
					'tiers' => array(
						'bis_2_min' => array( 'up_to' => 2, 'amount' => $money_range( 240, 340, 460 ) ),
						'bis_5_min' => array( 'up_to' => 5, 'amount' => $money_range( 390, 540, 720 ) ),
						'je_weitere_5' => array( 'block' => 5, 'amount' => $money_range( 150, 220, 320 ) ),
					),
				),
				'range_values' => array(
					'tiers' => array(
						'bis_2_min' => array( 'up_to' => 2, 'amount' => $money_range( 240, 340, 460 ) ),
						'bis_5_min' => array( 'up_to' => 5, 'amount' => $money_range( 390, 540, 720 ) ),
						'je_weitere_5' => array( 'block' => 5, 'amount' => $money_range( 150, 220, 320 ) ),
					),
				),
				'validation_rules' => array(
					'numeric_ranges' => array( 'duration_minutes' => array( 'min' => 1, 'max' => 600 ) ),
				),
				'legal_notes' => array( 'Hinweis: Die App-Kalkulation beinhaltet keine Freigabe für TTS-/synthetische Weiterverwertung.' ),
			)
		),
		'telefonansage' => $case_builder(
			array(
				'id' => 'case_telefonansage', 'family' => 'service_audio', 'group' => 'telefonansage', 'case_key' => 'telefonansage', 'label' => 'Telefonansage', 'title' => 'Telefonansage',
				'description' => 'Modulbasierte IVR-/Hotline-Logik.', 'pricing_mode' => 'modules', 'unit_type' => 'module', 'quantity_mode' => 'module',
				'pricing' => array( 'base' => $money_range( 180, 260, 360 ), 'extra_module' => $money_range( 40, 60, 90 ) ),
				'range_values' => array( 'bis_3_module' => $money_range( 180, 260, 360 ), 'je_weiteres' => $money_range( 40, 60, 90 ) ),
				'validation_rules' => array( 'numeric_ranges' => array( 'module_count' => array( 'min' => 1, 'max' => 250 ) ) ),
				'notes' => array( 'Maximal 30 Sekunden pro Modul als fachlicher Richtwert vorbereitend berücksichtigt.' ),
			)
		),
		'elearning_audioguide' => $case_builder(
			array(
				'id' => 'case_elearning', 'family' => 'education', 'group' => 'education', 'case_key' => 'elearning_audioguide', 'label' => 'E-Learning / Audioguide', 'title' => 'E-Learning / Audioguide',
				'description' => 'Minutenstaffeln für interne E-Learnings und Audioguides.', 'pricing_mode' => 'variant_tiered_minutes', 'unit_type' => 'minute', 'quantity_mode' => 'minute',
				'allowed_variants' => array( 'elearning_intern', 'audioguide' ),
				'pricing' => array(
					'variants' => array(
						'elearning_intern' => array( 'bis_5_min' => $money_range( 240, 340, 460 ), 'je_weitere_5' => $money_range( 90, 130, 180 ) ),
						'audioguide' => array( 'bis_5_min' => $money_range( 260, 370, 500 ), 'je_weitere_5' => $money_range( 100, 145, 200 ) ),
					),
				),
				'range_values' => array(
					'variants' => array(
						'elearning_intern' => array( 'bis_5_min' => $money_range( 240, 340, 460 ), 'je_weitere_5' => $money_range( 90, 130, 180 ) ),
						'audioguide' => array( 'bis_5_min' => $money_range( 260, 370, 500 ), 'je_weitere_5' => $money_range( 100, 145, 200 ) ),
					),
				),
				'validation_rules' => array(
					'required' => array( 'case_variant' ),
					'allowed_variant_field' => 'case_variant',
					'numeric_ranges' => array( 'duration_minutes' => array( 'min' => 1, 'max' => 1200 ) ),
				),
				'notes' => array( 'Anzahl Filme oder Module kann zusätzlich relevant werden und wird als Hinweis im Ergebnis aufgeführt.' ),
			)
		),
		'podcast' => $case_builder(
			array(
				'id' => 'case_podcast', 'family' => 'podcast', 'group' => 'podcast', 'case_key' => 'podcast', 'label' => 'Podcast', 'title' => 'Podcast',
				'description' => 'Podcast-Inhalte und nicht-/marketingkommerzielle Verpackung.', 'pricing_mode' => 'podcast', 'unit_type' => 'episode', 'quantity_mode' => 'episode',
				'allowed_variants' => array( 'podcast_inhalte', 'non_commercial_3', 'non_commercial_unlim', 'marketing_3', 'marketing_unlim' ),
				'pricing' => array(
					'content' => array( 'bis_5_min' => $money_range( 180, 260, 360 ), 'je_weitere_5' => $money_range( 70, 100, 150 ) ),
					'packaging' => array(
						'non_commercial_3' => $money_range( 280, 390, 520 ),
						'non_commercial_unlim' => $money_range( 420, 580, 760 ),
						'marketing_3' => $money_range( 420, 620, 840 ),
						'marketing_unlim' => $money_range( 680, 940, 1240 ),
					),
				),
				'range_values' => array(
					'content' => array( 'bis_5_min' => $money_range( 180, 260, 360 ), 'je_weitere_5' => $money_range( 70, 100, 150 ) ),
					'packaging' => array(
						'non_commercial_3' => $money_range( 280, 390, 520 ),
						'non_commercial_unlim' => $money_range( 420, 580, 760 ),
						'marketing_3' => $money_range( 420, 620, 840 ),
						'marketing_unlim' => $money_range( 680, 940, 1240 ),
					),
				),
				'validation_rules' => array( 'numeric_ranges' => array( 'duration_minutes' => array( 'min' => 1, 'max' => 600 ) ) ),
				'variant_visibility_rules' => array(
					'podcast_inhalte'      => array( 'show_blocks' => array( 'duration_minutes' ), 'required' => array( 'duration_minutes' ) ),
					'non_commercial_3'     => array( 'show_blocks' => array(), 'required' => array() ),
					'non_commercial_unlim' => array( 'show_blocks' => array(), 'required' => array() ),
					'marketing_3'          => array( 'show_blocks' => array(), 'required' => array() ),
					'marketing_unlim'      => array( 'show_blocks' => array(), 'required' => array() ),
				),
				'notes' => array( 'Video-Podcasts werden fachlich in den unpaid Bewegtbildbereich umgeleitet, solange keine aktive Werbenutzung vorliegt.' ),
			)
		),
		'hoerbuch' => $case_builder(
			array(
				'id' => 'case_hoerbuch', 'family' => 'longform', 'group' => 'hoerbuch', 'case_key' => 'hoerbuch', 'label' => 'Hörbuch', 'title' => 'Hörbuch',
				'description' => 'Vorschlagskalkulation je Final Audio Hour (FAH), finale Konditionen bleiben verhandlungs- und lizenzabhängig.', 'pricing_mode' => 'per_fah_suggestive', 'unit_type' => 'fah', 'quantity_mode' => 'fah',
				'pricing' => array( 'per_fah' => $money_range( 260, 340, 460 ) ),
				'range_values' => array( 'per_fah' => $money_range( 260, 340, 460 ) ),
				'validation_rules' => array( 'numeric_ranges' => array( 'fah' => array( 'min' => 1, 'max' => 500 ) ) ),
				'expert_options' => array( 'sonderverhandlung', 'session_fee', 'individuelle_einschaetzung' ),
				'notes' => array( 'Hörbuchergebnisse sind als Vorschlagskalkulation zu verstehen.' ),
				'legal_notes' => array( 'Lizenzumfang, Gewinnbeteiligung, Buyout-Regelungen und Exklusivität können den finalen Preis erheblich beeinflussen.' ),
			)
		),
		'games' => $case_builder(
			array(
				'id' => 'case_games', 'family' => 'interactive', 'group' => 'games', 'case_key' => 'games', 'label' => 'Games', 'title' => 'Games',
				'description' => 'Session- und Projektlogik für Games mit wiederkehrender erster Stunde.', 'pricing_mode' => 'games_sessions', 'unit_type' => 'hour', 'quantity_mode' => 'hour',
				'pricing' => array( 'erste_stunde' => $money_range( 320, 450, 620 ), 'folgestunde' => $money_range( 180, 250, 340 ) ),
				'range_values' => array( 'erste_stunde' => $money_range( 320, 450, 620 ), 'folgestunde' => $money_range( 180, 250, 340 ) ),
				'validation_rules' => array(
					'numeric_ranges' => array(
						'recording_hours' => array( 'min' => 1, 'max' => 24 ),
						'recording_days' => array( 'min' => 1, 'max' => 30 ),
						'same_day_projects' => array( 'min' => 1, 'max' => 20 ),
					),
				),
				'notes' => array( 'Die erste Stunde fällt an jedem weiteren Aufnahmetag und bei jedem weiteren Projekt am selben Tag erneut an.' ),
			)
		),
		'redaktionell_doku_tv_reportage' => $case_builder(
			array(
				'id' => 'case_redaktionell', 'family' => 'editorial', 'group' => 'redaktionell', 'case_key' => 'redaktionell_doku_tv_reportage', 'label' => 'Redaktionelle Inhalte / Doku / TV-Reportage', 'title' => 'Redaktionelle Inhalte / Doku / TV-Reportage',
				'description' => 'Minutensatz mit transparenter Mindestgage.', 'pricing_mode' => 'per_minute_with_minimum', 'unit_type' => 'netto_sendeminute', 'quantity_mode' => 'minute',
				'allowed_variants' => array( 'kommentarstimme', 'overvoice' ),
				'pricing' => array(
					'variants' => array(
						'kommentarstimme' => array( 'per_minute' => $money_range( 26, 34, 46 ), 'minimum' => $money_range( 180, 220, 280 ) ),
						'overvoice' => array( 'per_minute' => $money_range( 20, 28, 36 ), 'minimum' => $money_range( 160, 200, 250 ) ),
					),
				),
				'range_values' => array(
					'variants' => array(
						'kommentarstimme' => array( 'per_minute' => $money_range( 26, 34, 46 ), 'minimum' => $money_range( 180, 220, 280 ) ),
						'overvoice' => array( 'per_minute' => $money_range( 20, 28, 36 ), 'minimum' => $money_range( 160, 200, 250 ) ),
					),
				),
				'validation_rules' => array(
					'required' => array( 'case_variant' ),
					'allowed_variant_field' => 'case_variant',
					'numeric_ranges' => array( 'net_minutes' => array( 'min' => 1, 'max' => 2000 ) ),
				),
				'minimum_fee_rules' => array( 'enabled' => true ),
			)
		),
		'audiodeskription' => $case_builder(
			array(
				'id' => 'case_audiodeskription', 'family' => 'barrierefreiheit', 'group' => 'audiodeskription', 'case_key' => 'audiodeskription', 'label' => 'Audiodeskription', 'title' => 'Audiodeskription',
				'description' => 'Minutensatz mit Mindestgage für AD-Produktionen.', 'pricing_mode' => 'per_minute_with_minimum', 'unit_type' => 'netto_sendeminute', 'quantity_mode' => 'minute',
				'allowed_variants' => array( 'audiodeskription' ),
				'pricing' => array( 'variants' => array( 'audiodeskription' => array( 'per_minute' => $money_range( 24, 32, 42 ), 'minimum' => $money_range( 170, 210, 270 ) ) ) ),
				'range_values' => array( 'variants' => array( 'audiodeskription' => array( 'per_minute' => $money_range( 24, 32, 42 ), 'minimum' => $money_range( 170, 210, 270 ) ) ) ),
				'validation_rules' => array( 'numeric_ranges' => array( 'net_minutes' => array( 'min' => 1, 'max' => 2000 ) ) ),
				'minimum_fee_rules' => array( 'enabled' => true ),
				'notes' => array( 'Overvoice-Einsätze innerhalb einer AD-Produktion können fachlich bereits von der Mindestgage abgedeckt sein.' ),
			)
		),
		'kleinraeumig' => $case_builder(
			array(
				'id' => 'case_kleinraeumig', 'family' => 'local_usage', 'group' => 'kleinraeumig', 'case_key' => 'kleinraeumig', 'label' => 'Kleinräumige Nutzung', 'title' => 'Kleinräumige Nutzung',
				'description' => 'Lokale/kleinräumige Auswertung für Funk und Paid-Video.', 'pricing_mode' => 'variant_range_with_addons', 'unit_type' => 'spot', 'quantity_mode' => 'motif',
				'allowed_variants' => array( 'funk_spot_lokal', 'kleinraeumiger_online_video_paid' ),
				'pricing' => array( 'variants' => array( 'funk_spot_lokal' => $money_range( 160, 240, 320 ), 'kleinraeumiger_online_video_paid' => $money_range( 260, 360, 520 ) ) ),
				'range_values' => array( 'variants' => array( 'funk_spot_lokal' => $money_range( 160, 240, 320 ), 'kleinraeumiger_online_video_paid' => $money_range( 260, 360, 520 ) ) ),
				'validation_rules' => array(
					'required' => array( 'case_variant' ),
					'allowed_variant_field' => 'case_variant',
					'numeric_ranges' => array( 'additional_year' => array( 'min' => 0, 'max' => 10 ), 'additional_motif' => array( 'min' => 0, 'max' => 20 ) ),
				),
				'additive_rules' => array(
					'additional_year' => array( 'mode' => 'base_percent', 'percentage' => $money_range( 1, 1, 1 ), 'label' => 'Zusatzjahr' ),
					'additional_motif' => array( 'mode' => 'base_percent', 'percentage' => $money_range( 1, 1, 1 ), 'label' => 'Zusatzmotiv' ),
				),
			)
		),
		'session_fee' => $case_builder(
			array(
				'id' => 'case_session_fee', 'family' => 'expert_module', 'group' => 'session_fee', 'case_key' => 'session_fee', 'label' => 'Session Fee', 'title' => 'Session Fee',
				'description' => 'Stundenpauschale ohne öffentliche Lizenz; spätere Auswertung separat.', 'pricing_mode' => 'session_fee', 'unit_type' => 'hour', 'quantity_mode' => 'hour',
				'pricing' => array( 'per_hour' => $money_range( 180, 260, 360 ) ),
				'range_values' => array( 'per_hour' => $money_range( 180, 260, 360 ) ),
				'validation_rules' => array( 'numeric_ranges' => array( 'session_hours' => array( 'min' => 1, 'max' => 24 ) ) ),
			)
		),
	),
	'redirect_rules' => array(
		array( 'id' => 'redirect_online_audio_spot_unpaid', 'source_case' => 'online_audio_spot_unpaid', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Online Audio Spot unpaid wird in den unpaid Webvideo-/Imagefilm-Fall überführt.', 'conditions' => array() ),
		array( 'id' => 'redirect_online_video_spot_unpaid', 'source_case' => 'online_video_spot_unpaid', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Online Video Spot unpaid wird dem unpaid Webvideo-/Imagefilm-Fall zugeordnet.', 'conditions' => array() ),
		array( 'id' => 'redirect_in_app_ads', 'source_case' => 'in_app_ads', 'target_case' => 'werbung_mit_bild', 'description' => 'In-App-Ads sind Werbung mit Bild.', 'conditions' => array() ),
		array( 'id' => 'redirect_telefon_werbespot', 'source_case' => 'telefon_werbespot', 'target_case' => 'werbung_ohne_bild', 'description' => 'Telefon-Werbespots laufen über Werbung ohne Bild.', 'conditions' => array() ),
		array( 'id' => 'redirect_marketing_elearning', 'source_case' => 'marketing_elearning', 'target_case' => 'webvideo_imagefilm_praesentation_unpaid', 'description' => 'Marketing-E-Learning wird in den unpaid Bewegtbildfall gelenkt, solange keine Paid-Media-Werbung aktiv ist.', 'conditions' => array( array( 'field' => 'is_paid_media', 'value' => '0' ) ) ),
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
		array( 'label' => 'Paid Spot mit Archivgage', 'input' => array( 'case_key' => 'werbung_mit_bild', 'case_variant' => 'linear_tv_spot_national', 'archivgage' => 1 ) ),
		array( 'label' => 'Layout -> spätere Nachverwertung', 'input' => array( 'case_key' => 'werbung_mit_bild', 'case_variant' => 'layout_animatic_moodfilm_scribble', 'follow_up_usage' => 1, 'prior_layout_fee' => 330 ) ),
	),
);
