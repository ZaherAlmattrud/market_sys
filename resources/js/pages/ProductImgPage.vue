<template>

    <v-container>
        <v-row>
            <v-col cols="12" md="10">
                <v-button @click="$router.go(-1)"> العودة الى قائمة المنتجات </v-button>
            </v-col>

        </v-row>

        <v-row>

            <div v-if="fileUrl">
                <img :src="fileUrl" style="height:100%;width:100%" alt="Invoice Image" />
            </div>
        </v-row>
    </v-container>




</template>
<script>


export default {


    data: () => ({

        fileUrl: null,

    }),

    async beforeCreate() {

        const invoiceId = this.$route.params.url;

        console.log("=====================================");
        console.log(invoiceId);
        const response = await axios.get(`/api/getProductImgLink/${invoiceId}`)
        console.log("=====================================");
        console.log(response.data);
        this.fileUrl = response.data['link']; // img link



        // this.fileUrl = file ;

    },

}






</script>
<style></style>