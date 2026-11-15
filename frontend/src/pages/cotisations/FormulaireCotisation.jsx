import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { creerCotisation, getCotisation, modifierCotisation } from '../../api/cotisations'
import { getTousLesMembres } from '../../api/membres'
import Icone from '../../components/Icone'
import ErreurChamp from '../../components/ErreurChamp'
import MessageErreur from '../../components/MessageErreur'

// Formulaire pour enregistrer une cotisation, ou la modifier s'il y a un id dans l'adresse.
function FormulaireCotisation() {
  const { id } = useParams()
  const navigate = useNavigate()

  // Un seul state pour tous les champs : chaque clé = nom de la colonne dans la base.
  const [form, setForm] = useState({
    membre_id: '',
    periode: '',
    montant: '',
    date_paiement: '',
  })
  const [membres, setMembres] = useState([])
  const [erreurs, setErreurs] = useState({})
  const [messageErreur, setMessageErreur] = useState('')
  const [chargement, setChargement] = useState(true)
  const [envoi, setEnvoi] = useState(false)

  // Au démarrage : on charge la liste des membres, et la cotisation si on modifie.
  useEffect(() => {
    // Demande à l'API les membres (pour le select) et, en modification, la cotisation.
    async function chargerDonnees() {
      setChargement(true)
      try {
        const listeMembres = await getTousLesMembres()
        setMembres(listeMembres)

        if (id) {
          const cotisation = await getCotisation(id)
          setForm({
            membre_id: String(cotisation.membre_id),
            periode: cotisation.periode,
            montant: cotisation.montant,
            date_paiement: cotisation.date_paiement,
          })
        }
      } catch (e) {
        console.log('Erreur pendant le chargement', e)
        setMessageErreur('Impossible de charger les données du formulaire.')
      }
      setChargement(false)
    }

    chargerDonnees()
  }, [id])

  // Met à jour le champ modifié (grâce à son attribut name).
  function changerChamp(event) {
    setForm({ ...form, [event.target.name]: event.target.value })
  }

  // Envoie le formulaire à l'API, puis revient à la liste si tout va bien.
  async function envoyerFormulaire(event) {
    event.preventDefault()
    setErreurs({})
    setMessageErreur('')
    setEnvoi(true)

    try {
      if (id) {
        await modifierCotisation(id, form)
      } else {
        await creerCotisation(form)
      }
      navigate('/cotisations')
    } catch (e) {
      if (e.response && e.response.status === 422) {
        setErreurs(e.response.data.errors)
      } else if (e.response && e.response.status === 403) {
        setMessageErreur("Vous n'avez pas le droit de faire cette action.")
      } else {
        setMessageErreur("L'enregistrement a échoué.")
      }
    }

    setEnvoi(false)
  }

  if (chargement) {
    return <p className="chargement">Chargement...</p>
  }

  return (
    <div className="fenetre">
      <form onSubmit={envoyerFormulaire} noValidate>
        <div className="fenetre-entete">
          <h2>{id ? 'Modifier la cotisation' : 'Nouvelle cotisation'}</h2>
          <Link to="/cotisations" className="bouton-icone bouton-icone-gris" aria-label="Fermer" title="Fermer">
            <Icone nom="fermer" />
          </Link>
        </div>

        <div className="fenetre-corps">
          <div className="fenetre-champs">
            {messageErreur && <MessageErreur texte={messageErreur} />}

            <div className="champ">
              <label htmlFor="membre_id">Membre</label>
              <select id="membre_id" name="membre_id" value={form.membre_id} onChange={changerChamp}>
                <option value="">Choisissez un membre</option>
                {membres.map((membre) => (
                  <option key={membre.id} value={String(membre.id)}>
                    {membre.code_membre} · {membre.prenom} {membre.nom}
                    {membre.statut === 'inactif' ? ' (inactif)' : ''}
                  </option>
                ))}
              </select>
              <ErreurChamp messages={erreurs.membre_id} />
            </div>

            <div className="champ">
              <label htmlFor="periode">Période</label>
              <input id="periode" name="periode" type="text" value={form.periode} onChange={changerChamp} />
              <span className="aide-champ">Format MM/AAAA, par exemple 10/2026</span>
              <ErreurChamp messages={erreurs.periode} />
            </div>

            <div className="champ">
              <label htmlFor="montant">Montant (DH)</label>
              <input
                id="montant"
                name="montant"
                type="number"
                step="0.01"
                min="0"
                value={form.montant}
                onChange={changerChamp}
              />
              <ErreurChamp messages={erreurs.montant} />
            </div>

            <div className="champ">
              <label htmlFor="date_paiement">Date de paiement</label>
              <input id="date_paiement" name="date_paiement" type="date" value={form.date_paiement} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.date_paiement} />
            </div>
          </div>
        </div>

        <div className="fenetre-pied">
          <Link to="/cotisations" className="bouton-secondaire">
            Annuler
          </Link>
          <button type="submit" className="bouton" disabled={envoi}>
            {envoi ? 'Enregistrement...' : 'Enregistrer la cotisation'}
          </button>
        </div>
      </form>
    </div>
  )
}

export default FormulaireCotisation
