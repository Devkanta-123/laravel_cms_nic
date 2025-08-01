<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageSectionMasterController;
use App\Http\Controllers\CategoryMasterController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\LevelMasterController;
use App\Http\Controllers\WhosWhoController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\AuditController;

Route::get('/', [HomeController::class, 'index']);


//FRONT END WEBSIE
Route::get('get_mainmenus', [HomeController::class, 'get_mainmenus']);

Route::get('get_rightmenus', [HomeController::class, 'get_rightmenus']);

Route::get('get_carousel', [HomeController::class, 'getCarousel']);
Route::post('/approve_carousel', [HomeController::class, 'approveCarousel']); //for publisher approval
Route::post('/rejectedSlide', [HomeController::class, 'rejectedSlide']); //for publisher rejected
Route::get('get_banner', [HomeController::class, 'getBanner']);
Route::get('get_logo', [HomeController::class, 'getLogo']);
Route::get('getDashboardData', [HomeController::class, 'getDashboardData']); //for Dashboard Count data 
Route::get('getAdminDashboardStatistics', [HomeController::class, 'getAdminDashboardStatistics']); //for Dashboard statistic data 
Route::post('addVisitCount', [HomeController::class, 'addVisitCount']); //for visit count 
Route::get('getVisitorCount', [HomeController::class, 'getVisitorCount']); //fetch visit count 
Route::put('approved_logo', [HomeController::class, 'approveLogo']);
Route::post('update_logo', [HomeController::class, 'updateLogo']);
Route::post('rejected_logo', [HomeController::class, 'rejectLogo']);
Route::post('delete_logo', [HomeController::class, 'deleteLogo']);
Route::get('get_website_description', [HomeController::class, 'getWebsiteDescription']);

Route::get('/get_page_content/{menuId}', [HomeController::class, 'getPageContent']);
Route::get('/get_page_content_bypublisher', [HomeController::class, 'getPageContentByPublisher']);
Route::post('/approved_paragraph', [HomeController::class, 'approvedParagraph']);
Route::post('/api/rejected_paragraph', [HomeController::class, 'rejectedParagraph']);
Route::get('/getActivateLanguages', [HomeController::class, 'getActivateLanguages']);

Route::get('/page/{id}', [HomeController::class, 'index']);

Route::get('/get_latest_news', action: [HomeController::class, 'getLatestNews']); //added by dev on 14/11/24
Route::post('/archiveSingleNews', action: [HomeController::class, 'archiveSingleNews']); //added by dev on 30/07/25
Route::post('/restoreArchivedNew', action: [HomeController::class, 'restoreArchivedNew']); //added by dev on 31/07/25
Route::post('/approved_latestnews', action: [HomeController::class, 'approvedLatestNews']); //added by dev on 06/04/25

Route::get('/get_paragraphs', action: [HomeController::class, 'getParagraphs']); //added by dev on 14/11/24
Route::get('/get_cards', action: [HomeController::class, 'getCards']); //added by dev on 15/11/24
Route::put('/approved_cards', action: [HomeController::class, 'approveCards']); //added by dev on 09/05/25
Route::post('/rejected_card', action: [HomeController::class, 'rejectedCard']); //added by dev on 23/06/25
Route::post('/api/deleted_card', action: [HomeController::class, 'deletedCard']); //added by dev on 23/06/25
Route::get('/get_footer', action: [HomeController::class, 'getFooter']); //added by dev on 19/11/24
Route::get('/get_galleries', action: [HomeController::class, 'getGalleries']); //added by dev on 22/11/24
Route::post('/approved_gallery', action: [HomeController::class, 'approveGallery']); //added by dev on 06/05/24
Route::post('/rejected_gallery', action: [HomeController::class, 'rejectedGallery']); //added by dev on 23/06/25
Route::post('/api/delete_gallery', action: [HomeController::class, 'deleteGalleryImage']); //added by dev on 23/06/25
Route::get('/get_newsletter', action: [HomeController::class, 'getNewsLetter']); //added by dev on 04/12/24
Route::get('/get_allpagemenu', action: [HomeController::class, 'getAllPageMenu']); //added by dev on 09/05/25
// In routes/web.php or routes/api.php, depending on your setup
Route::get('/get_newsletter_detail/{newsId}', [HomeController::class, 'getNewsLetterDetails']);

