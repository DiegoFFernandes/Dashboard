@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="iframebox">
                <div class="col-md-12" id="iframe-mobile">
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
                '<iframe src="https://app.powerbi.com/view?r=eyJrIjoiMTJlMDc3ZTUtYjYwNS00MGIwLWFiODgtNjAwNDc5MjU3N2U3IiwidCI6ImUwZTY3NmNhLTU0YTYtNDlhZC1hNzgyLWJmYmNjYTk5ZWViMyJ9&pageName=ReportSection88a54e23b3034a6e6937" frameborder="0">' +
                '</iframe>' +
                '</div>';
            $('#iframe-mobile').html(div);
        } else {
            let div = '<div id="htmlTest" class="iframe-embed-wrapper iframe-embed-responsive-16by9"></div>';
            $('#iframe-mobile').html(div);
        }

        //var clickright = document.getElementById("click-right");

        $(document).ready(function() {
            $('#iframe-mobile').on('contextmenu', function(e) {
                e.preventDefault();
                alert('Clicou');
            });
        });
    </script>
    <script src="{{ asset('js/diretoria-sul/script.js?v=2') }}"></script>
@endsection
