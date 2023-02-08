<!DOCTYPE html>
<html>
<head>
	<title>HAPI</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="png" sizes="96x96" rel="icon" href="image/favicon.png">
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.min.js"></script>
	<script src="script.js"></script>
</head>

<body>

<?php

$data = file_get_contents('http://192.168.240.52/api/get_meteo.php');
$array = json_decode($data, true);

?>
<div class="row">

		<div class="col-5 col-s-12">
			<div class="date">
				<div id="time"></div>
			</div>
		</div>
		
		<div class="col-7 col-s-12">
			<div class="releve">
				<p>
				Dernier relevé : 
				<?php 
				echo $array[0]['temp']."°C"; 
				?>
				<img src="image/temp.png"> |
				<?php
				echo $array[0]['hum']."%";
				?>
				<img src="image/hum.png">
				</p>
			</div>
		</div>
		
		<div class="col-9 col-s-12">
			<div class="grahique">
				<canvas id="graph"></canvas>
			</div>
		</div>

		<div class="col-3 col-s-12">
			<div class="map">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d46320.15352609042!2d5.320451259613032!3d43.48127013213579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12c9edc093e0ee95%3A0x85c391f75d1288b7!2sCampus%20CESI!5e0!3m2!1sfr!2sfr!4v1674468759361!5m2!1sfr!2sfr"  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</div>
		
</div>

<script>

fetch('http://192.168.240.52/api/get_meteo.php')
    .then(response => response.json())
    .then(data => {
        // Extraire le labels et les data
        var labels = data.map(row => row.heure);
        var temperatures = data.map(row => row.temp);
        var humidites = data.map(row => row.hum);

        // Mise a jour du graphique
        myChart.data.labels = labels;
        myChart.data.datasets[0].data = temperatures;
        myChart.data.datasets[1].data = humidites;

        // Redessine the chart
        myChart.update();
    });
 
var ctx = document.getElementById('graph').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Temperature (en °C)',
            data: [],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }, {
            label: 'Humidité (en %)',
            data: [],
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});


//Pour afficher l'heure
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  document.getElementById('time').innerHTML ="Heure actuelle: "+ h + "h" + m + "m" +s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i}; 
  return i;
}
startTime();


</script>

</body>
</html>


