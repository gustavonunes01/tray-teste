<template>
  <div>
    <h1>Dashboard</h1>
    <button @click="logout">Sair</button>
  </div>
</template>

<script lang="ts" setup>
import { useRouter } from 'vue-router'
import {useAuthStore} from "../../store/auth.ts";

const auth = useAuthStore()
const router = useRouter()

const logout = () => {
  auth.logout()
  router.push('/login')
}
</script>