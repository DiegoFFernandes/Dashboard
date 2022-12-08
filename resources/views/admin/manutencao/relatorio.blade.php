@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @includeIf('admin.master.messages')
                
            </div>
        </div>
        
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            
            

        });
    </script>
@endsection
