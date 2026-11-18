import api from './axios'

// Récupère une page de la liste des comptes (10 par page). Réservé à l'admin.
export async function getUtilisateurs(page) {
  const response = await api.get('/utilisateurs?page=' + page)
  return response.data
}

// Crée un nouveau compte gestionnaire. Réservé à l'admin.
export async function creerUtilisateur(donnees) {
  const response = await api.post('/utilisateurs', donnees)
  return response.data.data
}
