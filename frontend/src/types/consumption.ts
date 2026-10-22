/**
 * Types et interfaces pour les consommations et liens HATEOAS
 *
 * Ce fichier doit contenir :
 * - L'interface pour les liens HATEOAS (ex: HateoasLink avec href, method)
 * - L'interface Consumption (id, developerName, drinkId/drinkName, date, _links)
 * - Le payload de création pour le POST /api/consumptions (CreateConsumptionDto)
 */

export interface HateoasLink {
  // TODO: Définir la structure d'un lien HATEOAS (ex: href: string)
}

export interface Consumption {
  // TODO: Définir les champs d'une consommation et la propriété _links
}

export interface CreateConsumptionDto {
  // TODO: Définir le payload attendu par POST /api/consumptions (nom du dév, boisson choisie)
}
