<?php

session_start();
include "../Connect.php";

$B_ID = $_SESSION['B_ID'] ?? null;

$product_name = $_POST['product_name'] ?? null;

$selected_category_id = $_GET['category_id'] ?? null;
$selected_sub_category_id = $_GET['sub_category_id'] ?? null;

$cart_count = 0;
if ($B_ID) {
    $sql1 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
    $row1 = mysqli_fetch_array($sql1);
    $cart_count = $row1['cart_count'] ?? 0;
}
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Inventrack</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">


    <link href="../assets/img/icon.png" rel="icon" />
    <link href="../assets/img/icon.png" rel="apple-touch-icon" />

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <style>
  .love-icon{
    position:absolute; top:10px; right:10px;
    color:red; z-index:10; font-size:20px; cursor:pointer;
  }
  .not-fav{
    position:absolute; top:10px; right:10px;
    color:white; z-index:10; font-size:20px; cursor:pointer;
    text-shadow: 0 0 2px #000; /* so it shows on light images */
  }
  .out-of-stock-badge {
  display: inline-block;
  margin-top: 6px;
  padding: 4px 10px;

  border: 1.5px solid #e63946;     /* red border */
  background-color: #fde2e4;       /* light pink background */
  color: #e63946;                  /* red text */

  font-size: 13px;
  font-weight: 600;
  border-radius: 20px;
  text-align: center;
}

</style>

    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar start -->
        <div class="container-fluid fixed-top">
       
            <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
    <img src="../assets/img/Logo.png" class="logo-inventrack" alt="Inventrack">
</a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                                   <div class="navbar-nav mx-auto">
                            <a href="index.php" class="nav-item nav-link active">Home</a>
                            <a href="Products.php" class="nav-item nav-link">Products</a>
                            <a href="Sellers.php" class="nav-item nav-link">Sellers</a>
                            <?php if ($B_ID) {?>
                                <a href="Favorites.php" class="nav-item nav-link">Favorites</a>
                                <?php }?>
                            <?php if ($B_ID) {?>
                                <a href="Orders.php" class="nav-item nav-link">Orders</a>
                            <?php }?>
