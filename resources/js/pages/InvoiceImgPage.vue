<template>
    <div v-if="fileUrl">
        <img :src="fileUrl" alt="Invoice Image" />
    </div>
</template>
<script>


export default {


    data: () => ({

        fileUrl:null,

    }),

     beforeCreate() {

        const file = this.$route.params.url;
  
        this.fileUrl = file ;

    },

}






</script>
<style></style>