<?php

    session_start();
    error_reporting(E_ALL);
    // A linha abaixo é só pra não ficar repetindo a pasta toda hora,
    // não precisa utilizar no seu projeto se estiver na mesma pasta
    $PATH = "./AuthMeReloaded/samples/website_integration";

    // troque os caminhos abaixo de acordo com a pasta que estão salvos
    $auth = require("$PATH/AuthMeController.php");
    require "$PATH/Bcrypt.php";
    include "./password.php";

class Session
{
    const AUTHME_TABLE = 'authme';
    const SESSION_TIMER = '86400'; // segundos

    private $AuthMe;
    public $registrado = false;// Variavel que vai dizer se existe login
    public $logado = false;// Variavel que vai dizer se existe login
    public $ExisteSessao = false;// Variavel que vai dizer se existe login
    
    function __construct($Action = null, $User = null, $Pass = null, $Pass2 = null, $Email = null){

        $this->AuthMe = new Bcrypt();

        // Recebimento do formulário
        $this->password = $_ENV['password'];
        $this->action = $Action;
        $this->user = $User;
        $this->pass = $Pass;
        $this->pass2 = $Pass2;
        $this->email = $Email;
        $this->ExisteSessao = false;
        if($this->VerificaSessao()){
            if(!$this->action === "Sair"){
                return true;
            }else{
                $this->SairSessao();
            }
        }
        if ($this->action && $User && $Pass) {
            if ($this->action === "Logar") {
                // Formulário de login
                $this->logado = $this->process_login($this->user, $this->pass);
                if($this->logado){
                    
                }
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

    private function getAuthmeMySqli() {
        // CHANGE YOUR DATABASE DETAILS HERE BELOW: host, user, password, database name
        $mysqli = new mysqli('localhost', 'root', $this->password, 'auth');
        if (mysqli_connect_error()) {
            printf('Could not connect to AuthMe database. Errno: %d, error: "%s"',
                mysqli_connect_errno(), mysqli_connect_error());
            return null;
        }
        return $mysqli;
    }

    private function getSession($index) {
        if(isset($_SESSION)){
            if(isset($_SESSION[$index])){
                return $_SESSION[$index];
            }
        }
        return null;
    }

    private function VerificaSessao(){
        $mysqli = $this->getAuthmeMySqli();
        $F_key = $this->getSession('key');
        $F_user = $this->getSession('user');

        if ($mysqli !== null) {
            $stmt = $mysqli->prepare('SELECT `key` FROM `session` WHERE `status` = 1 AND `key` = ? AND usuario = (SELECT id FROM authme WHERE username = ?) AND TIMESTAMPDIFF(SECOND, data, now()) < '. self::SESSION_TIMER);
            $stmt->bind_param('ss', $F_key, $F_user);
            $stmt->execute();
            $stmt->bind_result($key);
            if ($stmt->fetch()) {
                $this->ExisteSessao = true;
                return $key;
            }
        }
        return false;
    }

    private function SairSessao(){
        $mysqli = $this->getAuthmeMySqli();

        $F_user = $this->getSession('user');
        $this->removeOldKeys($F_user);
        session_unset();
        session_destroy();
        
        $this->ExisteSessao = false;
    }

    private function getUser ($username) {
        $mysqli = $this->getAuthmeMySqli();
        if ($mysqli !== null) {
            $stmt = $mysqli->prepare('SELECT id FROM ' . self::AUTHME_TABLE . ' WHERE username = ?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            return $stmt->fetch();
        }

        // Defensive default to true; we actually don't know
        return false;
    }

    private function removeOldKeys($user){
        $mysqli = $this->getAuthmeMySqli();
        if ($mysqli !== null) {
            $stmt = $mysqli->prepare('UPDATE `session` SET `status` = 0 WHERE `usuario` = (SELECT id FROM authme WHERE username = ?) AND `status` = 1;');
            $stmt->bind_param('s', $user);
            $stmt->execute();
        }
    }

    private function generateKey($usuario) {
        $mysqli = $this->getAuthmeMySqli();
        if ($mysqli !== null) {
            $stmt = $mysqli->prepare('
            INSERT INTO `session` (`usuario`, `key`) VALUES ((SELECT id FROM authme WHERE username = ?), MD5(CONCAT(?,DATE_FORMAT(NOW(),"%d%m%y%h%m%s"))))');
            $stmt->bind_param('ss', $usuario, $usuario);
            return $stmt->execute();
        }
        return false;
    }

    private function getKey($user) {
        $mysqli = $this->getAuthmeMySqli();

        if ($mysqli !== null) {
            $stmt = $mysqli->prepare('SELECT `key` FROM `session` WHERE `status` = 1 AND usuario = (SELECT id FROM authme WHERE username = ?) AND TIMESTAMPDIFF(SECOND, data, now()) < '. self::SESSION_TIMER);
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $stmt->bind_result($key);
            if ($stmt->fetch()) {
                $this->ExisteSessao = true;
                return $key;
            }
        }
        return false;
    }

    private function process_login($user, $pass)
    {
        if ($this->AuthMe->checkPassword($user, $pass)) {
            $this->removeOldKeys($user);
            if($this->generateKey($user)){
                $key = $this->GetKey($user);
                
                $_SESSION['user'] = $user;
                $_SESSION['key'] = $key;
                return true;
            }
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