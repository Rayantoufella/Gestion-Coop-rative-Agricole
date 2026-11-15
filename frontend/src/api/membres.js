import api from './axios'

// Récupère une page de la liste des membres (10 par page).
export async function getMembres(page) {
  const response = await api.get('/membres?page=' + page)
  return response.data
}

// Récupère un seul membre grâce à son id.
export async function getMembre(id) {
  const response = await api.get('/membres/' + id)
  return response.data.data
}

// Crée un nouveau membre.
export async function creerMembre(donnees) {
  const response = await api.post('/membres', donnees)
  return response.data.data
}

// Modifie un membre existant.
export async function modifierMembre(id, donnees) {
  const response = await api.put('/membres/' + id, donnees)
  return response.data.data
}

// Supprime un membre.
export async function supprimerMembre(id) {
  const response = await api.delete('/membres/' + id)
  return response.data
}

// Récupère TOUS les membres (toutes les pages) pour remplir une liste déroulante.
export async function getTousLesMembres() {
  // On lit la première page pour connaître le nombre de pages.
  const premiere = await getMembres(1)
  let tous = premiere.data

  // Puis on lit les pages suivantes une par une.
  for (let page = 2; page <= premiere.meta.last_page; page++) {
    const suivante = await getMembres(page)
    tous = tous.concat(suivante.data)
  }

  return tous
}
