<script setup lang="ts">
import { useNotificationStore } from '@/stores/notification.store'

const notificationStore = useNotificationStore()
</script>

<template>
  <div v-if="notificationStore.currentError" class="alert-banner">
    <div class="alert-content">
      <div class="alert-header">
        <span class="badge">Erreur {{ notificationStore.currentError.status }}</span>
        <strong class="title">{{ notificationStore.currentError.title }}</strong>
      </div>
      <p class="detail">{{ notificationStore.currentError.detail }}</p>
    </div>
    <button class="close-btn" @click="notificationStore.clearError()" title="Fermer">✕</button>
  </div>
</template>

<style scoped>
.alert-banner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fee2e2;
  border-left: 6px solid #ef4444;
  color: #991b1b;
  padding: 1rem 1.25rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.alert-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.25rem;
}

.badge {
  background: #dc2626;
  color: white;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: bold;
}

.title {
  font-size: 1rem;
}

.detail {
  margin: 0;
  font-size: 0.9rem;
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  color: #991b1b;
  padding: 0.25rem 0.5rem;
}

.close-btn:hover {
  color: #000;
}
</style>
