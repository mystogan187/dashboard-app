import React from 'react';

const Dashboard = () => {
    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div className="bg-white rounded-lg shadow-sm p-6">
                <h2 className="font-semibold text-lg mb-4">Resumen</h2>
                <div className="space-y-4">
                    <div>
                        <span className="text-gray-600">Total Usuarios</span>
                        <p className="text-2xl font-bold">1,234</p>
                    </div>
                    <div>
                        <span className="text-gray-600">Activos Hoy</span>
                        <p className="text-2xl font-bold">892</p>
                    </div>
                </div>
            </div>

            <div className="bg-white rounded-lg shadow-sm p-6">
                <h2 className="font-semibold text-lg mb-4">Actividad Reciente</h2>
                <div className="space-y-3">
                    {[1, 2, 3].map((i) => (
                        <div key={i} className="flex items-center gap-3">
                            <div className="w-2 h-2 rounded-full bg-blue-500" />
                            <span className="text-gray-600">Actividad {i}</span>
                        </div>
                    ))}
                </div>
            </div>

            <div className="bg-white rounded-lg shadow-sm p-6">
                <h2 className="font-semibold text-lg mb-4">Próximas Tareas</h2>
                <div className="space-y-3">
                    {[1, 2, 3].map((i) => (
                        <div key={i} className="p-3 bg-gray-50 rounded-lg">
                            <h3 className="font-medium">Tarea {i}</h3>
                            <p className="text-sm text-gray-600">Descripción de la tarea {i}</p>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default Dashboard;