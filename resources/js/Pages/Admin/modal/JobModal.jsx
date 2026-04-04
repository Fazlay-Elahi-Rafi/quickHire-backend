import { useState, useEffect } from "react";
import { router } from "@inertiajs/react";

function uid() {
    return "_" + Date.now() + Math.random().toString(36).slice(2);
}

function SkillGroup({ groupKey = "", values = [""], onRemove, onChange }) {
    const [key, setKey] = useState(groupKey);
    const [skillValues, setSkillValues] = useState(
        values.length ? values : [""],
    );

    useEffect(() => {
        onChange({ key, values: skillValues });
    }, [key, skillValues]);

    const addValue = () => setSkillValues((v) => [...v, ""]);
    const removeValue = (i) =>
        setSkillValues((v) => v.filter((_, idx) => idx !== i));
    const updateValue = (i, val) =>
        setSkillValues((v) => v.map((x, idx) => (idx === i ? val : x)));

    return (
        <div className="border border-slate-200 rounded-lg p-3 bg-slate-50">
            <div className="flex items-center gap-2 mb-2">
                <input
                    type="text"
                    placeholder="Group key (e.g. frontend)"
                    value={key}
                    onChange={(e) => setKey(e.target.value)}
                    className="flex-1 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                />
                <button
                    type="button"
                    onClick={onRemove}
                    className="dyn-minus p-1.5 rounded-md text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                >
                    <svg
                        className="w-3.5 h-3.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M20 12H4"
                        />
                    </svg>
                </button>
            </div>
            <div className="space-y-1.5 pl-2 mb-2">
                {skillValues.map((v, i) => (
                    <div key={i} className="flex items-center gap-2">
                        <input
                            type="text"
                            placeholder="Value (e.g. React)"
                            value={v}
                            onChange={(e) => updateValue(i, e.target.value)}
                            className="flex-1 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        />
                        <button
                            type="button"
                            onClick={() => removeValue(i)}
                            className="p-1.5 rounded-md text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                        >
                            <svg
                                className="w-3.5 h-3.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M20 12H4"
                                />
                            </svg>
                        </button>
                    </div>
                ))}
            </div>
            <div className="pl-2">
                <button
                    type="button"
                    onClick={addValue}
                    className="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-md transition-colors"
                >
                    <svg
                        className="w-3.5 h-3.5"
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
                    Add value
                </button>
            </div>
        </div>
    );
}

