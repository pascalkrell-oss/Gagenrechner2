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

		$result['breakdown_sections'] = $this->build_breakdown_sections( $result );

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
		$result['document_payload']    = $this->build_document_payload( $result['export_payload'] );
		$result['export_payload']['document_payload'] = $result['document_payload'];

		return $result;
	}

	protected function build_summary( array $result ) {
		$context = $this->build_calculation_context( $result );

		return array(
			'title'                 => $result['display_title'],
			'display_title'         => $result['display_title'],
			'case_key'              => $result['resolved_case'],
			'case_label'            => $context['case_label'],
			'sub_variant'           => $context['variant_label'],
			'recommended_range'     => array(
				'lower' => $result['formatted_totals']['lower'],
				'mid'   => $result['formatted_totals']['mid'],
				'upper' => $result['formatted_totals']['upper'],
			),
			'manual_offer_total'    => $result['formatted_manual_offer_total'],
			'manual_offer_required' => ! empty( $result['result_meta']['manual_final_offer_required'] ),
			'position_count'        => count( $result['offer_positions'] ),
			'rights_count'          => count( $result['rights_overview'] ),
			'context'               => $context,
			'final_highlight'       => array(
				'range_label' => $result['formatted_totals']['lower'] . ' – ' . $result['formatted_totals']['upper'],
				'mid_label'   => $result['formatted_totals']['mid'],
			),
		);
	}

	protected function build_breakdown_sections( array $result ) {
		$sections      = array();
		$raw_breakdown = isset( $result['breakdown'] ) ? $result['breakdown'] : array();
		$schema        = array(
			'basis' => array(
				'label'       => 'Basispreis',
				'description' => 'Grundpreis aus Projektart, Variante und Umfang.',
			),
			'surcharge' => array(
				'label'       => 'Sonderzuschläge',
				'description' => 'Zusätzliche Nutzungsarten wie Reminder.',
			),
			'additive' => array(
				'label'       => 'Zusatzrechte & Erweiterungen',
				'description' => 'Erweiterte Rechte und optionale Zusatzbausteine.',
			),
			'multiplier' => array(
				'label'       => 'Multiplikatoren',
				'description' => 'Aufschläge für besonders weitreichende Nutzung.',
			),
			'credit' => array(
				'label'       => 'Anrechnungen / Credits',
				'description' => 'Anrechnungen aus bereits vergüteten Vorleistungen.',
			),
			'minimum_fee_adjustment' => array(
				'label'       => 'Mindestgage',
				'description' => 'Anpassung, wenn eine Mindestgage greift.',
			),
		);

		foreach ( $schema as $bucket => $definition ) {
			$entries = isset( $raw_breakdown[ $bucket ] ) && is_array( $raw_breakdown[ $bucket ] ) ? $raw_breakdown[ $bucket ] : array();
			if ( empty( $entries ) ) {
				continue;
			}

			$items = array();
			foreach ( $entries as $entry ) {
				$items[] = array(
					'key'            => isset( $entry['key'] ) ? $entry['key'] : '',
					'label'          => isset( $entry['label'] ) ? $entry['label'] : '',
					'quantity_label' => $this->format_quantity_label( isset( $entry['quantity'] ) ? $entry['quantity'] : 1, isset( $entry['unit_label'] ) ? $entry['unit_label'] : '' ),
					'formatted'      => $this->format_amount_triplet( isset( $entry['totals'] ) ? $entry['totals'] : array() ),
					'note'           => isset( $entry['note'] ) ? $entry['note'] : '',
					'is_credit'      => 'credit' === $bucket,
					'is_minimum'     => 'minimum_fee_adjustment' === $bucket,
				);
			}

			$sections[] = array(
				'key'         => $bucket,
				'label'       => $definition['label'],
				'description' => $definition['description'],
				'items'       => $items,
			);
		}


		$sections[] = array(
			'key'         => 'final_totals',
			'label'       => 'Gesamtempfehlung',
			'description' => 'Preisrahmen nach allen Zu- und Abschlägen.',
			'items'       => array(
				array(
					'label'          => 'Empfohlener Preisrahmen',
					'quantity_label' => '',
					'formatted'      => $this->format_amount_triplet( $result['totals'] ),
					'note'           => null !== $result['manual_offer_total'] ? 'Angebotswert hinterlegt: ' . $this->format_currency( $result['manual_offer_total'] ) . '.' : 'Ein finaler Angebotswert kann bei Bedarf ergänzt werden.',
					'is_credit'      => false,
					'is_minimum'     => false,
				),
			),
		);

		return $sections;
	}

	protected function build_calculation_context( array $result ) {
		$input      = isset( $result['normalized_input'] ) ? $result['normalized_input'] : array();
		$base_items = isset( $result['breakdown']['basis'] ) ? $result['breakdown']['basis'] : array();
		$base_range = ! empty( $base_items[0]['totals'] ) ? $base_items[0]['totals'] : $result['totals'];

		$quantity_basis = array();
		$mapping        = array(
			'duration_minutes' => 'Dauer',
			'net_minutes'      => 'Netto-Sendeminuten',
			'module_count'     => 'Module',
			'fah'              => 'FAH',
			'recording_hours'  => 'Aufnahmestunden',
			'recording_days'   => 'Aufnahmetage',
			'same_day_projects'=> 'Projekte am selben Tag',
			'session_hours'    => 'Session-Stunden',
		);

		foreach ( $mapping as $field => $label ) {
			if ( empty( $input[ $field ] ) || '0' === (string) $input[ $field ] ) {
				continue;
			}
			$quantity_basis[] = $label . ': ' . $this->format_quantity( $input[ $field ] );
		}

		foreach ( array( 'additional_motif' => 'Zusatzmotive', 'additional_year' => 'Zusatzjahre', 'additional_territory' => 'Zusatzterritorien' ) as $field => $label ) {
			if ( empty( $input[ $field ] ) || (int) $input[ $field ] <= 0 ) {
				continue;
			}
			$quantity_basis[] = $label . ': ' . (int) $input[ $field ];
		}

		foreach ( array( 'reminder' => 'Reminder', 'archivgage' => 'Archivnutzung', 'allongen' => 'Allongen', 'follow_up_usage' => 'Nachnutzung' ) as $field => $label ) {
			if ( empty( $input[ $field ] ) || '1' !== (string) $input[ $field ] ) {
				continue;
			}
			$quantity_basis[] = $label . ': aktiv';
		}

		foreach ( array( 'usage_social_media' => 'Social Media', 'usage_praesentation' => 'Präsentation zusätzlich' ) as $field => $label ) {
			if ( empty( $input[ $field ] ) || '1' !== (string) $input[ $field ] ) {
				continue;
			}
			if ( 'usage_praesentation' === $field && 'imagefilm_webvideo_praesentation' === ( isset( $result['resolved_variant'] ) ? $result['resolved_variant'] : '' ) ) {
				continue;
			}
			$quantity_basis[] = $label . ': aktiv';
		}

		return array(
			'case_label'       => $this->humanize_key( $result['resolved_case'] ),
			'variant_label'    => ! empty( $result['resolved_variant'] ) ? $this->humanize_key( $result['resolved_variant'] ) : '',
			'quantity_basis'   => $quantity_basis,
			'base_range_label' => $this->format_currency( $base_range['lower'] ) . ' / ' . $this->format_currency( $base_range['mid'] ) . ' / ' . $this->format_currency( $base_range['upper'] ),
		);
	}

	protected function build_offer_positions( array $result ) {
		$positions = array();
		$sort_key  = 10;

		foreach ( $result['line_items'] as $index => $item ) {
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
				'empfohlener_preis'           => $item['mid'],
				'manuell_uebernommener_preis' => null,
				'kategorie'                   => $this->position_category_label( $item['category'] ),
				'lizenzbezug'                 => $this->license_reference_for_item( $item, $result['licenses'] ),
				'hinweistext'                 => $item['calculation_note'],
				'export_label'                => $item['export_label'],
				'formatted_prices'            => array(
					'lower'  => $this->format_currency( $item['lower'] ),
					'mid'    => $this->format_currency( $item['mid'] ),
					'upper'  => $this->format_currency( $item['upper'] ),
					'manual' => null,
				),
			);
			$sort_key += 10;
		}

		if ( null !== $result['manual_offer_total'] ) {
			$positions[] = array(
				'position_number'             => sprintf( '%02d', count( $positions ) + 1 ),
				'sort_key'                    => $sort_key,
				'titel'                       => 'Finale Angebotssumme',
				'beschreibung'                => 'Manuell gesetzter Angebotswert für Angebot, Export und Kundendokument. Die rechnerische Empfehlung bleibt separat nachvollziehbar.',
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

	protected function humanize_key( $key ) {
		$labels = array(
			'werbung_mit_bild'                    => 'Werbung mit Bild',
			'werbung_ohne_bild'                   => 'Werbung ohne Bild',
			'podcast'                             => 'Podcast',
			'podcast_inhalte'                     => 'Podcast-Inhalt',
			'non_commercial_3'                    => 'Podcast-Verpackung nicht-kommerziell · bis 3 Folgen',
			'non_commercial_unlim'                => 'Podcast-Verpackung nicht-kommerziell · Serienlizenz',
			'marketing_3'                         => 'Podcast-Verpackung kommerziell · bis 3 Folgen',
			'marketing_unlim'                     => 'Podcast-Verpackung kommerziell · Serienlizenz',
			'webvideo_imagefilm_praesentation_unpaid' => 'Webvideo / Imagefilm / Präsentation (unpaid)',
			'imagefilm_webvideo_praesentation'    => 'Imagefilm / Webvideo / Präsentation',
			'awardfilm'                           => 'Awardfilm',
			'casefilm'                            => 'Casefilm',
			'mitarbeiterfilm'                    => 'Mitarbeiterfilm',
			'primary_usage_imagefilm_webvideo_praesentation' => 'Primäre Nutzungsausprägung: Imagefilm / Webvideo / Präsentation',
			'primary_usage_awardfilm'            => 'Primäre Nutzungsausprägung: Awardfilm',
			'primary_usage_casefilm'             => 'Primäre Nutzungsausprägung: Casefilm',
			'primary_usage_mitarbeiterfilm'      => 'Primäre Nutzungsausprägung: Mitarbeiterfilm',
			'social_media'                        => 'Social Media',
			'praesentation'                       => 'Präsentation (zusätzlich)',
		);

		if ( isset( $labels[ $key ] ) ) {
			return $labels[ $key ];
		}

		return ucwords( str_replace( '_', ' ', $key ) );
	}

	protected function build_rights_overview( array $result ) {
		$overview = array();
		foreach ( $result['licenses'] as $license ) {
			$territory = isset( $license['territory_rules']['default_scope'] ) ? $license['territory_rules']['default_scope'] : ( isset( $license['territory_rules']['default'] ) ? $license['territory_rules']['default'] : 'projektbezogen' );
			$media     = isset( $license['media_rules']['default_scope'] ) ? $license['media_rules']['default_scope'] : ( isset( $license['media_rules']['default'] ) ? $license['media_rules']['default'] : 'fallabhängig' );
			$duration  = isset( $license['duration_rules']['default_term'] ) ? $license['duration_rules']['default_term'] : 'projektbezogen';
			if ( is_array( $media ) ) {
				$media = implode( ', ', array_map( 'strval', $media ) );
			}
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
			'resolved_case'       => $result['resolved_case'],
			'line_item_count'     => count( $result['line_items'] ),
			'addon_count'         => count( $result['addons'] ),
			'credit_count'        => count( $result['credits'] ),
			'manual_offer_total'  => $result['manual_offer_total'],
			'input_snapshot'      => $result['input_snapshot'],
			'route_trace'         => $result['route_trace'],
			'breakdown_sections'  => $result['breakdown_sections'],
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

		$breakdown_lines = array();
		foreach ( $result['breakdown_sections'] as $section ) {
			if ( empty( $section['items'] ) || in_array( $section['key'], array( 'context', 'final_totals' ), true ) ) {
				continue;
			}
			$entry_lines = array();
			foreach ( $section['items'] as $item ) {
				$entry_lines[] = $item['label'] . ': ' . $item['formatted']['low_mid_high'];
			}
			$breakdown_lines[] = $section['label'] . ' – ' . implode( '; ', $entry_lines );
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
			'breakdown_block'         => implode( "\n", $breakdown_lines ),
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
			'breakdown_sections'   => $result['breakdown_sections'],
			'calculation_meta'     => $result['internal_meta'],
			'export_text_blocks'   => $result['export_text_blocks'],
		);
	}

	protected function build_document_payload( array $export_payload ) {
		if ( class_exists( 'SGK_Offer_Document' ) ) {
			$builder = new SGK_Offer_Document();
			return $builder->build_from_export_payload( $export_payload );
		}

		return array();
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

	protected function format_amount_triplet( array $amounts ) {
		$lower = isset( $amounts['lower'] ) ? (float) $amounts['lower'] : 0;
		$mid   = isset( $amounts['mid'] ) ? (float) $amounts['mid'] : 0;
		$upper = isset( $amounts['upper'] ) ? (float) $amounts['upper'] : 0;

		return array(
			'lower'        => $this->format_currency( $lower ),
			'mid'          => $this->format_currency( $mid ),
			'upper'        => $this->format_currency( $upper ),
			'low_mid_high' => $this->format_currency( $lower ) . ' / ' . $this->format_currency( $mid ) . ' / ' . $this->format_currency( $upper ),
		);
	}

	protected function format_quantity_label( $quantity, $unit_label ) {
		if ( '' === (string) $unit_label ) {
			return '';
		}

		return $this->format_quantity( $quantity ) . ' ' . $unit_label;
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
