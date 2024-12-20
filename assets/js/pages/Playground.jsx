import React, { useState, useEffect, useRef } from 'react';

const CodeEditor = ({ code, onChange, language }) => {
    return (
        <textarea
            value={code}
            onChange={(e) => onChange(e.target.value)}
            className="w-full h-full font-mono text-sm bg-gray-900 text-gray-100 resize-none focus:outline-none p-4"
            placeholder={`Escribe tu código ${language} aquí...`}
        />
    );
};

const OutputPanel = ({ output }) => {
    return (
        <div className="w-full h-full font-mono text-sm bg-gray-800 text-gray-100 overflow-auto p-4">
            {output || 'El resultado se mostrará aquí...'}
        </div>
    );
};

const PlaygroundHeader = ({ language, setLanguage, onRun, onSave }) => {
    return (
        <div className="flex items-center justify-between">
            <div className="flex items-center gap-4">
                <select
                    value={language}
                    onChange={(e) => setLanguage(e.target.value)}
                    className="px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="javascript">JavaScript</option>
                    <option value="php">PHP</option>
                </select>

                <button
                    onClick={onRun}
                    className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                        <polygon points="5 3 19 12 5 21 5 3"/>
                    </svg>
                    Ejecutar
                </button>

                <button
                    onClick={onSave}
                    className="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Guardar
                </button>
            </div>
        </div>
    );
};

const Playground = () => {
    const [language, setLanguage] = useState('javascript');
    const [code, setCode] = useState('');
    const [output, setOutput] = useState('');
    const [height, setHeight] = useState('700px');
    const containerRef = useRef(null);

    useEffect(() => {
        const updateHeight = () => {
            if (containerRef.current) {
                const viewHeight = window.innerHeight;
                const containerTop = containerRef.current.getBoundingClientRect().top;
                const newHeight = Math.floor((viewHeight - containerTop) * 0.9); // 90% del espacio disponible
                setHeight(`${newHeight}px`);
            }
        };

        updateHeight();
        window.addEventListener('resize', updateHeight);

        return () => window.removeEventListener('resize', updateHeight);
    }, []);

    const handleRunCode = async () => {
        try {
            if (language === 'javascript') {
                const result = await fetch('/api/playground/execute', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    },
                    body: JSON.stringify({
                        code,
                        language
                    })
                });

                const data = await result.json();
                setOutput(data.output);
            } else {
                setOutput('Ejecución de PHP pendiente de implementar en el backend');
            }
        } catch (error) {
            setOutput(`Error: ${error.message}`);
        }
    };

    const handleSaveCode = async () => {
        try {
            const result = await fetch('/api/playground/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    code,
                    language
                })
            });
        } catch (error) {
            console.error('Error al guardar:', error);
        }
    };

    return (
        <div ref={containerRef} className="flex flex-col gap-4">
            <PlaygroundHeader
                language={language}
                setLanguage={setLanguage}
                onRun={handleRunCode}
                onSave={handleSaveCode}
            />

            <div
                className="grid grid-cols-2 gap-4"
                style={{ height: height }}
            >
                <div className="overflow-hidden">
                    <CodeEditor
                        code={code}
                        onChange={setCode}
                        language={language}
                    />
                </div>
                <div className="overflow-hidden">
                    <OutputPanel output={output} />
                </div>
            </div>
        </div>
    );
};

export default Playground;