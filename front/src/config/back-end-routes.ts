export const BackEndRoutes = {
    getHost: (): string => {
        const urlEnvDev = import.meta.env.VITE_API_LOCAL_URL || "";
        const urlEnvProd = import.meta.env.VITE_API_PRODUCTION_URL || "";

        const urlDev =
            urlEnvDev.length > 0
                ? urlEnvDev
                : "http://127.0.0.1:8001/api";

        const urlProd =
            urlEnvProd.length > 0
                ? urlEnvProd
                : "";

        const isDev = urlDev.length > 0;

        return isDev ? urlDev : urlProd;
    },
    routes: {
        auth: {
            LOGIN: "/login",
            ME: '/me',
            LOGOUT: "/logout",
        },
        sales: {
            LIST: "/v1/sales",
            SHOWS: "/v1/sales/ex/:external_id",
            DELETE: "/v1/sales/:external_id",
            CREATE: "/v1/sales",
        },
        seller: {
            LIST: "/v1/sellers",
            MY_SALES: "/v1/sellers/my-sales",
            UPDATE: "/v1/sellers/:id",
            CREATE: "/v1/sellers",
            DELETE: "/v1/sellers/:id",
            NOTIFY: (id: number) => `/v1/sellers/email/notify/${id}`,
        }
    }
}