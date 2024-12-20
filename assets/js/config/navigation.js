import {LayoutDashboard, Users, Settings, UserCircle, Code, MessageSquare, PenTool} from 'lucide-react';

export const navigation = [
    {
        name: 'Dashboard',
        icon: LayoutDashboard,
        path: '/',
        admin: false
    },
    {
        name: 'Playground',
        icon: Code,
        path: '/playground',
        admin: false
    },
    {
        name: 'AI-gent',
        icon: MessageSquare,
        path: '/chatbot',
        admin: false
    },
    {
        name: 'Canvas',
        icon: PenTool,
        path: '/canvas',
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