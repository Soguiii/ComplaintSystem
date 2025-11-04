<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = $this->getResources();
        return view('resources', ['cards' => $resources]);
    }

    /**
     * Show a single resource by slug.
     */
    public function show($slug)
    {
        $resources = $this->getResources();
        foreach ($resources as $res) {
            if ($res['slug'] === $slug) {
                return view('resources.show', ['card' => $res]);
            }
        }

        abort(404);
    }

    private function getResources()
    {
        return [
            [
                'title' => 'DOMESTIC CONFLICT',
                'slug' => 'domestic',
                'image' => 'conflict.png',
                'excerpt' => 'Domestic conflict involves ongoing arguments or tension between family or household members...',
                'description' => '
                    <h4>Domestic Conflict Resolution</h4>
                    <p class="lead">Our barangay offers confidential mediation services to help resolve family disputes.</p>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Steps to File a Complaint:</h5>
                            <ol class="mb-0">
                                <li>Visit the barangay office</li>
                                <li>Fill out the complaint form</li>
                                <li>Attend mediation sessions</li>
                                <li>Follow through with agreements</li>
                            </ol>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        For emergencies, contact authorities immediately.
                    </div>'
            ],
            [
                'title' => 'NOISE DISTURBANCE',
                'slug' => 'noise',
                'image' => 'noise.jpg',
                'excerpt' => 'Noise disturbance refers to excessive or unnecessary noise within a community...',
                'description' => '
                    <h4>Noise Complaint Process</h4>
                    <p class="lead">We help resolve noise-related issues in the community.</p>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Common Sources:</h5>
                            <ul class="mb-0">
                                <li>Loud music/parties</li>
                                <li>Construction work</li>
                                <li>Karaoke/Videoke</li>
                                <li>Business operations</li>
                            </ul>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        Quiet hours: 10:00 PM to 6:00 AM
                    </div>'
            ],
            [
                'title' => 'LAND/PROPERTY DISPUTE',
                'slug' => 'land',
                'image' => 'land.jpg',
                'excerpt' => 'Property disputes include conflicts over land boundaries or ownership...',
                'description' => '
                    <h4>Property Dispute Resolution</h4>
                    <p class="lead">We mediate property-related conflicts between community members.</p>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Required Documents:</h5>
                            <ul class="mb-0">
                                <li>Property titles/tax declarations</li>
                                <li>Lot surveys/plans</li>
                                <li>Photos of disputed area</li>
                                <li>Previous agreements</li>
                            </ul>
                        </div>
                    </div>'
            ],
            [
                'title' => 'BARANGAY OFFICIAL CONCERN',
                'slug' => 'official',
                'image' => 'concern.jpg',
                'excerpt' => 'Concerns about the actions or behavior of barangay officials...',
                'description' => '
                    <h4>Official Conduct Concerns</h4>
                    <p class="lead">Report and resolve issues with barangay officials.</p>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Complaint Process:</h5>
                            <ol class="mb-0">
                                <li>Submit written complaint</li>
                                <li>Provide evidence</li>
                                <li>Attend investigation</li>
                                <li>Receive updates</li>
                            </ol>
                        </div>
                    </div>'
            ],
            [
                'title' => 'TANOD MISCONDUCT',
                'slug' => 'tanod',
                'image' => 'tanod.jpg',
                'excerpt' => 'Tanod misconduct refers to improper or abusive behavior by a barangay tanod...',
                'description' => '
                    <h4>Tanod Conduct Reports</h4>
                    <p class="lead">Report issues with barangay tanod behavior.</p>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Filing Steps:</h5>
                            <ol class="mb-0">
                                <li>Document the incident</li>
                                <li>Gather witness statements</li>
                                <li>File formal complaint</li>
                                <li>Follow up on investigation</li>
                            </ol>
                        </div>
                    </div>'
            ],
            [
                'title' => 'OTHERS',
                'slug' => 'others',
                'image' => 'others.jpg',
                'excerpt' => 'Other community issues not covered by specific categories...',
                'description' => '
                    <h4>Other Community Concerns</h4>
                    <p class="lead">We handle various other community issues.</p>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Examples:</h5>
                            <ul class="mb-0">
                                <li>Community cleanliness</li>
                                <li>Street lighting</li>
                                <li>Drainage problems</li>
                                <li>Public safety</li>
                            </ul>
                        </div>
                    </div>'
            ]
        ];
    }
}