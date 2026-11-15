import api from './axios'

// Récupère une page de la liste des acheteurs (10 par page).
export async function getAcheteurs(page) {
  const response = await api.get('/acheteurs?page=' + page)
  return response.data
}

// Récupère un seul acheteur grâce à son id.
export async function getAcheteur(id) {
  const response = await api.get('/acheteurs/' + id)
  return response.data.data
}

// Crée un nouvel acheteur.
export async function creerAcheteur(donnees) {
  const response = await api.post('/acheteurs', donnees)
  return response.data.data
}

// Modifie un acheteur existant.
export async function modifierAcheteur(id, donnees) {
  const response = await api.put('/acheteurs/' + id, donnees)
  return response.data.data
}

// Supprime un acheteur.
export async function supprimerAcheteur(id) {
  const response = await api.delete('/acheteurs/' + id)
  return response.data
}

// Récupère TOUS les acheteurs (toutes les pages) pour remplir une liste déroulante.
export async function getTousLesAcheteurs() {
  // On lit la première page pour connaître le nombre de pages.
  const premiere = await getAcheteurs(1)
  let tous = premiere.data

  // Puis on lit les pages suivantes une par une.
  for (let page = 2; page <= premiere.meta.last_page; page++) {
    const suivante = await getAcheteurs(page)
    tous = tous.concat(suivante.data)
  }

  return tous
}
