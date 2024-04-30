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

        // Read embed application token    

        var newAccessToken;
        let models = window['powerbi-client'].models;
        // Read embed application token
        let accessToken = "{{ $content->token }}";
        // Read embed URL
        let embedUrl =
            "https://app.powerbi.com/reportEmbed?reportId={{ $reportID }}";
        // Read report Id
        let embedReportId = "{{ $reportID }}";
        let embedGroupID = "{{ $groupID }}";

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
                    },
                    // zoomLevel: 0.5
                }

            };

            // Get a reference to the embedded report HTML element
            let embedContainer = $('#reportContainer')[0];
            // Embed the report and display it within the div container.
            let report = powerbi.embed(embedContainer, config);

        }

        const MINUTES_BEFORE_EXPIRATION = 10;

        // Set the refresh interval time to 30 seconds
        const INTERVAL_TIME = 60000;

        // Get the token expiration from the access token
        var tokenExpiration = "{{ $content->expiration }}"

        // Set an interval to check the access token expiration, and update if needed
        setInterval(() => checkTokenAndUpdate(embedReportId, embedGroupID), INTERVAL_TIME);

        function checkTokenAndUpdate(reportId, groupId) {
            // Get the current time
            const currentTime = Date.now();
            const milisegundosAdicionais = 13500000 ;

            // const currentTime = currentTime_old + milisegundosAdicionais;
            console.log(currentTime);

            const expiration = Date.parse(tokenExpiration);
            console.log(expiration);

            // Time until token expiration in milliseconds
            const timeUntilExpiration = expiration - currentTime;

            const timeToUpdate = MINUTES_BEFORE_EXPIRATION * 60 * 1000;

            // Update the token if it is about to expired

            if (timeUntilExpiration <= timeToUpdate) {
                console.log("Updating report access token");
                updateToken(reportId, groupId);
            }
        }

        async function updateToken(reportId, groupId) {

            // Generate a new embed token or refresh the user Azure AD access token
            $.ajax({
                type: "GET",
                url: "{{ route('diretoria.rede', [Crypt::encrypt('rede'), 1]) }}",
                success: function(response) {
                    newAccessToken = response;
                }
            });
            // Update the new token expiration time
            tokenExpiration = newAccessToken.expiration;

            // Get a reference to the embedded report HTML element
            let embedContainer = $('#reportContainer')[0];

            // Get a reference to the embedded report.
            let report = powerbi.get(embedContainer);

            // Set the new access token
            await report.setAccessToken(newAccessToken.token);

        }

        // Add a listener to make sure token is updated after tab was inactive
        document.addEventListener("visibilitychange", function() {
            // Check the access token when the tab is visible
            if (!document.hidden) {
                checkTokenAndUpdate(embedReportId, embedGroupID)
            }
        });

        embedPowerBIReport();
    </script>
@endsection
