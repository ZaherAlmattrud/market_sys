<template>
  <v-app-bar  app color="gray" image="storage/uploads/sidebar.png">
    <template v-slot:prepend>
      <v-btn @click="toggleDrawer" icon>
        <v-icon>mdi-menu</v-icon>
      </v-btn>
    </template>

    <!-- <v-app-bar-title>مركز المطرود التجاري</v-app-bar-title>  -->

    <template v-slot:append>
      <v-row justify="center" no-gutters>
        <v-btn
          v-for="link in links"
          :key="id"
          class="mx-1"
          color="white"
          rounded="xl"
          variant="text"
        >
          <v-btn @click="moveTo(link.page)"> {{ link.title }} </v-btn>
        </v-btn>
      </v-row>
    </template>
  </v-app-bar>
</template>

<script>
export default {
  data: () => ({
    links: [
      { id: 8, title: "دليل البراغي", page: "TotalCatalog", pdf: "totalCatolog2024.pdf" },

      {
        id: 7,
        title: "دليل الكهرباء",
        page: "TotalCatalog",
        pdf: "totalCatolog2024.pdf",
      },

      { id: 6, title: "دليل الصحية", page: "TotalCatalog", pdf: "totalCatolog2024.pdf" },

      {
        id: 5,
        title: "دليل الرومانات",
        page: "TotalCatalog",
        pdf: "totalCatolog2024.pdf",
      },

      { id: 4, title: "دليل القشط", page: "TotalCatalog", pdf: "totalCatolog2024.pdf" },

      {
        id: 3,
        title: "معدلات ليبرا",
        page: "LibraRatingCatalog",
        pdf: "totalCatolog2024.pdf",
      },

      { id: 2, title: "دليل ليبرا", page: "LibraCatalog", pdf: "totalCatolog2024.pdf" },

      { id: 1, title: "دليل توتال", page: "TotalCatalog", pdf: "totalCatolog2024.pdf" },
    ],
  }),

  methods: {
    toggleDrawer() {
      this.$store.commit("toggleDrawer"); // استدعاء mutation مباشرة
    },
    moveTo(page) {
      this.$router.push({ name: page });
    },
  },
};
</script>
