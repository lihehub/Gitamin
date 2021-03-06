<?php

/*
 * This file is part of Gitamin.
 * 
 * Copyright (C) 2015-2016 The Gitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitamin\Composers;

use Gitamin\Models\Issue;
use Gitamin\Models\Project;
use Gitamin\Models\ProjectTeam;
use Illuminate\Contracts\View\View;

class ExploreComposer
{
    /**
     * Index page view composer.
     *
     * @param \Illuminate\Contracts\View\View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        // Default data
        $withData = [
            'systemStatus'  => 'info',
            'systemMessage' => trans('gitamin.service.bad'),
            'favicon'       => 'favicon-high-alert',
        ];

        if (Project::enabled()->notStatus(1)->count() === 0) {
            // If all our projects are ok, do we have any non-fixed issues?
            $issues = Issue::orderBy('created_at', 'desc')->get();
            $issueCount = $issues->count();

            if ($issueCount === 0 || ($issueCount >= 1 && (int) $issues->first()->status === 4)) {
                $withData = [
                    'systemStatus'  => 'success',
                    'systemMessage' => trans('gitamin.service.good'),
                    'favicon'       => 'favicon',
                ];
            }
        } else {
            if (Project::enabled()->whereIn('status', [2, 3])->count() > 0) {
                $withData['favicon'] = 'favicon-medium-alert';
            }
        }

        // Project & Project Team lists.
        $usedProjectTeams = Project::enabled()->where('team_id', '>', 0)->groupBy('team_id')->lists('team_id');
        $projectTeams = ProjectTeam::whereIn('id', $usedProjectTeams)->orderBy('order')->get();
        $unteamedProjects = Project::enabled()->where('team_id', 0)->orderBy('order')->orderBy('created_at')->get();

        $view->with($withData)
            ->withProjectTeams($projectTeams)
            ->withUnteamedProjects($unteamedProjects);
    }
}
