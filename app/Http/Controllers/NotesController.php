<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNoteRequest;
use App\Note;
use App\PurchaseOrder;
use App\PurchaseRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
    }

    /**
     * Takes a subject (camel_case class name) and an ID and attempt to
     * retrieve the model class. We also authenticate that the the client
     * is allowed to view the model.
     *
     * @param $subject
     * @param $subjectId
     * @return mixed
     */
    protected function fetchModel($subject, $subjectId)
    {
        $subject = str_snake_to_camel($subject);
        $className = "\App\\" . $subject;
        $model = $className::find($subjectId);
        $this->authorize('view', $model);
        return $model;
    }


    /**
     * Retrieves all the notes for a specific subject type and
     * it's id.
     * 
     * @param $subject
     * @param $subjectId
     * @return mixed
     */
    public function getNotes($subject, $subjectId)
    {
        $model = $this->fetchModel($subject, $subjectId);
        return $model->notes()->latest()->get();
    }


    /**
     * Post to save a Note to a subject
     *
     * @param $subject
     * @param $subjectId
     * @param AddNoteRequest $request
     * @return mixed
     */
    public function postAddNote($subject, $subjectId, AddNoteRequest $request)
    {
        $model = $this->fetchModel($subject, $subjectId);
        return $model->addNote($request->input('content'), Auth::user());
    }

    /**
     * Delete a Note at given route. We also check to see if user is allowed
     * to view the subject Model first.
     *
     * @param Note $note
     * @param $subject
     * @param $subjectId
     * @return mixed
     * @throws \Exception
     */
    public function deleteNote($subject, $subjectId, Note $note)
    {
        $this->fetchModel($subject, $subjectId);
        $this->authorize('delete', $note);
        if($note->delete())return response("Deleted a note");
        return response("Could not delete note", 500);
    }
}
