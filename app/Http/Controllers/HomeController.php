<?php

namespace App\Http\Controllers;

use App\Helpers\ThemeHelper;
use App\Models\Carousel;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\NewsletterDetails;
use App\Models\Paragraph;
use Illuminate\Support\Facades\DB;
use App\Models\LatestNews;
use App\Models\Website;
use App\Models\Menu;
use App\Models\Banner;
use App\Models\Logo;
use App\Models\Cards;
use App\Models\User;
use App\Models\AppTrack;
use App\Models\WebsiteSettings;
use App\Models\LanguageMaster;
use Carbon\Carbon;
use App\Models\Footer;
use App\Models\Newsletter;
use App\Models\WebsiteCache;
use App\Models\PageSectionMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\HtmlSanitizer;

class HomeController extends Controller
{


    public function index()
    {
        $website = Website::all();
        $user = Auth::user();


        $folderPath = ThemeHelper::getFolderPath();

        if ($folderPath) {
            $viewPath = $folderPath . '/home';

            return view('home', compact('website', 'folderPath'));
        } else {
            abort(404, 'Theme not found');
        }
    }

    public function getComponentsDetails()
    {

        return array(Website::all(), Footer::orderBy('type')->get());
    }

    public function updateComponent(Request $request, $websiteId)
    {

        if ($websiteId == 1) // HEADER id = 1
        {
            $website = Website::findOrFail($websiteId);

            if ($request->hasFile('logo')) {
                if ($website->logo && Storage::disk('public')->exists($website->logo)) {
                    Storage::disk('public')->delete($website->logo);
                }
                $file = $request->file('logo');
                $folderPath = ThemeHelper::getFolderPath();
                $viewPath = $folderPath . '/header';
                $filePath = $file->store($viewPath, 'public');
                $website->logo = $filePath;
            }
            $website->title = $request->title;
            $website->website_short = $request->short_form;
            $website->save();

            return response()->json($website);
        } else //FOOTER 
        {

            $footer = Footer::find($request->id);
            $newFileName = $request->logoUrl;
            if ($request->type == 'logo' && $request->hasFile('link')) {
                if ($request->link && Storage::disk('public')->exists($request->link)) {
                    Storage::disk('public')->delete($request->link);
                }
                $file = $request->file('link');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $originalName = preg_replace('/\s+/', '_', $originalName);
                $extension = $file->getClientOriginalExtension();
                $id = $footer ? $request->id : random_int(100, 1000);
                $newFileName = $originalName . '_' . $id . '.' . $extension;

                $folderPath = ThemeHelper::getFolderPath();
                $viewPath = $folderPath . '/footer';

                $filePath = $file->storeAs($viewPath, $newFileName, 'public');
                if (!$filePath) {
                    // Add debug information
                    throw new \Exception('File path is empty. File might not have been stored correctly.');
                }

                // Log file path for debugging
                //Log::info('File stored at path: ' . $filePath);
            }
            //quicklink edit

            if ($request->type == 'quicklink' && $footer) {
                $footer->type = $request->type;
                $footer->link = $request->quicklink;
                $footer->order = $request->order;
                $footer->content = $request->content;
                $footer->save();
            }

            if ($footer) {
                $footer->type = $request->type;
                $footer->link = $newFileName;
                $footer->order = $request->order;
                $footer->content = $request->content;
                $footer->save();
            } else if ($request->type == 'quicklink') { //create the quick link

                Footer::create([
                    'type' => $request->type,
                    'content' => $request->content,
                    'order' => $request->order,
                    'link' => $request->quicklink
                ]);
            } else {                              //create for link
                Footer::create([
                    'type' => $request->type,
                    'content' => $request->content,
                    'order' => $request->order,
                    'link' => $newFileName
                ]);
            }
        }
    }

    // public function deleteComponent(Request $request)
    // {
    //     if ($request->id) {
    //         $footer = Footer::where('id', $request->id)->first();
    //         if ($footer) {

    //             if (Storage::disk('smb_footer')->exists($footer->link)) {
    //                 Storage::disk('smb_footer')->delete($footer->link);
    //             }
    //         }
    //         $footer->delete();
    //         return response()->noContent();
    //     }
    // }


    // Example PHP Laravel controller

    public function deleteComponent(Request $request)
    {
        $componentId = $request->input('component_id');
        $menuId = $request->input('menu_id');
        $hasDependency = $request->input('has_dependency');
        $page_section_master_id = $request->input('page_section_master_id');

        if ($hasDependency) {
            // Delete from details table first
            DB::table('page_section')
                ->where('id', $componentId)
                ->where('menu_id', $menuId)
                ->delete();
        }

        // Then delete from master component table
        //DB::table('page_section_master')->where('id', $page_section_master_id)->delete();

        return response()->json(['status' => 'success']);
    }

    // public function saveContent(Request $request)
    // {

    //     $request->validate([
    //         'content' => 'required|string',
    //         'menu' => 'required',
    //         'page_section' => 'required',
    //     ]);

    //     $paragraph = Paragraph::updateOrCreate(
    //         [
    //             'menu_id' => $request->menu,
    //             'page_section_id' => $request->page_section,
    //         ],
    //         [
    //             'title' => '',  
    //             'description' => $request->content,
    //             'hindi_description' => '',
    //             'khasi_description' => '',
    //             'status' => "1"
    //         ]
    //     );

    //     return response()->json(['success' => true, 'data' => $paragraph]);
    // }

    // public function saveContent(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'content' => 'required|string',
    //         'menu' => 'required',
    //         'page_section' => 'required',
    //     ]);

    //     // Extract the content from the request
    //     $content = $request->content;

    //     // Use regex to extract the first <p>...</p> as the title
    //     preg_match('/<p>(.*?)<\/p>/is', $content, $matches);
    //     $title = $matches[0] ?? ''; // Extract the first <p> block, or empty if not found

    //     // Remove the extracted <p>...</p> from the content for the description
    //     $description = str_replace($title, '', $content);

    //     // Save the data using updateOrCreate
    //     $paragraph = Paragraph::updateOrCreate(
    //         [
    //             'menu_id' => $request->menu,
    //             'page_section_id' => $request->page_section,
    //         ],
    //         [
    //             'title' => $title, // Save the first paragraph as the title
    //             'description' => trim($description), // Save the rest as the description
    //             'hindi_description' => '',
    //             'khasi_description' => '',
    //             'status' => "1"
    //         ]
    //     );

