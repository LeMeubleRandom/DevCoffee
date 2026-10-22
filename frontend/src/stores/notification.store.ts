import { defineStore } from 'pinia'

/**
 * Store Pinia pour la gestion des notifications et alertes d'erreurs (RFC 7807)
 *
 * Ce fichier doit contenir :
 * - L'état (state) stockant l'erreur active ou le message d'alerte (title, detail, status)
 * - Les actions pour définir une erreur (ex: lors d'un code 429 surdosage) et pour la réinitialiser/fermer
 */

export const useNotificationStore = defineStore('notification', () => {
  // TODO: Définir l'état de l'erreur active (conforme au format RFC 7807)
  // TODO: Définir les actions setError(problemDetails) et clearError()

  return {}
})
