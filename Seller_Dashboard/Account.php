<?php
session_start();

include "../Connect.php";

// Must be logged in as seller
if (!isset($_SESSION['S_Log']) || empty($_SESSION['S_Log'])) {

    echo '<script language="JavaScript">
        document.location="../Login.php";
    </script>';

    exit;
}

$S_ID = (int) $_SESSION['S_Log'];


// =========================
// LOAD SELLER INFO
// =========================
$sql1 = mysqli_query(
    $con,
    "SELECT name, email, image, phone, description, instagram_link
     FROM users
     WHERE id = '$S_ID'
     LIMIT 1"
);

if ($sql1 && mysqli_num_rows($sql1) > 0) {

    $row1 = mysqli_fetch_assoc($sql1);

    $name           = $row1['name'] ?? '';
    $email          = $row1['email'] ?? '';
    $image          = $row1['image'] ?? '';
    $phone          = $row1['phone'] ?? '';
    $description    = $row1['description'] ?? '';
    $instagram_link = $row1['instagram_link'] ?? '';

} else {

    session_destroy();

    echo '<script language="JavaScript">
        document.location="../Login.php";
    </script>';

    exit;
}


// =========================
// UPDATE SELLER PROFILE
// =========================
if (isset($_POST['Submit'])) {

    $name           = trim($_POST['name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $description    = trim($_POST['description'] ?? '');
    $instagram_link = trim($_POST['instagram_link'] ?? '');

    $newImage = $_FILES["file"]["name"] ?? '';


    // If seller uploaded a new image
    if (!empty($newImage)) {

        $imagePath = 'Sellers_Images/' . basename($newImage);

        $stmt = $con->prepare(
            "UPDATE users
             SET name = ?,
                 phone = ?,
                 email = ?,
                 image = ?,
                 instagram_link = ?,
                 description = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssssi",
            $name,
            $phone,
            $email,
            $imagePath,
            $instagram_link,
            $description,
            $S_ID
        );

        if ($stmt->execute()) {

            move_uploaded_file(
                $_FILES["file"]["tmp_name"],
                "./Sellers_Images/" . basename($newImage)
            );

            echo "<script language='JavaScript'>
                alert('Account Updated Successfully!');
                document.location='./Account.php';
            </script>";

            exit;
        }

    } else {

        // No new image uploaded
        $stmt = $con->prepare(
            "UPDATE users
             SET name = ?,
                 phone = ?,
                 email = ?,
                 instagram_link = ?,
                 description = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssssi",
            $name,
            $phone,
            $email,
            $instagram_link,
            $description,
            $S_ID
        );

        if ($stmt->execute()) {

            echo "<script language='JavaScript'>
                alert('Account Updated Successfully!');
                document.location='./Account.php';
            </script>";

            exit;
        }
    }
}


// =========================
// CHANGE PASSWORD
// =========================
if (isset($_POST['ChangePassword'])) {

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Get current hashed password
    $stmtPassword = $con->prepare(
        "SELECT password
         FROM users
         WHERE id = ?
         LIMIT 1"
    );

    $stmtPassword->bind_param("i", $S_ID);
    $stmtPassword->execute();

    $passwordResult = $stmtPassword->get_result();
    $sellerPassword = $passwordResult->fetch_assoc();


    // Verify current password
    if (
        !$sellerPassword ||
        !password_verify(
            $currentPassword,
            $sellerPassword['password']
        )
    ) {

        echo "<script>
            alert('Current password is incorrect.');
        </script>";

    } elseif ($newPassword !== $confirmPassword) {

        echo "<script>
            alert('New passwords do not match.');
        </script>";

    } elseif (strlen($newPassword) < 4) {

        echo "<script>
            alert('New password must be at least 4 characters.');
        </script>";

    } else {

        $hashedPassword = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        $updatePassword = $con->prepare(
            "UPDATE users
             SET password = ?
             WHERE id = ?"
        );

        $updatePassword->bind_param(
            "si",
            $hashedPassword,
            $S_ID
        );

        if ($updatePassword->execute()) {

            echo "<script>
                alert('Password changed successfully!');
                document.location='./Account.php';
            </script>";

            exit;

        } else {

            die(
                "Password update failed: " .
                $updatePassword->error
            );
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />

    <meta
        content="width=device-width, initial-scale=1.0"
        name="viewport"
    />

    <title>Account - Inventrack</title>

    <meta content="" name="description" />
    <meta content="" name="keywords" />


    <!-- Favicons -->
    <link
        href="../assets/img/icon.png"
        rel="icon"
    />

    <link
        href="../assets/img/icon.png"
        rel="apple-touch-icon"
    />


    <!-- Google Fonts -->
    <link
        href="https://fonts.gstatic.com"
        rel="preconnect"
    />

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

    <link
        href="../assets/vendor/boxicons/css/boxicons.min.css"
        rel="stylesheet"
    />

    <link
        href="../assets/vendor/quill/quill.snow.css"
        rel="stylesheet"
    />

    <link
        href="../assets/vendor/quill/quill.bubble.css"
        rel="stylesheet"
    />

    <link
        href="../assets/vendor/remixicon/remixicon.css"
        rel="stylesheet"
    />

    <link
        href="../assets/vendor/simple-datatables/style.css"
        rel="stylesheet"
    />


    <!-- Template Main CSS File -->
    <link
        href="../assets/css/style.css"
        rel="stylesheet"
    />

</head>


<body>


<style>

/* Make header logo bigger */
.header .logo img {
    height: 75px !important;
    width: auto !important;
    margin-bottom: 0px;
}

/* Spacing around the logo */
.header .logo {
    display: flex;
    align-items: center;
    gap: 0px;
}

</style>


<!-- ======= Header ======= -->
<header
    id="header"
    class="header fixed-top d-flex align-items-center"
>

    <div
        class="d-flex align-items-center justify-content-between"
    >

        <a
            href="index.php"
            class="logo d-flex align-items-center"
        >

            <img
                src="../assets/img/Logo.png"
                alt="Inventrack"
            />

        </a>

    </div>


    <nav class="header-nav ms-auto">

        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown pe-3">

                <a
                    class="nav-link nav-profile d-flex align-items-center pe-0"
                    href="#"
                    data-bs-toggle="dropdown"
                >

                    <img
                        src="<?php echo htmlspecialchars($image); ?>"
                        alt="Profile"
                        class="rounded-circle"
                    />

                    <span
                        class="d-none d-md-block dropdown-toggle ps-2"
                    >
                        <?php echo htmlspecialchars($name); ?>
                    </span>

                </a>


                <ul
                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile"
                >

                    <li class="dropdown-header">

                        <h6>
                            <?php echo htmlspecialchars($name); ?>
                        </h6>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <li>

                        <a
                            class="dropdown-item d-flex align-items-center"
                            href="./Logout.php"
                        >

                            <i class="bi bi-box-arrow-right"></i>

                            <span>Sign Out</span>

                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </nav>

</header>
<!-- End Header -->


<!-- ======= Sidebar ======= -->
<?php require './Aside-Nav/Aside.php' ?>
<!-- End Sidebar -->


<main
    id="main"
    class="main"
>

    <div class="pagetitle">

        <h1>Account</h1>

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="index.php">
                        Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item">
                    Account
                </li>

            </ol>

        </nav>

    </div>


    <section class="section">

        <div class="row">

            <div
                class="col-lg-12 col-md-12 col-sm-12"
            >

                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title">
                            Account Information
                        </h5>


                        <!-- ======================= -->
                        <!-- SELLER PROFILE FORM -->
                        <!-- ======================= -->

                        <form
                            method="POST"
                            action="./Account.php"
                            enctype="multipart/form-data"
                        >


                            <div class="row mb-3">

                                <label
                                    for="name"
                                    class="col-sm-2 col-form-label"
                                >
                                    Name
                                </label>

                                <div class="col-sm-10">

                                    <input
                                        type="text"
                                        name="name"
                                        value="<?php echo htmlspecialchars($name); ?>"
                                        class="form-control"
                                        id="name"
                                        required
                                    />

                                </div>

                            </div>


                            <div class="row mb-3">

                                <label
                                    for="email"
                                    class="col-sm-2 col-form-label"
                                >
                                    Email
                                </label>

                                <div class="col-sm-10">

                                    <input
                                        type="email"
                                        name="email"
                                        value="<?php echo htmlspecialchars($email); ?>"
                                        class="form-control"
                                        id="email"
                                        required
                                    />

                                </div>

                            </div>


                            <div class="row mb-3">

                                <label
                                    for="phone"
                                    class="col-sm-2 col-form-label"
                                >
                                    Phone
                                </label>

                                <div class="col-sm-10">

                                    <input
                                        type="text"
                                        name="phone"
                                        value="<?php echo htmlspecialchars($phone); ?>"
                                        pattern="[0-9]{10}"
                                        title="Phone Number Must Be 10 Numbers"
                                        class="form-control"
                                        id="phone"
                                        required
                                    />

                                </div>

                            </div>


                            <div class="row mb-3">

                                <label
                                    for="instagram_link"
                                    class="col-sm-2 col-form-label"
                                >
                                    Instagram Link
                                </label>

                                <div class="col-sm-10">

                                    <input
                                        type="text"
                                        name="instagram_link"
                                        value="<?php echo htmlspecialchars($instagram_link); ?>"
                                        class="form-control"
                                        id="instagram_link"
                                    />

                                </div>

                            </div>


                            <div class="row mb-3">

                                <label
                                    for="description"
                                    class="col-sm-2 col-form-label"
                                >
                                    Description
                                </label>

                                <div class="col-sm-10">

                                    <textarea
                                        name="description"
                                        class="form-control"
                                        id="description"
                                    ><?php echo htmlspecialchars($description); ?></textarea>

                                </div>

                            </div>


                            <div class="row mb-3">

                                <label
                                    for="file"
                                    class="col-sm-2 col-form-label"
                                >
                                    Image
                                </label>

                                <div class="col-sm-10">

                                    <input
                                        type="file"
                                        name="file"
                                        class="form-control"
                                        id="file"
                                        accept="image/*"
                                    />

                                </div>

                            </div>


                            <div class="text-end">

                                <button
                                    type="submit"
                                    name="Submit"
                                    class="btn btn-primary"
                                >
                                    Save Profile
                                </button>


                                <button
                                    type="reset"
                                    class="btn btn-secondary"
                                >
                                    Reset
                                </button>

                            </div>

                        </form>


                        <!-- ======================= -->
                        <!-- CHANGE PASSWORD -->
                        <!-- ======================= -->

                        <hr class="my-5">


                        <h5 class="card-title">
                            Change Password
                        </h5>


                        <form
                            method="POST"
                            action="./Account.php"
                        >


                            <div class="row mb-3">

                                <label
                                    for="current_password"
                                    class="col-sm-2 col-form-label"
                                >
                                    Current Password
                                </label>

                                <div class="col-sm-10">

                                    <input
                                        type="password"
                                        name="current_password"
                                        class="form-control"
                                        id="current_password"
                                        required
                                    />

                                </div>

                            </div>


                            <div class="row mb-3">

                                <label
                                    for="new_password"
                                    class="col-sm-2 col-form-label"
                                >
                                    New Password
                                </label>

                                <div class="col-sm-10">

                                    <input
                                        type="password"
                                        name="new_password"
                                        class="form-control"
                                        id="new_password"
                                        minlength="4"
                                        required
                                    />

                                </div>

                            </div>


                            <div class="row mb-3">

                                <label
                                    for="confirm_password"
                                    class="col-sm-2 col-form-label"
                                >
                                    Confirm New Password
                                </label>

                                <div class="col-sm-10">

                                    <input
                                        type="password"
                                        name="confirm_password"
                                        class="form-control"
                                        id="confirm_password"
                                        minlength="4"
                                        required
                                    />

                                </div>

                            </div>


                            <div class="text-end">

                                <button
                                    type="submit"
                                    name="ChangePassword"
                                    class="btn btn-primary"
                                >
                                    Change Password
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- ======= Footer ======= -->
<footer
    id="footer"
    class="footer"
>

    <div class="copyright">

        &copy; Copyright

        <strong>
            <span>Inventrack</span>
        </strong>.

        All Rights Reserved

    </div>

</footer>
<!-- End Footer -->


<a
    href="#"
    class="back-to-top d-flex align-items-center justify-content-center"
>
    <i class="bi bi-arrow-up-short"></i>
</a>


<script>

window.addEventListener(
    'DOMContentLoaded',
    (event) => {

        const navItem = document.querySelector(
            '#sidebar-nav .nav-item:nth-child(2) .nav-link'
        );

        if (navItem) {
            navItem.classList.remove('collapsed');
        }
    }
);

</script>


<!-- Vendor JS Files -->
<script
    src="../assets/vendor/apexcharts/apexcharts.min.js"
></script>

<script
    src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"
></script>

<script
    src="../assets/vendor/chart.js/chart.umd.js"
></script>

<script
    src="../assets/vendor/echarts/echarts.min.js"
></script>

<script
    src="../assets/vendor/quill/quill.min.js"
></script>

<script
    src="../assets/vendor/simple-datatables/simple-datatables.js"
></script>

<script
    src="../assets/vendor/tinymce/tinymce.min.js"
></script>

<script
    src="../assets/vendor/php-email-form/validate.js"
></script>


<!-- Template Main JS File -->
<script
    src="../assets/js/main.js"
></script>

</body>

</html>