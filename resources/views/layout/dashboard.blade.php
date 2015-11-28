<!DOCTYPE html>
<html>
@include('dashboard.partials.head')

<body class="dashboard">
	@include('dashboard.partials.header')
    <div class="wrapper">
        @include('dashboard.partials.sidebar')
        <div class="page-content">
            @yield('content')
        </div>
    </div>
    @include('dashboard.partials.footer')
    @yield('js')
</body>
</html>
