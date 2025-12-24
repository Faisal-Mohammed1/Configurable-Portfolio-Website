<?php
include 'includes/db_connect.php';
include 'includes/translations.php';

$settings = $pdo->query("SELECT * FROM site_settings WHERE id=1")->fetch();

$msg_sent = false;
if(isset($_POST['send_msg'])) {
    $sql = "INSERT INTO contact_messages (first_name, last_name, email, phone, subject, message) VALUES (?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([$_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['phone'], $_POST['service'], $_POST['message']])) {
        $msg_sent = true;
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark" <?php echo ($lang == 'ar' ? 'dir="rtl"' : 'dir="ltr"'); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('nav_contact'); ?> | <?php echo $settings['site_logo']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@700&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="header">
        <div class="container nav-container">
            <a href="index.php" class="logo"><?php echo $settings['site_logo']; ?><span class="accent-dot">.</span></a>
            
            <button class="mobile-menu-btn" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>

            <nav class="nav-menu">
                <ul class="nav-links">
                    <li><a href="index.php"><?php echo t('nav_home'); ?></a></li>
                    <li><a href="experience.php"><?php echo t('nav_exp'); ?></a></li>
                    <li><a href="skills.php"><?php echo t('nav_skills'); ?></a></li>
                    <li><a href="contact.php" class="active"><?php echo t('nav_contact'); ?></a></li>
                    <li><a href="login.php"><?php echo t('nav_login'); ?></a></li>
                </ul>
                <div class="nav-actions">
                    <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="icon-btn">
                        <?php echo ($lang == 'en' ? 'AR' : 'EN'); ?>
                    </a>
                    <a href="contact.php" class="btn btn-primary"><?php echo t('btn_hire'); ?></a>
                </div>
            </nav>
        </div>
    </header>

    <main class="container page-content">
        <div class="contact-layout">
            
            <div class="contact-form-container">
                <h2 class="accent-text"><?php echo t('contact_title'); ?></h2>
                <p style="color:var(--text-secondary); margin-bottom:20px;"><?php echo t('contact_desc'); ?></p>
                
                <?php if($msg_sent): ?>
                    <div class="alert success"><?php echo t('success_msg'); ?></div>
                <?php endif; ?>

                <form method="POST" class="contact-form">
                    <div class="input-row">
                        <input type="text" name="fname" placeholder="<?php echo t('placeholder_fname'); ?>" required>
                        <input type="text" name="lname" placeholder="<?php echo t('placeholder_lname'); ?>" required>
                    </div>
                    <div class="input-row">
                        <input type="email" name="email" placeholder="<?php echo t('placeholder_email'); ?>" required>
                        <input type="text" name="phone" placeholder="<?php echo t('placeholder_phone'); ?>">
                    </div>
                    <select name="service" required>
                        <option value="" disabled selected><?php echo t('placeholder_subject'); ?></option>
                        <option value="Web Development">Web Development</option>
                        <option value="UI/UX Design">UI/UX Design</option>
                        <option value="Logo Design">Logo Design</option>
                        <option value="Other">Other</option>
                    </select>
                    <textarea name="message" rows="5" placeholder="<?php echo t('placeholder_textarea'); ?>" required></textarea>
                    <button type="submit" name="send_msg" class="btn btn-primary"><?php echo t('btn_send'); ?></button>
                </form>
            </div>

            <div class="contact-info">
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="info-text">
                        <span><?php echo t('label_phone'); ?></span>
                        <p><?php echo htmlspecialchars($settings['contact_phone']); ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="info-text">
                        <span><?php echo t('label_email'); ?></span>
                        <p><?php echo htmlspecialchars($settings['contact_email']); ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-text">
                        <span><?php echo t('label_address'); ?></span>
                        <p><?php echo htmlspecialchars($settings['contact_address']); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="assets/js/script.js"></script>
</body>
</html>