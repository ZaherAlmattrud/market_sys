<template>
    <div>
      <table>
        <thead>
          <tr>
            <th v-for="header in headers" :key="header">{{ header }}</th>
            <th v-if="$slots.actions">العمليات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id">
            <td v-for="key in fields" :key="key">{{ item[key] }}</td>
            <td v-if="$slots.actions">
              <slot name="actions" :item="item" />
            </td>
          </tr>
        </tbody>
      </table>
  
      <div v-if="pagination && pagination.length" class="pagination">
        <button
          v-for="(link, index) in pagination"
          :key="index"
          v-html="link.label"
          :disabled="!link.url"
          :class="{ active: link.active }"
          @click="$emit('page-change', link.url)"
        ></button>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    headers: Array,
    fields: Array,
    items: Array,
    pagination: Array,
  });
  defineEmits(["page-change"]);
  </script>
  
  <style scoped>
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
  }
  
  thead {
    background-color: #f5f5f5;
    color: #424242;
    font-weight: 600;
  }
  
  th,
  td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
  }
  
  tbody tr:hover {
    background-color: #e3f2fd;
    cursor: pointer;
  }
  
  .pagination {
    margin-top: 20px;
    text-align: center;
  }
  
  .pagination button {
    min-width: 36px;
    margin: 0 3px;
    padding: 6px 10px;
    font-size: 14px;
    border-radius: 4px;
    background-color: #eeeeee;
    color: #424242;
    border: 1px solid transparent;
    transition: all 0.3s ease;
  }
  
  .pagination button:hover:not(:disabled) {
    background-color: #1976d2;
    color: white;
    border-color: #1976d2;
  }
  
  .pagination button.active {
    background-color: #1976d2;
    color: white;
    font-weight: 600;
    border-color: #115293;
  }
  
  .pagination button:disabled {
    cursor: not-allowed;
    opacity: 0.5;
  }
  </style>
  