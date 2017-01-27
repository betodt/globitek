<?php
  require_once('../private/initialize.php');

  // Set default values for all variables the page needs.
  $first_name = $last_name = $email = $username = '';
  $errors =  array();

  // if this is a POST request, process the form
  // Hint: private/functions.php can help
  if (is_post_request()) {

    // Confirm that POST values are present before accessing them.
    $first_name = isset($_POST['first_name']) ? h($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? h($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? h($_POST['email']) : '';
    $username = isset($_POST['username']) ? h($_POST['username']) : '';

    // Perform Validations
    // Hint: Write these in private/validation_functions.php
    !is_blank($first_name) && has_length($first_name, ['min' => 2, 'max' => 255]) ? '' : array_push($errors, 'First name should be at least 2 characters');
    !is_blank($last_name) && has_length($last_name, ['min' => 2, 'max' => 255]) ? '' : array_push($errors, 'Last name should be at least 2 characters');
    !is_blank($email) && has_length($email, ['min' => 0, 'max' => 255]) && has_valid_email_format($email) ? '' : array_push($errors, 'Email is invalid');
    !is_blank($username) && has_length($username, ['min' => 8, 'max' => 255]) ? '' : array_push($errors, 'Username should be at least 8 characters');

    // if there were no errors, submit data to database
    if (empty($errors)) {
      // Write SQL INSERT statement
      $sql = "INSERT INTO `users` (first_name, last_name, username, email) VALUES ('$first_name', '$last_name', '$username', '$email')";

      // For INSERT statments, $result is just true/false
      $result = db_query($db, $sql);
      if($result) {
        db_close($db);

        redirect_to('registration_success.php');
      } else {
        // The SQL INSERT statement failed.
        // Just show the error, not the form
        echo db_error($db);
        db_close($db);
        exit;
      }
    }

  }

?>

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <h1>Register</h1>
  <p>Register to become a Globitek Partner.</p>

  <?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    echo display_errors($errors);
  ?>

  <!-- TODO: HTML form goes here -->
  <form class="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"><br>
    <label for="first_name">First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>"></label><br>
    <label for="last_name">Last Name: <input type="text" name="last_name" value="<?php echo $last_name; ?>"></label><br>
    <label for="email">Email: <input type="text" name="email" value="<?php echo $email; ?>"></label><br>
    <label for="username">Username: <input type="text" name="username" value="<?php echo $username; ?>"></label><br>
    <input type="submit" name="submit" value="submit">
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
