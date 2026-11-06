import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'home',
    redirect: '/show_product'
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
  },
  {
    path: '/add_contract',
    name: 'Add_Contract',
    // route level code-splitting
    component: () => import(/* webpackChunkName: "about" */ '../views/add_contract.vue')
  },
  {
    path: '/contract',
    name: 'contract',
    component: () => import(/* webpackChunkName: "contract" */ '../views/contract.vue')
  },
  {
    path: '/customer_crud',
    name: 'customer_crud',
    component: () => import(/* webpackChunkName: "customer_crud" */ '../views/customer_crud.vue'),
     meta: { requiresAuth: true }
  },
  {
    path: '/employee_crud',
    name: 'employee_crud',
    component: () => import(/* webpackChunkName: "employee_crud" */ '../views/employee_crud.vue'),
     meta: { requiresAuth: true }
  },
   {
    path: '/product',
    name: 'product',
    component: () => import(/* webpackChunkName: "product" */ '../views/product.vue'),
     meta: { requiresAuth: true }
  },
   {
    path: '/show_product',
    name: 'show_product',
    component: () => import(/* webpackChunkName: "product" */ '../views/show_product.vue')
  },
  {
    path: '/assets',
    name: 'assets',
    component: () => import(/* webpackChunkName: "assets" */ '../views/assets.vue'),
    meta: { requiresAuth: true }
  },
    {
    path: '/login',
    name: 'login',
    component: () => import(/* webpackChunkName: "assets" */ '../views/login.vue')
  },
    {
    path: '/register',
    name: 'register',
    component: () => import(/* webpackChunkName: "assets" */ '../views/Add_Employees.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

// ตรวจสอบสิทธิ์ก่อนเปลี่ยนหน้า
router.beforeEach((to, from, next) => {

  // ตรวจสอบสถานะ Login จาก localStorage
  const isLoggedIn = localStorage.getItem('adminLogin') === 'true'

  // ถ้าหน้านั้นต้อง Login แต่ยังไม่ได้ Login
  if (to.meta.requiresAuth && !isLoggedIn) {
    next('/login')
  }

  // ถ้า Login แล้ว ไม่ต้องกลับไปหน้า Login
  else if (to.path === '/login' && isLoggedIn) {
    next('/show_product')
  }

  // กรณีอื่น ๆ อนุญาตให้เข้าหน้าได้
  else {
    next()
  }
})

export default router