Route::get('/get_card_detail/{cardId}', [HomeController::class, 'getCardDetail']);
Route::get('/get_paragraph_ByCardID/{cardId}', [HomeController::class, 'getparagraphByCardID']);
Route::get('/get_notifications', action: [NotificationsController::class, 'getAllNotifications']);
Route::put('/approved_noticeboard', action: [NotificationsController::class, 'approveNoticeBoard']); //adde by Dev on 08/05/25
Route::get('/get_notificationsforcurrentmonth', action: [NotificationsController::class, 'getNotificationsForCurrentMonth']);
Route::get('/get_recruitmentsforcurrentmonth', action: [NotificationsController::class, 'getRecruitmentsForCurrentMonth']);
Route::get('/get_notificationbycategory/{pagename}', action: [NotificationsController::class, 'getNotificationsByCategory']);
Route::get('/get_faq', action: [FAQController::class, 'getFAQData']); //added by dev on 19/11/24
Route::put('/approved_faq', action: [FAQController::class, 'approveFAQ']); //added by dev on 19/11/24
Route::get('/get_whoswho', action: [WhosWhoController::class, 'getWhosWho']); //added by dev on 25/03/25
Route::put('/approved_whoswho', action: [WhosWhoController::class, 'approveWhosWho']); //added by dev on 13/05/25
Route::get('/get_contactus', action: [MapController::class, 'getMapData']); //added by dev on 25/03/25
Route::put('/approved_map', action: [MapController::class, 'approveMap']); //added by dev on 25/03/25
Route::post('/rejected_map', action: [MapController::class, 'rejectedMap']); //added by dev on 27/06/25
Route::post('/deleted_map', action: [MapController::class, 'deleteMap']); //added by dev on 27/06/25
Route::get('/get_archivedata', action: [HomeController::class, 'getArchiveData']); //added by dev on 29/05/25
Route::get('/generateCaptcha', [UserController::class, 'generateCaptcha']);








