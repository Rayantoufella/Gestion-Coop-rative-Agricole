import api from './axios'

// Récupère une page de la liste des produits (10 par page).
export async function getProduits(page) {
  const response = await api.get('/produits?page=' + page)
  return response.data
}

// Récupère un seul produit grâce à son id.
export async function getProduit(id) {
  const response = await api.get('/produits/' + id)
  return response.data.data
}

// Crée un nouveau produit.
export async function creerProduit(donnees) {
  const response = await api.post('/produits', donnees)
  return response.data.data
}

// Modifie un produit existant.
export async function modifierProduit(id, donnees) {
  const response = await api.put('/produits/' + id, donnees)
  return response.data.data
}

// Supprime un produit.
export async function supprimerProduit(id) {
  const response = await api.delete('/produits/' + id)
  return response.data
}

// Récupère TOUS les produits (toutes les pages) pour remplir une liste déroulante.
export async function getTousLesProduits() {
  // On lit la première page pour connaître le nombre de pages.
  const premiere = await getProduits(1)
  let tous = premiere.data

  // Puis on lit les pages suivantes une par une.
  for (let page = 2; page <= premiere.meta.last_page; page++) {
    const suivante = await getProduits(page)
    tous = tous.concat(suivante.data)
  }

  return tous
}
