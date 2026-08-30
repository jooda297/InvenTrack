<?php
    session_start();

    include "../Connect.php";

    $A_ID = $_SESSION['A_Log'];

    if (! $A_ID) {

        echo '<script language="JavaScript">
     document.location="../Login.php";
    </script>';

    } else {

        $sql1 = mysqli_query($con, "select * from users where id='$A_ID'");
        $row1 = mysqli_fetch_array($sql1);

        $name  = $row1['name'];
        $email = $row1['email'];

        $totalPrice = 0;

        $orderSql = mysqli_query($con, "select COUNT(id) AS orders_count from orders");
        $orderRow = mysqli_fetch_array($orderSql);

        $orders_count = $orderRow['orders_count'];

        $totalPriceSql = mysqli_query($con, "select SUM(product_price * quantity) AS total_price from order_items");
        $totalPriceRow = mysqli_fetch_array($totalPriceSql);

        $total_price = $totalPriceRow['total_price'];

        $productSql = mysqli_query($con, "select COUNT(id) AS products_count from products WHERE active = 1");
        $productRow = mysqli_fetch_array($productSql);

        $products_count = $productRow['products_count'];
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Dashboard - Inventrack</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link href="../assets/img/Logo.png" rel="icon" />
    <link href="../assets/img/Logo.png" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
      rel="stylesheet"
    />

    <!-- Vendor CSS Files -->
    <link
      href="../assets/vendor/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="../assets/vendor/bootstrap-icons/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet" />
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet" />
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet" />
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet" />
  </head>

  <body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
      <div class="d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
          <img src="../assets/img/Logo.png" alt="" />

        </a>
      </div>
      <!-- End Logo -->

      <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
          <li class="nav-item dropdown pe-3">
            <a
              class="nav-link nav-profile d-flex align-items-center pe-0"
              href="#"
              data-bs-toggle="dropdown"
            >
              <img
                src="https://www.computerhope.com/jargon/g/guest-user.png"
                alt="Profile"
                class="rounded-circle"
              />
              <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $name ?></span> </a
            >

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $name ?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="./Logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul>
          </li>
          <!-- End Profile Nav -->
        </ul>
      </nav>
    </header>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
      <?php require './Aside-Nav/Aside.php'?>
    <!-- End Sidebar-->

    <main id="main" class="main">
      <div class="pagetitle">
        <h1>Home Page</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->
      <section class="section faq">
        <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title">Hello admin, welcome to dashboard</h5>

                

              </div>
            </div>
          </div>




          <div class="col-lg-3">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title">IN STOCK</h5>

                <span id="productsCount"><?php echo $products_count ?? 0 ?></span>
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title"># Orders</h5>

                <span id="ordersCount"><?php echo $orders_count ?? 0 ?></span>
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title"># Total Cash</h5>

                <span id="totalCash"><?php echo $total_price ?? 0 ?> </span> JODs
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title">Profits</h5>

                <span id="Profits"><?php echo $total_price * 0.10 ?? 0 ?></span> JODs
              </div>
            </div>
          </div>


          <div class="col-lg-6">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title">Orders Report</h5>


                  <div id="ordersChart"></div>

              </div>
            </div>
          </div>


                    <div class="col-lg-6">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title">Products Created Report</h5>


<div id="productsCreatedChart"></div>

              </div>
            </div>
          </div>

                    <div class="col-lg-6">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title">Products QTY Report</h5>


<div id="productsQtyChart"></div>

              </div>
            </div>
          </div>

                    <div class="col-lg-6">
            <div class="card basic">
              <div class="card-body">
                <h5 class="card-title">Active Products Report</h5>


<div id="productsActiveChart"></div>

              </div>
            </div>
          </div>



        </div>
      </section>
    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
      <div class="copyright">
        &copy; Copyright <strong><span>Inventrack</span></strong
        >. All Rights Reserved
      </div>
    </footer>
    <!-- End Footer -->

    <a
      href="#"
      class="back-to-top d-flex align-items-center justify-content-center"
      ><i class="bi bi-arrow-up-short"></i
    ></a>

    <script>
    window.addEventListener('DOMContentLoaded', (event) => {
     document.querySelector('#sidebar-nav .nav-item:nth-child(1) .nav-link').classList.remove('collapsed')
   });
</script>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.min.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>
    <script src="http://localhost:3000/socket.io/socket.io.js"></script>



    <script>


let ordersChart;
let productsQtyChart;
let productsActiveChart;

fetch("./orders-report.php")
    .then(res => res.json())
    .then(data => {
         ordersChart = new ApexCharts(
            document.querySelector("#ordersChart"),
            {
                chart: {
                    type: "line",
                    height: 350
                },
                series: [
                    {
                        name: "Orders",
                        data: data.counts
                    },
                    {
                        name: "Revenue (JOD)",
                        data: data.revenues
                    }
                ],
                xaxis: {
                    categories: data.days
                },
                stroke: {
                    curve: "smooth"
                },
                colors: ["#008FFB", "#FEB019"],
                markers: {
                    size: 5
                }
            }
        );

        ordersChart.render();
    });



    fetch("./products-report.php")
    .then(res => res.json())
    .then(data => {

        // -----------------------------
        // 1) Products Created Per Day
        // -----------------------------
        const createdChart = new ApexCharts(
            document.querySelector("#productsCreatedChart"),
            {
                chart: { type: "line", height: 300 },
                series: [{
                    name: "Products Created",
                    data: data.createdCounts
                }],
                xaxis: { categories: data.days },
                colors: ["#008FFB"],
                stroke: { curve: "smooth" }
            }
        );
        createdChart.render();


        // -----------------------------
        // 2) Total Quantity Per Day
        // -----------------------------
         productsQtyChart = new ApexCharts(
            document.querySelector("#productsQtyChart"),
            {
                chart: { type: "area", height: 300 },
                series: [{
                    name: "Total Quantity",
                    data: data.qtyTotals
                }],
                xaxis: { categories: data.days },
                colors: ["#00E396"],
                stroke: { curve: "smooth" },
                fill: { opacity: 0.3 }
            }
        );
        productsQtyChart.render();


        // -----------------------------
        // 3) Active vs Inactive Products
        // -----------------------------
         productsActiveChart = new ApexCharts(
            document.querySelector("#productsActiveChart"),
            {
                chart: { type: "pie", height: 300 },
                series: [
                    parseInt(data.active.active_count),
                    parseInt(data.active.inactive_count)
                ],
                labels: ["Active Products", "Inactive Products"],
                colors: ["#FEB019", "#FF4560"]
            }
        );
        productsActiveChart.render();
    });



    const socket = io("http://localhost:3000");

    socket.on("order-updated", (data) => {

          fetch("./orders-report.php")
          .then(res => res.json())
          .then(data => {
            
              let summation = 0;

              data.counts.forEach(el => {
                summation += el;
              });

              let totalPrice = 0;
              let profits = 0;

              data.revenues.forEach(el => {
                totalPrice += el;
              });

              profits = totalPrice * 0.10;

              document.getElementById('ordersCount').textContent = Number(summation).toFixed(2);
              document.getElementById('totalCash').textContent = Number(totalPrice).toFixed(2);
              document.getElementById('Profits').textContent = Number(profits).toFixed(2);
              
          });


    if (!ordersChart || !ordersChart.w || !ordersChart.w.globals) {
        console.warn("Orders chart not ready yet.");
        return;
    }

    // normalize date
    let orderDate = data.created_at.split(" ")[0];

    const categories = ordersChart.w.globals.labels;   // safer than categories
    const series = ordersChart.w.globals.series;

    const dayIndex = categories.indexOf(orderDate);

    if (dayIndex !== -1) {
        // update existing day
        const newSeries = series[0].map((val, idx) =>
            idx === dayIndex ? val + 1 : val
        );

        ordersChart.updateSeries([
            { name: "Orders", data: newSeries },
            { name: "Revenue (JOD)", data: series[1] } // untouched
        ]);
    } else {
        // append new day
        ordersChart.updateOptions({
            xaxis: { categories: [...categories, orderDate] }
        });

        ordersChart.updateSeries([
            { name: "Orders", data: [...series[0], 1] },
            { name: "Revenue (JOD)", data: [...series[1], data.total_price] }
        ]);
    }
});


socket.on("product-updated", (data) => {
    const { new_quantity } = data;

    fetch("./products-report.php")
        .then(res => res.json())
        .then(newData => {
            productsQtyChart.updateSeries([
                { data: newData.qtyTotals }
            ]);

            productsQtyChart.updateOptions({
                xaxis: { categories: newData.days }
            });
        });
});

socket.on("product-updated", () => {
    fetch("./products-report.php")
        .then(res => res.json())
        .then(newData => {

          document.getElementById('productsCount').textContent = `${newData.active.active_count}`

            productsActiveChart.updateSeries([
                parseInt(newData.active.active_count),
                parseInt(newData.active.inactive_count)
            ]);
        });
});

</script>




  </body>
</html>