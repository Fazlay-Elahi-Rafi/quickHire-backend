import { useState, useEffect, useCallback } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import JobModal from "./modal/JobModal";
import ViewModal from "./modal/ViewModal";
import DeleteModal from "./modal/DeleteModal";

/* ── Toggle Switch ───────────────────────────────────────────── */
function ToggleSwitch({ jobId, active, onChange }) {
    const [status, setStatus] = useState(active);
    const [loading, setLoading] = useState(false);

    const toggle = (e) => {
        e.stopPropagation();
        if (loading) return;
        setLoading(true);
        router.patch(`/admin/jobs/${jobId}/toggle-status`);
        
        setStatus((prev) => !prev);
    };

    return (
        <label
            className="flex items-center gap-2 cursor-pointer"
            onClick={toggle}
        >
            <div
                className={`relative w-10 h-5 rounded-full transition-colors duration-200 ${status ? "bg-indigo-500" : "bg-slate-300"} ${loading ? "opacity-60" : ""}`}
            >
                <div
                    className={`absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 ${status ? "translate-x-5" : "translate-x-0"}`}
                />
            </div>
            <span
                className={`text-xs font-semibold ${status ? "text-indigo-600" : "text-slate-400"}`}
            >
                {status ? "Active" : "Inactive"}
            </span>
        </label>
    );
}

