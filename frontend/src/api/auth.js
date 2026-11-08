import api from './axios'

export async function login(email, password) {
  const response = await api.post('/login', { email, password })

  localStorage.setItem('token', response.data.token)
  localStorage.setItem('user', JSON.stringify(response.data.user))

  return response.data.user
}

export async function logout() {
  try {
    await api.post('/logout')
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }
}

export async function getMe() {
  const response = await api.get('/user')

  return response.data
}
