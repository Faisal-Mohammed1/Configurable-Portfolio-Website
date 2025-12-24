<?php
// 1. Database and Translation files
include 'includes/db_connect.php'; // CRUCIAL: This defines $pdo
include 'includes/translations.php'; 

// 2. Fetch General Settings from Database
$settings_stmt = $pdo->query("SELECT * FROM site_settings WHERE id = 1");
$settings = $settings_stmt->fetch();

// 3. Fetch Social Links from Database
$socials_stmt = $pdo->query("SELECT * FROM social_links");
$socials = $socials_stmt->fetchAll();

// 4. Default data if database is empty (to prevent errors)
if (!$settings) {
    $settings = [
        'site_logo' => 'John',
        'full_name_en' => 'John Doe', 'full_name_ar' => 'جون دو',
        'career_en' => 'Software Engineer', 'career_ar' => 'مهندس برمجيات',
        'bio_en' => 'As a Software Engineer, I design and build solutions...', 
        'bio_ar' => 'كمهندس برمجيات، أقوم بتصميم وبناء حلول...',
        'stat1_val' => '0', 'stat2_val' => '0', 'stat3_val' => '0', 'stat4_val' => '0',
        'profile_image' => 'assets/img/profile.png',
        'cv_url' => '#'
    ];
}

// Helper variables for clean code below
$current_name = ($lang == 'ar' ? $settings['full_name_ar'] : $settings['full_name_en']);
$current_career = ($lang == 'ar' ? $settings['career_ar'] : $settings['career_en']);
$current_bio = ($lang == 'ar' ? $settings['bio_ar'] : $settings['bio_en']);
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark" <?php echo ($lang == 'ar' ? 'dir="rtl"' : 'dir="ltr"'); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('nav_home'); ?> | <?php echo $current_name; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@700&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="header">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <?php echo $settings['site_logo']; ?><span class="accent-dot">.</span>
            </a>

            <button class="mobile-menu-btn" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>

            <nav class="nav-menu">
                <ul class="nav-links">
                    <li><a href="index.php" class="active"><?php echo t('nav_home'); ?></a></li>
                    <li><a href="experience.php"><?php echo t('nav_exp'); ?></a></li>
                    <li><a href="skills.php"><?php echo t('nav_skills'); ?></a></li>
                    <li><a href="contact.php"><?php echo t('nav_contact'); ?></a></li>
                    <li><a href="login.php"><?php echo t('nav_login'); ?></a></li>
                </ul>
                
                <div class="nav-actions">
                    <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="icon-btn">
                        <?php echo ($lang == 'en' ? 'AR' : 'EN'); ?>
                    </a>
                    
                    <button id="theme-toggle" class="icon-btn">
                        <i class="fas fa-sun light-icon"></i>
                        <i class="fas fa-moon dark-icon"></i>
                    </button>

                    <a href="contact.php?subject=hire" class="btn btn-primary"><?php echo t('btn_hire'); ?></a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero-section">
            <div class="container hero-container">
                <div class="hero-content">
                    <span class="job-title-heading"><?php echo $current_career; ?></span>
                    <h1><?php echo t('hello'); ?> <br> <span class="accent-text"><?php echo $current_name; ?></span></h1>
                    <p class="hero-bio"><?php echo $current_bio; ?></p>

                    <div class="hero-actions">
                        <a href="<?php echo $settings['cv_url']; ?>" class="btn btn-outline" download>
                            <?php echo t('cv_btn'); ?> <i class="fas fa-download"></i>
                        </a>
                        <div class="social-links">
                            <?php foreach($socials as $link): ?>
                                <a href="<?php echo $link['url']; ?>" target="_blank">
                                    <i class="<?php echo $link['icon_class']; ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                    <div class="hero-visual">
                        <div class="image-container-circles">
                            <?php 
                            // 1. Get the path from the database
                            $dbImage = $settings['profile_image'];
                            
                            // 2. Default placeholder if empty
                            $finalImage = 'assets/img/profile.png';
                            
                            // 3. Check if the file actually exists on the server
                            if (!empty($dbImage) && file_exists($dbImage)) {
                                $finalImage = $dbImage;
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($finalImage); ?>" alt="Profile" class="profile-img">
                        </div>
                    </div>
            </div>
        </section>

    </main>

    <script src="assets/js/script.js"></script>
</body>
</html>