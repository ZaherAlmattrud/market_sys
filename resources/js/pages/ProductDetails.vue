<template>
  <v-container fluid class="pa-0">
    <v-row no-gutters>
      <v-col cols="12" md="6">
        <v-img :src="product.photo || defaultImage" alt="صورة المنتج" class="product-image" cover>
          <template v-slot:placeholder>
            <v-row class="fill-height ma-0" align="center" justify="center">
              <v-icon size="70" color="grey lighten-1">mdi-image-off</v-icon>
            </v-row>
          </template>
        </v-img>
      </v-col>

      <v-col cols="12" md="6">
        <v-card class="product-card" dir="rtl">


          <div dir="rtl">
            <v-simple-table dense>
              <tbody>
                <tr>
                  <td>الرقم التسلسلي</td>
                  <td>{{ product.id }}</td>
                </tr>
                <tr>
                  <td>الاسم</td>
                  <td>{{ product.name }}</td>
                </tr>

                  <tr>
                  <td>الكود</td>
                  <td>{{ product.code }}</td>
                </tr>

                <tr>
                  <td>ثابت الشراء بالليرة</td>
                  <td>{{ product.price_in_sp }}</td>
                </tr>
                <tr>
                  <td> سعر الشراء بالدولار</td>
                  <td>{{ product.price_in_dollar }}</td>
                </tr>
                <tr>
                  <td> ثابت الشراء بعد الحسم بالليرة</td>
                  <td style="color:coral;">{{ product.price_in_sp_after_descount }}</td>
                </tr>
                <tr>
                  <td>سعر الشراء بعد الحسم بالدولار</td>
                  <td style="color:coral;">{{ product.price_in_dollar_after_descount }}</td>
                </tr>

                <tr>
                  <td>الصنف وحسم النشرة</td>
                  <td>{{ product.category }}</td>
                </tr>
                <tr>
                  <td>الفاتورة</td>
                  <td>{{ product.invoice }}</td>
                </tr>
                <tr>
                  <td>التاجر</td>
                  <td>{{ product.suppler }}</td>
                </tr>
                <tr>
                  <td>متغير الشراء بالليرة</td>
                  <td>{{ product.dynamic_price_in_sp }}</td>
                </tr>
                <tr>
                  <td>متغير الشراء بعد الحسم بالليرة</td>
                  <td>{{ product.dynamic_price_in_sp_after_descount }} </td>
                </tr>
                <tr>
                  <td> الربح </td>
                  <td>{{ product.profit }}</td>
                </tr>
                <tr>
                  <td>ثابت البيع بالدولار</td>
                  <td style="color:coral;">{{ product.sell_in_dollar }}</td>
                </tr>
                <tr>
                  <td>متغير البيع بالليرة</td>
                  <td style="color:coral;">{{ product.dynamic_sell_in_sp }}</td>
                </tr>
                <tr>
                  <td> التاريخ </td>
                  <td>{{ product.date }}</td>
                </tr>
                  <tr>
                  <td> الصرف </td>
                  <td>{{ product.exchange }}</td>
                </tr>


                
              </tbody>
                <v-btn color="primary" class="mt-1" @click="goBack">
            ⬅️ رجوع إلى قائمة المنتجات
          </v-btn>

            </v-simple-table>
           
          </div>

         
        </v-card>
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
        dynamic_price_in_sp_after_descount:'',
        dynamic_sell_in_sp:'',
        exchange:'',
        
      }
    };
  },
  created() {
    this.productId = this.$route.params.id;
    this.loadProductDetails(this.productId);
  },
  methods: {
    loadProductDetails(id) {
      axios.get(`/api/products/${id}`)
        .then(res => {
          const data = res.data;
          this.product = {


            id: data.id || '',
            name: data.name || '',
            code: data.code || '',
            category: data.category || '',
            invoice: data.invoice || '',
            price_in_sp: data.price_in_sp || '',
            price_in_dollar: data.price_in_dollar || '',
            fix_sell_in_sp: data.fix_sell_in_sp || '',
            sell_in_dollar: data.sell_in_dollar || '',
            price_in_sp_after_descount: data.price_in_sp_after_descount || '',
            price_in_dollar_after_descount: data.price_in_dollar_after_descount || '',
            dynamic_price_in_sp: data.dynamic_price_in_sp || '',
            photo: data.photo || '',
            suppler: data.suppler || '',
            profit: data.profit || '',
            date: data.date || '',
            dynamic_price_in_sp_after_descount: data.dynamic_price_in_sp_after_descount || '',
            dynamic_sell_in_sp : data.dynamic_sell_in_sp || '',
            exchange : data.exchange || '',

          };
        })
        .catch(error => {
          console.error('خطأ في جلب بيانات المنتج:', error);
        });
    },
    goBack() {
      this.$router.push('/products');
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
