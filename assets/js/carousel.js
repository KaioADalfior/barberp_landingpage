/**
 * carousel.js
 * Carrossel de produto (seção "O sistema") — 10 capturas reais do BarbERP.
 * Comportamento simples e dependency-free: troca a slide ativa por classe
 * CSS, com setas, indicadores (dots) e arraste no touch. Sem JavaScript (ou
 * se este arquivo falhar), o CSS dentro de <noscript> no index.php exibe
 * todas as slides empilhadas, então nenhum conteúdo fica escondido.
 */

(function () {
    'use strict';

    var root = document.querySelector('[data-carousel]');
    if (!root) return;

    var track = root.querySelector('[data-carousel-track]');
    var slides = Array.prototype.slice.call(root.querySelectorAll('[data-carousel-slide]'));
    var prevBtn = root.querySelector('[data-carousel-prev]');
    var nextBtn = root.querySelector('[data-carousel-next]');
    var dotsWrap = root.querySelector('[data-carousel-dots]');

    if (!track || slides.length === 0) return;

    var index = 0;
    var dots = [];

    if (dotsWrap) {
        slides.forEach(function (_, i) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'carousel__dot';
            dot.setAttribute('aria-label', 'Ir para a funcionalidade ' + (i + 1) + ' de ' + slides.length);
            dot.addEventListener('click', function () { goTo(i); });
            dotsWrap.appendChild(dot);
            dots.push(dot);
        });
    }

    function update() {
        track.style.transform = 'translateX(-' + (index * 100) + '%)';
        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === index); });
        slides.forEach(function (s, i) {
            s.setAttribute('aria-hidden', i === index ? 'false' : 'true');
        });
    }

    function goTo(i) {
        index = (i + slides.length) % slides.length;
        update();
    }

    if (prevBtn) prevBtn.addEventListener('click', function () { goTo(index - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goTo(index + 1); });

    document.addEventListener('keydown', function (evento) {
        if (!isCarouselInViewport()) return;
        if (evento.key === 'ArrowLeft') goTo(index - 1);
        if (evento.key === 'ArrowRight') goTo(index + 1);
    });

    function isCarouselInViewport() {
        var rect = root.getBoundingClientRect();
        return rect.top < window.innerHeight && rect.bottom > 0;
    }

    // Arraste simples no touch (mobile)
    var startX = null;
    track.addEventListener('touchstart', function (evento) {
        startX = evento.touches[0].clientX;
    }, { passive: true });

    track.addEventListener('touchend', function (evento) {
        if (startX === null) return;
        var diff = evento.changedTouches[0].clientX - startX;
        if (Math.abs(diff) > 40) {
            diff < 0 ? goTo(index + 1) : goTo(index - 1);
        }
        startX = null;
    });

    update();
})();
