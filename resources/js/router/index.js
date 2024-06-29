import { createMemoryHistory, createRouter } from 'vue-router'

import HomePage from "@/pages/HomePage.vue"
 

const routes = [
  { path: '/', component: HomePage },
 
]

const router = createRouter({
  history: createMemoryHistory(),
  routes,
})

export default router