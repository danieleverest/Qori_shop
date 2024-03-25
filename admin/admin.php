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
                <!-- <form action="../php/update_database.php" method="post" enctype="multipart/form-data"
                    class="border-secondary"> -->
                <form action="admin.php" method="post" enctype="multipart/form-data" class="border-secondary">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product_name" class="col-form-label">Product Name:</label>
                            <input type="text" class="form-control" id="product_name" required>
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
                            <!-- <input type="text" class="form-control" id="category" required> -->
                            <select class="form-select" aria-label="Default select example">
                                <option selected>select one</option>
                                <option value="1">Phone</option>
                                <option value="2">Test</option>
                                <option value="3">Computer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="col-form-label">Description:</label>
                            <textarea class="form-control" id="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imagen">Image:</label>
                            <input type="file" name="uploadfile" value="" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input class="btn btn-secondary btn-success" type="submit" name="newitem" value="Add New Item">
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
                            <a href="../" class="nav-link px-2 text-truncate">
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
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="media align-items-center">
                                                                    <a href="#" class="avatar rounded-circle mr-3">
                                                                        <img alt="Image placeholder"
                                                                            src="https://www.hitcase.com/cdn/shop/products/hitcase-shield-link-for-iphone-x-case-13904682.jpg?v=1542924598">
                                                                    </a>
                                                                </div>
                                                            </th>
                                                            <td>
                                                                <div class="media-body">
                                                                    <span class="mb-0 text-sm">iPhone</span>
                                                                </div>

                                                            </td>
                                                            <td>
                                                                <span class="badge badge-dot mr-4">
                                                                    <i class="bg-warning"></i> $1500 USD
                                                                </span>
                                                            </td>
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

                    </div>
                </div>
            </main>
        </div>
    </div>



    <?php include '../components/externaljs.php'; ?>

</body>

</html>