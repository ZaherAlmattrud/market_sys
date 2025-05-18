<template>
  <v-container fluid>
    <v-row>
      <!-- صورة المنتج -->
      <v-col cols="12" md="6" class="pa-2">
        <v-img
          src="storage\download.jpg"
          alt="صورة المنتج"
          class="h-auto"
          cover
        ></v-img>
      </v-col>

      <!-- تفاصيل المنتج -->
      <v-col cols="12" md="6" class="pa-8">
        <v-card elevation="2" class="pa-6">
          <v-card-title class="text-h5 mb-4">
            تفاصيل المنتج #{{ productId }}
          </v-card-title>
          <v-list dense>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>الاسم: {{ product.name }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>الصنف: {{ product.category }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-divider class="my-3"></v-divider>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>سعر الشراء (ل.س): {{ product.buyPriceSYP }}</v-list-item-title>
                <v-list-item-title>سعر الشراء ($): {{ product.buyPriceUSD }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>سعر المبيع (ل.س): {{ product.sellPriceSYP }}</v-list-item-title>
                <v-list-item-title>سعر المبيع ($): {{ product.sellPriceUSD }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-divider class="my-3"></v-divider>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>اسم التاجر: {{ product.supplier }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>تاريخ الشراء: {{ product.purchaseDate }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>

        <!-- زر الرجوع -->
        <v-btn color="primary" class="mt-4" @click="goBack">
          ⬅️ رجوع إلى قائمة المنتجات
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      productId: null,
      product: {
        name: "",
        category: "",
        buyPriceSYP: "",
        buyPriceUSD: "",
        sellPriceSYP: "",
        sellPriceUSD: "",
        supplier: "",
        purchaseDate: "",
        image: ""
      }
    };
  },
  created() {
    this.productId = this.$route.params.id;
    this.loadProductDetails(this.productId);
  },
  methods: {
    loadProductDetails(id) {
      // لاحقًا: يمكن جلب البيانات من API حقيقي
      // هنا مثال ثابت:
      this.product = {
        name: "هاتف ذكي",
        category: "إلكترونيات",
        buyPriceSYP: "2,500,000",
        buyPriceUSD: "200",
        sellPriceSYP: "3,000,000",
        sellPriceUSD: "240",
        supplier: "تاجر التقنيات الحديثة",
        purchaseDate: "2025-04-22",
        image: "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.bencomobile.com%2Fin%2Fproduct%2Fdefault.html&psig=AOvVaw0EeDvHOV7wU9g5_QgUwlJr&ust=1746633468274000&source=images&cd=vfe&opi=89978449&ved=0CBQQjRxqFwoTCKjW992bj40DFQAAAAAdAAAAABAE"
      };
    },
    goBack() {
      this.$router.push('/products');
    }
  }
};
</script>

<style scoped>
.h-100 {
  height: 100vh;
}
</style>
