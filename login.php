<?php 

    if($Session->logado){
        // A variavel acima representa se o usuário logou co sucesso
        // Use ela quando enviar o formulário de login, ela vai te
        // informar se o usuário foi logado com sucesso
        echo("<h3>
            Login Feito Com sucesso $Session->user
        </h3>");
    }

    if(!$Session->ExisteSessao){ 
        // a variavel acima representa a sessão do usuário, se for válida ela terá o valor true
        // Use onded precisar verificar se o usuário está logado ou não
        // Caso o usuário esteja logado não faz sentido ele logar novamente ;)
        
        // O formulário abaixo é um exemplo de login, os dados necessários são os inputs 
        // - username
        // - password
        // - action (botão)
        // o formulário deve ser enviado para a mesma página que o arquivo Session foi chamado
        // usando a tag <form method="post"> sem target
    ?>

        <h1>Exemplo de Login</h1>
        <form method="post">
            <table>
                <tr>
                    <td>usuário</td>
                    <td>
                        <input type="text" value="" name="username" />
                    </td>
                </tr>
                <tr>
                    <td>senha</td>
                    <td>
                        <input type="password" value="" name="password" />
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" name="action" value="Logar" /></td>
                </tr>
            </table>
        </form>
<?php 
    }else{
?>
            LOGADO<br>
            <form method="post">
                <input type="submit" name="action" value="Sair" />
            </form>
        <?php
    }
?>