<?php if (! $B_ID) {?>
                            <a href="../Login.php" class="nav-item nav-link">Login</a>
                            <?php } else {?>
                                <a href="./Logout.php" class="nav-item nav-link">Logout</a>
                            <?php }?>
                        </div>
                        <?php if ($B_ID) {?>

<div class="d-flex m-3 me-0">
    <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
    <a href="./Cart.php" class="position-relative me-4 my-auto">
        <i class="fa fa-shopping-bag fa-2x"></i>
        <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;"><?php echo $cart_count ?></span>
    </a>
    <a href="./Profile.php" class="my-auto">
        <i class="fas fa-user fa-2x"></i>
    </a>
</div>

<?php }?>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <form class="w-75 mx-auto d-flex" action="./Products.php" method="POST">
                                <input type="search" class="form-control p-3" name="product_name" placeholder="product name" aria-describedby="search-icon-1">
                                <button type="submit" id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Products</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="./index.php" style="color: #fff !important;">Home</a></li>
                <li class="breadcrumb-item active text-white" style="color: #fff !important;">Products</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <h1 class="mb-4">Inventrack</h1>
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <div class="col-xl-3">

                            <form action="./Products.php" method="POST">

                                <div class="input-group w-100 mx-auto d-flex">
                                    <input type="search" class="form-control p-3" name="product_name" placeholder="keywords" aria-describedby="search-icon-1">
                                    <button type="submit" id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></button>
                                </div>

                            </form>
                            </div>
                            <div class="col-3"></div>
                            <div class="col-3">
                            <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                                    <label for="fruits">Rating:</label>
                                    <select id="rating" name="rating" class="border-0 form-select-sm bg-light me-3" form="fruitform">
                                        <option value="all">All</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                                    <label for="fruits">Default Sorting:</label>
                                    <select id="fruits" name="fruitlist" class="border-0 form-select-sm bg-light me-3" form="fruitform">
                                        <option value="nothing">Nothing</option>
                                        <option value="popularity">Popularity</option>
                                        <option value="price">High price to low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-3">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <h4>Categories</h4>
                                            <ul class="list-unstyled fruite-categorie">
                                            <?php
                                                $sql1 = mysqli_query($con, "SELECT * from categories WHERE active = 1");

                                                while ($row1 = mysqli_fetch_array($sql1)) {

                                                    $category_id   = $row1['id'];
                                                    $category_name = $row1['name'];

                                                    $sql2 = mysqli_query($con, "SELECT COUNT(id) AS products_count from products WHERE active = 1 AND category_id = '$category_id'");
                                                    $row2 = mysqli_fetch_array($sql2);

                                                    $products_count = $row2['products_count'];

                                                ?>
                                                <li>
                                                    <div class="d-flex justify-content-between fruite-name category-link" id="<?php echo $category_id ?>">
                                                        <a href="./Products.php?category_id=<?php echo $category_id ?>" ><?php echo $category_name ?></a>
                                                        <span>(<?php echo $products_count ?>)</span>
                                                    </div>
                                                </li>

                                 <?php }?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <h4 class="mb-2">Price</h4>




                                            <!-- <input type="range" class="form-range w-100 price-change" id="rangeInput" name="rangeInput" min="0" max="500" value="0" oninput="rangeShowVal(this.value)">
                                            <output id="amount" name="amount" min-velue="0" max-value="500" for="rangeInput">0</output> -->


                                            <label for="min_price">Minimum Price</label>
                                            <input type="number" id="min_price" min="0" name="min price" class="form-control mb-2">
                                            <label for="min_price">Max Price</label>
                                            <input type="number" id="max_price" min="0" name="max price" class="form-control">





                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <h4>Sub Categories</h4>
                                            <ul class="list-unstyled fruite-categorie" id="sub_categories_div">




                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <h4 class="mb-3">Top Rated Sellers</h4>

                                        <?php
                                            $sql1 = mysqli_query($con, "SELECT * from users WHERE active = 1 AND user_type_id = 2 AND total_rate >= 3.5");

                                            while ($row1 = mysqli_fetch_array($sql1)) {

                                                $seller_id         = $row1['id'];
                                                $seller_name       = $row1['name'];
                                                $seller_image      = $row1['image'];
                                                $seller_total_rate = $row1['total_rate'];

                                            ?>
                                        <div class="d-flex align-items-center justify-content-start">
                                            <div class="rounded me-4" style="width: 100px; height: 100px;">
                                                <img src="../Seller_Dashboard/<?php echo $seller_image ?>" class="img-fluid rounded" alt="">
                                            </div>
                                            <div>
                                                <a href="./Seller.php?seller_id=<?php echo $seller_id ?>"><h6 class="mb-2"><?php echo $seller_name ?></h6></a>
                                                <div class="d-flex mb-2">
                                                    <?php for ($ii = 1; $ii < $seller_total_rate; $ii++) {?>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <?php }?>
                                                </div>
                                                <div class="d-flex mb-2">
                                                    <h5 class="fw-bold me-2"></h5>
                                                    <h5 class="text-danger text-decoration-line-through"></h5>
                                                </div>
                                            </div>
                                        </div>
<?php }?>


                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="row g-4 justify-content-center" id="products_div">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fruits Shop End-->


        <?php require './Footer.php'?>



        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>


    <script>
const BUYER_ID = <?php echo json_encode($B_ID); ?>;

const addToFav = (buyerId, productId) => {
  fetch(`./AddToFavorite.php?buyer_id=${buyerId}&product_id=${productId}`)
    .then(r => r.json())
    .then(data => {
      if (data.error) {
        alert(data.message || "Error");
        return;
      }

      const icon = document.getElementById(`icon-${productId}`);
      if (!icon) return;

      if (data.action === "added") {
        icon.classList.remove("not-fav");
        icon.classList.add("love-icon");
      } else if (data.action === "removed") {
        icon.classList.remove("love-icon");
        icon.classList.add("not-fav");
      }
    })
    .catch(err => {
      console.error(err);
      alert("Something went wrong.");
    });
};

