<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if a language change was requested via URL
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] == 'ar' ? 'ar' : 'en';
}

// Default to English if no session is set
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// The variable name here MUST match the one inside the t() function below
$translations = [
    'en' => [
        // Navigation
        'nav_home' => 'Home',
        'nav_exp' => 'Experience',
        'nav_skills' => 'Skills',
        'nav_contact' => 'Contact',
        'nav_login' => 'Login',
        'logout' => 'Logout',
        'back_to_site' => 'Back to Site',
        'btn_hire' => 'Hire Me',
        
        // General
        'hello' => "Hello I'm",
        'cv_btn' => 'Download CV',
        
        // Stats (Note: <br> is used for line breaks in the UI)
        'stat_years' => 'Years of <br> experience',
        'stat_projects' => 'Projects <br> completed',
        'stat_tech' => 'Technologies <br> mastered',
        'stat_commits' => 'Code <br> commits',
        
        // Sections
        'exp_title' => 'My Experience',
        'exp_desc' => 'I have worked with various companies and projects, honing my skills in software development and design.',
        'skills_title' => 'My Skills',
        'skills_desc' => 'I have a diverse set of skills ranging from front-end design to back-end development and system architecture.',
        
        // Contact Page
        'contact_title' => "Let's work together",
        'contact_desc' => "I am available for freelance work and full-time positions. Reach out and let's build something great.",
        'placeholder_fname' => "Firstname",
        'placeholder_lname' => "Lastname",
        'placeholder_email' => "Email address",
        'placeholder_phone' => "Phone number",
        'placeholder_subject' => "Select a service",
        'placeholder_textarea' => 'Type your message here...',
        'btn_send' => "Send message",
        'success_msg' => "Message sent successfully!",
        'label_phone' => 'Phone',
        'label_email' => 'Email',
        'label_address' => 'Address',
        
        // Login Page
        'login_title' => 'Admin Login',
        'login_subtitle' => 'Enter your credentials to access the dashboard.',
        'placeholder_user' => 'Username or Email',
        'placeholder_pass' => 'Password',
        'btn_login' => 'Login Now',
        'error_login' => 'Invalid username or password.',
        
        // Dashboard
        'dash_welcome' => 'Welcome, Admin',
        'tab_messages' => 'Messages',
        'tab_experience' => 'Experience',
        'tab_skills' => 'Skills',
        'btn_add_new' => 'Add New',
        'placeholder_skill_name_en' => 'Skill Name (English)',
        'placeholder_skill_name_ar' => 'Skill Name (Arabic)',
        'total_inquiries' => 'You have %d total inquiries',
        'tooltip_view' => 'View Message',
        'tooltip_status' => 'Toggle Read Status',
    ],

    'ar' => [
        // Navigation
        'nav_home' => 'الرئيسية',
        'nav_exp' => 'الخبرات',
        'nav_skills' => 'المهارات',
        'nav_contact' => 'تواصل معي',
        'nav_login' => 'تسجيل الدخول',
        'logout' => 'تسجيل الخروج',
        'back_to_site' => 'العودة للموقع',
        'btn_hire' => 'وظفني',
        
        // General
        'hello' => "أهلاً، أنا",
        'cv_btn' => 'تحميل السيرة الذاتية',
        
        // Stats
        'stat_years' => 'سنوات <br> الخبرة',
        'stat_projects' => 'مشروع <br> تم إنجازه',
        'stat_tech' => 'تقنية <br> متقنة',
        'stat_commits' => 'مساهمة <br> برمجية',
        
        // Sections
        'exp_title' => 'خبرتي',
        'exp_desc' => 'لقد عملت مع شركات ومشاريع مختلفة، مما ساعدني على صقل مهاراتي في تطوير البرمجيات والتصميم.',
        'skills_title' => 'مهاراتي',
        'skills_desc' => 'أمتلك مجموعة متنوعة من المهارات التي تتراوح من تصميم الواجهات الأمامية إلى تطوير الأنظمة الخلفية وهندسة البرمجيات.',
        
        // Contact Page
        'contact_title' => "لنعمل معاً",
        'contact_desc' =>"أنا متاح للعمل الحر والوظائف بدوام كامل. تواصل معي. اذا كنت مهتم.",
        'placeholder_fname' => "الاسم الأول",
        'placeholder_lname' => "اسم العائلة",
        'placeholder_email' => "البريد الإلكتروني",
        'placeholder_phone' => "رقم الهاتف",
        'placeholder_subject' => "اختر الخدمة",
        'placeholder_textarea' => 'اكتب رسالتك هنا...',
        'btn_send' => "إرسال",
        'success_msg' => "تم إرسال الرسالة بنجاح!",
        'label_phone' => 'الهاتف',
        'label_email' => 'البريد الإلكتروني',
        'label_address' => 'العنوان',
        
        // Login Page
        'login_title' => 'دخول المسؤول',
        'login_subtitle' => 'أدخل بيانات الاعتماد للوصول إلى لوحة التحكم.',
        'placeholder_user' => 'اسم المستخدم أو البريد',
        'placeholder_pass' => 'كلمة المرور',
        'btn_login' => 'تسجيل الدخول',
        'error_login' => 'اسم المستخدم أو كلمة المرور غير صحيحة.',
        
        // Dashboard
        'dash_welcome' => 'مرحباً، أيها المسؤول',
        'tab_messages' => 'الرسائل',
        'tab_experience' => 'الخبرات',
        'tab_skills' => 'المهارات',
        'btn_add_new' => 'إضافة',
        'placeholder_skill_name_en' => 'اسم المهارة (إنجليزي)',
        'placeholder_skill_name_ar' => 'اسم المهارة (عربي)',
        'total_inquiries' => 'لديك %d رسائل إجمالاً',
        'tooltip_view' => 'عرض الرسالة',
        'tooltip_status' => 'تغيير حالة القراءة',
    ]
];

function t($key) {
    global $translations, $lang;
    // Returns the translated string, or the key name if the translation is missing
    return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
}
?>