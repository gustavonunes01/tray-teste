export type MySalesResponse = IMySales[]
export interface IMySales {
    id: number
    external_id: string
    name: string
    price: string
    commission_value: string
    seller_id: number
    created_at: string
    updated_at: string
    deleted_at: string | null
}