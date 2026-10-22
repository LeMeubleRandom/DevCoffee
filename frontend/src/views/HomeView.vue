<script setup lang="ts">
import AlertBanner from '@/components/common/AlertBanner.vue'
import ConsumptionForm from '@/components/consumption/ConsumptionForm.vue'
import ConsumptionList from '@/components/consumption/ConsumptionList.vue'
import EcoScoreBadge from '@/components/metrics/EcoScoreBadge.vue'
</script>

<template>
  <div class="app-layout">
    <header class="app-header">
      <div class="header-inner">
        <h1>☕ DevCoffee Tracker</h1>
        <p class="subtitle">Suivi des consommations de caféine et prévention du surdosage (Max 400 mg/j)</p>
      </div>
    </header>

    <main class="main-content">
      <AlertBanner />
      <ConsumptionForm />
      <ConsumptionList />
    </main>

    <EcoScoreBadge />
  </div>
</template>

<style scoped>
.app-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.app-header {
  background: #1e293b;
  color: white;
  padding: 1.5rem 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.header-inner {
  max-width: 800px;
  margin: 0 auto;
}

h1 {
  margin: 0;
  font-size: 1.75rem;
}

.subtitle {
  margin: 0.3rem 0 0 0;
  font-size: 0.95rem;
  color: #94a3b8;
}

.main-content {
  max-width: 800px;
  width: 100%;
  margin: 2rem auto;
  padding: 0 1rem;
}
</style>
