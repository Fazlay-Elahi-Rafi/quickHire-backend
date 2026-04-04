<?php

namespace App\Http\Controllers;

use App\Models\JobPost as Job;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get jobs with pagination for the admin dashboard
        $jobs = Job::latest()->paginate(10);

        // Calculate statistics for the dashboard cards
        $totalJobs = Job::count();
        $activeJobs = Job::where('is_active', true)->count();
        $totalApplications = Job::sum('applications_count');
        $totalCompanies = Job::distinct('company')->count('company');

        return view('dashboard', compact(
            'jobs',
            'totalJobs',
            'activeJobs',
            'totalApplications',
            'totalCompanies'
        ));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
