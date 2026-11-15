/* aMule WebUI Reloaded (Material) - script comune
   Sostituisce jQuery: attiva tooltip e popover di Bootstrap 5
   leggendo anche gli attributi "vecchi" di Bootstrap 3 usati dal codice PHP
   (data-placement, data-html, data-trigger, data-content, data-title). */
// Tema scuro di Bootstrap e colore della barra del browser.
// Gira subito (amule.js è caricato nella <head>), così la pagina non lampeggia in chiaro.
(function () {
	document.documentElement.setAttribute('data-bs-theme', 'dark');
	if (!document.querySelector('meta[name="theme-color"]')) {
		var m = document.createElement('meta');
		m.name = 'theme-color';
		m.content = '#172631';
		document.head.appendChild(m);
	}
})();

// Finestre di conferma e di avviso nei colori del tema, al posto di quelle bianche del browser.
//   amuleConfirm(messaggio, testoPulsante, funzioneSeConfermato, pericoloso)
//   alert(messaggio)  -> stessa finestra, solo con OK
(function () {
	var modale = null, el = null, azione = null;

	function crea() {
		el = document.createElement('div');
		el.className = 'modal fade amule-modal';
		el.tabIndex = -1;
		el.setAttribute('aria-hidden', 'true');
		el.innerHTML =
			'<div class="modal-dialog modal-dialog-centered modal-sm">' +
				'<div class="modal-content">' +
					'<div class="modal-header"><h5 class="modal-title">aMule</h5></div>' +
					'<div class="modal-body"></div>' +
					'<div class="modal-footer">' +
						'<button type="button" class="btn btn-default amule-modal-cancel" data-bs-dismiss="modal">Cancel</button>' +
						'<button type="button" class="btn amule-modal-ok">OK</button>' +
					'</div>' +
				'</div>' +
			'</div>';
		document.body.appendChild(el);
		modale = new bootstrap.Modal(el);
		el.querySelector('.amule-modal-ok').addEventListener('click', function () {
			var f = azione; azione = null;
			modale.hide();
			if (f) f();
		});
		el.addEventListener('shown.bs.modal', function () {
			el.querySelector('.amule-modal-ok').focus();
		});
	}

	function mostra(msg, testoOk, seOk, pericoloso, soloOk) {
		if (!window.bootstrap || !document.body) {          // ripiego: finestra del browser
			if (soloOk) { window.alert.originale(msg); return; }
			if (window.confirm(msg) && seOk) seOk();
			return;
		}
		if (!el) crea();
		el.querySelector('.modal-body').textContent = msg;
		var ok = el.querySelector('.amule-modal-ok');
		ok.textContent = testoOk || 'OK';
		ok.className = 'btn amule-modal-ok ' + (pericoloso ? 'btn-amule-danger' : 'btn-info');
		el.querySelector('.amule-modal-cancel').style.display = soloOk ? 'none' : '';
		azione = seOk || null;
		modale.show();
	}

	window.amuleConfirm = function (msg, testoOk, seOk, pericoloso) {
		mostra(msg, testoOk, seOk, pericoloso, false);
	};
	var originale = window.alert;
	window.alert = function (msg) { mostra(String(msg), 'OK', null, false, true); };
	window.alert.originale = function (msg) { originale.call(window, msg); };

	// Link con data-confirm="domanda": chiedono conferma prima di aprirsi (es. pagina Logs)
	document.addEventListener('click', function (e) {
		var a = e.target.closest ? e.target.closest('a[data-confirm]') : null;
		if (!a) return;
		e.preventDefault();
		var href = a.getAttribute('href');
		mostra(a.getAttribute('data-confirm'), a.getAttribute('data-confirm-ok') || 'OK',
			function () { window.location.href = href; }, a.classList.contains('btn-amule-danger'), false);
	});
})();

