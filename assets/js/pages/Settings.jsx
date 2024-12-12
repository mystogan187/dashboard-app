import React from 'react';

const Settings = () => {
    return (
        <div>
            <h2 className="text-2xl font-bold mb-6">Configuración</h2>
            <div className="bg-white rounded-lg shadow-sm p-6">
                <div className="mb-8">
                    <h3 className="text-lg font-semibold mb-4">Preferencias Generales</h3>
                    <div className="space-y-4">
                        <div className="flex items-center justify-between">
                            <span>Notificaciones por Email</span>
                            <button className="bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span className="translate-x-5 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div className="flex items-center justify-between">
                            <span>Modo Oscuro</span>
                            <button className="bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span className="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 className="text-lg font-semibold mb-4">Seguridad</h3>
                    <div className="space-y-4">
                        <button className="text-blue-500 hover:text-blue-700">
                            Cambiar Contraseña
                        </button>
                        <button className="text-blue-500 hover:text-blue-700 block">
                            Configurar Autenticación de Dos Factores
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Settings;