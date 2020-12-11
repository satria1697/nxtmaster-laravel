<!doctype html>
<html lang="en">
<head>
    <title>Laporan Analisis</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="//code.highcharts.com/highcharts.js"></script>
    <script src="//code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
</head>
<style>
    body {
        font-family: "Segoe UI", sans-serif;
    }
    header {
        position: fixed;
        top: -60px;
        left: 0px;
        right: 0px;
        height: 50px;

        /** Extra personal styles **/
        /*background-color: #03a9f4;*/
        /*color: white;*/
        text-align: center;
        line-height: 45px;
    }

    footer {
        position: fixed;
        bottom: -60px;
        left: 0px;
        right: 0px;
        height: 50px;

        /** Extra personal styles **/
        /*background-color: #03a9f4;*/
        /*color: black;*/
        /*text-align: center;*/
        line-height: 30px;
        font-size: 12px;
    }
</style>
<body>
<header>
    Wish Enterprise
</header>

<footer>
    @Copyright {{date("Y")}}
</footer>

<h3>Laporan Hasil Rekapitulasi Perbandingan {{$text}}</h3>
<div>
    <p>Persentase kelengkapan berkas dari {{$tglawal}} sampai dengan {{$tglakhir}}, digambarkan pada tabel berikut: </p>
    <table class="table table-sm table-striped">
        <thead>
        <tr>
            <th>Bulan</th>
            <th>Persentase Dokter 2x24jam</th>
            <th>Persentase Perawat 2x24jam</th>
            <th>Jumlah Data Dokter</th>
            <th>Jumlah Data Perawat</th>
        </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < count($bulan); $i++)
        <tr>
            <td>{{$bulan[$i]}}</td>
            @for($j = 0; $j < 2; $j++)
            <td>{{$data[$i][$j]}}%</td>
            @endfor
            @for($j = 2; $j < 4; $j++)
                <td>{{$data[$i][$j]}}</td>
            @endfor
        </tr>
        @endfor
        </tbody>
    </table>
</div>

<div id="container"></div>
<script type="text/javascript">
    Highcharts.chart('container', {
        title: {
            text: 'New User Growth, 2019'
        },
        subtitle: {
            text: 'Source: tutsmake.com'
        },
        xAxis: {
            categories: ['jan', 'feb', 'mar']
        },
        yAxis: {
            title: {
                text: 'Number of New Users'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'New Users',
            data: [20, 30, 10]
        }, {
            name: 'New asd',
            data: [20, 40, 100]
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>

</body>
</html>
