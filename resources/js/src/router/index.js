import AppLayout from '@/layout/AppLayout.vue';
import { createRouter, createWebHashHistory, createWebHistory } from 'vue-router';
import { requireAuth } from '@/middleware/auth';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: AppLayout,
            beforeEnter: requireAuth,
            children: [
                {
                    path: '/',
                    name: 'dashboard',
                    component: () => import('@/views/Dashboard.vue'),
                },
                {
                    path: '/categories',
                    name: 'categories',
                    component: () => import('@/views/Categories/Index.vue'),
                },
                {
                    path: '/categories/create',
                    name: 'categories.create',
                    component: () => import('@/views/Categories/Create.vue'),
                },
                {
                    path: '/categories/edit/:id',
                    name: 'categories.edit',
                    props: true,
                    component: () => import('@/views/Categories/Edit.vue'),
                },
                {
                    path: '/products',
                    name: 'products',
                    component: () => import('@/views/Products/Index.vue'),
                },
                {
                    path: '/products/create',
                    name: 'products.create',
                    component: () => import('@/views/Products/Create.vue'),
                },
                {
                    path: '/products/edit/:id',
                    name: 'products.edit',
                    props: true,
                    component: () => import('@/views/Products/Edit.vue'),
                },

            ]
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('@/views/Login.vue'),
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('@/views/Register.vue'),
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'NotFound',
            component: () => import('@/views/NotFound.vue'),
        },]
});


export default router;
