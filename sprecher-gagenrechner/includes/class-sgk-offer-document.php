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
		$summary     = isset( $payload['summary'] ) ? $payload['summary'] : array();
		$positions   = isset( $payload['positions'] ) ? $payload['positions'] : array();
		$rights      = isset( $payload['rights_overview'] ) ? $payload['rights_overview'] : array();
		$notes       = isset( $payload['notes_for_offer'] ) ? $payload['notes_for_offer'] : array();
		$legal       = isset( $payload['legal_notice'] ) ? $payload['legal_notice'] : array();
		$alternatives= isset( $payload['alternative_packages'] ) ? $payload['alternative_packages'] : array();

		return array(
			'document_title'    => 'Angebot Sprecherhonorar',
			'document_subtitle' => ! empty( $summary['title'] ) ? $summary['title'] : 'Projektkalkulation',
			'sections'          => array(
				'project'      => $this->build_project_section( $summary, $payload ),
				'positions'    => $this->build_positions_section( $positions ),
				'totals'       => $this->build_totals_section( $payload ),
				'rights'       => $this->build_rights_section( $rights ),
				'notes'        => $this->build_notes_section( $notes ),
				'legal'        => $this->build_legal_section( $legal ),
				'alternatives' => $this->build_alternatives_section( $alternatives ),
			),
			'print_hints'       => array(
				'show_range'        => true,
				'show_internal_meta'=> false,
				'requires_manual_total' => empty( $payload['manual_offer_total'] ),
			),
		);
	}

	protected function build_project_section( array $summary, array $payload ) {
		return array(
			'label' => 'Projektdetails',
			'items' => array(
				array( 'label' => 'Projekt', 'value' => isset( $summary['title'] ) ? $summary['title'] : '' ),
				array( 'label' => 'Empfohlene Spanne', 'value' => isset( $summary['recommended_range']['lower'], $summary['recommended_range']['upper'] ) ? $summary['recommended_range']['lower'] . ' bis ' . $summary['recommended_range']['upper'] : '' ),
				array( 'label' => 'Mittelwert', 'value' => isset( $summary['recommended_range']['mid'] ) ? $summary['recommended_range']['mid'] : '' ),
				array( 'label' => 'Finale Angebotssumme', 'value' => ! empty( $summary['manual_offer_total'] ) ? $summary['manual_offer_total'] : 'Noch offen' ),
			),
			'route_summary' => isset( $payload['route_summary'] ) ? $payload['route_summary'] : array(),
		);
	}

	protected function build_positions_section( array $positions ) {
		return array(
			'label' => 'Angebotspositionen',
			'items' => $positions,
		);
	}

	protected function build_totals_section( array $payload ) {
		return array(
			'label' => 'Angebotssumme',
			'items' => array(
				array( 'label' => 'Finale Angebotssumme', 'value' => isset( $payload['manual_offer_total'] ) && null !== $payload['manual_offer_total'] ? $payload['manual_offer_total'] : null ),
				array( 'label' => 'Empfohlener Mittelwert', 'value' => isset( $payload['recommended_mid'] ) ? $payload['recommended_mid'] : null ),
				array( 'label' => 'Errechnete Spanne', 'value' => isset( $payload['recommended_range']['lower'], $payload['recommended_range']['upper'] ) ? $payload['recommended_range']['lower'] . ' bis ' . $payload['recommended_range']['upper'] : null ),
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
}
