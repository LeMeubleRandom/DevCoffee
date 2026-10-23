<script setup lang="ts">
import { useEcoScoreStore } from '@/stores/ecoScore.store'

const ecoScoreStore = useEcoScoreStore()
</script>

<template>
  <div class="eco-badge">
    <div class="eco-title">🌱 Éco-Score (Dernière requête)</div>
    <div class="eco-metrics">
      <span class="metric">
        ⏱️ <strong>{{ ecoScoreStore.metrics.responseTimeMs !== null ? ecoScoreStore.metrics.responseTimeMs + ' ms' : '-' }}</strong>
      </span>
      <span class="separator">•</span>
      <span class="metric">
        📦 <strong>{{ ecoScoreStore.metrics.payloadSizeBytes !== null ? ecoScoreStore.metrics.payloadSizeBytes + ' octets' : '-' }}</strong>
      </span>
      <span class="separator">•</span>
      <span class="metric">
        🌍 <strong>{{ ecoScoreStore.metrics.estimatedCo2Grams !== null ? ecoScoreStore.metrics.estimatedCo2Grams.toFixed(7) + ' g CO₂' : '-' }}</strong>
      </span>
    </div>
  </div>
</template>

<style scoped>
.eco-badge {
  position: fixed;
  bottom: 12px;
  right: 12px;
  background: rgba(15, 23, 42, 0.92);
  backdrop-filter: blur(4px);
  color: #f8fafc;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  font-size: 0.85rem;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.eco-title {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #34d399;
  font-weight: 700;
}

.eco-metrics {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.separator {
  color: #64748b;
}

.metric strong {
  color: #e2e8f0;
}
</style>
