<?php
/**
 * Offer document builder.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SGK_Offer_Document {
	public function build_from_export_payload( array $payload ) {
		$summary    = isset( $payload['summary'] ) ? $payload['summary'] : array();
		$positions  = isset( $payload['positions'] ) ? $payload['positions'] : array();
		$rights     = isset( $payload['rights_overview'] ) ? $payload['rights_overview'] : array();
		$notes      = isset( $payload['notes_for_offer'] ) ? $payload['notes_for_offer'] : array();
		$legal      = isset( $payload['legal_notice'] ) ? $payload['legal_notice'] : array();
		$alternatives = isset( $payload['alternative_packages'] ) ? $payload['alternative_packages'] : array();
		$breakdown  = isset( $payload['breakdown_sections'] ) ? $payload['breakdown_sections'] : array();

		return array(
			'document_title'    => 'Angebot Sprecherhonorar',
			'document_subtitle' => ! empty( $summary['title'] ) ? $summary['title'] : 'Projektkalkulation',
			'sections'          => array(
				'project'      => $this->build_project_section( $summary, $payload ),
				'breakdown'    => $this->build_breakdown_section( $breakdown ),
				'positions'    => $this->build_positions_section( $positions ),
				'totals'       => $this->build_totals_section( $payload, $summary ),
				'rights'       => $this->build_rights_section( $rights ),
				'notes'        => $this->build_notes_section( $notes ),
				'legal'        => $this->build_legal_section( $legal ),
				'alternatives' => $this->build_alternatives_section( $alternatives ),
			),
			'print_hints'       => array(
				'show_range'            => true,
				'show_internal_meta'    => false,
				'requires_manual_total' => empty( $payload['manual_offer_total'] ),
			),
		);
	}

	protected function build_project_section( array $summary, array $payload ) {
		$recommended_range = isset( $payload['recommended_range'] ) ? $payload['recommended_range'] : array();

		return array(
			'label' => 'Projektdetails',
			'items' => array(
				array( 'label' => 'Projekt', 'value' => isset( $summary['title'] ) ? $summary['title'] : '' ),
				array( 'label' => 'Hauptfall', 'value' => isset( $summary['case_label'] ) ? $summary['case_label'] : '' ),
				array( 'label' => 'Untervariante', 'value' => isset( $summary['sub_variant'] ) ? $summary['sub_variant'] : '' ),
				array( 'label' => 'Empfohlene Spanne', 'value' => $this->format_range( $recommended_range ) ),
				array( 'label' => 'Mittelwert', 'value' => isset( $recommended_range['mid'] ) ? $this->format_currency( $recommended_range['mid'] ) : '' ),
				array( 'label' => 'Finale Angebotssumme', 'value' => isset( $payload['manual_offer_total'] ) && null !== $payload['manual_offer_total'] ? $this->format_currency( $payload['manual_offer_total'] ) : 'Noch offen' ),
			),
			'route_summary' => isset( $payload['route_summary'] ) ? $payload['route_summary'] : array(),
		);
	}

	protected function build_breakdown_section( array $breakdown ) {
		return array(
			'label' => 'Rechenweg',
			'items' => $breakdown,
		);
	}

	protected function build_positions_section( array $positions ) {
		return array(
			'label' => 'Angebotspositionen',
			'items' => $positions,
		);
	}

	protected function build_totals_section( array $payload, array $summary ) {
		$recommended_range = isset( $payload['recommended_range'] ) ? $payload['recommended_range'] : array();

		return array(
			'label' => 'Angebotssumme',
			'items' => array(
				array( 'label' => 'Finale Angebotssumme', 'value' => isset( $payload['manual_offer_total'] ) && null !== $payload['manual_offer_total'] ? $this->format_currency( $payload['manual_offer_total'] ) : 'Noch offen' ),
				array( 'label' => 'Empfohlener Mittelwert', 'value' => isset( $payload['recommended_mid'] ) ? $this->format_currency( $payload['recommended_mid'] ) : null ),
				array( 'label' => 'Errechnete Spanne', 'value' => $this->format_range( $recommended_range ) ),
				array( 'label' => 'Hinweis', 'value' => ! empty( $summary['manual_offer_required'] ) ? 'Für das finale Angebot sollte ein finaler Angebotswert gesetzt werden.' : 'Die errechnete Empfehlung kann direkt als Angebotsbasis genutzt werden.' ),
			),
		);
	}

	protected function build_rights_section( array $rights ) {
		return array(
			'label' => 'Nutzungsrechte & Lizenzen',
			'items' => $rights,
		);
	}

	protected function build_notes_section( array $notes ) {
		return array(
			'label' => 'Hinweise & Anmerkungen',
			'items' => $notes,
		);
	}

	protected function build_legal_section( array $legal ) {
		return array(
			'label' => 'Rechtlicher Hinweis',
			'items' => $legal,
		);
	}

	protected function build_alternatives_section( array $alternatives ) {
		return array(
			'label' => 'Paket-Alternativen',
			'items' => $alternatives,
		);
	}

	protected function format_range( array $range ) {
		if ( ! isset( $range['lower'], $range['upper'] ) ) {
			return '';
		}

		return $this->format_currency( $range['lower'] ) . ' bis ' . $this->format_currency( $range['upper'] );
	}

	protected function format_currency( $amount ) {
		return number_format_i18n( (float) $amount, 2 ) . ' €';
	}
}
