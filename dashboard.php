<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/translations.php';

// 1. Security Checks
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}

// 2. Logic Handling

// Messages: Toggle Read
if (isset($_GET['toggle_read'])) {
    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = NOT is_read WHERE id = ?");
    $stmt->execute([$_GET['toggle_read']]);
    header("Location: dashboard.php?tab=messages");
    exit();
}

// Messages: Delete
if (isset($_GET['delete_id'])) {
    $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$_GET['delete_id']]);
    header("Location: dashboard.php?tab=messages");
    exit();
}

// Settings: Update General Info, Contact & Security
if (isset($_POST['update_settings'])) {
    $img_path = $_POST['current_img'];
    $cv_path = $_POST['current_cv'];

    // 1. Handle File Uploads
    if (!empty($_FILES['profile_img']['name'])) {
        $target_dir = "assets/img/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_name = time() . '_' . basename($_FILES['profile_img']['name']);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $target_file)) {
            $img_path = $target_file;
        }
    }

    if (!empty($_FILES['cv_file']['name'])) {
        $target_dir = "assets/docs/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_name = time() . '_' . basename($_FILES['cv_file']['name']);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $target_file)) {
            $cv_path = $target_file;
        }
    }

    // 2. Handle Password Security
    $final_username = $_POST['admin_username'];
    $final_password = $_POST['current_password_hash']; // Default to existing
    
    if (!empty($_POST['new_password'])) {
        $final_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    }

    // 3. Update Database (Removed Stats)
    $sql = "UPDATE site_settings SET 
            site_logo=?, full_name_en=?, full_name_ar=?, career_en=?, career_ar=?, 
            bio_en=?, bio_ar=?, 
            contact_phone=?, contact_email=?, contact_address=?, 
            admin_username=?, admin_password=?, 
            profile_image=?, cv_url=? WHERE id=1";
            
    $pdo->prepare($sql)->execute([
        $_POST['site_logo'], $_POST['name_en'], $_POST['name_ar'], 
        $_POST['career_en'], $_POST['career_ar'], $_POST['bio_en'], $_POST['bio_ar'], 
        $_POST['contact_phone'], $_POST['contact_email'], $_POST['contact_address'],
        $final_username, $final_password,
        $img_path, $cv_path
    ]);
    
    header("Location: dashboard.php?tab=settings&msg=updated");
    exit();
}

// Settings: Add Social Link
if (isset($_POST['add_social'])) {
    $pdo->prepare("INSERT INTO social_links (platform_name, icon_class, url) VALUES (?, ?, ?)")
        ->execute([$_POST['plat'], $_POST['icon'], $_POST['url']]);
    header("Location: dashboard.php?tab=settings");
    exit();
}

// Settings: Delete Social Link
if (isset($_GET['delete_social'])) {
    $pdo->prepare("DELETE FROM social_links WHERE id=?")->execute([$_GET['delete_social']]);
    header("Location: dashboard.php?tab=settings");
    exit();
}

// Experience: Add/Delete
if (isset($_POST['add_experience'])) {
    $sql = "INSERT INTO experience (role_en, role_ar, company_en, company_ar, duration_en, duration_ar) VALUES (?, ?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$_POST['role_en'], $_POST['role_ar'], $_POST['company_en'], $_POST['company_ar'], $_POST['dur_en'], $_POST['dur_ar']]);
    header("Location: dashboard.php?tab=experience");
    exit();
}
if (isset($_GET['delete_exp'])) {
    $pdo->prepare("DELETE FROM experience WHERE id = ?")->execute([$_GET['delete_exp']]);
    header("Location: dashboard.php?tab=experience");
    exit();
}

// Skills: Add/Delete
if (isset($_POST['add_skill'])) {
    $sql = "INSERT INTO skills (name, icon_class) VALUES (?, ?)";
    $pdo->prepare($sql)->execute([$_POST['skill_name_en'], $_POST['skill_icon']]);
    header("Location: dashboard.php?tab=skills");
    exit();
}
if (isset($_GET['delete_skill'])) {
    $pdo->prepare("DELETE FROM skills WHERE id = ?")->execute([$_GET['delete_skill']]);
    header("Location: dashboard.php?tab=skills");
    exit();
}

