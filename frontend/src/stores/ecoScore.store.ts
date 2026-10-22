import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { EcoScoreMetrics } from '@/types/metrics'

export const useEcoScoreStore = defineStore('ecoScore', () => {
  const metrics = ref<EcoScoreMetrics>({
    responseTimeMs: null,
    payloadSizeBytes: null,
    estimatedCo2Grams: null,
  })

  function updateFromHeaders(headers: Headers) {
    const time = headers.get('X-Response-Time-Ms')
    const size = headers.get('X-Payload-Size-Bytes')
    const co2 = headers.get('X-Estimated-CO2-Grams')

    if (time !== null || size !== null || co2 !== null) {
      metrics.value = {
        responseTimeMs: time ? parseFloat(time) : null,
        payloadSizeBytes: size ? parseInt(size, 10) : null,
        estimatedCo2Grams: co2 ? parseFloat(co2) : null,
      }
    }
  }

  return {
    metrics,
    updateFromHeaders,
  }
})
