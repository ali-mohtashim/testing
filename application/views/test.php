<link href="//cdnjs.cloudflare.com/ajax/libs/foundation/5.0.2/css/foundation.min.css" rel="stylesheet"/>

<style>
    body {
        padding: 20px;
    }
    td, th {
        min-width: 300px;
        text-align:left;
    }
</style>
<div class="row">
    <div class="large-6 columns">
        <label for="start">Start Date</label>
        <input id="start" type="date" /><br />
    </div>
    <div class="large-6 columns">
        <label for="end">End Date</label>
        <input id="end" type="date" /><br />
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <button class="button radius" id="filter">Filter</button>
        <button id="clearFilter" class="button radius secondary">Clear Filter</button>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <table id="oTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js"></script>
<script>
    $(function () {
        // Basic Setup
        var $tableSel = $('#oTable');
        $tableSel.dataTable({
            'aaData': dummyData,
            'aoColumns': [
                {'mData': 'name'},
                {'mData': 'registered'}
            ],
            'sDom': '' // Hiding the datatables ui
        });

        $('#filter').on('click', function (e) {
            e.preventDefault();
            var startDate = $('#start').val(),
                    endDate = $('#end').val();

            filterByDate(1, startDate, endDate); // We call our filter function

            $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
        });

        // Clear the filter. Unlike normal filters in Datatables,
        // custom filters need to be removed from the afnFiltering array.
        $('#clearFilter').on('click', function (e) {
            e.preventDefault();
            $.fn.dataTableExt.afnFiltering.length = 0;
            $tableSel.dataTable().fnDraw();
        });

    });

    /* Our main filter function
     * We pass the column location, the start date, and the end date
     */
    var filterByDate = function (column, startDate, endDate) {
        // Custom filter syntax requires pushing the new filter to the global filter array
        $.fn.dataTableExt.afnFiltering.push(
                function (oSettings, aData, iDataIndex) {
                    var rowDate = normalizeDate(aData[column]),
                            start = normalizeDate(startDate),
                            end = normalizeDate(endDate);

                    // If our date from the row is between the start and end
                    if (start <= rowDate && rowDate <= end) {
                        return true;
                    } else if (rowDate >= start && end === '' && start !== '') {
                        return true;
                    } else if (rowDate <= end && start === '' && end !== '') {
                        return true;
                    } else {
                        return false;
                    }
                }
        );
    };

// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
    var normalizeDate = function (dateString) {
        var date = new Date(dateString);
        var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
        return normalized;
    }

// Filler data for demo (thanks to http://json-generator.com)
    var dummyData = [
        {
            "name": "Chasity Stanton",
            "registered": "1992-08-12"
        },
        {
            "name": "Mcgowan Vance",
            "registered": "2010-06-25"
        },
        {
            "name": "Craig Hill",
            "registered": "1992-08-11"
        },
        {
            "name": "Lois Elliott",
            "registered": "2003-02-22"
        },
        {
            "name": "Gross Frost",
            "registered": "2005-09-02"
        },
        {
            "name": "Debra Emerson",
            "registered": "2003-05-29"
        },
        {
            "name": "Leona Wilkinson",
            "registered": "1991-01-22"
        },
        {
            "name": "Elena Puckett",
            "registered": "1996-06-21"
        },
        {
            "name": "Mcintosh Hudson",
            "registered": "1992-12-13"
        }
    ];
</script>