<template>
    <v-form @submit.prevent="handleSubmit" ref="formRef" v-model="valid">
      <v-row>
        <v-col
          v-for="field in fields"
          :key="field.name"
          :cols="field.cols || 12"
        >
          <!-- دعم رفع الملفات -->
          <template v-if="field.component === 'file'">
            <v-label class="mb-1">{{ field.label }}</v-label>
            <input
              type="file"
              :accept="field.accept"
              @change="handleFileChange($event, field.name)"
            />
          </template>
  
          <!-- باقي الحقول -->
          <template v-else>
            <component
            outlined
            variant="outlined"
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
              
            />
          </template>
        </v-col>
      </v-row>
  
      <!-- Slot مخصص قبل الأزرار -->
      <slot name="before-actions" />
  
      <!-- الأزرار -->
      <v-row>
        <v-col cols="12" class="d-flex justify-end">
          <slot name="actions">
            <v-btn
              v-if="showCancel"
              variant="text"
              color="secondary"
              @click="$emit('cancel')"
              class="me-2"
            >
              {{ cancelLabel }}
            </v-btn>
            <v-btn color="primary" type="submit">
              {{ submitLabel }}
            </v-btn>
          </slot>
        </v-col>
      </v-row>
  
      <!-- Slot بعد الفورم -->
      <slot name="after-form" />
    </v-form>
  </template>
  
  <script setup>
  import { ref, computed } from "vue";
  import {
    VTextField,
    VTextarea,
    VSelect,
    VAutocomplete,
    VCheckbox,
  } from "vuetify/components";
  
  const props = defineProps({
    modelValue: Object,
    fields: Array,
    submitLabel: { type: String, default: "حفظ" },
    cancelLabel: { type: String, default: "إلغاء" },
    showCancel: { type: Boolean, default: false },
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
        return "input"; // handled manually
      default:
        return VTextField;
    }
  }
  </script>
  