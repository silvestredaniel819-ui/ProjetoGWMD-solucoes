const soDigitos = (valor) => valor.replace(/\D/g, '');

const mascaraCpf = (valor) =>
    soDigitos(valor)
        .slice(0, 11)
        .replace(/^(\d{3})(\d)/, '$1.$2')
        .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
        .replace(/\.(\d{3})(\d)/, '.$1-$2');

const mascaraTelefone = (valor) => {
    const d = soDigitos(valor).slice(0, 11);
    if (d.length > 10) return d.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    if (d.length > 6) return d.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
    if (d.length > 2) return d.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
    return d ? '(' + d : '';
};

const mascaras = { cpf: mascaraCpf, telefone: mascaraTelefone };

document.querySelectorAll('input[data-mascara]').forEach((campo) => {
    const aplicar = mascaras[campo.dataset.mascara];
    if (!aplicar) return;
    campo.value = aplicar(campo.value);
    campo.addEventListener('input', () => {
        campo.value = aplicar(campo.value);
    });
});

const senha = document.getElementById('senha');
const confirmar = document.getElementById('confirmar_senha');

if (senha && confirmar) {
    const conferir = () => {
        confirmar.setCustomValidity(
            senha.value === confirmar.value ? '' : 'As senhas não conferem.'
        );
    };
    senha.addEventListener('input', conferir);
    confirmar.addEventListener('input', conferir);
}