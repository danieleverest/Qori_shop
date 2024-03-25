<?php
$pageTitle = "Zay Shop - Product Listing Page";
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../components/admincss.php'; ?>

<?php
include "../config/db.php";
?>

<body>
    <?php include './header.php'; ?>

    <div class="modal fade" id="addNewItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="productmanage.php" method="post" enctype="multipart/form-data" class="border-secondary">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product_name" class="col-form-label">Product Name:</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="col-form-label">Price:</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">$</span>
                                <input name="price" type="number" id="price" class="form-control"
                                    aria-label="Amount (to the nearest dollar)">
                            </div>
                            <!-- <input type="number" class="form-control" id="product_name" required min="1"> -->
                        </div>
                        <div class="mb-3">
                            <label for="category" class="col-form-label">Category:</label>
                            <select class="form-select" id="category1" name="category1" aria-label="Category1" required>
                                <?php
                                $sql = "SELECT * FROM categories";
                                $result = mysqli_query($conn, $sql) or die ('Database query error!');

                                // Initialize an array to hold categories grouped by parent IDs
                                $categoriesByParent = array();

                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through the categories and group them by parent ID
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $category_id = $row['id'];
                                        $category = $row['category'];
                                        $parentId = $row['parentId'];

                                        // If parentId is 0, it's a parent category
                                        if ($parentId == 0) {
                                            // Add the parent category to the categoriesByParent array
                                            $categoriesByParent[$category_id]['name'] = $category;
                                        } else {
                                            // If parentId is not 0, it's a child category
                                            // Check if the parent category exists in categoriesByParent array
                                            if (isset ($categoriesByParent[$parentId])) {
                                                // Add the child category to the parent category's children array
                                                $categoriesByParent[$parentId]['children'][] = array('id' => $category_id, 'name' => $category);
                                            }
                                        }
                                    }

                                    // Loop through the grouped categories and output optgroup labels and options
                                    foreach ($categoriesByParent as $parentCategoryId => $parentCategory) {
                                        $parentCategoryName = $parentCategory['name'];
                                        // Output the optgroup label for parent category
                                        echo "<optgroup label='{$parentCategoryName}'>";

                                        // Output the options within the optgroup
                                        if (isset ($parentCategory['children'])) {
                                            foreach ($parentCategory['children'] as $childCategory) {
                                                echo "<option value='{$childCategory['id']}'>{$childCategory['name']}</option>";
                                            }
                                        }

                                        // Close the optgroup
                                        echo "</optgroup>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="col-form-label">Description:</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image">Image:</label>
                            <input type="file" name="image" id="image" accept="image/*" value="" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input class="btn btn-secondary btn-success" type="submit" name="addproduct"
                            value="Add New Product">
                        <!-- <button type="button" class="btn btn-primary">Send message</button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3 flex-grow-1 d-flex flex-column flex-sm-row overflow-auto pt-4">
        <div class="row flex-grow-sm-1 flex-grow-1">
            <aside class="col-sm-3 flex-grow-sm-1 flex-shrink-1 flex-grow-0 sticky-top pb-sm-0 pb-3">
                <div class="bg-light border rounded-3 p-1 h-100 sticky-top">
                    <ul class="nav nav-pills flex-sm-column flex-row mb-auto justify-content-between text-truncate">
                        <li class="nav-item ms-5">
                            <a href="./" class="nav-link px-2 text-truncate">
                                <i class="bi bi-house fs-5"></i>
                                <span class="d-none d-sm-inline text-secondary">Home</span>
                            </a>
                        </li>
                        <hr />
                        <li class="ms-5">
                            <a href="category.php" class="nav-link px-2 text-truncate">
                                <i class="bi bi-speedometer fs-5"></i>
                                <span class="d-none d-sm-inline text-secondary">Catetory</span>
                            </a>
                        </li>
                        <li class="ms-5">
                            <a href="#" class="nav-link px-2 text-truncate"><i class="bi bi-bricks fs-5"></i>
                                <span class="d-none d-sm-inline text-secondary">Products</span> </a>
                        </li>
                        <!-- <li class="ms-5">
                            <a href="#" class="nav-link px-2 text-truncate"><i class="bi bi-people fs-5"></i>
                                <span class="d-none d-sm-inline text-secondary">Customers</span> </a>
                        </li> -->
                    </ul>
                </div>
            </aside>
            <main class="col overflow-auto h-100">
                <div class="bg-light border rounded-3 p-3">
                <?php
                    if (isset ($_GET['successMessage'])) {
                        $successMessage = $_GET['successMessage'];
                        echo "
                            <div class='offset-sm-3 col-sm-6'>
                                <div id='successMessage' class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>$successMessage</strong>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            </div>
                            <script>
                                setTimeout(function() {
                                    document.getElementById('successMessage').style.display = 'none';
                                    // Remove the successMessage parameter from the URL
                                    var url = window.location.href;
                                    if (url.includes('?successMessage=')) {
                                        var newUrl = url.split('?')[0];
                                        window.history.replaceState({}, document.title, newUrl);
                                    }
                                }, 5000);
                            </script>";

                    }
                    ?>
                    <?php
                    if (isset ($_GET['errorMessage'])) {
                        $errorMessage = $_GET['errorMessage'];
                        echo "
                            <div id='errorMessageContainer' class='offset-sm-3 col-sm-6'>
                                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                    <strong>$errorMessage</strong>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            </div>
                            <script>
                                // Automatically hide the error message after 5 seconds
                                setTimeout(function() {
                                    document.getElementById('errorMessageContainer').style.display = 'none';
                                }, 5000);
                            </script>";
                    }
                    ?>
                    <div class="main-content">
                        <div class="container mt-5">
                            <!-- Table -->
                            <h2 class="mb-3">Products</h2>
                            <div class="row">
                                <!-- Dark table -->
                                <div class="row mt-5">
                                    <div class="col">
                                        <div class="card bg-default shadow">
                                            <div class="card-header bg-transparent border-0">
                                                <div class="row justify-content-between">
                                                    <h3 class="text-white mb-0 col-md-9">Card tables</h3>
                                                    <button type="button" class="btn btn-outline-info col-md-3"
                                                        data-bs-toggle="modal" data-bs-target="#addNewItem"
                                                        data-bs-whatever="@mdo"><i class="fa fa-plus"></i>Add New
                                                        Item</button>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table align-items-center table-dark table-flush">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">Image</th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col">Category</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        // Fetch categories from the database
                                                        $sql = "SELECT * FROM products";
                                                        $result = mysqli_query($conn, $sql);

                                                        if (mysqli_num_rows($result) > 0) {
                                                            // Loop through the categories and display them
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $product = $row['product_name'];
                                                                $product_image = $row['image'];
                                                                $product_id = $row['id'];
                                                                echo "
                                                                <tr>
                                                                    
                                                                    <th scope='row'>
                                                                        <div class='media align-items-center'>
                                                                            <a href='#' class='avatar rounded-circle mr-3'>
                                                                                <img alt='Image placeholder'
                                                                                    src='uploads/$product_image'>
                                                                            </a>
                                                                        </div>
                                                                    </th>
                                                                    <td>
                                                                        <div class='media-body'>
                                                                            <span class='mb-0 text-sm' edit-product-id='{$product_id}'>$product</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span class='badge badge-dot mr-4'>
                                                                            <i class='bg-warning'></i> $2,500 USD
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <a class='btn btn-sm btn-icon-only text-primary edit-product' href='#' role='button' 
                                                                        data-bs-toggle='modal' data-bs-target='#editItem'
                                                                        data-bs-whatever='@mdo' data-product-id='{$product_id}' edit-product='{$product}'>
                                                                            <i class='fas fa-edit'></i>
                                                                        </a>
                                                                        <a class='btn btn-sm btn-icon-only text-danger delete-product' href='#' role='button' data-product-id='{$product_id}'>
                                                                            <i class='fas fa-trash-alt'></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>";
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='2'>No categories found.</td></tr>";
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                Phone
                                                                <!-- <div class="d-flex align-items-center">
                                                                    <span class="mr-2">60%</span>
                                                                    <div>
                                                                        <div class="progress">
                                                                            <div class="progress-bar bg-warning"
                                                                                role="progressbar" aria-valuenow="60"
                                                                                aria-valuemin="0" aria-valuemax="100"
                                                                                style="width: 60%;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div> -->
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-sm btn-icon-only text-primary"
                                                                    href="#" role="button">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a class="btn btn-sm btn-icon-only text-danger" href="#"
                                                                    role="button">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a>
                                                                <!-- <button type="button" class="btn btn-outline-primary"><i
                                                                        class="far fa-edit"></i></button>
                                                                <button type="button" class="btn btn-outline-danger"><i
                                                                        class="far fa-trash-alt"></i></button> -->
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="card-footer py-4">
                                            <nav aria-label="...">
                                                <ul class="pagination justify-content-end mb-0">
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1">
                                                            <i class="fas fa-angle-left"></i>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                    </li>
                                                    <li class="page-item active">
                                                        <a class="page-link" href="#">1</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">2 <span
                                                                class="sr-only">(current)</span></a>
                                                    </li>
                                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">
                                                            <i class="fas fa-angle-right"></i>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Start Content -->
                        <div class="container py-5">
                            <div class="row">

                                <div class="col-lg-3">
                                    <h1 class="h2 pb-4">Categorias</h1>
                                    <ul class="list-unstyled templatemo-accordion">
                                        <li class="pb-3">
                                            <a class="collapsed d-flex justify-content-between h3 text-decoration-none"
                                                href="#">
                                                Marca
                                                <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                                            </a>
                                            <ul class="collapse show list-unstyled pl-3">
                                                <li><a class="text-decoration-none" href="#">Samsumg</a></li>
                                                <li><a class="text-decoration-none" href="#">Apple</a></li>
                                            </ul>
                                        </li>
                                        <li class="pb-3">
                                            <a class="collapsed d-flex justify-content-between h3 text-decoration-none"
                                                href="#">
                                                Accesorios
                                                <i class="pull-right fa fa-fw fa-chevron-circle-down mt-1"></i>
                                            </a>
                                            <ul id="collapseTwo" class="collapse list-unstyled pl-3">
                                                <li><a class="text-decoration-none" href="#">Fundas</a></li>
                                                <li><a class="text-decoration-none" href="#">Cargadores</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-inline shop-top-menu pb-3 pt-1">
                                                <li class="list-inline-item">
                                                    <a class="h3 text-dark text-decoration-none mr-3" href="#">Todo</a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a class="h3 text-dark text-decoration-none mr-3"
                                                        href="#">Ofertas</a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a class="h3 text-dark text-decoration-none" href="#">Destacados</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6 pb-4">
                                            <div class="d-flex">
                                                <select class="form-control">
                                                    <option>Orden de los productos</option>
                                                    <option>Mayor Precio ↑</option>
                                                    <option>Menor Precio ↓</option>
                                                    <option>A - Z</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_01.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_02.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_03.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_04.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_05.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_06.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_07.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_08.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card mb-4 product-wap rounded-0">
                                                <div class="card rounded-0">
                                                    <img class="card-img rounded-0 img-fluid"
                                                        src="../assets/img/shop_09.jpg">
                                                    <div
                                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                                        <ul class="list-unstyled">
                                                            <li><a class="btn btn-success text-white"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-heart"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="far fa-eye"></i></a>
                                                            </li>
                                                            <li><a class="btn btn-success text-white mt-2"
                                                                    href="shop-single.php"><i
                                                                        class="fas fa-cart-plus"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <a href="shop-single.php" class="h3 text-decoration-none">Oupidatat
                                                        non</a>
                                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                                        <li>M/L/X/XL</li>
                                                        <li class="pt-2">
                                                            <span
                                                                class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                            <span
                                                                class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                                        <li>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-warning fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                            <i class="text-muted fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="text-center mb-0">$250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div div="row">
                                        <ul class="pagination pagination-lg justify-content-end">
                                            <li class="page-item disabled">
                                                <a class="page-link active rounded-0 mr-3 shadow-sm border-top-0 border-left-0"
                                                    href="#" tabindex="-1">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link rounded-0 mr-3 shadow-sm border-top-0 border-left-0 text-dark"
                                                    href="#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link rounded-0 shadow-sm border-top-0 border-left-0 text-dark"
                                                    href="#">3</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End Content -->
                    </div>
                </div>
            </main>
        </div>
    </div>


    <?php include '../components/footer.php'; ?>

    <?php include '../components/externaljs.php'; ?>

</body>

</html>