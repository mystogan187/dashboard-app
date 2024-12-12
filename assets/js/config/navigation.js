import { LayoutDashboard, Users, Settings, UserCircle } from 'lucide-react';

export const navigation = [
    {
        name: 'Dashboard',
        icon: LayoutDashboard,
        path: '/',
        admin: false
    },
    {
        name: 'Usuarios',
        icon: Users,
        path: '/users',
        admin: true
    },
    {
        name: 'Perfil',
        icon: UserCircle,
        path: '/profile',
        admin: false
    },
    {
        name: 'Configuración',
        icon: Settings,
        path: '/settings',
        admin: true
    }
];