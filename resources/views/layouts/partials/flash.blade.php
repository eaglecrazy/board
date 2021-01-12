@if (session('status'))
    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
@endif
@if (session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif
@if (session('info'))
    <div class="alert alert-info" role="alert">{{ session('info') }}</div>
@endif
@if (count($errors) > 0)
    <div class="alert alert-danger mb-4">
        <ul class="list-unstyled mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
