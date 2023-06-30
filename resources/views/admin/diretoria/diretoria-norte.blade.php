@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-list-client">
                    <div class="box-body">
                        <div class="iframe-embed-wrapper iframe-embed-responsive-16by9">
                            <div id="reportContainer">

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
    <script type="text/javascript">
        // Embed a Power BI report in the given HTML element with the given configurations        
        // Read more about how to embed a Power BI report in your application here: https://go.microsoft.com/fwlink/?linkid=2153590

        let models = window['powerbi-client'].models;
        // Read embed application token
        let accessToken = "{{ $content->token }}";
        // Read embed URL
        let embedUrl =
            "https://app.powerbi.com/reportEmbed?reportId={{ $reportID }}";
        // Read report Id
        let embedReportId = "{{ $reportID }}";

        function embedPowerBIReport() {

            // Read embed type from radio
            let tokenType = '1';

            // We give All permissions to demonstrate switching between View and Edit mode and saving report.
            let permissions = models.Permissions.All;

            // Create the embed configuration object for the report
            // For more information see https://go.microsoft.com/fwlink/?linkid=2153590
            let config = {
                type: 'report',
                tokenType: tokenType == '0' ? models.TokenType.Aad : models.TokenType.Embed,
                accessToken: accessToken,
                embedUrl: embedUrl,
                contrastMode: 0,
                // viewMode: models.ViewMode.View,
                id: embedReportId,
                permissions: permissions,
                // viewMode: models.ViewMode.Edit,
                // autoAuth: true,
                settings: {
                    optimizeForPerformance: false,
                    panes: {
                        filters: {
                            visible: false
                        },
                        pageNavigation: {
                            visible: false
                        }
                    }
                }

            };

            // Get a reference to the embedded report HTML element
            let embedContainer = $('#reportContainer')[0];

            // Embed the report and display it within the div container.
            powerbi.embed(embedContainer, config);
        }
        embedPowerBIReport();
    </script>
@endsection
