import React, { useState, useRef, useEffect } from 'react';
import { Settings, Moon, Bell } from 'lucide-react';
import { useUserPreferencesContext } from '../contexts/UserPreferencesContext';
import { useAuth } from '../contexts/AuthContext';

const SettingsMenu = () => {
    const [isOpen, setIsOpen] = useState(false);
    const menuRef = useRef(null);
    const {
        notifications,
        handleNotificationsChange,
        darkMode,
        handleDarkModeChange
    } = useUserPreferencesContext();

    const { user } = useAuth();

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (menuRef.current && !menuRef.current.contains(event.target)) {
                setIsOpen(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const toggleMenu = () => setIsOpen(!isOpen);

    const Toggle = ({ checked, onChange }) => (
        <button
            onClick={() => onChange(!checked)}
            className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ${
                checked ? 'bg-blue-600' : 'bg-gray-200'
            }`}
        >
            <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${
                    checked ? 'translate-x-6' : 'translate-x-1'
                }`}
            />
        </button>
    );

    return (
        <div className="relative" ref={menuRef}>
            <button
                onClick={toggleMenu}
                className="p-2 rounded-lg hover:bg-gray-100 focus:outline-none"
            >
                <Settings size={20} />
            </button>

            {isOpen && (
                <div className="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                    <div className="px-4 py-2 border-b border-gray-200">
                        <h3 className="text-sm font-semibold text-gray-700">Configuración Rápida</h3>
                    </div>

                    <div className="p-4 space-y-4">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-2">
                                <Moon size={18} className="text-gray-600" />
                                <span className="text-sm text-gray-700">Modo Oscuro</span>
                            </div>
                            <Toggle checked={darkMode} onChange={handleDarkModeChange} />
                        </div>

                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-2">
                                <Bell size={18} className="text-gray-600" />
                                <span className="text-sm text-gray-700">Notificaciones Email</span>
                            </div>
                            <Toggle checked={notifications} onChange={handleNotificationsChange} />
                        </div>
                    </div>

                    {user?.roles?.includes('ROLE_ADMIN') && (
                        <div className="px-4 py-2 border-t border-gray-200">
                            <button
                                onClick={() => window.location.href = '/settings'}
                                className="text-sm text-blue-600 hover:text-blue-700"
                            >
                                Ver todas las configuraciones
                            </button>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
};

export default SettingsMenu;