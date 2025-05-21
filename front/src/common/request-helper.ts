import axios from "axios";
import {BackEndRoutes} from "../config/back-end-routes.ts";

interface OptionsExternal {
    externalURL?: "";
    headers?: object;
    responseType?: any;
    signal?: AbortSignal;
}

export class RequestHelper {
    static getAuthToken(accessToken: string | undefined) {
        return accessToken !== "" ? `Bearer  ${accessToken}` : "";
    }
    static async httpRequest(verb: "GET" | "POST" | "PUT" | "DELETE", path: string, data?:object | [] | undefined, opts?: OptionsExternal | undefined, accessToken?: string, responseType?: any){
        const token = localStorage.getItem('token')

        const headers: any = opts?.headers ? opts.headers : {};
        headers.Authorization = this.getAuthToken( token || "");

        return axios({
            method: verb,
            baseURL: opts?.externalURL ? "" : BackEndRoutes.getHost(),
            url: `${path}`,
            data,
            headers:headers,
            responseType: responseType ?? undefined,
            signal: opts?.signal,
        });
    }

    static getQueryString(object: any) {
        if (!object) return "";

        if (typeof object === "string") return object;

        if (typeof object === "object") object = { ...object };

        let queryString = "";

        // Monta a querystring
        Object.keys(object).forEach((key) => {
            if (object[key] !== null && object[key] !== undefined && object[key] !== "") {
                if (queryString.length > 0) queryString += "&";
                queryString += key + "=" + encodeURIComponent(object[key]);
            }
        });

        return queryString;
    }

}

export default RequestHelper;
