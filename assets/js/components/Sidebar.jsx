import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { navigation } from '../config/navigation';

const Sidebar = ({ isOpen }) => {
    const location = useLocation();
    const { user } = useAuth();
    const isAdmin = user?.roles?.includes('ROLE_ADMIN');

    return (
        <aside className={`${isOpen ? 'w-64' : 'w-20'} transition-all duration-300 bg-white min-h-screen shadow-sm`}>
            <div className="p-4">
                <h1 className={`font-bold text-xl ${!isOpen && 'hidden'}`}>SphereForge</h1>
            </div>

            <nav className="mt-5 px-2">
                {navigation.map((item) => {
                    if (item.admin && !isAdmin) return null;

                    const isActive = location.pathname === item.path;
                    return (
                        <Link
                            key={item.name}
                            to={item.path}
                            className={`
                                flex items-center px-2 py-3 mb-1 rounded-lg
                                ${isActive ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'}
                                ${!isOpen && 'justify-center'}
                            `}
                        >
                            <item.icon size={20} />
                            {isOpen && (
                                <span className="ml-3">{item.name}</span>
                            )}
                        </Link>
                    );
                })}
            </nav>
        </aside>
    );
};

export default Sidebar;