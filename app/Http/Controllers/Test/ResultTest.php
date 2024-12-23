<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use App\Models\QuestionTrait;
use App\Models\Result;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Traits\ArchetypeFinder;

class ResultTest extends Controller
{
    public function checkAllTestsCompleted()
    {
        try {
            $resultUser = Session::get('result_user');
            
            if (!$resultUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'No results found in session'
                ], 400);
            }

            DB::beginTransaction();
            
            try {
                $result = new Result();
                $result->user_id = auth()->id();
                $result->scores = $resultUser['scores'] ?? [];
                $result->highestTwoScores = $resultUser['archtype']['topTraits'] ?? [];
                $result->jobs = $resultUser['jobs'] ?? [];
                $result->degree = json_encode($resultUser['degreeMatching'] ?? []);
                $result->Archetype = $resultUser['archetype']['archetypes'][0] ?? null;
                $result->save();
                
                Session::forget('result_user');
                Session::forget('holland_codes_progress');
                Session::forget('basic_interest_progress');
                Session::forget('degree_progress');
                Session::forget('current_test_stage');
                Session::forget('result_user');

                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Results saved successfully',
                    'redirect' => route('results')
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('Error in checkAllTestsCompleted:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_data' => $resultUser ?? null
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving your results: ' . $e->getMessage()
            ], 500);
        }
    }
}