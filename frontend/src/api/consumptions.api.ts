import { apiClient } from './apiClient'
import type { Consumption, CreateConsumptionDto } from '@/types/consumption'

export async function getConsumptions(): Promise<Consumption[]> {
  return await apiClient<Consumption[]>('/api/consumptions', { method: 'GET' })
}

export async function createConsumptionApi(dto: CreateConsumptionDto): Promise<Consumption> {
  return await apiClient<Consumption>('/api/consumptions', {
    method: 'POST',
    body: JSON.stringify(dto),
  })
}

export async function deleteConsumptionByUrl(deleteUrl: string): Promise<void> {
  await apiClient<void>(deleteUrl, { method: 'DELETE' })
}
