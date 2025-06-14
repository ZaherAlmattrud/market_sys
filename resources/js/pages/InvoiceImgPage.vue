<template>
  <v-container fluid class="pa-0">
    <v-row no-gutters>

           <v-col cols="2" md="2">
        <v-btn color="primary" class="mt-1" @click="goBack">
          ⬅️ رجوع إلى قائمة المشتريات
        </v-btn>
      </v-col>

      <v-col cols="10" md="10">
        <v-img :src="product.photo || defaultImage" alt="صورة المنتج" class="product-image" cover>
          <template v-slot:placeholder>
            <v-row class="fill-height ma-0" align="center" justify="center">
              <v-icon size="70" color="grey lighten-1">mdi-image-off</v-icon>
            </v-row>
          </template>
        </v-img>
      </v-col>

 
    </v-row>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      productId: null,
      defaultImage: '/images/default-product.jpg',
      product: {
        id: '',
        name: '',
        category: '',
        invoice: '',
        price_in_sp: '',
        price_in_dollar: '',
        fix_sell_in_sp: '',
        dynamic_sell_in_sp: '',
        sell_in_dollar: '',
        price_in_sp_after_descount: '',
        price_in_dollar_after_descount: '',
        photo: '',
        suppler: '',
        profit: '',
        date: '',
        dynamic_price_in_sp_after_descount: '',
        dynamic_sell_in_sp: '',
        exchange: '',

      }
    };
  },
  created() {
    this.productId = this.$route.params.id;
    this.loadProductDetails(this.productId);
  },
  methods: {
    loadProductDetails(id) {
      axios.get(`/api/invoice/photo/${id}`)
        .then(res => {
          const data = res.data;
          this.product = {

            photo: data.photo || '',
             

          };
        })
        .catch(error => {
          console.error('خطأ في جلب بيانات المنتج:', error);
        });
    },
    goBack() {
      this.$router.push('/invoices');
    }
  }
};
</script>

<style scoped>
.product-image {
  height: 100%;
  min-height: 100%;
}

.product-card {
  height: 100%;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.product-card table {
  border-collapse: collapse;
  width: 100%;
  direction: rtl;
  table-layout: fixed;
}

.product-card td {
  border: 1px solid #ddd;
  padding: 3px;
  text-align: right;
}

.product-card td:first-child {
  width: 40%;
  font-weight: 600;
}

.product-card td:last-child {
  width: 60%;
}

.product-card tr:nth-child(even) {
  background-color: #f9f9f9;
}

.product-card tr:hover {

  background-color: rgba(25, 118, 210, 0.1);
}
</style>
