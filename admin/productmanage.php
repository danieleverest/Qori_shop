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

    if (isset ($_POST["editproduct"])) {
        $category_id = $_POST["edit_product_id"];
        $edit_product_name = $_POST["edit_product_name"];
        $edit_product_price = $_POST["edit_product_price"];
        $edit_product_category = $_POST["edit_product_category"];
        $edit_product_description = $_POST["edit_product_description"];

        // Check if a new image has been uploaded
        if ($_FILES["editimage"]["error"] === UPLOAD_ERR_OK) {
            // Retrieve the old image name
            $old_image_sql = "SELECT image FROM products WHERE id = '$category_id'";
            $old_image_result = mysqli_query($conn, $old_image_sql);
            $old_image_row = mysqli_fetch_assoc($old_image_result);
            $old_image = $old_image_row['image'];

            // Delete the old image
            unlink("uploads/$old_image");

            // Upload the new image
            $new_image_name = uniqid("product_image_") . "." . pathinfo($_FILES["editimage"]["name"], PATHINFO_EXTENSION);
            $uploadDirectory = "uploads/";
            if (move_uploaded_file($_FILES["editimage"]["tmp_name"], $uploadDirectory . $new_image_name)) {
                // Update the product data with the new image
                $sql = "UPDATE products SET product_name = '$edit_product_name', price = '$edit_product_price', category_id = '$edit_product_category', description = '$edit_product_description', image = '$new_image_name' WHERE id = '$category_id'";
            } else {
                // Error moving uploaded file
                $errorMessage = "Error uploading new image.";
                header("location: products.php?errorMessage=" . urlencode($errorMessage));
                exit;
            }
        } else {
            // No new image uploaded, update product data without changing the image
            $sql = "UPDATE products SET product_name = '$edit_product_name', price = '$edit_product_price', category_id = '$edit_product_category', description = '$edit_product_description' WHERE id = '$category_id'";
        }

        // Execute the SQL statement
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            $errorMessage = "Error updating product: " . mysqli_error($conn);
            header("location: products.php?errorMessage=" . urlencode($errorMessage));
            exit;
        }

        $successMessage = "Product updated successfully!";
        header("location: products.php?successMessage=" . urlencode($successMessage));
        exit;
    }
} else {
    echo "ELse";
}

?>