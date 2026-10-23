import { useEcoScoreStore } from '@/stores/ecoScore.store'
import { useNotificationStore } from '@/stores/notification.store'
import type { ProblemDetails } from '@/types/rfc7807'

const BASE_URL = 'http://127.0.0.1:5500'

export async function apiClient<T>(endpoint: string, options: RequestInit = {}): Promise<T> {
  const notificationStore = useNotificationStore()
  const ecoScoreStore = useEcoScoreStore()

  const url = endpoint.startsWith('http') ? endpoint : `${BASE_URL}${endpoint}`

  const headers = new Headers(options.headers || {})
  if (!headers.has('Content-Type') && options.body) {
    headers.set('Content-Type', 'application/json')
  }
  headers.set('Accept', 'application/json, application/problem+json')

  const response = await fetch(url, {
    ...options,
    headers,
  })

  ecoScoreStore.updateFromHeaders(response.headers)

  if (!response.ok) {
    let errorProblem: ProblemDetails

    try {
      errorProblem = await response.json()
    } catch {
      errorProblem = {
        title: 'Erreur Serveur',
        status: response.status,
        detail: `Une erreur est survenue (HTTP ${response.status}).`,
      }
    }

    notificationStore.setError(errorProblem)
    throw errorProblem
  }

  if (response.status === 204 || response.headers.get('content-length') === '0') {
    return {} as T
  }

  return await response.json()
}
