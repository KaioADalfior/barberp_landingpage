/**
 * faq.js
 * Accordion acessível da seção de Perguntas Frequentes. Cada pergunta é um
 * <button> com aria-expanded/aria-controls; a resposta correspondente é
 * revelada ajustando a altura real do conteúdo (medida com scrollHeight),
 * para permitir uma transição suave sem depender de "height: auto" direto
 * (que não anima em CSS).
 *
 * Sem JavaScript, o <noscript> em index.php mantém todas as respostas
 * visíveis por padrão — ninguém fica sem conseguir ler o conteúdo.
 */

(function () {
    'use strict';

    var itens = document.querySelectorAll('[data-faq] .faq-item');
    if (itens.length === 0) {
        return;
    }

    function fechar(item) {
        var botao = item.querySelector('[data-faq-trigger]');
        var painel = item.querySelector('[data-faq-panel]');
        item.setAttribute('data-open', 'false');
        botao.setAttribute('aria-expanded', 'false');
        painel.style.height = painel.scrollHeight + 'px';
        // Força o navegador a registrar a altura atual antes de animar para 0
        window.requestAnimationFrame(function () {
            painel.style.height = '0px';
        });
    }

    function abrir(item) {
        var botao = item.querySelector('[data-faq-trigger]');
        var painel = item.querySelector('[data-faq-panel]');
        item.setAttribute('data-open', 'true');
        botao.setAttribute('aria-expanded', 'true');
        painel.style.height = painel.scrollHeight + 'px';
    }

    itens.forEach(function (item) {
        var botao = item.querySelector('[data-faq-trigger]');
        var painel = item.querySelector('[data-faq-panel]');
        if (!botao || !painel) {
            return;
        }

        // Estado inicial: tudo fechado, altura 0 (o HTML já vem com
        // data-open="false" e aria-expanded="false")
        painel.style.height = '0px';

        botao.addEventListener('click', function () {
            var estaAberto = item.getAttribute('data-open') === 'true';

            // Comportamento de "sanfona": abrir um fecha os outros,
            // deixando a lista mais fácil de escanear.
            itens.forEach(function (outroItem) {
                if (outroItem !== item && outroItem.getAttribute('data-open') === 'true') {
                    fechar(outroItem);
                }
            });

            if (estaAberto) {
                fechar(item);
            } else {
                abrir(item);
            }
        });
    });

    // Se o texto da página for redimensionado (zoom, fonte maior) com um
    // item aberto, recalcula a altura para não cortar o conteúdo.
    window.addEventListener('resize', function () {
        itens.forEach(function (item) {
            if (item.getAttribute('data-open') === 'true') {
                var painel = item.querySelector('[data-faq-panel]');
                painel.style.height = 'auto';
                var altura = painel.scrollHeight;
                painel.style.height = altura + 'px';
            }
        });
    });
})();
