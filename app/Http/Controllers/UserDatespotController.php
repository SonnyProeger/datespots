<?php

namespace App\Http\Controllers;

use App\Services\UserDatespotService;
use App\Services\UserReviewService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserDatespotController extends Controller
{
    private UserDatespotService $datespotService;

    private UserReviewService $reviewService;

    public function __construct(UserDatespotService $datespotService, UserReviewService $userReviewService)
    {
        $this->datespotService = $datespotService;
        $this->reviewService = $userReviewService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datespots = $this->datespotService->getAllDatespotsWithTypes();

        $cities = $this->datespotService->getTopFourCities();
        $types = $this->datespotService->getAllTypesWithCategoriesAndSubcategories();

        return Inertia::render('Datespot/Datespots', [
            'datespots' => $datespots,
            'cities' => $cities,
            'types' => $types,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $name)
    {
        $datespotExists = $this->datespotService->datespotExistsByIdAndName($id, $name);

        if ($datespotExists) {
            $datespot = $this->datespotService->getDatespotByIdAndName($id, $name);
            $reviews = $this->reviewService->getAllReviewsForDatespot($id);
        } else {
            return response()->json(['error' => 'Datespot does not exist or Name does not match the ID.'], 404);

            //			return Inertia::render('Error', [
            //				'status' => 404,
            //			]);
        }

        return Inertia::render('Datespot/DatespotDetail', [
            'datespot' => $datespot,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Display a listing of the resource by location
     */
    public function showByLocation($city)
    {
        $datespots = $this->datespotService->getDatespotsByLocation($city);

        $types = $this->datespotService->getAllTypesWithCategoriesAndSubcategories();

        return Inertia::render('Datespot/DatespotsCity', [
            'datespots' => $datespots,
            'city' => $city,
            'types' => $types,
        ]);
    }

    public function filterByLocation(Request $request, $city)
    {
        $types = $this->datespotService->getAllTypesWithCategoriesAndSubcategories();

        $filteredDatespots = $this->datespotService->filterDatespotsByLocation($city, $request);

        return Inertia::render('Datespot/DatespotsCity', [
            'datespots' => $filteredDatespots,
            'city' => $city,
            'types' => $types,
        ]);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');

        $suggestions = $this->datespotService->getAutocompleteSuggestions($query);

        return response()->json($suggestions);
    }

    /**
     * Display the form for suggesting a datespot.
     */
    public function suggest()
    {
        $types = $this->datespotService->getAllTypesWithCategoriesAndSubcategories();

        return Inertia::render('Datespot/SuggestDatespot', [
            'types' => $types,
        ]);
    }
}
