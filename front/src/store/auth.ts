import { defineStore } from 'pinia'
import axios from 'axios'
import {BackEndRoutes} from "../config/back-end-routes.ts";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token') || ''
    }),
    getters: {
        isAuthenticated: (state) => !!state.token
    },
    actions: {
        async login(username: string, password: string) {
            // Substitua pela URL da sua API
            const url = BackEndRoutes.getHost() + BackEndRoutes.routes.auth.LOGIN;
            const res = await axios.post(url, { email:username, password })
            this.token = res.data.access_token
            localStorage.setItem('token', this.token)
        },
        logout() {
            this.token = ''
            localStorage.removeItem('token')
        }
    }
})

