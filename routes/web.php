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

Route::get('/', [HomeController::class, 'index']);


//FRONT END WEBSIE
Route::get('get_mainmenus', [HomeController::class, 'get_mainmenus']);

Route::get('get_rightmenus', [HomeController::class, 'get_rightmenus']);

Route::get('get_carousel', [HomeController::class, 'getCarousel']);

Route::get('get_banner', [HomeController::class, 'getBanner']);

Route::get('get_website_description', [HomeController::class, 'getWebsiteDescription']);

Route::get('/get_page_content/{menuId}', [HomeController::class, 'getPageContent']);

Route::get('/page/{id}', [HomeController::class, 'index']);

Route::get('/get_latest_news', action: [HomeController::class, 'getLatestNews']); //added by dev on 14/11/24
Route::get('/get_paragraphs', action: [HomeController::class, 'getParagraphs']); //added by dev on 14/11/24
Route::get('/get_cards', action: [HomeController::class, 'getCards']); //added by dev on 15/11/24
Route::get('/get_footer', action: [HomeController::class, 'getFooter']); //added by dev on 19/11/24
Route::get('/get_galleries', action: [HomeController::class, 'getGalleries']); //added by dev on 22/11/24
Route::get('/get_newsletter', action: [HomeController::class, 'getNewsLetter']); //added by dev on 04/12/24
// In routes/web.php or routes/api.php, depending on your setup
Route::get('/get_newsletter_detail/{newsId}', [HomeController::class, 'getNewsLetterDetails']);

Route::get('/get_card_detail/{cardId}', [HomeController::class, 'getCardDetail']);
Route::get('/get_paragraph_ByCardID/{cardId}', [HomeController::class, 'getparagraphByCardID']);
Route::get('/get_notifications', action: [NotificationsController::class, 'getAllNotifications']);
Route::get('/get_notificationsforcurrentmonth', action: [NotificationsController::class, 'getNotificationsForCurrentMonth']);
Route::get('/get_recruitmentsforcurrentmonth', action: [NotificationsController::class, 'getRecruitmentsForCurrentMonth']);
Route::get('/get_notificationbycategory/{pagename}', action: [NotificationsController::class, 'getNotificationsByCategory']);
Route::get('/get_faq', action: [FAQController::class, 'getFAQData']); //added by dev on 19/11/24






Route::middleware(('auth'))->group(function () {

    Route::get('/api/get_users', [UserController::class, 'index']);

    Route::get('/api/get_user', [UserController::class, 'get_user']);

    Route::get('/api/get_roles', [UserController::class, 'get_roles']);

    Route::post('/api/users', [UserController::class, 'store']);

    Route::post('/api/roles', [UserController::class, 'storeRoles']);

    Route::post('/api/update_users/{user}', [UserController::class, 'update']);

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
    //EDIT CATEGORY MASTER MENU
    Route::post('/api/editCategoryMaster', [CategoryMasterController::class, 'editCategoryMaster']); //added by Dev on 18/02/2025
    Route::post('/api/submitNotificationsData', [NotificationsController::class, 'submitNotificationsData']); //added by Dev on 24/02/2025
    Route::post('/api/submitFAQData', [FAQController::class, 'submitFAQData']); //added by Dev on 03/03/2025



    Route::post('/api/menus', [MenuController::class, 'store']);

    Route::post('/api/delete_menu/{menu}', [MenuController::class, 'destroy']);

    Route::post('/api/update_menu/{menu}', [MenuController::class, 'update']);

    Route::get('/api/get_page_menu/{id}', [HomeController::class, 'getPageMenu']);//added by dev on 09/12/2024

    //     //Slides MAnager
    Route::get('/api/fetchSliderImages', [SectionsController::class, 'fetchSliderImages']);

    Route::post('/api/uploadSliderImage', [SectionsController::class, 'uploadSliderImages']);

    //Pages  
    Route::get('/api/get_pages', [PagesController::class, 'index']);

    Route::post('/api/get_page_details/{menu}', [PagesController::class, 'get_page_details']);

    Route::post('/api/save-content', [HomeController::class, 'saveContent']);

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


    //GALLERY

    Route::post('/api/upload_gallery', [HomeController::class, 'uploadGallery']);

    // Latest News

    Route::post('/api/save_latest_news', [HomeController::class, 'saveLatestNews']);
    Route::post('/api/get_latest_news', action: [HomeController::class, 'getLatestNews']);
    Route::post('/api/delete_news', [HomeController::class, 'deleteNews']);
    Route::post('/api/update_news_order', [HomeController::class, 'updateNewsOrder']);

    //Cards 
    Route::post('/api/save_card', [HomeController::class, 'save_card']);



    //PAGES DESIGN

    Route::get('/api/getAllComponents', [PagesController::class, 'getAllComponents']);

    Route::post('/api/save_component', [PagesController::class, 'saveComponent']);

    Route::post('/api/delete_component', [PagesController::class, 'deleteComponent']);


    Route::get('{view}', ApplicationController::class)->where('view', '(.*)');




});





