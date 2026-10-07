/**
 * Efemérides Vallenatas — interacciones del tema (sin dependencias).
 * Encabezado, menú móvil, CTA fijo, scroll reveal, parallax, conteo de cifras,
 * línea de tiempo, calendario de efemérides, galería, copiar enlace y formularios.
 */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

  /* ---------- Scroll: encabezado y CTA fijo móvil (un solo listener con rAF) ---------- */
  var header = $('[data-header]');
  var sticky = $('[data-sticky-cta]');
  var scrollTasks = [];
  var ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(function () {
      ticking = false;
      scrollTasks.forEach(function (fn) { fn(); });
    });
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll);

  scrollTasks.push(function () {
    var y = window.scrollY;
    if (header) header.classList.toggle('is-scrolled', y > 12);
    if (sticky) {
      var show = y > 520;
      sticky.classList.toggle('translate-y-full', !show);
      if (show) sticky.removeAttribute('inert'); else sticky.setAttribute('inert', '');
    }
  });

  /* ---------- Menú móvil ---------- */
  var toggle = $('[data-menu-toggle]');
  var menu = $('#mobile-menu');
  if (toggle && menu) {
    var setMenu = function (open) {
      menu.hidden = !open;
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
      $('[data-icon-open]', toggle).hidden = open;
      $('[data-icon-close]', toggle).hidden = !open;
      document.body.style.overflow = open ? 'hidden' : '';
      header.classList.toggle('menu-open', open);
    };
    toggle.addEventListener('click', function () { setMenu(menu.hidden); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !menu.hidden) { setMenu(false); toggle.focus(); }
    });
  }

  /* ---------- Scroll reveal ---------- */
  var reveals = $$('.reveal');
  if ('IntersectionObserver' in window && !reduceMotion) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* ---------- Parallax ligero ---------- */
  if (!reduceMotion) {
    $$('[data-parallax]').forEach(function (el) {
      var speed = parseFloat(el.getAttribute('data-parallax')) || 0.05;
      scrollTasks.push(function () {
        var r = el.getBoundingClientRect();
        var offset = (r.top + r.height / 2 - window.innerHeight / 2) * -speed;
        el.style.transform = 'translate3d(0,' + offset.toFixed(1) + 'px,0)';
      });
    });
  }

  /* ---------- Conteo de cifras ---------- */
  var counters = $$('[data-count]');
  if (counters.length && 'IntersectionObserver' in window && !reduceMotion) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        cio.unobserve(entry.target);
        var el = entry.target;
        var target = parseInt(el.getAttribute('data-count'), 10);
        var start = performance.now();
        (function tick(now) {
          var p = Math.min(1, (now - start) / 1600);
          el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
          if (p < 1) requestAnimationFrame(tick);
        })(start);
      });
    }, { threshold: 0.4 });
    counters.forEach(function (el) { el.textContent = '0'; cio.observe(el); });
  }

  /* ---------- Línea de tiempo animada ---------- */
  $$('[data-timeline]').forEach(function (tl) {
    var bar = $('[data-timeline-bar]', tl);
    var steps = $$('.ev-timeline__step', tl);
    var update = function () {
      var r = tl.getBoundingClientRect();
      var p = reduceMotion ? 1 : Math.max(0, Math.min(1, (window.innerHeight * 0.75 - r.top) / r.height));
      bar.style.transform = 'scaleY(' + p + ')';
      steps.forEach(function (s, i) { s.classList.toggle('is-active', p >= (i + 0.35) / steps.length); });
    };
    scrollTasks.push(update);
    update();
  });

  /* ---------- Calendario de efemérides ---------- */
  var cal = $('[data-ephemerides]');
  if (cal) {
    var items = $$('[data-item]', cal);
    var groups = $$('[data-group]', cal);
    var monthBtns = $$('[data-month]', cal).filter(function (b) { return b.tagName === 'BUTTON'; });
    var catBtns = $$('button[data-category]', cal);
    var search = $('[data-search]', cal);
    var status = $('[data-count-status]', cal);
    var empty = $('[data-empty]', cal);
    var months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    var state = { month: 0, category: '', q: '' };
    var norm = function (s) { return s.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim(); };

    var params = new URLSearchParams(location.search);
    var m = parseInt(params.get('mes'), 10);
    if (m >= 1 && m <= 12) state.month = m;
    if (params.get('categoria')) state.category = params.get('categoria');
    if (params.get('q')) { state.q = params.get('q'); search.value = state.q; }

    var apply = function () {
      var q = norm(state.q);
      var visible = 0;
      items.forEach(function (li) {
        var ok = (!state.month || li.getAttribute('data-month') === String(state.month)) &&
          (!state.category || li.getAttribute('data-category') === state.category) &&
          (!q || li.getAttribute('data-text').indexOf(q) !== -1);
        li.hidden = !ok;
        if (ok) visible++;
      });
      groups.forEach(function (g) { g.hidden = !$$('[data-item]', g).some(function (li) { return !li.hidden; }); });
      monthBtns.forEach(function (b) { b.setAttribute('aria-pressed', String(b.getAttribute('data-month') === String(state.month))); });
      catBtns.forEach(function (b) { b.setAttribute('aria-pressed', String(b.getAttribute('data-category') === state.category)); });
      status.textContent = visible + (visible === 1 ? ' efeméride' : ' efemérides') + (state.month ? ' en ' + months[state.month - 1] : '');
      if (empty) empty.hidden = visible > 0 || !items.length;

      var p = new URLSearchParams();
      if (state.month) p.set('mes', state.month);
      if (state.category) p.set('categoria', state.category);
      if (state.q) p.set('q', state.q);
      var qs = p.toString();
      history.replaceState(null, '', location.pathname + (qs ? '?' + qs : ''));
    };

    monthBtns.forEach(function (b) {
      b.addEventListener('click', function () { state.month = parseInt(b.getAttribute('data-month'), 10); apply(); });
    });
    catBtns.forEach(function (b) {
      b.addEventListener('click', function () {
        var c = b.getAttribute('data-category');
        state.category = state.category === c ? '' : c;
        apply();
      });
    });
    search.addEventListener('input', function () { state.q = search.value; apply(); });
    var reset = $('[data-reset]', cal);
    if (reset) reset.addEventListener('click', function () { state = { month: 0, category: '', q: '' }; search.value = ''; apply(); });
    apply();
  }

  /* ---------- Galería de producto ---------- */
  $$('[data-gallery]').forEach(function (g) {
    var thumbs = $$('[data-show]', g);
    var slides = $$('[data-slide]', g);
    thumbs.forEach(function (t) {
      t.addEventListener('click', function () {
        var key = t.getAttribute('data-show');
        slides.forEach(function (s) { s.hidden = s.getAttribute('data-slide') !== key; });
        thumbs.forEach(function (x) { x.setAttribute('aria-selected', String(x === t)); });
      });
    });
  });

  /* ---------- Copiar enlace ---------- */
  $$('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (!navigator.clipboard) return;
      navigator.clipboard.writeText(btn.getAttribute('data-copy')).then(function () {
        var label = $('span', btn);
        label.textContent = 'Enlace copiado';
        setTimeout(function () { label.textContent = 'Copiar enlace'; }, 2000);
      });
    });
  });

  /* ---------- Formularios (AJAX con respaldo sin JS) ---------- */
  $$('[data-ajax-form]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!window.EV || !window.fetch) return;
      e.preventDefault();
      var btn = $('button[type=submit]', form);
      var msg = $('[data-form-msg]', form);
      var label = btn.textContent;
      btn.disabled = true;
      btn.textContent = btn.getAttribute('data-loading-text') || '…';
      fetch(window.EV.ajaxUrl, { method: 'POST', body: new FormData(form), credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          msg.textContent = (res.data && res.data.message) || '';
          msg.classList.toggle('text-terracotta-deep', !res.success);
          msg.classList.toggle('text-forest', !!res.success);
          if (res.success) form.reset();
        })
        .catch(function () {
          msg.textContent = 'No pudimos enviar el formulario. Inténtalo de nuevo.';
          msg.classList.add('text-terracotta-deep');
        })
        .finally(function () { btn.disabled = false; btn.textContent = label; });
    });
  });

  onScroll();
})();
