import { useContext, useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { AuthContext } from '../../context/AuthContext'
import { getAcheteurs, supprimerAcheteur } from '../../api/acheteurs'
import Icone from '../../components/Icone'
import Pagination from '../../components/Pagination'
import ListeVide from '../../components/ListeVide'
import MessageErreur from '../../components/MessageErreur'

// Page qui affiche la liste des acheteurs, page par page.
function ListeAcheteurs() {
  const { utilisateur } = useContext(AuthContext)
  // Dans le backend, seul le gestionnaire peut ajouter, modifier ou supprimer un acheteur.
  const estGestionnaire = utilisateur.role === 'gestionnaire'

  const [acheteurs, setAcheteurs] = useState([])
  const [page, setPage] = useState(1)
  // "meta" = informations de pagination envoyées par Laravel (page, total...).
  const [meta, setMeta] = useState(null)
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')
  const [erreurSuppression, setErreurSuppression] = useState('')

  // À chaque changement de page, on recharge les acheteurs depuis l'API.
  useEffect(() => {
    // Demande à l'API la page courante des acheteurs.
    async function chargerAcheteurs() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getAcheteurs(page)
        setAcheteurs(reponse.data)
        setMeta(reponse.meta)
      } catch (e) {
        if (e.response && e.response.status === 403) {
          setErreur("Vous n'avez pas le droit de voir les acheteurs.")
        } else {
          setErreur('Impossible de charger les acheteurs.')
        }
      }
      setChargement(false)
    }

    chargerAcheteurs()
  }, [page])

  // Demande confirmation puis supprime l'acheteur.
  async function supprimer(acheteur) {
    const ok = window.confirm(
      acheteur.nom + ' sera retiré de la liste des acheteurs. Cette action est définitive. Continuer ?'
    )
    if (!ok) {
      return
    }

    setErreurSuppression('')
    try {
      await supprimerAcheteur(acheteur.id)
      // On retire la ligne supprimée du tableau.
      setAcheteurs(acheteurs.filter((a) => a.id !== acheteur.id))
    } catch (e) {
      if (e.response && e.response.status === 409) {
        setErreurSuppression(e.response.data.message)
      } else if (e.response && e.response.status === 403) {
        setErreurSuppression("Vous n'avez pas le droit de supprimer un acheteur.")
      } else {
        setErreurSuppression('La suppression a échoué.')
      }
    }
  }

  return (
    <div>
      <div className="page-entete">
        <div>
          <h1>Acheteurs</h1>
          <p className="page-intro">Les clients qui achètent en gros.</p>
        </div>
        {estGestionnaire && (
          <Link to="/acheteurs/nouveau" className="bouton">
            <Icone nom="plus" taille={18} />
            <span>Ajouter un acheteur</span>
          </Link>
        )}
      </div>

      {erreurSuppression && <MessageErreur texte={erreurSuppression} />}

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && (
        <div className="cadre-tableau">
          {acheteurs.length === 0 && (
            <ListeVide icone="acheteurs" message="Aucun acheteur pour l'instant.">
              {estGestionnaire && (
                <Link to="/acheteurs/nouveau" className="bouton">
                  Ajouter un acheteur
                </Link>
              )}
            </ListeVide>
          )}

          {acheteurs.length > 0 && (
            <div>
              <div className="boite-tableau">
                <table className="tableau">
                  <thead>
                    <tr>
                      <th>Nom</th>
                      <th>Téléphone</th>
                      {estGestionnaire && <th className="colonne-actions">Actions</th>}
                    </tr>
                  </thead>
                  <tbody>
                    {acheteurs.map((acheteur) => (
                      <tr key={acheteur.id}>
                        <td className="texte-gras">{acheteur.nom}</td>
                        <td>{acheteur.telephone}</td>
                        {estGestionnaire && (
                          <td className="colonne-actions">
                            <Link
                              to={'/acheteurs/' + acheteur.id + '/modifier'}
                              className="bouton-icone"
                              aria-label={'Modifier ' + acheteur.nom}
                              title="Modifier"
                            >
                              <Icone nom="modifier" taille={19} />
                            </Link>
                            <button
                              type="button"
                              className="bouton-icone bouton-icone-danger"
                              onClick={() => supprimer(acheteur)}
                              aria-label={'Supprimer ' + acheteur.nom}
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

export default ListeAcheteurs
