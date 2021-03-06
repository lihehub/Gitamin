@extends('layout.master')

@section('content')
<div class="section-messages">
    @include('dashboard.partials.errors')
</div>

@include('partials.about-app')

<div class="section-explore">
    <div class="alert alert-{{ $systemStatus }}">{{ $systemMessage }}</div>
</div>

@if(!$project_teams->isEmpty() || !$unteamed_projects->isEmpty())
<div class="section-projects">
    @include('partials.projects')
</div>
@endif

@if($days_to_show > 0)
<div class="section-timeline">
    <h1>{{ trans('gitamin.issues.past') }}</h1>
    @foreach($all_issues as $date => $issues)
    @include('partials.issues', [compact($date), compact($issues)])
    @endforeach
</div>

<nav>
    <ul class="pager">
        @if($can_page_backward)
        <li class="previous">
            <a href="{{ route('explore') }}?start_date={{ $previous_date }}" class="links">
                <span aria-hidden="true">&larr;</span> {{ trans('gitamin.issues.previous_week') }}
            </a>
        </li>
        @endif
        @if($can_page_forward)
        <li class="next">
            <a href="{{ route('explore') }}?start_date={{ $next_date }}" class="links">
                {{ trans('gitamin.issues.next_week') }} <span aria-hidden="true">&rarr;</span>
            </a>
        </li>
        @endif
    </ul>
</nav>
@endif
@stop
