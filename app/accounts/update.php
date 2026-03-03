<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/account.php");
  include_once("../../core/repositories/role.php");

  $obj = new Role();
  $roles = $obj->getRoles();
  
  $obj = new Account();
  $account = $obj->getAccountById($_GET["account_id"]);

  $avatar_path = $account["avatar_path"];
  if (isset($_POST["submit"])) {
    if ($_FILES["upload"]["name"] != "") {
      $avatar_path = "../../assets/img/upload/" . $_FILES["upload"]["name"];
      move_uploaded_file($_FILES["upload"]["tmp_name"], $avatar_path);
    }

    $first_name = $_POST["first-name"];
    $middle_name = $_POST["middle-name"];
    $last_name = $_POST["last-name"];
    $suffix = $_POST["suffix"];
    $contact_no = $_POST["contact-no"];
    $position = $_POST["position"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $birthdate = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["birthdate"])));
    $employment_date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["employment-date"])));
    
    $obj = new Role();
    $role = $obj->getRoleByName($role);

    $obj = new Account();
    $obj->updateAccount($_GET["account_id"], $first_name, $middle_name, $last_name, $suffix, $birthdate, $contact_no, $position,
      $employment_date, $avatar_path, $role["role_id"], $email, $password);
    
    $_SESSION["toastr"] = array("type" => "success", "message" => "Account has been updated!");
    header("location: read.php?account_id=$_GET[account_id]");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounts</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <!-- Custom style -->
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include("../../app/components/navbar.php"); ?>
    <!-- Sidebar -->
    <?php include("../../app/components/sidebar.php"); ?>

    <!-- Content -->
    <div class="content-wrapper">
      <!-- Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Edit Account</h1>
            </div>
          </div>
        </div>
      </div>

      <!-- Main -->
      <div class="content">
        <div class="container-fluid">
          <form id="account-form" name="account-form" action="#" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-3 d-flex">
                <div class="card card-primary flex-fill">
                  <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                      <div class="profile-container mt-2 mb-2">
                        <img id="avatar" src="<?php echo $account["avatar_path"]; ?>" alt="" class="rounded-circle img-thumbnail" width="130" height="130" style="cursor: pointer; object-fit: cover;">
                        <input id="upload" name="upload" type="file" accept="image/*" class="d-none">
                      </div>
                      <label for="upload" class="btn btn-primary btn-sm mb-2">
                        <i class="fas fa-upload mr-1"></i> Upload image
                      </label>
                      <small class="text-muted">or click the avatar</small>
                      <h4 class="mt-2 mb-0">Avatar</h4>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-9 d-flex">
                <div class="card card-primary flex-fill">
                  <div class="card-body">
                    <h4 class="mb-2">Credentials</h4>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Email</label>
                          <input id="email" name="email" type="email" class="form-control" value="<?php echo $account["email"]; ?>">
                        </div>
                        <div class="form-group">
                          <label>Role</label>
                          <select name="role" class="form-control select2bs4" style="width: 100%;">
                            <option value="" disabled selected hidden><?php echo $account["role"]; ?></option>
                            <?php
                              foreach ($roles as $item) {
                                if ($account["role"] == $item["name"]) {
                                  echo "<option selected>$item[name]</option>";
                                } else {
                                  echo "<option>$item[name]</option>";
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12 d-flex">
                <div class="card card-primary flex-fill">
                  <div class="card-body">
                    <h4 class="mb-2">Personal Information</h4>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>First Name</label>
                          <input name="first-name" type="text" class="form-control" value="<?php echo $account["first_name"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Middle Name</label>
                          <input name="middle-name" type="text" class="form-control" value="<?php echo $account["middle_name"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Last Name</label>
                          <input name="last-name" type="text" class="form-control" value="<?php echo $account["last_name"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Suffix</label>
                          <input name="suffix" type="text" class="form-control" value="<?php echo $account["suffix"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Birthdate</label>
                          <div id="birthdate-group" class="input-group date" data-target-input="nearest">
                            <input name="birthdate" type="text" class="form-control datetimepicker-input" data-target="#birthdate-group"
                              data-toggle="datetimepicker" value="<?php echo date("m/d/Y", strtotime($account["birthdate"])); ?>"/>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Contact No</label>
                          <input name="contact-no" type="text" class="form-control" value="<?php echo $account["contact_no"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Position</label>
                          <input name="position" type="text" class="form-control" value="<?php echo $account["position"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Employment Date</label>
                          <div id="employment-date-group" class="input-group date" data-target-input="nearest">
                            <input name="employment-date" type="text" class="form-control datetimepicker-input" data-target="#employment-date-group"
                             data-toggle="datetimepicker" value="<?php echo date("m/d/Y", strtotime($account["employment_date"])); ?>"/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row mb-2">
              <div class="col-md-12">
                <button name="submit" type="submit" class="btn btn-success">Save</button>
                <a href="#" onclick="window.history.back(); return false;" class="btn btn-secondary ml-2">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <?php include("../../app/components/footer.php"); ?>
  </div>

  <!-- jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Select2 -->
  <script src="../../plugins/select2/js/select2.full.min.js"></script>
  <!-- Moment -->
  <script src="../../plugins/moment/moment.min.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Jquery Validation -->
  <script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
  <!-- Toastr -->
  <script src="../../plugins/toastr/toastr.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/js/adminlte.min.js"></script>
  <!-- Page specific script -->
  <script>
    $(function () {
      <?php
        if (isset($_SESSION["toastr"])) {
          echo "toastr." . $_SESSION["toastr"]["type"] . "('" . $_SESSION["toastr"]["message"] . "')";
          unset($_SESSION["toastr"]);
        }
      ?>

      $("#email").attr("maxlength", "100");
      $("#password").attr("maxlength", "50");
      $("#first-name").attr("maxlength", "50");
      $("#middle-name").attr("maxlength", "50");
      $("#last-name").attr("maxlength", "50");
      $("#suffix").attr("maxlength", "10");
      $("#contact-no").attr("maxlength", "15");
      $("#position").attr("maxlength", "50");
      
      $.validator.addMethod("lettersandcharacters", function(value, element) {
        return /^[^0-9]*$/i.test(value);
      });
      $.validator.addMethod("numbersandcharacters", function(value, element) {
        return /^[^a-zA-Z]*$/i.test(value);
      }); 
      $.validator.addMethod("date", function(value, element) {
        return moment(value,"mm/dd/yyyy").isValid();
      }); 

      $("#account-form").validate({
        rules: {
          "email": {
            required: true,
            email: true,
          },
          "password": {
            required: true
          },
          "role": {
            required: true
          },
          "first-name": {
            required: true,
            lettersandcharacters: true
          },
          "middle-name": {
            lettersandcharacters: true
          },
          "last-name": {
            required: true,
            lettersandcharacters: true
          },
          "suffix": {
            lettersandcharacters: true
          },
          "contact-no": {
            numbersandcharacters: true
          },
          "position": {
            required: true
          },
          "birthdate": {
            required: true,
            date: true
          },
          "employment-date": {
            required: true,
            date: true
          }
        },
        messages: {
          "email": {
            required: "Email is required",
            email: "Email should be a valid email address (e.g., delacruz.juan@deped.com.ph)"
          },
          "password": {
            required: "Password is required"
          },
          "role": {
            required: "Role is required"
          },
          "first-name": {
            required: "First name is required",
            lettersandcharacters: "First name should contain letters and special characters only"
          },
          "middle-name": {
            lettersandcharacters: "Middle name should contain letters and special characters only"
          },
          "last-name": {
            required: "Last name is required",
            lettersandcharacters: "Last name should contain letters and special characters only"
          },
          "suffix": {
            lettersandcharacters: "Suffix should contain letters and special characters only"
          },
          "contact-no": {
            numbersandcharacters: "Contact number should contain letters and special characters only"
          },
          "position": {
            required: "Position is required"
          },
          "birthdate": {
            required: "Birthdate is required",
            date: "Birthdate should be a valid date (e.g., MM/DD/YYYY)"
          },
          "employment-date": {
            required: "Employment date is required",
            date: "Employment date should be a valid date (e.g., MM/DD/YYYY)"
          }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
          error.addClass("invalid-feedback");
          element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass("is-invalid");
        }
      });

      $(".select2bs4").select2({
        theme: "bootstrap4",
        placeholder: "Role"
      }).on("change", function (e) {
        $(this).valid()
      });

      $("#birthdate-group").datetimepicker({
        minDate: new Date("1900-01-01"),
        format: "L"
      });
      $("#employment-date-group").datetimepicker({
        minDate: new Date("1900-01-01"),
        format: "L"
      });

      $("#avatar").click(function(e) {
        $("#upload").click();
      });
      function fasterPreview(uploader) {
        if (uploader.files && uploader.files[0]){
          $("#avatar").attr("src", window.URL.createObjectURL(uploader.files[0]));
        }
      }
      $("#upload").change(function(){
        fasterPreview(this);
      });
    });
  </script>
</body>
</html>