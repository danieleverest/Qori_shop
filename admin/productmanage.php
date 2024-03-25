<?php
include "../config/db.php";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form fields are set and not empty
    if (isset ($_POST["addproduct"])) {
        // Retrieve form data
        $productName = $_POST['product_name'];
        $price = $_POST['price'];
        $categoryID = $_POST['category1'];
        $description = $_POST['description'];

        if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            // Check if the file is an image
            $fileType = exif_imagetype($_FILES["image"]["tmp_name"]);
            if ($fileType !== false) {
                // Generate a unique name for the image
                $imageName = uniqid("product_image_") . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

                // Specify the directory where uploaded images will be saved
                $uploadDirectory = "uploads/";

                // Move the uploaded file to the specified directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDirectory . $imageName)) {
                    // File upload successful, now insert product data into the database

                    // Prepare the SQL statement
                    $sql = "INSERT INTO products (product_name, price, category_id, description, image) VALUES ('$productName', $price, $categoryID, '$description', '$imageName')";

                    // Execute the SQL statement
                    if (mysqli_query($conn, $sql)) {
                        // Product added successfully
                        // echo "Product added successfully.";
                        $successMessage = "Product added successfully!";
                        header("location: products.php?successMessage=" . urlencode($successMessage));
                        exit;
                    } else {
                        // Error inserting product into the database
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    // Error moving uploaded file
                    // echo "Error uploading image.";
                    $errorMessage = "Error uploading image.";
                    header("location: products.php?errorMessage=" . urlencode($errorMessage));
                    exit;
                }
            } else {
                // File is not an image
                $errorMessage = "Uploaded file is not an image.";
                header("location: products.php?errorMessage=" . urlencode($errorMessage));
                exit;
            }
        } else {
            $errorMessage = "Error uploading file: " . $_FILES["image"]["error"];
            header("location: products.php?errorMessage=" . urlencode($errorMessage));
            exit;
        }
    }

    if (isset ($_POST["newcategory2"])) {
        $category1 = $_POST["category1"];
        $category2 = $_POST["category2"];

        $check_sql = "SELECT * FROM categories WHERE category = '$category2' AND parentId = $category1";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $errorMessage = "Category already exists!";
            header("location: category.php?errorMessage=" . urlencode($errorMessage));
        } else {
            // Insert the category into the database
            $sql = "INSERT INTO categories (category, parentId) VALUES ('$category2', '$category1')";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die ("Invalid query : " . mysqli_error($conn));
            }

            $successMessage = "Successfully Added!";
            header("location: category.php?successMessage=" . urlencode($successMessage));
            exit;
        }
    }

    if (isset ($_POST["editcategory"])) {
        $category_id = $_POST["edit_category_id"];
        $category = $_POST["edit_category"];

        $check_sql = "SELECT * FROM categories WHERE category = '$category'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // $errorMessage = "Category already exists!";
            $errorMessage = mysqli_num_rows($check_result);
            header("location: category.php?errorMessage=" . urlencode($errorMessage));
        } else {
            // Insert the category into the database
            $sql = "UPDATE categories SET category = '$category' WHERE id = '$category_id'";
            $result = mysqli_query($conn, $sql);
            // echo "SQL Query: " . $sql . "<br>";
            if (!$result) {
                $errorMessage = "Error updating category: " . mysqli_error($conn);
                header("location: category.php?errorMessage=" . urlencode($errorMessage));
                exit;
            }

            // $successMessage = "SQL Query: " . $sql . "<br>";
            $successMessage = "Successfully Updated!";
            header("location: category.php?successMessage=" . urlencode($successMessage));
            exit;
        }
    }
} else {
    echo "ELse";
}

?>