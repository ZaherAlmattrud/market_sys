import { createMemoryHistory, createRouter } from 'vue-router'

 
import ProductPage from "@/pages/ProductsPage.vue"
import CustomerPage from "@/pages/CustomerPage.vue"
import HealthyPage from "@/pages/HealthyPage.vue"
import AccountsPage from "@/pages/AccountsPage.vue"
import SupplersPage from "@/pages/SupplersPage.vue"
import InvoicesPage from "@/pages/InvoicesPage.vue"
import AreasPage from "@/pages/AreasPage.vue"
import CategoryPage from "@/pages/CategoryPage.vue"
import AccountDetailsPage  from "@/pages/AccountDetailsPage.vue"
import LoginPage from "@/pages/LoginPage.vue"
 

const routes = [
  { path: '/', component: ProductPage , name : 'products' , meta: { requiresAuth: true },},
  { path: '/customers', component: CustomerPage , meta: { requiresAuth: true }, },
  { path: '/healthy', component: HealthyPage , meta: { requiresAuth: true }, },
  { path: '/accounts', component: AccountsPage , meta: { requiresAuth: true }, },
  { path: '/supplers', component: SupplersPage , meta: { requiresAuth: true }, },
  { path: '/invoices', component: InvoicesPage , meta: { requiresAuth: true }, },
  { path: '/areas', component: AreasPage , meta: { requiresAuth: true }, },
  { path: '/category', component: CategoryPage , meta: { requiresAuth: true }, },
  { path: '/accountDetails:accountId', component: AccountDetailsPage ,  props: true,  name: 'accountDetails' , meta: { requiresAuth: true },},
  { path: '/login', component: LoginPage , name : 'login' },
  
 
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