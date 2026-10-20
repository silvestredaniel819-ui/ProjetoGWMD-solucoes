const servicos = {
    "analise-bi": {
        assunto: "analise",
        mensagem:
            "Olá! Tenho interesse em Análise & Business Intelligence. Gostaria de conversar sobre dashboards, indicadores e relatórios para a minha empresa."
    },
    automacao: {
        assunto: "automacao",
        mensagem:
            "Olá! Tenho interesse em Automação de Processos (ETL). Gostaria de conversar sobre integrações e automações para a minha empresa."
    },
    "machine-learning": {
        assunto: "machine-learning",
        mensagem:
            "Olá! Tenho interesse em Modelos Preditivos & Machine Learning. Gostaria de conversar sobre como aplicar inteligência artificial aos dados da minha empresa."
    },
    treinamento: {
        assunto: "treinamento",
        mensagem:
            "Olá! Tenho interesse em Capacitação & Treinamento. Gostaria de conversar sobre treinamentos para a equipe da minha empresa."
    },
    sistema: {
        assunto: "sistema",
        mensagem:
            "Olá! Tenho interesse em Sistemas de Gestão sob Medida. Gostaria de conversar sobre uma solução para as necessidades da minha empresa."
    }
};

const produtoSelecionado = new URLSearchParams(window.location.search).get("produto");
const servico = Object.prototype.hasOwnProperty.call(servicos, produtoSelecionado)
    ? servicos[produtoSelecionado]
    : null;

if (servico) {
    const campoAssunto = document.querySelector("#assunto");
    const campoMensagem = document.querySelector("#mensagem");

    campoAssunto.value = servico.assunto;
    campoMensagem.value = servico.mensagem;
}