/* ── Main Dashboard ──────────────────────────────────────────── */
export default function Dashboard({
    jobs = [],
    totalJobs = 0,
    totalApplications = 0,
    totalCompanies = 0,
}) {
    const [search, setSearch] = useState("");
    const [filterType, setFilterType] = useState("");
    const [modalOpen, setModalOpen] = useState(false);
    const [editingJob, setEditingJob] = useState(null);
    const [viewingJob, setViewingJob] = useState(null);
    const [deletingId, setDeletingId] = useState(null);

    const filtered = jobs.data.filter((job) => {
        const matchSearch =
            !search ||
            JSON.stringify(job).toLowerCase().includes(search.toLowerCase());
        const matchType = !filterType || job.job_type === filterType;
        return matchSearch && matchType;
    });

    const handleView = async (jobId) => {
        const res = await fetch(`/admin/jobs/${jobId}`);
        const data = await res.json();
        setViewingJob(data);
    };

    const handleEdit = async (jobId) => {
        const res = await fetch(`/admin/jobs/${jobId}/edit`);
        const data = await res.json();
        setEditingJob(data);
        setModalOpen(true);
    };

    const handleDelete = async () => {
        if (!deletingId) return;
        const res = await fetch(`/admin/jobs/${deletingId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") ?? "",
                "Content-Type": "application/json",
            },
        });
        const data = await res.json();
        if (data.success) router.reload();
        setDeletingId(null);
    };

    const openAdd = () => {
        setEditingJob(null);
        setModalOpen(true);
    };
    // console.log(jobs)
    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Job Management Dashboard
                    </h2>
                    <button
                        onClick={openAdd}
                        className="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded-lg shadow transition-colors duration-200"
                    >
                        <svg
                            className="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                        Add New Job
                    </button>
                </div>
            }
        >
            <Head title="Job Management Dashboard" />

            <div className="py-10">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    {/* Stats */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {[
                            { label: "Total Jobs", value: totalJobs },
                            { label: "Applications", value: totalApplications },
                            { label: "Companies", value: totalCompanies },
                        ].map((stat) => (
                            <div
                                key={stat.label}
                                className="bg-white rounded-xl shadow-sm border border-slate-100 p-5"
                            >
                                <p className="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                                    {stat.label}
                                </p>
                                <p className="text-3xl font-bold text-slate-800">
                                    {stat.value}
                                </p>
                            </div>
                        ))}
                    </div>

                    {/* Jobs Table */}
                    <div className="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        {/* Toolbar */}
                        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
                            <div className="flex gap-2">
                                <input
                                    type="text"
                                    placeholder="Search jobs…"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    className="w-56 px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                />
                                <select
                                    value={filterType}
                                    onChange={(e) =>
                                        setFilterType(e.target.value)
                                    }
                                    className="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                >
                                    <option value="">All Types</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Internship">
                                        Internship
                                    </option>
                                </select>
                            </div>
                            <p className="text-xs text-slate-400">
                                Showing{" "}
                                <span className="font-semibold text-slate-600">
                                    {filtered.length}
                                </span>{" "}
                                entries
                            </p>
                        </div>

                        {/* Table */}
                        <div className="overflow-x-auto">
                            <table className="min-w-full">
                                <thead className="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        {[
                                            "ID",
                                            "Title",
                                            "Company",
                                            "Location",
                                            "Type",
                                            "Workplace",
                                            "Salary",
                                            "Status",
                                            "Actions",
                                        ].map((h) => (
                                            <th
                                                key={h}
                                                className="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                            >
                                                {h}
                                            </th>
                                        ))}
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-50">
                                    {filtered.length === 0 ? (
                                        <tr>
                                            <td
                                                colSpan="9"
                                                className="text-center py-12 text-slate-400"
                                            >
                                                <svg
                                                    className="w-10 h-10 mx-auto mb-3 text-slate-300"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                    />
                                                </svg>
                                                No jobs found. Click{" "}
                                                <strong>Add New Job</strong> to
                                                create one.
                                            </td>
                                        </tr>
                                    ) : (
                                        filtered.map((job) => (
                                            <tr
                                                key={job.id}
                                                className="hover:bg-slate-50/60 transition-colors"
                                            >
                                                <td className="px-4 py-3 text-slate-400 font-mono text-xs">
                                                    #{job.id}
                                                </td>
                                                <td className="px-4 py-3">
                                                    <p className="font-semibold text-slate-800">
                                                        {job.title}
                                                    </p>
                                                    {job.sub_title && (
                                                        <p className="text-xs text-slate-400 mt-0.5">
                                                            {job.sub_title}
                                                        </p>
                                                    )}
                                                </td>
                                                <td className="px-4 py-3 font-medium text-slate-700">
                                                    {job.company}
                                                </td>
                                                <td className="px-4 py-3 text-slate-500">
                                                    {job.location}
                                                </td>
                                                <td className="px-4 py-3">
                                                    <span className="px-2 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">
                                                        {job.job_type}
                                                    </span>
                                                </td>
                                                <td className="px-4 py-3">
                                                    <span className="px-2 py-1 rounded-full text-xs font-semibold bg-violet-50 text-violet-600">
                                                        {job.workplace}
                                                    </span>
                                                </td>
                                                <td className="px-4 py-3 text-slate-600">
                                                    {job.salary_range || "—"}
                                                </td>
                                                <td className="px-4 py-3">
                                                    <ToggleSwitch
                                                        jobId={job.id}
                                                        active={!!job.status}
                                                    />
                                                </td>
                                                <td className="px-4 py-3">
                                                    <div className="flex items-center gap-1">
                                                        {/* View */}
                                                        <button
                                                            onClick={() =>
                                                                handleView(
                                                                    job.id,
                                                                )
                                                            }
                                                            className="p-1.5 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                                            title="View"
                                                        >
                                                            <svg
                                                                className="w-4 h-4"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                                />
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                                />
                                                            </svg>
                                                        </button>
                                                        {/* Edit */}
                                                        <button
                                                            onClick={() =>
                                                                handleEdit(
                                                                    job.id,
                                                                )
                                                            }
                                                            className="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                                            title="Edit"
                                                        >
                                                            <svg
                                                                className="w-4 h-4"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                                />
                                                            </svg>
                                                        </button>
                                                        {/* Delete */}
                                                        <button
                                                            onClick={() =>
                                                                setDeletingId(
                                                                    job.id,
                                                                )
                                                            }
                                                            className="p-1.5 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                            title="Delete"
                                                        >
                                                            <svg
                                                                className="w-4 h-4"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {/* Modals */}
            <JobModal
                isOpen={modalOpen}
                onClose={() => {
                    setModalOpen(false);
                    setEditingJob(null);
                }}
                editingJob={editingJob}
                onSuccess={() => router.reload()}
            />
            <ViewModal job={viewingJob} onClose={() => setViewingJob(null)} />
            <DeleteModal
                jobId={deletingId}
                onClose={() => setDeletingId(null)}
                onConfirm={handleDelete}
            />
        </AuthenticatedLayout>
    );
}
