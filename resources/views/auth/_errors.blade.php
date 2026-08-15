@if (session('status'))
    <div class="mb-4 rounded border border-green-300 bg-green-50 p-3 text-sm text-green-700">
        {{ session('status') }}
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-sm text-red-700">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
