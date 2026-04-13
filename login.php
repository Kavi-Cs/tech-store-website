<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// අපි දැනටමත් ලොග් වෙලා නම්, ආයෙත් Login පිටුවට එන්න බැරි වෙන්න මුල් පිටුවට යවනවා
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include('includes/header.php');
require('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // ඊමේල් එක Database එකේ තියෙනවද කියලා බලනවා
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // Password එක හරියටම ගැලපෙනවද කියලා බලනවා
        if (password_verify($password, $row['password'])) {
            
            // Session එකේ User ගේ විස්තර Save කරනවා (මුළු වෙබ් අඩවිය පුරාම පාවිච්චි කරන්න)
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $row['role'];

            // සාර්ථකව ලොග් වුණාම මුල් පිටුවට හරවා යවනවා
            echo "<script>
                    alert('Login Successful! Welcome " . $row['name'] . "'); 
                    window.location.href='index.php';
                  </script>";
            exit();
        } else {
            $error = "Invalid Password! Please try again.";
        }
    } else {
        $error = "No account found with that email address!";
    }
}
?>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Login to Your Account</h3>
                
                <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>