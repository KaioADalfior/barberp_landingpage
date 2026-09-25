/**
 * carousel.js
 * Carrossel de telas do sistema (seção "Demonstração"): setas, arraste no
 * touch, indicadores (bolinhas), abrir em lightbox ao clicar e autoplay
 * discreto (pausado ao passar o mouse, focar com o teclado, a aba ficar
 * em segundo plano, ou se a pessoa pediu menos movimento no sistema).
 */

(function () {
    'use strict';

    var carrossel = document.querySelector('[data-carousel]');
    if (!carrossel) {
        return;
    }

    var track = carrossel.querySelector('[data-carousel-track]');
    var slides = Array.prototype.slice.call(carrossel.querySelectorAll('.carousel__slide'));
    var botaoAnterior = carrossel.querySelector('[data-carousel-prev]');
    var botaoProximo = carrossel.querySelector('[data-carousel-next]');
    var pontos = Array.prototype.slice.call(carrossel.querySelectorAll('[data-carousel-dot]'));
    var status = carrossel.querySelector('[data-carousel-status]');

    if (!track || slides.length === 0) {
        return;
    }

    var indiceAtual = 0;
    var total = slides.length;
    var prefereMenosMovimento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function irPara(indice, opcoes) {
        opcoes = opcoes || {};
        indiceAtual = ((indice % total) + total) % total; // sempre positivo, circular
        track.style.transform = 'translateX(-' + (indiceAtual * 100) + '%)';

        pontos.forEach(function (ponto, i) {
            var ativo = i === indiceAtual;
            ponto.classList.toggle('is-active', ativo);
            ponto.setAttribute('aria-selected', ativo ? 'true' : 'false');
        });

        if (status) {
            var titulo = slides[indiceAtual].querySelector('figcaption');
            status.textContent = 'Tela ' + (indiceAtual + 1) + ' de ' + total + (titulo ? ': ' + titulo.textContent : '');
        }

        if (!opcoes.silencioso) {
            reiniciarAutoplay();
        }
    }

    if (botaoProximo) {
        botaoProximo.addEventListener('click', function () {
            irPara(indiceAtual + 1);
        });
    }
    if (botaoAnterior) {
        botaoAnterior.addEventListener('click', function () {
            irPara(indiceAtual - 1);
        });
    }
    pontos.forEach(function (ponto, i) {
        ponto.addEventListener('click', function () {
            irPara(i);
        });
    });

    // Navegação por teclado quando o carrossel está em foco
    carrossel.addEventListener('keydown', function (evento) {
        if (evento.key === 'ArrowRight') {
            irPara(indiceAtual + 1);
        } else if (evento.key === 'ArrowLeft') {
            irPara(indiceAtual - 1);
        }
    });

    /* ---------------------------------------------------------------
     * Arraste / swipe (touch e mouse)
     * ------------------------------------------------------------- */
    var arrastando = false;
    var posInicialX = 0;
    var deslocamentoAtual = 0;

    function posX(evento) {
        return (evento.touches && evento.touches[0] ? evento.touches[0].clientX : evento.clientX);
    }

    function iniciarArraste(evento) {
        arrastando = true;
        posInicialX = posX(evento);
        track.style.transition = 'none';
        pararAutoplay();
    }

    function moverArraste(evento) {
        if (!arrastando) { return; }
        var deltaX = posX(evento) - posInicialX;
        deslocamentoAtual = deltaX;
        var larguraPercentual = (deltaX / carrossel.offsetWidth) * 100;
        track.style.transform = 'translateX(calc(-' + (indiceAtual * 100) + '% + ' + larguraPercentual + '%))';
    }

    function finalizarArraste() {
        if (!arrastando) { return; }
        arrastando = false;
        track.style.transition = '';

        var limiarPx = carrossel.offsetWidth * 0.15;
        if (deslocamentoAtual > limiarPx) {
            irPara(indiceAtual - 1);
        } else if (deslocamentoAtual < -limiarPx) {
            irPara(indiceAtual + 1);
        } else {
            irPara(indiceAtual, { silencioso: true });
        }
        deslocamentoAtual = 0;
        reiniciarAutoplay();
    }

    track.addEventListener('touchstart', iniciarArraste, { passive: true });
    track.addEventListener('touchmove', moverArraste, { passive: true });
    track.addEventListener('touchend', finalizarArraste);

    track.addEventListener('mousedown', function (evento) {
        evento.preventDefault();
        iniciarArraste(evento);
    });
    window.addEventListener('mousemove', moverArraste);
    window.addEventListener('mouseup', finalizarArraste);

    /* ---------------------------------------------------------------
     * Lightbox — abre a tela clicada em destaque
     * ------------------------------------------------------------- */
    var lightbox = document.querySelector('[data-lightbox]');
    var lightboxTitulo = lightbox ? lightbox.querySelector('[data-lightbox-title]') : null;
    var lightboxImg = lightbox ? lightbox.querySelector('[data-lightbox-img]') : null;
    var lightboxPlaceholder = lightbox ? lightbox.querySelector('[data-lightbox-placeholder]') : null;
    var botaoFecharLightbox = lightbox ? lightbox.querySelector('[data-lightbox-close]') : null;
    var ultimoFocoAntesLightbox = null;

    function abrirLightbox(titulo, srcImagem) {
        if (!lightbox) { return; }

        // Se a tela já tem uma captura real (data-slide-src), mostra a
        // imagem ampliada; caso contrário, mantém o card explicativo.
        if (srcImagem && lightboxImg) {
            lightboxImg.src = srcImagem;
            lightboxImg.alt = titulo;
            lightboxImg.hidden = false;
            if (lightboxPlaceholder) { lightboxPlaceholder.hidden = true; }
        } else {
            if (lightboxImg) { lightboxImg.hidden = true; lightboxImg.removeAttribute('src'); }
            if (lightboxPlaceholder) { lightboxPlaceholder.hidden = false; }
            if (lightboxTitulo) { lightboxTitulo.textContent = titulo; }
        }

        ultimoFocoAntesLightbox = document.activeElement;
        lightbox.classList.add('is-open');
        pararAutoplay();
        if (botaoFecharLightbox) {
            botaoFecharLightbox.focus();
        }
    }

    function fecharLightbox() {
        if (!lightbox) { return; }
        lightbox.classList.remove('is-open');
        reiniciarAutoplay();
        if (ultimoFocoAntesLightbox && typeof ultimoFocoAntesLightbox.focus === 'function') {
            ultimoFocoAntesLightbox.focus();
        }
    }

    carrossel.querySelectorAll('[data-lightbox-trigger]').forEach(function (gatilho) {
        gatilho.style.cursor = 'zoom-in';
        gatilho.setAttribute('tabindex', '0');
        gatilho.setAttribute('role', 'button');
        var titulo = gatilho.getAttribute('data-slide-title') || 'Tela do sistema';
        var srcImagem = gatilho.getAttribute('data-slide-src');
        gatilho.setAttribute('aria-label', 'Ampliar tela: ' + titulo);

        // Em algumas fotos reais (elemento <img>), o navegador pode tentar
        // iniciar o "arraste nativo" da imagem (aquele efeito de arrastar
        // para copiar/salvar) em vez de disparar o clique — isso faz a foto
        // real parecer que "não abre a prévia", diferente do mockup em CSS
        // (que é uma <div> e nunca tem esse comportamento nativo). Bloqueia
        // esse arraste nativo explicitamente, além do draggable="false" e do
        // CSS, como garantia extra em navegadores mais antigos/diferentes.
        gatilho.addEventListener('dragstart', function (evento) {
            evento.preventDefault();
        });

        gatilho.addEventListener('click', function () {
            abrirLightbox(titulo, srcImagem);
        });
        gatilho.addEventListener('keydown', function (evento) {
            if (evento.key === 'Enter' || evento.key === ' ') {
                evento.preventDefault();
                abrirLightbox(titulo, srcImagem);
            }
        });
    });

    if (botaoFecharLightbox) {
        botaoFecharLightbox.addEventListener('click', fecharLightbox);
    }
    if (lightbox) {
        lightbox.addEventListener('click', function (evento) {
            if (evento.target === lightbox) {
                fecharLightbox();
            }
        });
        document.addEventListener('keydown', function (evento) {
            if (evento.key === 'Escape' && lightbox.classList.contains('is-open')) {
                fecharLightbox();
            }
        });
    }

    /* ---------------------------------------------------------------
     * Autoplay discreto — opcional, pausa com facilidade
     * ------------------------------------------------------------- */
    var autoplayId = null;
    var INTERVALO_MS = 6000;

    function pararAutoplay() {
        if (autoplayId) {
            window.clearInterval(autoplayId);
            autoplayId = null;
        }
    }

    function iniciarAutoplay() {
        if (prefereMenosMovimento || document.hidden) {
            return;
        }
        pararAutoplay();
        autoplayId = window.setInterval(function () {
            irPara(indiceAtual + 1, { silencioso: true });
        }, INTERVALO_MS);
    }

    function reiniciarAutoplay() {
        pararAutoplay();
        iniciarAutoplay();
    }

    carrossel.addEventListener('mouseenter', pararAutoplay);
    carrossel.addEventListener('mouseleave', iniciarAutoplay);
    carrossel.addEventListener('focusin', pararAutoplay);
    carrossel.addEventListener('focusout', iniciarAutoplay);
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            pararAutoplay();
        } else {
            iniciarAutoplay();
        }
    });

    // Estado inicial
    irPara(0, { silencioso: true });
    iniciarAutoplay();
})();