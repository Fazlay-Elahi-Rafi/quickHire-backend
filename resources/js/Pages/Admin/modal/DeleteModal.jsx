import React from "react";

const DeleteModal = ({ jobId, onClose, onConfirm }) => {
    if (!jobId) return null;
    return (
        <div
            className="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto z-50"
            onClick={(e) => e.target === e.currentTarget && onClose()}
        >
            <div className="relative top-40 mx-auto p-6 w-96 shadow-2xl rounded-2xl bg-white text-center">
                <div className="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                    <svg
                        className="h-7 w-7 text-red-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                </div>
                <h3 className="text-base font-bold text-slate-800 mb-2">
                    Delete Job
                </h3>
                <p className="text-sm text-slate-500 mb-5">
                    Are you sure you want to delete this job? This action cannot
                    be undone.
                </p>
                <div className="flex justify-center gap-3">
                    <button
                        onClick={onClose}
                        className="px-5 py-2 rounded-lg text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        onClick={onConfirm}
                        className="px-5 py-2 rounded-lg text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition-colors"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    );
};

export default DeleteModal;
