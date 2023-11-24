@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                @includeIf('admin.master.messages')
            </div>
            <div class="col-md-12">
                <div class="box box-solid">                    
                    <div class="box-body">                        
                        <h2>Olá seja bem vindo(a), {{$user_auth->name}}!</h2>                        
                    </div>
                </div>
            </div>           
            
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    @isset($chart)
        {!! $chart->script() !!}
    @endisset
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
