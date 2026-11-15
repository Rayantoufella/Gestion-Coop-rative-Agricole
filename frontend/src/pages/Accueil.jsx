import { useContext, useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { AuthContext } from '../context/AuthContext'
import { getDashboard } from '../api/dashboard'
import Carte from '../components/Carte'
import Silo from '../components/Silo'
import Icone from '../components/Icone'
import MessageErreur from '../components/MessageErreur'

// Transforme une date "AAAA-MM-JJ" en "JJ/MM/AAAA".
function formaterDate(date) {
  const morceaux = date.split('-')
  return morceaux[2] + '/' + morceaux[1] + '/' + morceaux[0]
}

// Transforme un nombre 1250.5 en "1 250,50".
function formaterNombre(nombre) {
  return Number(nombre).toLocaleString('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })
}

// Renvoie la date du jour, par exemple "dimanche 15/11/2026".
function dateDuJour() {
  const maintenant = new Date()
  return (
    maintenant.toLocaleDateString('fr-FR', { weekday: 'long' }) +
    ' ' +
    maintenant.toLocaleDateString('fr-FR')
  )
}

// Renvoie le mois en cours, par exemple "novembre".
function moisEnCours() {
  return new Date().toLocaleDateString('fr-FR', { month: 'long' })
}

// Renvoie l'année en cours, par exemple 2026.
function anneeEnCours() {
  return new Date().getFullYear()
}

// Renvoie true si l'utilisateur a demandé moins d'animations dans son système.
function animationsReduites() {
  return window.matchMedia('(prefers-reduced-motion: reduce)').matches
}

// Tableau de bord : silos, chiffres du mois et dernières ventes, lus depuis l'API.
function Accueil() {
  const { utilisateur } = useContext(AuthContext)
  const estAdmin = utilisateur.role === 'admin'

  const [statistiques, setStatistiques] = useState(null)
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')
  // Avancement du défilement des chiffres : de 0 (début) à 1 (valeurs finales).
  // Si l'utilisateur a demandé moins d'animations, on commence directement à 1.
  const [progression, setProgression] = useState(animationsReduites() ? 1 : 0)

  // Au démarrage, on charge les statistiques depuis l'API.
  useEffect(() => {
    // Demande à l'API les chiffres du tableau de bord.
    async function chargerStatistiques() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getDashboard()
        setStatistiques(reponse)
      } catch (e) {
        console.log('Erreur pendant le chargement du tableau de bord', e)
        setErreur('Impossible de charger le tableau de bord.')
      }
      setChargement(false)
    }

    chargerStatistiques()
  }, [])

  // Quand les statistiques arrivent, les chiffres défilent de 0 jusqu'à leur valeur en 1,1 seconde.
  useEffect(() => {
    // Pas encore de données, ou animations réduites : pas de défilement.
    if (!statistiques || animationsReduites()) {
      return
    }

    const debut = Date.now()
    const minuteur = setInterval(() => {
      // t va de 0 à 1 pendant 1100 ms.
      const t = Math.min(1, (Date.now() - debut) / 1100)
      // Formule "ease-out" : rapide au début, puis ralentit à la fin.
      setProgression(1 - Math.pow(1 - t, 3))
      if (t >= 1) {
        clearInterval(minuteur)
      }
    }, 33)

    // Si on quitte la page avant la fin, on arrête le minuteur.
    return () => clearInterval(minuteur)
  }, [statistiques])

  return (
    <div>
      <div className="page-entete">
        <div>
          <p className="page-surtitre">{dateDuJour()}</p>
          <h1>Le magasin aujourd'hui</h1>
        </div>

        {/* Raccourcis différents selon le rôle, comme dans le prototype */}
        {estAdmin ? (
          <div className="raccourcis">
            <Link to="/cotisations/nouvelle" className="bouton-secondaire">
              <Icone nom="cotisations" taille={18} />
              <span>Enregistrer une cotisation</span>
            </Link>
            <Link to="/membres/nouveau" className="bouton">
              <Icone nom="plus" taille={18} />
              <span>Ajouter un membre</span>
            </Link>
          </div>
        ) : (
          <div className="raccourcis">
            <Link to="/depots/nouveau" className="bouton-secondaire">
              <Icone nom="depots" taille={18} />
              <span>Enregistrer un dépôt</span>
            </Link>
            <Link to="/ventes/nouvelle" className="bouton">
              <Icone nom="plus" taille={18} />
              <span>Nouvelle vente</span>
            </Link>
          </div>
        )}
      </div>

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && statistiques && (
        <div className="accueil">
          <div className="accueil-haut">
            {/* À gauche : les silos (5 premiers produits) */}
            <section className="accueil-silos">
              <div className="accueil-silos-titre">
                <h2>Stock disponible</h2>
                {!estAdmin && (
                  <Link to="/stock" className="lien-souligne">
                    Voir le détail du stock
                  </Link>
                )}
              </div>
              <div className="rangee-silos">
                {statistiques.stock.slice(0, 5).map((produit) => (
                  <Silo key={produit.id} produit={produit} taille="accueil" />
                ))}
              </div>
            </section>

            {/* À droite : le registre du mois avec son tampon */}
            <section className="registre">
              <h2>Registre du mois</h2>
              <div className="tampon" aria-hidden="true">
                {moisEnCours()}
                <br />
                {anneeEnCours()}
              </div>
              {/* Chaque valeur est multipliée par "progression" pour le défilement */}
              <Carte titre="membres actifs" valeur={Math.round(statistiques.membres.actifs * progression)} />
              <Carte
                titre="cotisations du mois"
                valeur={formaterNombre(statistiques.cotisations_mois.montant * progression) + ' DH'}
              />
              <Carte titre="ventes du mois" valeur={Math.round(statistiques.ventes_mois.nombre * progression)} />
              <Carte
                titre="chiffre d'affaires du mois"
                valeur={formaterNombre(statistiques.ventes_mois.chiffre_affaires * progression) + ' DH'}
              />
            </section>
          </div>

          {/* En bas : les 5 dernières ventes */}
          <section className="accueil-ventes">
            <h2>Les 5 dernières ventes</h2>

            {statistiques.ventes_recentes.length === 0 && (
              <p className="texte-doux">Aucune vente pour l'instant.</p>
            )}

            {statistiques.ventes_recentes.length > 0 && (
              <div className="boite-tableau">
                <table className="tableau tableau-simple">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Produit</th>
                      <th>Acheteur</th>
                      <th className="colonne-nombre">Quantité</th>
                      <th className="colonne-nombre">Montant</th>
                    </tr>
                  </thead>
                  <tbody>
                    {statistiques.ventes_recentes.map((vente) => (
                      <tr key={vente.id}>
                        <td>{formaterDate(vente.date_vente)}</td>
                        <td className="texte-gras">{vente.produit}</td>
                        <td>{vente.acheteur}</td>
                        <td className="colonne-nombre">{formaterNombre(vente.quantite)}</td>
                        <td className="colonne-nombre texte-gras">
                          {formaterNombre(vente.quantite * vente.prix_unitaire)} DH
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </section>
        </div>
      )}
    </div>
  )
}

export default Accueil