document.addEventListener('DOMContentLoaded', function () {
	function opzioni(el) {
		var o = {};
		if (el.dataset.placement) o.placement = el.dataset.placement;
		if (el.dataset.trigger) o.trigger = el.dataset.trigger;
		if (el.dataset.html) o.html = el.dataset.html === 'true';
		if (el.dataset.title) o.title = el.dataset.title;
		if (el.dataset.content) o.content = el.dataset.content;
		return o;
	}

	document.querySelectorAll('[data-toggle="tooltip"]').forEach(function (el) {
		new bootstrap.Tooltip(el, opzioni(el));
	});

	document.querySelectorAll('[data-toggle="popover"]').forEach(function (el) {
		new bootstrap.Popover(el, opzioni(el));
	});

	// Nome completo del file: il contenuto è nell'elemento .popper-content successivo
	// I nomi dei file in Transfer ora vanno a capo e si leggono interi:
	// il riquadro con il nome completo (data-toggle="popoverTooltip") non serve più.

	// Menu delle categorie: se c'è solo "all" (nessuna categoria creata in aMule)
	// non servono e vengono nascosti; restano comunque nel modulo, quindi i comandi
	// funzionano come prima. Ricompaiono da soli quando esistono delle categorie.
	document.querySelectorAll('#category, #selectcat, select[name="targetcat"]').forEach(function (sel) {
		if (sel.options.length > 1) return;
		sel.classList.add('d-none');
		var prima = sel.previousElementSibling;
		if (sel.name === 'targetcat' && prima && prima.classList.contains('input-group-text')) {
			prima.classList.add('d-none');            // la scritta "in category"
		}
		var gruppo = sel.parentElement;
		if (gruppo) gruppo.classList.add('senza-categorie');
	});

	// Grafici delle statistiche (Stats e Kad): aMule li disegna con colori fissi
	// (fondo blu scuro, assi bianchi, linea rossa). Qui vengono ridipinti con i colori
	// del tema, presi dalle variabili di amule.css.
	ricoloraGrafici();

	// Pulsante "torna su" (pagina di ricerca)
	var su = document.getElementById('scroll');
	if (su) {
		var aggiorna = function () { su.classList.toggle('visible', window.scrollY > 100); };
		window.addEventListener('scroll', aggiorna, { passive: true });
		aggiorna();
		su.addEventListener('click', function (e) {
			e.preventDefault();
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}
});

function ricoloraGrafici() {
	var css = getComputedStyle(document.documentElement);
	function rgb(nome, ripiego) {
		var v = (css.getPropertyValue(nome) || '').trim() || ripiego;
		var m = /^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i.exec(v);
		return m ? [parseInt(m[1], 16), parseInt(m[2], 16), parseInt(m[3], 16)] : null;
	}
	var fondo = rgb('--amule-bg', '#10181e');
	var assi = rgb('--amule-text-muted', '#9fb3c1');
	var linea = rgb('--amule-accent', '#7cc4e6');
	if (!fondo || !assi || !linea) return;

	function ricolora(img) {
		if (img.dataset.ricolorato) return;
		try {
			var c = document.createElement('canvas');
			c.width = img.naturalWidth;
			c.height = img.naturalHeight;
			if (!c.width || !c.height) return;
			var ctx = c.getContext('2d');
			ctx.drawImage(img, 0, 0);
			var dati = ctx.getImageData(0, 0, c.width, c.height);
			var p = dati.data;
			for (var i = 0; i < p.length; i += 4) {
				var r = p[i], g = p[i + 1], b = p[i + 2], n = null;
				// con un po' di tolleranza, nel caso il browser corregga leggermente i colori
				if (r > 200 && g > 200 && b > 200) n = assi;                     // assi, griglia, cifre (bianco)
				else if (r > 150 && g < 90 && b < 90) n = linea;                 // linea del grafico (rosso)
				else if (r < 40 && g < 40 && b > 30 && b < 110) n = fondo;       // fondo (blu scuro)
				if (n) { p[i] = n[0]; p[i + 1] = n[1]; p[i + 2] = n[2]; }
			}
			ctx.putImageData(dati, 0, 0);
			img.dataset.ricolorato = '1';
			img.src = c.toDataURL('image/png');
		} catch (e) { /* se qualcosa va storto resta l'immagine originale */ }
	}

	document.querySelectorAll('img[src*="amule_stats_"]').forEach(function (img) {
		if (img.complete && img.naturalWidth) ricolora(img);
		else img.addEventListener('load', function () { ricolora(img); }, { once: true });
	});
}
