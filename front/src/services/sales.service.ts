import RequestHelper from "@/common/request-helper";
import { BackEndRoutes } from "@/config/back-end-routes";

export interface Sale {
    id: number
    external_id: string
    name: string
    price: number
    commission_value: number
    seller_id: number
    created_at: string
    updated_at: string
}

export interface SalesPagination {
    current_page: number
    data: Sale[]
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    links: Link[]
    next_page_url: any
    path: string
    per_page: number
    prev_page_url: any
    to: number
    total: number
}

export interface Link {
    url?: string
    label: string
    active: boolean
}

export class SalesService {
    static async getSales(page: number = 1, perPage: number = 10): Promise<SalesPagination> {
        const response = await RequestHelper.httpRequest(
            "GET",
            `${BackEndRoutes.routes.sales.LIST}?page=${page}&per_page=${perPage}`
        );
        return response.data;
    }

    static async createSale(sale: Partial<Sale>): Promise<Sale> {
        const response = await RequestHelper.httpRequest(
            "POST",
            BackEndRoutes.routes.sales.CREATE,
            sale
        );
        return response.data;
    }
} 