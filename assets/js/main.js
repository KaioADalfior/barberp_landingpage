/**
 * main.js
 * Comportamentos gerais da landing page: marca a página como "com JS"
 * (necessário para o efeito de revelar ao rolar funcionar — ver
 * .has-js .reveal em style.css), header que "endurece" ao rolar, menu
 * mobile (hamburger) e revelar seções ao entrar na tela.
 *
 * Nada aqui é essencial para o funcionamento da página: sem JavaScript,
 * ou se este arquivo falhar ao carregar, todo o conteúdo continua visível
 * e a navegação por âncoras (#funcionalidades, #planos, etc.) continua
 * funcionando normalmente pelo navegador.
 */

(function () {
    'use strict';

    // Marca <html> como "com JavaScript" o quanto antes. É essa classe que
    // liga o efeito de fade-in — por isso ela só é adicionada aqui dentro,
    // nunca em um <script> solto no <head>: se este arquivo não carregar,
    // a classe nunca existe e o conteúdo permanece sempre visível.
    document.documentElement.classList.add('has-js');

    /* ---------------------------------------------------------------
     * Header: sombra/fundo sólido ao rolar a página
     * ------------------------------------------------------------- */
    var header = document.querySelector('[data-header]');
    if (header) {
        var aplicarEstadoHeader = function () {
            if (window.scrollY > 8) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        };
        aplicarEstadoHeader();
        window.addEventListener('scroll', aplicarEstadoHeader, { passive: true });
    }

    /* ---------------------------------------------------------------
     * Menu mobile (hamburger)
     * ------------------------------------------------------------- */
    var menuToggle = document.querySelector('[data-menu-toggle]');
    var mobileNav = document.querySelector('[data-mobile-nav]');

    if (menuToggle && mobileNav) {
        var fecharMenu = function () {
            menuToggle.setAttribute('aria-expanded', 'false');
            mobileNav.classList.remove('is-open');
            document.body.style.overflow = '';
        };

        var abrirMenu = function () {
            menuToggle.setAttribute('aria-expanded', 'true');
            mobileNav.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        };

        menuToggle.addEventListener('click', function () {
            var aberto = menuToggle.getAttribute('aria-expanded') === 'true';
            if (aberto) {
                fecharMenu();
            } else {
                abrirMenu();
            }
        });

        // Fecha o menu ao clicar em qualquer link dentro dele
        mobileNav.querySelectorAll('[data-menu-link]').forEach(function (link) {
            link.addEventListener('click', fecharMenu);
        });

        // Fecha com a tecla Esc (acessibilidade de teclado)
        document.addEventListener('keydown', function (evento) {
            if (evento.key === 'Escape' && mobileNav.classList.contains('is-open')) {
                fecharMenu();
                menuToggle.focus();
            }
        });

        // Se a tela crescer para o layout desktop, garante que o menu
        // mobile não fique "aberto" escondido atrás do menu de topo.
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                fecharMenu();
            }
        });
    }

    /* ---------------------------------------------------------------
     * Revelar seções ao rolar (fade-in sutil). Se o navegador não
     * suportar IntersectionObserver, mostra tudo de uma vez — nunca
     * deixa conteúdo escondido por falta de suporte.
     * ------------------------------------------------------------- */
    var elementosReveal = document.querySelectorAll('.reveal');

    function mostrarTodosImediatamente() {
        elementosReveal.forEach(function (el) {
            el.classList.add('is-visible');
        });
    }

    if (!('IntersectionObserver' in window) || elementosReveal.length === 0) {
        mostrarTodosImediatamente();
    } else {
        try {
            var observer = new IntersectionObserver(
                function (entradas, obs) {
                    entradas.forEach(function (entrada) {
                        if (entrada.isIntersecting) {
                            entrada.target.classList.add('is-visible');
                            obs.unobserve(entrada.target);
                        }
                    });
                },
                { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
            );
            elementosReveal.forEach(function (el) {
                observer.observe(el);
            });
        } catch (erro) {
            mostrarTodosImediatamente();
        }

        // Rede de segurança: se por qualquer motivo o observer não disparar
        // (ex.: elemento já visível no primeiro paint em telas grandes,
        // navegadores com bugs pontuais), garante que nada fique invisível
        // para sempre.
        window.setTimeout(mostrarTodosImediatamente, 2500);
    }
})();
