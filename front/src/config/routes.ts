import Login from "../views/Login/Login.vue";
import Dashboard from "../views/Dashboard/Dashboard.vue";
import Sellers from "../views/Users/Sellers.vue";
import Sales from "../views/Sales/Sales.vue";

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/vendas',
        name: 'Todas as vendas',
        component: Sales,
        meta: { requiresAuth: true }
    },
    {
        path: '/sellers',
        name: 'Vendedores',
        component: Sellers,
        meta: { requiresAuth: true }
    },
    {
        path: '/',
        redirect: '/dashboard'
    }
]

export default routes