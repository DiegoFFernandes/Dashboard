@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="iframebox" id="click-right">
                <div class="col-md-12" id="iframe-mobile"></div>
                {{-- <div class="blueborder">&nbsp;</div> --}}
                <div class="iframefooter">
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </section>
    <!-- /.row -->

    <!-- /.content -->
@endsection

@section('scripts')
    <script type="text/javascript" defer>
        if ($(window).width() < 960) {
            let div = '<div class="iframe-embed-wrapper iframe-embed-responsive-16by9">'
                +'<iframe src="https://app.powerbi.com/view?r=eyJrIjoiYjIzYTc0NjctYzI1Zi00ZjMwLWI2ZjEtMmQ2M2RjZmI0NzlkIiwidCI6ImUwZTY3NmNhLTU0YTYtNDlhZC1hNzgyLWJmYmNjYTk5ZWViMyJ9" frameborder="0">'
                +'</iframe>'
                +'</div>';
            $('#iframe-mobile').html(div);
        } else {
            let div = '<div id="htmlTest" class="iframe-embed-wrapper iframe-embed-responsive-16by9"></div>';
            $('#iframe-mobile').html(div);
        }        
    </script>
    <script src="{{ asset('js/saldo-estoque/script.js?v=2') }}"></script>
@endsection
