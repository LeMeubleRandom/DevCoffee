<script setup lang="ts">
import { ref } from 'vue'
import type { Consumption } from '@/types/consumption'
import { useConsumptionsStore } from '@/stores/consumptions.store'

const props = defineProps<{
  consumption: Consumption
}>()

const store = useConsumptionsStore()
const isDeleting = ref(false)

async function handleDelete() {
  const deleteUrl = props.consumption._links?.delete?.href
  if (!deleteUrl) {
    return
  }

  if (!confirm(`Supprimer la consommation de ${props.consumption.developerName} ?`)) {
    return
  }

  isDeleting.value = true
  try {
    await store.removeConsumption(deleteUrl)
  } catch (e) {
    console.error('Erreur lors de la suppression', e)
  } finally {
    isDeleting.value = false
  }
}
</script>

<template>
  <div class="consumption-item">
    <div class="info">
      <div class="main-line">
        <strong class="dev-name">{{ consumption.developerName }}</strong>
        <span class="drink-badge">{{ consumption.drinkName }}</span>
        <span class="caffeine-badge">{{ consumption.caffeineMg }} mg</span>
      </div>
      <div class="date-line">
        Consommé le : {{ consumption.consumedAt }}
      </div>
    </div>

    <button
      v-if="consumption._links?.delete?.href"
      @click="handleDelete"
      :disabled="isDeleting"
      class="btn-delete"
      title="Supprimer via HATEOAS"
    >
      {{ isDeleting ? '...' : 'Supprimer' }}
    </button>
  </div>
</template>

<style scoped>
.consumption-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.9rem 1rem;
  border-bottom: 1px solid #f0f0f0;
}

.consumption-item:last-child {
  border-bottom: none;
}

.main-line {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  margin-bottom: 0.25rem;
}

.dev-name {
  font-size: 1.05rem;
  color: #1e293b;
}

.drink-badge {
  background: #e0e7ff;
  color: #3730a3;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  font-size: 0.85rem;
  font-weight: 500;
}

.caffeine-badge {
  background: #fef3c7;
  color: #92400e;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  font-size: 0.85rem;
  font-weight: bold;
}

.date-line {
  font-size: 0.8rem;
  color: #64748b;
}

.btn-delete {
  background: #fee2e2;
  color: #dc2626;
  border: 1px solid #fca5a5;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.85rem;
  font-weight: 600;
  transition: all 0.2s;
}

.btn-delete:hover:not(:disabled) {
  background: #dc2626;
  color: white;
}
</style>
