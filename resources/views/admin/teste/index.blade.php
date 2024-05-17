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
                        <div class="row invoice-info">
                            <div class="col-md-4 invoice-col">
                                <div class="col-xs-12 table-responsive">
                                    <p class="lead">Dados Cliente</p>
                                    <table class="table table-striped table-sm" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Placa</th>
                                                <th>Pressão Min</th>
                                                <th>Pressão Max</th>
                                                <th>Sulco Ideal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Diego</td>
                                                <td>Atb8320</td>
                                                <td>123</td>
                                                <td>123</td>
                                                <td>123</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>                            
                        </div>                       
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
