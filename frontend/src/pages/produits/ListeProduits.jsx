import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { getProduits, supprimerProduit } from '../../api/produits'
import Icone from '../../components/Icone'
import Pagination from '../../components/Pagination'
import ListeVide from '../../components/ListeVide'
import MessageErreur from '../../components/MessageErreur'

// Page qui affiche la liste des produits, page par page.
function ListeProduits() {
  const [produits, setProduits] = useState([])
  const [page, setPage] = useState(1)
  // "meta" = informations de pagination envoyées par Laravel (page, total...).
  const [meta, setMeta] = useState(null)
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')
  const [erreurSuppression, setErreurSuppression] = useState('')

  // À chaque changement de page, on recharge les produits depuis l'API.
  useEffect(() => {
    // Demande à l'API la page courante des produits.
    async function chargerProduits() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getProduits(page)
        setProduits(reponse.data)
        setMeta(reponse.meta)
      } catch (e) {
        if (e.response && e.response.status === 403) {
          setErreur("Vous n'avez pas le droit de voir les produits.")
        } else {
          setErreur('Impossible de charger les produits.')
        }
      }
      setChargement(false)
    }

    chargerProduits()
  }, [page])

  // Demande confirmation puis supprime le produit.
  async function supprimer(produit) {
    const ok = window.confirm(
      produit.nom + ' sera retiré de la liste des produits. Cette action est définitive. Continuer ?'
    )
    if (!ok) {
      return
    }

    setErreurSuppression('')
    try {
      await supprimerProduit(produit.id)
      // On retire la ligne supprimée du tableau.
      setProduits(produits.filter((p) => p.id !== produit.id))
    } catch (e) {
      if (e.response && e.response.status === 409) {
        setErreurSuppression(e.response.data.message)
      } else if (e.response && e.response.status === 403) {
        setErreurSuppression("Vous n'avez pas le droit de supprimer un produit.")
      } else {
        setErreurSuppression('La suppression a échoué.')
      }
    }
  }

  return (
    <div>
      <div className="page-entete">
        <div>
          <h1>Produits</h1>
          <p className="page-intro">Les récoltes que la coopérative reçoit et revend.</p>
        </div>
        <Link to="/produits/nouveau" className="bouton">
          <Icone nom="plus" taille={18} />
          <span>Ajouter un produit</span>
        </Link>
      </div>

      {erreurSuppression && <MessageErreur texte={erreurSuppression} />}

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && (
        <div className="cadre-tableau">
          {produits.length === 0 && (
            <ListeVide icone="produits" message="Aucun produit pour l'instant.">
              <Link to="/produits/nouveau" className="bouton">
                Ajouter un produit
              </Link>
            </ListeVide>
          )}

          {produits.length > 0 && (
            <div>
              <div className="boite-tableau">
                <table className="tableau">
                  <thead>
                    <tr>
                      <th>Nom</th>
                      <th>Catégorie</th>
                      <th>Unité</th>
                      <th className="colonne-actions">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    {produits.map((produit) => (
                      <tr key={produit.id}>
                        <td className="texte-gras">{produit.nom}</td>
                        <td>{produit.categorie}</td>
                        <td>{produit.unite}</td>
                        <td className="colonne-actions">
                          <Link
                            to={'/produits/' + produit.id + '/modifier'}
                            className="bouton-icone"
                            aria-label={'Modifier ' + produit.nom}
                            title="Modifier"
                          >
                            <Icone nom="modifier" taille={19} />
                          </Link>
                          <button
                            type="button"
                            className="bouton-icone bouton-icone-danger"
                            onClick={() => supprimer(produit)}
                            aria-label={'Supprimer ' + produit.nom}
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

export default ListeProduits