Route::middleware(('auth'))->group(function () {

    Route::get('/api/get_users', [UserController::class, 'index']);
    Route::get('/api/get_user', [UserController::class, 'get_user']);
    Route::get('/api/get_allpublisher', [UserController::class, 'getAllPublisher']);
    Route::get('/api/get_roles', [UserController::class, 'get_roles']);

    Route::post('/api/users', [UserController::class, 'store']);

    Route::post('/api/roles', [UserController::class, 'storeRoles']);

    Route::post('/api/update_users/{user}', [UserController::class, 'update']);

    Route::post('/api/update_user_status', [UserController::class, 'updateUserStatus']);
    Route::post('/api/update_roles/{role}', [UserController::class, 'updateRole']);

    Route::post('/api/delete_user/{user}', [UserController::class, 'destroy']);

    Route::post('/api/change_role/{user}', [UserController::class, 'change_role']);

    //THEMES

    Route::post('/api/get_themes', [ThemeController::class, 'getThemes']);

    Route::post('/api/save_theme', [ThemeController::class, 'saveTheme']);

    //WEBSITE

    Route::get('get_website_details', [UserController::class, 'get_website_details']);

    Route::post('/api/save-contentWebsite', [HomeController::class, 'saveContentWebsite']);

    //MENU
    Route::get('/api/get_menus', [MenuController::class, 'index']);

    Route::get('/api/menu_types', [MenuController::class, 'MenuTypes']);


    //Add the Page Section Master data to database
    Route::post('/api/save_page_section_master', [PageSectionMasterController::class, 'store_page_section_master']); //added by Dev on 10/02/2025
    //GET PAGE SECTION MASTER MENU 
    Route::get('/api/getPageSectionMaster', [PageSectionMasterController::class, 'get_page_section_master']); //added by Dev on 10/02/2025
    // Update the page section master
    Route::post('/api/update_page_section_master/{id}', [PageSectionMasterController::class, 'update_page_section_master']); //added by Dev on 10/02/2025
    //Add the Category Master data to database 
    Route::post('/api/addCategoryMaster', [CategoryMasterController::class, 'addCategoryMaster']); //added by Dev on 18/02/2025
    //GET CATEGORY MASTER MENU
    Route::get('/api/getAllCategoryMaster', [CategoryMasterController::class, 'getAllCategoryMaster']); //added by Dev on 18/02/2025
    Route::get('/api/getAllLevelMaster', [LevelMasterController::class, 'getAllLevelMaster']); //added by Dev on 18/02/2025
    //EDIT CATEGORY MASTER MENU
    Route::post('/api/editCategoryMaster', [CategoryMasterController::class, 'editCategoryMaster']); //added by Dev on 18/02/2025
    Route::post('/api/delete_mastercategory', [CategoryMasterController::class, 'deleteCategoryMaster']); //added by Dev on 21/07/2025
    Route::post('/api/submitNotificationsData', [NotificationsController::class, 'submitNotificationsData']); //added by Dev on 24/02/2025
    Route::post('/api/updateNotice', [NotificationsController::class, 'updateNotice']); //added by Dev on 12/06/2025
    Route::post('/api/rejectedNotification', [NotificationsController::class, 'rejectedNotification']); //added by Dev on 16/06/2025
    Route::get('/api/get_archivitylog', [NotificationsController::class, 'getActivityLog']); //added by Dev on 16/06/2025
    Route::get('/api/get_auditlogs', [AuditController::class, 'getAuditLogs']); //added by Dev on 07/07/2025
    Route::post('/api/deleteNotification', [NotificationsController::class, 'deleteNotification']); //added by Dev on 12/06/2025
    Route::post('/api/submitFAQData', [FAQController::class, 'submitFAQData']); //added by Dev on 03/03/2025
    Route::post('/api/update_faq/{id}', [FAQController::class, 'updateFAQ']); //added by Dev on 03/03/2025
    Route::post('/api/deleted_faq', [FAQController::class, 'deleteFAQ']); //added by Dev on 24/06/2025
    Route::post('/rejectedFAQ', [FAQController::class, 'rejectedFAQ']); //added by Dev on 05/06/2025

    //ADD LEVEL MASTER 
    Route::post('/api/addLevelMaster', [LevelMasterController::class, 'addLevelMaster']); //added by Dev on 25/03/2025
    //GET LEVEL MASTER MENU
    Route::get('/api/getAllLevelMaster', [LevelMasterController::class, 'getAllLevelMaster']); //added by Dev on 25/03/2025
    Route::post('/api/editLevelMaster', [LevelMasterController::class, 'editLevelMaster']); //added by Dev on 21/07/2025
    Route::post('/api/delete_levelmaster', [LevelMasterController::class, 'deleteLevelMaster']); //added by Dev on 21/07/2025
    //add whos who data 
    Route::post('/api/addWhosWho', [WhosWhoController::class, 'addWhosWho']); //added by Dev on 25/03/2025
    Route::post('/api/updateWhosWho', [WhosWhoController::class, 'updateWHosWho']); //added by Dev on 26/06/2025
    Route::post('/api/rejected_whoswho', [WhosWhoController::class, 'rejectedWhosWho']); //added by Dev on 26/06/2025
    Route::post('/api/deleteWhosWho', [WhosWhoController::class, 'deleteWhosWho']); //added by Dev on 26/06/2025

    Route::post('/api/menus', [MenuController::class, 'store']);

    Route::post('/api/delete_menu/{menu}', [MenuController::class, 'destroy']);

    Route::post('/api/update_menu/{menu}', [MenuController::class, 'update']);

    Route::get('/api/get_page_menu/{id}', [HomeController::class, 'getPageMenu']); //added by dev on 09/12/2024

    //     //Slides MAnager
    Route::get('/api/fetchSliderImages', [SectionsController::class, 'fetchSliderImages']);

    Route::post('/api/uploadSliderImage', [SectionsController::class, 'uploadSliderImages']);

    //Pages  
    Route::get('/api/get_pages', [PagesController::class, 'index']);

    Route::post('/api/get_page_details/{menu}', [PagesController::class, 'get_page_details']);

    Route::post('/api/save-content', [HomeController::class, 'saveContent']);
    Route::put('/api/update-content', [HomeController::class, 'updateContent']);

    //Main Component: header and footer

    Route::get('/api/getComponentsDetails', [HomeController::class, 'getComponentsDetails']);

    Route::post('/api/update_component/{component}', [HomeController::class, 'updateComponent']);

    Route::post('/api/delete_component/', [HomeController::class, 'deleteComponent']);

    //CAROUSEL  

    Route::post('/api/upload_carousel', [HomeController::class, 'uploadCarousel']);

    Route::post('/api/delete_slide', [HomeController::class, 'deleteSlide']);

    //BANNER

    Route::post('/api/upload_banner', [HomeController::class, 'uploadBanner']);

    Route::post('/api/delete_banner', [HomeController::class, 'deleteBanner']);

    //logo
    Route::post('/api/upload_logo', [HomeController::class, 'uploadLogo']);


    //GALLERY

    Route::post('/api/upload_gallery', [HomeController::class, 'uploadGallery']);

    // Latest News

    Route::post('/api/save_latest_news', [HomeController::class, 'saveLatestNews']);
    Route::post('/rejected_latestNews', [HomeController::class, 'rejectedLatestNews']);
    Route::post('/api/get_latest_news', action: [HomeController::class, 'getLatestNews']);
    Route::post('/api/delete_news', [HomeController::class, 'deleteNews']);
    Route::post('/api/update_news_order', [HomeController::class, 'updateNewsOrder']);

    //Cards 
    Route::post('/api/save_card', [HomeController::class, 'save_card']);



    //PAGES DESIGN

    Route::get('/api/getAllComponents', [PagesController::class, 'getAllComponents']);

    Route::post('/api/save_component', [PagesController::class, 'saveComponent']);

    // Route::post('/api/delete_component', [PagesController::class, 'deleteComponent']);
    //MAP Controller

    Route::post('/api/addMapData', [MapController::class, 'storeMapData']);

    //Archieve Data saving
    Route::post('/api/saveArchiveData', [HomeController::class, 'saveArchiveData']);
    Route::post('/api/deleteArchiveData', [HomeController::class, 'deleteArchiveData']);
    Route::get('/api/getArchieveData', [HomeController::class, 'getArchieveData']);
    Route::post('/api/saveLanguage', [HomeController::class, 'saveLanguage']);
    Route::get('/api/getMasterLanguages', [HomeController::class, 'getMasterLanguages']);

    Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
});

