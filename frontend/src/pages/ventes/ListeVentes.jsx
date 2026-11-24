import { useContext, useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { AuthContext } from '../../context/AuthContext'
import { getVentes, supprimerVente } from '../../api/ventes'
import Icone from '../../components/Icone'
import Pagination from '../../components/Pagination'
import ListeVide from '../../components/ListeVide'
import MessageErreur from '../../components/MessageErreur'

// Transforme une date "AAAA-MM-JJ" en "JJ/MM/AAAA".
function formaterDate(date) {
  const morceaux = date.split('-')
  return morceaux[2] + '/' + morceaux[1] + '/' + morceaux[0]
}

// Transforme un nombre 1250.5 en "1 250,50".
function formaterNombre(nombre) {
  return Number(nombre).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

// Page qui affiche la liste des ventes groupées, page par page.
function ListeVentes() {
  const { utilisateur } = useContext(AuthContext)
  // Les deux rôles peuvent enregistrer ou supprimer une vente.
  const peutGerer = ['admin', 'gestionnaire'].includes(utilisateur.role)

  const [ventes, setVentes] = useState([])
  const [page, setPage] = useState(1)
  // "meta" = informations de pagination envoyées par Laravel (page, total...).
  const [meta, setMeta] = useState(null)
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')
  const [erreurSuppression, setErreurSuppression] = useState('')

  // À chaque changement de page, on recharge les ventes depuis l'API.
  useEffect(() => {
    // Demande à l'API la page courante des ventes.
    async function chargerVentes() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getVentes(page)
        setVentes(reponse.data)
        setMeta(reponse.meta)
      } catch (e) {
        if (e.response && e.response.status === 403) {
          setErreur("Vous n'avez pas le droit de voir les ventes.")
        } else {
          setErreur('Impossible de charger les ventes.')
        }
      }
      setChargement(false)
    }

    chargerVentes()
  }, [page])

  // Demande confirmation puis supprime la vente.
  async function supprimer(vente) {
    const ok = window.confirm(
      'La vente du ' + formaterDate(vente.date_vente) + ' à ' + vente.acheteur.nom + ' (' +
        formaterNombre(vente.quantite) + ' ' + vente.produit.unite + ' de ' + vente.produit.nom +
        ') sera supprimée. La quantité reviendra dans le stock. Continuer ?'
    )
    if (!ok) {
      return
    }

    setErreurSuppression('')
    try {
      await supprimerVente(vente.id)
      // On retire la ligne supprimée du tableau.
      setVentes(ventes.filter((v) => v.id !== vente.id))
    } catch (e) {
      if (e.response && e.response.status === 403) {
        setErreurSuppression("Vous n'avez pas le droit de supprimer une vente.")
      } else {
        setErreurSuppression('La suppression a échoué.')
      }
    }
  }

  return (
    <div>
      <div className="page-entete">
        <div>
          <h1>Ventes groupées</h1>
          <p className="page-intro">Les ventes en gros. Une vente enregistrée ne se modifie pas.</p>
        </div>
        {peutGerer && (
          <Link to="/ventes/nouvelle" className="bouton">
            <Icone nom="plus" taille={18} />
            <span>Nouvelle vente</span>
          </Link>
        )}
      </div>

      {erreurSuppression && <MessageErreur texte={erreurSuppression} />}

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && (
        <div className="cadre-tableau">
          {ventes.length === 0 && (
            <ListeVide icone="ventes" message="Aucune vente pour l'instant.">
              {peutGerer && (
                <Link to="/ventes/nouvelle" className="bouton">
                  Nouvelle vente
                </Link>
              )}
            </ListeVide>
          )}

          {ventes.length > 0 && (
            <div>
              <div className="boite-tableau">
                <table className="tableau">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Produit</th>
                      <th>Acheteur</th>
                      <th className="colonne-nombre">Quantité</th>
                      <th className="colonne-nombre">Prix unitaire</th>
                      <th className="colonne-nombre">Montant</th>
                      {peutGerer && <th className="colonne-actions">Actions</th>}
                    </tr>
                  </thead>
                  <tbody>
                    {ventes.map((vente) => (
                      <tr key={vente.id}>
                        <td>{formaterDate(vente.date_vente)}</td>
                        <td className="texte-gras">{vente.produit.nom}</td>
                        <td>{vente.acheteur.nom}</td>
                        <td className="colonne-nombre">
                          {formaterNombre(vente.quantite)} {vente.produit.unite}
                        </td>
                        <td className="colonne-nombre">{formaterNombre(vente.prix_unitaire)} DH</td>
                        <td className="colonne-nombre texte-gras">{formaterNombre(vente.montant_total)} DH</td>
                        {peutGerer && (
                          <td className="colonne-actions">
                            <button
                              type="button"
                              className="bouton-icone bouton-icone-danger"
                              onClick={() => supprimer(vente)}
                              aria-label={'Supprimer la vente du ' + formaterDate(vente.date_vente)}
                              title="Supprimer"
                            >
                              <Icone nom="supprimer" taille={19} />
                            </button>
                          </td>
                        )}
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

export default ListeVentes
