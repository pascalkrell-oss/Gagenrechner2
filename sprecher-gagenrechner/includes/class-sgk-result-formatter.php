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

		return $result;
	}

	protected function format_currency( $amount ) {
		return number_format_i18n( (float) $amount, 2 ) . ' €';
	}
}
