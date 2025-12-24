<?php
session_start();
include 'includes/db_connect.php';
include 'includes/translations.php';

// 1. Fetch General Settings (For Logo & Auth)
$settings = $pdo->query("SELECT * FROM site_settings WHERE id=1")->fetch();

// 2. Handle Login Logic
$error = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Get credentials from DB
    $db_user = $settings['admin_username']; 
    $db_pass = $settings['admin_password'];

    $is_authenticated = false;

    // Check Username
    if ($username === $db_user) {
        // SCENARIO A: User has NOT changed password yet (DB is empty) -> Use Default
        if (empty($db_pass) && $password === '123456') {
            $is_authenticated = true;
        }
        // SCENARIO B: User HAS changed password -> Verify Hash
        elseif (!empty($db_pass) && password_verify($password, $db_pass)) {
            $is_authenticated = true;
        }
    }

    if ($is_authenticated) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = ($lang == 'ar' ? 'اسم المستخدم أو كلمة المرور غير صحيحة' : 'Invalid username or password');
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark" <?php echo ($lang == 'ar' ? 'dir="rtl"' : 'dir="ltr"'); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('nav_login'); ?> | <?php echo htmlspecialchars($settings['site_logo']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@700&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="header">
        <div class="container nav-container">
            <a href="index.php" class="logo"><?php echo htmlspecialchars($settings['site_logo']); ?><span class="accent-dot">.</span></a>
            
            <div class="nav-actions">
                <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="icon-btn">
                    <?php echo ($lang == 'en' ? 'AR' : 'EN'); ?>
                </a>
                <a href="index.php" class="btn btn-outline"><?php echo t('nav_home'); ?></a>
            </div>
        </div>
    </header>

    <main class="login-page">
        <div class="login-card">
            <div class="login-header">
                <h2><?php echo t('nav_login'); ?></h2>
                <p><?php echo ($lang == 'ar' ? 'مرحباً بعودتك! يرجى تسجيل الدخول.' : 'Welcome back! Please login to continue.'); ?></p>
            </div>

            <?php if($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="contact-form">
                <div class="w-full">
                    <input type="text" name="username" placeholder="<?php echo ($lang == 'ar' ? 'اسم المستخدم' : 'Username'); ?>" required style="width: 100%;">
                </div>
                <div class="w-full" style="margin-top: 15px;">
                    <input type="password" name="password" placeholder="<?php echo ($lang == 'ar' ? 'كلمة المرور' : 'Password'); ?>" required style="width: 100%;">
                </div>
                
                <button type="submit" name="login" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                    <?php echo ($lang == 'ar' ? 'دخول' : 'Login'); ?> <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer">
                <a href="index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> <?php echo ($lang == 'ar' ? 'العودة للرئيسية' : 'Back to Home'); ?>
                </a>
            </div>
        </div>
    </main>

</body>
</html>