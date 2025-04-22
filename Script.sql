-- Afficher les freelances qui parlent l’anglais (langues.nom = 'Anglais') avec un niveau avancé:
  USE freelance_platform;
    SELECT * FROM utilisateurs
    INNER JOIN profils on utilisateurs.id = profils.utilisateur_id 
    INNER JOIN profil_langue on profils.id=profil_langue.profil_id 
    INNER JOIN langues on profil_langue.langue_id = langues.id
    WHERE utilisateurs.role='freelance' AND langues.nom = 'Anglais' AND profil_langue.niveau='avancé'
    --Lister les freelances ayant plus de 3 compétences.


 SELECT * FROM utilisateurs
  INNER JOIN profils ON utilisateurs.id =profils.utilisateur_id
  INNER JOIN profil_competence on profils.id=profil_competence.profil_id
  INNER JOIN competences on profil_competence.competence_id=competences.id
  WHERE competences.id>3    

  --Afficher les freelances disponibles, leur tarif horaire et leur ville.
     SELECT utilisateurs.*, profils.tarif_horaire, adresses.ville FROM utilisateurs
 INNER JOIN adresses ON utilisateurs.id=adresses.utilisateur_id 
 INNER JOIN profils on utilisateurs.id=profils.utilisateur_id
 WHERE utilisateurs.role='freelance'
  --Lister tous les utilisateurs qui ne possèdent pas encore de profil.
  SELECT * FROM utilisateurs 
  WHERE utilisateurs.id NOT IN(SELECT profils.utilisateur_id FROM profils)
-- Afficher les clients qui n'ont jamais publié de projet.

     SELECT * FROM utilisateurs 
  WHERE utilisateurs.role='client' AND utilisateurs.id NOT IN(SELECT projets.client_id FROM projets)


  -- Afficher les projets ouverts avec leur budget et leur nombre total d’offres reçues.
SELECT projets.budget, projets.statut, COUNT(offres.id)as nombre__totaloffres FROM projets
INNER JOIN offres on projets.id=offres.projet_id
WHERE projets.statut='ouvert'

--7--Lister les offres envoyées par des freelances dont le tarif horaire est inférieur à 100 MAD.
SELECT 
    offres.id AS offre_id,
    offres.projet_id,
    offres.freelance_id,
    utilisateurs.nom AS nom_freelance,
    profils.tarif_horaire,
    offres.prix_propose,
    offres.delai_propose,
    offres.message,
    offres.date_envoi
FROM offres
JOIN utilisateurs ON offres.freelance_id = utilisateurs.id
JOIN profils ON utilisateurs.id = profils.utilisateur_id
WHERE utilisateurs.role = 'freelance'
  AND profils.tarif_horaire < 100;



--8--Afficher les projets qui ont reçu au moins 3 offres.
SELECT projets.*, COUNT(offres.id) as nombre_offres FROM projets
INNER JOIN offres on projets.id=offres.projet_id
WHERE projets.statut='ouvert'
GROUP BY projets.id
HAVING COUNT(offres.id) >= 3

--9--Afficher les freelances qui ont postulé sur plus de 5 projets différents.
SELECT utilisateurs.*, COUNT(DISTINCT offres.projet_id) as nombre_projets_postules FROM utilisateurs
INNER JOIN offres on utilisateurs.id=offres.freelance_id
WHERE utilisateurs.role='freelance'

--10--Afficher les projets terminés avec les dates de début et de fin des missions associées
SELECT projets.*, missions.date_debut, missions.date_fin FROM projets
INNER JOIN offres ON projets.id=offres.projet_id 
INNER JOIN missions ON offres.id=missions.offre_id
WHERE projets.statut='terminé'

--11--Lister les factures payées avec le nom du freelance, le montant, et le moyen de paiement.
  SELECT factures.*, utilisateurs.nom, transactions.moyen_paiement as moyen_paiement FROM factures
INNER JOIN transactions ON factures.id= transactions.facture_id
INNER JOIN missions on factures.mission_id= missions.id
INNER JOIN offres on missions.offre_id=offres.id
INNER JOIN utilisateurs on offres.freelance_id = utilisateurs.id
WHERE factures.paye=1

--12--Afficher le total des gains par freelance (somme des factures payées).
SELECT utilisateurs.nom, SUM(factures.montant) as total_gains FROM utilisateurs
INNER JOIN offres ON utilisateurs.id=offres.freelance_id
INNER JOIN missions ON offres.id=missions.offre_id
INNER JOIN factures ON missions.id=factures.mission_id
WHERE factures.paye=1
GROUP BY utilisateurs.nom   
--13--Lister les missions validées dont le paiement n’a pas encore été effectué.
SELECT missions.*, factures.paye FROM missions
INNER JOIN factures ON missions.id=factures.mission_id
WHERE missions.statut='validée' AND factures.paye=0
--14--Afficher les freelances qui ont généré plus de 20 000 MAD de chiffre d’affaires.
SELECT utilisateurs.nom, SUM(factures.montant) as chiffre_affaires FROM utilisateurs
INNER JOIN offres ON utilisateurs.id=offres.freelance_id                
INNER JOIN missions ON offres.id=missions.offre_id
INNER JOIN factures ON missions.id=factures.mission_id
WHERE factures.paye=1
GROUP BY utilisateurs.nom   
HAVING  SUM(factures.montant)>20000.00

--15--Afficher les clients dont les projets ont généré plus de 10 000 MAD de dépenses.
SELECT utilisateurs.nom, SUM(factures.montant) as depenses FROM utilisateurs
INNER JOIN projets ON utilisateurs.id=projets.client_id
INNER JOIN offres ON projets.id=offres.projet_id
INNER JOIN missions ON offres.id=missions.offre_id
INNER JOIN factures ON missions.id=factures.mission_id
WHERE factures.paye=1
GROUP BY utilisateurs.nom
HAVING SUM(factures.montant)>10000.00

--16--Afficher la note moyenne obtenue par chaque freelance.
SELECT utilisateurs.*, evaluations.note
FROM utilisateurs
INNER JOIN offres ON utilisateurs.id=offres.freelance_id 
INNER JOIN missions ON offres.id = missions.offre_id
INNER JOIN evaluations ON missions.id = evaluations.mission_id
--17--Lister les missions ayant une évaluation inférieure à 3 avec le commentaire associé.
SELECT utilisateurs.*, evaluations.note, evaluations.commentaire
FROM utilisateurs
INNER JOIN offres ON utilisateurs.id=offres.freelance_id 
INNER JOIN missions ON offres.id = missions.offre_id
INNER JOIN evaluations ON missions.id = evaluations.mission_id
WHERE evaluations.note<=3
--18--Afficher le top 5 des freelances les mieux notés (note moyenne).
 SELECT utilisateurs.*, AVG(evaluations.note) as note_moyenne
FROM utilisateurs
INNER JOIN offres ON utilisateurs.id=offres.freelance_id
INNER JOIN missions ON offres.id = missions.offre_id
INNER JOIN evaluations ON missions.id = evaluations.mission_id
GROUP BY utilisateurs.id
HAVING AVG(evaluations.note)
ORDER BY note_moyenne DESC
LIMIT 5

--19--Afficher les projets sans offres reçues.


SELECT projets.*, COUNT(offres.id) as nombre_offres FROM projets
INNER JOIN offres on projets.id=offres.projet_id
WHERE projets.statut='ouvert' 
GROUP BY projets.id
HAVING COUNT(offres.id) = 0