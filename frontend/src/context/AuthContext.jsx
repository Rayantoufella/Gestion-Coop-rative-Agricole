import { createContext, useState } from 'react'
import { login, logout } from '../api/auth'

// Le Context permet de partager l'utilisateur connecté avec toutes les pages.
export const AuthContext = createContext(null)

// Lit l'utilisateur enregistré dans le navigateur (ou null si personne).
function lireUtilisateurEnregistre() {
  const texte = localStorage.getItem('user')
  if (texte) {
    return JSON.parse(texte)
  }
  return null
}

// Fournit l'utilisateur et les fonctions connexion / deconnexion aux composants enfants.
export function AuthProvider({ children }) {
  const [utilisateur, setUtilisateur] = useState(lireUtilisateurEnregistre())

  // Envoie l'email et le mot de passe à l'API puis garde l'utilisateur en mémoire.
  async function connexion(email, motDePasse) {
    const utilisateurConnecte = await login(email, motDePasse)
    setUtilisateur(utilisateurConnecte)
  }

  // Prévient l'API de la déconnexion puis oublie l'utilisateur.
  async function deconnexion() {
    try {
      await logout()
    } catch (erreur) {
      // Même si l'API ne répond pas, on déconnecte quand même dans le navigateur.
      console.log('Erreur pendant la déconnexion', erreur)
    }
    setUtilisateur(null)
  }

  return (
    <AuthContext.Provider value={{ utilisateur, connexion, deconnexion }}>
      {children}
    </AuthContext.Provider>
  )
}
