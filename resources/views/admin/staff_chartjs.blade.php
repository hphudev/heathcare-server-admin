<div style="display: flex; justify-content:start; overflow-x: auto; width: 100vw">
    <div>
        <canvas id="staffChart" width="400" height="400"></canvas>
    </div>
    <div>
        <div style="display:flex; justify-content: start">
            <div style="margin-right: 12px;">
                <label for="date-start">Start time</label>
                <br>
                <input id="date-start" type="month">
            </div>
            <div>
                <label for="date-end">End time</label>
                <br>
                <input id="date-end" type="month">

            </div>
        </div>
        <div style="overflow-x: auto;">
            <canvas id="billChart" width="870" height="400" style="overflow-x: auto;"></canvas>

        </div>
    </div>
</div>
<script>
    function updateChart(type, labels, label, data, id) {
        var ctx = document.getElementById(id).getContext('2d');
        var myChart = new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        // 'rgba(255, 206, 86, 0.2)',
                        // 'rgba(75, 192, 192, 0.2)',
                        // 'rgba(153, 102, 255, 0.2)',
                        // 'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        // 'rgba(255, 206, 86, 1)',
                        // 'rgba(75, 192, 192, 1)',
                        // 'rgba(153, 102, 255, 1)',
                        // 'rgba(255, 159, 64, 1)'
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
    };

    function loadUserAdmins() {
        $.ajax({
            type: "post",
            url: "/public/api/user-admin/get-users-and-admins",
            success: function(data) {
                console.log(data);
                updateChart('bar', ["Admins", "Users"], 'Users and Admins', [data.num_admins, data.num_users], 'staffChart');
            },
            error: function(param) {
                console.log(param);
            }
        });
    }

    function initLoadBillChart() {
        let date = new Date();
        console.log(date.getFullYear() + '-' + (date.getMonth() + 1));
        document.getElementById('date-start').value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2);
        document.getElementById('date-end').value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2);
    }

    function loadBillChart() {
        let date_start = new Date(document.getElementById('date-start').value);
        date_start = new Date(date_start.getFullYear(), date_start.getMonth() + 1, 0);
        let date_end = new Date(document.getElementById('date-end').value);
        date_end = new Date(date_end.getFullYear(), date_end.getMonth() + 1, 0);
        console.log(date_start, ' ', date_end);
        $.ajax({
            type: "post",
            url: "/public/api/bill/get-bills",
            data: {
                start_date: date_start.getFullYear() + '-' + ('0' + (date_start.getMonth() + 1)).slice(-2) + '-' + '01',
                end_date: date_end.getFullYear() + '-' + ('0' + (date_end.getMonth() + 1)).slice(-2) + '-' + ('0' + (date_end.getDate())).slice(-2),
            },
            success: function(data) {
                console.log(data);
                let labels = [],
                    datas = [];
                data.forEach(element => {
                    date = new Date(element.date);
                    // date = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                    labels.push("Thg " + (date.getMonth() + 1));
                    datas.push(element.price);
                });
                console.log(labels);
                updateChart('line', labels, 'Sales', datas, 'billChart')
            }
        });
    }

    $(document).ready(function() {
        loadUserAdmins();
        initLoadBillChart();
        $('#date-start').on('input', () => {
            loadBillChart();
        })
        $('#date-end').on('change', () => {
            loadBillChart();
        })
        loadBillChart();
    });
</script>