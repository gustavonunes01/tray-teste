import RequestHelper from "@/common/request-helper";
import { BackEndRoutes } from "@/config/back-end-routes";

export interface User {
    id: number
    name: string
    email: string
    email_verified_at: string
    profile_id: number
    created_at: string
    updated_at: string
}

export class UserService {
    static async getCurrentUser(): Promise<User> {
        const response = await RequestHelper.httpRequest(
            "GET",
            BackEndRoutes.routes.auth.ME
        );
        return response.data;
    }
} 