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

// Récupère un compte gestionnaire pour le modifier. Réservé à l'admin.
export async function getUtilisateur(id) {
  const response = await api.get('/utilisateurs/' + id)
  return response.data.data
}

// Modifie un compte gestionnaire. Réservé à l'admin.
export async function modifierUtilisateur(id, donnees) {
  const response = await api.put('/utilisateurs/' + id, donnees)
  return response.data.data
}

// Supprime un compte gestionnaire. Réservé à l'admin.
export async function supprimerUtilisateur(id) {
  const response = await api.delete('/utilisateurs/' + id)
  return response.data
}
