// ============================
// MONTHLY REVENUE
// ============================

const revenueCtx = document.getElementById('revenueChart');

new Chart(revenueCtx, {

    type: 'line',

    data: {

        labels: bulan,

        datasets: [{

            label: 'Revenue',

            data: pendapatan,

            borderWidth: 3,

            tension: .4,

            fill: true

        }]

    },

    options: {

        responsive: true,
        maintainAspectRatio: false,

        plugins: {

            legend: {
                display: false
            }

        }

    }

});


// ============================
// CATEGORY
// ============================

const categoryCtx = document.getElementById("categoryChart");

new Chart(categoryCtx, {

    type: "doughnut",
    cutout: "60%",

    data: {

        labels: kategori,

        datasets: [{

            data: qtyKategori

        }]

    },

    options: {

        responsive: true

    }

});