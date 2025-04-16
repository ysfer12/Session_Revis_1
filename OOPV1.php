<?php 


interface ReservableInterface {
  public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation;
}

abstract Class Vehicule{
  protected $id;
  protected $immatriculation;
  protected $marque;
  protected $modele;
  protected $prixJour;
  protected $disponible;
  
  public function __construct($id, $immatriculation, $marque, $modele, $prixJour, $disponible){
    $this->id = $id;
    $this->immatriculation = $immatriculation;
    $this->marque = $marque;
    $this->modele = $modele;
    $this->prixJour = $prixJour;
    $this->disponible = $disponible;
  }
  public function getId(){return $this->id;}
  public function getImmatriculation(){return $this->immatriculation;}
  public function getMarque(){return $this->marque;}
  public function getModele(){return $this->modele;}
  public function getPrixJour(){return $this->prixJour;}
  public function getDisponible(){return $this->disponible;}

  public function setId($id){$this->id=$id;}
  public function setImmatriculation($immatriculation){$this->immatriculation=$immatriculation;}
  public function setMarque($marque){$this->marque=$marque;}
  public function setModele($modele){$this->modele=$modele;}
  public function setPrixJour($prixJour){$this->prixJour=$prixJour;}
  public function setDisponible($disponible){$this->disponible=$disponible;}

  
  
  public function afficherDetails(){
    echo "ID: ".$this->id."\n";
    echo "Immatriculation: ".$this->immatriculation."\n";
    echo "Marque: ".$this->marque."\n";
    echo "Modèle: ".$this->modele."\n";
    echo "Prix par jour: ".$this->prixJour."\n";
    echo "Disponible: ".($this->disponible ? "Oui" : "Non")."\n";
  }
  public function calculerPrix(int $jours){
    $prixTotal;
    $this->$prixTotal = prixJour*jours;
    return $prixTotal;
  }
  public function estDisponible(){
    $this->disponible = true;
  }
  abstract protected function getType();
}


Class Voiture extends Vehicule{
  private $nbPortes;
  private $transmission;
  public function __construct($nbPortes, $transmission){
    Parent::__construct();
    $this->nbPortes=$nbPortes;
    $this->transmission=$transmission;
  }
  
  public function afficherDetails(){
    Parent::afficherDetails()."".$this->nbPortes."".$this->transmission;
  }
  
  public function getType(){
    return "Voiture";
  }
  public function reserver(){
    $reservation= new Reservation($this,$client, $dateDebut, $nbJours);
    $this->disponible->setDisponible(false);
    return $reservation;
  }
  
}

Class Moto extends Vehicule{
  private $cylindree;
  public function __construct($cylindree){
    Parent::__construct();
    $this->$cylindree=$cylindree;
  }
  
  public function afficherDetails(){
    Parent::afficherDetails()."".$this->cylindree;
  }
  
  public function getType(){
    return "Moto";
  }
  public function reserver(){
      
      $reservation= new Reservation($this,$client, $dateDebut, $nbJours);
      $this->disponible->setDisponible(false);
      return $reservation;
  }
}

Class Camion extends Vehicule{
  private $capaciteTonnage;
  public function __construct($capaciteTonnage){
    Parent::__construct();
    $this->capaciteTonnage=$capaciteTonnage;
  }
  
  public function afficherDetails(){
    Parent::afficherDetails()."".$this->capaciteTonnage;
  }
  
  public function getType(){
    return "Camion";
  }
  
  public function reserver(){
    
    $reservation= new Reservation($this,$client, $dateDebut, $nbJours);
    $this->disponible->setDisponible(false);
    return $reservation;
  }
  
}

abstract Class Personne{
  private $nom;
  private $prenom;
  private $email;
  
  public function __construct($nom,$prenom,$email){
    $this->nom=$nom;
    $this->prenom=$prenom;
    $this->email=$email;
  }
  public function getNom(){return $this->nom;}
  public function getPrenom(){return $this->prenom;}
  public function getEmail(){return $this->email;}

  public function setNom($nom){$this->nom=$nom;}
  public function setPrenom($prenom){$this->prenom=$prenom;}
  public function setEmail($email){$this->email=$email;}

  public abstract function afficherProfil();
  
}

