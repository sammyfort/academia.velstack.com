<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
     <link rel="shortcut icon" href="{{ URL::asset('images/favicon.png')}}">
    <title>@yield('title')</title>
    <style>
        table tr{
            height: 35px;
        }
        .bg-grey{
            background-color: #c4c4c4 !important;
        }
        .border-end, .border, .border-bottom, .border-top, .border-start{
            border-color: black !important;
        }
        .amount-width{
            width: 170px;
        }
        td img{
            max-width: 70px;
        }
        .signature-text{
            font-family: 'Great Vibes', cursive;
        }
        body{
            font-size: 11px;
        }
    </style>

    <style>
        .grading {
            position: absolute;
            margin-bottom: 100px;
            right: 25px;
            font-size: 11px; /* Reduced font size */
            width: 5.5cm;  /* Fixed width */
            max-height: 3.5cm; /* Max height */
            padding: 5px;
            background: #fafafa;
        }

        .grading table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Ensure consistent column width */
        }

        .grading th, .grading td {
            border: 1px solid #000;
            padding: 2px; /* Reduced padding */
            text-align: center;
            font-size: 6px; /* Smaller font size to fit content */
            line-height: 1; /* Reduced line height */
        }

        .grading th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .grading td {
            word-wrap: break-word; /* Prevent text overflow */
        }

        .grading tr:nth-child(even) td {
            background: #f9f9f9; /* Alternating row colors */
        }

        .grading tr:hover td {
            background: #eaeaea; /* Highlight row on hover */
        }
        /* Page container styles */
        .page-container {
            min-height: 100vh;
            border: 2px solid #000;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .content-wrapper {
            padding: 20px;
        }

        /* Table styles */
        .table {
            border: 1px solid #000;
            margin-bottom: 0;
        }

        .table td, .table th {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: middle;
        }

        /* Grading table styles */
        .grading-table {
            font-size: 9px;
        }

        .grading-table td, .grading-table th {
            padding: 1px 3px;
            height: 18px;
        }

        .signature-text {
            font-family: 'Great Vibes', cursive;
            font-size: 14px;
        }

        /* Print styles */
        @media print {
            .page-container {
                width: 100%;
                max-width: none;
                border: 2px solid #000 !important;
                margin: 0;
                padding: 0;
            }

            .content-wrapper {
                padding: 20px;
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
<div class="w-100 h-100">
    @yield('content')
</div>
</body>
</html>
