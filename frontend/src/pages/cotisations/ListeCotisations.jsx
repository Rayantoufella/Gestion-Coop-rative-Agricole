import { useContext, useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { AuthContext } from '../../context/AuthContext'
import { getCotisations, supprimerCotisation } from '../../api/cotisations'
import Icone from '../../components/Icone'
import Pagination from '../../components/Pagination'
import ListeVide from '../../components/ListeVide'
import MessageErreur from '../../components/MessageErreur'

// Transforme une date "AAAA-MM-JJ" en "JJ/MM/AAAA".
function formaterDate(date) {
  const morceaux = date.split('-')
  return morceaux[2] + '/' + morceaux[1] + '/' + morceaux[0]
}

// Transforme un montant 150 en "150,00 DH".
function formaterMontant(montant) {
  return (
    Number(montant).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' DH'
  )
}

// Page qui affiche la liste des cotisations, page par page.
function ListeCotisations() {
  const { utilisateur } = useContext(AuthContext)
  const estAdmin = utilisateur.role === 'admin'

  const [cotisations, setCotisations] = useState([])
  const [page, setPage] = useState(1)
  // "meta" = informations de pagination envoyées par Laravel (page, total...).
  const [meta, setMeta] = useState(null)
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')
  const [erreurSuppression, setErreurSuppression] = useState('')

  // À chaque changement de page, on recharge les cotisations depuis l'API.
  useEffect(() => {
    // Demande à l'API la page courante des cotisations.
    async function chargerCotisations() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getCotisations(page)
        setCotisations(reponse.data)
        setMeta(reponse.meta)
      } catch (e) {
        if (e.response && e.response.status === 403) {
          setErreur("Vous n'avez pas le droit de voir les cotisations.")
        } else {
          setErreur('Impossible de charger les cotisations.')
        }
      }
      setChargement(false)
    }

    chargerCotisations()
  }, [page])

  // Demande confirmation puis supprime la cotisation.
  async function supprimer(cotisation) {
    const ok = window.confirm(
      'La cotisation ' + cotisation.periode + ' de ' + cotisation.membre.prenom + ' ' + cotisation.membre.nom +
        ' (' + formaterMontant(cotisation.montant) + ') sera supprimée. Cette action est définitive. Continuer ?'
    )
    if (!ok) {
      return
    }

    setErreurSuppression('')
    try {
      await supprimerCotisation(cotisation.id)
      // On retire la ligne supprimée du tableau.
      setCotisations(cotisations.filter((c) => c.id !== cotisation.id))
    } catch (e) {
      if (e.response && e.response.status === 403) {
        setErreurSuppression("Vous n'avez pas le droit de supprimer une cotisation.")
      } else {
        setErreurSuppression('La suppression a échoué.')
      }
    }
  }

  return (
    <div>
      <div className="page-entete">
        <div>
          <h1>Cotisations</h1>
          <p className="page-intro">Les cotisations payées par les membres, période par période.</p>
        </div>
        {estAdmin && (
          <Link to="/cotisations/nouvelle" className="bouton">
            <Icone nom="plus" taille={18} />
            <span>Enregistrer une cotisation</span>
          </Link>
        )}
      </div>

      {erreurSuppression && <MessageErreur texte={erreurSuppression} />}

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && (
        <div className="cadre-tableau">
          {cotisations.length === 0 && (
            <ListeVide icone="cotisations" message="Aucune cotisation pour l'instant.">
              {estAdmin && (
                <Link to="/cotisations/nouvelle" className="bouton">
                  Enregistrer une cotisation
                </Link>
              )}
            </ListeVide>
          )}

          {cotisations.length > 0 && (
            <div>
              <div className="boite-tableau">
                <table className="tableau">
                  <thead>
                    <tr>
                      <th>Membre</th>
                      <th>Période</th>
                      <th className="colonne-nombre">Montant</th>
                      <th>Date de paiement</th>
                      {estAdmin && <th className="colonne-actions">Actions</th>}
                    </tr>
                  </thead>
                  <tbody>
                    {cotisations.map((cotisation) => (
                      <tr key={cotisation.id}>
                        <td className="texte-gras">
                          {cotisation.membre.prenom} {cotisation.membre.nom}
                        </td>
                        <td>{cotisation.periode}</td>
                        <td className="colonne-nombre">{formaterMontant(cotisation.montant)}</td>
                        <td>{formaterDate(cotisation.date_paiement)}</td>
                        {estAdmin && (
                          <td className="colonne-actions">
                            <Link
                              to={'/cotisations/' + cotisation.id + '/modifier'}
                              className="bouton-icone"
                              aria-label={'Modifier la cotisation ' + cotisation.periode}
                              title="Modifier"
                            >
                              <Icone nom="modifier" taille={19} />
                            </Link>
                            <button
                              type="button"
                              className="bouton-icone bouton-icone-danger"
                              onClick={() => supprimer(cotisation)}
                              aria-label={'Supprimer la cotisation ' + cotisation.periode}
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

export default ListeCotisations
