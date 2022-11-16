<!DOCTYPE html>
<html>
@include('layouts._head')

@yield('extra_style')
<body>
	@include('layouts._setting')

	<div class="container-scroller">
		@include('layouts._sidebar')

			@yield('content')

		@include('layouts._footer')
	</div>

@include('layouts._script')

@yield('extra_script')
</body>
</html>