    //     // Return a JSON response
    //     return response()->json(['success' => true, 'data' => $paragraph]);
    // }
    public function saveContent(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'content' => 'nullable|string',
            'menu_id' => 'required|integer',
            'page_section_id' => 'required|integer',
            'publisher_id' => 'nullable|integer'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Determine the flag based on role
        $flag = match ($user->role_id) {
            2 => 'A', // Admin
            3 => 'N', // Creator
            default => null,
        };

        if (!$flag) {
            return response()->json(['message' => 'Invalid role'], 403);
        }

        // Sanitize the content using helper
        $content = HtmlSanitizer::sanitize($request->content ?? '');
        $publisher = User::find($request->publisher_id);
        $isUpdate = false;

        // Update case
        if ($request->has('id')) {
            $paragraph = Paragraph::find($request->id);
            if (!$paragraph) {
                return response()->json(['message' => 'Paragraph not found'], 404);
            }

            $paragraph->description = $content;
            $paragraph->flag = $flag;
            $paragraph->user_id = $user->id;
            $paragraph->role_id = $user->role_id;
            $paragraph->publisher_id = $request->publisher_id ?? null;
            $paragraph->save();
            $isUpdate = true;

            AppTrack::create([
                'application_id' => $paragraph->application_id,
                'menu_id' => $request->menu_id,
                'page_section_master_id' => $request->page_section_id,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $request->publisher_id,
                'user_to_name' => $publisher?->name,
                'remarks' => 'Paragraph updated: ' . Str::limit($content, 15, '...'),
                'flag' => 'U',
                'action' => 'Updated'
            ]);
        } else {
            // Create case
            $applicationId = 'PARA' . now()->format('YmdHis');

            $paragraph = Paragraph::create([
                'menu_id' => $request->menu_id,
                'page_section_id' => $request->page_section_id,
                'title' => '',
                'description' => $content,
                'hindi_description' => '',
                'khasi_description' => '',
                'status' => '1',
                'flag' => $flag,
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'application_id' => $applicationId,
                'publisher_id' => $request->publisher_id ?? null
            ]);

            AppTrack::create([
                'application_id' => $applicationId,
                'menu_id' => $request->menu_id,
                'page_section_master_id' => $request->page_section_id,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $request->publisher_id,
                'user_to_name' => $publisher?->name,
                'remarks' => 'Paragraph submitted: ' . Str::limit($content, 15, '...'),
                'flag' => $flag,
                'action' => 'Add',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $isUpdate ? 'Content updated successfully!' : 'Content saved successfully!',
            'data' => $paragraph
        ]);
    }



    public function updateContent(Request $request) //Paragraph Content
    {
        $request->validate([
            'menu' => 'required|integer',
            'content' => 'required|string'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $page = Paragraph::where('menu_id', $request->menu)->first();
        if (!$page) {
            return response()->json(['message' => 'Content not found'], 404);
        }

        // Determine flag based on role_id
        if ($user->role_id == 2) {
            $flag = 'A'; // Admin update - auto approved
        } elseif ($user->role_id == 3) {
            $flag = 'U'; // Content creator update - mark for review/update
        } else {
            return response()->json(['message' => 'Invalid role'], 403);
        }

        $page->new_description = $request->content; // Use correct column (you were using 'content' but saveContent uses 'description')
        $page->flag = $flag;
        $page->user_id = $user->id;
        $page->role_id = $user->role_id;
        $page->save();

        return response()->json(['message' => 'Content updated successfully']);
    }



    public function saveContentWebsite(Request $request)
    {
        $request->validate([
            'content1' => 'required|string',
            'content2' => 'string',
            'menu' => 'required|string',
        ]);

        $page = Website::update(
            [
                'description' => $request->content1,
                'hindi_description' => $request->content2,
            ],
        );

        return response()->json(['success' => true, 'data' => $page]);
    }

    // public function uploadCarousel(Request $request)
    // {
    //     $request->validate([
    //         'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:20480' // 20MB
    //     ]);
    //     // Get logged-in user
    //     $user = Auth::user(); // This will get the authenticated user
    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }
    //     //before submit check roleid and set flag value
    //     // Determine flag based on role_id
    //     if ($user->role_id == 2) { //if admin upload 
    //         $flag = 'A';
    //     } elseif ($user->role_id == 3) { //if contentcreator upload
    //         $flag = 'N';
    //     }
    //     // Handle file upload
    //     $uploadedImages = [];
    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $image) {
    //             $filename = $image->getClientOriginalName();
    //             $path = $image->store('slides', 'public'); // Store in /public/storage/slides directory
    //             $uploadedImages[] = $filename;


    //             Carousel::create([
    //                 'image' => $path,
    //                 'link' => $path,
    //                 'type' => 'Slider',
    //                 'user_id' => $user->id,
    //                 'role_id' => $user->role_id,
    //                 'flag' => $flag
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Images uploaded successfully', 'filenames' => $uploadedImages]);
    // }
    public function uploadCarousel(Request $request)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:20480' // 20MB
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $publisher = User::find($request->publisher_id);
        $flag = null;
        if ($user->role_id == 2) {
            $flag = 'A';
        } elseif ($user->role_id == 3) {
            $flag = 'N';
        }

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();
                $path = $image->store('slides', 'public'); // e.g. slides/abcd.jpg
                $uploadedImages[] = $filename;
                $applicationId = 'CAR' . now()->format('YmdHis');
                // Save in Carousel table
                $carousel = Carousel::create([
                    'image' => $path,
                    'link' => $path,
                    'type' => 'Slider',
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'flag' => $flag,
                    'publisher_id' => $request->publisher_id ?? null,
                    'application_id' => $applicationId
                ]);
                AppTrack::create([
                    'application_id' => $applicationId,
                    'menu_id' => $request->menu_id,
                    'page_section_master_id' => $request->page_section_master_id,
                    'user_from' => $user->id,
                    'user_from_name' => $user->name,
                    'user_to' => $request->publisher_id,
                    'user_to_name' => $publisher ? $publisher->name : null,
                    'remarks' => 'Carousel submitted: ' . $filename,
                    'flag' => $flag,
                    'action' => 'Add',
                ]);
                // Prepare single record for cache
                $carouselData = [
                    'carousel_id' => $carousel->id,
                    'image' => $carousel->image,
                    'link' => $carousel->link,
                    'type' => $carousel->type,
                    'flag' => $carousel->flag
                ];

                // Insert as a new row (row-wise) into WebsiteCache
                WebsiteCache::create([
                    'carousel_id' => $carousel->id,
                    'type' => 'carousel',
                    'data' => base64_encode(json_encode(['data' => [$carouselData]])),
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'flag' => $flag
                ]);
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'filenames' => $uploadedImages
        ]);
    }


    public function deleteSlide(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $user = Auth::user();
        $slide = Carousel::where('id', $request->id)->first();
        $userTo = User::find($slide->user_id);
        if ($slide) {
            if (Storage::disk('public')->exists($slide->link)) {

                Storage::disk('public')->delete($slide->link);
            }

            $slide->delete();
            // Add entry to AppTrack
            AppTrack::create([
                'menu_id' => $request->menu_id ?? null,
                'page_section_master_id' => $request->page_section_master_id ?? null,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $slide->user_id,
                'user_to_name' => $userTo ? $userTo->name : null,
                'remarks' => 'Carousel Deleted: ' . $slide->image,
                'action' => 'Deleted',
                'flag' => 'D',
                'application_id' => $slide->application_id

            ]);
            // Delete related cache entry
            WebsiteCache::where('carousel_id', $request->id)->delete();
            return response()->json(['message' => 'Slide deleted successfully']);
        }
    }

    //BANNER
    public function uploadBanner(Request $request)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Adjust max file size as needed
        ]);

        $images = $request->images;
        $menu_id = $request->menu_id;

        // Handle file upload
        $uploadedImages = [];
        if ($request->hasFile('images')) {

            $filename = $images[0]->getClientOriginalName();

            $path = $images[0]->store('banner', 'public'); // Store in /public/storage/slides directory
            $uploadedImages[] = $filename;


            Banner::create([
                'image' => $path,
                'menu_id' => $menu_id
            ]);
        }

        return response()->json(['message' => 'Banner uploaded successfully', 'filenames' => $uploadedImages]);
    }

    public function deleteBanner(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $banner = Banner::where('id', $request->id)->first();


        if ($banner) {

            if (Storage::disk('public')->exists($banner->image)) {

                Storage::disk('public')->delete($banner->image);
            }

            $banner->delete();
            return response()->json(['message' => 'Banner deleted successfully']);
        }
    }


    //GALLERY
    //save gallery image
    // public function uploadGallery(Request $request)
    // {
    //     $request->validate([
    //         'gallery_name' => 'required|string|max:255',
    //         'gallery_description' => 'required|string',
    //         'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Adjust max file size as needed
    //     ]);

    //     $user = Auth::user(); // This will get the authenticated user
    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }
    //     //before submit check roleid and set flag value
    //     // Determine flag based on role_id
    //     if ($user->role_id == 2) { //if admin upload 
    //         $flag = 'A';
    //     } elseif ($user->role_id == 3) { //if contentcreator upload
    //         $flag = 'N';
    //     }
    //     $galleryName = $request->input('gallery_name');
    //     $galleryDescription = $request->input('gallery_description');

    //     // Handle file upload
    //     $uploadedImages = [];
    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $image) {
    //             $filename = $image->getClientOriginalName();

    //             $folderPath = ThemeHelper::getFolderPath();
    //             $viewPath = $folderPath . '/gallery';

    //             $path = $image->store($viewPath, 'public'); // Store in /public/storage/gallery directory
    //             $uploadedImages[] = $filename;

    //             Gallery::create([
    //                 'gallery_image' => $path,
    //                 'link' => $path,
    //                 'parent_id' => 2,
    //                 'meta_title' => 'test gallery',
    //                 'meta_description' => 'test gallery',
    //                 'meta_keywords' => 'msrls gallery',
    //                 'order' => 1,
    //                 'status' => 1,
    //                 'gallery_description' => $galleryDescription,
    //                 'gallery_name' => $galleryName,
    //                 'user_id' => $user->id,
    //                 'role_id' => $user->role_id,
    //                 'flag' => $flag
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Gallery Images uploaded successfully', 'filenames' => $uploadedImages]);
    // }
    public function uploadGallery(Request $request)
    {
        $request->validate([
            'gallery_name' => 'required|string|max:255',
            'gallery_description' => 'required|string',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:20480', // Images or videos
            'items' => 'array',
            'items.*.name' => 'required|string|max:255',
            'items.*.file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:20480', // Images or videos
            'menu_id' => 'required|integer',
            'page_section_master_id' => 'required|integer',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $flag = null;
        if ($user->role_id == 2) { // admin
            $flag = 'A';
        } elseif ($user->role_id == 3) { // content creator
            $flag = 'N';
        }

        $galleryName = $request->input('gallery_name');
        $galleryDescription = $request->input('gallery_description');

        $folderPath = ThemeHelper::getFolderPath();

        // Create main gallery folder if not exists
        $galleryFolder = $folderPath . '/gallery';
        $galleryStoragePath = storage_path('app/public/' . $galleryFolder);
        if (!file_exists($galleryStoragePath)) {
            mkdir($galleryStoragePath, 0755, true);
        }

        // Create gallery items folder if not exists
        $itemFolder = $folderPath . '/gallery_items';
        $itemStoragePath = storage_path('app/public/' . $itemFolder);
        if (!file_exists($itemStoragePath)) {
            mkdir($itemStoragePath, 0755, true);
        }

        $uploadedFiles = [];
        $gallery = null;
        // ✅ Generate unique application_id
        $applicationId = 'GALL' . now()->format('YmdHis');
        $publisher = User::find($request->publisher_id);
        // Handle main gallery images/videos and create gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->store($galleryFolder, 'public');
                $uploadedFiles[] = $filename;
                if (!$gallery) {
                    $gallery = Gallery::create([
                        'application_id' => $applicationId,
                        'gallery_image' => $path,
                        'link' => $path,
                        'parent_id' => 2,
                        'meta_title' => 'test gallery',
                        'meta_description' => 'test gallery',
                        'meta_keywords' => 'msrls gallery',
                        'order' => 1,
                        'status' => 1,
                        'gallery_description' => $galleryDescription,
                        'gallery_name' => $galleryName,
                        'user_id' => $user->id,
                        'role_id' => $user->role_id,
                        'flag' => $flag,
                        'publisher_id' => $request->publisher_id ?? null,

                    ]);
                    // ✅ Insert into AppTrack
                    AppTrack::create([
                        'application_id' => $applicationId,
                        'menu_id' => $request->menu_id,
                        'page_section_master_id' => $request->page_section_master_id,
                        'user_from' => $user->id,
                        'user_from_name' => $user->name,
                        'user_to' => $request->publisher_id,
                        'user_to_name' => $publisher ? $publisher->name : null,
                        'remarks' => 'Gallery uploaded: ' . $galleryName,
                        'flag' => $flag,
                        'action' => 'Add',
                    ]);
                }
            }
        } else {
            // If no files uploaded, still create a gallery record
            $gallery = Gallery::create([
                'application_id' => $applicationId,
                'gallery_image' => null,
                'link' => null,
                'parent_id' => 2,
                'meta_title' => 'test gallery',
                'meta_description' => 'test gallery',
                'meta_keywords' => 'msrls gallery',
                'order' => 1,
                'status' => 1,
                'gallery_description' => $galleryDescription,
                'gallery_name' => $galleryName,
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'flag' => $flag
            ]);
            AppTrack::create([
                'application_id' => $applicationId,
                'menu_id' => $request->menu_id,
                'page_section_master_id' => $request->page_section_master_id,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $request->publisher_id,
                'user_to_name' => $publisher ? $publisher->name : null,
                'remarks' => 'Gallery uploaded: ' . $galleryName,
                'flag' => $flag,
                'action' => 'Add',
            ]);
        }

        // Handle gallery item files
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $itemName = $item['name'];

                if (isset($item['file']) && $item['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $itemFile = $item['file'];
                    $itemImagePath = $itemFile->store($itemFolder, 'public');

                    GalleryItem::create([
                        'gallery_id' => $gallery->id,
                        'name' => $itemName,
                        'image' => $itemImagePath,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Gallery Images and Items uploaded successfully',
            'filenames' => $uploadedFiles,
        ]);
    }


    //get galleries
    public function getGalleries(Request $request)
    {
        $flag = $request->query('flag') ?? $request->input('flag');

        $user = Auth::user();

        if ($flag === 'A') {
            $data = DB::select("
        SELECT g.*, gi.gallery_id, gi.name, gi.image 
        FROM gallery g 
        LEFT JOIN gallery_item gi ON g.id = gi.gallery_id 
        WHERE g.flag = 'A'
    ");

            return response()->json($data);
        }

        if (in_array($user->role_id, [2, 3, 4])) {
            $galleries = DB::table('gallery as g')
                ->join('users as u', 'u.id', '=', 'g.user_id')
                ->select('g.*', 'u.name as addedby')
                ->get();
        } else {
            $galleries = [];
        }

        return response()->json($galleries);
    }

    public function approveGallery(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:gallery,id'
        ]);
        $gallery = Gallery::find($request->id);
        $gallery->flag = 'A'; // Approve
        $gallery->save();
        $gall = Gallery::find($request->id);
        // Get current user (approver)
        $user = Auth::user();
        // Get notice creator
        $userTo = User::find($gall->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $gall->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Gallery approved: ' . $gall->gallery_name,
            'action' => 'Approved',
            'flag' => 'A',
            'application_id' => $gall->application_id

        ]);
        return response()->json(['success' => true, 'message' => 'Gallery approved successfully']);
    }

    public function rejectedGallery(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:gallery,id'
        ]);
        $gallery = Gallery::find($request->id);
        $gallery->flag = 'R';
        $gallery->rejected_remarks = $request->remarks;
        $gallery->save();
        $gall = Gallery::find($request->id);
        if (!$gall) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery data not found',
            ], 404);
        }
        $user = Auth::user();
        $userTo = User::find($gall->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $gall->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Gallery Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $gall->application_id
        ]);
        return response()->json(['success' => true, 'message' => 'Gallery  has been rejected']);
    }



    //delete gallery image
    public function deleteGalleryImage(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $gallery = Gallery::find($request->id);

        if ($gallery) {
            // Delete main gallery image if it exists
            if (Storage::disk('public')->exists($gallery->gallery_image)) {
                Storage::disk('public')->delete($gallery->gallery_image);
            }

            // Get all related GalleryItems
            $galleryItems = GalleryItem::where('gallery_id', $gallery->id)->get();

            foreach ($galleryItems as $item) {
                // Delete each gallery item file
                if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
                    Storage::disk('public')->delete($item->file_path);
                }

                // Delete the gallery item record
                $item->delete();
            }

            // Finally, delete the gallery record
            $gallery->delete();
            $user = Auth::user();

            $userTo = User::find($gallery->user_id);

            // Add entry to AppTrack
            AppTrack::create([
                'menu_id' => $request->menu_id ?? null,
                'page_section_master_id' => $request->page_section_master_id ?? null,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $gallery->user_id,
                'user_to_name' => $userTo ? $userTo->name : null,
                'remarks' => 'Gallery and its item  has been deleted: ' . $gallery->gallery_name,
                'action' => 'Deleted',
                'flag' => 'D',
                'application_id' => $gallery->application_id

            ]);

            return response()->json(['message' => 'Gallery and related images deleted successfully']);
        }

        return response()->json(['message' => 'Gallery not found'], 404);
    }

    //GET ARTICLE
    public function getArticle($id)
    {
        $article = Article::where('id', $id)->first();
        return response()->json($article);
    }


    //LATEST NEWS

    public function saveLatestNews(Request $request)
    {
        $user = Auth::user(); // This will get the authenticated user
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //before submit check roleid and set flag value
        // Determine flag based on role_id
        if ($user->role_id == 2) { //if admin upload 
            $flag = 'A';
        } elseif ($user->role_id == 3) { //if contentcreator upload
            $flag = 'N';
        }
        $publisher = User::find($request->publisher_id);
        DB::beginTransaction();
        $request->validate([
            // 'file' => 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240', // 10240 KB = 10 MB
            'title' => 'required|string'
        ]);

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480', // 20 MB max
            ]);
        }
        if ($request->file) {
            $type = "file";
            $filename = time() . '-' . $request->file->getClientOriginalName();
            $path = $request->file->storeAs('news', $filename, 'public');
        } else {
            $path = $filename = "";
            $type = "link";
        }
        $applicationId = 'LAT' . now()->format('YmdHis');
        try {
            LatestNews::create([
                'title' => $request->title,
                'file' => $path,  //$filename
                'link' => $request->link,
                'type' => $type,
                'order' => '1',
                'status' => 1,
                'hindi_title' => $request->titleH,
                'khasi_title' => $request->titleK,
                'other_title' => $request->titleO,
                'page' => $request->page,
                'pagename' => $request->pagename,
                'pagemenuid' => $request->pagemenuid,
                'flag' => $flag,
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'publisher_id' => $request->publisher_id ?? null,
                'application_id' => $applicationId
            ]);
            AppTrack::create([
                'application_id' => $applicationId,
                'menu_id' => $request->menu_id,
                'page_section_master_id' => $request->page_section_master_id,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $request->publisher_id,
                'user_to_name' => $publisher ? $publisher->name : null,
                'remarks' => 'LatestNews submitted: ' . $request->title,
                'flag' => $flag,
                'action' => 'Add',
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            dd($e->getMessage()); // or handle it appropriately
            return response()->json([
                'title' => 'Some Error Occurred',
                'content' => $e . getMessage(),
            ], 500);
        }
        return response()->json(['message' => 'News added successfully']);
    }
    public function rejectedLatestNews(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'remarks' => 'required|string',
        ]);
        $news = LatestNews::find($request->id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Notice not found',
            ], 404);
        }
        $news->flag = 'R';
        $news->rejected_remarks = $request->remarks;
        $news->save();
        $user = Auth::user();
        $userTo = User::find($news->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $news->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'LatestNews Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $news->application_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'LatestNews rejected successfully',
        ]);
    }
    //save Cards to database
    public function save_card(Request $request)
    {
        $publisher = User::find($request->publisher_id);
        DB::beginTransaction();
        $user = Auth::user();
        $applicationId = 'CARD' . now()->format('YmdHis');
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Determine flag based on role_id
        if ($user->role_id == 2) {
            $flag = 'A';
        } elseif ($user->role_id == 3) {
            $flag = 'N';
        }

        // Basic validation
        $request->validate([
            'card_title' => 'required|string',
        ]);

        // Validate file if it exists
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            ]);
        }

        // Handle image upload
        if ($request->image) {
            $folderPath = storage_path('app/public/cards');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $filename = time() . '-' . $request->image->getClientOriginalName();
            $path = $request->image->storeAs('cards', $filename, 'public');
            $type = "file";
        } else {
            $path = $filename = "";
            $type = "link";
        }

        // Determine order value
        $order = $request->order;
        if (empty($order)) {
            $latestOrder = Cards::max('order');
            $order = $latestOrder ? $latestOrder + 1 : 1;
        }

        try {
            Cards::create([
                'card_title' => $request->card_title,
                'card_description' => $request->card_description,
                'image' => $path,
                'more_link' => $request->more_link,
                'type' => $type,
                'order' => $order,
                'status' => 1,
                'menu_id' => $request->menu ?? 1,
                'page_section_id' => $request->page_section ?? 1,
                'hindi_title' => $request->hindi_title,
                'khasi_title' => $request->khasi_title,
                'other_title' => $request->other_title,
                'hindi_description' => $request->hindi_description,
                'khasi_description' => $request->khasi_description,
                'other_description' => $request->other_description,
                'flag' => $flag,
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'publisher_id' => $request->publisher_id ?? null,
                'application_id' => $applicationId
            ]);
            AppTrack::create([
                'application_id' => $applicationId,
                'menu_id' => $request->menu,
                'page_section_master_id' => $request->page_section,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $request->publisher_id,
                'user_to_name' => $publisher ? $publisher->name : null,
                'remarks' => 'Cards submitted: ' . $request->card_title,
                'flag' => $flag,
                'action' => 'Add',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'title' => 'Some Error Occurred',
                'content' => $e->getMessage(),
            ], 500);
        }

        return response()->json(['message' => 'New Cards added successfully']);
    }




    // public function getLatestNews(Request $request)
    // {
    //     // Get the current month
    //     $currentMonth = Carbon::now()->month; // Get the current month (1-12)
    //     $currentYear = Carbon::now()->year; // Get the current year (e.g. 2024)
    //     $flag = $request->input('flag');
    //     $user = Auth::user();

    //     if ($flag == 'A') { //Approved{
    //         return LatestNews::where('flag', 'A')->get();
    //     } elseif (in_array($user->role_id, [3, 4])) {
    //         return DB::table('latest_news as ln')
    //             ->join('users as u', 'ln.user_id', '=', 'u.id')
    //             ->select('ln.id', 'ln.title', 'ln.created_at as addedon', 'u.name as addedby', 'ln.file', 'ln.status', 'ln.flag', 'ln.type', 'ln.link')
    //             ->get();
    //     }


    //     // 1. Migrate old news to archive_news table (news not from the current month)
    //     $oldNews = DB::table('latest_news')
    //         ->whereYear('created_at', '<>', $currentYear) // Filter by year
    //         ->orWhereMonth('created_at', '<>', $currentMonth) // Filter by month
    //         ->get();

    //     if ($oldNews->isNotEmpty()) {
    //         // Move the old news to the archive_news table
    //         foreach ($oldNews as $newsItem) {
    //             $insertToArchive = DB::table('archive_news')->insert([
    //                 'file' => $newsItem->file,
    //                 'link' => $newsItem->link,
    //                 'type' => $newsItem->type,
    //                 'title' => $newsItem->title,
    //                 'order' => $newsItem->order,
    //                 'status' => $newsItem->status,
    //                 'hindi_title' => $newsItem->hindi_title,
    //                 'khasi_title' => $newsItem->khasi_title,
    //                 'other_title' => $newsItem->other_title,
    //                 'created_at' => $newsItem->created_at, // Preserve the created_at timestamp
    //                 'updated_at' => $newsItem->updated_at, // Preserve the updated_at timestamp
    //             ]);
    //         }
    //         // After migrating to archive, delete the old news from the latest_news table

    //         if ($insertToArchive === true) {
    //             DB::table('latest_news')
    //                 ->whereYear('created_at', '<>', $currentYear)
    //                 ->orWhereMonth('created_at', '<>', $currentMonth)
    //                 ->delete();
    //         }
    //     }


    //     // 2. Get the latest news from the current month
    //     $data = DB::table('latest_news')
    //         ->whereYear('created_at', $currentYear) // Only get news from the current year
    //         ->whereMonth('created_at', $currentMonth) // Only get news from the current month
    //         ->get();

    //     // Return the current month news as JSON response
    //     return response()->json($data);
    // }

    public function getLatestNews(Request $request)
    {
        $flag = $request->input('flag');
        $user = Auth::user();
        // Step 1: Get archive duration from settings (in days)
        $settings = DB::table('website_settings')->get();
        $validSetting = $settings->first(function ($row) {
            return !is_null($row->archieve_duration) && is_numeric($row->archieve_duration);
        });
        $archiveDuration = $validSetting->archieve_duration ?? null;

        if ($archiveDuration !== null && is_numeric($archiveDuration)) {
            $thresholdDate = Carbon::now()->subDays($archiveDuration);

            // Step 2: Find all news older than archive duration
            $oldNews = DB::table('latest_news')
                ->where('created_at', '<', $thresholdDate)
                ->get();

            if ($oldNews->isNotEmpty()) {
                foreach ($oldNews as $newsItem) {
                    $insertToArchive = DB::table('archive_news')->insert([
                        'file' => $newsItem->file,
                        'link' => $newsItem->link,
                        'type' => $newsItem->type,
                        'title' => $newsItem->title,
                        'order' => $newsItem->order,
                        'status' => $newsItem->status,
                        'hindi_title' => $newsItem->hindi_title,
                        'khasi_title' => $newsItem->khasi_title,
                        'other_title' => $newsItem->other_title,
                        'created_at' => $newsItem->created_at,
                        'updated_at' => $newsItem->updated_at,
                    ]);
                }

                // Delete from latest_news after archiving
                if ($insertToArchive === true) {
                    DB::table('latest_news')
                        ->where('created_at', '<', $thresholdDate)
                        ->delete();
                }
            }
        }
        // If Approved flag passed, return approved news only
        if ($flag == 'A') {
            return LatestNews::where('flag', 'A')->get();
        } elseif (in_array($user->role_id, [3, 4])) {
            return DB::table('latest_news as ln')
                ->join('users as u', 'ln.user_id', '=', 'u.id')
                ->leftJoin('users as u2', 'ln.publisher_id', '=', 'u2.id')
                ->select('ln.id', 'ln.title', 'ln.created_at as addedon', 'u.name as addedby', 'ln.file', 'ln.status', 'ln.flag', 'ln.type', 'ln.link', 'ln.rejected_remarks', 'u2.name as approver')
                ->get();
        }

        // Step 3: Return news that is within the archive duration
        // $data = DB::table('latest_news')
        //     ->where('created_at', '>=', Carbon::now()->subDays($archiveDuration))
        //     ->get();
        $data = DB::table('latest_news as ln')
            ->join('users as u', 'ln.user_id', '=', 'u.id')
            ->select('ln.id', 'ln.title', 'ln.created_at as addedon', 'u.name as addedby', 'ln.file', 'ln.status', 'ln.flag', 'ln.type', 'ln.link')
            ->get();

        return response()->json($data);
    }
    public function approvedLatestNews(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:latest_news,id'
        ]);

        $latestnews = LatestNews::find($request->id);
        $latestnews->flag = 'A'; // Approve
        $latestnews->save();

        if ($latestnews) {
            // Fetch the notice after approval
            $news = LatestNews::find($request->id);
            // Get current user (approver)
            $user = Auth::user();
            // Get notice creator
            $userTo = User::find($news->user_id);

            // Add entry to AppTrack
            AppTrack::create([
                'menu_id' => $request->menu_id ?? null,
                'page_section_master_id' => $request->page_section_master_id ?? null,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $news->user_id,
                'user_to_name' => $userTo ? $userTo->name : null,
                'remarks' => 'Notice approved: ' . $news->title,
                'action' => 'Approved',
                'flag' => 'A',
                'application_id' => $news->application_id

            ]);

            return response()->json(['message' => 'LatestNews approved successfully.'], 200);
        } else {
            return response()->json(['message' => 'Approval failed or already approved.'], 400);
        }
    }

    public function getParagraphs()
    {
        $data = DB::table('paragraph')
            ->get();
        return response()->json(['data' => $data]);
    }
    public function getCards(Request $request)
    {
        $flag = $request->input('flag');
        $user = Auth::user();

        if ($user && in_array($user->role_id, [3, 4])) {
            // Return cards joined with users if role_id is 3 or 4
            $data = DB::table('cards as c')
                ->join('users as u', 'u.id', '=', 'c.user_id')
                ->select('c.*', 'u.name as addedby')
                ->get();

            return response()->json(['data' => $data]);
        }

        if ($flag === 'A') {
            // Return only approved cards
            $data = Cards::where('flag', 'A')->get();
            return response()->json(['data' => $data]);
        }

        // Default: return all cards
        // $data = DB::table('cards')->get();
        $data = DB::table('cards as c')
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->select('c.*', 'u.name as addedby')
            ->get();

        return response()->json(['data' => $data]);
    }
    public function approveCards(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'id' => 'required|exists:cards,id'
        ]);

        $cards = Cards::find($request->id);
        $cards->flag = 'A'; // Approve
        $cards->save();
        if ($cards) {
            // Fetch the notice after approval
            $card = Cards::find($request->id);
            // Get current user (approver)
            $user = Auth::user();
            // Get notice creator
            $userTo = User::find($card->user_id);
            // Add entry to AppTrack
            AppTrack::create([
                'menu_id' => $request->menu_id ?? null,
                'page_section_master_id' => $request->page_section_master_id ?? null,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $card->user_id,
                'user_to_name' => $userTo ? $userTo->name : null,
                'remarks' => 'Card approved: ' . $card->card_title,
                'action' => 'Approved',
                'flag' => 'A',
                'application_id' => $card->application_id

            ]);

            return response()->json(['message' => 'Notice approved successfully.'], 200);
        } else {
            return response()->json(['message' => 'Approval failed or already approved.'], 400);
        }
    }

    public function rejectedCard(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'id' => 'required|exists:cards,id'
        ]);
        $cards = Cards::find($request->id);
        $cards->flag = 'R'; // Approve
        $cards->rejected_remarks = $request->remarks;
        $cards->save();
        $userTo = User::find($cards->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $cards->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Card has been Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $cards->application_id
        ]);

        return response()->json(['success' => true, 'message' => 'Approved successfully']);
    }

    public function deletedCard(Request $request)
    {
        $id = $request->input('id');
        $card = Cards::find($id);

        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        // Delete the file if it exists
        if ($card->image && Storage::disk('public')->exists($card->image)) {
            Storage::disk('public')->delete($card->image);
        }

        // Save user and card info for tracking (before deleting the card)
        $user = Auth::user();
        $userTo = User::find($card->user_id);
        $appId = $card->application_id;
        $title = $card->card_title; // assuming 'card_title' is correct column

        // Delete the card
        $card->delete();

        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $card->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Card Deleted: ' . $title,
            'action' => 'Deleted',
            'flag' => 'D',
            'application_id' => $appId
        ]);

        return response()->json(['message' => 'Card deleted successfully']);
    }





    //get footer
    public function getFooter()
    {
        $data = DB::table('footer')
            ->get();
        return response()->json(['data' => $data]);
    }

    public function deleteNews(Request $request)
    {
        DB::beginTransaction();
        $id = $request->input('id');
        $news = LatestNews::find($id);
        // Delete the file if it exists
        if ($news->file && Storage::disk('public')->exists($news->file)) {
            Storage::disk('public')->delete($news->file);
        }
        $data = DB::table('latest_news')
            ->where('id', $request->id)
            ->delete();
        $user = Auth::user();
        $userTo = User::find($news->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $news->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'LatestNews been Deleted: ' . $news->title,
            'action' => 'Deleted',
            'flag' => 'D',
            'application_id' => $news->application_id

        ]);
        DB::commit();
        return response()->json([
            'message' => 'Deleted Successfully',
            'data' => DB::table('latest_news')
                ->get()
        ]);
    }

    public function updateNewsOrder(Request $request)
    {
        // dd($request->all());
        foreach ($request->items as $index => $item) {
            LatestNews::where('id', $item['id'])->update(['order' => $index + 1]);
        }

        return response()->json(['message' => 'Order updated successfully', 'data' => Card::all()]);
    }


    //FRONT END
    public function get_mainmenus()
    {
        $getMenus = Menu::getMenus();
        return $getMenus;
    }

    public function get_rightmenus()
    {

        $getMenus = Menu::getRightMenus();

        return $getMenus;
    }

    // public function getCarousel()
    // {
    //     return (Carousel::all());
    // }

    // public function getCarousel(Request $request)
    // {

    //     $flag = $request->query('flag');
    //     $user = Auth::user();
    //     if ($flag === 'A') { //for  website
    //         // Return Carousel records with flag 'A'
    //         return Carousel::where('flag', 'A')->get();
    //     } elseif (in_array($user->role_id, [3, 4])) {
    //         // Use query builder to join with users table and return specific fields
    //         return DB::table('carousel as cs')
    //             ->join('users as u', 'cs.user_id', '=', 'u.id')
    //             ->select('cs.image', 'cs.flag', 'cs.created_at as addedon', 'u.name as addedby', 'cs.id')
    //             ->get();
    //     }

    //     // Default: return all Carousel records
    //     return Carousel::all();
    // }

    public function getCarousel(Request $request)
    {
        $flag = $request->query('flag');
        $user = Auth::user();

        // Case 1: Return only approved base64 strings from website cache
        if ($flag === 'A') {
            $caches = WebsiteCache::where('type', 'carousel')
                ->where('flag', 'A')
                ->pluck('data'); // Return just base64 strings

            return response()->json($caches);
        }

        // Case 2: Role-based filtered carousel records
        if ($user && in_array($user->role_id, [3, 4])) {
            $query = DB::table('carousel as cs')
                ->leftJoin('users as u', 'cs.user_id', '=', 'u.id')
                ->leftJoin('users as u2', 'cs.publisher_id', '=', 'u2.id')
                ->select(
                    'cs.image',
                    'cs.flag',
                    'cs.created_at as addedon',
                    'u.name as addedby',
                    'cs.id',
                    'cs.rejected_remarks',
                    'u2.name as approver'
                );

            if ($user->role_id == 3) {
                $query->where('cs.user_id', $user->id);
            } elseif ($user->role_id == 4) {
                $query->where('cs.publisher_id', $user->id);
            }

            return $query->get();
        }

        // Case 3: Default (admin or other roles) - return all carousel records
        return Carousel::all();
    }


    // public function approveCarousel(Request $request)
    // {
    //     $user = Auth::user();
    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }
    //     $request->validate([
    //         'id' => 'required|exists:carousel,id'
    //     ]);

    //     $carousel = Carousel::find($request->id);
    //     $carousel->flag = 'A'; // Approve
    //     $carousel->save();

    //     return response()->json(['success' => true, 'message' => 'Approved successfully']);
    // }

    public function approveCarousel(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'id' => 'required|exists:carousel,id'
        ]);
        // Approve the carousel
        $carousel = Carousel::find($request->id);
        $carousel->flag = 'A';
        $carousel->save();
        $user = Auth::user();
        $userTo = User::find($carousel->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $carousel->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Carousel approved: ' . $carousel->image,
            'action' => 'Approved',
            'flag' => 'A',
            'application_id' => $carousel->application_id

        ]);

        // Update the website_cache row where carousel_id matches
        DB::table('website_cache')
            ->where('type', 'carousel')
            ->where('carousel_id', $carousel->id)
            ->update([
                'flag' => 'A',
                'user_id' => $user->id,
                'role_id' => $user->role_id
            ]);
        return response()->json(['success' => true, 'message' => 'Approved successfully']);
    }

    public function rejectedSlide(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'remarks' => 'required|string',
        ]);

        $slide = Carousel::find($request->id);
        if (!$slide) {
            return response()->json([
                'success' => false,
                'message' => 'slide not found',
            ], 404);
        }

        $slide->flag = 'R';
        $slide->rejected_remarks = $request->remarks;
        $slide->save();
        $websitecache = WebsiteCache::find($request->id);
        $websitecache->flag = 'R';
        $websitecache->rejected_remarks = $request->remarks;
        $websitecache->save();
        $user = Auth::user();
        $userTo = User::find($slide->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $slide->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Carousel Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $slide->application_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Carousal rejected successfully',
        ]);
    }

    public function getBanner()
    {
        return (Banner::all());
    }
    public function getLogo(Request $request)
    {
        $flag = $request->query('flag');
        $user = Auth::user();
        if ($flag === 'A') { //for  website
            // Return Logo records with flag 'A'
            return Logo::where('flag', 'A')->get();
        } elseif (in_array($user->role_id, [2, 3, 4])) {
            return DB::table('logo as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.*', 'u.name as addedby')
                ->get();
        }
        // Default: return all Logo records
        return Logo::all();
    }

    public function approveLogo(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:logo,id'
        ]);
        $logo = Logo::find($request->id);
        $logo->flag = 'A'; // Approve
        $logo->save();
        $user = Auth::user();
        $userTo = User::find($logo->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $logo->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Logo approved: ' . $logo->image,
            'action' => 'Approved',
            'flag' => 'A',
            'application_id' => $logo->application_id
        ]);
        return response()->json(['success' => true, 'message' => 'Logo approved successfully']);
    }
    public function updateLogo(Request $request)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $logo = Logo::findOrFail($request->id);
        // Determine flag
        $flag = ($user->role_id == 2) ? 'A' : 'U';
        $publisher = User::find($request->publisher_id);
        // Delete old image from storage
        if (!empty($logo->image)) {
            $oldImagePath = storage_path('app/public/' . $logo->image);
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }
        $uploadedFilename = null;
        $storedPath = null;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uploadedFilename = $image->getClientOriginalName();
                $storedPath = $image->storeAs('bannerlogo', $uploadedFilename, 'public');

                // Update the existing record
                $logo->update([
                    'image' => $storedPath,
                    'publisher_id' => $request->publisher_id ?? null,
                    'menu_id' => $request->menu_id,
                    'flag' => $flag,
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                ]);

                // Log update in AppTrack
                AppTrack::create([
                    'application_id' => $logo->application_id,
                    'menu_id' => $request->menu_id,
                    'page_section_master_id' => $request->page_section_master_id,
                    'user_from' => $user->id,
                    'user_from_name' => $user->name,
                    'user_to' => $request->publisher_id,
                    'user_to_name' => $publisher ? $publisher->name : null,
                    'remarks' => 'Logo updated: ' . $storedPath,
                    'flag' => 'U',
                    'action' => 'Updated',
                ]);
            }
        } else {
            // Update metadata even if no file uploaded
            $logo->update([
                'publisher_id' => $request->publisher_id ?? null,
                'menu_id' => $request->menu_id,
                'flag' => $flag,
                'user_id' => $user->id,
                'role_id' => $user->role_id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logo updated successfully'
        ]);
    }

    public function rejectLogo(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'remarks' => 'required|string',
        ]);
        $logo = Logo::find($request->id);
        if (!$logo) {
            return response()->json([
                'success' => false,
                'message' => 'Logo data not found',
            ], 404);
        }
        $logo->flag = 'R';
        $logo->rejected_remarks = $request->remarks;
        $logo->save();
        $user = Auth::user();
        $userTo = User::find($logo->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $logo->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Logo Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $logo->application_id
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Logo  has been rejected',
        ]);
    }


    public function deleteLogo(Request $request)
    {
        $id = $request->input('id');
        $logo = Logo::find($id);
        if (!$logo) {
            return response()->json(['message' => 'Logo not found'], 404);
        }
        if ($logo->image && Storage::disk('public')->exists($logo->image)) {
            Storage::disk('public')->delete($logo->image);
        }
        // Delete the record
        $logo->delete();
        $user = Auth::user();
        $userTo = User::find($logo->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $logo->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Logo Deleted: ' . $logo->name,
            'action' => 'Deleted',
            'flag' => 'D',
            'application_id' => $logo->application_id

        ]);

        return response()->json(['message' => 'Logo deleted successfully']);
    }




    public function getWebsiteDescription()
    {
        return Website::first();
    }

    public function getPageContent($menuId)
    {

        $page = Paragraph::where('menu_id', $menuId)->first();


        if ($page) {
            return response()->json([
                'title' => $page->title,
                'content' => $page->description,
                'hindi_title' => $page->hindi_title,
                'hindi_content' => $page->hindi_description,
                'khasi_title' => $page->khasi_title,
                'khasi_content' => $page->khasi_description,
                'flag' => $page->flag,
                'userid' => $page->user_id,
                'id' => $page->id,
                'publisher_id' => $page->publisher_id,
                'created_at' => $page->created_at
            ]);
        }

        return response()->json([
            'title' => 'Page Not Found',
            'content' => 'The content for this page is not available.',
        ], 404);
    }

    public function getPageContentByPublisher()
    {
        $paragraphs = DB::table('paragraph as ph')
            ->join('users as u', 'u.id', '=', 'ph.user_id')
            ->select('ph.*', 'u.name as addedby')
            ->get();

        return response()->json($paragraphs);
    }

    public function approvedParagraph(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'id' => 'required|exists:paragraph,id',
        ]);

        // Get the paragraph record
        $paragraph = Paragraph::find($request->id);

        if (!$paragraph) {
            return response()->json(['message' => 'Paragraph not found'], 404);
        }

        // Store old description (optional)
        $oldDescription = $paragraph->description;

        // Only update if new_description is not null
        if (!is_null($paragraph->new_description)) {
            $paragraph->description = $paragraph->new_description;
            $paragraph->new_description = null; // Clear after updating
        }

        // Update flag regardless
        $paragraph->flag = 'A';
        $paragraph->save();
        $userTo = User::find($paragraph->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $paragraph->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Paragraph approved',
            'action' => 'Approved',
            'flag' => 'A',
            'application_id' => $paragraph->application_id

        ]);
        return response()->json([
            'message' => 'Paragraph approved successfully',
            'old_description' => $oldDescription,
            'updated_description' => $paragraph->description,
        ]);
    }
    public function rejectedParagraph(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'remarks' => 'required|string',
        ]);

        $para = Paragraph::find($request->id);

        if (!$para) {
            return response()->json([
                'success' => false,
                'message' => 'Paragraph data not found',
            ], 404);
        }

        $para->flag = 'R';
        $para->rejected_remarks = $request->remarks;
        $para->save();
        $user = Auth::user();
        $userTo = User::find($para->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $para->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Paragraph Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $para->application_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Paragraph rejected',
        ]);
    }

    public function deleteParagraph(Request $request)
    {
        //to do
    }


    public function getNewsLetter()
    {
        $newsLetter = NewsLetter::all();
        return $newsLetter;
    }
    public function getNewsLetterDetails($newsletterid)
    {
        $newsletterDetail = NewsletterDetails::where('newsletterid', $newsletterid)->get();
        if ($newsletterDetail) {
            return response()->json($newsletterDetail); // Return the newsletter data as JSON
        } else {
            return response()->json(['message' => 'NewsletterDetails not found'], 404); // Handle the case where no newsletter is found
        }
    }
    public function getPageMenu($id)
    {
        $pagemenus = Menu::getPageMenu($id); // Calls the static method in the model
        return response()->json($pagemenus);
    }

    public function getCardDetail($cardid)
    {

        $cardDetail = LatestNews::where('pagemenuid', $cardid)->get();
        if ($cardDetail) {
            return response()->json($cardDetail); // Return the card data as JSON
        } else {
            return response()->json(['message' => 'Card not found'], 404); // Handle the case where no card is found
        }
    }
    public function getparagraphByCardID($card_detailsID)
    {
        $paragraph = Paragraph::where('page_section_id', $card_detailsID)->get();
        if ($paragraph) {
            return response()->json($paragraph);
        } else {
            return response()->json(['message' => 'Paragraph data  not found'], 404);
        }
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120' // Adjust max file size as needed
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Determine flag based on role_id
        if ($user->role_id == 2) {
            $flag = 'A';
        } elseif ($user->role_id == 3) {
            $flag = 'N';
        } else {
            $flag = 'N'; // default if needed
        }
        $publisher = User::find($request->publisher_id);
        $applicationId = 'LOGO' . now()->format('YmdHis');
        $menu_id = $request->menu_id;
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();
                $path = $image->store('bannerlogo', 'public');
                $uploadedImages[] = $filename;
                // Save to database
                Logo::create([
                    'application_id' => $applicationId,
                    'image' => $path,
                    'menu_id' => $menu_id,
                    'flag' => $flag,
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'publisher_id' => $request->publisher_id ?? null
                ]);
                //add entry to  AppTracker table
                AppTrack::create([
                    'application_id' => $applicationId,
                    'menu_id' => $request->menu_id,
                    'page_section_master_id' => $request->page_section_master_id,
                    'user_from' => $user->id,
                    'user_from_name' => $user->name,
                    'user_to' => $request->publisher_id,
                    'user_to_name' => $publisher ? $publisher->name : null,
                    'remarks' => 'Logo submitted: ' . $filename,
                    'flag' => $flag,
                    'action' => 'Add',
                ]);
            }
        }

        return response()->json([
            'message' => 'Logos uploaded successfully',
            'filenames' => $uploadedImages
        ]);
    }

    public function getAllPageMenu()
    {
        return Menu::where('menu_master', 4)->get();
    }

    public function saveArchiveData(Request $request)
    {
        // If only language_id is set, insert new row with language_id only
        if ($request->filled('language_id') && !$request->filled('archieve_duration')) {
            $request->validate([
                'language_id' => 'required|exists:language_master,id'
            ]);

            $setting = WebsiteSettings::create([
                'language_id' => $request->language_id
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Language setting added successfully.',
                'website_setting' => $setting
            ]);
        }

        // If archieve_duration is present (normal path)
        if ($request->filled('archieve_duration')) {
            $request->validate([
                'archieve_duration' => 'required|integer|min:0'
            ]);

            $setting = WebsiteSettings::first();

            if ($setting) {
                $setting->update([
                    'archieve_duration' => $request->archieve_duration
                ]);
            } else {
                $data = [
                    'archieve_duration' => $request->archieve_duration
                ];

                // Only insert language_id during creation if it's also sent
                if ($request->filled('language_id')) {
                    $data['language_id'] = $request->language_id;
                }

                $setting = WebsiteSettings::create($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'Archive duration updated successfully.',
                'website_setting' => $setting
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid data provided.'
        ], 400);
    }

    public function deleteArchiveData(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:language_master,id',
        ]);

        WebsiteSettings::where('language_id', $request->language_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Language setting removed successfully.'
        ]);
    }

    public function getArchieveData()
    {
        // Assuming you have only one row of website settings
        $settings = WebsiteSettings::all();
        return response()->json($settings);
    }
    public function saveLanguage(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'language_name' => 'required|string|max:255',
        ]);

        if ($request->has('id')) {
            // Update existing language
            $language = LanguageMaster::find($request->id);
            $language->language_name = $request->language_name;
            $language->save();
        } else {
            // Create new language
            $language = LanguageMaster::create([
                'language_name' => $request->language_name,
            ]);
        }

        return response()->json(['success' => true, 'language' => $language]);
    }
    public function getMasterLanguages()
    {
        $languages = LanguageMaster::all();
        return response()->json($languages);
    }

    public function getActivateLanguages()
    {
        $languages = DB::table('website_settings as ws')
            ->join('language_master as lm', 'lm.id', '=', 'ws.language_id')
            ->select('ws.language_id', 'lm.language_name')
            ->get();

        return response()->json($languages);
    }

    public function getDashboardData()
    {
        $user = Auth::user();
        $userId = $user->id;
        $filterByUser = $user->role_id == 3; // true only for role_id 3

        // Helper to build the query
        $buildQuery = function ($table, $includeUpdated = false) use ($filterByUser, $userId) {
            $query = DB::table($table);
            if ($filterByUser) {
                $query->where('user_id', $userId);
            }

            $select = "
            COUNT(CASE WHEN flag = 'N' THEN 1 END) AS pendingcount,
            COUNT(CASE WHEN flag = 'A' THEN 1 END) AS approvedcount,
            COUNT(CASE WHEN flag = 'R' THEN 1 END) AS rejectedcount
        ";

            if ($includeUpdated) {
                $select .= ",
                COUNT(CASE WHEN flag = 'U' THEN 1 END) AS updatedcount
            ";
            }

            return $query->selectRaw($select)->first();
        };

        // Run queries
        $cards = $buildQuery('cards');
        $latestNews = $buildQuery('latest_news');
        $noticeBoard = $buildQuery('notifications', true);
        $carousel = $buildQuery('carousel');
        $gallery = $buildQuery('gallery');
        $paragraph = $buildQuery('paragraph', true);
        $whos_who = $buildQuery('whos_who');
        $logo = $buildQuery('logo');
        $map = $buildQuery('map');
        $faqs = $buildQuery('faqs');

        // Return result
        return response()->json([
            'cards' => [
                'pending' => $cards->pendingcount,
                'approved' => $cards->approvedcount,
                'rejected' => $cards->rejectedcount,
            ],
            'latest_news' => [
                'pending' => $latestNews->pendingcount,
                'approved' => $latestNews->approvedcount,
                'rejected' => $latestNews->rejectedcount,
            ],
            'notice_board' => [
                'pending' => $noticeBoard->pendingcount,
                'approved' => $noticeBoard->approvedcount,
                'updated' => $noticeBoard->updatedcount,
                'rejected' => $noticeBoard->rejectedcount,
            ],
            'carousel' => [
                'pending' => $carousel->pendingcount,
                'approved' => $carousel->approvedcount,
                'rejected' => $carousel->rejectedcount,
            ],
            'gallery' => [
                'pending' => $gallery->pendingcount,
                'approved' => $gallery->approvedcount,
                'rejected' => $gallery->rejectedcount,
            ],
            'paragraph' => [
                'pending' => $paragraph->pendingcount,
                'approved' => $paragraph->approvedcount,
                'updated' => $paragraph->updatedcount,
                'rejected' => $paragraph->rejectedcount,
            ],
            'whos_who' => [
                'pending' => $whos_who->pendingcount,
                'approved' => $whos_who->approvedcount,
                'rejected' => $whos_who->rejectedcount,
            ],
            'logo' => [
                'pending' => $logo->pendingcount,
                'approved' => $logo->approvedcount,
                'rejected' => $logo->rejectedcount,
            ],
            'map' => [
                'pending' => $map->pendingcount,
                'approved' => $map->approvedcount,
                'rejected' => $map->rejectedcount,
            ],
            'faqs' => [
                'pending' => $faqs->pendingcount,
                'approved' => $faqs->approvedcount,
                'rejected' => $faqs->rejectedcount,
            ],
        ]);
    }



    public function getArchiveData()
    {
        $data = DB::table('archive_news')->get();
        return response()->json($data);
    }
}
