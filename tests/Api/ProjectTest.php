<?php

/*
 * This file is part of Gitamin.
 * 
 * Copyright (C) 2015-2016 The Gitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitamin\Tests\Api;

use Gitamin\Tests\AbstractTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectTest extends AbstractTestCase
{
    use DatabaseMigrations;

    public function testGetProjects()
    {
        $projects = factory('Gitamin\Models\Project', 3)->create();

        $this->get('/api/v1/projects');
        $this->seeJson(['id' => $projects[0]->id]);
        $this->seeJson(['id' => $projects[1]->id]);
        $this->seeJson(['id' => $projects[2]->id]);
        $this->assertResponseOk();
    }

    public function testGetInvalidProject()
    {
        $this->get('/api/v1/projects/1');
        $this->assertResponseStatus(404);
    }

    public function testPostProjectUnauthorized()
    {
        $this->post('/api/v1/projects');

        $this->assertResponseStatus(401);
    }

    public function testPostProjectNoData()
    {
        $this->beUser();

        $this->post('/api/v1/projects');
        $this->assertResponseStatus(400);
    }

    public function testPostProject()
    {
        $this->beUser();

        $this->post('/api/v1/projects', [
            'name'        => 'Foo',
            'description' => 'Bar',
            'status'      => 1,
            'slug'        => 'Baidu',
            'order'       => 1,
            'team_id'     => 1,
            'enabled'     => true,
        ]);
        $this->seeJson(['name' => 'Foo']);
        $this->assertResponseOk();
    }

    public function testPostProjectWithoutEnabledField()
    {
        $this->beUser();

        $this->post('/api/v1/projects', [
            'name'        => 'Foo',
            'description' => 'Bar',
            'status'      => 1,
            'slug'        => 'Alibaba',
            'order'       => 1,
            'team_id'     => 1,
        ]);
        $this->seeJson(['name' => 'Foo', 'enabled' => true]);
        $this->assertResponseOk();
    }

    public function testPostDisabledProject()
    {
        $this->beUser();

        $this->post('/api/v1/projects', [
            'name'        => 'Foo',
            'description' => 'Bar',
            'status'      => 1,
            'slug'        => 'Tencent',
            'order'       => 1,
            'team_id'     => 1,
            'enabled'     => 0,
        ]);
        $this->seeJson(['name' => 'Foo', 'enabled' => false]);
        $this->assertResponseOk();
    }

    public function testGetNewProject()
    {
        $project = factory('Gitamin\Models\Project')->create();

        $this->get('/api/v1/projects/1');
        $this->seeJson(['name' => $project->name]);
        $this->assertResponseOk();
    }

    public function testPutProject()
    {
        $this->beUser();
        $project = factory('Gitamin\Models\Project')->create();

        $this->put('/api/v1/projects/1', [
            'name' => 'Foo',
        ]);
        $this->seeJson(['name' => 'Foo']);
        $this->assertResponseOk();
    }

    public function testDeleteProject()
    {
        $this->beUser();
        $project = factory('Gitamin\Models\Project')->create();

        $this->delete('/api/v1/projects/1');
        $this->assertResponseStatus(204);
    }
}
