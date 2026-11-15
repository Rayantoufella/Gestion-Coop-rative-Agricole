import { useContext, useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { AuthContext } from '../../context/AuthContext'
import { getMembres, supprimerMembre } from '../../api/membres'
import Icone from '../../components/Icone'
import Pagination from '../../components/Pagination'
import ListeVide from '../../components/ListeVide'
import MessageErreur from '../../components/MessageErreur'

// Transforme une date "AAAA-MM-JJ" en "JJ/MM/AAAA".
function formaterDate(date) {
  const morceaux = date.split('-')
  return morceaux[2] + '/' + morceaux[1] + '/' + morceaux[0]
}

// Page qui affiche la liste des membres, page par page.
function ListeMembres() {
  const { utilisateur } = useContext(AuthContext)
  const estAdmin = utilisateur.role === 'admin'

  const [membres, setMembres] = useState([])
  const [page, setPage] = useState(1)
  // "meta" = informations de pagination envoyées par Laravel (page, total...).
  const [meta, setMeta] = useState(null)
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')
  const [erreurSuppression, setErreurSuppression] = useState('')

  // À chaque changement de page, on recharge les membres depuis l'API.
  useEffect(() => {
    // Demande à l'API la page courante des membres.
    async function chargerMembres() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getMembres(page)
        setMembres(reponse.data)
        setMeta(reponse.meta)
      } catch (e) {
        if (e.response && e.response.status === 403) {
          setErreur("Vous n'avez pas le droit de voir les membres.")
        } else {
          setErreur('Impossible de charger les membres.')
        }
      }
      setChargement(false)
    }

    chargerMembres()
  }, [page])

  // Demande confirmation puis supprime le membre.
  async function supprimer(membre) {
    const ok = window.confirm(
      membre.prenom + ' ' + membre.nom + ' (' + membre.code_membre + ') sera retiré de la liste des membres. ' +
        'Cette action est définitive. Continuer ?'
    )
    if (!ok) {
      return
    }

    setErreurSuppression('')
    try {
      await supprimerMembre(membre.id)
      // On retire la ligne supprimée du tableau.
      setMembres(membres.filter((m) => m.id !== membre.id))
    } catch (e) {
      if (e.response && (e.response.status === 400 || e.response.status === 409)) {
        setErreurSuppression(e.response.data.message)
      } else if (e.response && e.response.status === 403) {
        setErreurSuppression("Vous n'avez pas le droit de supprimer un membre.")
      } else {
        setErreurSuppression('La suppression a échoué.')
      }
    }
  }

  return (
    <div>
      <div className="page-entete">
        <div>
          <h1>Membres</h1>
          <p className="page-intro">Les agriculteurs adhérents de la coopérative.</p>
        </div>
        {estAdmin && (
          <Link to="/membres/nouveau" className="bouton">
            <Icone nom="plus" taille={18} />
            <span>Ajouter un membre</span>
          </Link>
        )}
      </div>

      {erreurSuppression && <MessageErreur texte={erreurSuppression} />}

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && (
        <div className="cadre-tableau">
          {membres.length === 0 && (
            <ListeVide icone="membres" message="Aucun membre pour l'instant.">
              {estAdmin && (
                <Link to="/membres/nouveau" className="bouton">
                  Ajouter un membre
                </Link>
              )}
            </ListeVide>
          )}

          {membres.length > 0 && (
            <div>
              <div className="boite-tableau">
                <table className="tableau">
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th>Nom</th>
                      <th>Prénom</th>
                      <th>Téléphone</th>
                      <th>Date d'adhésion</th>
                      <th>Statut</th>
                      {estAdmin && <th className="colonne-actions">Actions</th>}
                    </tr>
                  </thead>
                  <tbody>
                    {membres.map((membre) => (
                      <tr key={membre.id}>
                        <td className="texte-gras">{membre.code_membre}</td>
                        <td>{membre.nom}</td>
                        <td>{membre.prenom}</td>
                        <td>{membre.telephone}</td>
                        <td>{formaterDate(membre.date_adhesion)}</td>
                        <td>
                          <span className={membre.statut === 'actif' ? 'badge badge-actif' : 'badge badge-inactif'}>
                            <span className="badge-point"></span>
                            {membre.statut === 'actif' ? 'Actif' : 'Inactif'}
                          </span>
                        </td>
                        {estAdmin && (
                          <td className="colonne-actions">
                            <Link
                              to={'/membres/' + membre.id + '/modifier'}
                              className="bouton-icone"
                              aria-label={'Modifier ' + membre.prenom + ' ' + membre.nom}
                              title="Modifier"
                            >
                              <Icone nom="modifier" taille={19} />
                            </Link>
                            <button
                              type="button"
                              className="bouton-icone bouton-icone-danger"
                              onClick={() => supprimer(membre)}
                              aria-label={'Supprimer ' + membre.prenom + ' ' + membre.nom}
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

export default ListeMembres
