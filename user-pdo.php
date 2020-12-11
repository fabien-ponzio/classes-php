<?php
class user{

    private $id;
    public $login = '';
    public $password = '';
    public $email = '';
    public $firstname = '';
    public $lastname = '';

    public function register($login, $password, $email, $firstname, $lastname){

        $bdd = new PDO('mysql:host=localhost; dbname=classes', 'root', '');
        $count = $bdd ->prepare("SELECT COUNT(*) FROM utilisateurs WHERE login = :login");
        $count->execute(array(':login'=>$login)); 
        $numrows = $count-> fetchColumn();
        if (!$numrows) {
            $password = password_hash($password,PASSWORD_BCRYPT); 
            $query = $bdd->prepare ("INSERT INTO `utilisateurs`(`login`, `password`, `email`, `firstname`, `lastname`) VALUES (:login, :password, :email, :firstname, :lastname)");

            $query->execute(array(
                ':login' => $login, 
                ':password' => $password,
                ':email' => $email, 
                ':firstname' => $firstname,
                ':lastname' => $lastname
            ));
            if ($query) {
                $this->login = $login;
                $this->password = $password;
                $this->email = $email;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
            }        
        }
    }
// verifier si le login existe
// verifier si le password est le bon via password verify
//si verify password = ok alors les propriétés(id, log, mdp etc) sont égales à leurs équivalents en bdd 
//1. ecrire la requête
//2. la préparer 
//2.5 (bindvalues) 
//3. l'execute
//4. fetch (PDO:: FETCH_ASSOC)

    public function connect($login, $password){
        $bdd = new PDO('mysql:host=localhost; dbname=classes', 'root', '');
        $query = $bdd->prepare("SELECT * FROM utilisateurs WHERE login= :login");
        $query->execute(array(':login'=>$login));
        $numrows = $query->fetch(PDO::FETCH_ASSOC);
      
        if ($query) {
        if (password_verify($password, $numrows['password'])) {
            $this->id = $numrows['id']; //on sauvegarde nos variables de l'objet avec $this
            $this->login = $numrows['login'];
            $this->password = $numrows['password'];
            $this->email = $numrows['email'];
            $this->firstname = $numrows['firstname'];
            $this->lastname = $numrows['lastname'];
        }            
        }
        return [$this->id, $this->login, $this->password, $this->email, $this->firstname, $this->lastname]; 
    }

    public function disconnect(){
        unset(
          $this->login,
          $this->password,
          $this->email,
          $this->firstname,
          $this->lastname,  
        );
        }

        public function delete(){
            $bdd = new PDO('mysql:host=localhost; dbname=classes', 'root', '');
            $login = $this->login;
            $query= $bdd->prepare("SELECT * FROM utilisateurs WHERE login = :login");
            $query->execute(array(':login'=>$login));
            if ($query) {
                $delete = $bdd->prepare("DELETE FROM utilisateurs WHERE login = :login"); // login = :login  on lie login avec :login
                $delete ->execute(array(':login'=>$login)); // => = KEY / VALUES 
                echo "Utilisateur supprimé";    
            }
        }
        public function update($login, $password, $email, $firstname, $lastname){
            $bdd = new PDO('mysql:host=localhost; dbname=classes', 'root', '');
            $previousLogin = $this->login; 

            $update = $bdd->prepare("UPDATE utilisateurs SET login = '$login', password = :password, email= :email, firstname= :firstname, lastname= :lastname WHERE login ='$previousLogin'");
            $update = execute([

                "login" => $login,
                "password" => $password, 
                "email" => $email,
                "firstname" => $firstname, 
                "lastname" => $lastname, 
            ]); 

            echo "utilisateur mis à jour"; 
        }

        public function isConnected(){
            if ($this->login) {
                echo "utilisateur connecté";
            }
            else {
                echo "Pas d'utilisateur connecté"; 

                return true; 
            }
        }
        public function getAllInfos(){
            return [$this->login, $this->password, $this->email, $this->firstname, $this->lastname];
        }
        public function getLogin(){
            return [$this->login]; 
        }
        public function getEmail(){
            return [$this->email]; 
          }
          public function getFirstname(){
            return [$this->email]; 
          }
          public function getLastname(){
            return [$this->email]; 
          }
        public function refresh(){
            $bdd = new PDO('mysql:host=localhost; dbname=classes', 'root', '');
            $id = $this->id;
            $query = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = :id");
            $query -> execute([
                "id" => $id 
            ]);
            
            if ($query) {
                $result = $query -> fetch(PDO::FETCH_ASSOC); 
                $this->login = $result['login'];
                $this->password= $result['password'];
                $this->email = $result['email'];
                $this->firstname = $result['firstname'];
                $this->lastname = $result['lastname'];

                return($result); 
            }
            
        }
}







?>