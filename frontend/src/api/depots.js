import api from './axios'

// Récupère une page de la liste des dépôts (10 par page).
export async function getDepots(page) {
  const response = await api.get('/depots-stock?page=' + page)
  return response.data
}

// Récupère un seul dépôt grâce à son id.
export async function getDepot(id) {
  const response = await api.get('/depots-stock/' + id)
  return response.data.data
}

// Crée un nouveau dépôt.
export async function creerDepot(donnees) {
  const response = await api.post('/depots-stock', donnees)
  return response.data.data
}

// Modifie un dépôt existant.
export async function modifierDepot(id, donnees) {
  const response = await api.put('/depots-stock/' + id, donnees)
  return response.data.data
}

// Supprime un dépôt.
export async function supprimerDepot(id) {
  const response = await api.delete('/depots-stock/' + id)
  return response.data
}
