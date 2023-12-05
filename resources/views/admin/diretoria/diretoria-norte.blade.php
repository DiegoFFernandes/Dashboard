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
    <script type="text/javascript" defer>
        if ($(window).width() < 960) {
            let div = '<div class="iframe-embed-wrapper iframe-embed-responsive-16by9">' +
                '<iframe src="https://app.powerbi.com/view?r=eyJrIjoiNzc3YzMxZjUtNmNjZi00N2RkLWIyNTctNzliMDY3MTI1OGFmIiwidCI6ImUwZTY3NmNhLTU0YTYtNDlhZC1hNzgyLWJmYmNjYTk5ZWViMyJ9" frameborder="0">' +
                '</iframe>' +
                '</div>';
            $('#iframe-mobile').html(div);
        } else {
            let div = '<div id="htmlTest" class="iframe-embed-wrapper iframe-embed-responsive-16by9"></div>';
            $('#iframe-mobile').html(div);
        }        
    </script>
    <script src="{{ asset('js/diretoria-norte/script.js?v=2') }}"></script>
@endsection
