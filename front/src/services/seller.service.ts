import RequestHelper from "@/common/request-helper";
import { BackEndRoutes } from "@/config/back-end-routes";

export interface Seller {
    id: number
    name: string
    email: string
    email_verified_at: string
    profile_id: number
    created_at: string
    updated_at: string
}

export interface SellersPagination {
    current_page: number
    data: Seller[]
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

export class SellerService {
    static async getSellers(): Promise<SellersPagination> {
        const response = await RequestHelper.httpRequest(
            "GET",
            BackEndRoutes.routes.seller.LIST
        );
        return response.data;
    }

    static async deleteSeller(id: number): Promise<void> {
        await RequestHelper.httpRequest(
            "DELETE",
            BackEndRoutes.routes.seller.DELETE(id)
        );
    }

    static async notifySeller(id: number): Promise<void> {
        await RequestHelper.httpRequest(
            "GET",
            BackEndRoutes.routes.seller.NOTIFY(id)
        );
    }
} 