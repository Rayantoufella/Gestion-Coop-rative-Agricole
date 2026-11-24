import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { creerUtilisateur, getUtilisateur, modifierUtilisateur } from '../../api/utilisateurs'
import Icone from '../../components/Icone'
import ErreurChamp from '../../components/ErreurChamp'
import MessageErreur from '../../components/MessageErreur'

// Formulaire pour créer ou modifier un compte gestionnaire (Paramètres > Comptes). Réservé à l'admin.
function FormulaireGestionnaire() {
  const { id } = useParams()
  const navigate = useNavigate()

  // Un seul state pour tous les champs : chaque clé = nom attendu par l'API Laravel.
  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  })
  const [erreurs, setErreurs] = useState({})
  const [messageErreur, setMessageErreur] = useState('')
  const [chargement, setChargement] = useState(false)
  const [envoi, setEnvoi] = useState(false)

  // En modification, on charge le compte pour remplir le formulaire.
  useEffect(() => {
    // Demande le compte à l'API et copie ses valeurs dans le formulaire.
    async function chargerUtilisateur() {
      setChargement(true)
      try {
        const utilisateur = await getUtilisateur(id)
        setForm({
          name: utilisateur.name,
          email: utilisateur.email,
          password: '',
          password_confirmation: '',
        })
      } catch (e) {
        console.log('Erreur pendant le chargement du compte', e)
        setMessageErreur('Impossible de charger ce compte.')
      }
      setChargement(false)
    }

    if (id) {
      chargerUtilisateur()
    }
  }, [id])

  // Met à jour le champ modifié (grâce à son attribut name).
  function changerChamp(event) {
    setForm({ ...form, [event.target.name]: event.target.value })
  }

  // Envoie le formulaire à l'API, puis revient à la liste des comptes si tout va bien.
  async function envoyerFormulaire(event) {
    event.preventDefault()
    setErreurs({})
    setMessageErreur('')
    setEnvoi(true)

    try {
      if (id) {
        await modifierUtilisateur(id, form)
      } else {
        await creerUtilisateur(form)
      }
      navigate('/parametres/comptes')
    } catch (e) {
      if (e.response && e.response.status === 422) {
        setErreurs(e.response.data.errors)
      } else if (e.response && e.response.status === 403) {
        setMessageErreur(
          id
            ? "Vous n'avez pas le droit de modifier ce compte."
            : "Vous n'avez pas le droit de créer un compte."
        )
      } else {
        setMessageErreur("L'enregistrement a échoué.")
      }
    }

    setEnvoi(false)
  }

  if (chargement) {
    return <p className="chargement">Chargement...</p>
  }

  const texteAction = id ? 'Enregistrer les modifications' : 'Créer le gestionnaire'

  return (
    <div className="fenetre">
      <form onSubmit={envoyerFormulaire} noValidate>
        <div className="fenetre-entete">
          <h2>{id ? 'Modifier le gestionnaire' : 'Nouveau gestionnaire'}</h2>
          <Link to="/parametres/comptes" className="bouton-icone bouton-icone-gris" aria-label="Fermer" title="Fermer">
            <Icone nom="fermer" />
          </Link>
        </div>

        <div className="fenetre-corps">
          <div className="fenetre-champs">
            {messageErreur && <MessageErreur texte={messageErreur} />}

            <div className="champ">
              <label htmlFor="name">Nom complet</label>
              <input id="name" name="name" type="text" value={form.name} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.name} />
            </div>

            <div className="champ">
              <label htmlFor="email">Email</label>
              <input id="email" name="email" type="email" autoComplete="off" value={form.email} onChange={changerChamp} />
              <span className="aide-champ">Il servira à se connecter.</span>
              <ErreurChamp messages={erreurs.email} />
            </div>

            <div className="champ">
              <label htmlFor="password">Mot de passe</label>
              <input
                id="password"
                name="password"
                type="password"
                autoComplete="new-password"
                value={form.password}
                onChange={changerChamp}
              />
              <span className="aide-champ">
                {id ? 'Laisser vide pour conserver le mot de passe actuel.' : '8 caractères minimum.'}
              </span>
              <ErreurChamp messages={erreurs.password} />
            </div>

            <div className="champ">
              <label htmlFor="password_confirmation">Confirmer le mot de passe</label>
              <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autoComplete="new-password"
                value={form.password_confirmation}
                onChange={changerChamp}
              />
            </div>
          </div>
        </div>

        <div className="fenetre-pied">
          <Link to="/parametres/comptes" className="bouton-secondaire">
            Annuler
          </Link>
          <button type="submit" className="bouton" disabled={envoi}>
            {envoi ? 'Enregistrement...' : texteAction}
          </button>
        </div>
      </form>
    </div>
  )
}

export default FormulaireGestionnaire
