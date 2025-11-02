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