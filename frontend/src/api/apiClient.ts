/**
 * Client HTTP de base (Wrapper fetch ou axios)
 *
 * Ce fichier doit contenir :
 * - Une fonction centrale d'appel HTTP (ex: apiFetch ou instance axios)
 * - L'interception des en-têtes HTTP de réponse (X-Response-Time-Ms, X-Payload-Size-Bytes, X-Estimated-CO2-Grams)
 *   pour mettre à jour le store d'Éco-Score
 * - L'interception et le traitement des erreurs (notamment parsing du format RFC 7807)
 *   pour notifier l'utilisateur via le store de notifications/alertes
 */

// TODO: Implémenter la fonction d'appel API et l'extraction des en-têtes / gestion des erreurs
