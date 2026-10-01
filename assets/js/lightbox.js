/**
 * lightbox.js
 * Visualizador de imagens (hero + capturas do carrossel). Qualquer elemento
 * com [data-lightbox-src] abre a imagem em um modal grande (~90% da tela),
 * sem navegar para outra página. Fecha com o botão X, clicando fora da
 * imagem ou com a tecla Esc. Não interfere em nenhum outro script da
 * página (formulário, FAQ, carrossel).
 */

(function () {
    'use strict';

    var overlay = document.querySelector('[data-lightbox-overlay]');
    if (!overlay) return;

    var imgEl = overlay.querySelector('[data-lightbox-img]');
    var closeBtn = overlay.querySelector('[data-lightbox-close]');
    var lastTrigger = null;

    function abrir(src, alt) {
        if (!src) return;
        imgEl.src = src;
        imgEl.alt = alt || '';
        overlay.classList.add('is-open');
        document.body.classList.add('lightbox-open');
    }

    function fechar() {
        overlay.classList.remove('is-open');
        document.body.classList.remove('lightbox-open');
        imgEl.removeAttribute('src');
        if (lastTrigger) {
            lastTrigger.focus();
        }
    }

    document.addEventListener('click', function (evento) {
        var trigger = evento.target.closest('[data-lightbox-src]');
        if (!trigger) return;
        evento.preventDefault();
        lastTrigger = trigger;
        abrir(trigger.getAttribute('data-lightbox-src'), trigger.getAttribute('data-lightbox-alt'));
    });

    overlay.addEventListener('click', function (evento) {
        if (evento.target === overlay) {
            fechar();
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', fechar);
    }

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape' && overlay.classList.contains('is-open')) {
            fechar();
        }
    });
})();