$(function(){
  // ——— 1) Initialize filters (with any PHP‐supplied defaults) ———
  const filters = {
     buyer_id: <?php echo json_encode($B_ID ?? 0); ?>,
    category_id:     <?php echo json_encode($selected_category_id); ?> || null,
    sub_category_id: <?php echo json_encode($selected_sub_category_id); ?> || null,

    product_name:    <?php echo json_encode($product_name); ?>     || null,
    filter:          null,
    rating:          null,
    min:             null,
    max:             null
  };

  // ——— 2) Core fetch & render functions ———
  function fetchProducts() {
    $.getJSON('GetProducts.php', filters)
      .done(renderProducts)
      .fail((jq,X,t) => console.error('Products error:', t));
  }

  function renderProducts(products) {
  const $out = $('#products_div').empty();

  products.forEach((p) => {
    const heartClass = (parseInt(p.is_favorite) === 1) ? 'love-icon' : 'not-fav';

    $out.append(`
      <div class="col-md-6 col-lg-6 col-xl-4">
        <div class="rounded position-relative fruite-item">

          ${filters.buyer_id ? `
            <i id="icon-${p.id}"
               class="fa fa-heart ${heartClass}"
               onclick="addToFav(${filters.buyer_id}, ${p.id})"></i>
          ` : ''}


            <div class="fruite-img">
              <img src="../Seller_Dashboard/${p.image}"
                   class="img-fluid w-100 rounded-top"
                   style="height:220px;" alt="">
            </div>
            <div class="text-white background-sec px-3 py-1 rounded position-absolute"
                 style="top:10px;left:10px;">
              ${p.category_name}
            </div>
            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
              <h4>${p.name.substring(0, 15) + '...'}</h4>
              <p>${(p.description || '').substring(0, 30)}</p>

                          
              <div class="d-flex justify-content-between flex-lg-wrap">
                <p class="text-dark fs-5 fw-bold mb-0">${p.price} JODs</p>
                <a href="./Product.php?product_id=${p.id}"
                   class="btn border border-secondary rounded-pill px-3 text-primary">
                  View Product
                </a>
              </div>
                ${p.out_of_stock == 1 ? `<span class="out-of-stock-badge">Out Of Stock</span>` : ''}

            </div>
          </div>
        </div>
      `);
    });
  }

  function fetchSubCategories(catId) {
    $.getJSON('GetSubCategories.php', { category_id: catId })
      .done(renderSubCategories)
      .fail((jq,X,t) => console.error('Subs error:', t));
  }

  function renderSubCategories(cats) {
    const $list = $('#sub_categories_div').empty();
    cats.forEach(c => {
      $list.append(`
        <li>
          <div class="d-flex justify-content-between fruite-name sub-category-link">
            <a href="./Products.php?sub_category_id=${c.id}">${c.name}</a>
            <span>(${c.count})</span>
          </div>
        </li>
      `);
    });
  }

  // ——— 3) Wire up all inputs & controls ———
  // Price sliders/inputs
  $('#min_price, #max_price').on('input', function(){
    filters.min = parseFloat($('#min_price').val()) || null;
    filters.max = parseFloat($('#max_price').val()) || null;
    fetchProducts();
  });

  // Rating dropdown
  $('#rating').on('change', function(){
    const v = $(this).val();
    filters.rating = (v && v !== 'all') ? parseFloat(v) : null;
    fetchProducts();
  });

  // Default sorting dropdown
  $('#fruits').on('change', function(){
    const v = $(this).val();
    filters.filter = (v && v !== 'nothing') ? v : null;
    fetchProducts();
  });

  // Category links
  $('.category-link a').on('click', function(e){
    e.preventDefault();
    const cid = new URL(this.href, location.origin).searchParams.get('category_id');
    filters.category_id     = cid;
    filters.sub_category_id = null;    // reset subcategory
    fetchProducts();
    fetchSubCategories(cid);
  });

  // Sub‐category links (delegated)
  $(document).on('click', '.sub-category-link a', function(e){
    e.preventDefault();
    const scid = new URL(this.href, location.origin).searchParams.get('sub_category_id');
    filters.sub_category_id = scid;
    fetchProducts();
  });

  // Keyword search (modal and main bar)
  $('form[action="./Products.php"]').on('submit', function(e){
    e.preventDefault();
    const val = $(this).find('input[name="product_name"]').val().trim();
    filters.product_name = val.length ? val : null;
    fetchProducts();
    $('#searchModal').modal('hide');  // if inside modal
  });

  // ——— 4) Kick things off ———
  fetchProducts();
  if (filters.category_id) {
    fetchSubCategories(filters.category_id);
  }
});
</script>

   <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>