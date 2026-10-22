<script setup lang="ts">
import { ref } from 'vue'
import type { Consumption } from '@/types/consumption'
import { useConsumptionsStore } from '@/stores/consumptions.store'

const props = defineProps<{
  consumption: Consumption
}>()

const store = useConsumptionsStore()
const isDeleting = ref(false)
const showModal = ref(false)

function openModal() {
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

async function confirmDelete() {
  const deleteUrl = props.consumption._links?.delete?.href
  if (!deleteUrl) {
    closeModal()
    return
  }

  isDeleting.value = true
  try {
    await store.removeConsumption(deleteUrl)
    closeModal()
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
      @click="openModal"
      :disabled="isDeleting"
      class="btn-delete"
      title="Supprimer via HATEOAS"
    >
      {{ isDeleting ? '...' : 'Supprimer' }}
    </button>

    <!-- Modal Popup de confirmation -->
    <div v-if="showModal" class="modal-backdrop" @click.self="closeModal">
      <div class="modal-box">
        <h3>Confirmer la suppression</h3>
        <p>
          Voulez-vous vraiment supprimer la consommation de
          <strong>{{ consumption.developerName }}</strong> ({{ consumption.drinkName }}) ?
        </p>
        <div class="modal-actions">
          <button @click="closeModal" :disabled="isDeleting" class="btn-cancel">
            Annuler
          </button>
          <button @click="confirmDelete" :disabled="isDeleting" class="btn-confirm">
            {{ isDeleting ? 'Suppression...' : 'Confirmer' }}
          </button>
        </div>
      </div>
    </div>
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

/* Styles Popup Modal */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
  backdrop-filter: blur(2px);
}

.modal-box {
  background: #ffffff;
  padding: 1.5rem;
  border-radius: 10px;
  max-width: 420px;
  width: 90%;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.modal-box h3 {
  margin-top: 0;
  margin-bottom: 0.75rem;
  font-size: 1.15rem;
  color: #0f172a;
}

.modal-box p {
  color: #475569;
  font-size: 0.95rem;
  margin-bottom: 1.5rem;
  line-height: 1.4;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

.btn-cancel {
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  color: #475569;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

.btn-cancel:hover:not(:disabled) {
  background: #e2e8f0;
}

.btn-confirm {
  background: #dc2626;
  border: none;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}

.btn-confirm:hover:not(:disabled) {
  background: #b91c1c;
}
</style>
