import api from './axios'

// Récupère les statistiques du tableau de bord (membres, cotisations, ventes, stock).
export async function getDashboard() {
  const response = await api.get('/dashboard')
  return response.data
}
