import { defineStore } from 'pinia'

/**
 * Store Pinia pour la gestion globale de l'état des consommations et des boissons
 *
 * Ce fichier doit contenir :
 * - L'état (state) des listes (drinks, consumptions) et les indicateurs de chargement (loading, error)
 * - Les actions pour charger les boissons (GET /api/drinks)
 * - Les actions pour charger les consommations (GET /api/consumptions)
 * - L'action pour ajouter une consommation (POST /api/consumptions)
 * - L'action pour supprimer une consommation via son lien HATEOAS
 */

export const useConsumptionsStore = defineStore('consumptions', () => {
  // TODO: Définir les listes réactives des boissons et des consommations
  // TODO: Définir les actions d'orchestration (fetchDrinks, fetchConsumptions, createConsumption, deleteConsumption)

  return {}
})
