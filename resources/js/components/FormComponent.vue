<template>
  <v-sheet
    elevation="1"
    class="pa-4"
    style="max-height: 90vh; overflow-y: auto;"
  >
    <v-form @submit.prevent="handleSubmit"  @keydown.enter.prevent="handleSubmit" ref="formRef" v-model="valid">
      <!-- الحقول -->
      <v-row class="d-flex flex-wrap">
        <v-col
          v-for="field in fields"
          :key="field.name"
          :cols="field.fullWidth ? 12 : (field.cols || Math.floor(12 / gridCols))"
        >
          <!-- رفع ملفات -->
          <template v-if="field.component === 'file'">
            <v-label class="mb-1">{{ field.label }}</v-label>
            <input
              type="file"
              :accept="field.accept || 'image/*'"
              :capture="field.useCamera ? 'environment' : undefined"
              @change="handleFileChange($event, field.name)"
              class="my-2"
            />
          </template>

          <!-- رفع من كاميرا أو ملف -->
          <template v-else-if="field.component === 'cameraOrFile'">
            <v-label class="mb-1">{{ field.label }}</v-label>
            <CameraOrFile v-model:file="model[field.name]" />
          </template>

          <!-- باقي الحقول -->
          <template v-else>
            <component
              :is="resolveComponent(field)"
              v-model="model[field.name]"
              :label="field.label"
              :type="field.type"
              :items="field.items"
              :placeholder="field.placeholder"
              :rules="field.rules"
              :required="field.required"
              :clearable="field.clearable ?? true"
              :multiple="field.multiple"
              :chips="field.chips"
              :return-object="field.returnObject"
              :item-title="field.itemTitle"
              :item-value="field.itemValue"
              variant="outlined"
              density="compact"
              class="mb-2"
            />
          </template>
        </v-col>
      </v-row>

      <!-- أزرار -->
      <v-row class="mt-4">
        <v-col cols="12" class="d-flex justify-end">
          <v-btn v-if="showCancel" variant="text" color="secondary" @click="$emit('cancel')" class="me-2">
            {{ cancelLabel }}
          </v-btn>
          <v-btn color="primary" type="submit">{{ submitLabel }}</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-sheet>
</template>


<script setup>
import { ref, computed } from "vue";
import {
  VTextField,
  VTextarea,
  VSelect,
  VAutocomplete,
  VCheckbox,
  VBtn,
  VRow,
  VCol,
  VForm,
  VLabel,
  VBtnToggle,
} from "vuetify/components";

import CameraOrFile from "./CameraOrFile.vue";

const props = defineProps({
  modelValue: Object,
  fields: Array,
  submitLabel: { type: String, default: "حفظ" },
  cancelLabel: { type: String, default: "إلغاء" },
  showCancel: { type: Boolean, default: false },
  gridCols: { type: Number, default: 1 },
});

const emit = defineEmits(["submit", "cancel", "update:modelValue"]);

const model = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const valid = ref(true);
const formRef = ref(null);

function handleSubmit() {
  if (formRef.value?.validate()) {
    emit("submit", { ...model.value });
  }
}

function handleFileChange(event, fieldName) {
  const file = event.target.files[0];
  if (file) {
    model.value[fieldName] = file;
  }
}

function resolveComponent(field) {
  switch (field.component) {
    case "VTextarea":
      return VTextarea;
    case "VSelect":
      return VSelect;
    case "VAutocomplete":
      return VAutocomplete;
    case "VCheckbox":
      return VCheckbox;
    case "file":
      return "input";
    case "cameraOrFile":
      return CameraOrFile;
    default:
      return VTextField;
  }
}
</script>
