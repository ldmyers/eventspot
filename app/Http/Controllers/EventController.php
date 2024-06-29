<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

/**
 *  Class providing methods for displaying events.
 */
class EventController extends Controller {

    /**
     * @var \App\Models\Category
     */
    protected Category $category;

    /**
     * @var \App\Models\Event
     */
    protected Event $event;

    /**
     * @var \Inertia\ResponseFactory
     */
    protected ResponseFactory $inertia;

    /**
     * The EventController constructor.
     *
     * @param \App\Models\Category $category
     * @param \App\Models\Event $event
     * @param \Inertia\ResponseFactory $inertia
     */
    public function __construct(Category $category, Event $event, ResponseFactory $inertia) {
        $this->category = $category;
        $this->event = $event;
        $this->inertia = $inertia;
    }

    /**
     * Displays a list of events.
     *
     * @param \Illuminate\Http\Request $request
     * @param $category_slug
     * @param $category_id
     *
     * @return \Inertia\Response
     */
    public function index(Request $request, $category_slug = NULL, $category_id = NULL): Response {
        // Get the category name, it's needed for the dynamic title generation.
        $category_name = NULL;
        if ($category_id) {
            $category = $this->category->find($category_id);
            if ($category) {
                $category_name = $category->name;
            }
        }

        // If category is not found, display a 404 page.
        if ($category_slug && !$category_name) {
            abort(404);
        }

        // Get all categories and return the events page.
        $categories = $this->category->all();
        return $this->inertia->render('Event/Index', [
            'categories' => $categories,
            'categoryId' => $category_id,
            'categoryName' => $category_name,
        ]);
    }

    /**
     * Load events and returns a JSON response for the infinite scroll.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventsLoad(Request $request): JsonResponse {
        // Get the page number from the view.
        $page = $request->input('page', 1);
        $query = $this->event->with('location')
            ->with('category');

        // Check if category parameter is present
        if ($request->has('category')) {
            $categoryId = $request->input('category');
            $query->where('category_id', $categoryId);
        }

        // Load events.
        $events = $query->paginate(10, ['*'], 'page', $page);

        // Return the events as JSON response.
        return response()->json($events);
    }

    /**
     * Display a single event.
     *
     * @param $title
     * @param $id
     *
     * @return \Inertia\Response
     */
    public function show($title, $id) {
        // Get the event with location and category.
        $event = $this->event->with('location')
            ->with('category')
            ->where('id', $id)
            ->first();

        // If event is not found, display a 404 page.
        if (!$event) {
            abort(404);
        }

        // Return the event page.
        return $this->inertia->render('Event/Show', [
            'event' => $event,
        ]);
    }

}
