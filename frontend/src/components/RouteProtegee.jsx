import { useContext } from 'react'
import { Navigate } from 'react-router-dom'
import { AuthContext } from '../context/AuthContext'

// Affiche la page demandée seulement si quelqu'un est connecté, sinon renvoie vers /connexion.
// Si la prop "role" est donnée, l'utilisateur doit aussi avoir ce rôle, sinon retour à l'accueil.
function RouteProtegee({ children, role }) {
  const { utilisateur } = useContext(AuthContext)

  if (!utilisateur) {
    return <Navigate to="/connexion" />
  }

  if (role && utilisateur.role !== role) {
    return <Navigate to="/" />
  }

  return children
}

export default RouteProtegee
