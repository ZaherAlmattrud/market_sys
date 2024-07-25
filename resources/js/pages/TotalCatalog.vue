<script setup>
import PDF from "pdf-vue3";
import { onMounted } from "vue";


 

const myFunction = () => {
  console.log('test function')
}



onMounted(() => {
  
  
 
})

</script>

<template>
  <PDF src="totalCatolog2024.pdf" />
  <!-- <PDF :src="BASE64" /> -->
  <!-- <PDF :src="Uint8Array" /> -->
</template>