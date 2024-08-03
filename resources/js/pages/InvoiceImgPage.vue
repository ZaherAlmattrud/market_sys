<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="10">
        <v-button @click="$router.go(-1)"> العودة الى قائمة الفواتير </v-button>
      </v-col>
    </v-row>

    <v-row>
      <!-- <div v-if="fileUrl">
                <img :src="fileUrl" style="height:100%;width:100%" alt="Invoice Image" />
            </div> -->

      <div>
        <img :src="imageUrl" alt="Image" v-if="imageUrl" />
      </div>
    </v-row>
  </v-container>

  <!-- <div v-if="fileUrl">
        <img :src="fileUrl" style="height:100%;width:100%" alt="Invoice Image" />
    </div> -->
</template>
<script>
export default {
  data: () => ({
    imageUrl: null,
  }),

  mounted() {
    const invoiceId = this.$route.params.id;
    this.fetchImage(invoiceId); // Replace 1 with the actual image ID
  },

  methods: {
    async fetchImage(invoiceId) {
      try {

        const response = await axios.get(`/api/getInvoiceImgLink/${invoiceId}`);
        const blob = await response.blob();
        this.imageUrl = URL.createObjectURL(blob);
        
      } catch (error) {
        console.error("Error fetching image:", error);
      }
    },

    // async beforeCreate() {

    //     const invoiceId = this.$route.params.id;

    //     // console.log("=====================================");
    //     // console.log(invoiceId);
    //     // const response = await axios.get(`/api/getInvoiceImgLink/${invoiceId}`)
    //     // console.log("=====================================");
    //     // console.log(response.data);
    //     // this.fileUrl = response.data['link']; // img link

    //     try {
    //     const response = await fetch(`/api/getInvoiceImgLink/${invoiceId}`);
    //     const blob = await response.blob();
    //     this.fileUrl = URL.createObjectURL(blob);
    //   } catch (error) {
    //     console.error('Error fetching image:', error);
    //   }
  },
};
</script>
<style></style>
