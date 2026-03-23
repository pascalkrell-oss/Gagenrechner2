(function () {
	'use strict';

	var ICONS = {
		'monitor-play': '<rect x="3" y="4" width="18" height="12" rx="2"></rect><path d="m10 9 5 3-5 3z"></path><path d="M7 20h10"></path>',
		radio: '<path d="M4.9 19A10 10 0 0 1 19.1 4.9"></path><path d="M7.8 16.2a6 6 0 0 1 8.4-8.4"></path><circle cx="12" cy="12" r="2"></circle>',
		clapperboard: '<path d="M4 7h16"></path><path d="M4 7l2-3"></path><path d="M10 7l2-3"></path><path d="M16 7l2-3"></path><rect x="3" y="7" width="18" height="14" rx="2"></rect>',
		'phone-call': '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.61 2.62a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.46-1.18a2 2 0 0 1 2.11-.45c.84.28 1.72.49 2.62.61A2 2 0 0 1 22 16.92z"></path><path d="M14.5 2A5.5 5.5 0 0 1 20 7.5"></path><path d="M14.5 6A1.5 1.5 0 0 1 16 7.5"></path>',
		'graduation-cap': '<path d="m22 10-10-5L2 10l10 5 10-5z"></path><path d="M6 12v5c3 2 9 2 12 0v-5"></path>',
		podcast: '<path d="M12 2a7 7 0 0 1 7 7c0 2.59-1.4 4.85-3.5 6.06"></path><path d="M12 22a2 2 0 0 0 2-2v-3"></path><path d="M12 13a4 4 0 0 0 4-4"></path><path d="M12 13a4 4 0 0 1-4-4"></path><circle cx="12" cy="9" r="1"></circle>',
		sparkles: '<path d="M12 3l1.9 4.6L18.5 9l-4.6 1.4L12 15l-1.9-4.6L5.5 9l4.6-1.4L12 3z"></path><path d="M19 15l.9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9L19 15z"></path><path d="M5 15l.9 2.1L8 18l-2.1.9L5 21l-.9-2.1L2 18l2.1-.9L5 15z"></path>',
		minus: '<path d="M5 12h14"></path>',
		plus: '<path d="M12 5v14"></path><path d="M5 12h14"></path>',
		'folder-pen': '<path d="M3 6a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v1"></path><path d="M3 10v8a2 2 0 0 0 2 2h6"></path><path d="m18 13 3 3"></path><path d="m15 19 1.5-4.5L20 11l3 3-3.5 3.5z"></path>',
		'chevron-down': '<path d="m6 9 6 6 6-6"></path>',
		'settings-2': '<path d="M20 7h-9"></path><path d="M14 17H5"></path><circle cx="17" cy="17" r="3"></circle><circle cx="8" cy="7" r="3"></circle>',
		lightbulb: '<path d="M9 18h6"></path><path d="M10 22h4"></path><path d="M12 2a7 7 0 0 0-4 12c.6.5 1 1.2 1 2h6c0-.8.4-1.5 1-2a7 7 0 0 0-4-12z"></path>'
	};

	function renderIcon(node, attrs) {
		var name = node.getAttribute('data-lucide');
		if (!name || !ICONS[name]) {
			return;
		}
		var width = node.getAttribute('width') || 24;
		var height = node.getAttribute('height') || 24;
		var className = node.getAttribute('class') || '';
		var strokeWidth = (attrs && attrs['stroke-width']) || node.getAttribute('stroke-width') || 2;
		node.outerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="' + width + '" height="' + height + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="' + strokeWidth + '" class="lucide lucide-' + name + (className ? ' ' + className : '') + '" aria-hidden="true">' + ICONS[name] + '</svg>';
	}

	window.lucide = {
		createIcons: function (options) {
			var attrs = options && options.attrs ? options.attrs : {};
			document.querySelectorAll('[data-lucide]').forEach(function (node) {
				renderIcon(node, attrs);
			});
		}
	};
}());
