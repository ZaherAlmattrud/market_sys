import { createMemoryHistory, createRouter } from 'vue-router'


import ProductPage from "@/pages/ProductsPage.vue"
import CustomerPage from "@/pages/CustomerPage.vue"
import HealthyPage from "@/pages/HealthyPage.vue"
import AccountsPage from "@/pages/AccountsPage.vue"
import UsersPage from "@/pages/UsersPage.vue"
import InvoicesPage from "@/pages/InvoicesPage.vue"
import AreasPage from "@/pages/AreasPage.vue"
import CategoryPage from "@/pages/CategoryPage.vue"
import AccountDetailsPage from "@/pages/AccountDetailsPage.vue"
import LoginPage from "@/pages/LoginPage.vue"
import ArrestedsPage from "@/pages/ArrestedsPage.vue"
import PaidsPage from "@/pages/PaidsPage.vue"
import InvoiceImgPage from "@/pages/InvoiceImgPage.vue"
import ProductImgPage from "@/pages/ProductImgPage.vue"

const routes = [
  { path: '/', component: ProductPage, name: 'products', meta: { requiresAuth: false }, },
  { path: '/customers', component: CustomerPage, meta: { requiresAuth: false }, },
  { path: '/healthy', component: HealthyPage, meta: { requiresAuth: false }, },
  { path: '/accounts', component: AccountsPage, meta: { requiresAuth: false }, },
  { path: '/users', component: UsersPage, meta: { requiresAuth: false }, },
  { path: '/invoices', component: InvoicesPage, meta: { requiresAuth: false }, },
  { path: '/areas', component: AreasPage, meta: { requiresAuth: false }, },
  { path: '/category', component: CategoryPage, meta: { requiresAuth: false }, },
  { path: '/accountDetails:accountId', component: AccountDetailsPage, props: true, name: 'accountDetails', meta: { requiresAuth: false }, },
  { path: '/login', component: LoginPage, name: 'login' },
  { path: '/arresteds', component: ArrestedsPage, meta: { requiresAuth: false }, },
  { path: '/paids', component: PaidsPage, meta: { requiresAuth: false }, },

   { path: '/invoiceImg:id', component: InvoiceImgPage, name: 'invoiceImg', props: true, meta: { requiresAuth: false },},
    {path: '/productImg:url', component: ProductImgPage, name: 'ProductImage', props: true, meta: { requiresAuth: false },}




]

const router = createRouter({
  history: createMemoryHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('user');

  if (to.matched.some(record => record.meta.requiresAuth) && !loggedIn) {
    next('/login');
  } else {
    next();
  }
});

export default router