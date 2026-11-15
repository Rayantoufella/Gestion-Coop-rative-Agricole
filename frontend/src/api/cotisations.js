import api from './axios'

// Récupère une page de la liste des cotisations (10 par page).
export async function getCotisations(page) {
  const response = await api.get('/cotisations?page=' + page)
  return response.data
}

// Récupère une seule cotisation grâce à son id.
export async function getCotisation(id) {
  const response = await api.get('/cotisations/' + id)
  return response.data.data
}

// Crée une nouvelle cotisation.
export async function creerCotisation(donnees) {
  const response = await api.post('/cotisations', donnees)
  return response.data.data
}

// Modifie une cotisation existante.
export async function modifierCotisation(id, donnees) {
  const response = await api.put('/cotisations/' + id, donnees)
  return response.data.data
}

// Supprime une cotisation.
export async function supprimerCotisation(id) {
  const response = await api.delete('/cotisations/' + id)
  return response.data
}
