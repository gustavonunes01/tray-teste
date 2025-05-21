<template>
  <Dialog
    :visible="modelValue"
    @update:visible="emit('update:modelValue', $event)"
    modal
    :header="isEdit ? 'Editar Venda' : 'Nova Venda'"
    :style="{ width: '30rem' }"
  >
    <div class="grid">
      <div class="col-12 mb-4">
        <label for="name" class="font-semibold block mb-2">Nome do Pedido (opcional)</label>
        <InputText
          id="name"
          v-model="saleData.name"
          class="w-full"
          placeholder="Digite o nome do pedido"
        />
      </div>
      <div v-if="!props.is_my_sale" class="col-12 mb-4">
        <label for="seller" class="font-semibold block mb-2">Vendedor</label>
        <Dropdown
          id="seller"
          v-model="saleData.seller_id"
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
          v-model="saleData.price"
          mode="currency"
          currency="BRL"
          locale="pt-BR"
          class="w-full"
        />
      </div>
    </div>
    <template #footer>
      <Button label="Cancelar" icon="pi pi-times" text @click="onCancel" />
      <Button
        label="Salvar"
        icon="pi pi-check"
        @click="onSave"
        :loading="loading"
      />
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import InputNumber from 'primevue/inputnumber'
import type { Sale } from '@/services/sales.service'
import type { Seller } from '@/services/seller.service'

interface Props {
  modelValue: boolean
  sellers: Seller[]
  sale?: Sale
  loading?: boolean
  is_my_sale?: boolean
}

interface Emits {
  (e: 'update:modelValue', value: boolean): void
  (e: 'save', sale: Partial<Sale>): void
  (e: 'cancel'): void
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  sale: undefined,
  is_my_sale: false
})

const emit = defineEmits<Emits>()

const isEdit = computed(() => !!props.sale)

const saleData = ref<Partial<Sale>>({
  name: '',
  price: 0,
  seller_id: null
})

watch(() => props.sale, (newSale) => {
  if (newSale) {
    saleData.value = { ...newSale }
  } else {
    saleData.value = {
      name: '',
      price: 0,
      seller_id: null
    }
  }
}, { immediate: true })

const onSave = () => {
  emit('save', saleData.value)
}

const onCancel = () => {
  emit('cancel')
  emit('update:modelValue', false)
}
</script> 