// 3. Fetch Data
$settings = $pdo->query("SELECT * FROM site_settings WHERE id=1")->fetch();
$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
$socials = $pdo->query("SELECT * FROM social_links")->fetchAll();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark" <?php echo ($lang == 'ar' ? 'dir="rtl"' : 'dir="ltr"'); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($lang == 'ar' ? 'لوحة التحكم' : 'Admin Dashboard'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Modal Styles */
        .msg-modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.8); backdrop-filter: blur(5px);
        }
        .modal-content {
            background-color: var(--bg-secondary); margin: 10% auto; padding: 30px;
            border: 1px solid var(--accent-color); width: 80%; max-width: 600px;
            border-radius: 15px; position: relative; color: var(--text-primary);
        }
        .close-modal { position: absolute; right: 20px; top: 15px; font-size: 28px; cursor: pointer; }
    </style>
</head>
<body class="dashboard-page">

    <header class="header">
        <div class="container nav-container">
            <a href="index.php" class="logo"><?php echo ($lang == 'ar' ? 'المدير' : 'Admin'); ?><span class="accent-dot">.</span></a>
            <div class="nav-actions">
                <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="icon-btn">
                    <?php echo ($lang == 'en' ? 'AR' : 'EN'); ?>
                </a>
                <a href="dashboard.php?action=logout" class="btn btn-outline" style="border-color: #ff4747; color: #ff4747;">
                    <i class="fas fa-sign-out-alt"></i> <?php echo t('logout'); ?>
                </a>
            </div>
        </div>
    </header>

    <main class="container page-content">
        <div class="dashboard-nav" style="display:flex; gap:20px; margin-bottom:30px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:10px;">
            <a href="?tab=messages" class="<?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'messages') ? 'accent-text' : ''; ?>"><?php echo t('tab_messages'); ?></a>
            <a href="?tab=experience" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'experience') ? 'accent-text' : ''; ?>"><?php echo t('tab_experience'); ?></a>
            <a href="?tab=skills" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'skills') ? 'accent-text' : ''; ?>"><?php echo t('tab_skills'); ?></a>
            <a href="?tab=settings" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'settings') ? 'accent-text' : ''; ?>"><?php echo ($lang == 'ar' ? 'الإعدادات' : 'Settings'); ?></a>
        </div>

        <?php if(!isset($_GET['tab']) || $_GET['tab'] == 'messages'): ?>
            <div class="dashboard-header">
                <h1 class="accent-text"><?php echo t('tab_messages'); ?></h1>
                <p>
                    <?php 
                        $total = count($messages);
                        $unread = 0;
                        foreach($messages as $m) { if(!$m['is_read']) $unread++; }
                        echo sprintf(t('total_inquiries'), $total); 
                    ?> 
                    (<?php echo $unread; ?> <?php echo ($lang == 'ar' ? 'غير مقروءة' : 'unread'); ?>)
                </p>
            </div>
            
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><?php echo ($lang == 'ar' ? 'الاسم' : 'Name'); ?></th>
                            <th><?php echo ($lang == 'ar' ? 'الموضوع' : 'Subject'); ?></th>
                            <th><?php echo ($lang == 'ar' ? 'إجراء' : 'Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                        <tr style="<?php echo $msg['is_read'] ? 'opacity: 0.6;' : 'font-weight: bold;'; ?>">
                            <td>
                                <?php if(!$msg['is_read']): ?>
                                    <span class="badge" style="background: #00ff99; color: #000; font-size: 10px; padding: 2px 5px; border-radius: 4px; margin-right: 5px;"><?php echo ($lang == 'ar' ? 'جديد' : 'NEW'); ?></span>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                            <td>
                                <div style="display: flex; gap: 15px; align-items: center;">
                                    <button onclick='viewMessage(<?php echo json_encode($msg); ?>)' class="icon-btn" title="<?php echo t('tooltip_view'); ?>" style="color: var(--accent-color); background:none; border:none; cursor:pointer;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="?toggle_read=<?php echo $msg['id']; ?>&tab=messages" title="<?php echo t('tooltip_status'); ?>" style="color: #fff;">
                                        <i class="fas <?php echo $msg['is_read'] ? 'fa-envelope-open' : 'fa-envelope'; ?>"></i>
                                    </a>
                                    <a href="mailto:<?php echo $msg['email']; ?>?subject=Re: <?php echo rawurlencode($msg['subject']); ?>" title="<?php echo ($lang == 'ar' ? 'رد عبر البريد' : 'Reply via Email'); ?>" style="color: #3498db;">
                                        <i class="fas fa-reply"></i>
                                    </a>
                                    <a href="?delete_id=<?php echo $msg['id']; ?>&tab=messages" class="delete-btn" style="color: #ff4747;" onclick="return confirm('<?php echo ($lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Delete?'); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($messages)): ?>
                            <tr><td colspan="3" style="text-align:center; padding:20px;"><?php echo ($lang == 'ar' ? 'لا توجد رسائل.' : 'No messages.'); ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['tab']) && $_GET['tab'] == 'experience'): ?>
            <h1 class="accent-text"><?php echo t('tab_experience'); ?></h1>
            <form method="POST" class="contact-form" style="background:var(--bg-secondary); padding:20px; border-radius:10px; margin-bottom:30px;">
                <div class="input-row">
                    <input type="text" name="role_en" placeholder="<?php echo ($lang == 'ar' ? 'الوظيفة (إنجليزي)' : 'Role (EN)'); ?>" required>
                    <input type="text" name="role_ar" placeholder="<?php echo ($lang == 'ar' ? 'الوظيفة (عربي)' : 'الوظيفة (AR)'); ?>" required>
                </div>
                <div class="input-row">
                    <input type="text" name="company_en" placeholder="<?php echo ($lang == 'ar' ? 'الشركة (إنجليزي)' : 'Company (EN)'); ?>" required>
                    <input type="text" name="company_ar" placeholder="<?php echo ($lang == 'ar' ? 'الشركة (عربي)' : 'الشركة (AR)'); ?>" required>
                </div>
                <div class="input-row">
                    <input type="text" name="dur_en" placeholder="<?php echo ($lang == 'ar' ? 'الفترة (إنجليزي)' : 'Duration (EN)'); ?>" required>
                    <input type="text" name="dur_ar" placeholder="<?php echo ($lang == 'ar' ? 'الفترة (عربي)' : 'الفترة (AR)'); ?>" required>
                </div>
                <button type="submit" name="add_experience" class="btn btn-primary"><?php echo t('btn_add_new'); ?></button>
            </form>
            <div class="table-container">
                <table class="admin-table">
                    <tbody>
                        <?php 
                        $exp_list = $pdo->query("SELECT * FROM experience ORDER BY id DESC")->fetchAll();
                        foreach($exp_list as $e): ?>
                        <tr>
                            <td><?php echo $e['role_en']; ?> / <?php echo $e['role_ar']; ?></td>
                            <td><a href="?delete_exp=<?php echo $e['id']; ?>&tab=experience" class="delete-btn"><i class="fas fa-trash"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['tab']) && $_GET['tab'] == 'skills'): ?>
            <h1 class="accent-text"><?php echo t('tab_skills'); ?></h1>
            <form method="POST" class="contact-form" style="background:var(--bg-secondary); padding:20px; border-radius:10px; margin-bottom:30px;">
                <div class="input-row">
                    <input type="text" name="skill_name_en" placeholder="<?php echo ($lang == 'ar' ? 'اسم المهارة' : 'Skill Name'); ?>" required>
                    
                    <select name="skill_icon" style="background:var(--bg-primary); color:var(--text-primary); border:1px solid rgba(255,255,255,0.1); padding:15px; border-radius:8px;" required>
                        <option value="" disabled selected><?php echo ($lang == 'ar' ? 'اختر الأيقونة' : 'Select Icon'); ?></option>
                        <optgroup label="Languages">
                            <option value="fab fa-html5">HTML5</option>
                            <option value="fab fa-css3-alt">CSS3</option>
                            <option value="fab fa-js">JavaScript</option>
                            <option value="fab fa-php">PHP</option>
                            <option value="fab fa-python">Python</option>
                            <option value="fab fa-java">Java</option>
                            <option value="fab fa-swift">Swift</option>
                            <option value="fab fa-rust">Rust</option>
                            <option value="fab fa-golang">Go</option>
                            <option value="fab fa-cuttlefish">C / C++</option>
                        </optgroup>
                        <optgroup label="Frameworks & Libraries">
                            <option value="fab fa-react">React</option>
                            <option value="fab fa-vuejs">Vue.js</option>
                            <option value="fab fa-angular">Angular</option>
                            <option value="fab fa-node">Node.js</option>
                            <option value="fab fa-laravel">Laravel</option>
                            <option value="fab fa-symfony">Symfony</option>
                            <option value="fab fa-bootstrap">Bootstrap</option>
                            <option value="fab fa-sass">Sass/SCSS</option>
                            <option value="fab fa-wordpress">WordPress</option>
                        </optgroup>
                        <optgroup label="DevOps & Tools">
                            <option value="fab fa-git-alt">Git</option>
                            <option value="fab fa-github">GitHub</option>
                            <option value="fab fa-gitlab">GitLab</option>
                            <option value="fab fa-docker">Docker</option>
                            <option value="fab fa-aws">AWS</option>
                            <option value="fab fa-jenkins">Jenkins</option>
                            <option value="fab fa-npm">NPM</option>
                        </optgroup>
                        <optgroup label="OS & Platforms">
                            <option value="fab fa-linux">Linux</option>
                            <option value="fab fa-ubuntu">Ubuntu</option>
                            <option value="fab fa-android">Android</option>
                            <option value="fab fa-apple">iOS / macOS</option>
                            <option value="fab fa-windows">Windows</option>
                        </optgroup>
                        <optgroup label="Generic / Other">
                            <option value="fas fa-code">Code</option>
                            <option value="fas fa-database">Database</option>
                            <option value="fas fa-server">Server</option>
                            <option value="fas fa-terminal">Terminal</option>
                            <option value="fas fa-cloud">Cloud</option>
                            <option value="fas fa-star">Star</option>
                        </optgroup>
                    </select>
                </div>
                <button type="submit" name="add_skill" class="btn btn-primary"><?php echo t('btn_add_new'); ?></button>
            </form>
            
            <div class="table-container">
                <table class="admin-table">
                    <tbody>
                        <?php
                        $sk_list = $pdo->query("SELECT * FROM skills ORDER BY id DESC")->fetchAll();
                        foreach($sk_list as $s): ?>
                        <tr>
                            <td><i class="<?php echo $s['icon_class']; ?>"></i> <?php echo $s['name']; ?></td>
                            <td><a href="?delete_skill=<?php echo $s['id']; ?>&tab=skills" class="delete-btn" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['tab']) && $_GET['tab'] == 'settings'): ?>
            <h1 class="accent-text"><?php echo ($lang == 'ar' ? 'إعدادات الموقع' : 'Portfolio Settings'); ?></h1>
            <form method="POST" enctype="multipart/form-data" class="contact-form" style="background:var(--bg-secondary); padding:25px; border-radius:15px;">
                <label><?php echo ($lang == 'ar' ? 'شعار الموقع' : 'Website Logo Name'); ?></label>
                <input type="text" name="site_logo" value="<?php echo $settings['site_logo']; ?>">

                <div class="input-row">
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'الاسم الكامل (إنجليزي)' : 'Full Name (English)'); ?></label>
                        <input type="text" name="name_en" value="<?php echo $settings['full_name_en']; ?>">
                    </div>
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'الاسم الكامل (عربي)' : 'Full Name (Arabic)'); ?></label>
                        <input type="text" name="name_ar" value="<?php echo $settings['full_name_ar']; ?>">
                    </div>
                </div>

                <div class="input-row">
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'المسمى الوظيفي (إنجليزي)' : 'Career Title (English)'); ?></label>
                        <input type="text" name="career_en" value="<?php echo $settings['career_en']; ?>">
                    </div>
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'المسمى الوظيفي (عربي)' : 'Career Title (Arabic)'); ?></label>
                        <input type="text" name="career_ar" value="<?php echo $settings['career_ar']; ?>">
                    </div>
                </div>

                <label><?php echo ($lang == 'ar' ? 'نبذة تعريفية (إنجليزي)' : 'Bio (English)'); ?></label>
                <textarea name="bio_en" rows="3"><?php echo $settings['bio_en']; ?></textarea>
                
                <label><?php echo ($lang == 'ar' ? 'نبذة تعريفية (عربي)' : 'Bio (Arabic)'); ?></label>
                <textarea name="bio_ar" rows="3" dir="rtl"><?php echo $settings['bio_ar']; ?></textarea>

                <div class="input-row" style="margin-top:20px;">
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'الصورة الشخصية' : 'Profile Image'); ?></label>
                        <input type="file" name="profile_img">
                        <input type="hidden" name="current_img" value="<?php echo $settings['profile_image']; ?>">
                    </div>
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'ملف السيرة الذاتية (PDF)' : 'CV File (PDF)'); ?></label>
                        <input type="file" name="cv_file">
                        <input type="hidden" name="current_cv" value="<?php echo $settings['cv_url']; ?>">
                    </div>
                </div>

                <h3 class="accent-text" style="margin-top:20px;"><?php echo ($lang == 'ar' ? 'معلومات الاتصال' : 'Contact Information'); ?></h3>
                <div class="input-row">
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'رقم الهاتف' : 'Phone Number'); ?></label>
                        <input type="text" name="contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone']); ?>" placeholder="+1 234...">
                    </div>
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'البريد الإلكتروني' : 'Contact Email'); ?></label>
                        <input type="text" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email']); ?>" placeholder="email@example.com">
                    </div>
                </div>
                <div class="w-full" style="margin-top: 15px;">
                    <label><?php echo ($lang == 'ar' ? 'العنوان' : 'Address'); ?></label>
                    <input type="text" name="contact_address" value="<?php echo htmlspecialchars($settings['contact_address']); ?>" placeholder="City, Country">
                </div>

                <hr style="margin: 40px 0; border: 0; border-top: 1px solid rgba(255,255,255,0.1);">
                <h3 class="accent-text" style="color: #ff4747;"><?php echo ($lang == 'ar' ? 'إعدادات الحساب' : 'Account Security'); ?></h3>
                <div class="input-row">
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'اسم المستخدم' : 'Username'); ?></label>
                        <input type="text" name="admin_username" value="<?php echo htmlspecialchars($settings['admin_username']); ?>" required>
                    </div>
                    <div>
                        <label><?php echo ($lang == 'ar' ? 'كلمة المرور الجديدة' : 'New Password'); ?></label>
                        <input type="password" name="new_password" placeholder="<?php echo ($lang == 'ar' ? 'اتركه فارغاً للإبقاء على القديمة' : 'Leave blank to keep current'); ?>">
                        <input type="hidden" name="current_password_hash" value="<?php echo htmlspecialchars($settings['admin_password']); ?>">
                    </div>
                </div>

                <button type="submit" name="update_settings" class="btn btn-primary" style="width:100%; margin-top:20px;">
                    <?php echo ($lang == 'ar' ? 'حفظ التغييرات' : 'Save Changes'); ?>
                </button>
            </form>

            <hr style="margin: 40px 0; border: 0; border-top: 1px solid rgba(255,255,255,0.1);">

            <h3 class="accent-text"><?php echo ($lang == 'ar' ? 'روابط التواصل الاجتماعي' : 'Social Media Links'); ?></h3>
            <form method="POST" class="contact-form" style="background:var(--bg-secondary); padding:20px; border-radius:10px; margin-bottom:20px;">
                <div class="input-row">
                    <input type="text" name="plat" placeholder="<?php echo ($lang == 'ar' ? 'اسم المنصة (مثال: GitHub)' : 'Platform Name (e.g. GitHub)'); ?>" required>
                    <select name="icon" style="background:var(--bg-primary); color:var(--text-primary); border:1px solid rgba(255,255,255,0.1); padding:15px; border-radius:8px;" required>
                        <option value="" disabled selected><?php echo ($lang == 'ar' ? 'اختر الأيقونة' : 'Select Icon'); ?></option>
                        <option value="fab fa-github">GitHub</option>
                        <option value="fab fa-linkedin-in">LinkedIn</option>
                        <option value="fab fa-twitter">Twitter / X</option>
                        <option value="fab fa-instagram">Instagram</option>
                        <option value="fab fa-youtube">YouTube</option>
                        <option value="fab fa-facebook-f">Facebook</option>
                        <option value="fab fa-discord">Discord</option>
                        <option value="fas fa-envelope">Email</option>
                        <option value="fas fa-globe">Website</option>
                    </select>
                    <input type="url" name="url" placeholder="https://..." required>
                </div>
                <button type="submit" name="add_social" class="btn btn-outline" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-plus"></i> <?php echo ($lang == 'ar' ? 'إضافة رابط' : 'Add Link'); ?>
                </button>
            </form>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><?php echo ($lang == 'ar' ? 'المنصة' : 'Platform'); ?></th>
                            <th><?php echo ($lang == 'ar' ? 'الأيقونة' : 'Icon'); ?></th>
                            <th><?php echo ($lang == 'ar' ? 'الرابط' : 'URL'); ?></th>
                            <th><?php echo ($lang == 'ar' ? 'إجراء' : 'Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($socials as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['platform_name']); ?></td>
                            <td><i class="<?php echo htmlspecialchars($s['icon_class']); ?>" style="font-size: 1.2rem;"></i></td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($s['url']); ?></td>
                            <td>
                                <a href="?tab=settings&delete_social=<?php echo $s['id']; ?>" class="delete-btn" onclick="return confirm('<?php echo ($lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Delete?'); ?>')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </main>

    <div id="messageModal" class="msg-modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2 id="modalSubject" class="accent-text" style="margin-bottom: 20px;"></h2>
            <div style="margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                <p><strong><?php echo ($lang == 'ar' ? 'من:' : 'From:'); ?></strong> <span id="modalName"></span> (<span id="modalEmail"></span>)</p>
                <p><strong><?php echo ($lang == 'ar' ? 'هاتف:' : 'Phone:'); ?></strong> <span id="modalPhone"></span></p>
            </div>
            <div id="modalBody" style="line-height: 1.6; white-space: pre-wrap;"></div>
        </div>
    </div>

    <script>
    function viewMessage(msg) {
        document.getElementById('modalSubject').innerText = msg.subject;
        document.getElementById('modalName').innerText = msg.first_name + ' ' + msg.last_name;
        document.getElementById('modalEmail').innerText = msg.email;
        document.getElementById('modalPhone').innerText = msg.phone || 'N/A';
        document.getElementById('modalBody').innerText = msg.message;
        document.getElementById('messageModal').style.display = "block";

        // Auto-mark as read
        if(msg.is_read == 0) {
            window.location.href = "?toggle_read=" + msg.id + "&tab=messages";
        }
    }

    function closeModal() {
        document.getElementById('messageModal').style.display = "none";
    }

    window.onclick = function(event) {
        let modal = document.getElementById('messageModal');
        if (event.target == modal) {
            closeModal();
        }
    }
    </script>

</body>
</html>