@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="iframebox">
                <div class="col-md-12">
                    <div id="htmlTest" class="iframe-embed-wrapper iframe-embed-responsive-16by9"></div>
                </div>
                {{-- <div class="blueborder">&nbsp;</div> --}}
                <div class="iframefooter">
                    <br>
                    <br>
                    <br>
                </div>
            </div>
            
        </div>
        <!-- /.row -->



    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script src="{{ asset('js/comercial/script.js') }}"></script>
@endsection
