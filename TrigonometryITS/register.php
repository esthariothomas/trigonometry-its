<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $schoolname = $password = $email = "";
$fullname_err = $schoolname_err = $password_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userID FROM user WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate schoolname
    if(empty(trim($_POST["schoolname"]))){
        $schoolname_err = "Please enter your school name.";
    } elseif(!preg_match('/^[a-zA-Z0-9_ ]+$/', trim($_POST["schoolname"]))){
        $schoolname_err = "School name can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userID FROM user WHERE schoolname = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_schoolname);
            
            // Set parameters
            $param_schoolname = trim($_POST["schoolname"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 100){
                    $username_err = "Your school quota has fully filled.";
                } else{
                    $schoolname = trim($_POST["schoolname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    // if(empty(trim($_POST["confirm_password"]))){
    //     $confirm_password_err = "Please confirm password.";     
    // } else{
    //     $confirm_password = trim($_POST["confirm_password"]);
    //     if(empty($password_err) && ($password != $confirm_password)){
    //         $confirm_password_err = "Password did not match.";
    //     }
    // }

    // Validate email
    // if(empty(trim($_POST["email"]))){
    //     $email_err = "Please enter a email.";
    // } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["email"]))){
    //     $email_err = "Email can only contain letters, numbers, and underscores.";
    // } else{
    //     // Prepare a select statement
    //     $sql = "SELECT userID FROM user WHERE email = ?";
        
    //     if($stmt = mysqli_prepare($link, $sql)){
    //         // Bind variables to the prepared statement as parameters
    //         mysqli_stmt_bind_param($stmt, "s", $param_email);
            
    //         // Set parameters
    //         $param_email = trim($_POST["email"]);
            
    //         // Attempt to execute the prepared statement
    //         if(mysqli_stmt_execute($stmt)){
    //             /* store result */
    //             mysqli_stmt_store_result($stmt);
                
    //             if(mysqli_stmt_num_rows($stmt) == 1){
    //                 $email_err = "This email is already taken.";
    //             } else{
    //                 $email = trim($_POST["email"]);
    //             }
    //         } else{
    //             echo "Oops! Something went wrong. Please try again later.";
    //         }

    //         // Close statement
    //         mysqli_stmt_close($stmt);
    //     }
    // }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($schoolname_err) && empty($password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO user (username, schoolname, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_schoolname, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_schoolname = $schoolname;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/stylelogin.css">
    <title>Register</title>
</head>
<body>
<div class="wrapper">
        <h2>Sign Up Now !</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                
                <input type="text" name="username" placeholder="Username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                
                <input type="text" name="schoolname" placeholder="School Name" class="form-control <?php echo (!empty($schoolname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $schoolname; ?>">
                <span class="invalid-feedback"><?php echo $schoolname_err; ?></span>
            </div>    
            <div class="form-group">
                
                <input type="password" name="password" placeholder="Password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p class="bottom">
                Already have an account? 
                <a href="login.php">Login here</a>.
            </p>
        </form>
    </div>    
</body>
</html>