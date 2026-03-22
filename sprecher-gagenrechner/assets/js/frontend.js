(function () {
	'use strict';

	function htmlEscape(value) {
		return String(value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

	function renderList(items, mapper, emptyText) {
		if (!items || !items.length) {
			return '<p>' + emptyText + '</p>';
		}
		return '<ul>' + items.map(mapper).join('') + '</ul>';
	}

	function renderResult(container, payload) {
		var result = payload.result || {};
		var routeTrace = Array.isArray(result.route_trace) ? result.route_trace : [];
		var notes = Array.isArray(result.notes) ? result.notes : [];
		var warnings = Array.isArray(result.warnings) ? result.warnings : [];
		var lineItems = Array.isArray(result.line_items) ? result.line_items : [];
		var alternatives = Array.isArray(result.alternatives) ? result.alternatives : [];
		var credits = Array.isArray(result.credits) ? result.credits : [];

		container.innerHTML = '' +
			'<div class="sgk-result-card">' +
				'<h3>' + htmlEscape(result.display_title || 'Kein Fall aufgelöst') + '</h3>' +
				'<p><strong>Resolved Case:</strong> ' + htmlEscape(result.resolved_case || '–') + '</p>' +
				'<p><strong>Empfehlung:</strong> ' + htmlEscape((result.result_meta && result.result_meta.recommendation_type) || 'range_with_midpoint') + '</p>' +
				'<div class="sgk-totals">' +
					'<div><span>Lower</span><strong>' + htmlEscape((result.formatted_totals && result.formatted_totals.lower) || '0,00 €') + '</strong></div>' +
					'<div><span>Mid</span><strong>' + htmlEscape((result.formatted_totals && result.formatted_totals.mid) || '0,00 €') + '</strong></div>' +
					'<div><span>Upper</span><strong>' + htmlEscape((result.formatted_totals && result.formatted_totals.upper) || '0,00 €') + '</strong></div>' +
				'</div>' +
				'<div class="sgk-subsection"><h4>Breakdown</h4>' +
					renderList(lineItems, function (item) {
						return '<li><strong>' + htmlEscape(item.label) + '</strong> (' + htmlEscape(item.category) + ')<br><span>' + htmlEscape(item.quantity + ' ' + item.unit_label) + ' · ' + htmlEscape((item.formatted && item.formatted.mid) || '0,00 €') + '</span><br><small>' + htmlEscape(item.calculation_note) + '</small></li>';
					}, 'Keine Positionen.') +
				'</div>' +
				'<div class="sgk-subsection"><h4>Alternative Pakete</h4>' +
					renderList(alternatives, function (item) {
						return '<li><strong>' + htmlEscape(item.label) + '</strong><br><span>' + htmlEscape((item.formatted_totals && item.formatted_totals.mid) || '0,00 €') + '</span></li>';
					}, 'Keine Paket-Alternativen.') +
				'</div>' +
				'<div class="sgk-subsection"><h4>Credits</h4>' +
					renderList(credits, function (item) {
						return '<li><strong>' + htmlEscape(item.label) + '</strong><br><span>' + htmlEscape((item.formatted && item.formatted.mid) || '0,00 €') + '</span></li>';
					}, 'Keine Credits.') +
				'</div>' +
				'<div class="sgk-subsection"><h4>Route Trace</h4>' +
					renderList(routeTrace, function (step) {
						return '<li><strong>' + htmlEscape(step.step) + '</strong><br><span>' + htmlEscape(step.message || '') + '</span></li>';
					}, 'Keine Resolver-Route.') +
				'</div>' +
				'<div class="sgk-subsection"><h4>Hinweise</h4>' + renderList(notes, function (note) { return '<li>' + htmlEscape(note) + '</li>'; }, 'Keine Hinweise.') + '</div>' +
				'<div class="sgk-subsection"><h4>Warnungen</h4>' + renderList(warnings, function (note) { return '<li>' + htmlEscape(note) + '</li>'; }, 'Keine Warnungen.') + '</div>' +
				'<div class="sgk-subsection"><h4>Export Payload</h4><pre>' + htmlEscape(JSON.stringify(result.export_payload || {}, null, 2)) + '</pre></div>' +
			'</div>';
	}

	document.addEventListener('DOMContentLoaded', function () {
		var app = document.querySelector('[data-sgk-app]');
		if (!app) {
			return;
		}

		var form = app.querySelector('[data-sgk-form]');
		var resultContainer = app.querySelector('[data-sgk-result]');
		if (!form || !resultContainer || typeof fetch !== 'function') {
			return;
		}

		app.querySelectorAll('[data-sgk-demo]').forEach(function (button) {
			button.addEventListener('click', function () {
				var data = JSON.parse(button.getAttribute('data-sgk-demo') || '{}');
				Object.keys(data).forEach(function (key) {
					var field = form.querySelector('[name="' + key + '"]');
					if (!field) {
						return;
					}
					if (field.type === 'checkbox') {
						field.checked = String(data[key]) === '1' || data[key] === 1 || data[key] === true;
					} else {
						field.value = data[key];
					}
				});
			});
		});

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			var formData = new FormData(form);
			var payload = {};
			formData.forEach(function (value, key) {
				payload[key] = value;
			});
			form.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
				payload[checkbox.name] = checkbox.checked ? '1' : '0';
			});
			resultContainer.innerHTML = '<p class="sgk-result__placeholder">Berechne Resolver-Pfad, Staffelungen und strukturierte Spanne…</p>';
			fetch(sgkFrontend.restUrl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': sgkFrontend.nonce },
				body: JSON.stringify(payload)
			})
				.then(function (response) { return response.json(); })
				.then(function (json) { renderResult(resultContainer, json); })
				.catch(function () {
					resultContainer.innerHTML = '<p class="sgk-result__placeholder">Die REST-Berechnung konnte nicht geladen werden.</p>';
				});
		});
	});
}());
