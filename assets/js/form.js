/**
 * form.js
 * Formulário "Teste gratuito": marcação do plano de interesse quando a
 * pessoa chega por um botão de plano, validação no navegador (espelhando
 * as mesmas regras de processar_lead.php, só que com feedback imediato),
 * estado de carregamento e envio real via fetch() para processar_lead.php.
 *
 * Sem JavaScript, o <form> continua funcionando: ele tem action="/processar_lead.php"
 * method="post", então o navegador envia normalmente e processar_lead.php
 * responde (processar_lead.php já aceita tanto JSON quanto POST tradicional).
 */

(function () {
    'use strict';

    var form = document.querySelector('[data-lead-form]');
    if (!form) {
        return;
    }

    var campoPlano = form.querySelector('[data-plano-interesse]');
    var tagPlano = document.querySelector('[data-plan-tag]');
    var tagPlanoTexto = tagPlano ? tagPlano.querySelector('[data-plan-tag-text]') : null;
    var botaoEnviar = form.querySelector('[data-submit-btn]');
    var blocoSucesso = form.querySelector('[data-form-success]');
    var blocoErro = form.querySelector('[data-form-error]');
    var textoErro = form.querySelector('[data-form-error-text]');

    var MENSAGEM_ERRO_PADRAO = 'Não foi possível registrar sua solicitação agora. Tente novamente em instantes.';

    /* ---------------------------------------------------------------
     * Marca o plano de interesse quando a pessoa clica em um CTA que
     * leva um plano (data-cta-plano="Nome do plano"). Funciona em
     * qualquer botão da página, não só nos cards de planos.
     * ------------------------------------------------------------- */
    document.querySelectorAll('[data-cta-plano]').forEach(function (botao) {
        botao.addEventListener('click', function () {
            var nomePlano = botao.getAttribute('data-cta-plano');
            if (!nomePlano) {
                return;
            }
            if (campoPlano) {
                campoPlano.value = nomePlano;
            }
            if (tagPlano && tagPlanoTexto) {
                tagPlanoTexto.textContent = 'Plano selecionado: ' + nomePlano;
                tagPlano.classList.add('is-visible');
            }
        });
    });

    /* ---------------------------------------------------------------
     * Máscara simples de telefone brasileiro, só para digitar mais fácil
     * — a validação real olha apenas para os dígitos.
     * ------------------------------------------------------------- */
    var campoTelefone = form.querySelector('#telefone');
    if (campoTelefone) {
        campoTelefone.addEventListener('input', function () {
            var digitos = campoTelefone.value.replace(/\D/g, '').slice(0, 11);
            var formatado = digitos;
            if (digitos.length > 10) {
                formatado = digitos.replace(/(\d{2})(\d{5})(\d{0,4})/, function (m, ddd, parte1, parte2) {
                    return '(' + ddd + ') ' + parte1 + (parte2 ? '-' + parte2 : '');
                });
            } else if (digitos.length > 5) {
                formatado = digitos.replace(/(\d{2})(\d{4})(\d{0,4})/, function (m, ddd, parte1, parte2) {
                    return '(' + ddd + ') ' + parte1 + (parte2 ? '-' + parte2 : '');
                });
            } else if (digitos.length > 2) {
                formatado = digitos.replace(/(\d{2})(\d*)/, function (m, ddd, resto) {
                    return '(' + ddd + ') ' + resto;
                });
            } else if (digitos.length > 0) {
                formatado = '(' + digitos;
            }
            campoTelefone.value = formatado;
        });
    }

    /* ---------------------------------------------------------------
     * Validação — mesmas regras do backend (config.php/processar_lead.php),
     * repetidas aqui só para dar retorno imediato antes de enviar.
     * ------------------------------------------------------------- */
    function validarCampo(nomeCampo) {
        var grupo = form.querySelector('[data-error-for="' + nomeCampo + '"]');
        if (grupo) {
            grupo = grupo.closest('.form-group');
        }
        var valido = true;

        if (nomeCampo === 'nome_completo') {
            var nome = form.nome_completo.value.trim();
            valido = nome.length >= 3;
        } else if (nomeCampo === 'email') {
            var email = form.email.value.trim();
            // Verificação simples, compatível com FILTER_VALIDATE_EMAIL do backend
            valido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        } else if (nomeCampo === 'telefone') {
            var digitosTel = form.telefone.value.replace(/\D/g, '');
            valido = digitosTel.length >= 10 && digitosTel.length <= 11;
        }

        if (grupo) {
            grupo.classList.toggle('has-error', !valido);
        }
        return valido;
    }

    ['nome_completo', 'email', 'telefone'].forEach(function (nomeCampo) {
        var campo = form[nomeCampo];
        if (campo) {
            campo.addEventListener('blur', function () {
                validarCampo(nomeCampo);
            });
            campo.addEventListener('input', function () {
                var grupo = campo.closest('.form-group');
                if (grupo && grupo.classList.contains('has-error')) {
                    validarCampo(nomeCampo);
                }
            });
        }
    });

    function validarFormulario() {
        var nomeOk = validarCampo('nome_completo');
        var emailOk = validarCampo('email');
        var telefoneOk = validarCampo('telefone');
        return nomeOk && emailOk && telefoneOk;
    }

    /* ---------------------------------------------------------------
     * Envio
     * ------------------------------------------------------------- */
    function definirCarregando(carregando) {
        if (!botaoEnviar) { return; }
        botaoEnviar.disabled = carregando;
        botaoEnviar.classList.toggle('is-loading', carregando);
    }

    function esconderFeedback() {
        if (blocoSucesso) { blocoSucesso.classList.remove('is-visible'); }
        if (blocoErro) { blocoErro.classList.remove('is-visible'); }
    }

    function mostrarSucesso() {
        esconderFeedback();
        if (blocoSucesso) {
            blocoSucesso.classList.add('is-visible');
            blocoSucesso.setAttribute('tabindex', '-1');
            blocoSucesso.focus({ preventScroll: false });
        }
    }

    function mostrarErro(mensagem) {
        esconderFeedback();
        if (textoErro) {
            textoErro.textContent = mensagem || MENSAGEM_ERRO_PADRAO;
        }
        if (blocoErro) {
            blocoErro.classList.add('is-visible');
            blocoErro.setAttribute('tabindex', '-1');
            blocoErro.focus({ preventScroll: false });
        }
    }

    form.addEventListener('submit', function (evento) {
        evento.preventDefault();
        esconderFeedback();

        if (!validarFormulario()) {
            var primeiroComErro = form.querySelector('.form-group.has-error input, .form-group.has-error textarea');
            if (primeiroComErro) {
                primeiroComErro.focus();
            }
            return;
        }

        var dados = {
            nome_completo: form.nome_completo.value.trim(),
            email: form.email.value.trim(),
            telefone: form.telefone.value.trim(),
            descricao: form.descricao.value.trim(),
            plano_interesse: campoPlano ? campoPlano.value.trim() : '',
            empresa: form.empresa.value // honeypot — deve ir sempre vazio
        };

        definirCarregando(true);

        fetch(form.getAttribute('action') || '/processar_lead.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        })
            .then(function (resposta) {
                return resposta.json().catch(function () {
                    // Resposta não veio em JSON (ex.: erro 500 de servidor) —
                    // trata como falha genérica, sem quebrar a página.
                    return { ok: false };
                });
            })
            .then(function (resultado) {
                if (resultado && resultado.ok) {
                    mostrarSucesso();
                    form.reset();
                    if (tagPlano) {
                        tagPlano.classList.remove('is-visible');
                    }
                    if (campoPlano) {
                        campoPlano.value = '';
                    }
                } else {
                    mostrarErro(resultado && resultado.erro ? resultado.erro : MENSAGEM_ERRO_PADRAO);
                }
            })
            .catch(function () {
                mostrarErro(MENSAGEM_ERRO_PADRAO);
            })
            .finally(function () {
                definirCarregando(false);
            });
    });
})();
