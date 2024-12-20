import React from 'react';
import { Outlet, useNavigate } from 'react-router-dom';
import { Menu, ChevronDown, Search, Bell, Settings, LogOut } from 'lucide-react';
import { useAuth } from '../contexts/AuthContext';
import Sidebar from '../components/Sidebar';
import SettingsMenu from '../components/SettingsMenu';

const DashboardLayout = () => {
    const [sidebarOpen, setSidebarOpen] = React.useState(true);
    const { user, logout } = useAuth();
    const navigate = useNavigate();

    const handleLogout = () => {
        logout();
        navigate('/login');
    };

    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white shadow-sm">
                <div className="flex items-center justify-between px-4 py-3">
                    <div className="flex items-center gap-3">
                        <button
                            onClick={() => setSidebarOpen(!sidebarOpen)}
                            className="p-2 rounded-lg hover:bg-gray-100"
                        >
                            <Menu size={20} />
                        </button>
                        <div className="relative">
                            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" size={16} />
                            <input
                                type="text"
                                placeholder="Buscar..."
                                className="pl-10 pr-4 py-2 bg-gray-100 rounded-lg w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                    </div>
                    <div className="flex items-center gap-4">
                        <button className="p-2 rounded-lg hover:bg-gray-100">
                            <Bell size={20} />
                        </button>
                        <SettingsMenu />
                        <div className="flex items-center gap-2">
                            {user?.profilePhoto ? (
                                <img
                                    src={`/uploads/profile/${user.profilePhoto}`}
                                    alt="Foto de perfil"
                                    className="w-8 h-8 rounded-full object-cover"
                                />
                            ) : (
                                <div className="w-8 h-8 rounded-full bg-blue-500" />
                            )}
                            <span className="font-medium">{user?.name || 'Usuario'}</span>
                            <button
                                onClick={handleLogout}
                                className="p-2 rounded-lg hover:bg-gray-100"
                            >
                                <LogOut size={20} />
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <div className="flex">
                <Sidebar isOpen={sidebarOpen} />
                <main className="flex-1 p-6">
                    <Outlet />
                </main>
            </div>
        </div>
    );
};

export default DashboardLayout;