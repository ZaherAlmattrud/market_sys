<template>
    <div v-if="fileUrl">
        <img :src="fileUrl" style="height:100%;width:100%" alt="Invoice Image" />
    </div>
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