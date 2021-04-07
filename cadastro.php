<?php 

    if($Session->registrado){
        // A variavel acima terá o valor true caso o usuário se cadastre com sucesso
        // Use ela para informar o usuário do sucesso no cadastro
        echo("<h3>
            Registro Feito Com sucesso $Session->user
        </h3>");
    }

    if(!$Session->ExisteSessao){ 
        // a variavel acima representa a sessão do usuário, se for válida ela terá o valor true
        // Use onde precisar verificar se o usuário está logado ou não
        // Caso o usuário esteja logado não faz sentido ele cadastrar ;)

        // O formulário abaixo é um exemplo de cadastro, os dados necessários são os inputs 
        // - username
        // - email
        // - password
        // - action (botão)
        // o formulário deve ser enviado para a mesma página que o arquivo Session foi chamado
        // usando a tag <form method="post"> sem target
    ?>

        <h1>Exemplo de registro</h1>
        <form method="post">
            <table>
                <tr>
                    <td>usuário</td>
                    <td>
                        <input type="text" value="" name="username" />
                    </td>
                </tr>
                <tr>
                    <td>email</td>
                    <td>
                        <input type="text" value="" name="email" />
                    </td>
                </tr>
                <tr>
                    <td>senha</td>
                    <td>
                        <input type="password" value="" name="password" />
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" name="action" value="Registrar" /></td>
                </tr>
            </table>
        </form>
    <?php } ?>