<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useConsumptionsStore } from '@/stores/consumptions.store'
import { useNotificationStore } from '@/stores/notification.store'

const store = useConsumptionsStore()
const notificationStore = useNotificationStore()

const developerName = ref('')
const selectedDrinkId = ref<number | ''>('')
const isSubmitting = ref(false)

onMounted(() => {
  store.loadDrinks()
})

async function handleSubmit() {
  if (!developerName.value.trim() || selectedDrinkId.value === '') {
    return
  }

  notificationStore.clearError()
  isSubmitting.value = true

  try {
    await store.addConsumption({
      developerName: developerName.value.trim(),
      drinkId: Number(selectedDrinkId.value),
    })
    selectedDrinkId.value = ''
  } catch {
    // Erreur gérée par apiClient et affichée via le store de notifications
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div class="card form-card">
    <h2>☕ Enregistrer un café</h2>
    <form @submit.prevent="handleSubmit" class="form-container">
      <div class="form-group">
        <label for="devName">Développeur :</label>
        <input
          id="devName"
          v-model="developerName"
          type="text"
          placeholder="Ex: Antoine le LEADER (dictateur)"
          required
        />
      </div>

      <div class="form-group">
        <label for="drinkSelect">Boisson :</label>
        <select id="drinkSelect" v-model="selectedDrinkId" required>
          <option value="" disabled>-- Choisir une boisson --</option>
          <option v-for="drink in store.drinks" :key="drink.id" :value="drink.id">
            {{ drink.name }} ({{ drink.caffeineMg }}mg caféine - {{ drink.volumeMl }}ml)
          </option>
        </select>
      </div>

      <button type="submit" :disabled="isSubmitting" class="btn-primary">
        {{ isSubmitting ? 'Enregistrement...' : 'Consommer (+)' }}
      </button>
    </form>
  </div>
</template>

<style scoped>
.form-card {
  background: #ffffff;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  margin-bottom: 2rem;
}

.form-container {
  display: flex;
  gap: 1rem;
  align-items: flex-end;
  flex-wrap: wrap;
}

.form-group {
  display: flex;
  flex-direction: column;
  flex: 1;
  min-width: 200px;
}

label {
  font-weight: 600;
  margin-bottom: 0.35rem;
  color: #333;
}

input, select {
  padding: 0.6rem 0.8rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 0.95rem;
}

.btn-primary {
  background: #3b82f6;
  color: white;
  border: none;
  padding: 0.65rem 1.25rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-primary:hover:not(:disabled) {
  background: #2563eb;
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
