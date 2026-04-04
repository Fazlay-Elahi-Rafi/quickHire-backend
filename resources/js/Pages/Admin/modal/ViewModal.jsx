import { useState, useEffect, useCallback } from "react";

const ViewModal = ({ job, onClose }) => {
    if (!job) return null;

    function formatValue(value) {
        if (!value) return <span className="text-slate-400">—</span>;
        if (Array.isArray(value)) {
            if (!value.length) return <span className="text-slate-400">—</span>;
            return (
                <ul className="list-disc list-inside space-y-1">
                    {value.map((v, i) => (
                        <li key={i}>{v}</li>
                    ))}
                </ul>
            );
        }
        if (typeof value === "object") {
            if (!Object.keys(value).length)
                return <span className="text-slate-400">—</span>;
            return (
                <div className="space-y-2">
                    {Object.entries(value).map(([k, v]) => (
                        <div key={k}>
                            <span className="font-semibold text-indigo-600">
                                {k}:
                            </span>
                            {Array.isArray(v) ? (
                                <ul className="list-none mt-1 flex flex-wrap gap-2">
                                    {v.map((item, i) => (
                                        <li
                                            key={i}
                                            className="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium"
                                        >
                                            {item}
                                        </li>
                                    ))}
                                </ul>
                            ) : (
                                <span className="text-slate-700 ml-1">{v}</span>
                            )}
                        </div>
                    ))}
                </div>
            );
        }
        return <span>{String(value)}</span>;
    }

    const Section = ({ title, children }) => (
        <div>
            <h4 className="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">
                {title}
            </h4>
            {children}
        </div>
    );

    const Field = ({ label, value }) => (
        <div>
            <p className="font-semibold text-slate-500 mb-0.5">{label}</p>
            <p className="text-slate-800">{value || "—"}</p>
        </div>
    );

    return (
        <div
            className="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto z-50"
            onClick={(e) => e.target === e.currentTarget && onClose()}
        >
            <div className="relative top-10 mx-auto mb-10 w-11/12 max-w-3xl shadow-2xl rounded-2xl bg-white overflow-hidden">
                <div className="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-white">
                    <div className="flex items-center gap-3">
                        <div className="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg
                                className="w-5 h-5 text-indigo-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <h3 className="text-base font-bold text-slate-800">
                            Job Details
                        </h3>
                    </div>
                    <button
                        onClick={onClose}
                        className="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                    >
                        <svg
                            className="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div className="p-6 space-y-6 text-sm">
                    <Section title="Basic Information">
                        <div className="grid grid-cols-2 gap-4">
                            <Field label="Title" value={job.title} />
                            <Field label="Sub Title" value={job.sub_title} />
                            <Field label="Tag" value={job.tag} />
                            <div>
                                <p className="font-semibold text-slate-500 mb-0.5">
                                    Status
                                </p>
                                <span
                                    className={`px-2 py-1 rounded-full text-xs font-semibold ${job.status ? "bg-green-100 text-green-600" : "bg-red-100 text-red-500"}`}
                                >
                                    {job.status ? "Active" : "Inactive"}
                                </span>
                            </div>
                        </div>
                    </Section>

                    <Section title="Job Details">
                        <div className="grid grid-cols-2 gap-4">
                            <Field label="Company" value={job.company} />
                            <Field label="Location" value={job.location} />
                            <Field label="Job Type" value={job.job_type} />
                            <Field label="Workplace" value={job.workplace} />
                            <Field
                                label="Salary Range"
                                value={job.salary_range}
                            />
                            <Field
                                label="Experience Required"
                                value={job.experience_required}
                            />
                            <Field
                                label="Available Positions"
                                value={job.available}
                            />
                            <Field
                                label="Min Passing Score"
                                value={job.min_passing_score}
                            />
                        </div>
                    </Section>

                    <Section title="Description & Role">
                        <div className="space-y-3">
                            <div>
                                <p className="font-semibold text-slate-500 mb-1">
                                    Description
                                </p>
                                <div className="text-slate-700 whitespace-pre-wrap">
                                    {job.description}
                                </div>
                            </div>
                            <div>
                                <p className="font-semibold text-slate-500 mb-1">
                                    About Role
                                </p>
                                <div className="text-slate-700 whitespace-pre-wrap">
                                    {job.about_role}
                                </div>
                            </div>
                        </div>
                    </Section>

                    <Section title="Key Responsibilities">
                        <div className="text-slate-700">
                            {formatValue(job.key_responsibilities)}
                        </div>
                    </Section>

                    <Section title="Categories">
                        <div className="flex flex-wrap gap-2">
                            {job.categories &&
                            Array.isArray(job.categories) &&
                            job.categories.length ? (
                                job.categories.map((cat, i) => (
                                    <span
                                        key={i}
                                        className="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium"
                                    >
                                        {cat}
                                    </span>
                                ))
                            ) : (
                                <span className="text-slate-500">—</span>
                            )}
                        </div>
                    </Section>

                    <Section title="Required Technical Skills">
                        <div className="text-slate-700">
                            {formatValue(job.required_technical_skills)}
                        </div>
                    </Section>
                    <Section title="Database Knowledge">
                        <div className="text-slate-700">
                            {formatValue(job.database_knowledge)}
                        </div>
                    </Section>
                    <Section title="Development Tools">
                        <div className="text-slate-700">
                            {formatValue(job.development_tools)}
                        </div>
                    </Section>
                    <Section title="Bonus Skills">
                        <div className="text-slate-700">
                            {formatValue(job.bonus_skills)}
                        </div>
                    </Section>
                    <Section title="Qualifications">
                        <div className="text-slate-700">
                            {formatValue(job.qualifications)}
                        </div>
                    </Section>
                    <Section title="Benefits">
                        <div className="text-slate-700">
                            {formatValue(job.benefits)}
                        </div>
                    </Section>
                </div>

                <div className="flex justify-end px-6 py-4 border-t border-slate-100 bg-slate-50">
                    <button
                        onClick={onClose}
                        className="px-5 py-2 rounded-lg text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 transition-colors shadow-sm"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ViewModal;
