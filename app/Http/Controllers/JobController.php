<?php

namespace App\Http\Controllers;

use App\Mail\JobPosted;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::with('employer')->latest('id')->cursorPaginate(5);

        return view('jobs.index', [
            'jobs' => $jobs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation
        request()->validate([
            'title' => ['required', 'min:3'],
            'salary' => ['required']
        ]);

        $job = Job::create([
            'title' => $request->title,
            'salary' => request('salary'),
            'employer_id' => Auth::user()->employer->id
        ]);

        Mail::to($job->employer->user->email)->send(
            new JobPosted($job)
        );

        return redirect('/jobs');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        // $selected_job = Job::find($id); --------------> Route Model Binding

        return view('jobs.show', ['job' => $job]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        // $selected_job = Job::find($id); --------------> Route Model Binding

        return view('jobs.edit', ['job' => $job]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        // -- authorize (On Hold)


        // -- validate
        request()->validate([
            'title' => ['required', 'min:3'],
            'salary' => ['required']
        ]);


        // -- Store db
        // $job = Job::findOrFail($id); --------------> Route Model Binding

        // ** Method 1 **
        // $job->title = request('title');
        // $job->save();
        
        // ** Method 2 **
        // Job::update([
        $job->update([
            'title' => request('title'),
            'salary' => request('salary'),
        ]);

        return redirect('/jobs/' . $job->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        // -- authorize (On Hold)

        $job->delete();

        return redirect('/jobs');
    }
}
