document.getElementById('valor').addEventListener('input', function (e) {
    // Substitui tudo que não for número, ponto ou vírgula
    this.value = this.value.replace(/[^0-9,\.]/g, '');

    if (this.value.indexOf(',') !== this.value.lastIndexOf(',')) {
        this.value = this.value.replace(/,/g, '');
    }
    if (this.value.indexOf('.') !== this.value.lastIndexOf('.')) {
        this.value = this.value.replace(/\./g, '');
    }
});


// document.getElementById("item").addEventListener("change", function () {
//     const selectItem = this;
//     const quantidadeSelect = document.getElementById("quantidade");

//     // Limpa quantidade atual
//     quantidadeSelect.innerHTML = "";

//     const estoque = selectItem.options[selectItem.selectedIndex].getAttribute("data-estoque");

//     if (!estoque || estoque == 0) {
//         quantidadeSelect.innerHTML = '<option value="">Sem estoque disponível</option>';
//         return;
//     }

//     // Cria opções de 1 até a quantidade disponível
//     quantidadeSelect.innerHTML = '<option value="">Selecione...</option>';
//     for (let i = 1; i <= estoque; i++) {
//         quantidadeSelect.innerHTML += `<option value="${i}">${i}</option>`;
//     }
// });

const selectItem = document.getElementById("item");
const quantidadeSelect = document.getElementById("quantidade");
const campoPreco = document.getElementById("preco_item");
const campoValor = document.getElementById("valor");

selectItem.addEventListener("change", function () {

    quantidadeSelect.innerHTML = "";
    campoValor.value = "";
    
    const estoque = this.options[this.selectedIndex].getAttribute("data-estoque");
    const preco = this.options[this.selectedIndex].getAttribute("data-preco");

    // Preenche preço unitário
    campoPreco.value = preco ? preco : "";

    if (!estoque || estoque == 0) {
        quantidadeSelect.innerHTML = '<option value="">Sem estoque disponível</option>';
        return;
    }

    quantidadeSelect.innerHTML = '<option value="">Selecione...</option>';
    for (let i = 1; i <= estoque; i++) {
        quantidadeSelect.innerHTML += `<option value="${i}">${i}</option>`;
    }
});

// Quando selecionar quantidade → calcula total
quantidadeSelect.addEventListener("change", function () {

    const preco = parseFloat(campoPreco.value);
    const qtd = parseInt(this.value);

    if (!isNaN(preco) && !isNaN(qtd)) {
        campoValor.value = (preco * qtd).toFixed(2);
    }
});



























document.addEventListener("DOMContentLoaded", function () {

    const selectCliente = document.getElementById('cliente');
    const docInput = document.getElementById('doc_cliente');

    if (!selectCliente) return; // segurança

    selectCliente.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];

        if (!selected.value) {
            docInput.value = "";
            return;
        }

        const tipo = selected.getAttribute('data-tipo'); 
        const cpf = selected.getAttribute('data-cpf');
        const cnpj = selected.getAttribute('data-cnpj');

        let documento = "";

        if (tipo === "fisica") {
            documento = cpf;
        } else if (tipo === "juridica") {
            documento = cnpj;
        }

        docInput.value = documento;
    });
});
