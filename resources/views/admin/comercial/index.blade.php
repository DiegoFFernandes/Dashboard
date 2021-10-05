@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            {{-- index --}}
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">

                    <!-- .box-body -->
                    <div class="box-body">
                        <div class="iframe-embed-wrapper iframe-embed-responsive-16by9">
                            <iframe class="iframe-embed" id="powerbi" src="javascript:;"></iframe>
                        </div>                        
                    </div>

                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->


    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var _0x55a2 = ["\x73\x72\x63",
                "\x68\x74\x74\x70\x73\x3A\x2F\x2F\x69\x73\x2E\x67\x64\x2F\x49\x56\x4F\x42\x49\x5F\x43\x4F\x4D\x43\x47\x53",
                "\x61\x74\x74\x72", "\x23\x70\x6F\x77\x65\x72\x62\x69"
            ];
            $(_0x55a2[3])[_0x55a2[2]](_0x55a2[0], _0x55a2[1])
        });            
    </script>

@endsection
