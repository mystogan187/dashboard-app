import React from 'react';
import { Bell, Moon, Lock, Key, Shield } from 'lucide-react';
import ChangePasswordModal from '../components/ChangePasswordModal';
import { useUserPreferencesContext } from '../contexts/UserPreferencesContext';

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

const Settings = () => {
    const {
        notifications,
        handleNotificationsChange,
        darkMode,
        handleDarkModeChange,
        showAlert,
        alertMessage,
        isPasswordModalOpen,
        setIsPasswordModalOpen,
        loading,
        error,
        handlePasswordChange,
        handle2FASetup
    } = useUserPreferencesContext();

    if (loading) return <div className="max-w-2xl mx-auto p-4">Cargando preferencias...</div>;
    if (error) return <div className="max-w-2xl mx-auto p-4 text-red-500">Error: {error}</div>;

    return (
        <div className="max-w-2xl mx-auto p-4">
            {showAlert && (
                <div className="mb-4 p-4 bg-blue-100 text-blue-700 rounded-lg">
                    {alertMessage}
                </div>
            )}

            <div className="bg-white rounded-lg shadow">
                <div className="px-6 py-4 border-b border-gray-200">
                    <h2 className="text-2xl font-bold">Configuración</h2>
                </div>

                <div className="p-6 space-y-8">
                    <div className="space-y-6">
                        <h3 className="text-lg font-semibold flex items-center gap-2">
                            <Bell className="w-5 h-5" />
                            Preferencias Generales
                        </h3>

                        <div className="space-y-4">
                            <div className="flex items-center justify-between">
                                <div className="space-y-1">
                                    <span className="font-medium">Notificaciones por Email</span>
                                    <p className="text-sm text-gray-500">
                                        Recibe actualizaciones importantes en tu correo
                                    </p>
                                </div>
                                <Toggle
                                    checked={notifications}
                                    onChange={handleNotificationsChange}
                                />
                            </div>

                            <div className="flex items-center justify-between">
                                <div className="space-y-1">
                                    <span className="font-medium">Modo Oscuro</span>
                                    <p className="text-sm text-gray-500">
                                        Cambia entre tema claro y oscuro
                                    </p>
                                </div>
                                <Toggle
                                    checked={darkMode}
                                    onChange={handleDarkModeChange}
                                />
                            </div>
                        </div>
                    </div>

                    <div className="space-y-6">
                        <h3 className="text-lg font-semibold flex items-center gap-2">
                            <Shield className="w-5 h-5" />
                            Seguridad
                        </h3>

                        <div className="space-y-4">
                            <div className="space-y-1">
                                <button
                                    onClick={() => setIsPasswordModalOpen(true)}
                                    className="w-full px-4 py-2 text-left flex items-center gap-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    <Key className="w-4 h-4" />
                                    Cambiar Contraseña
                                </button>
                                <p className="text-sm text-gray-500">
                                    Actualiza tu contraseña regularmente para mayor seguridad
                                </p>
                            </div>

                            <div className="space-y-1">
                                <button
                                    onClick={handle2FASetup}
                                    className="w-full px-4 py-2 text-left flex items-center gap-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    <Lock className="w-4 h-4" />
                                    Configurar Autenticación de Dos Factores
                                </button>
                                <p className="text-sm text-gray-500">
                                    Añade una capa extra de seguridad a tu cuenta
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ChangePasswordModal
                isOpen={isPasswordModalOpen}
                onClose={() => setIsPasswordModalOpen(false)}
                onSave={handlePasswordChange}
            />
        </div>
    );
};

export default Settings;