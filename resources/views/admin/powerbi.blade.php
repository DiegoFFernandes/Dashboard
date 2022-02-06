<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <title>Document</title>
</head>

<body>
    <div class="container-fluid">
        <h3 class="">Power Bi Embedded</h3>
        <div class="row">
            <div class="col-md-12">
                <div id="reportContainer" style="height: 600px">

                </div>
            </div>
        </div>
    </div>
    <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/powerbi-client@2.19.1/dist/powerbi.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                "https://embedded.powerbi.com/reportEmbed?reportId=8ee36fa6-5b54-49d4-8e66-713791f7fd3e&groupId=04d511b0-ded9-4952-9708-dfa331d1ee83";

            // Read report Id
            let embedReportId = "8ee36fa6-5b54-49d4-8e66-713791f7fd3e";

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
                            visible: true
                        },
                        pageNavigation: {
                            visible: true
                        }
                    }
                }
            };

            // Get a reference to the embedded report HTML element
            let embedContainer = $('#reportContainer')[0];

            // Embed the report and display it within the div container.
            report = powerbi.embed(embedContainer, config);

            // report.off removes all event handlers for a specific event
            report.off("loaded");

            // report.on will add an event handler
            report.on("loaded", function() {
                loadedResolve();
                report.off("loaded");
            });

            // report.off removes all event handlers for a specific event
            report.off("error");

            report.on("error", function(event) {
                console.log(event.detail);
            });

            // report.off removes all event handlers for a specific event
            report.off("rendered");

            // report.on will add an event handler
            report.on("rendered", function() {
                renderedResolve();
                report.off("rendered");
            });
        }

        embedPowerBIReport();
    </script>
</body>

</html>
