<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $jobs = JobPost::latest()->paginate(10);
        $jobs = JobPost::where('status', 1)->latest()->paginate(10);

        if ($request->expectsJson()) {
            return response()->json($jobs);
        }

        $totalJobs         = JobPost::count();
        $activeJobs = JobPost::where('status', 1)->count();
        $totalApplications = 0;
        $totalCompanies    = JobPost::distinct('company')->count('company');

        return view('dashboard', compact('jobs', 'totalJobs', 'activeJobs', 'totalApplications', 'totalCompanies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $jsonFields = [
            'key_responsibilities',
            'benefits',
            'categories',
            'development_tools',
            'bonus_skills',
            'required_technical_skills',
            'database_knowledge',
            'qualifications',
        ];

        foreach ($jsonFields as $field) {
            if ($request->has($field) && is_string($request->input($field))) {
                $decoded = json_decode($request->input($field), true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid JSON in field: {$field}",
                    ], 422);
                }

                $request->merge([$field => $decoded]);
            }
        }

        $validated = $request->validate([
            'title'                     => 'required|string|max:255',
            'sub_title'                 => 'nullable|string|max:255',
            'tag'                       => 'required|string|max:255',
            'company'                   => 'required|string|max:255',
            'location'                  => 'required|string|max:255',
            'job_type'                  => 'required|string|max:100',
            'workplace'                 => 'required|string|max:100',
            'description'               => 'required|string',
            'about_role'                => 'required|string',

            'key_responsibilities'      => 'required|array',
            'key_responsibilities.*'    => 'string',
            'benefits'                  => 'required|array',
            'benefits.*'                => 'string',
            'categories'                => 'required|array',
            'categories.*'              => 'string',
            'development_tools'         => 'required|array',
            'development_tools.*'       => 'string',
            'bonus_skills'              => 'nullable|array',
            'bonus_skills.*'            => 'string',

            'required_technical_skills' => 'required|array',
            'database_knowledge'        => 'required|array',
            'qualifications'            => 'required|array',

            'experience_required'       => 'nullable|string',
            'salary_range'              => 'nullable|string',
            'min_passing_score'         => 'nullable|integer|min:0|max:100',
            'available'                 => 'nullable|integer|min:0',
            'icon'                      => 'nullable|string',
            'status'                    => 'nullable|in:0,1',
        ]);

        $jobId = $request->input('job_id');

        if ($jobId) {
            $job = JobPost::findOrFail($jobId);
            $job->update($validated);
        } else {
            $job = JobPost::create($validated);
        }

        return response()->json(['success' => true, 'data' => $job]);
    }


    /**
     * Toggle job status (called via PATCH /admin/jobs/{id}/toggle-status)
     */
    public function toggleStatus(JobPost $job)
    {
        $job->status = $job->status ? 0 : 1;
        $job->save();

        return response()->json([
            'success' => true,
            'status'  => $job->status,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(JobPost::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return response()->json(JobPost::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobPost $job)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $job = JobPost::findOrFail($id);
        $job->delete();

        return response()->json(['success' => true]);
    }
}
