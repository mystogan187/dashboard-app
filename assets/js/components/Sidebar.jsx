import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { navigation } from '../config/navigation';

const Sidebar = ({ isOpen }) => {
    const location = useLocation();
    const { user } = useAuth();
    const isAdmin = user?.roles?.includes('ROLE_ADMIN');

    // Separar la navegación en principal y footer
    const mainNavigation = navigation.filter(item => !['Perfil', 'Configuración'].includes(item.name));
    const footerNavigation = navigation.filter(item => ['Perfil', 'Configuración'].includes(item.name));

    const NavLink = ({ item }) => {
        const isActive = location.pathname === item.path;
        return (
            <Link
                to={item.path}
                className={`
                    flex items-center px-2 py-3 mb-1 rounded-lg
                    ${isActive ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'}
                    ${!isOpen && 'justify-center'}
                `}
            >
                <item.icon size={20} />
                {isOpen && (
                    <span className="ml-3 whitespace-nowrap">{item.name}</span>
                )}
            </Link>
        );
    };

    return (
        <aside
            className={`
                ${isOpen ? 'w-64' : 'w-20'} 
                flex-none
                bg-white 
                flex 
                flex-col 
                overflow-hidden
                transition-all
                duration-300
            `}
        >
            {/* Header */}
            <div className="p-4">
                <h1 className={`font-bold text-xl truncate ${!isOpen && 'hidden'}`}>SphereForge</h1>
            </div>

            {/* Navigation container */}
            <div className="flex flex-col flex-1 overflow-hidden">
                {/* Main navigation */}
                <nav className="flex-1 px-2 overflow-y-auto overflow-x-hidden">
                    {mainNavigation.map((item) => {
                        if (item.admin && !isAdmin) return null;
                        return <NavLink key={item.name} item={item} />;
                    })}
                </nav>

                {/* Footer navigation */}
                <nav className="flex-none px-2 pb-6">
                    <div className="border-t pt-4">
                        {footerNavigation.map((item) => {
                            if (item.admin && !isAdmin) return null;
                            return <NavLink key={item.name} item={item} />;
                        })}
                    </div>
                </nav>
            </div>
        </aside>
    );
};

export default Sidebar;