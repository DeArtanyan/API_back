<?php

namespace App\Http\Controllers;

use App\Models\HotelReview;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreHotelReviewRequest;
use App\Http\Requests\DeleteHotelReviewRequest;

class HotelReviewController extends Controller
{
    // Получить все отзывы об отеле
    public function index(): JsonResponse
    {
        $reviews = HotelReview::with('user')->latest()->get()->map(function($review) {
            return [
                'id' => $review->id,
                'comment' => $review->comment,
                'rating' => $review->rating,
                'created_at' => $review->created_at->format('d.m.Y'),
                'user_first_name' => $review->user->first_name
            ];
        });

        return response()->json($reviews);
    }

    // Добавить отзыв
    public function store(StoreHotelReviewRequest $request): JsonResponse
    {
        $review = HotelReview::create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'rating' => $request->rating
        ]);

        $review->load('user');

        return response()->json([
            'message' => 'Отзыв об отеле добавлен',
            'review' => [
                'id' => $review->id,
                'user_first_name' => $review->user->first_name,
                'comment' => $review->comment,
                'rating' => $review->rating
            ]
        ], 201);
    }

    // Удалить отзыв
    public function destroy(DeleteHotelReviewRequest $request, HotelReview $review): JsonResponse
    {
        $review->delete();
        return response()->json(['message' => 'Отзыв об отеле удален']);
    }
}
