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
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/powerbi-client@2.19.1/dist/powerbi.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
    <script type="text/javascript">
        let loadedResolve, reportLoaded = new Promise((res, rej) => {
            loadedResolve = res;
        });
        let renderedResolve, reportRendered = new Promise((res, rej) => {
            renderedResolve = res;
        });

        // Get models. models contains enums that can be used.
        models = window['powerbi-client'].models;

        // Embed a Power BI report in the given HTML element with the given configurations
        // Read more about how to embed a Power BI report in your application here: https://go.microsoft.com/fwlink/?linkid=2153590
        function embedPowerBIReport() {

            // Read embed application token
            let accessToken = "{{ $content->token }}";

            // Read embed URL
            let embedUrl =
                "https://embedded.powerbi.com/reportEmbed?reportId={{ $reportID }}&groupId={{ $groupID }}&variable={{ urlencode($variableValue) }}";

            // Read report Id
            let embedReportId = "{{ $reportID }}";

            // Read embed type from radio
            let tokenType = 'report';

            // We give All permissions to demonstrate switching between View and Edit mode and saving report.
            let permissions = models.Permissions.All;

            // Create the embed configuration object for the report
            // For more information see https://go.microsoft.com/fwlink/?linkid=2153590
            let config = {
                type: 'report',
                tokenType: tokenType == '0' ? models.TokenType.Aad : models.TokenType.Embed,
                accessToken: accessToken,
                embedUrl: embedUrl,
                id: embedReportId,
                permissions: permissions,
                settings: {
                    panes: {
                        filters: {
                            visible: false
                        },
                        pageNavigation: {
                            visible: false
                        },
                        visualizations: {
                            expanded: false
                        }
                    }
                }
            };

            // Get a reference to the embedded report HTML element
            let embedContainer = $('#reportContainer')[0];

            // Embed the report and display it within the div container.
            report = powerbi.embed(embedContainer, config);

            // // report.off removes all event handlers for a specific event
            // report.off("loaded");

            // // report.on will add an event handler
            // report.on("loaded", function() {
            //     loadedResolve();
            //     report.off("loaded");
            // });

            // // report.off removes all event handlers for a specific event
            // report.off("error");

            // report.on("error", function(event) {
            //     console.log(event.detail);
            // });

            // // report.off removes all event handlers for a specific event
            // report.off("rendered");

            // // report.on will add an event handler
            // report.on("rendered", function() {
            //     renderedResolve();
            //     report.off("rendered");
            // });

        }

        embedPowerBIReport();
    </script>
@endsection
