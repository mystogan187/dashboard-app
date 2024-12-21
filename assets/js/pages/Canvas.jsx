import React, { useState } from 'react';
import {
    Layers,
    Move,
    Square,
    Circle,
    Type,
    Image as ImageIcon,
    MousePointer2,
    Minus,
    Plus,
    PenTool
} from 'lucide-react';

const ToolButton = ({ children, active, onClick }) => (
    <button
        onClick={onClick}
        className={`w-full p-2 rounded-lg flex items-center justify-center ${
            active ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'
        }`}
    >
        {children}
    </button>
);

const Canvas = () => {
    const [zoom, setZoom] = useState(100);
    const [activeTool, setActiveTool] = useState('select');
    const [selectedElement, setSelectedElement] = useState(null);

    const tools = [
        { id: 'select', icon: MousePointer2, label: 'Seleccionar' },
        { id: 'move', icon: Move, label: 'Mover' },
        { id: 'rectangle', icon: Square, label: 'Rectángulo' },
        { id: 'circle', icon: Circle, label: 'Círculo' },
        { id: 'text', icon: Type, label: 'Texto' },
        { id: 'image', icon: ImageIcon, label: 'Imagen' }
    ];

    return (
        <div className="h-full flex flex-col pb-6">
            <div className="flex-none">
                <div className="flex items-center justify-between mb-4">
                    <div className="flex items-center gap-2">
                        <PenTool className="w-6 h-6 text-blue-500" />
                        <h1 className="text-2xl font-bold text-gray-800">Canvas</h1>
                    </div>
                    <div className="flex items-center gap-4">
                        <div className="flex items-center gap-2">
                            <button className="p-1 hover:bg-gray-100 rounded">
                                <Minus className="w-4 h-4" />
                            </button>
                            <span className="w-12 text-center">{zoom}%</span>
                            <button className="p-1 hover:bg-gray-100 rounded">
                                <Plus className="w-4 h-4" />
                            </button>
                        </div>
                        <button className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Exportar
                        </button>
                    </div>
                </div>
            </div>

            <div className="flex-1 min-h-0 flex bg-white rounded-lg shadow-lg">
                {/* Barra de herramientas izquierda */}
                <div className="flex-none w-12 border-r border-gray-200">
                    <div className="p-1.5 flex flex-col gap-1">
                        {tools.map((tool) => (
                            <ToolButton
                                key={tool.id}
                                active={activeTool === tool.id}
                                onClick={() => setActiveTool(tool.id)}
                            >
                                <tool.icon className="w-4 h-4" />
                            </ToolButton>
                        ))}
                    </div>
                </div>

                {/* Área del canvas */}
                <div className="flex-1 bg-gray-50">
                    <div className="h-full w-full">
                        <div
                            className="h-full w-full bg-white"
                            style={{
                                backgroundImage: 'url("data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Crect width=\'20\' height=\'20\' fill=\'%23f9fafb\'/%3E%3Crect width=\'1\' height=\'1\' fill=\'%23e5e7eb\'/%3E%3C/svg%3E")',
                                backgroundSize: '20px 20px'
                            }}
                        >
                            {/* Elementos del canvas */}
                        </div>
                    </div>
                </div>

                {/* Panel derecho */}
                <div className="flex-none w-48 border-l border-gray-200">
                    <div className="p-3">
                        <div className="flex items-center gap-2 mb-3">
                            <Layers className="w-4 h-4" />
                            <span className="text-sm font-medium">Capas</span>
                        </div>
                        <div className="space-y-1">
                            <div className="p-2 text-sm hover:bg-gray-100 rounded cursor-pointer">
                                Rectángulo 1
                            </div>
                            <div className="p-2 text-sm hover:bg-gray-100 rounded cursor-pointer">
                                Texto 1
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Canvas;