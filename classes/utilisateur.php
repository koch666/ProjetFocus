<?php
/*-----------------------------------------
        Projet Focus
        creation date : 11 jan 2017
        classes/utilisateur.php
-----------------------------------------*/

class utilisateur {

  private $id;
  private $nom;
  private $email;
  private $autorisations;

// construction du nouvel utilisation dans la BDD

// on initilise à 0 pour dire que si on a un id(1.2.....) on a déjà un utilisateur donc on se connecte, 
// sinon on a des champs à remplir pour un nouvelle utilisateur

  function __construct($id = 0) {
          if($id!=0) {
              $res = sql("SELECT id,nom,email,autorisations FROM utilisateur WHERE id='".$id."'");
              $utilisateur = $res[0];
              $this->id = $utilisateur['id'];
              $this->nom = $utilisateur['nom'];
              $this->email = $utilisateur['email'];
              $this->autorisations = $utilisateur['autorisations'];
            }



//fonction de lecture et d'envoi en bdd
  }

  function getId() {
    return $this->id;

  }

  function getNom() {
    return $this->nom;

  }

  function setNom($nom) {
    return $this->nom = $nom;

  }

  function getEmail(){
    return $this->email;

  }

  function setEmail($email){
    return $this->email = $email;
    return TRUE;

  }

  function getAutorisations(){
    return $this->autorisations;

  }

  function setAutorisations($autorisations){
    return $this->autorisations = $autorisations;
    return TRUE;
  }


// fonction d'écriture et de mise à jour dans la BDD

  function syncDb() {
    if(empty($this->id)) {
      // si $this->id est vide, on fait un INSERT
      $res = sql("INSERT INTO utilisateur (nom, email, autorisations)
        VALUES (
        '".addslashes($this->nom)."',
        '".addslashes($this->email)."',
        '".addslashes($this->autorisations)."')");
     if($res !== FALSE) {
        $this->id = $res;
        return TRUE;
      }
      else {
        return FALSE;
      }

    }
    else{
      // Sinon on fait un update
      $res = sql("UPDATE nom SET
        nom ='".addslashes($this->nom)."',
        email = '".addslashes($this->email)."',
        autorisations = '".addslashes($this->autorisations)."'
        WHERE id='".addslashes($this->id)."';");
      return $res;
    }
  }


//mise à jour du mot de passe utilisateur

  function updateMdp($mdp) {
    if(empty($this->id)){
      return FALSE;
    }
    $res = sql("UPDATE utilisateur set mdp = '".crypt($mdp, CRYPT_SALT)."'
     WHERE id='".$this->id."'");
  return $res;
    }
  

  //  fonction de connection à l'application

  static function connect($nom, $mdp) {
    $res = sql("SELECT id 
      FROM utilisateur 
      WHERE nom ='".$nom."'
      AND mdp ='".crypt($mdp, CRYPT_SALT)."'");
    if($res != NULL ){
      return $res;
    }
    else {
      return NULL;
    }
  }


//deconnection..

   function disconnect() {
         session_destroy();
         header('Location: login.php');
      }


// formulaire de creation d'utilisateur de l'aplication

  function form($target, $submit = ''){
    ?>
          <section class="container">
              <div class="row">
                  <div class="col-md-offset-3 col-md-6">
                    <form action="<?php echo $target; ?>" method="post">
                      <label for="nom">Nom d'utilisateur</label>
                        <input type="text" name="nom" value="<?php echo $this->nom; ?>">
                      
                      <label for="email">Courriel</label>
                        <input type="email" name="email" value="<?php echo $this->email; ?>">

                      <label for="autorisations">autorisations</label>
                        <select type="text" name="autorisations">
                            <option value="2">utilisateur</option>
                            <option value="1">administrateur</option>
                         </select><br>
                
                      <label for="password">Mot de Passe</label>
                        <input type="password" name="mdp1">
                        <input type="password" name="mdp2">

                      <input type="submit" value="<?php echo $submit==''?'Envoyer':$submit; ?>">
                    </form>
                  </div>
              </div>
          </section>
    <?php
  }


// fonction effacer de la BDD

function delete() {
        $resDeleteUser = sql("
            DELETE FROM utilisateur
            WHERE id='".$this->id."'
            ");
        
        if($resDeleteUser) {
            return TRUE;
            header('Location: utilisateur.php');
        }
        else {
            return FALSE;
        }
    }

}
