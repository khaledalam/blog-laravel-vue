import { createRouter, createWebHistory } from 'vue-router';

import HomePage from './pages/HomePage.vue';
import LoginPage from './pages/LoginPage.vue';
import RegisterPage from './pages/RegisterPage.vue';
import CreateBlogPage from './pages/CreateBlogPage.vue';
import BlogsPage from './pages/BlogsPage.vue';
import EditBlogPage from "./pages/EditBlogPage.vue";
import BlogPage from './pages/BlogPage.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: HomePage },
        { path: '/home', component: HomePage },
        { path: '/login', component: LoginPage },
        { path: '/register', component: RegisterPage },

        { path: '/blogs', component: BlogsPage },
        { path: '/blogs/create', component: CreateBlogPage },
        { path: '/blogs/:id', component: BlogPage, props: true },
        { path: '/blogs/:id/edit', component: EditBlogPage },
    ],
});

router.beforeEach((to, from, next) => {
    if (to.path === '/login' || to.path === '/register' || localStorage.getItem('auth_token')) {
        next();
    } else {
        next('/login');
    }
});

export default router;

