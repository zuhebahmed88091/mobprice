<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Mobile;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class UserOpinionsController extends Controller
{
    /**
     * Display a listing of the opinions.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTable($request);
        }
        $opinions = Rating::orderBy('id', 'ASC');

        return view('user_opinions.index', compact('opinions'));
    }

    /**
     * Display a json listing for table body.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function getDataTable($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($opinions) {
                return view('user_opinions.action', compact('opinions'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getQuery($request)
    {
        $query = Rating::query();
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('created_at', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->mobileId)) {
            $query->where('mobile_id', $request->mobileId);
        }

        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    public function edit($id)
    {
        $opinions = Rating::findOrFail($id);

        return view('user_opinions.edit', compact('opinions'));
    }

    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request, $id);

            $opinions = Rating::findOrFail($id);
            $opinions->update($data);
            $mobile = Mobile::where('id', $opinions->mobile_id)->first();

            if($mobile){
                $allOpinionsSum  = Rating::where('mobile_id', $mobile->id)->where('status', 'Approved')->get()->sum('rating');
                $allOpinions  = Rating::where('mobile_id', $mobile->id)->where('status', 'Approved')->count();

                if($allOpinions !=0 ){
                    $avgRating = $allOpinionsSum/$allOpinions;
                } else{
                    $avgRating = 0;
                }
                $mobile->update(['avg_rating'=>$avgRating]);
            }

            return redirect()->route('opinions.index')
                             ->with('success_message', 'User Opnions was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Get last 25 reviews with summery
     *
     * @param Request $request
     * @param $mobileId
     * @return JsonResponse
     */
    public function ratingItems(Request $request, $mobileId): JsonResponse
    {
        $avgRatings = Rating::where([
            ['mobile_id', $mobileId],
            ['status', 'Approved']
        ])->avg('rating');

        $myRatings = 0.0;
        if (!empty($request->user_id)) {
            $myRatings = Rating::where([
                ['mobile_id', $mobileId],
                ['user_id', $request->user_id],
                ['status', 'Approved']
            ])->value('rating');
        }

        $sumTotalRatings = Rating::where([
            ['mobile_id', $mobileId],
            ['status', 'Approved']
        ])->sum('rating');

        $oneStarPercent = 0.0;
        $twoStarPercent = 0.0;
        $threeStarPercent = 0.0;
        $fourStarPercent = 0.0;
        $fiveStarPercent = 0.0;
        if ($sumTotalRatings > 0) {
            $oneStarRatings = Rating::where([
                ['mobile_id', $mobileId],
                ['rating' , 1],
                ['status', 'Approved']
            ])->sum('rating');
            $oneStarPercent = $oneStarRatings / $sumTotalRatings;

            $twoStarRatings = Rating::where([
                ['mobile_id', $mobileId],
                ['rating' , 2],
                ['status', 'Approved']
            ])->sum('rating');
            $twoStarPercent = $twoStarRatings / $sumTotalRatings;

            $threeStarRatings = Rating::where([
                ['mobile_id', $mobileId],
                ['rating' , 3],
                ['status', 'Approved']
            ])->sum('rating');
            $threeStarPercent = $threeStarRatings / $sumTotalRatings;

            $fourStarRatings = Rating::where([
                ['mobile_id', $mobileId],
                ['rating' , 4],
                ['status', 'Approved']
            ])->sum('rating');
            $fourStarPercent = $fourStarRatings / $sumTotalRatings;

            $fiveStarRatings = Rating::where([
                ['mobile_id', $mobileId],
                ['rating' , 5],
                ['status', 'Approved']
            ])->sum('rating');
            $fiveStarPercent = $fiveStarRatings / $sumTotalRatings;
        }

        $ratings = Rating::where([
            ['mobile_id', $mobileId],
            ['status', 'Approved']
        ])->orderBy('created_at', 'DESC')->limit(25)->get();

        $reviews = [];
        foreach ($ratings as $rating) {
            $reviews[] = [
                'id' => $rating->id,
                'name' => $rating->user->name,
                'email' => $rating->user->email,
                'review' => $rating->review_summary,
                'rating' => $rating->rating,
                'created_at' => date('Y-m-d H:i:s', strtotime($rating->created_at))
            ];
        }

        return response()->json([
            'avg_ratings' => CommonHelper::round($avgRatings, 2),
            'my_ratings' => CommonHelper::round($myRatings, 2),
            'one_star_percent' => CommonHelper::round($oneStarPercent, 2),
            'two_star_percent' => CommonHelper::round($twoStarPercent, 2),
            'three_star_percent' => CommonHelper::round($threeStarPercent, 2),
            'four_star_percent' => CommonHelper::round($fourStarPercent, 2),
            'five_star_percent' => CommonHelper::round($fiveStarPercent, 2),
            'reviews' => $reviews
        ], Response::HTTP_OK);
    }

    public function saveReview(Request $request, $mobileId)
    {
        $data = $request->validate([
            'review_summary' => 'required|max:200',
            'rating' => 'required',
            'user_id' => 'required'
        ]);

        try {
            $rating = Rating::where('mobile_id', $mobileId)
                ->where('user_id', $request->input('user_id'))
                ->first();
            if (!empty($rating)) {
                return response()->json(['error' => 'You have already given your review'], Response::HTTP_BAD_REQUEST);
            } else {
                $data['mobile_id'] = $mobileId;
                $review = Rating::create($data);
                return response()->json($review, Response::HTTP_OK);
            }
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    public function destroy($id)
    {
        try {
            $opinions = Rating::findOrFail($id);
            $mobile = Mobile::where('id', $opinions->mobile_id)->first();
            $opinions->delete();

            if($mobile){
                $allOpinionsSum  = Rating::where('mobile_id', $mobile->id)->where('status', 'Approved')->get()->sum('rating');
                $allOpinions  = Rating::where('mobile_id', $mobile->id)->where('status', 'Approved')->count();

                if($allOpinions !=0 ){
                    $avgRating = $allOpinionsSum/$allOpinions;
                } else{
                    $avgRating = 0;
                }
                $mobile->update(['avg_rating'=>$avgRating]);
            }

            return redirect()->route('opinions.index')
                             ->with('success_message', 'User Opinions was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    protected function getData(Request $request, $id = 0)
    {
        $data = $request->validate([
            'status' => 'required',
            'rating' => 'required',
            'review_summary' => 'required|string'

        ]);

        return $data;
    }

}
