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


document.getElementById("item").addEventListener("change", function () {
    const selectItem = this;
    const quantidadeSelect = document.getElementById("quantidade");

    // Limpa quantidade atual
    quantidadeSelect.innerHTML = "";

    const estoque = selectItem.options[selectItem.selectedIndex].getAttribute("data-estoque");

    if (!estoque || estoque == 0) {
        quantidadeSelect.innerHTML = '<option value="">Sem estoque disponível</option>';
        return;
    }

    // Cria opções de 1 até a quantidade disponível
    quantidadeSelect.innerHTML = '<option value="">Selecione...</option>';
    for (let i = 1; i <= estoque; i++) {
        quantidadeSelect.innerHTML += `<option value="${i}">${i}</option>`;
    }
});

