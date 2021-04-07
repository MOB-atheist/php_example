<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Exemplos integração com website</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <?php
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        // codigo que você vai precisar 
        
        require "session.php";
        // Arquivo com a classe Session

        $Session = new Session();
        // Inicialização da classe

        include("./cadastro.php");
        // Cadastro

        include("./login.php");
        // Login

    ?>
</body>

</html>