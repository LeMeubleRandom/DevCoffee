import { defineStore } from 'pinia'

/**
 * Store Pinia pour les métriques de l'Éco-Score
 *
 * Ce fichier doit contenir :
 * - L'état (state) stockant les valeurs actuelles des métriques (temps de réponse, taille du payload, CO2 estimé)
 * - Les actions pour mettre à jour ces valeurs à partir des en-têtes HTTP de la dernière réponse reçue
 */

export const useEcoScoreStore = defineStore('ecoScore', () => {
  // TODO: Définir les refs réactives pour responseTime, payloadSize, estimatedCo2
  // TODO: Définir l'action pour parser et enregistrer les headers HTTP (X-Response-Time-Ms, etc.)

  return {}
})