const JobModal = ({ isOpen, onClose, editingJob, onSuccess }) => {
    const isEdit = !!editingJob;

    const [form, setForm] = useState({
        title: "",
        sub_title: "",
        tag: "",
        company: "",
        location: "",
        job_type: "Full-time",
        workplace: "Remote",
        salary_range: "",
        experience_required: "",
        available: "",
        status: "1",
        description: "",
        about_role: "",
        key_responsibilities: "",
        benefits: "",
        categories: "",
        development_tools: "",
        bonus_skills: "",
    });
    const [skillGroups, setSkillGroups] = useState({
        required: [],
        database: [],
    });
    const [qualifications, setQualifications] = useState([]);
    const [submitting, setSubmitting] = useState(false);

    useEffect(() => {
        if (editingJob) {
            setForm({
                title: editingJob.title ?? "",
                sub_title: editingJob.sub_title ?? "",
                tag: editingJob.tag ?? "",
                company: editingJob.company ?? "",
                location: editingJob.location ?? "",
                job_type: editingJob.job_type ?? "Full-time",
                workplace: editingJob.workplace ?? "Remote",
                salary_range: editingJob.salary_range ?? "",
                experience_required: editingJob.experience_required ?? "",
                available: editingJob.available ?? "",
                status: editingJob.status ? "1" : "0",
                description: editingJob.description ?? "",
                about_role: editingJob.about_role ?? "",
                key_responsibilities: Array.isArray(
                    editingJob.key_responsibilities,
                )
                    ? editingJob.key_responsibilities.join("\n")
                    : "",
                benefits: Array.isArray(editingJob.benefits)
                    ? editingJob.benefits.join("\n")
                    : "",
                categories: Array.isArray(editingJob.categories)
                    ? editingJob.categories.join("\n")
                    : "",
                development_tools: Array.isArray(editingJob.development_tools)
                    ? editingJob.development_tools.join("\n")
                    : "",
                bonus_skills: Array.isArray(editingJob.bonus_skills)
                    ? editingJob.bonus_skills.join("\n")
                    : "",
            });
            const required =
                editingJob.required_technical_skills &&
                typeof editingJob.required_technical_skills === "object"
                    ? Object.entries(editingJob.required_technical_skills).map(
                          ([k, v]) => ({
                              id: uid(),
                              key: k,
                              values: Array.isArray(v) ? v : [String(v)],
                          }),
                      )
                    : [];
            const database =
                editingJob.database_knowledge &&
                typeof editingJob.database_knowledge === "object"
                    ? Object.entries(editingJob.database_knowledge).map(
                          ([k, v]) => ({
                              id: uid(),
                              key: k,
                              values: Array.isArray(v) ? v : [String(v)],
                          }),
                      )
                    : [];
            setSkillGroups({ required, database });
            const quals =
                editingJob.qualifications &&
                typeof editingJob.qualifications === "object"
                    ? Object.entries(editingJob.qualifications).map(
                          ([k, v]) => ({ id: uid(), key: k, value: v }),
                      )
                    : [];
            setQualifications(quals);
        } else {
            setForm({
                title: "",
                sub_title: "",
                tag: "",
                company: "",
                location: "",
                job_type: "Full-time",
                workplace: "Remote",
                salary_range: "",
                experience_required: "",
                available: "",
                status: "1",
                description: "",
                about_role: "",
                key_responsibilities: "",
                benefits: "",
                categories: "",
                development_tools: "",
                bonus_skills: "",
            });
            setSkillGroups({ required: [], database: [] });
            setQualifications([]);
        }
    }, [editingJob, isOpen]);

    const addSkillGroup = (type) => {
        setSkillGroups((prev) => ({
            ...prev,
            [type]: [...prev[type], { id: uid(), key: "", values: [""] }],
        }));
    };

    const removeSkillGroup = (type, id) => {
        setSkillGroups((prev) => ({
            ...prev,
            [type]: prev[type].filter((g) => g.id !== id),
        }));
    };

    const updateSkillGroup = (type, id, data) => {
        setSkillGroups((prev) => ({
            ...prev,
            [type]: prev[type].map((g) =>
                g.id === id ? { ...g, ...data } : g,
            ),
        }));
    };

    const addQualification = () =>
        setQualifications((prev) => [
            ...prev,
            { id: uid(), key: "", value: "" },
        ]);
    const removeQualification = (id) =>
        setQualifications((prev) => prev.filter((q) => q.id !== id));
    const updateQualification = (id, field, val) =>
        setQualifications((prev) =>
            prev.map((q) => (q.id === id ? { ...q, [field]: val } : q)),
        );

    const collectSkillGroups = (groups) => {
        const result = {};
        groups.forEach((g) => {
            if (g.key.trim()) result[g.key.trim()] = g.values.filter(Boolean);
        });
        return result;
    };

    const collectQuals = () => {
        const result = {};
        qualifications.forEach((q) => {
            if (q.key.trim()) result[q.key.trim()] = q.value;
        });
        return result;
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setSubmitting(true);

        const payload = { ...form };

        [
            "key_responsibilities",
            "benefits",
            "categories",
            "development_tools",
            "bonus_skills",
        ].forEach((field) => {
            if (payload[field]) {
                payload[field] = payload[field]
                    .split("\n")
                    .map((i) => i.trim())
                    .filter(Boolean);
            }
        });

        payload.required_technical_skills = collectSkillGroups(
            skillGroups.required,
        );
        payload.database_knowledge = collectSkillGroups(skillGroups.database);
        payload.qualifications = collectQuals();

        if (isEdit) payload.job_id = editingJob.id;

        router.post("/admin/jobs", payload, {
            onSuccess: () => {
                onSuccess();
                onClose();
            },
            onFinish: () => setSubmitting(false),
        });
    };

    if (!isOpen) return null;

    const inputCls =
        "w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400";
    const labelCls = "block text-sm font-semibold text-slate-700 mb-1.5";

    return (
        <div
            className="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto z-50"
            onClick={(e) => e.target === e.currentTarget && onClose()}
        >
            <div className="relative top-10 mx-auto mb-10 p-6 w-11/12 max-w-4xl shadow-2xl rounded-2xl bg-white">
                <div className="flex justify-between items-center mb-6">
                    <h3 className="text-lg font-bold text-slate-800">
                        {isEdit ? "Edit Job" : "Add New Job"}
                    </h3>
                    <button
                        onClick={onClose}
                        className="text-slate-400 hover:text-slate-600 transition-colors"
                    >
                        <svg
                            className="w-6 h-6"
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

                <form onSubmit={handleSubmit}>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div className="col-span-2">
                            <h4 className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                                Basic Information
                            </h4>
                        </div>

                        <div>
                            <label className={labelCls}>Title *</label>
                            <input
                                type="text"
                                required
                                value={form.title}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        title: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div>
                            <label className={labelCls}>Sub Title</label>
                            <input
                                type="text"
                                value={form.sub_title}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        sub_title: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div>
                            <label className={labelCls}>Tag *</label>
                            <input
                                type="text"
                                required
                                value={form.tag}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        tag: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div>
                            <label className={labelCls}>Status</label>
                            <select
                                value={form.status}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        status: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            >
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div className="col-span-2">
                            <label className={labelCls}>
                                Categories (one per line) *
                            </label>
                            <textarea
                                rows="2"
                                value={form.categories}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        categories: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>

                        {/* Required Technical Skills */}
                        <div className="col-span-2">
                            <div className="flex items-center justify-between mb-2">
                                <label className="text-sm font-semibold text-slate-700">
                                    Required Technical Skills
                                </label>
                                <button
                                    type="button"
                                    onClick={() => addSkillGroup("required")}
                                    className="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-md transition-colors"
                                >
                                    <svg
                                        className="w-3.5 h-3.5"
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
                                    Add Group
                                </button>
                            </div>
                            <div className="space-y-2">
                                {skillGroups.required.map((g) => (
                                    <SkillGroup
                                        key={g.id}
                                        groupKey={g.key}
                                        values={g.values}
                                        onRemove={() =>
                                            removeSkillGroup("required", g.id)
                                        }
                                        onChange={(data) =>
                                            updateSkillGroup(
                                                "required",
                                                g.id,
                                                data,
                                            )
                                        }
                                    />
                                ))}
                            </div>
                        </div>

                        {/* Database Knowledge */}
                        <div className="col-span-2">
                            <div className="flex items-center justify-between mb-2">
                                <label className="text-sm font-semibold text-slate-700">
                                    Database Knowledge
                                </label>
                                <button
                                    type="button"
                                    onClick={() => addSkillGroup("database")}
                                    className="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-md transition-colors"
                                >
                                    <svg
                                        className="w-3.5 h-3.5"
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
                                    Add Group
                                </button>
                            </div>
                            <div className="space-y-2">
                                {skillGroups.database.map((g) => (
                                    <SkillGroup
                                        key={g.id}
                                        groupKey={g.key}
                                        values={g.values}
                                        onRemove={() =>
                                            removeSkillGroup("database", g.id)
                                        }
                                        onChange={(data) =>
                                            updateSkillGroup(
                                                "database",
                                                g.id,
                                                data,
                                            )
                                        }
                                    />
                                ))}
                            </div>
                        </div>

                        <div className="col-span-2">
                            <label className={labelCls}>
                                Development Tools (one per line)
                            </label>
                            <textarea
                                rows="2"
                                value={form.development_tools}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        development_tools: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div className="col-span-2">
                            <label className={labelCls}>
                                Bonus Skills (one per line)
                            </label>
                            <textarea
                                rows="2"
                                value={form.bonus_skills}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        bonus_skills: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>

                        {/* Qualifications */}
                        <div className="col-span-2">
                            <div className="flex items-center justify-between mb-2">
                                <label className="text-sm font-semibold text-slate-700">
                                    Qualifications
                                </label>
                                <button
                                    type="button"
                                    onClick={addQualification}
                                    className="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-md transition-colors"
                                >
                                    <svg
                                        className="w-3.5 h-3.5"
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
                                    Add Field
                                </button>
                            </div>
                            <div className="space-y-2">
                                {qualifications.map((q) => (
                                    <div
                                        key={q.id}
                                        className="flex items-center gap-2"
                                    >
                                        <input
                                            type="text"
                                            placeholder="Key (e.g. education)"
                                            value={q.key}
                                            onChange={(e) =>
                                                updateQualification(
                                                    q.id,
                                                    "key",
                                                    e.target.value,
                                                )
                                            }
                                            className="w-1/3 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                        />
                                        <input
                                            type="text"
                                            placeholder="Value (e.g. Bachelor degree)"
                                            value={q.value}
                                            onChange={(e) =>
                                                updateQualification(
                                                    q.id,
                                                    "value",
                                                    e.target.value,
                                                )
                                            }
                                            className="flex-1 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                        />
                                        <button
                                            type="button"
                                            onClick={() =>
                                                removeQualification(q.id)
                                            }
                                            className="p-1.5 rounded-md text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                        >
                                            <svg
                                                className="w-3.5 h-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M20 12H4"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="col-span-2">
                            <hr className="border-slate-100" />
                        </div>
                        <div className="col-span-2">
                            <h4 className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                                Job Details
                            </h4>
                        </div>

                        <div>
                            <label className={labelCls}>Company *</label>
                            <input
                                type="text"
                                required
                                value={form.company}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        company: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div>
                            <label className={labelCls}>Location *</label>
                            <input
                                type="text"
                                required
                                value={form.location}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        location: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div>
                            <label className={labelCls}>Job Type *</label>
                            <select
                                required
                                value={form.job_type}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        job_type: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            >
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div>
                            <label className={labelCls}>Workplace *</label>
                            <select
                                required
                                value={form.workplace}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        workplace: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            >
                                <option value="Remote">Remote</option>
                                <option value="On-site">On-site</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div>
                            <label className={labelCls}>Salary Range</label>
                            <input
                                type="text"
                                value={form.salary_range}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        salary_range: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div>
                            <label className={labelCls}>
                                Experience Required
                            </label>
                            <input
                                type="text"
                                value={form.experience_required}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        experience_required: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div>
                            <label className={labelCls}>
                                Available Positions
                            </label>
                            <input
                                type="number"
                                value={form.available}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        available: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>

                        <div className="col-span-2">
                            <label className={labelCls}>Description *</label>
                            <textarea
                                rows="3"
                                required
                                value={form.description}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        description: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div className="col-span-2">
                            <label className={labelCls}>About Role *</label>
                            <textarea
                                rows="3"
                                required
                                value={form.about_role}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        about_role: e.target.value,
                                    }))
                                }
                                className={inputCls}
                            />
                        </div>
                        <div className="col-span-2">
                            <label className={labelCls}>
                                Key Responsibilities (one per line)
                            </label>
                            <textarea
                                rows="4"
                                value={form.key_responsibilities}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        key_responsibilities: e.target.value,
                                    }))
                                }
                                className={inputCls}
                                placeholder={
                                    "Build dynamic, responsive user interfaces...\nLeverage AI tools like GitHub Copilot..."
                                }
                            />
                        </div>
                        <div className="col-span-2">
                            <label className={labelCls}>
                                Benefits (one per line)
                            </label>
                            <textarea
                                rows="3"
                                value={form.benefits}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        benefits: e.target.value,
                                    }))
                                }
                                className={inputCls}
                                placeholder={
                                    "Yearly salary review\nFlexible bonus after probation"
                                }
                            />
                        </div>
                    </div>

                    <div className="flex justify-end gap-3 mt-7 pt-5 border-t border-slate-100">
                        <button
                            type="button"
                            onClick={onClose}
                            className="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            disabled={submitting}
                            className="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow disabled:opacity-60"
                        >
                            {submitting ? "Saving…" : "Save Job"}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default JobModal;
