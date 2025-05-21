<template>
  <div class="layout-wrapper px-5">
    <!-- Sidebar -->
    <aside class="layout-sidebar" :class="{ 'active': sidebarActive }">
      <div class="sidebar-header">
        <img src="https://sitetray.s3.amazonaws.com/wp-content/uploads/2023/01/logo_tray_site-svg.png" alt="Logo" class="logo" />
      </div>
      
      <Menu :model="menuItems" class="layout-menu">
        <template #item="{ item, props }">
          <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
            <a v-ripple :href="href" v-bind="props.action" @click="navigate">
              <span :class="item.icon" />
              <span class="ml-2">{{ item.label }}</span>
            </a>
          </router-link>
          <a v-else v-ripple :href="item.url" :target="item.target" v-bind="props.action">
            <span :class="item.icon" />
            <span class="ml-2">{{ item.label }}</span>
          </a>
        </template>
      </Menu>
    </aside>

    <!-- Main Content -->
    <div class="layout-main">
      <!-- Topbar -->
      <header class="layout-topbar">
        <button class="p-link layout-menu-button" @click="toggleSidebar">
          <i class="pi pi-bars"></i>
        </button>
        <div class="topbar-right">
          <p>{{userStore.currentUser?.name}}</p>
          <Button icon="pi pi-sign-out" @click="logout" text severity="danger" />
        </div>
      </header>

      <!-- Content -->
      <main class="layout-content">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { useUserStore } from '@/store/user.store.ts'
import Menu from 'primevue/menu'
import Button from 'primevue/button'

const router = useRouter()
const authStore = useAuthStore()
const userStore = useUserStore()
const sidebarActive = ref(true)

const menuItems = computed(() => {
  const items = [
    {
      label: 'Dashboard',
      icon: 'pi pi-home',
      route: '/dashboard'
    }
  ]

  // Se for admin (profile_id === 1), adiciona os outros itens
  if (userStore.currentUser?.profile_id === 1) {
    items.push(
      {
        label: 'Vendas',
        icon: 'pi pi-shopping-cart',
        route: '/vendas'
      },
      {
        label: 'Vendedores',
        icon: 'pi pi-users',
        route: '/sellers'
      }
    )
  }

  return items
})

const toggleSidebar = () => {
  sidebarActive.value = !sidebarActive.value
}

const logout = () => {
  authStore.logout()
  router.push('/login')
}

onMounted(async () => {
  if (!userStore.currentUser) {
    await userStore.fetchCurrentUser()
  }
})
</script>

<style scoped>
.layout-wrapper {
  display: flex;
  min-height: 100vh;
}

.layout-sidebar {
  width: 250px;
  background: var(--surface-section);
  border-right: 1px solid var(--surface-border);
  transition: transform 0.3s;
}

.layout-sidebar.active {
  transform: translateX(0);
}

.sidebar-header {
  padding: 1rem;
  text-align: center;
}

.logo {
  max-width: 150px;
}

.layout-menu {
  border: none;
}

.layout-main {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.layout-topbar {
  height: 4rem;
  background: var(--surface-section);
  border-bottom: 1px solid var(--surface-border);
  display: flex;
  align-items: center;
  padding: 0 1rem;
  justify-content: space-between;
}

.layout-menu-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.2s;
}

.layout-menu-button:hover {
  background: var(--surface-hover);
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.user-name {
  font-weight: 500;
}

.layout-content {
  flex: 1;
  padding: 2rem;
  background: var(--surface-ground);
}
</style> 