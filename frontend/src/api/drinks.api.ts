import { apiClient } from './apiClient'
import type { Drink } from '@/types/drink'

export async function getDrinks(): Promise<Drink[]> {
  return await apiClient<Drink[]>('/api/drinks', { method: 'GET' })
}
