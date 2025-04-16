let candidatures = [];
let cptID = 1;

function ajouterCandidature(nom, age, projet) {
  const nouvelleCandidature = {
    id: cptID++,
    nom: nom,
    age: age,
    projet: projet,
    statut: "en attente"
  };
  candidatures.push(nouvelleCandidature);
  return candidatures;
}

function afficherToutesLesCandidatures() {
  for (let i = 0; i < candidatures.length; i++) {
    const candidature = candidatures[i];
    console.log("ID: " + candidature.id + ", Nom: " + candidature.nom + ", Age: " + candidature.age + ", Projet: " + candidature.projet + ", Statut: " + candidature.statut);
  }
}

function validerCandidature(id) {
  for (let i = 0; i < candidatures.length; i++) {
    if (candidatures[i].id === id) {
      candidatures[i].statut = "validée";
      return candidatures[i];
    }
  }
}

function rejeterCandidature(id) {
  for (let i = 0; i < candidatures.length; i++) {
    if (candidatures[i].id === id) {
      candidatures[i].statut = "rejetée";
      return candidatures[i];
    }
  }
}

function rechercherCandidat(nom) {
  for (let i = 0; i < candidatures.length; i++) {
    if (candidatures[i].nom === nom) {
        console.log("Informations du candidat trouvé: ID: " + candidatures[i].id + ", Nom: " + candidatures[i].nom + ", Age: " + candidatures[i].age + ", Projet: " + candidatures[i].projet + ", Statut: " + candidatures[i].statut);
    }
  }
}

function filtrerParStatut(statut) {
  return candidatures.filter(candidature => candidature.statut === statut);
}

function statistiques() {
  let totalCandidatures = candidatures.length;
  let totalValidees = candidatures.filter(c => c.statut === "validée").length;
  let totalRejetees = candidatures.filter(c => c.statut === "rejetée").length;
  let totalEnAttente = candidatures.filter(c => c.statut === "en attente").length;

  console.log("Total candidatures:", totalCandidatures);
  console.log("Validées:", totalValidees);
  console.log("Rejetées:", totalRejetees);
  console.log("En attente:", totalEnAttente);
}


function trierParNom() {
  candidatures.sort((a, b) => a.nom.localeCompare(b.nom));
  afficherToutesLesCandidatures();
}
function resetSysteme(){
  candidatures=[]
  cptID=1
  console.log("la liste des candidatures a été bien réinitialiser")
  return candidatures
}
function topProjets(motCle) {
    let projets = candidatures.map(candidature => candidature.projet);
    let results = projets.filter(projet => projet.includes(motCle));
    console.log("Les projets qui contiennent le mot clé sont:", results);
    return results;
}


ajouterCandidature("Ali", 25, "Projet A");
ajouterCandidature("Sara", 28, "Projet B");
ajouterCandidature("youssef", 30, "Projet C");
ajouterCandidature("hamid", 22, "Projet A");
ajouterCandidature("saçd", 89, "Projet E");
ajouterCandidature("said", 28, "Projet F");
ajouterCandidature("moahmed", 77, "Projet A");
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

validerCandidature(1);
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

rejeterCandidature(2);
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

validerCandidature(6);
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

rejeterCandidature(7);
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

afficherToutesLesCandidatures();
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

statistiques();
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

rechercherCandidat("youssef");
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

trierParNom();
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")

topProjets("Projet A")
console.log("---***---***---***---***---***---***---***---***---***---***---***---***---")
resetSysteme()