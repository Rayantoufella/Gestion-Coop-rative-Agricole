import { useContext, useState } from 'react'
import { Navigate, useNavigate } from 'react-router-dom'
import { AuthContext } from '../context/AuthContext'
import Logo from '../components/Logo'
import FondConnexion from '../components/FondConnexion'
import MessageErreur from '../components/MessageErreur'
import ErreurChamp from '../components/ErreurChamp'

// Page de connexion : formulaire email + mot de passe.
function Connexion() {
  const { utilisateur, connexion } = useContext(AuthContext)
  const navigate = useNavigate()

  const [email, setEmail] = useState('')
  const [motDePasse, setMotDePasse] = useState('')
  const [erreur, setErreur] = useState('')
  const [erreurs, setErreurs] = useState({})
  const [chargement, setChargement] = useState(false)

  // Envoie le formulaire : connexion puis retour à l'accueil, ou affichage d'une erreur.
  async function envoyerFormulaire(event) {
    event.preventDefault()
    setErreur('')
    setErreurs({})
    setChargement(true)

    try {
      await connexion(email, motDePasse)
      navigate('/')
    } catch (e) {
      if (e.response && e.response.status === 401) {
        setErreur('Email ou mot de passe incorrect.')
      } else if (e.response && e.response.status === 422) {
        // Champ vide ou email mal écrit : un message sous chaque champ.
        setErreurs(e.response.data.errors)
      } else {
        setErreur('Impossible de contacter le serveur.')
      }
    }

    setChargement(false)
  }

  // Si quelqu'un est déjà connecté, on l'envoie directement à l'accueil.
  if (utilisateur) {
    return <Navigate to="/" />
  }

  return (
    <div className="connexion-page">
      <FondConnexion />
      <form className="connexion-formulaire" onSubmit={envoyerFormulaire} noValidate>
        <div className="logo-bloc">
          <Logo anime={true} />
          <div>
            <div className="logo-nom">GCA</div>
            <div className="texte-doux">Gestion de Coopérative Agricole</div>
          </div>
        </div>

        <div>
          <h1>Bienvenue au magasin</h1>
          <p className="texte-doux">
            Connectez-vous pour suivre le stock et enregistrer les dépôts et les ventes.
          </p>
        </div>

        <div className="champ">
          <label htmlFor="email">Email</label>
          <input
            id="email"
            type="email"
            autoComplete="username"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
          <ErreurChamp messages={erreurs.email} />
        </div>

        <div className="champ">
          <label htmlFor="mot-de-passe">Mot de passe</label>
          <input
            id="mot-de-passe"
            type="password"
            autoComplete="current-password"
            value={motDePasse}
            onChange={(e) => setMotDePasse(e.target.value)}
          />
          <ErreurChamp messages={erreurs.password} />
        </div>

        {erreur && <MessageErreur texte={erreur} />}

        <button type="submit" className="bouton" disabled={chargement}>
          {chargement ? 'Connexion...' : 'Se connecter'}
        </button>

      </form>
    </div>
  )
}

export default Connexion
