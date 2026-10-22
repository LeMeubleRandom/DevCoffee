<script setup lang="ts">
import { onMounted } from 'vue'
import { useConsumptionsStore } from '@/stores/consumptions.store'
import ConsumptionItem from './ConsumptionItem.vue'

const store = useConsumptionsStore()

onMounted(() => {
  store.loadConsumptions()
})
</script>

<template>
  <div class="card list-card">
    <div class="list-header">
      <h2>📋 Historique des consommations</h2>
      <button @click="store.loadConsumptions()" class="btn-refresh" title="Actualiser">↻</button>
    </div>

    <div v-if="store.loading && store.consumptions.length === 0" class="empty-state">
      Chargement de l'historique...
    </div>

    <div v-else-if="store.consumptions.length === 0" class="empty-state">
      Aucune consommation enregistrée pour le moment.
    </div>

    <div v-else class="items-list">
      <ConsumptionItem
        v-for="item in store.consumptions"
        :key="item.id"
        :consumption="item"
      />
    </div>
  </div>
</template>

<style scoped>
.list-card {
  background: #ffffff;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  margin-bottom: 5rem;
}

.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.btn-refresh {
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  padding: 0.3rem 0.6rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1.1rem;
}

.btn-refresh:hover {
  background: #e2e8f0;
}

.empty-state {
  text-align: center;
  color: #64748b;
  padding: 2rem;
  font-style: italic;
}

.items-list {
  display: flex;
  flex-direction: column;
}
</style>
