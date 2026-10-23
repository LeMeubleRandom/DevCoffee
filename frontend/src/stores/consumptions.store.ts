import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Drink } from '@/types/drink'
import type { Consumption, CreateConsumptionDto } from '@/types/consumption'
import { getDrinks } from '@/api/drinks.api'
import { getConsumptions, createConsumptionApi, deleteConsumptionByUrl } from '@/api/consumptions.api'

export const useConsumptionsStore = defineStore('consumptions', () => {
  const drinks = ref<Drink[]>([])
  const consumptions = ref<Consumption[]>([])
  const loading = ref(false)

  async function loadDrinks() {
    try {
      drinks.value = await getDrinks()
    } catch (e) {
      console.error('Erreur chargement boissons', e)
    }
  }

  async function loadConsumptions() {
    loading.value = true
    try {
      consumptions.value = await getConsumptions()
    } catch (e) {
      console.error('Erreur chargement consommations', e)
    } finally {
      loading.value = false
    }
  }

  async function addConsumption(dto: CreateConsumptionDto) {
    await createConsumptionApi(dto)
    await loadConsumptions()
  }

  async function removeConsumption(url: string) {
    await deleteConsumptionByUrl(url)
    await loadConsumptions()
  }

  return {
    drinks,
    consumptions,
    loading,
    loadDrinks,
    loadConsumptions,
    addConsumption,
    removeConsumption,
  }
})
