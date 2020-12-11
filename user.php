<?php
class user{

private $id;
public $login = '';
public $password = '';
public $email = '';
public $firstname = '';
public $lastname = ''; // on enleve les espace, les \n -> string et caractere non affichable 

public function register($login, $password, $email, $firstname, $lastname){

    $bdd = mysqli_connect("localhost", "root", "", "classes");

    $login = mysqli_real_escape_string($bdd,htmlspecialchars( trim($login)));
    $password = mysqli_real_escape_string($bdd,htmlspecialchars( trim($password)));
    $email = mysqli_real_escape_string($bdd,htmlspecialchars( trim($email)));
    $firstname = mysqli_real_escape_string($bdd,htmlspecialchars( trim($firstname)));
    $lastname = mysqli_real_escape_string($bdd,htmlspecialchars( trim($lastname))); // on enleve les espace, les \n -> string et caractere non affichable 

    $errorLog = null;

        $query = mysqli_query($bdd, "SELECT login FROM utilisateurs WHERE login = '$login'");
        $count = mysqli_num_rows($query);

        if(!$count){ // si l'identifiant existe déjà alors $errorLog

                if (isset($login, $password, $email, $firstname, $lastname)) {
                  $this ->login = $login;
                  $this ->password = $cryptedpass = password_hash($password, PASSWORD_BCRYPT); // CRYPTED    
                  $this ->email = $email;   
                  $this ->firstname = $firstname;  
                  $this ->lastname = $lastname; 

                    $insert = mysqli_query($bdd, "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES ('$login', '$cryptedpass', '$email', '$firstname', '$lastname')");
                }

                if ($insert) {
                    $select = mysqli_query($bdd, "SELECT * FROM utilisateurs WHERE login = '$login'");
                    $user_identity = mysqli_fetch_assoc($select);
                    return $user_identity;
                }
            }
        else $errorLog = "Ce pseudo est déjà utilisé";
    
return $errorLog;
    }
    //public function connect($login, $password)
//Connecte l’utilisateur, modifie les attributs présents dans la classe et
//retourne un tableau contenant l’ensemble de ses informations.

public function connect($login, $password){
    $bdd = mysqli_connect("localhost", "root", "", "classes");
    
    $login = mysqli_real_escape_string($bdd,htmlspecialchars( trim($login)));
    $password = mysqli_real_escape_string($bdd,htmlspecialchars( trim($password)));
    
      $query = mysqli_query($bdd, "SELECT * FROM utilisateurs WHERE login = '$login'");
      $pass = mysqli_query($bdd, "SELECT password FROM utilisateurs WHERE login = '$login'");
      $count = mysqli_num_rows($pass);
      
      if($count){
        $utilisateur = mysqli_fetch_assoc($query);
        $result = mysqli_fetch_assoc($pass);
  
        if(password_verify($password, $result['password'])){
          $this->id = $utilisateur['id'];
          $this->login = $utilisateur['login'];
          $this->password = $utilisateur['password'];
          $this->email = $utilisateur['email'];
          $this->firstname = $utilisateur['firstname'];
          $this->lastname = $utilisateur['lastname'];
  
          $_SESSION['utilisateur'] = $utilisateur;
          
          echo 'Vous êtes connecté !';
        }
        else{
          echo 'Mauvais MDP';
        }
        return $utilisateur;
      }
    }
//Déconnecte l’utilisateur.
public function disconnect(){
  $this->login =NULL;
  $this->password = NULL;
  $this->email = NULL;
  $this->firstname = NULL;
  $this->lastname = NULL;
echo"ciao bambin!";}
//supprime et deconnecte l'utilisateur

public function delete(){
  $bdd= mysqli_connect("localhost","root","","classes");
  $login = $this->login;
  $query = mysqli_query($bdd,"SELECT * FROM utilisateurs WHERE login = '$login'"); 
  if ($query) {
      $delete = mysqli_query($bdd, "DELETE FROM utilisateurs WHERE login = '$login'");
      echo "utilisateur supprimé";
  }
}
//modifie les informations de l'utilisateur en bdd
public function update($login, $password, $email, $firstname, $lastname){
  $bdd= mysqli_connect("localhost","root","","classes");

  $previousLogin = $this->login;
  $this->login = mysqli_real_escape_string($bdd,( trim($login)));
  $this->password = mysqli_real_escape_string($bdd,( trim($password)));
  $this->email = mysqli_real_escape_string($bdd,( trim($email)));
  $this->firstname = mysqli_real_escape_string($bdd,( trim($firstname)));
  $this->lastname = mysqli_real_escape_string($bdd,( trim($lastname)));

  $update = mysqli_query($bdd, "UPDATE utilisateurs SET login = '$login', password = '$password', email='$email', firstname='$firstname', lastname='$lastname' WHERE login ='$previousLogin'");
  echo "utilisateur mis à jour";
}
//retourne un booleen permettant de savoir si l'utilisateur est connecté ou non 
public function isConnected(){

  $login = $this->login;

  if($login){
    
      echo " Vous êtes Connecté ";
      
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

  $bdd = mysqli_connect("localhost", "root", "", "classes");
  $query = ("SELECT * FROM utilisateurs WHERE id = {$this->id}"); 
  $result=mysqli_query($bdd,$query);
  
  if ($result) {
    $verif = mysqli_fetch_assoc($result); 
    $this->login = $verif['login']; 
    $this->password = $verif['password']; 
    $this->email = $verif['email']; 
    $this->firstname = $verif['firstname']; 
    $this->lastname = $verif['lastname']; 
  }

}
}


