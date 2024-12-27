@if ($errors->all())
    @foreach ($errors->all() as $error)
        <!-- alert -->
        <div class="alert alert-warning alert-dismissible alert-geral">
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

{{-- Verificar se existe algo para exportar para excel --}}
@if (session()->has('export_results'))
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script>
        window.onload = function() {
            @if (session('export_results') && count(session('export_results')) > 0)
                msgToastr("Ordens que foram associadas na MRC004 e foram recusadas foram atualizadas para 'Atendidas'. Por favor, aguarde estamos criando uma planilha com as ordens alteradas por segurança!", 'success');
            @else
                msgToastr("Nenhum dado disponível para download.", 'success');
                return; // Não redirecionar se não houver dados
            @endif

            window.location.href = "{{ route('admin.download-excel') }}";
        };
    </script>
@endif
