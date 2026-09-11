import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/customers',
    name: 'customers',
    // route level code-splitting
    component: () => import(/* webpackChunkName: "about" */ '../views/customers.vue')
  },
  {
    path: '/employees',
    name: 'employees',
    // route level code-splitting
    component: () => import(/* webpackChunkName: "about" */ '../views/employees.vue')
  },
   {
    path: '/Add_Customers',
    name: 'Add_Customers',
    // route level code-splitting
    component: () => import(/* webpackChunkName: "about" */ '../views/Add_Customers.vue')
  },
  {
    path: '/Add_Employees',
    name: 'Add_Employees',
    // route level code-splitting
    component: () => import(/* webpackChunkName: "about" */ '../views/Add_Employees.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
