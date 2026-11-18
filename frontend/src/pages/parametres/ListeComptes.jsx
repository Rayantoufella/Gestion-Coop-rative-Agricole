import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { getUtilisateurs } from '../../api/utilisateurs'
import Icone from '../../components/Icone'
import Pagination from '../../components/Pagination'
import MessageErreur from '../../components/MessageErreur'

// Transforme une date "AAAA-MM-JJ" (avec ou sans heure "HH:MM") en "JJ/MM/AAAA" (+ " à HH:MM").
function formaterDate(date) {
  const morceaux = date.split(' ')
  const jour = morceaux[0].split('-')
  const texte = jour[2] + '/' + jour[1] + '/' + jour[0]
  if (morceaux.length > 1) {
    return texte + ' à ' + morceaux[1]
  }
  return texte
}

// Page Paramètres > Comptes : la liste des comptes et qui est en ligne. Réservée à l'admin.
function ListeComptes() {
  const [comptes, setComptes] = useState([])
  const [page, setPage] = useState(1)
  // "meta" = informations de pagination envoyées par Laravel (page, total...).
  const [meta, setMeta] = useState(null)
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')

  // À chaque changement de page, on recharge les comptes depuis l'API.
  useEffect(() => {
    // Demande à l'API la page courante des comptes.
    async function chargerComptes() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getUtilisateurs(page)
        setComptes(reponse.data)
        setMeta(reponse.meta)
      } catch (e) {
        if (e.response && e.response.status === 403) {
          setErreur("Vous n'avez pas le droit de voir les comptes.")
        } else {
          setErreur('Impossible de charger les comptes.')
        }
      }
      setChargement(false)
    }

    chargerComptes()
  }, [page])

  return (
    <div>
      <div className="page-entete">
        <div>
          <h1>Comptes</h1>
          <p className="page-intro">
            Les personnes qui ont accès à l'application. « En ligne » : actif dans les 5 dernières minutes.
          </p>
        </div>
        <Link to="/parametres/comptes/nouveau" className="bouton">
          <Icone nom="plus" taille={18} />
          <span>Créer un gestionnaire</span>
        </Link>
      </div>

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && comptes.length > 0 && (
        <div className="cadre-tableau">
          <div className="boite-tableau">
            <table className="tableau">
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Rôle</th>
                  <th>Statut</th>
                  <th>Dernière activité</th>
                  <th>Créé le</th>
                </tr>
              </thead>
              <tbody>
                {comptes.map((compte) => (
                  <tr key={compte.id}>
                    <td className="texte-gras">{compte.name}</td>
                    <td>{compte.email}</td>
                    <td>{compte.role === 'admin' ? 'Administrateur' : 'Gestionnaire'}</td>
                    <td>
                      <span className={compte.en_ligne ? 'badge badge-actif' : 'badge badge-inactif'}>
                        <span className="badge-point"></span>
                        {compte.en_ligne ? 'En ligne' : 'Hors ligne'}
                      </span>
                    </td>
                    <td>{compte.derniere_activite ? formaterDate(compte.derniere_activite) : 'Jamais'}</td>
                    <td>{formaterDate(compte.created_at)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {meta && <Pagination meta={meta} onChangerPage={setPage} />}
        </div>
      )}
    </div>
  )
}

export default ListeComptes
