<template>
  <AppLayout>
    <div class="dashboard">
      <div class="card">
        <div class="flex justify-between items-center mb-4">
          <h2>Vendedores</h2>
          <Button label="Novo Vendedor" icon="pi pi-plus" @click="openNewSellerDialog" />
        </div>
        <DataTable
            :value="sellers"
            :paginator="true"
            :rows="10"
            :loading="loading"
            dataKey="id"
            :rowsPerPageOptions="[5, 10, 25, 50]"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            currentPageReportTemplate="Mostrando {first} até {last} de {totalRecords} vendedores"
            responsiveLayout="scroll"
        >
          <Column field="id" header="ID" sortable style="width: 4rem"></Column>
          <Column field="name" header="Nome" sortable></Column>
          <Column field="email" header="Email" sortable></Column>
          <Column field="created_at" header="Data de Cadastro" sortable>
            <template #body="slotProps">
              {{ formatDate(slotProps.data.created_at) }}
            </template>
          </Column>
          <Column header="Ações" style="width: 8rem">
            <template #body="slotProps">
              <Button icon="pi pi-pencil" text rounded @click="editSeller(slotProps.data)" class="mr-2" />
              <Button icon="pi pi-trash" text rounded severity="danger" @click="confirmDeleteSeller(slotProps.data)" />
            </template>
          </Column>
        </DataTable>
      </div>
    </div>

    <!-- Dialog Novo/Editar Vendedor -->
    <Dialog v-model:visible="sellerDialog" modal :header="dialogHeader" :style="{ width: '30rem' }">
      <div class="grid">
        <div class="col-12 mb-4">
          <label for="name" class="font-semibold block mb-2">Nome</label>
          <InputText id="name" v-model="seller.name" class="w-full" placeholder="Digite o nome do vendedor" />
        </div>
        <div class="col-12 mb-4">
          <label for="email" class="font-semibold block mb-2">Email</label>
          <InputText id="email" v-model="seller.email" class="w-full" placeholder="Digite o email do vendedor" type="email" />
        </div>
      </div>
      <template #footer>
        <Button label="Cancelar" icon="pi pi-times" text @click="sellerDialog = false" />
        <Button label="Salvar" icon="pi pi-check" @click="saveSeller" :loading="saving" />
      </template>
    </Dialog>

    <!-- Dialog de Confirmação de Exclusão -->
    <Dialog v-model:visible="deleteDialog" modal header="Confirmar Exclusão" :style="{ width: '30rem' }">
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span>Tem certeza que deseja excluir este vendedor?</span>
      </div>
      <template #footer>
        <Button label="Não" icon="pi pi-times" text @click="deleteDialog = false" />
        <Button label="Sim" icon="pi pi-check" text severity="danger" @click="deleteSeller" :loading="deleting" />
      </template>
    </Dialog>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import AppLayout from '@/components/layout/AppLayout.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import RequestHelper from "@/common/request-helper"
import { BackEndRoutes } from "@/config/back-end-routes"
import { SellerService, type Seller, type SellersPagination } from '@/services/seller.service'

const loading = ref(true)
const saving = ref(false)
const deleting = ref(false)
const sellers = ref<Seller[]>([])
const sellerDialog = ref(false)
const deleteDialog = ref(false)
const seller = ref({
  id: null as number | null,
  name: '',
  email: ''
})
const selectedSeller = ref<Seller | null>(null)

const dialogHeader = computed(() => {
  return seller.value.id ? 'Editar Vendedor' : 'Novo Vendedor'
})

onMounted(async () => {
  await loadSellers()
})

const loadSellers = async () => {
  try {
    const response = await SellerService.getSellers()
    sellers.value = response.data
  } catch (error) {
    console.error('Erro ao carregar vendedores:', error)
  } finally {
    loading.value = false
  }
}

function formatDate(isoString) {
  const date = new Date(isoString)
  return date.toLocaleDateString('pt-BR')
}

const openNewSellerDialog = () => {
  seller.value = {
    id: null,
    name: '',
    email: ''
  }
  sellerDialog.value = true
}

const editSeller = (data: Seller) => {
  seller.value = { ...data }
  sellerDialog.value = true
}

const confirmDeleteSeller = (data: Seller) => {
  seller.value = { ...data }
  deleteDialog.value = true
}

const saveSeller = async () => {
  if (!seller.value.name || !seller.value.email) {
    return
  }

  saving.value = true
  try {
    if (seller.value.id) {
      await RequestHelper.httpRequest(
          "PUT",
          `${BackEndRoutes.routes.seller.UPDATE.replace(":id", seller.value.id)}`,
          seller.value
      )
    } else {
      await RequestHelper.httpRequest(
          "POST",
          BackEndRoutes.routes.seller.CREATE,
          seller.value
      )
    }
    await loadSellers()
    sellerDialog.value = false
  } catch (error) {
    console.error('Erro ao salvar vendedor:', error)
  } finally {
    saving.value = false
  }
}

const deleteSeller = async () => {
  if (!seller.value?.id) return
  console.log("deletando", seller.value)
  deleting.value = true
  try {
    await RequestHelper.httpRequest(
        "DELETE",
        `${BackEndRoutes.routes.seller.DELETE.replace(":id", seller.value.id)}`,
    )
    await loadSellers()
    deleteDialog.value = false
  } catch (error) {
    console.error('Erro ao excluir vendedor:', error)
  } finally {
    deleting.value = false
  }
}
</script>

<style scoped>
.dashboard {
  .card {
    background: var(--surface-card);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 1px -1px rgba(0,0,0,.2), 0 1px 1px 0 rgba(0,0,0,.14), 0 1px 3px 0 rgba(0,0,0,.12);
  }

  h2 {
    margin-bottom: 1.5rem;
    color: var(--text-color);
  }
}

.confirmation-content {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
}
</style>