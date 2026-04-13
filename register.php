<?php
// Header එක සහ Database Connection එක සම්බන්ධ කරගැනීම
include('includes/header.php');
require('includes/db_connect.php');

// Form එක Submit කරාම ක්‍රියාත්මක වෙන කොටස
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // දත්ත විචල්‍යයන්ට ලබාගැනීම (ආරක්ෂාව සඳහා mysqli_real_escape_string භාවිතය)
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Passwords දෙක සමානදැයි පරීක්ෂා කිරීම
    if ($password === $confirm_password) {
        
        // මේ Email එක කලින් ලියාපදිංචි වෙලා තියෙනවදැයි බැලීම
        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($result) > 0) {
            $error = "This Email is already registered!";
        } else {
            // Password එක ආරක්ෂිතව Hash කිරීම
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Users වගුවට දත්ත ඇතුළත් කිරීම (Role එක ස්වයංක්‍රීයව 'customer' වේ)
            $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_password', 'customer')";
            
            if (mysqli_query($conn, $sql)) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    } else {
        $error = "Passwords do not match!";
    }
}
?>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Create an Account</h3>
                
                <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <?php if(isset($success)) { echo "<div class='alert alert-success'>$success <br> <a href='login.php'>Click here to Login</a></div>"; } ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6" placeholder="At least 6 characters">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="6" placeholder="Re-enter password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">Register</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="login.php" class="text-decoration-none">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>