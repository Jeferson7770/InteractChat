<?php
session_start();
if (!isset($_SESSION['logado'])) {
    $_SESSION['logado'] = false;
    $_SESSION['tema'] = 'comum';
    $_SESSION['usuario'] = '';
    $_SESSION['nome'] = '';
    $_SESSION['cor_nome_texto'] = '#000';
    $_SESSION['cor_nome_fundo'] = '#fff';
    $_SESSION['ult_msg'] = -1;
}

if (isset($_POST['acao'])) {
    require('bd.php');
    
    switch ($_POST['acao']) {
        case "logar":
            $login = mysqli_real_escape_string($link, $_POST['usuario']);
            $senha = mysqli_real_escape_string($link, $_POST['senha']);
            
            // Convertendo a senha fornecida em MD5 para comparar com o valor no banco de dados
            $senha_hashed = md5($senha);
            
            $qry = mysqli_query($link, "SELECT * FROM usuarios WHERE lower(login) = lower('$login') AND senha = '$senha_hashed'");
            
            if ($qry && $row = mysqli_fetch_assoc($qry)) {
                $_SESSION['logado'] = true;
                $_SESSION['usuario'] = $row['login'];
                $_SESSION['nome'] = $row['nome'];
            } else {
                $_SESSION['msg'] = 'Senha incorreta ou usuário não encontrado!';
            }
            break;

        case "deslogar":
            session_destroy();
            session_start();
            break;

        case "mudatema":
            $_SESSION['tema'] = $_POST['tema'] == '1' ? 'dark' : 'comum';
            break;
    }
    
    Header('Location: .');
}

Header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InteractChat</title>
    <script src="script_head.js"></script>
    <link rel="stylesheet" href="estilo.css"/>
    <link rel="stylesheet" href="tema_<?=$_SESSION['tema']?>.css"/>
</head>
<body>
<div class="tela_login">
    <?php if ($_SESSION['logado']) { ?>
        <span class="informacao">
            <span class="nome">Logado como:</span>
            <span class="valor"><?=$_SESSION['usuario']?></span>
        </span>
        <span class="informacao">
            Seja bem-vindo <?=$_SESSION['nome']?>!
        </span>
        <form method="POST">
            <input type="hidden" name="acao" value="deslogar"/>
            <input type="submit" value="Deslogar"/>
        </form>
    <?php } else { ?>
        <span class="informacao">
            Entre com as suas informações abaixo.
        </span>
        <form method="POST">
            <input type="hidden" name="acao" value="logar"/>
            <span class="informacao">
                <span class="nome">Nome de usuário</span>
                <input class="valor" name="usuario"/>
            </span>
            <span class="informacao">
                <span class="nome">Senha</span>
                <input class="valor" name="senha" type="password"/>
            </span>
            <input type="submit" value="logar"/>
            <!-- Botão para redirecionar ao formulário de cadastro -->
            <input type="button" value="Cadastrar" onclick="window.location.href='formulario.php';"/>
        </form>
        <?php if (isset($_SESSION['msg'])) { echo "<h2>".$_SESSION['msg']."</h2>"; unset($_SESSION['msg']); } ?>
    <?php } ?>
</div>

<div class="tela_mensagem">
    <form class="tema" method="post">
        <input type="hidden" name="acao" value="mudatema"/>
        <select name="tema" onchange="submit();">
            <option value="0" <?php if ($_SESSION['tema'] == 'comum') { echo "selected"; } ?>>Tema comum</option>
            <option value="1" <?php if ($_SESSION['tema'] == 'dark') { echo "selected"; } ?>>Tema dark</option>
        </select>
    </form>

    <?php if ($_SESSION['logado']) { ?>
        <div class="informacao">Digite abaixo sua mensagem</div>
        <form autocomplete="off" onsubmit="return enviar();">
            <input id="msg_enviar" class="mensagem" type="text">
            <input type="submit" value="Enviar"/>
        </form>
    <?php } ?>
</div>

<div id="tela_usuarios" class="tela_usuarios"></div>
<div class="tela_chat" id="tela_chat"></div>

<script src="script_head.js"></script>
</body>
</html>
