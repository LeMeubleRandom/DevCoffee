import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { ProblemDetails } from '@/types/rfc7807'

export const useNotificationStore = defineStore('notification', () => {
  const currentError = ref<ProblemDetails | null>(null)

  function setError(error: ProblemDetails) {
    currentError.value = error
  }

  function clearError() {
    currentError.value = null
  }

  return {
    currentError,
    setError,
    clearError,
  }
})
