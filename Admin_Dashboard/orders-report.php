                <?php

                    session_start();

                    include "../Connect.php";

                    $sql = "
    SELECT
        DATE(created_at) AS order_day,
        COUNT(*) AS order_count,
        SUM(total_price) AS total_revenue
    FROM orders
    GROUP BY DATE(created_at)
    ORDER BY order_day ASC
";

                    $result = mysqli_query($con, $sql);

                    $days     = [];
                    $counts   = [];
                    $revenues = [];

                    while ($row = mysqli_fetch_assoc($result)) {
                        $days[]     = $row['order_day'];
                        $counts[]   = (int) $row['order_count'];
                        $revenues[] = (float) $row['total_revenue'];
                    }

                    echo json_encode([
                        "days"     => $days,
                        "counts"   => $counts,
                        "revenues" => $revenues,
                    ]);

                ?>