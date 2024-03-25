<?php
$pageTitle = "Zay Shop - Category Manage Page";
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../components/admincss.php'; ?>
<?php
include "../config/db.php";
?>

<body>

    <?php include './header.php'; ?>

    <div class="modal fade" id="addNewItem1" tabindex="-1" aria-labelledby="addcategory1modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addcategory1modal">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="categorymanage.php" method="post" enctype="multipart/form-data" class="border-secondary">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category" class="col-form-label">Category:</label>
                            <input type="text" class="form-control" id="category" name="category" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn btn-secondary btn-success" type="submit" name="newcategory1"
                            value="Add New Item">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addNewItem2" tabindex="-1" aria-labelledby="addcategory2modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addcategory2modal">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="categorymanage.php" method="post" enctype="multipart/form-data" class="border-secondary">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category1" class="col-form-label">Category1:</label>
                            <select class="form-select" id="category1" name="category1" aria-label="Category1" required>
                                <?php
                                // Fetch categories from the database
                                $sql = "SELECT * FROM categories WHERE parentId = 0";
                                $result = mysqli_query($conn, $sql) or die ('Database query error!');

                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through the categories and display them
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $category = $row['category'];
                                        $category_id = $row['id'];
                                        echo "<option value='{$category_id}'>$category</option>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No categories found.</td></tr>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category2" class="col-form-label">Category2:</label>
                            <input type="text" class="form-control" id="category2" name="category2" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn btn-secondary btn-success" type="submit" name="newcategory2"
                            value="Add New Item">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="editcategory" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editcategory">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" action="categorymanage.php" method="post" enctype="multipart/form-data"
                    class="border-secondary">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_category" class="col-form-label">Category:</label>
                            <input type="text" class="form-control" id="edit_category" name="edit_category" required>
                            <input type="hidden" id="edit_category_id" name="edit_category_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn btn-secondary btn-success" type="submit" name="editcategory"
                            value="Edit Item">
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
                            <a href="products.php" class="nav-link px-2 text-truncate"><i class="bi bi-bricks fs-5"></i>
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
                                if (url.includes('?errorMessageContainer=')) {
                                    var newUrl = url.split('?')[0];
                                    window.history.replaceState({}, document.title, newUrl);
                                }
                            }, 5000);
                        </script>";
                    }
                    ?>
                    <div class="main-content row">
                        <div class="mt-5 col-md-6">
                            <!-- Table -->
                            <h2 class="mb-3">Category1</h2>
                            <div class="row">
                                <!-- Dark table -->
                                <div class="row mt-5">
                                    <div class="col">
                                        <div class="card bg-default shadow">
                                            <div class="card-header bg-transparent border-0">
                                                <div class="row justify-content-between">
                                                    <h3 class="text-white mb-0 col-md-9">Category1 tables</h3>
                                                    <button type="button" class="btn btn-outline-info col-md-3"
                                                        data-bs-toggle="modal" data-bs-target="#addNewItem1"
                                                        data-bs-whatever="@mdo"><i class="fa fa-plus"></i>Add New
                                                        Item</button>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table align-items-center table-dark table-flush">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">Name</th>
                                                            <th scope="col text-end">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        // Fetch categories from the database
                                                        $sql = "SELECT * FROM categories WHERE parentId = 0";
                                                        $result = mysqli_query($conn, $sql);

                                                        if (mysqli_num_rows($result) > 0) {
                                                            // Loop through the categories and display them
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $category = $row['category'];
                                                                $category_id = $row['id'];
                                                                echo "
                                                                <tr>
                                                                    <td>
                                                                        <div class='media-body'>
                                                                            <span class='mb-0 text-sm' edit-category-id='{$category_id}'>$category</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <a class='btn btn-sm btn-icon-only text-primary edit-category' href='#' role='button' 
                                                                        data-bs-toggle='modal' data-bs-target='#editItem'
                                                                        data-bs-whatever='@mdo' data-category-id='{$category_id}' edit-category='{$category}'>
                                                                            <i class='fas fa-edit'></i>
                                                                        </a>
                                                                        <a class='btn btn-sm btn-icon-only text-danger delete-category' href='#' role='button' data-category-id='{$category_id}'>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 col-md-6">
                            <!-- Table -->
                            <h2 class="mb-3">Category2</h2>
                            <div class="row">
                                <!-- Dark table -->
                                <div class="row mt-5">
                                    <div class="col">
                                        <div class="card bg-default shadow">
                                            <div class="card-header bg-transparent border-0">
                                                <div class="row justify-content-between">
                                                    <h3 class="text-white mb-0 col-md-9">Category2 tables</h3>
                                                    <button type="button" class="btn btn-outline-info col-md-3"
                                                        data-bs-toggle="modal" data-bs-target="#addNewItem2"
                                                        data-bs-whatever="@mdo"><i class="fa fa-plus"></i>Add New
                                                        Item</button>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table align-items-center table-dark table-flush">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Category1</th>
                                                            <th scope="col text-md-end">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        // Fetch categories from the database
                                                        $sql = "SELECT * FROM categories WHERE parentId != 0";

                                                        $result = mysqli_query($conn, $sql);

                                                        if (mysqli_num_rows($result) > 0) {
                                                            // Loop through the categories and display them
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $category = $row['category'];
                                                                $category_parentId = $row['parentId'];
                                                                $category1_query = mysqli_query($conn, "SELECT * FROM categories WHERE id = $category_parentId");
                                                                if ($category1_query) {
                                                                    $category1_row = mysqli_fetch_assoc($category1_query);
                                                                    $category1 = $category1_row['category']; // Retrieve the category name from the parent category query result
                                                                    $category_id1 = $category1_row['id']; // Retrieve the category name from the parent category query result
                                                                } else {
                                                                    // Handle query error if necessary
                                                                }
                                                                $category_id = $row['id'];
                                                                echo "
                                                                <tr>
                                                                    <td>
                                                                        <div class='media-body'>
                                                                            <span class='mb-0 text-sm' edit-category-id2='{$category_id}'>$category</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class='media-body'>
                                                                            <span class='mb-0 text-sm' edit-category-id1='{$category_id1}'>$category1</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <a class='btn btn-sm btn-icon-only text-primary edit-category' href='#' role='button' 
                                                                        data-bs-toggle='modal' data-bs-target='#editItem'
                                                                        data-bs-whatever='@mdo' data-category-id='{$category_id}' edit-category='{$category}'>
                                                                            <i class='fas fa-edit'></i>
                                                                        </a>
                                                                        <a class='btn btn-sm btn-icon-only text-danger delete-category' href='#' role='button' data-category-id='{$category_id}'>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include '../components/externaljs.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var editButtons = document.querySelectorAll('.edit-category');
            editButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var categoryId = button.getAttribute('data-category-id');
                    var categoryValue = button.getAttribute('edit-category');
                    document.getElementById('edit_category_id').value = categoryId;
                    document.getElementById('edit_category').value = categoryValue;
                    console.log(categoryId + categoryValue);
                });
            });

            var deleteButtons = document.querySelectorAll('.delete-category');

            deleteButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var categoryId = button.getAttribute('data-category-id');
                    if (confirm("Are you sure you want to delete this category?")) {
                        deleteCategory(categoryId);
                    }
                });
            });

            function deleteCategory(categoryId) {
                // Send an AJAX request to delete-category.php with the category ID
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // If deletion is successful, reload the page
                            // location.reload();
                            $successMessage = "Successfully Deleted!";
                            location.href = "category.php?successMessage=" + $successMessage;
                        } else {
                            // If there's an error, display an error message
                            console.error('Error deleting category: ' + xhr.status);
                        }
                    }
                };
                xhr.open('POST', 'delete-category.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('category_id=' + categoryId);
            }
        });
    </script>

</body>

</html>