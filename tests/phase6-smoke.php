<?php
declare(strict_types=1);

define( 'ABSPATH', __DIR__ );
define( 'SGK_PLUGIN_DIR', dirname( __DIR__ ) . '/sprecher-gagenrechner/' );

function __( $text ) {
	return $text;
}

function sanitize_key( $key ) {
	return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $key ) );
}

function sanitize_text_field( $value ) {
	return trim( (string) $value );
}

function number_format_i18n( $number, $decimals ) {
	return number_format( (float) $number, (int) $decimals, ',', '.' );
}

require SGK_PLUGIN_DIR . 'includes/class-sgk-config.php';
require SGK_PLUGIN_DIR . 'includes/class-sgk-resolver.php';
require SGK_PLUGIN_DIR . 'includes/class-sgk-calculator.php';
require SGK_PLUGIN_DIR . 'includes/class-sgk-result-formatter.php';
require SGK_PLUGIN_DIR . 'includes/class-sgk-offer-document.php';
require SGK_PLUGIN_DIR . 'includes/class-sgk-ui-state.php';

$config     = new SGK_Config();
$resolver   = new SGK_Resolver( $config );
$calculator = new SGK_Calculator( $config, $resolver );
$formatter  = new SGK_Result_Formatter();
$ui_state   = new SGK_UI_State( $config );

$cases = array(
	'werbung_mit_bild_paid_archiv' => array(
		'case_key'       => 'werbung_mit_bild',
		'case_variant'   => 'linear_tv_spot_national',
		'archivgage'     => '1',
		'additional_year'=> '1',
	),
	'werbung_ohne_bild_layout_credit' => array(
		'case_key'          => 'werbung_ohne_bild',
		'case_variant'      => 'funk_spot_national',
		'follow_up_usage'   => '1',
		'prior_layout_fee'  => '250',
		'manual_offer_total'=> '490',
	),
	'unpaid_social' => array(
		'case_key'            => 'webvideo_imagefilm_praesentation_unpaid',
		'duration_minutes'    => '7',
		'usage_social_media'  => '1',
		'usage_praesentation' => '1',
	),
	'app_case' => array(
		'case_key'          => 'app',
		'duration_minutes'  => '9',
	),
	'telefonansage' => array(
		'case_key'      => 'telefonansage',
		'module_count'  => '5',
	),
	'elearning' => array(
		'case_key'          => 'elearning_audioguide',
		'case_variant'      => 'elearning_intern',
		'duration_minutes'  => '12',
	),
	'podcast_packaging' => array(
		'case_key'      => 'podcast',
		'case_variant'  => 'marketing_unlim',
	),
	'podcast_content' => array(
		'case_key'          => 'podcast',
		'case_variant'      => 'podcast_inhalte',
		'duration_minutes'  => '12',
	),
	'patronat_without_unlimited' => array(
		'case_key'      => 'werbung_mit_bild',
		'case_variant'  => 'tv_patronat',
	),
	'allowed_credit_case' => array(
		'case_key'         => 'werbung_mit_bild',
		'case_variant'     => 'linear_tv_spot_national',
		'follow_up_usage'  => '1',
		'prior_layout_fee' => '300',
	),
	'hoerbuch' => array(
		'case_key'  => 'hoerbuch',
		'fah'       => '8',
	),
	'games' => array(
		'case_key'          => 'games',
		'recording_hours'   => '4',
		'recording_days'    => '2',
		'same_day_projects' => '2',
	),
	'redaktionell_minimum' => array(
		'case_key'      => 'redaktionell_doku_tv_reportage',
		'case_variant'  => 'kommentarstimme',
		'net_minutes'   => '3',
	),
	'audiodeskription' => array(
		'case_key'      => 'audiodeskription',
		'net_minutes'   => '8',
	),
	'kleinraeumig' => array(
		'case_key'            => 'kleinraeumig',
		'case_variant'        => 'funk_spot_lokal',
		'additional_motif'    => '1',
	),
	'session_fee' => array(
		'case_key'       => 'session_fee',
		'session_hours'  => '3.5',
	),
	'redirect_video_podcast' => array(
		'case_key'      => 'video_podcast',
		'is_paid_media' => '0',
	),
	'redirect_marketing_elearning_paid_guard' => array(
		'case_key'       => 'marketing_elearning',
		'is_paid_media'  => '1',
	),
	'disallowed_patronat_unlimited' => array(
		'case_key'          => 'werbung_mit_bild',
		'case_variant'      => 'tv_patronat',
		'unlimited_time'    => '1',
	),
	'disallowed_credit_case' => array(
		'case_key'         => 'podcast',
		'case_variant'     => 'marketing_3',
		'follow_up_usage'  => '1',
		'prior_layout_fee' => '300',
	),
	'podcast_ad_redirect' => array(
		'case_key' => 'podcast_sponsoring_audio',
	),
);

