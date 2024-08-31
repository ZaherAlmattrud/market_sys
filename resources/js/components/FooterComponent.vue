<template>
    <v-footer color="gray" class="bg-grey-lighten-1">
      <v-row justify="center" no-gutters>
        <!-- <v-btn
          v-for="link in links"
          :key="link"
          class="mx-2"
          color="white"
          rounded="xl"
          variant="text"
        >
          {{ link }}
        </v-btn> -->
        <v-col class="text-center" cols="12">
          {{ new Date().getFullYear() }} — <strong>مركز المطرود التجاري</strong>
        </v-col>
      </v-row>
    </v-footer>
  </template>
  <script>
    export default {
      data: () => ({
        links: [
          'تمديدات كهربائية',
          'أكسسوارات صحية',
          'خراطيم زراعية',
          'رومانات',
          'قشط',
          'براغي',
        ],
      }),
    }
  </script>