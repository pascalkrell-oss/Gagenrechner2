<?php
/**
 * Result formatter.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SGK_Result_Formatter {
	public function format( array $result ) {
		$result['formatted_totals'] = array(
			'lower' => $this->format_currency( $result['totals']['lower'] ),
			'mid'   => $this->format_currency( $result['totals']['mid'] ),
			'upper' => $this->format_currency( $result['totals']['upper'] ),
		);

		$result['formatted_manual_offer_total'] = null;
		if ( null !== $result['manual_offer_total'] ) {
			$result['formatted_manual_offer_total'] = $this->format_currency( $result['manual_offer_total'] );
		}

		foreach ( $result['line_items'] as $index => $item ) {
			$result['line_items'][ $index ]['formatted'] = array(
				'lower' => $this->format_currency( $item['lower'] ),
				'mid'   => $this->format_currency( $item['mid'] ),
				'upper' => $this->format_currency( $item['upper'] ),
			);
		}

		foreach ( $result['alternatives'] as $index => $alternative ) {
			$result['alternatives'][ $index ]['formatted_totals'] = array(
				'lower' => $this->format_currency( $alternative['totals']['lower'] ),
				'mid'   => $this->format_currency( $alternative['totals']['mid'] ),
				'upper' => $this->format_currency( $alternative['totals']['upper'] ),
			);
		}

		$result['rights_overview']     = $this->build_rights_overview( $result );
		$result['offer_positions']     = $this->build_offer_positions( $result );
		$result['offer_notes']         = $this->build_offer_notes( $result );
		$result['route_summary_offer'] = $this->build_route_summary_offer( $result );
		$result['internal_meta']       = $this->build_internal_meta( $result );
		$result['export_text_blocks']  = $this->build_export_text_blocks( $result );
		$result['summary']             = $this->build_summary( $result );
		$result['export_payload']      = $this->build_export_payload( $result );

		return $result;
	}

	protected function build_summary( array $result ) {
		return array(
			'title'                 => $result['display_title'],
			'recommended_range'     => array(
				'lower' => $result['formatted_totals']['lower'],
				'mid'   => $result['formatted_totals']['mid'],
				'upper' => $result['formatted_totals']['upper'],
			),
			'manual_offer_total'    => $result['formatted_manual_offer_total'],
			'manual_offer_required' => ! empty( $result['result_meta']['manual_final_offer_required'] ),
			'position_count'        => count( $result['offer_positions'] ),
			'rights_count'          => count( $result['rights_overview'] ),
		);
	}

	protected function build_offer_positions( array $result ) {
		$positions = array();
		$sort_key  = 10;

		foreach ( $result['line_items'] as $index => $item ) {
			$recommended = $item['mid'];
			$manual      = null;

			if ( 0 === $index && null !== $result['manual_offer_total'] ) {
				$manual = $result['manual_offer_total'];
			}

			$positions[] = array(
				'position_number'             => sprintf( '%02d', $index + 1 ),
				'sort_key'                    => $sort_key,
				'titel'                       => $this->position_title( $item ),
				'beschreibung'                => $this->position_description( $item ),
				'menge'                       => (float) $item['quantity'],
				'einheit'                     => $item['unit_label'],
				'einzelpreis_lower'           => $item['lower'],
				'einzelpreis_mid'             => $item['mid'],
				'einzelpreis_upper'           => $item['upper'],
				'empfohlener_preis'           => $recommended,
				'manuell_uebernommener_preis' => $manual,
				'kategorie'                   => $this->position_category_label( $item['category'] ),
				'lizenzbezug'                 => $this->license_reference_for_item( $item, $result['licenses'] ),
				'hinweistext'                 => $item['calculation_note'],
				'export_label'                => $item['export_label'],
				'formatted_prices'            => array(
					'lower'     => $this->format_currency( $item['lower'] ),
					'mid'       => $this->format_currency( $item['mid'] ),
					'upper'     => $this->format_currency( $item['upper'] ),
					'manual'    => null !== $manual ? $this->format_currency( $manual ) : null,
				),
			);
			$sort_key += 10;
		}

		if ( null !== $result['manual_offer_total'] ) {
			$positions[] = array(
				'position_number'             => sprintf( '%02d', count( $positions ) + 1 ),
				'sort_key'                    => $sort_key,
				'titel'                       => 'Finale Angebotssumme',
				'beschreibung'                => 'Manuell gesetzter Angebotswert als Verhandlungs- und Transferposition. Überschreibt nicht die Empfehlung, sondern ergänzt sie als finale Angebotsangabe.',
				'menge'                       => 1,
				'einheit'                     => 'Angebot',
				'einzelpreis_lower'           => $result['manual_offer_total'],
				'einzelpreis_mid'             => $result['manual_offer_total'],
				'einzelpreis_upper'           => $result['manual_offer_total'],
				'empfohlener_preis'           => $result['totals']['mid'],
				'manuell_uebernommener_preis' => $result['manual_offer_total'],
				'kategorie'                   => 'Angebotssumme',
				'lizenzbezug'                 => 'Gesamtkalkulation',
				'hinweistext'                 => 'Separater Angebotstransferwert auf Basis der Empfehlung.',
				'export_label'                => 'Finale Angebotssumme',
				'formatted_prices'            => array(
					'lower'  => $this->format_currency( $result['manual_offer_total'] ),
					'mid'    => $this->format_currency( $result['manual_offer_total'] ),
					'upper'  => $this->format_currency( $result['manual_offer_total'] ),
					'manual' => $this->format_currency( $result['manual_offer_total'] ),
				),
			);
		}

		return $positions;
	}

	protected function build_rights_overview( array $result ) {
		$overview = array();
		foreach ( $result['licenses'] as $license ) {
			$territory = isset( $license['territory_rules']['default_scope'] ) ? $license['territory_rules']['default_scope'] : 'projektbezogen';
			$media     = isset( $license['media_rules']['default_scope'] ) ? $license['media_rules']['default_scope'] : 'gemäß Fallkonfiguration';
			$duration  = isset( $license['duration_rules']['default_term'] ) ? $license['duration_rules']['default_term'] : 'projektbezogen';
			$overview[] = array(
				'title'       => $this->humanize_key( $license['case_key'] ),
				'variant'     => ! empty( $license['variant'] ) ? $this->humanize_key( $license['variant'] ) : '',
				'territory'   => $this->humanize_key( $territory ),
				'media'       => $this->humanize_key( $media ),
				'duration'    => $this->humanize_key( $duration ),
				'usage_notes' => isset( $license['usage_notes'] ) ? $license['usage_notes'] : array(),
			);
		}

		if ( ! empty( $result['input_snapshot']['unlimited_time'] ) && '1' === $result['input_snapshot']['unlimited_time'] ) {
			$overview[] = array(
				'title'       => 'Zusatzrecht',
				'variant'     => 'Zeitlich unbegrenzt',
				'territory'   => 'bestehende Territorien',
				'media'       => 'bestehende Medien',
				'duration'    => 'unbegrenzt',
				'usage_notes' => array( 'Multiplikatorlogik wurde fachlich ergänzt.' ),
			);
		}

		return $overview;
	}

	protected function build_offer_notes( array $result ) {
		$notes = array_values( array_filter( array_merge(
			$result['notes'],
			$result['legal_texts'],
			$result['warnings'],
			array(
				! empty( $result['result_meta']['manual_final_offer_required'] ) ? 'Die finale Angebotssumme wird manuell gesetzt und als separate Angebotsposition geführt.' : '',
				null !== $result['manual_offer_total'] ? 'Aktuell hinterlegte finale Angebotssumme: ' . $this->format_currency( $result['manual_offer_total'] ) . '.' : 'Es wurde noch keine finale Angebotssumme eingetragen.',
			)
		) ) );

		return array_values( array_unique( $notes ) );
	}

	protected function build_route_summary_offer( array $result ) {
		$summary = array();
		foreach ( $result['route_trace'] as $trace ) {
			$summary[] = array(
				'step'    => isset( $trace['step'] ) ? $trace['step'] : 'resolver',
				'label'   => $this->humanize_key( isset( $trace['step'] ) ? $trace['step'] : 'resolver' ),
				'message' => isset( $trace['message'] ) ? $trace['message'] : '',
			);
		}
		return $summary;
	}

	protected function build_internal_meta( array $result ) {
		return array(
			'resolved_case'      => $result['resolved_case'],
			'line_item_count'    => count( $result['line_items'] ),
			'addon_count'        => count( $result['addons'] ),
			'credit_count'       => count( $result['credits'] ),
			'manual_offer_total' => $result['manual_offer_total'],
			'input_snapshot'     => $result['input_snapshot'],
		);
	}

	protected function build_export_text_blocks( array $result ) {
		$title       = $result['display_title'] ? $result['display_title'] : 'Kalkulation';
		$manual_note = null !== $result['manual_offer_total']
			? 'Finale Angebotssumme manuell gesetzt: ' . $this->format_currency( $result['manual_offer_total'] ) . '.'
			: 'Finale Angebotssumme noch offen; Verhandlung und Angebotsabschluss werden manuell festgelegt.';

		$rights_lines = array();
		foreach ( $result['rights_overview'] as $right ) {
			$line = $right['title'];
			if ( ! empty( $right['variant'] ) ) {
				$line .= ' – ' . $right['variant'];
			}
			$line .= ': Laufzeit ' . $right['duration'] . ', Territorium ' . $right['territory'] . ', Medien ' . $right['media'] . '.';
			$rights_lines[] = $line;
		}

		$summary = $title . ' | Empfehlung ' . $result['formatted_totals']['lower'] . ' bis ' . $result['formatted_totals']['upper'] . ', Mittelwert ' . $result['formatted_totals']['mid'] . '. ' . $manual_note;
		$headline = 'Angebot Sprecherhonorar – ' . $title;
		$rights_block = ! empty( $rights_lines ) ? implode( "\n", $rights_lines ) : 'Rechteumfang wird anhand der gewählten Nutzung im Detail ergänzt.';
		$notes_block = implode( "\n", array_slice( $result['offer_notes'], 0, 6 ) );
		$positions_block = $this->build_positions_text( $result['offer_positions'] );

		return array(
			'copy_summary'            => $summary,
			'offer_headline'          => $headline,
			'rights_block'            => $rights_block,
			'notes_block'             => $notes_block,
			'positions_block'         => $positions_block,
			'manual_offer_notice'     => $manual_note,
			'legal_notice_block'      => implode( "\n", $result['legal_texts'] ),
		);
	}

	protected function build_positions_text( array $positions ) {
		$lines = array();
		foreach ( $positions as $position ) {
			$price = null !== $position['manuell_uebernommener_preis']
				? $this->format_currency( $position['manuell_uebernommener_preis'] ) . ' (manuell gesetzt)'
				: $this->format_currency( $position['empfohlener_preis'] ) . ' empfohlen';
			$lines[] = $position['position_number'] . '. ' . $position['titel'] . ' – ' . $position['beschreibung'] . ' | ' . $price;
		}
		return implode( "\n", $lines );
	}

	protected function build_export_payload( array $result ) {
		return array(
			'summary'              => $result['summary'],
			'recommended_range'    => $result['totals'],
			'recommended_mid'      => $result['totals']['mid'],
			'manual_offer_total'   => $result['manual_offer_total'],
			'positions'            => $result['offer_positions'],
			'rights_overview'      => $result['rights_overview'],
			'notes_for_offer'      => $result['offer_notes'],
			'legal_notice'         => $result['legal_texts'],
			'route_summary'        => $result['route_summary_offer'],
			'alternative_packages' => $result['alternatives'],
			'credit_information'   => $result['credits'],
			'calculation_meta'     => $result['internal_meta'],
			'export_text_blocks'   => $result['export_text_blocks'],
		);
	}

	protected function position_title( array $item ) {
		if ( 'credit' === $item['category'] ) {
			return 'Anrechnung: ' . $item['label'];
		}

		if ( 'addon_license' === $item['category'] ) {
			return 'Zusatzposition: ' . $item['label'];
		}

		return $item['label'];
	}

	protected function position_description( array $item ) {
		$parts = array();
		$parts[] = sprintf( '%s %s', $this->format_quantity( $item['quantity'] ), $item['unit_label'] );
		if ( ! empty( $item['calculation_note'] ) ) {
			$parts[] = $item['calculation_note'];
		}
		if ( ! empty( $item['is_redirected_logic'] ) ) {
			$parts[] = 'Fachlich aus einem verbundenen Fall übertragen.';
		}
		return implode( ' ', array_filter( $parts ) );
	}

	protected function position_category_label( $category ) {
		$labels = array(
			'license'            => 'Basislizenz',
			'addon_license'      => 'Zusatzlizenz',
			'minimum_adjustment' => 'Mindestgage',
			'expert_module'      => 'Expertenmodul',
			'credit'             => 'Anrechnung',
		);
		return isset( $labels[ $category ] ) ? $labels[ $category ] : $this->humanize_key( $category );
	}

	protected function license_reference_for_item( array $item, array $licenses ) {
		if ( empty( $licenses ) ) {
			return 'Gemäß ausgewähltem Projektfall';
		}

		$license = $licenses[0];
		$label   = $this->humanize_key( $license['case_key'] );
		if ( ! empty( $license['variant'] ) ) {
			$label .= ' / ' . $this->humanize_key( $license['variant'] );
		}
		if ( 'credit' === $item['category'] ) {
			$label = 'Anrechnung auf bestehende Vorleistung';
		}
		return $label;
	}

	protected function humanize_key( $key ) {
		return ucwords( str_replace( '_', ' ', (string) $key ) );
	}

	protected function format_quantity( $quantity ) {
		if ( floor( (float) $quantity ) === (float) $quantity ) {
			return (string) (int) $quantity;
		}

		return str_replace( '.', ',', (string) $quantity );
	}

	protected function format_currency( $amount ) {
		return number_format_i18n( (float) $amount, 2 ) . ' €';
	}
}