$failures = array();

foreach ( $cases as $name => $payload ) {
	$sanitized = $ui_state->sanitize_input( $payload );
	$result    = $formatter->format( $calculator->calculate( $sanitized ) );

	if ( $result['totals']['lower'] > $result['totals']['mid'] || $result['totals']['mid'] > $result['totals']['upper'] ) {
		$failures[] = $name . ': totals order invalid';
	}

	if ( ! array_key_exists( 'manual_offer_total', $result['export_payload'] ) ) {
		$failures[] = $name . ': export payload missing manual_offer_total';
	}

	if ( ! isset( $result['document_payload']['sections'] ) ) {
		$failures[] = $name . ': document payload missing sections';
	}
}

$layoutCreditResult = $formatter->format(
	$calculator->calculate(
		$ui_state->sanitize_input( $cases['werbung_ohne_bild_layout_credit'] )
	)
);

if ( null !== $layoutCreditResult['offer_positions'][0]['manuell_uebernommener_preis'] ) {
	$failures[] = 'manual offer leaked into first line item';
}

if ( (float) $layoutCreditResult['export_payload']['manual_offer_total'] !== 490.0 ) {
	$failures[] = 'manual offer total not preserved separately';
}

$redirectResult = $calculator->calculate( $ui_state->sanitize_input( $cases['redirect_video_podcast'] ) );
if ( 'webvideo_imagefilm_praesentation_unpaid' !== $redirectResult['resolved_case'] ) {
	$failures[] = 'video podcast redirect failed';
}

$guardResult = $calculator->calculate( $ui_state->sanitize_input( $cases['redirect_marketing_elearning_paid_guard'] ) );
if ( 'werbung_mit_bild' !== $guardResult['resolved_case'] ) {
	$failures[] = 'marketing e-learning paid-media guard failed';
}

$podcastAdResult = $calculator->calculate( $ui_state->sanitize_input( $cases['podcast_ad_redirect'] ) );
if ( 'werbung_ohne_bild' !== $podcastAdResult['resolved_case'] ) {
	$failures[] = 'podcast ad redirect failed';
}

$patronatUnlimitedResult = $calculator->calculate( $ui_state->sanitize_input( $cases['disallowed_patronat_unlimited'] ) );
if ( empty( $patronatUnlimitedResult['errors'] ) ) {
	$failures[] = 'patronat unlimited guard failed';
}

$disallowedCreditResult = $calculator->calculate( $ui_state->sanitize_input( $cases['disallowed_credit_case'] ) );
if ( empty( $disallowedCreditResult['errors'] ) ) {
	$failures[] = 'credit guard outside allowed family failed';
}

if ( $failures ) {
	foreach ( $failures as $failure ) {
		fwrite( STDERR, $failure . PHP_EOL );
	}
	exit( 1 );
}

echo 'Phase 6 smoke tests passed (' . count( $cases ) . ' scenarios).' . PHP_EOL;
