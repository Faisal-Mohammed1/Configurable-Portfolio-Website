<?php
include 'includes/db_connect.php';
include 'includes/translations.php';

// 1. Fetch General Settings
$settings = $pdo->query("SELECT * FROM site_settings WHERE id=1")->fetch();

// 2. Fetch Experience Data
$exp_stmt = $pdo->query("SELECT * FROM experience ORDER BY id DESC");
$experiences = $exp_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark" <?php echo ($lang == 'ar' ? 'dir="rtl"' : 'dir="ltr"'); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('nav_exp'); ?> | <?php echo htmlspecialchars($settings['site_logo']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@700&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="header">
        <div class="container nav-container">
            <a href="index.php" class="logo"><?php echo htmlspecialchars($settings['site_logo']); ?><span class="accent-dot">.</span></a>
            
            <button class="mobile-menu-btn" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>

            <nav class="nav-menu">
                <ul class="nav-links">
                    <li><a href="index.php"><?php echo t('nav_home'); ?></a></li>
                    <li><a href="experience.php" class="active"><?php echo t('nav_exp'); ?></a></li>
                    <li><a href="skills.php"><?php echo t('nav_skills'); ?></a></li>
                    <li><a href="contact.php"><?php echo t('nav_contact'); ?></a></li>
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
        <div class="experience-layout">
            <div class="page-intro">
                <h1 class="accent-text"><?php echo t('exp_title'); ?></h1>
                <p><?php echo t('exp_desc'); ?></p>
            </div>

            <div class="experience-grid">
                <?php if(count($experiences) > 0): ?>
                    <?php foreach($experiences as $exp): ?>
                    <div class="exp-card">
                        <span class="exp-duration">
                            <?php echo ($lang == 'ar' ? htmlspecialchars($exp['duration_ar']) : htmlspecialchars($exp['duration_en'])); ?>
                        </span>
                        <h3 class="exp-role">
                            <?php echo ($lang == 'ar' ? htmlspecialchars($exp['role_ar']) : htmlspecialchars($exp['role_en'])); ?>
                        </h3>
                        <div class="exp-company">
                            <span class="dot"></span>
                            <?php echo ($lang == 'ar' ? htmlspecialchars($exp['company_ar']) : htmlspecialchars($exp['company_en'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: var(--text-secondary);">No experience added yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="assets/js/script.js"></script>
</body>
</html>