<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Candidature;
use App\Models\Job;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Candidature::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Candidature::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidature  $candidature
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return Candidature::findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidature  $candidature
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->delete();
    }

    public function ranking(int $jobId)
    {
        $ranking = array();
        $job = Job::findOrFail($jobId);
        // Peguei as candidaturas para essa vaga
        $candidatures = Candidature::query()->where('job_id', '=', $jobId)->get();
        foreach ($candidatures as $candidature) {
            $applicant = Applicant::query()->where('id', '=', $candidature->applicant_id)->first();
            $applicant = $this->calculateDistance($job, $applicant);
            $ranking[] = $applicant;
        }
        return $ranking;
    }

    public function calculateDistance(Job $job, Applicant $applicant)
    {
        $distances = [
            'ab' => 5,
            'ac' => 12,
            'ad' => 8,
            'ae' => 16,
            'af' => 16,
            'bc' => 7,
            'bd' => 3,
            'be' => 11,
            'bf' => 11,
            'cd' => 10,
            'ce' => 4,
            'cf' => 18,
            'de' => 10,
            'df' => 8,
            'ef' => 18
        ];

        $nv = $job->level;
        $nc = $applicant->level;
        $n = 100 - 25 * ($nv - $nc);

        if ($applicant->localization == $job->localization) {
            $d = 100;
        } else {
            $applicantDistance = $distances[strtolower($applicant->localization . $job->localization)] ?? $distances[strtolower($job->localization . $applicant->localization)];
            if ($applicantDistance >= 0 && $applicantDistance <= 5) {
                $d = 100;
            } elseif ($applicantDistance <= 10) {
                $d = 75;
            } elseif ($applicantDistance <= 15) {
                $d = 50;
            } elseif ($applicantDistance <= 20) {
                $d = 25;
            } else {
                $d = 0;
            }
        }

        $score = ($n + $d) / 2;
        $applicant->score = $score;

        return $applicant;
    }
}
