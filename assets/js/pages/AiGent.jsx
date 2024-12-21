import React from 'react';
import { MessageSquare } from 'lucide-react';
import ChatBot from '../components/ChatBot';

const AiGent = () => {
    return (
        <div className="h-full flex flex-col pb-6">
            <div className="flex-none">
                <div className="flex items-center gap-2 mb-4">
                    <MessageSquare className="w-6 h-6 text-blue-500" />
                    <h1 className="text-2xl font-bold text-gray-800">AI-gent Chat</h1>
                </div>
            </div>
            <div className="flex-1 min-h-0">
                <div className="h-full bg-white rounded-lg shadow-lg">
                    <ChatBot />
                </div>
            </div>
        </div>
    );
};

export default AiGent;