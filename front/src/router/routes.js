
const routes = [
  { path: '/login', component: () => import('pages/Login.vue') },
  { path: '/register', component: () => import('pages/Register.vue') },
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
      children: [
        { path: '',
          component: () => import('pages/Index.vue'),
            beforeEnter: (to, from, next) => {
              const token = localStorage.getItem('token');
              if (token !== null) {
                next();
              } else {
                next('/login')
              }
            }
            },
        { path: 'orders',
          component: () => import('pages/Orders.vue'),
          beforeEnter: (to, from, next) => {
            const token = localStorage.getItem('token');
            if (token !== null) {
              next();
            } else {
              next('/login')
            }
          }
        }
      ]
},

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
