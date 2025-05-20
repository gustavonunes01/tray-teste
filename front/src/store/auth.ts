import { defineStore } from 'pinia'
import axios from 'axios'

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
            const res = await axios.post('https://sua-api.com/login', { username, password })
            this.token = res.data.token
            localStorage.setItem('token', this.token)
        },
        logout() {
            this.token = ''
            localStorage.removeItem('token')
        }
    }
})

