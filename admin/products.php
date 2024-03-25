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
                                    aria-label="Amount (to the nearest dollar)" required>
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
                            <input type="file" name="image" id="image" accept="image/*" value="" required />
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
                            setTimeout(function() {
                                document.getElementById('errorMessageContainer').style.display = 'none';
                                // Remove the errorMessageContainer parameter from the URL
                                var url = window.location.href;
                                if (url.includes('?errorMessage=')) {
                                    var newUrl = url.split('?')[0];
                                    window.history.replaceState({}, document.title, newUrl);
                                }
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
                                                            <th scope="col">Description</th>
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
                                                                $price = $row['price'];
                                                                $category_id = $row['category_id'];
                                                                $description = $row['description'];
                                                                $product_image = $row['image'];
                                                                $product_id = $row['id'];
                                                                $category_query = mysqli_query($conn, "SELECT * FROM categories WHERE id = $category_id");
                                                                $category_row = mysqli_fetch_assoc($category_query);
                                                                $category = $category_row['category']; // Retrieve the category name from the parent category query result
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
                                                                    <i class='bg-warning'></i> {$price} USD
                                                                    </span>
                                                                    </td>
                                                                    <td>
                                                                        <div class='media-body'>
                                                                            <span class='mb-0 text-sm'>$category</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class='media-body'>
                                                                            <span class='mb-0 text-sm'>$description</span>
                                                                        </div>
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
                    </div>
                </div>
            </main>
        </div>
    </div>


    <?php include '../components/footer.php'; ?>

    <?php include '../components/externaljs.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var editButtons = document.querySelectorAll('.edit-category');
            editButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var categoryId = button.getAttribute('data-product-id');
                    var categoryValue = button.getAttribute('edit-category');
                    document.getElementById('edit_category_id').value = categoryId;
                    document.getElementById('edit_category').value = categoryValue;
                    console.log(categoryId + categoryValue);
                });
            });

            var deleteButtons = document.querySelectorAll('.delete-product');

            deleteButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var categoryId = button.getAttribute('data-product-id');
                    if (confirm("Are you sure you want to delete this category?")) {
                        deleteCategory(categoryId);
                    }

                });
            });

            function deleteCategory(categoryId) {
                // Send an AJAX request to delete-product.php with the category ID
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // If deletion is successful, reload the page
                            // location.reload();
                            $successMessage = "Successfully Deleted!";
                            location.href = "products.php?successMessage=" + $successMessage;
                        } else {
                            // If there's an error, display an error message
                            console.error('Error deleting category: ' + xhr.status);
                        }
                    }
                };
                xhr.open('POST', 'delete-product.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('category_id=' + categoryId);
            }
        });
    </script>

</body>

</html>