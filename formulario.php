<?php
$error_message = '';
$success_message = ''; // Variável para armazenar a mensagem de sucesso

if (isset($_POST['submit'])) {
    // Inclui o arquivo de configuração para conexão com o banco de dados
    include_once('bd.php');

    // Coletando dados do formulário e aplicando validações do lado do servidor
    $login = trim($_POST['login']);
    $nome = trim($_POST['nome']);
    $senha = trim($_POST['senha']);
    $online = date("Y-m-d H:i:s"); // Captura a data e hora atual para o campo "online"

    // Validação no servidor
    if (empty($login)) {
        $error_message = "O campo de login é obrigatório.";
    } elseif (empty($nome)) {
        $error_message = "O campo de nome é obrigatório.";
    } elseif (empty($senha)) {
        $error_message = "O campo de senha é obrigatório.";
    } elseif (strlen($senha) < 8 || !preg_match("/[A-Z]/", $senha) || !preg_match("/[0-9]/", $senha) || !preg_match("/[@$!%*#?&]/", $senha)) {
        $error_message = "A senha deve ter no mínimo 8 caracteres, com pelo menos uma letra maiúscula, um número e um caractere especial.";
    } else {
        // Verificando se o login já existe
        $checkLoginQuery = "SELECT * FROM usuarios WHERE login = ?";
        $stmt = mysqli_prepare($link, $checkLoginQuery);

        if ($stmt === false) {
            $error_message = "Erro na preparação da consulta: " . mysqli_error($link);
        } else {
            mysqli_stmt_bind_param($stmt, "s", $login);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $error_message = "Este login já está em uso. Por favor, escolha outro.";
            } else {
                // Inserindo os dados no banco se o login não existir
                $insertQuery = "INSERT INTO usuarios (login, nome, senha, online) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($link, $insertQuery);

                if ($stmt === false) {
                    $error_message = "Erro na preparação da inserção: " . mysqli_error($link);
                } else {
                    // Usando a senha em texto simples
                    mysqli_stmt_bind_param($stmt, "ssss", $login, $nome, $senha, $online);

                    if (mysqli_stmt_execute($stmt)) {
                        $success_message = "Cadastro realizado com sucesso!";
                    } else {
                        $error_message = "Erro ao inserir os dados: " . mysqli_error($link);
                    }
                }
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($link);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
    // Validação no lado do cliente com JavaScript
    function validarFormulario() {
        let login = document.getElementById("login").value.trim();
        let nome = document.getElementById("nome").value.trim();
        let senha = document.getElementById("senha").value.trim();
        let error_message = "";

        if (login === "") {
            error_message = "O campo de login é obrigatório.";
        } else if (nome === "") {
            error_message = "O campo de nome é obrigatório.";
        } else if (senha.length < 8 || !/[A-Z]/.test(senha) || !/[0-9]/.test(senha) || !/[@$!%*#?&]/.test(senha)) {
            error_message = "A senha deve ter no mínimo 8 caracteres, com pelo menos uma letra maiúscula, um número e um caractere especial.";
        }

        if (error_message !== "") {
            alert(error_message);
            return false;
        }

        return true;
    }
    </script>

    <link rel="stylesheet" href="style.form.css">
    <link rel="stylesheet" href="style.home.css">
</head>
<body>
    <header>
        <nav class="navbar fixed-top navbar-expand-lg navbar-light custom-navbar">
          <div class="container-fluid">
            <a class="navbar-brand" href="index.php">InteractChat</a>
            <div class="collapse navbar-collapse" id="navbarNav"></div>
          </div>
        </nav>
    </header>

    <div class="box">
        <form action="formulario.php" method="POST" onsubmit="return validarFormulario()">
            <fieldset>
                <legend><b>Formulário de Cadastro</b></legend>
                <br><br><br>

                <!-- Exibe mensagens de erro -->
                <?php if (!empty($error_message)): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <!-- Exibe mensagem de sucesso -->
                <?php if (!empty($success_message)): ?>
                    <p style="color: green;"><?php echo $success_message; ?></p>
                    <p>Voltar para a <a href="index.php" class="btn btn-primary-custom">página inicial de login</a> </p>
                <?php endif; ?>

                <!-- Campo de Login -->
                <div class="inputBox">
                    <input type="text" name="login" id="login" class="inputUser exemplo-transparente" required>
                    <label for="login" class="labelInput">Login</label>
                    <p class="exemplo-comment">Exemplo: joaosilva</p>
                </div>
                <br><br>

                <!-- Campo de Nome -->
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser exemplo-transparente" required>
                    <label for="nome" class="labelInput">Nome completo</label>
                    <p class="exemplo-comment">Exemplo: João da Silva Santos</p>
                </div>
                <br><br>

                <!-- Campo de Senha -->
                <div class="inputBox">
                    <input type="password" name="senha" id="senha" class="inputUser exemplo-transparente" required>
                    <label for="senha" class="labelInput">Senha</label>
                    <p class="exemplo-comment">Exemplo de senha: P@ssw0rd123!</p>
                </div>
                <br><br>

                <input type="submit" name="submit" id="submit" value="Enviar">
            </fieldset>
        </form>
    </div>

    <footer>
        <div class="container">
          <p>InteractChat &copy; Todos os direitos reservados</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
