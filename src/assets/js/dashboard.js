
$(document).ready(function () {
    var formData = new FormData();
    formData.append('action', 'getDataForDashboardCounters')
    const xhr = new XMLHttpRequest();
    xhr.open("POST", './src/controllers/admin/dashboardController.php', true);
    xhr.send(formData);
    xhr.onreadystatechange = () => { // Call a function when the state changes.
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            console.log(response)
            if (response.status === 1) {
                document.getElementById('present-person-counter').innerHTML = response.presentPersonCounter
                document.getElementById('present-absent-counter').innerHTML = response.presentAbsentCounter
            } else {
                alert(response.msg)
            }
        }
    }
});
(function () {
    'use strict'

    feather.replace({ 'aria-hidden': 'true' })

    // Graphs
    var ctx = document.getElementById('myChart')


    var dataCollected = [
        0, 0, 0, 0, 0
    ]

    var formData = new FormData();
    formData.append('action', 'getPresencesPerWeekDay')
    const xhr = new XMLHttpRequest();
    xhr.open("POST", './src/controllers/admin/dashboardController.php', true);
    xhr.send(formData);
    xhr.onreadystatechange = () => { // Call a function when the state changes.
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            console.log(xhr)
            let response = JSON.parse(xhr.responseText);
            if (response.status === 1) {
                dataCollected = Object.values(response);
                dataCollected.pop();
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [
                            'Segunda',
                            'Terça',
                            'Quarta',
                            'Quinta',
                            'Sexta',
                        ],
                        datasets: [{
                            data: dataCollected,
                            lineTension: 0,
                            backgroundColor: 'transparent',
                            borderColor: '#007bff',
                            borderWidth: 4,
                            pointBackgroundColor: '#007bff'
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1,
                                    min: 0, // minimum value
                                    max: 20 // maximum value
                                }
                            }]
                        },
                        legend: {
                            display: false
                        }
                    }
                })
            } else {
                alert(response.msg)
            }
        }
    }



})()
