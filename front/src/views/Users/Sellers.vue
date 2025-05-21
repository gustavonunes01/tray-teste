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
          <Column :exportable="false" style="min-width:8rem">
            <template #body="slotProps">
              <Button icon="pi pi-pencil" text rounded @click="editSeller(slotProps.data)" class="mr-2" />
              <Button icon="pi pi-send" text rounded @click="confirmNotifySeller(slotProps.data)" class="mr-2"
                      v-tooltip.bottom="'Enviar notificação'"
              />
              <Button icon="pi pi-trash" text rounded severity="danger" @click="confirmDeleteSeller(slotProps.data)" class="mr-2" />
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
    <Dialog v-model:visible="deleteSellerDialog" :style="{width: '450px'}" header="Confirmar" :modal="true">
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="seller">Tem certeza que deseja excluir <b>{{seller.name}}</b>?</span>
      </div>
      <template #footer>
        <Button label="Não" icon="pi pi-times" text @click="deleteSellerDialog = false"/>
        <Button label="Sim" icon="pi pi-check" text @click="deleteSeller" />
      </template>
    </Dialog>

    <!-- Dialog de Notificação -->
    <Dialog v-model:visible="notifySellerDialog" :style="{width: '450px'}" header="Confirmar Notificação" :modal="true">
      <div class="confirmation-content">
        <i class="pi pi-envelope mr-3" style="font-size: 2rem" />
        <span v-if="seller">Deseja enviar uma notificação de vendas para <b>{{seller.name}}</b>?</span>
      </div>
      <template #footer>
        <Button label="Não" icon="pi pi-times" text @click="notifySellerDialog = false"/>
        <Button label="Sim" icon="pi pi-check" text @click="notifySeller" />
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
import Tooltip from 'primevue/tooltip'
import RequestHelper from "@/common/request-helper"
import { BackEndRoutes } from "@/config/back-end-routes"
import { SellerService, type Seller, type SellersPagination } from '@/services/seller.service'
import { useToast } from 'primevue/usetoast'
import { formatDate } from '@/common/utils'

const loading = ref(true)
const saving = ref(false)
const deleting = ref(false)
const sellers = ref<Seller[]>([])
const sellerDialog = ref(false)
const deleteSellerDialog = ref(false)
const notifySellerDialog = ref(false)
const seller = ref({
  id: null as number | null,
  name: '',
  email: ''
})
const selectedSeller = ref<Seller | null>(null)
const submitted = ref(false)

const dialogHeader = computed(() => {
  return seller.value.id ? 'Editar Vendedor' : 'Novo Vendedor'
})

// Registrar a diretiva do tooltip
const vTooltip = Tooltip

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
  submitted.value = false
  sellerDialog.value = true
}

const editSeller = (data: Seller) => {
  seller.value = { ...data }
  sellerDialog.value = true
}

const confirmDeleteSeller = (data: Seller) => {
  seller.value = { ...data }
  deleteSellerDialog.value = true
}

const hideDialog = () => {
  sellerDialog.value = false
  submitted.value = false
}

const saveSeller = async () => {
  submitted.value = true

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
    deleteSellerDialog.value = false
  } catch (error) {
    console.error('Erro ao excluir vendedor:', error)
  } finally {
    deleting.value = false
  }
}

const confirmNotifySeller = (data: Seller) => {
  seller.value = { ...data }
  notifySellerDialog.value = true
}

const notifySeller = async () => {
  if (!seller.value?.id) return
  try {
    await SellerService.notifySeller(seller.value.id)
    notifySellerDialog.value = false
  } catch (error) {
    console.error('Erro ao enviar notificação:', error)
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