import type { HateoasLink } from './drink'

export { type HateoasLink }

export interface Consumption {
  id: number
  developerName: string
  drinkName: string
  caffeineMg: number
  consumedAt: string
  _links?: {
    delete?: HateoasLink
    [key: string]: HateoasLink | undefined
  }
}

export interface CreateConsumptionDto {
  drinkId: number
  developerName: string
}
