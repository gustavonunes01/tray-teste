<template>
  <AppLayout>
    <div class="dashboard">
      <div class="card">
        <div class="flex justify-between items-center mb-4">
          <h2>Todas as Vendas</h2>
          <Button label="Nova Venda" icon="pi pi-plus" @click="openNewSaleDialog" />
        </div>
        <div class="flex justify-between items-center mb-4">
          <div class="flex align-items-center">
            <label for="sellerFilter" class="font-semibold mr-2">Filtrar por Vendedor:</label>
            <Dropdown
              id="sellerFilter"
              v-model="selectedSellerFilter"
              :options="[{ id: null, name: 'Todos' }, ...sellers]"
              optionLabel="name"
              optionValue="id"
              placeholder="Selecione um vendedor"
              class="w-20rem"
              @change="onSellerFilterChange"
            />
          </div>
        </div>
        <DataTable
          :value="sales.data"
          :paginator="true"
          :rows="sales.per_page"
          :loading="loading"
          dataKey="id"
          :rowsPerPageOptions="[5, 10, 25, 50]"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          currentPageReportTemplate="Mostrando {first} até {last} de {totalRecords} vendas"
          responsiveLayout="scroll"
          :totalRecords="sales.total"
          :first="(sales.current_page - 1) * sales.per_page"
          @page="onPage($event)"
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

    <!-- Dialog Nova Venda -->
    <Dialog v-model:visible="saleDialog" modal header="Nova Venda" :style="{ width: '30rem' }">
      <div class="grid">
        <div class="col-12 mb-4">
          <label for="name" class="font-semibold block mb-2">Nome do Pedido (opcional)</label>
          <InputText id="name" v-model="sale.name" class="w-full" placeholder="Digite o nome do pedido" />
        </div>
        <div class="col-12 mb-4">
          <label for="seller" class="font-semibold block mb-2">Vendedor</label>
          <Dropdown
            id="seller"
            v-model="sale.seller_id"
            :options="sellers"
            optionLabel="name"
            optionValue="id"
            placeholder="Selecione um vendedor"
            class="w-full"
          />
        </div>
        <div class="col-12 mb-4">
          <label for="price" class="font-semibold block mb-2">Valor</label>
          <InputNumber
            id="price"
            v-model="sale.price"
            mode="currency"
            currency="BRL"
            locale="pt-BR"
            class="w-full"
          />
        </div>
      </div>
      <template #footer>
        <Button label="Cancelar" icon="pi pi-times" text @click="saleDialog = false" />
        <Button label="Salvar" icon="pi pi-check" @click="saveSale" :loading="saving" />
      </template>
    </Dialog>

    <!-- Dialog Visualizar Venda -->
    <Dialog v-model:visible="viewSaleDialog" modal header="Detalhes da Venda" :style="{ width: '30rem' }">
      <div class="grid">
        <div class="col-12 mb-4">
          <label class="font-semibold block mb-2">ID</label>
          <div class="p-inputtext">{{ selectedSale?.external_id }}</div>
        </div>
        <div class="col-12 mb-4">
          <label class="font-semibold block mb-2">Nome do Pedido</label>
          <div class="p-inputtext">{{ selectedSale?.name || 'Não informado' }}</div>
        </div>
        <div class="col-12 mb-4">
          <label class="font-semibold block mb-2">Vendedor</label>
          <div class="p-inputtext">{{ getSellerName(selectedSale?.seller_id) }}</div>
        </div>
        <div class="col-12 mb-4">
          <label class="font-semibold block mb-2">Valor</label>
          <div class="p-inputtext">{{ formatCurrency(selectedSale?.price || 0) }}</div>
        </div>
        <div class="col-12 mb-4">
          <label class="font-semibold block mb-2">Comissão</label>
          <div class="p-inputtext">{{ formatCurrency(selectedSale?.commission_value || 0) }}</div>
        </div>
        <div class="col-12 mb-4">
          <label class="font-semibold block mb-2">Data de Criação</label>
          <div class="p-inputtext">{{ formatDate(selectedSale?.created_at || '') }}</div>
        </div>
      </div>
      <template #footer>
        <Button label="Fechar" icon="pi pi-times" @click="viewSaleDialog = false" />
      </template>
    </Dialog>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AppLayout from '@/components/layout/AppLayout.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import Dropdown from 'primevue/dropdown'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import RequestHelper from "@/common/request-helper"
import { BackEndRoutes } from "@/config/back-end-routes"
import type { MySalesResponse } from "@/types/my-sales-rest"
import { SellerService, type Seller } from '@/services/seller.service'
import { SalesService, type Sale, type SalesPagination } from '@/services/sales.service'

const loading = ref(true)
const saving = ref(false)
const sales = ref<SalesPagination>({
  current_page: 1,
  data: [],
  first_page_url: '',
  from: 0,
  last_page: 1,
  last_page_url: '',
  links: [],
  next_page_url: null,
  path: '',
  per_page: 10,
  prev_page_url: null,
  to: 0,
  total: 0
})
const saleDialog = ref(false)
const viewSaleDialog = ref(false)
const selectedSale = ref<Sale | null>(null)
const sellers = ref<Seller[]>([])
const selectedSellerFilter = ref<number | null>(null)
const sale = ref({
  name: '',
  price: 0,
  seller_id: null as number | null
})

onMounted(async () => {
  await Promise.all([
    loadSales(),
    loadSellers()
  ])
})

const loadSales = async (page: number = 1) => {
  try {
    const response = await SalesService.getSales(page)
    sales.value = response
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

function formatDate(isoString: string) {
  const date = new Date(isoString)
  return date.toLocaleDateString('pt-BR')
}

const getSellerName = (sellerId: number | undefined) => {
  if (!sellerId) return 'Não informado'
  const seller = sellers.value.find(s => s.id === sellerId)
  return seller?.name || 'Não informado'
}

const viewSale = (sale: Sale) => {
  selectedSale.value = sale
  viewSaleDialog.value = true
}

const openNewSaleDialog = () => {
  sale.value = {
    name: '',
    price: 0,
    seller_id: null
  }
  saleDialog.value = true
}

const saveSale = async () => {
  if (!sale.value.seller_id || !sale.value.price) {
    return
  }

  saving.value = true
  try {
    await SalesService.createSale(sale.value)
    await loadSales(sales.value.current_page)
    saleDialog.value = false
  } catch (error) {
    console.error('Erro ao salvar venda:', error)
  } finally {
    saving.value = false
  }
}

const onPage = (event: any) => {
  const page = event.page + 1
  loadSales(page)
}

const onSellerFilterChange = async () => {
  loading.value = true
  try {
    const response = await SalesService.getSales(1, sales.value.per_page)
    if (selectedSellerFilter.value) {
      sales.value = {
        ...response,
        data: response.data.filter(sale => sale.seller_id === selectedSellerFilter.value)
      }
    } else {
      sales.value = response
    }
  } catch (error) {
    console.error('Erro ao filtrar vendas:', error)
  } finally {
    loading.value = false
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