@if ($errors->all())
    @foreach ($errors->all() as $error)
        <!-- alert -->
        <div class="alert alert-warning alert-dismissible alert-geral" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-check"></i>{{ $error }}
        </div>
        <!-- /alert -->
    @endforeach
@endif
@if (session('warning'))
    <!-- alert -->
    <div class="alert alert-warning alert-dismissible alert-geral">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-exclamation-circle"></i>{{ session('warning') }}
    </div>
    <!-- /alert -->
@endif
@if (session('status'))
    <!-- alert -->
    <div class="alert alert-success alert-dismissible alert-geral">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-check"></i>{{ session('status') }}
    </div>
    <!-- /alert -->
@endif
{{-- @error('file')
    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
@enderror --}}
