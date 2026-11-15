import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { getDepots, supprimerDepot } from '../../api/depots'
import Icone from '../../components/Icone'
import Pagination from '../../components/Pagination'
import ListeVide from '../../components/ListeVide'
import MessageErreur from '../../components/MessageErreur'

// Transforme une date "AAAA-MM-JJ" en "JJ/MM/AAAA".
function formaterDate(date) {
  const morceaux = date.split('-')
  return morceaux[2] + '/' + morceaux[1] + '/' + morceaux[0]
}

// Transforme une quantité 1250.5 en "1 250,50".
function formaterQuantite(quantite) {
  return Number(quantite).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

// Page qui affiche la liste des dépôts de stock, page par page.
function ListeDepots() {
  const [depots, setDepots] = useState([])
  const [page, setPage] = useState(1)
  // "meta" = informations de pagination envoyées par Laravel (page, total...).
  const [meta, setMeta] = useState(null)
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')
  const [erreurSuppression, setErreurSuppression] = useState('')

  // À chaque changement de page, on recharge les dépôts depuis l'API.
  useEffect(() => {
    // Demande à l'API la page courante des dépôts.
    async function chargerDepots() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getDepots(page)
        setDepots(reponse.data)
        setMeta(reponse.meta)
      } catch (e) {
        if (e.response && e.response.status === 403) {
          setErreur("Vous n'avez pas le droit de voir les dépôts.")
        } else {
          setErreur('Impossible de charger les dépôts.')
        }
      }
      setChargement(false)
    }

    chargerDepots()
  }, [page])

  // Demande confirmation puis supprime le dépôt.
  async function supprimer(depot) {
    const ok = window.confirm(
      'Le dépôt de ' + formaterQuantite(depot.quantite) + ' ' + depot.produit.unite + ' de ' + depot.produit.nom +
        ' (' + depot.membre.prenom + ' ' + depot.membre.nom + ') sera supprimé. Cette action est définitive. Continuer ?'
    )
    if (!ok) {
      return
    }

    setErreurSuppression('')
    try {
      await supprimerDepot(depot.id)
      // On retire la ligne supprimée du tableau.
      setDepots(depots.filter((d) => d.id !== depot.id))
    } catch (e) {
      if (e.response && e.response.status === 403) {
        setErreurSuppression("Vous n'avez pas le droit de supprimer un dépôt.")
      } else {
        setErreurSuppression('La suppression a échoué.')
      }
    }
  }

  return (
    <div>
      <div className="page-entete">
        <div>
          <h1>Dépôts de stock</h1>
          <p className="page-intro">Chaque récolte déposée au magasin par un membre.</p>
        </div>
        <Link to="/depots/nouveau" className="bouton">
          <Icone nom="plus" taille={18} />
          <span>Enregistrer un dépôt</span>
        </Link>
      </div>

      {erreurSuppression && <MessageErreur texte={erreurSuppression} />}

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && (
        <div className="cadre-tableau">
          {depots.length === 0 && (
            <ListeVide icone="depots" message="Aucun dépôt pour l'instant.">
              <Link to="/depots/nouveau" className="bouton">
                Enregistrer un dépôt
              </Link>
            </ListeVide>
          )}

          {depots.length > 0 && (
            <div>
              <div className="boite-tableau">
                <table className="tableau">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Membre</th>
                      <th>Produit</th>
                      <th className="colonne-nombre">Quantité</th>
                      <th className="colonne-actions">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    {depots.map((depot) => (
                      <tr key={depot.id}>
                        <td>{formaterDate(depot.date_depot)}</td>
                        <td className="texte-gras">
                          {depot.membre.prenom} {depot.membre.nom}
                        </td>
                        <td>{depot.produit.nom}</td>
                        <td className="colonne-nombre">
                          {formaterQuantite(depot.quantite)} {depot.produit.unite}
                        </td>
                        <td className="colonne-actions">
                          <Link
                            to={'/depots/' + depot.id + '/modifier'}
                            className="bouton-icone"
                            aria-label={'Modifier le dépôt du ' + formaterDate(depot.date_depot)}
                            title="Modifier"
                          >
                            <Icone nom="modifier" taille={19} />
                          </Link>
                          <button
                            type="button"
                            className="bouton-icone bouton-icone-danger"
                            onClick={() => supprimer(depot)}
                            aria-label={'Supprimer le dépôt du ' + formaterDate(depot.date_depot)}
                            title="Supprimer"
                          >
                            <Icone nom="supprimer" taille={19} />
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>

              {meta && <Pagination meta={meta} onChangerPage={setPage} />}
            </div>
          )}
        </div>
      )}
    </div>
  )
}

export default ListeDepots
