export interface HateoasLink {
  href: string
  method: string
}

export interface Drink {
  id: number
  name: string
  caffeineMg: number
  volumeMl: number
  _links?: {
    self?: HateoasLink
  }
}
