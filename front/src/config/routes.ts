import Login from "../views/Login/Login.vue";
import Dashboard from "../views/Dashboard/Dashboard.vue";

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/',
        redirect: '/dashboard'
    }
]

export default routes