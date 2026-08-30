<?php
// Aside.php notifications (Seller Dashboard)

// If the page that includes Aside.php already has these, this won't hurt:
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($con)) include "../Connect.php";

$S_ID = $_SESSION['S_Log'] ?? null;

$reorderCount = 0;
$newOrdersCount = 0;

if ($S_ID) {
    // 1) Reorder Stock count (out of stock products for THIS seller)
    $stmt = $con->prepare("
        SELECT COUNT(*) 
        FROM products 
        WHERE seller_id = ?
          AND (qty <= 0 OR out_of_stock = 1)
    ");
    $stmt->bind_param("i", $S_ID);
    $stmt->execute();
    $stmt->bind_result($reorderCount);
    $stmt->fetch();
    $stmt->close();

    // 2) New Orders count (orders containing THIS seller's items + status_id = 1)
    $stmt2 = $con->prepare("
        SELECT COUNT(DISTINCT o.id)
        FROM orders o
        INNER JOIN order_items oi ON oi.order_id = o.id
        WHERE oi.seller_id = ?
          AND o.status_id = 1
    ");
    $stmt2->bind_param("i", $S_ID);
    $stmt2->execute();
    $stmt2->bind_result($newOrdersCount);
    $stmt2->fetch();
    $stmt2->close();
}
?>



<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="./Account.php">
        <i class="bi bi-file-person"></i><span>Account</span>
      </a>
    </li>
        
    <li class="nav-item">
      <a class="nav-link collapsed" href="./Products.php">
        <i class="bi bi-box-seam"></i><span>Products</span>
      </a>
    </li>
        
    
    
  <li class="nav-item">
  <a class="nav-link collapsed" href="./Reorder.php">
    <i class="bi bi-plus-circle"></i>
    <span>Reorder Stock</span>

    <?php if ($reorderCount > 0) { ?>
      <span class="badge bg-danger ms-2"><?php echo $reorderCount; ?></span>
    <?php } ?>
  </a>
</li>


    
    <li class="nav-item">
      <a class="nav-link collapsed" href="./Subscriptions.php">
        <i class="bi bi-calendar-week"></i><span>Subscriptions</span>
      </a>
    </li>

    <li class="nav-item">
  <a class="nav-link collapsed" href="./Orders.php">
    <i class="bi bi-cart-check"></i>
    <span>Orders</span>

    <?php if ($newOrdersCount > 0) { ?>
      <span class="badge bg-warning text-dark ms-2"><?php echo $newOrdersCount; ?></span>
    <?php } ?>
  </a>
</li>

    <!-- <li class="nav-item">
      <a class="nav-link collapsed" href="./Offers.php">
        <i class="bi bi-percent"></i><span>Offers</span>
      </a>
    </li> -->



  </ul>
</aside>
