<template>
  <v-app-bar  app color="gray" image="storage/uploads/sidebar.png">
    <template v-slot:prepend>
      <v-btn @click="toggleDrawer" icon>
        <v-icon>mdi-menu</v-icon>
      </v-btn>
    </template>

    <!-- <v-app-bar-title>مركز المطرود التجاري</v-app-bar-title>  -->

<div class="ticker-container">
  <div class="ticker-text">
    <span v-for="(zekr, index) in azkar" :key="'first-' + index">
      {{ zekr }} &nbsp; • &nbsp;
    </span>
    <span v-for="(zekr, index) in azkar" :key="'second-' + index">
      {{ zekr }} &nbsp; • &nbsp;
    </span>
  </div>
</div>



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

     azkar: [
 
    
      "سبحان الله",
      "الحمد لله",
      "لا إله إلا الله",
     
      "الله أكبر",
      "لا حول ولا قوة إلا بالله",
      "أستغفر الله",
       "اللهم صل وسلم وبارك على سيدنا محمد",
       
        "الله اكبر ولله الحمد",
         "سبحان الله وبحمده سبحان الله العظيم",
   
     
       
    ],
    links: [
    

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
<style scoped> 
 .ticker-container {
  width: 60%;
  overflow: hidden;
  direction: rtl;
  height: 40px;
  display: flex;
  align-items: center;
  background: transparent;
}

.ticker-text {
  display: inline-block;
  white-space: nowrap;
  animation: scroll-continuous 40s linear infinite;
}

@keyframes scroll-continuous {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(+100%);
  }
}


</style>
