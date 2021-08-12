<?php

namespace App\Http\Controllers;

use App\Models\TodoNote;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Exception;

class TodoNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user_id = null)
    {
        if (!$user_id) $user_id = $request->user()->id;

        try {
            $user = User::find($user_id);
            if (!$user) throw new Exception('User id provided does not exist in our system', 404);

            return $this->apiResponse([
                'action' => 'index',
                'notes' => $user->todo_notes
            ]);
        }
        catch(Exception $e) {
            return $this->apiError($e);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['content' => 'required|string|max:255']);

        try {
            $note = TodoNote::create([
                'user_id' => $request->user()->id,
                'content' => $request->content
            ]);
            return $this->apiResponse([
                'action' => 'create',
                'note' => $note->fresh()
            ]);
        }
        catch(Exception $e) {
            return $this->apiError($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['complete' => 'required|boolean']);

        try {
            $note = TodoNote::findOrFail($id);
            if ($note->user->id != $request->user()->id) abort(403, 'User does not have permission to update this todo note');

            $update_value = $request->complete ? Carbon::now()->toDateTimeString() : null;
            $note->update(['completion_time' => $update_value]);

            return $this->apiResponse([
                'action' => 'update',
                'note' => $note->fresh()
            ]);
        }
        catch(Exception $e) {
            return $this->apiError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $note = TodoNote::findOrFail($id);
            if ($note->user->id != $request->user()->id) abort(403, 'User does not have permission to delete this todo note');

            $note->delete();

            return $this->apiResponse([
                'action' => 'delete'
            ]);
        }
        catch(Exception $e) {
            return $this->apiError($e);
        }
    }
}
