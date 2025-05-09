<?php

namespace App\Http\Controllers;

use App\Helpers\ThemeHelper;
use App\Models\Carousel;
use App\Models\Gallery;
use App\Models\NewsletterDetails;
use App\Models\Paragraph;
use Illuminate\Support\Facades\DB;
use App\Models\LatestNews;
use App\Models\Website;
use App\Models\Menu;
use App\Models\Banner;
use App\Models\Logo;
use App\Models\Cards;
use Carbon\Carbon;
use App\Models\Footer;
use App\Models\Newsletter;
use App\Models\PageSectionMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function deleteComponent(Request $request)
    {
        if ($request->id) {
            $footer = Footer::where('id', $request->id)->first();
            if ($footer) {

                if (Storage::disk('smb_footer')->exists($footer->link)) {
                    Storage::disk('smb_footer')->delete($footer->link);
                }
            }
            $footer->delete();
            return response()->noContent();
        }
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
        // Validate request
        $validatedData = $request->validate([
            'content' => 'nullable|string',
            'menu' => 'required'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Determine flag based on role_id
        if ($user->role_id == 2) {
            $flag = 'A'; // Admin save
        } elseif ($user->role_id == 3) {
            $flag = 'N'; // New content by creator
        } else {
            return response()->json(['message' => 'Invalid role'], 403);
        }

        $content = $request->content ?? '';

        // Save or update paragraph details
        $paragraph = Paragraph::updateOrCreate(
            [
                'menu_id' => $request->menu,
                'page_section_id' => $request->menu,
            ],
            [
                'title' => '',
                'description' => $content,
                'hindi_description' => '',
                'khasi_description' => '',
                'status' => "1",
                'flag' => $flag,
                'user_id' => $user->id,
                'role_id' => $user->role_id
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Content saved successfully!',
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

    public function uploadCarousel(Request $request)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:20480' // 20MB
        ]);
        // Get logged-in user
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
        // Handle file upload
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();
                $path = $image->store('slides', 'public'); // Store in /public/storage/slides directory
                $uploadedImages[] = $filename;


                Carousel::create([
                    'image' => $path,
                    'link' => $path,
                    'type' => 'Slider',
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'flag' => $flag
                ]);
            }
        }

        return response()->json(['message' => 'Images uploaded successfully', 'filenames' => $uploadedImages]);
    }

    public function deleteSlide(Request $request)
    {

        $request->validate([
            'id' => 'required'
        ]);

        $slide = Carousel::where('id', $request->id)->first();

        if ($slide) {

            if (Storage::disk('public')->exists($slide->link)) {

                Storage::disk('public')->delete($slide->link);
            }

            $slide->delete();
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
    public function uploadGallery(Request $request)
    {
        $request->validate([
            'gallery_name' => 'required|string|max:255',
            'gallery_description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Adjust max file size as needed
        ]);

        $galleryName = $request->input('gallery_name');
        $galleryDescription = $request->input('gallery_description');

        // Handle file upload
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();

                $folderPath = ThemeHelper::getFolderPath();
                $viewPath = $folderPath . '/gallery';

                $path = $image->store($viewPath, 'public'); // Store in /public/storage/gallery directory
                $uploadedImages[] = $filename;

                Gallery::create([
                    'gallery_image' => $path,
                    'link' => $path,
                    'parent_id' => 2,
                    'meta_title' => 'test gallery',
                    'meta_description' => 'test gallery',
                    'meta_keywords' => 'msrls gallery',
                    'order' => 1,
                    'status' => 1,
                    'gallery_description' => $galleryDescription,
                    'gallery_name' => $galleryName // Assuming you have a 'gallery_name' field in your Gallery model
                ]);
            }
        }

        return response()->json(['message' => 'Gallery Images uploaded successfully', 'filenames' => $uploadedImages]);
    }
    //get galleries
    public function getGalleries()
    {
        $galleries = Gallery::all();
        return response()->json($galleries);
    }

    //delete gallery image
    public function deleteGalleryImage(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $gallery = Gallery::find($request->id);

        if ($gallery) {

            if (Storage::disk('public')->exists($gallery->gallery_image)) {

                Storage::disk('public')->delete($gallery->gallery_image);
            }

            $gallery->delete();
            return response()->json(['message' => 'Gallery Image deleted successfully']);
        }
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

        DB::beginTransaction();
        $request->validate([
            // 'file' => 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240', // 10240 KB = 10 MB
            'title' => 'required|string',


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
                'role_id' => $user->role_id
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
    //save Cards to database
    public function save_card(Request $request)
{
    DB::beginTransaction();
    $user = Auth::user();

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
            'role_id' => $user->role_id
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



    // public function getLatestNews()
    // {
    //     // dd(1);
    //     $data = DB::table('latest_news')
    //         ->get();
    //     // dd($data);
    //     return response()->json(['data' => $data]);
    // }
    public function getLatestNews(Request $request)
    {
        // Get the current month
        $currentMonth = Carbon::now()->month; // Get the current month (1-12)
        $currentYear = Carbon::now()->year; // Get the current year (e.g. 2024)
        $flag = $request->input('flag');
        $user = Auth::user();

            if ($flag == 'A') { //Approved{
            return LatestNews::where('flag', 'A')->get();
        } elseif ($flag == '4' && $user->role_id == $flag) {
            return DB::table('latest_news as ln')
                ->join('users as u', 'ln.user_id', '=', 'u.id')
                ->select('ln.id', 'ln.title', 'ln.created_at as addedon', 'u.name as addedby', 'ln.file', 'ln.status', 'ln.flag', 'ln.type')
                ->get();
        }


        // 1. Migrate old news to archive_news table (news not from the current month)
        $oldNews = DB::table('latest_news')
            ->whereYear('created_at', '<>', $currentYear) // Filter by year
            ->orWhereMonth('created_at', '<>', $currentMonth) // Filter by month
            ->get();

        if ($oldNews->isNotEmpty()) {
            // Move the old news to the archive_news table
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
                    'created_at' => $newsItem->created_at, // Preserve the created_at timestamp
                    'updated_at' => $newsItem->updated_at, // Preserve the updated_at timestamp
                ]);
            }
            // After migrating to archive, delete the old news from the latest_news table

            if ($insertToArchive === true) {
                DB::table('latest_news')
                    ->whereYear('created_at', '<>', $currentYear)
                    ->orWhereMonth('created_at', '<>', $currentMonth)
                    ->delete();
            }
        }


        // 2. Get the latest news from the current month
        $data = DB::table('latest_news')
            ->whereYear('created_at', $currentYear) // Only get news from the current year
            ->whereMonth('created_at', $currentMonth) // Only get news from the current month
            ->get();

        // Return the current month news as JSON response
        return response()->json($data);
    }
    public function approvedLatestNews(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:latest_news,id'
        ]);

        $carousel = LatestNews::find($request->id);
        $carousel->flag = 'A'; // Approve
        $carousel->save();

        return response()->json(['success' => true, 'message' => 'Slide approved successfully']);
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
        $data = DB::table('cards')->get();
        return response()->json(['data' => $data]);
    }
     public function approveCards(Request $request){
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

        return response()->json(['success' => true, 'message' => 'Approved successfully']);
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
        $data = DB::table('latest_news')
            ->where('id', $request->id)
            ->delete();
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

    public function getCarousel(Request $request)
    {

        $flag = $request->query('flag');
        $user = Auth::user();
        if ($flag === 'A') { //for  website
            // Return Carousel records with flag 'A'
            return Carousel::where('flag', 'A')->get();
        } elseif ($flag === '4'  && $user->role_id == $flag) {
            // Use query builder to join with users table and return specific fields
            return DB::table('carousel as cs')
                ->join('users as u', 'cs.user_id', '=', 'u.id')
                ->select('cs.image', 'cs.flag', 'cs.created_at as addedon', 'u.name as addedby', 'cs.id')
                ->get();
        }

        // Default: return all Carousel records
        return Carousel::all();
    }

    public function approveCarousel(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'id' => 'required|exists:carousel,id'
        ]);

        $carousel = Carousel::find($request->id);
        $carousel->flag = 'A'; // Approve
        $carousel->save();

        return response()->json(['success' => true, 'message' => 'Approved successfully']);
    }

    public function getBanner()
    {
        return (Banner::all());
    }
    public function getLogo()
    {
        return (Logo::all());
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
                'userid' => $page->user_id
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

    return response()->json([
        'message' => 'Paragraph approved successfully',
        'old_description' => $oldDescription,
        'updated_description' => $paragraph->description,
    ]);
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Adjust max file size as needed
        ]);

        $images = $request->images;
        $menu_id = $request->menu_id;

        // Handle file upload
        $uploadedImages = [];
        if ($request->hasFile('images')) {

            $filename = $images[0]->getClientOriginalName();

            $path = $images[0]->store('bannerlogo', 'public'); // Store in /public/storage/logo
            $uploadedImages[] = $filename;


            Logo::create([
                'image' => $path,
                'menu_id' => $menu_id
            ]);
        }

        return response()->json(['message' => 'Logo uploaded successfully', 'filenames' => $uploadedImages]);
    }

    public function getAllPageMenu()
    {
        return Menu::where('menu_master', 4)->get();
    }
}
