// Função para exibir mensagens de erro no formulário
function exibirErro(campo, mensagem) {
    var erroElemento = document.createElement('span');
    erroElemento.className = 'erro-mensagem';
    erroElemento.textContent = mensagem;

    // Remover mensagem de erro antiga, se existir
    var erroAntigo = campo.nextElementSibling;
    if (erroAntigo && erroAntigo.className === 'erro-mensagem') {
        erroAntigo.remove();
    }

    // Inserir nova mensagem de erro após o campo
    campo.insertAdjacentElement('afterend', erroElemento);
}

// Função para validar o nome (somente letras e espaços)
function validarNome(nome) {
    var regex = /^[a-zA-ZÀ-ÿ0-9]+$/;
    return regex.test(nome);
}

// Função para validar o email
function validarEmail(email) {
    var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
}

// Função para validar a senha
function validarSenha(senha) {
    var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;
    return regex.test(senha);
}

// Função para validar se o usuário tem pelo menos 18 anos
function validarIdadeMinima(dataNascimento) {
    var hoje = new Date();
    var dataNasc = new Date(dataNascimento);
    var idade = hoje.getFullYear() - dataNasc.getFullYear();
    var mes = hoje.getMonth() - dataNasc.getMonth();

    if (mes < 0 || (mes === 0 && hoje.getDate() < dataNasc.getDate())) {
        idade--;
    }
    return idade >= 18;
}

// Modificar a função de validação do formulário
// Modificar a função de validação do formulário
function validarFormulario(event) {
    event.preventDefault();

    var nome = document.getElementById('name');
    var email = document.getElementById('email');
    var senha = document.getElementById('password');
    var dataNascimento = document.getElementById('birth_date');

    var erroEncontrado = false; // Verificar se algum erro foi encontrado

    // Validação de nome
    if (!validarNome(nome.value)) {
        exibirErro(nome, "O usuário não pode conter espaços.");
        nome.focus();
        erroEncontrado = true;
    }


    // Validação de email
    if (!validarEmail(email.value)) {
        exibirErro(email, "O email inserido não é válido.");
        email.focus();
        erroEncontrado = true;
    }

    // Validação de senha
    if (!validarSenha(senha.value)) {
        exibirErro(senha, "A senha inserida é inválida.");
        senha.focus();
        erroEncontrado = true;
    }

    // Validação de idade
    if (!validarIdadeMinima(dataNascimento.value)) {
        exibirErro(dataNascimento, "Você deve ter pelo menos 18 anos para se registrar.");
        dataNascimento.focus();
        erroEncontrado = true;
    }

    // Se todos os campos estiverem válidos, submeter o formulário
    if (!erroEncontrado) {
        document.getElementById('formulario').submit();
    }
}


// Atualizar os requisitos da senha em tempo real
document.getElementById('password').addEventListener('keyup', function() {
    var senha = this.value;
    var minimo = senha.length >= 8;
    var minuscula = /[a-z]/.test(senha);
    var maiuscula = /[A-Z]/.test(senha);
    var numeros = /[0-9]/.test(senha);
    var carater_especial = /[!@#$%^&*(),.?":{}|<>]/.test(senha);

    document.getElementById("char-count").className = minimo ? "pass" : "fail";
    document.getElementById("lowercase").className = minuscula ? "pass" : "fail";
    document.getElementById("uppercase").className = maiuscula ? "pass" : "fail";
    document.getElementById("number").className = numeros ? "pass" : "fail";
    document.getElementById("special-char").className = carater_especial ? "pass" : "fail";
});

// Alternar visualização da senha
function togglePasswordVisibility(passwordFieldId, toggleButtonId) {
    var passwordField = document.getElementById(passwordFieldId);
    var toggleButton = document.getElementById(toggleButtonId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleButton.src = "Imagens/ocultar.png";
        toggleButton.alt = "Ocultar senha";
    } else {
        passwordField.type = "password";
        toggleButton.src = "Imagens/mostrar.png";
        toggleButton.alt = "Mostrar senha";
    }
}

document.getElementById('togglePassword').addEventListener('click', function() {
    togglePasswordVisibility('password', 'togglePassword');
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    togglePasswordVisibility('confirm_password', 'toggleConfirmPassword');
});

function alterarQuantidadeItem(itemId) {
	var produto_id = itemId.substr(4);  // obter a substring com o id do produto (por ex., se itemId for "item123", produto_id fica igual a "123"
	var item_quantidade = document.getElementById(itemId).value;
	window.location.assign("gerir-carrinho.php?item_quantidade=" + item_quantidade + "&idproduto=" + produto_id);
}