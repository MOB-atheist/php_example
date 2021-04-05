<?php

    session_start();
    error_reporting(E_ALL);
    // A linha abaixo é só pra não ficar repetindo a pasta toda hora,
    // não precisa utilizar no seu projeto se estiver na mesma pasta
    $PATH = "./AuthMeReloaded/samples/website_integration";

    // troque os caminhos abaixo de acordo com a pasta que estão salvos
    $auth = require("$PATH/AuthMeController.php");
    require "$PATH/Bcrypt.php";

class Session
{
    private $AuthMe;
    public $registrado = false;// Variavel que vai dizer se existe login
    public $logado = false;// Variavel que vai dizer se existe login

    function __construct($Action = null, $User = null, $Pass = null, $Pass2 = null, $Email = null){

        $this->AuthMe = new Bcrypt();

        // Recebimento do formulário
        $this->action = $Action;
        $this->user = $User;
        $this->pass = $Pass;
        $this->pass2 = $Pass2;
        $this->email = $Email;

        if ($this->action && $User && $Pass) {
            if ($this->action === "Logar") {
                // Formulário de login
                $this->logado = $this->process_login($this->user, $this->pass);
            } elseif ($this->action === "Registrar") {
                // Formulário de registro
                $this->registrado = $this->process_register(
                    $this->user,
                    $this->pass,
                    $this->email,
                    $this->pass2
                );
            }
        }
    }

    public function VerificaSessao(){
        return false;
    }

    private function process_login($user, $pass)
    {
        if ($this->AuthMe->checkPassword($user, $pass)) {
            return true;
        } else {
            return false;// senha errada
        }
        return false;
    }

    public function process_register(
        $user,
        $pass,
        $email,
        $pass2
    ) {
        // if($pass !== $pass2){ // descomente caso queira verificar a senha registrada
        //     return false; // Confirmação de senha deu errado
        // }
        if ($this->AuthMe->isUserRegistered($user)) {
            return false; //usuário existe
        } elseif (!$this->is_email_valid($email)) {
            return false; //email incorreto
        } else {
            $this->register_success = $this->AuthMe->register($user, $pass, $email);
            if ($this->register_success) {
                return true; // registro com sucesso
            } else {
               return false; //erro
            }
        }
        return false;
    }

    private function is_email_valid($email)
    {
        return trim($email) === ""
            ? true // accept no email
            : filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}
?>