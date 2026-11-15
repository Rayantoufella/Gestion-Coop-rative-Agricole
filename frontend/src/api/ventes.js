import api from './axios'

// Récupère une page de la liste des ventes groupées (10 par page).
export async function getVentes(page) {
  const response = await api.get('/ventes-groupees?page=' + page)
  return response.data
}

// Crée une nouvelle vente groupée.
// (Pas de fonction "modifier" : une vente enregistrée ne se modifie pas.)
export async function creerVente(donnees) {
  const response = await api.post('/ventes-groupees', donnees)
  return response.data.data
}

// Supprime une vente groupée.
export async function supprimerVente(id) {
  const response = await api.delete('/ventes-groupees/' + id)
  return response.data
}
