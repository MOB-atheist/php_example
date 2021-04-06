<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Examples integração com website</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    // codigo que você vai precisar 
    function get_from_post_or_empty($index_name)
    {
        return trim(
            filter_input(
                INPUT_POST,
                $index_name,
                FILTER_UNSAFE_RAW,
                FILTER_REQUIRE_SCALAR | FILTER_FLAG_STRIP_LOW
            ) ?:
            ""
        );
    }
    require "session.php";
    $Session = new Session(
        get_from_post_or_empty("action"),
        get_from_post_or_empty("username"),
        get_from_post_or_empty("password"),
        get_from_post_or_empty("password2"),// se quiser verificar a senha
        get_from_post_or_empty("email")
    );
    // fim do codigo qeu vai precisar

    if($Session->logado){// quando o fomulário de login der certo variavel logado será true
        echo("<h3>
            Login Feito Com sucesso $Session->user
        </h3>");
    }

    if($Session->registrado){// quando o fomulário de registro der certo variavel registro será true
        echo("<h3>
            Registro Feito Com sucesso $Session->user
        </h3>");
    }
    echo('WTF'.($Session->ExisteSessao?'true':'false'));
    if(!$Session->ExisteSessao){ 
        // Vai pegar a sessão do usuário e verificar se elá é
        // Se a sessão for válida significa que o usuário está logado
        // se ele estiver logado e a sessão for válida será retornado true
        // o operador ! vai retornar o contrário, usuário logado n precisa mais do
        // formulário de login nem de registro
        // você pode usar essa variavel onde quiser
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
    <?php } ?>
</body>

</html>