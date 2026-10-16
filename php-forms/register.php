<?php
session_name('sh_forms');
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function clean($value) {
    return trim(strip_tags($value));         
}
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); 
}

$errors  = [];
$success = "";
$old = ['name' => '', 'email' => '', 'mobile' => '', 'course' => '', 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid form submission (CSRF check failed).");
    }

    $old['name']    = clean($_POST['name']    ?? '');
    $old['email']   = clean($_POST['email']   ?? '');
    $old['mobile']  = clean($_POST['mobile']  ?? '');
    $old['course']  = clean($_POST['course']  ?? '');
    $old['message'] = clean($_POST['message'] ?? '');

    if ($old['name'] === '') {
        $errors['name'] = "Name is required.";
    } elseif (!preg_match("/^[A-Za-z ]{3,50}$/", $old['name'])) {
        $errors['name'] = "Name must be 3-50 letters (spaces allowed).";
    }

    if ($old['email'] === '') {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email address.";
    }

    if ($old['mobile'] === '') {
        $errors['mobile'] = "Mobile number is required.";
    } elseif (!preg_match("/^[6-9][0-9]{9}$/", $old['mobile'])) {
        $errors['mobile'] = "Mobile must be 10 digits and start with 6-9.";
    }

    $allowedCourses = ['BCA', 'BTech', 'MCA', 'BSc IT'];
    if (!in_array($old['course'], $allowedCourses, true)) {
        $errors['course'] = "Please select a valid course.";
    }

    if (strlen($old['message']) > 200) {
        $errors['message'] = "Message can be at most 200 characters.";
    }

    if (empty($errors)) {
        $folder = __DIR__ . "/data";
        $file   = $folder . "/registrations.csv";

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $isNewFile = !file_exists($file);
        $handle = fopen($file, "a");           

        if ($handle === false) {
            $errors['file'] = "Could not open the storage file.";
        } else {
            flock($handle, LOCK_EX);          
            if ($isNewFile) {
                fputcsv($handle, ['Date', 'Name', 'Email', 'Mobile', 'Course', 'Message'], ",", '"', "");
            }
            fputcsv($handle, [
                date("Y-m-d H:i:s"),
                $old['name'], $old['email'], $old['mobile'],
                $old['course'], $old['message']
            ], ",", '"', "");
            flock($handle, LOCK_UN);
            fclose($handle);

            $success = "Registration successful! Thank you, " . e($old['name']) . ".";
            $old = ['name' => '', 'email' => '', 'mobile' => '', 'course' => '', 'message' => ''];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudentHub - Register</title>
    <style>
        body {
             font-family: Arial, sans-serif;
         background:#f4f6f9; 
         margin:0;
          padding:20px;
         }
        .box {
             max-width:480px; 
        margin:auto;
         background:#fff; 
         padding:25px;
          border-radius:8px;
               box-shadow:0 2px 8px rgba(0,0,0,.1); 
            }
        label {
             display:block; 
        margin-top:14px;
         font-weight:bold;
         }
        input, select, textarea { 
            width:100%;
             padding:8px;
              margin-top:5px;
               box-sizing:border-box;
             }
        .error {
             color:#c0392b; 
             font-size:14px;
             }
        .success {
             background:#d4edda; 
             color:#155724;
              padding:10px; 
              border-radius:4px; 
            }
        .fail {
             background:#f8d7da;
              color:#721c24;
               padding:10px; 
               border-radius:4px;
             }
        button {
             margin-top:18px; 
             padding:10px 20px; 
             background:#2c3e50;
              color:#fff;
               border:0; 
              cursor:pointer;
             }
    </style>
</head>
<body>
<div class="box">
    <h2>StudentHub Registration</h2>

    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <p class="fail">Please fix the errors below.</p>
    <?php endif; ?>
    <?php if (isset($errors['file'])): ?>
        <p class="error"><?= e($errors['file']) ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?= e($old['name']) ?>">
        <span class="error"><?= e($errors['name'] ?? '') ?></span>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= e($old['email']) ?>">
        <span class="error"><?= e($errors['email'] ?? '') ?></span>

        <label for="mobile">Mobile</label>
        <input type="text" id="mobile" name="mobile" value="<?= e($old['mobile']) ?>">
        <span class="error"><?= e($errors['mobile'] ?? '') ?></span>

        <label for="course">Course</label>
        <select id="course" name="course">
            <option value="">-- Select --</option>
            <?php foreach (['BCA', 'BTech', 'MCA', 'BSc IT'] as $c): ?>
                <option value="<?= e($c) ?>" <?= $old['course'] === $c ? 'selected' : '' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
        </select>
        <span class="error"><?= e($errors['course'] ?? '') ?></span>

        <label for="message">Message (optional)</label>
        <textarea id="message" name="message" rows="3"><?= e($old['message']) ?></textarea>
        <span class="error"><?= e($errors['message'] ?? '') ?></span>

        <button type="submit">Register</button>
    </form>
    <p><a href="view.php">View saved records</a></p>
</div>
</body>
</html>