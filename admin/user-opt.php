<?php require_once "usercontroller.php"; ?>
<?php
$email = isset ($_SESSION['email']) ? $_SESSION['email'] : false;
if (!$email) {
    header('Location: login.php');
    exit(); // Add an exit after redirection to prevent further execution
}
$errors = [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/register.css">

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="user-otp.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Code Verification</h2>
                    <?php if (isset ($_SESSION['info'])): ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty ($errors)): ?>
                        <div class="alert alert-danger text-center">
                            <?php foreach ($errors as $showerror): ?>
                                <?php echo $showerror; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input class="form-control" type="number" name="otp" placeholder="Enter verification code"
                            required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check" value="Submit">
                    </div>
                </form>



            </div>
        </div>
    </div>



</body>

</html>