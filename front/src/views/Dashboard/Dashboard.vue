<template>
  <AppLayout>
    <div class="dashboard">
      <div class="card">
        <div class="flex justify-between items-center mb-4">
          <h2>Minhas Vendas</h2>
          <Button label="Nova Venda" icon="pi pi-plus" @click="openNewSaleDialog" />
        </div>
        <DataTable
          :value="sales"
          :paginator="true"
          :rows="10"
          :loading="loading"
          dataKey="id"
          :rowsPerPageOptions="[5, 10, 25, 50]"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          currentPageReportTemplate="Mostrando {first} até {last} de {totalRecords} vendas"
          responsiveLayout="scroll"
        >
          <Column field="external_id" header="ID" sortable style="width: 4rem"></Column>
          <Column field="name" header="Pedido" sortable></Column>
          <Column field="price" header="Total" sortable>
            <template #body="slotProps">
              {{ formatCurrency(slotProps.data.price) }}
            </template>
          </Column>
          <Column field="commission_value" header="Comissão" sortable>
            <template #body="slotProps">
              {{ formatCurrency(slotProps.data.commission_value) }}
            </template>
          </Column>
          <Column field="created_at" header="Data" sortable>
            <template #body="slotProps">
              {{ formatDate(slotProps.data.created_at) }}
            </template>
          </Column>
          <Column header="Ações" style="width: 8rem">
            <template #body="slotProps">
              <Button icon="pi pi-eye" text rounded @click="viewSale(slotProps.data)" />
            </template>
          </Column>
        </DataTable>
      </div>
    </div>

    <!-- Dialog Nova/Editar Venda -->
    <SaleDialog
      v-model="saleDialog"
      :sellers="sellers"
      :loading="saving"
      :sale="selectedSale"
      :is-view="isViewMode"
      :is_my_sale="true"
      @save="saveSale"
      @cancel="closeDialog"
    />
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AppLayout from '@/components/layout/AppLayout.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import RequestHelper from "@/common/request-helper"
import { BackEndRoutes } from "@/config/back-end-routes"
import type { MySalesResponse } from "@/types/my-sales-rest"
import { SellerService, type Seller } from '@/services/seller.service'
import SaleDialog from '@/components/sales/SaleDialog.vue'
import { useUserStore } from '@/store/user.store.ts'

const loading = ref(true)
const saving = ref(false)
const sales = ref<MySalesResponse>([])
const saleDialog = ref(false)
const sellers = ref<Seller[]>([])
const selectedSale = ref<Sale | null>(null)
const isViewMode = ref(false)
const userStore = useUserStore()

onMounted(async () => {
  await Promise.all([
    loadSales(),
    loadSellers()
  ])
})

const loadSales = async () => {
  try {
    const response = await RequestHelper.httpRequest(
      "GET",
      BackEndRoutes.routes.seller.MY_SALES
    )
    sales.value = response.data
  } catch (error) {
    console.error('Erro ao carregar vendas:', error)
  } finally {
    loading.value = false
  }
}

const loadSellers = async () => {
  try {
    const response = await SellerService.getSellers()
    sellers.value = response.data
  } catch (error) {
    console.error('Erro ao carregar vendedores:', error)
  }
}

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(value)
}

function formatDate(isoString) {
  const date = new Date(isoString)
  return date.toLocaleDateString('pt-BR')
}

const viewSale = (sale: Sale) => {
  selectedSale.value = sale
  isViewMode.value = true
  saleDialog.value = true
}

const openNewSaleDialog = () => {
  selectedSale.value = null
  isViewMode.value = false
  saleDialog.value = true
}

const closeDialog = () => {
  saleDialog.value = false
  selectedSale.value = null
  isViewMode.value = false
}

const saveSale = async (saleData: any) => {
  if (!saleData.price) {
    return
  }

  console.log(userStore.currentUser?.id)

  saving.value = true
  try {
    await RequestHelper.httpRequest(
      "POST",
      BackEndRoutes.routes.sales.CREATE,
      { ...saleData, seller_id: userStore.currentUser?.id }
    )
    await loadSales()
    saleDialog.value = false
  } catch (error) {
    console.error('Erro ao salvar venda:', error)
  } finally {
    saving.value = false
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
</style>