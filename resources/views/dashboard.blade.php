<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Job Management Dashboard') }}
            </h2>
            <button onclick="openAddJobModal()"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded-lg shadow transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Job
            </button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Stats ──────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Jobs</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalJobs ?? 0 }}</p>
                </div>
                <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Applications</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalApplications ?? 0 }}</p>
                </div>
                <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Companies</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalCompanies ?? 0 }}</p>
                </div>
            </div>

            {{-- ── Jobs Table ──────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">

                {{-- toolbar --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="flex gap-2">
                        <input type="text" id="searchInput" placeholder="Search jobs…" class="search-input w-56">
                        <select id="filterType" class="filter-select">
                            <option value="">All Types</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>
                    <p class="text-xs text-slate-400">
                        Showing <span id="showingCount" class="font-semibold text-slate-600">{{ count($jobs) }}</span>
                        entries
                    </p>
                </div>

                {{-- table --}}
                <div class="overflow-x-auto">
                    <table class="jobs-table min-w-full">
                        <thead>
                            <tr>
                                <th class="text-left">ID</th>
                                <th class="text-left">Title</th>
                                <th class="text-left">Company</th>
                                <th class="text-left">Location</th>
                                <th class="text-left">Type</th>
                                <th class="text-left">Workplace</th>
                                <th class="text-left">Salary</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobsTableBody">
                            @forelse($jobs ?? [] as $job)
                                <tr>
                                    <td class="text-slate-400 font-mono text-xs">#{{ $job->id }}</td>
                                    <td>
                                        <p class="font-semibold text-slate-800">{{ $job->title }}</p>
                                        @if ($job->sub_title)
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $job->sub_title }}</p>
                                        @endif
                                    </td>
                                    <td class="font-medium text-slate-700">{{ $job->company }}</td>
                                    <td class="text-slate-500">{{ $job->location }}</td>
                                    <td>
                                        <span class="badge badge-blue">{{ $job->job_type }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-violet">{{ $job->workplace }}</span>
                                    </td>
                                    <td class="text-slate-600">{{ $job->salary_range ?? '—' }}</td>
                                    <td>
                                        {{-- Toggle switch --}}
                                        <label class="toggle-wrap" onclick="toggleStatus({{ $job->id }})"
                                            title="Toggle status">
                                            <div class="toggle-track {{ $job->status ? 'on' : 'off' }}"
                                                id="toggle-track-{{ $job->id }}">
                                                <div class="toggle-thumb"></div>
                                            </div>
                                            <span class="toggle-label {{ $job->status ? 'on' : 'off' }}"
                                                id="toggle-label-{{ $job->id }}">
                                                {{ $job->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <button onclick="viewJob({{ $job->id }})" class="action-btn view"
                                                title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button onclick="editJob({{ $job->id }})" class="action-btn edit"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteJob({{ $job->id }})" class="action-btn delete"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-12 text-slate-400">
                                        <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        No jobs found. Click <strong>Add New Job</strong> to create one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- @if ($jobs->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $jobs->links() }}
                    </div>
                @endif --}}
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════
         ADD / EDIT JOB MODAL
    ════════════════════════════════════════════════════════ --}}
    <div id="jobModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto hidden z-50">
        <div class="relative top-10 mx-auto mb-10 p-6 w-11/12 max-w-4xl shadow-2xl rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Add New Job</h3>
                <button onclick="closeJobModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="jobForm" method="POST" action="{{ route('admin.jobs.store') }}">
                @csrf
                <input type="hidden" id="jobId" name="job_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="col-span-2">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Basic Information
                        </h4>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Title *</label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sub Title</label>
                        <input type="text" name="sub_title" id="sub_title"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tag *</label>
                        <input type="text" name="tag" id="tag" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Categories (one per line)
                            *</label>
                        <textarea id="categories" rows="2"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
                    </div>

                    {{-- Required Technical Skills --}}
                    <div class="col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-semibold text-slate-700">Required Technical Skills</label>
                            <button type="button" onclick="addSkillGroup('required_technical_skills_container')"
                                class="dyn-add-btn">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Group
                            </button>
                        </div>
                        <div id="required_technical_skills_container" class="space-y-2"></div>
                    </div>

                    {{-- Database Knowledge --}}
                    <div class="col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-semibold text-slate-700">Database Knowledge</label>
                            <button type="button" onclick="addSkillGroup('database_knowledge_container')"
                                class="dyn-add-btn">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Group
                            </button>
                        </div>
                        <div id="database_knowledge_container" class="space-y-2"></div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Development Tools (one per
                            line)</label>
                        <textarea id="development_tools" rows="2"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bonus Skills (one per
                            line)</label>
                        <textarea id="bonus_skills" rows="2"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
                    </div>

                    {{-- Qualifications --}}
                    <div class="col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-semibold text-slate-700">Qualifications</label>
                            <button type="button" onclick="addQualificationRow()" class="dyn-add-btn">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Field
                            </button>
                        </div>
                        <div id="qualifications_container" class="space-y-2"></div>
                    </div>

                    <div class="col-span-2">
                        <hr class="border-slate-100">
                    </div>
                    <div class="col-span-2">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Job Details</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Company *</label>
                        <input type="text" name="company" id="company" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Location *</label>
                        <input type="text" name="location" id="location" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Job Type *</label>
                        <select name="job_type" id="job_type" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Workplace *</label>
                        <select name="workplace" id="workplace" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option value="Remote">Remote</option>
                            <option value="On-site">On-site</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Salary Range</label>
                        <input type="text" name="salary_range" id="salary_range"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Experience Required</label>
                        <input type="text" name="experience_required" id="experience_required"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Available Positions</label>
                        <input type="number" name="available" id="available"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description *</label>
                        <textarea name="description" id="description" rows="3" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">About Role *</label>
                        <textarea name="about_role" id="about_role" rows="3" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Key Responsibilities (one per
                            line)</label>
                        <textarea name="key_responsibilities" id="key_responsibilities" rows="4"
                            placeholder="Build dynamic, responsive user interfaces...&#10;Leverage AI tools like GitHub Copilot..."
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Benefits (one per
                            line)</label>
                        <textarea name="benefits" id="benefits" rows="3"
                            placeholder="Yearly salary review&#10;Flexible bonus after probation"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-7 pt-5 border-t border-slate-100">
                    <button type="button" onclick="closeJobModal()"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow">
                        Save Job
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════ VIEW MODAL ════════════════════ --}}

    <div id="viewJobModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto hidden z-50">
        <div class="relative top-10 mx-auto mb-10 w-11/12 max-w-3xl shadow-2xl rounded-2xl bg-white overflow-hidden">

            {{-- Header --}}
            <div
                class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Job Details</h3>
                    </div>
                </div>
                <button onclick="closeViewModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div id="jobDetails" class="p-6 space-y-6 text-sm"></div>

            {{-- Footer --}}
            <div class="flex justify-end px-6 py-4 border-t border-slate-100 bg-slate-50">
                <button onclick="closeViewModal()"
                    class="px-5 py-2 rounded-lg text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 transition-colors shadow-sm">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════════════ DELETE MODAL ════════════════════ --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto hidden z-50">
        <div class="relative top-40 mx-auto p-6 w-96 shadow-2xl rounded-2xl bg-white text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-2">Delete Job</h3>
            <p class="text-sm text-slate-500 mb-5">Are you sure you want to delete this job? This action cannot be
                undone.</p>
            <div class="flex justify-center gap-3">
                <button onclick="closeDeleteModal()"
                    class="px-5 py-2 rounded-lg text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmDelete"
                    class="px-5 py-2 rounded-lg text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentJobId = null;

            /* ── Helpers ─────────────────────────────────────────────────── */
            function escHtml(str) {
                return String(str ?? '')
                    .replace(/&/g, '&amp;').replace(/"/g, '&quot;')
                    .replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }

            function uid() {
                return '_' + Date.now() + Math.random().toString(36).slice(2);
            }

            function removeElement(id) {
                document.getElementById(id)?.remove();
            }

            function clearContainer(id) {
                document.getElementById(id).innerHTML = '';
            }

            function mkMinusBtn(fn) {
                return `<button type="button" onclick="${fn}" class="dyn-minus" title="Remove">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg></button>`;
            }

            /* ── Skill Groups ────────────────────────────────────────────── */
            function addSkillGroup(containerId, key = '', values = ['']) {
                const container = document.getElementById(containerId);
                const gid = 'sg' + uid();
                const wrap = document.createElement('div');
                wrap.id = gid;
                wrap.className = 'border border-slate-200 rounded-lg p-3 bg-slate-50';
                wrap.innerHTML = `
                <div class="flex items-center gap-2 mb-2">
                    <input type="text" placeholder="Group key (e.g. frontend)" value="${escHtml(key)}"
                        class="skill-group-key flex-1 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    ${mkMinusBtn(`removeElement('${gid}')`)}
                </div>
                <div class="skill-values space-y-1.5 pl-2 mb-2"></div>
                <div class="pl-2">
                    <button type="button" onclick="addSkillValueTo('${gid}')" class="dyn-add-btn">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add value
                    </button>
                </div>`;
                container.appendChild(wrap);
                const vc = wrap.querySelector('.skill-values');
                values.forEach(v => appendSkillValueRow(vc, v));
            }

            function addSkillValueTo(gid) {
                const w = document.getElementById(gid);
                if (w) appendSkillValueRow(w.querySelector('.skill-values'), '');
            }

            function appendSkillValueRow(container, value = '') {
                const rid = 'sv' + uid();
                const row = document.createElement('div');
                row.id = rid;
                row.className = 'flex items-center gap-2';
                row.innerHTML = `
                <input type="text" placeholder="Value (e.g. React)" value="${escHtml(value)}"
                    class="skill-value flex-1 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                ${mkMinusBtn(`removeElement('${rid}')`)}`;
                container.appendChild(row);
            }

            function collectSkillGroups(containerId) {
                const result = {};
                document.querySelectorAll(`#${containerId} > div`).forEach(g => {
                    const key = g.querySelector('.skill-group-key')?.value.trim();
                    if (!key) return;
                    result[key] = [...g.querySelectorAll('.skill-value')]
                        .map(i => i.value.trim()).filter(Boolean);
                });
                return result;
            }

            /* ── Qualifications ──────────────────────────────────────────── */
            function addQualificationRow(key = '', value = '') {
                const container = document.getElementById('qualifications_container');
                const rid = 'qr' + uid();
                const row = document.createElement('div');
                row.id = rid;
                row.className = 'flex items-center gap-2';
                row.innerHTML = `
                <input type="text" placeholder="Key (e.g. education)" value="${escHtml(key)}"
                    class="qual-key w-1/3 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <input type="text" placeholder="Value (e.g. Bachelor degree)" value="${escHtml(value)}"
                    class="qual-value flex-1 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                ${mkMinusBtn(`removeElement('${rid}')`)}`;
                container.appendChild(row);
            }

            function collectQualifications() {
                const result = {};
                document.querySelectorAll('#qualifications_container > div').forEach(row => {
                    const k = row.querySelector('.qual-key')?.value.trim();
                    const v = row.querySelector('.qual-value')?.value.trim();
                    if (k) result[k] = v ?? '';
                });
                return result;
            }

            /* ── Toggle Status ───────────────────────────────────────────── */
            function toggleStatus(jobId) {
                fetch(`/admin/jobs/${jobId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) return;

                        const track = document.getElementById('toggle-track-' + jobId);
                        const label = document.getElementById('toggle-label-' + jobId);
                        const counter = document.getElementById('activeJobsCount');
                        const current = parseInt(counter.textContent) || 0;

                        if (data.status === 1) {
                            track.classList.remove('off');
                            track.classList.add('on');
                            label.classList.remove('off');
                            label.classList.add('on');
                            label.textContent = 'Active';
                            counter.textContent = current + 1;
                        } else {
                            track.classList.remove('on');
                            track.classList.add('off');
                            label.classList.remove('on');
                            label.classList.add('off');
                            label.textContent = 'Inactive';
                            counter.textContent = Math.max(0, current - 1);
                        }
                    });
            }

            /* ── Modals ──────────────────────────────────────────────────── */
            function openAddJobModal() {
                document.getElementById('modalTitle').textContent = 'Add New Job';
                document.getElementById('jobForm').reset();
                document.getElementById('jobId').value = '';
                clearContainer('required_technical_skills_container');
                clearContainer('database_knowledge_container');
                clearContainer('qualifications_container');
                document.getElementById('jobModal').classList.remove('hidden');
            }

            function closeJobModal() {
                document.getElementById('jobModal').classList.add('hidden');
            }

            function closeViewModal() {
                document.getElementById('viewJobModal').classList.add('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            /* ── View ────────────────────────────────────────────────────── */
            function viewJob(jobId) {
                fetch(`/admin/jobs/${jobId}`)
                    .then(r => r.json())
                    .then(job => {
                        // Helper function to format arrays or objects
                        function formatValue(value) {
                            if (!value) return '—';
                            if (Array.isArray(value)) {
                                if (value.length === 0) return '—';
                                return `<ul class="list-disc list-inside space-y-1">${value.map(v => `<li>${escHtml(v)}</li>`).join('')}</ul>`;
                            }
                            if (typeof value === 'object') {
                                if (Object.keys(value).length === 0) return '—';
                                return `<div class="space-y-2">${Object.entries(value).map(([k, v]) => `
                                <div>
                                    <span class="font-semibold text-indigo-600">${escHtml(k)}:</span>
                                    ${Array.isArray(v) ? 
                                        `<ul class="list-none list-inside mt-1 flex flex-wrap gap-2 justify-start ">${v.map(item => `<li class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">${escHtml(item)}</li>`).join('')}</ul>` : 
                                        `<span class="text-slate-700">${escHtml(v)}</span>`
                                    }
                                </div>
                            `).join('')}</div>`;
                            }
                            return escHtml(value);
                        }

                        document.getElementById('jobDetails').innerHTML = `
                <div class="space-y-6">
                    <!-- Basic Information Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Basic Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div><p class="font-semibold text-slate-500 mb-0.5">Title</p><p class="text-slate-800">${escHtml(job.title)}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Sub Title</p><p class="text-slate-800">${escHtml(job.sub_title) || '—'}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Tag</p><p class="text-slate-800">${escHtml(job.tag)}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Status</p><span class="${job.status ? 'bg-green-200 text-green-600' : 'badge-red'}">${job.status ? 'Active' : 'Inactive'}</span></div>
                        </div>
                    </div>

                    <!-- Company & Job Details Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Job Details</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div><p class="font-semibold text-slate-500 mb-0.5">Company</p><p class="text-slate-800">${escHtml(job.company)}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Location</p><p class="text-slate-800">${escHtml(job.location)}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Job Type</p><p class="text-slate-800">${escHtml(job.job_type)}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Workplace</p><p class="text-slate-800">${escHtml(job.workplace)}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Salary Range</p><p class="text-slate-800">${escHtml(job.salary_range) || '—'}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Experience Required</p><p class="text-slate-800">${escHtml(job.experience_required) || '—'}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Available Positions</p><p class="text-slate-800">${job.available || '—'}</p></div>
                            <div><p class="font-semibold text-slate-500 mb-0.5">Min Passing Score</p><p class="text-slate-800">${job.min_passing_score || '—'}</p></div>
                        </div>
                    </div>

                    <!-- Description & About Role Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Description & Role</h4>
                        <div class="space-y-3">
                            <div><p class="font-semibold text-slate-500 mb-1">Description</p><div class="text-slate-700 whitespace-pre-wrap">${escHtml(job.description)}</div></div>
                            <div><p class="font-semibold text-slate-500 mb-1">About Role</p><div class="text-slate-700 whitespace-pre-wrap">${escHtml(job.about_role)}</div></div>
                        </div>
                    </div>

                    <!-- Key Responsibilities Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Key Responsibilities</h4>
                        <div class="text-slate-700">${formatValue(job.key_responsibilities)}</div>
                    </div>

                    <!-- Categories Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Categories</h4>
                        <div class="flex flex-wrap gap-2">
                            ${job.categories && Array.isArray(job.categories) ? 
                                job.categories.map(cat => `<span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">${escHtml(cat)}</span>`).join('') : 
                                '<span class="text-slate-500">—</span>'
                            }
                        </div>
                    </div>

                    <!-- Required Technical Skills Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Required Technical Skills</h4>
                        <div class="text-slate-700">${formatValue(job.required_technical_skills)}</div>
                    </div>

                    <!-- Database Knowledge Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Database Knowledge</h4>
                        <div class="text-slate-700">${formatValue(job.database_knowledge)}</div>
                    </div>

                    <!-- Development Tools Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Development Tools</h4>
                        <div class="text-slate-700">${formatValue(job.development_tools)}</div>
                    </div>

                    <!-- Bonus Skills Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Bonus Skills</h4>
                        <div class="text-slate-700">${formatValue(job.bonus_skills)}</div>
                    </div>

                    <!-- Qualifications Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Qualifications</h4>
                        <div class="text-slate-700">${formatValue(job.qualifications)}</div>
                    </div>

                    <!-- Benefits Section -->
                    <div>
                        <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Benefits</h4>
                        <div class="text-slate-700">${formatValue(job.benefits)}</div>
                    </div>
                </div>
            `;
                        document.getElementById('viewJobModal').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching job details:', error);
                        alert('Failed to load job details. Please try again.');
                    });
            }

            /* ── Edit ────────────────────────────────────────────────────── */
            function editJob(jobId) {
                fetch(`/admin/jobs/${jobId}/edit`)
                    .then(r => r.json())
                    .then(job => {
                        document.getElementById('modalTitle').textContent = 'Edit Job';
                        document.getElementById('jobId').value = job.id;
                        document.getElementById('title').value = job.title ?? '';
                        document.getElementById('sub_title').value = job.sub_title ?? '';
                        document.getElementById('tag').value = job.tag ?? '';
                        document.getElementById('company').value = job.company ?? '';
                        document.getElementById('location').value = job.location ?? '';
                        document.getElementById('job_type').value = job.job_type ?? '';
                        document.getElementById('workplace').value = job.workplace ?? '';
                        document.getElementById('salary_range').value = job.salary_range ?? '';
                        document.getElementById('experience_required').value = job.experience_required ?? '';
                        document.getElementById('available').value = job.available ?? '';
                        document.getElementById('status').value = job.status ? '1' : '0';
                        document.getElementById('description').value = job.description ?? '';
                        document.getElementById('about_role').value = job.about_role ?? '';

                        ['key_responsibilities', 'benefits', 'categories', 'development_tools', 'bonus_skills'].forEach(
                            f => {
                                const el = document.getElementById(f);
                                if (el) el.value = Array.isArray(job[f]) ? job[f].join('\n') : '';
                            });

                        clearContainer('required_technical_skills_container');
                        if (job.required_technical_skills && typeof job.required_technical_skills === 'object') {
                            Object.entries(job.required_technical_skills).forEach(([k, v]) =>
                                addSkillGroup('required_technical_skills_container', k, Array.isArray(v) ? v : [String(
                                    v)]));
                        }

                        clearContainer('database_knowledge_container');
                        if (job.database_knowledge && typeof job.database_knowledge === 'object') {
                            Object.entries(job.database_knowledge).forEach(([k, v]) =>
                                addSkillGroup('database_knowledge_container', k, Array.isArray(v) ? v : [String(v)]));
                        }

                        clearContainer('qualifications_container');
                        if (job.qualifications && typeof job.qualifications === 'object') {
                            Object.entries(job.qualifications).forEach(([k, v]) => addQualificationRow(k, v));
                        }

                        document.getElementById('jobModal').classList.remove('hidden');
                    });
            }

            /* ── Delete ──────────────────────────────────────────────────── */
            function deleteJob(jobId) {
                currentJobId = jobId;
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            document.getElementById('confirmDelete')?.addEventListener('click', function() {
                if (!currentJobId) return;
                fetch(`/admin/jobs/${currentJobId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) location.reload();
                    });
            });

            /* ── Search / Filter ─────────────────────────────────────────── */
            document.getElementById('searchInput')?.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();
                document.querySelectorAll('#jobsTableBody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
                });
                updateShowingCount();
            });

            document.getElementById('filterType')?.addEventListener('change', function() {
                const val = this.value;
                document.querySelectorAll('#jobsTableBody tr').forEach(row => {
                    if (!val) {
                        row.style.display = '';
                        return;
                    }
                    const cell = row.querySelector('td:nth-child(5) span');
                    row.style.display = (cell && cell.textContent.trim() === val) ? '' : 'none';
                });
                updateShowingCount();
            });

            function updateShowingCount() {
                document.getElementById('showingCount').textContent =
                    document.querySelectorAll('#jobsTableBody tr:not([style*="display: none"])').length;
            }

            /* ── Form Submit ─────────────────────────────────────────────── */
            document.getElementById('jobForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);

                ['key_responsibilities', 'benefits', 'categories', 'development_tools', 'bonus_skills'].forEach(
                    field => {
                        const el = document.getElementById(field);
                        if (el && el.value.trim()) {
                            fd.set(field, JSON.stringify(el.value.split('\n').map(i => i.trim()).filter(Boolean)));
                        }
                    });

                fd.set('required_technical_skills', JSON.stringify(collectSkillGroups(
                    'required_technical_skills_container')));
                fd.set('database_knowledge', JSON.stringify(collectSkillGroups('database_knowledge_container')));
                fd.set('qualifications', JSON.stringify(collectQualifications()));

                fetch(this.action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) location.reload();
                    });
            });

            /* ── Backdrop close ──────────────────────────────────────────── */
            window.onclick = function(e) {
                ['jobModal', 'viewJobModal', 'deleteModal'].forEach(id => {
                    const m = document.getElementById(id);
                    if (e.target === m) m.classList.add('hidden');
                });
            };
        </script>
    @endpush
</x-app-layout>
