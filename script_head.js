// setTimeout('document.location.reload(true)', 2000);

var usuarios = [];

function ajaxcmd(cmd, val, callback, tentativa = 1) {
    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "chat.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
    
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status != 200) {
                if (tentativa <= 3) {
                    ajaxcmd(cmd, val, callback, tentativa + 1);
                }
            } else {
                if (callback) {
                    callback(this.responseText, cmd, val);
                }
            }
        }
    };
    
    xhttp.send("acao=" + encodeURIComponent(cmd) + (val ? "&val=" + encodeURIComponent(val) : "")); 
}

function enviaPara(destino) {
    enviar(destino);
    return true;
}

function enviar(destino = 'global') {
    let msg_campo = document.getElementById('msg_enviar');
    let msg = msg_campo.value.trim();
    msg_campo.focus(); 
    
    if (msg === '') {
        return false;
    }
    
    msg_campo.value = '';
    ajaxcmd('envia', JSON.stringify({ texto: msg, para: destino }));
    return false;
}

function verifica() {
    ajaxcmd('verifica', 's', function (resposta) {
        resposta = JSON.parse(resposta);
        
        switch (resposta.status) {
            case "dc":
                document.location.reload(true);
                break;
                
            case "ok":
                if (resposta.mensagens.length > 0) {
                    var msgs_antigas = document.getElementsByClassName('msg_nova');
                    for (var i = 0; i < msgs_antigas.length; i++) {
                        msgs_antigas[i].className = 'chat_mensagem';
                    }
                    
                    resposta.mensagens.forEach(msg => {
                        var novo = '<div class="chat_mensagem msg_nova"';
                        
                        if (msg.destino !== 'global') {
                            novo += ' msg_priv="true"';
                        }
                        
                        novo += '><div class="nome">' + msg.nome;
                        
                        if (msg.destino !== 'global') {
                            novo += ' -> ' + msg.destino;
                        }
                        
                        novo += '</div>';
                        novo += '<div class="login">' + msg.login + '</div>';
                        novo += '<div class="texto">' + msg.texto + '</div>';
                        novo += '<div class="datahora">' + msg.datahora + '</div>';
                        novo += '</div>';
                        
                        document.getElementById('tela_chat').innerHTML = novo + document.getElementById('tela_chat').innerHTML;
                    });
                    
                    var msgs_antigas = document.getElementsByClassName('chat_mensagem');
                    for (var i = 30; i < msgs_antigas.length; i++) {
                        msgs_antigas[i].remove();
                    }
                }
                
                if (resposta.usuarios.length > 0) {
                    resposta.usuarios.forEach(ur => {
                        if (!usuarios.find(u => u.login == ur.login)) {
                            usuarios.push(ur);
                            var novo = '<div class="usuario" onclick="enviaPara(this.getAttribute(\'user\'))" user="' + ur.login + '" id="usuario_' + ur.login + '">';
                            novo += '<div class="parametro nome">' + ur.nome + '</div>';
                            novo += '<div class="parametro login">' + ur.login + '</div>';
                            novo += '</div>';
                            document.getElementById('tela_usuarios').innerHTML += novo;
                        }
                    });
                }
                
                usuarios.forEach(function (user, index) {
                    let u = resposta.usuarios.find(u => u.login == user.login);
                    if (u) {
                        u.online = true;
                        usuarios[index] = u;
                        document.getElementById('usuario_' + user.login).setAttribute('online', 'true');
                        document.getElementById('usuario_' + user.login)
                            .getElementsByClassName('nome')[0].innerHTML = u.nome;
                    } else {
                        document.getElementById('usuario_' + user.login).setAttribute('online', 'false');
                    }
                });
                
                break;
        }
    });
}

var atualiza = setInterval(verifica, 1000);
verifica();