Class Client extends Personne{
  private $numeroClient;
  private $reservations =[];
  
public function __construct($numeroClient, $reservations){
  Parent::__construct();
  $this->numeroClient=$numeroClient;
  $this->reservations=$reservations;
}
public function afficherProfil(){
  echo "Nom: ".$this->nom."\n";
  echo "Prénom: ".$this->prenom."\n";
  echo "Email: ".$this->email."\n";
  echo "Numéro Client: ".$this->numeroClient."\n";
  echo "Réservations: \n";
  foreach($this->reservations as $reservation){
    $reservation->afficherDetails();
  }
}
public function getHistorique()
{
  $historique = [];
  foreach ($this->reservations as $reservation) {
    $historique[] = [
      'vehicule' => $reservation->getVehicule(),
      'dateDebut' => $reservation->getDateDebut(),
      'nbJours' => $reservation->getNbJours(),
      'montant' => $reservation->calculerMontant()
    ];
  }
  return $historique;
}

}



Class Agence{
  private $nom;
  private $ville;
  private $vehicules = [];
  private $clients=[];

  public function __construct($nom, $ville){
    $this->nom=$nom;
    $this->ville=$ville;
  }
  public function getNom(){return $this->nom;}
  public function getVille(){return $this->ville;}

  public function setNom($nom){$this->nom=$nom;}
  public function setVille($ville){$this->ville=$ville;}

  public function ajouterVehicule(Vehicule $v){
    
    $this->vehicules[] = $v;
  }
  
  public function rechercherVehiculeDisponible(string $type)
{
  
  $resultat = [];
  foreach ($this->vehicules as $vehicule) {
    if ($vehicule->getType() === $type && $vehicule->estDisponible()) {
      $resultat[] = $vehicule;
    }
  }
  return $resultat;
}
public function enregistrerClient(Client $c){
  
  $this->clients[] = $c;
  return $c;
}

public function faireReservation(Client $client, Vehicule $v, DateTime $dateDebut, int $nbJours){
  
  if ($v->estDisponible()) {
    $reservation = $v->reserver($client, $dateDebut, $nbJours);
    $client->ajouterReservation($reservation);
    return $reservation;
  } else {
    throw new Exception("Le véhicule n'est pas disponible.");
  }
}
}

Class Reservation{
  private $vehicule;
  private $client;
  private $dateDebut;
  private $nbJours;
  private $statut;
  
  public function __construct($vehicule, $client, $dateDebut, $nbJours, $statut){
    $this->vehicule=$vehicule;
    $this->client=$client;
    $this->dateDebut=$dateDebut;
    $this->nbrJours=$nbJours;
    $this->statut=$statut;

  }
  public function getVehicule(){return $this->vehicule;}
  public function getClient(){return $this->client;}
  public function getDateDebut(){return $this->dateDebut;}
  public function getNbJours(){return $this->nbJours;}
  public function getStatut(){return $this->statut;}
  
  public function setVehicule($vehicule){$this->vehicule=$vehicule;}
  public function setClient($client){$this->client=$client;}
  public function setDateDebut($dateDebut){$this->dateDebut=$dateDebut;}
  public function setNbJours($nbJours){$this->nbJours=$nbJours;}
  public function setStatut($statut){$this->statut=$statut;}

    public function calculerMontant(){
      return $this->vehicule->calculerPrix($this->nbJours);
    }
    public function confirmer(){
      $this->statut = "confirmée";
    }
    public function annuler(){
      $this->statut = "annulée";
    }


  }


//execution

$voiture = new Voiture(1, "57-A-87388", "Toyota", "Corolla", 50, true, 4, "Automatique");
$moto = new Moto(2, "1-W-11111", "Yamaha", "R1", 30, true, 1000);
$camion = new Camion(3, "57-A-57575", "Volvo", "FH16", 100, true, 20);

$client = new Client("YOUSSEF", "ERR", "YSFER@gmail.com", 101);


$agence = new Agence("Agence Centrale", "Paris");

$agence->ajouterVehicule($voiture);
$agence->ajouterVehicule($moto);
$agence->ajouterVehicule($camion);

$dateDebut = new DateTime('2023-10-01');
$reservation = $agence->faireReservation($client, $voiture, $dateDebut, 5);

echo "Réservation effectuée :\n";
echo "Client : " . $client->getNom() . " " . $client->getPrenom() . "\n";
echo "Véhicule : " . $voiture->getMarque() . " " . $voiture->getModele() . "\n";
echo "Date de début : " . $reservation->getDateDebut()->format('Y-m-d') . "\n";
echo "Nombre de jours : " . $reservation->getNbJours() . "\n";
echo "Montant total : " . $reservation->calculerMontant() . "€\n";
echo "Statut de la réservation : " . $reservation->getStatut() . "\n";

