<?php

namespace App\Http\Controllers;

use App\Models\Models\Page;

use Session;
use Redirect;
use DB;
use DataTables;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) {
            //PREPARE DATA FOR DATATABLE
            $data = Page::select(
                'page_id',
                'title',
                DB::raw('SUBSTR(description,1,100) AS description'),
                'status'
            )->orderBy('page_id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    if ($row->status == "active") {
                        $editUrl = route('edit-page', ['pageId' => $row->page_id]);
                        $btn = '   
                                        <button class="unpublish_it btn btn-warning btn-stroke btn-circle" title="Deactivate" onclick="changeStatus(' . $row->page_id . ', 0)"><i class="fa fa-close"></i></button>   
                                        <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->title . '\',' . $row->page_id . ')"><i class="fa fa-trash"></i></button>
                                    ';
                    }
                    //ADD ACTION BUTTONS ALONG WITH 'UNPUBLISH' BUTTON
                    else {
                        $editUrl = route('edit-page', ['pageId' => $row->page_id]);
                        $btn =  '
                                        <button class="publish_it btn btn-success btn-stroke btn-circle" title="Activate" onclick="changeStatus(' . $row->page_id . ', 1)"><i class="fa fa-check"></i></button>                                      
                                        <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->title . '\',' . $row->page_id . ')"><i class="fa fa-trash"></i></button>
                                ';
                    }

                    return $btn;
                })
                ->rawColumns(['action', 'banner'])
                ->make(true);
        } else {
            return view('pages.pageList');
        }
    }

    function addNewPage(Request $request)
    {
        //SAVING NEW PAGE
        if ($request->isMethod('post')) 
        {
            //INITIATE NEW PAGE OBJECT
            $page = new Page();
            $page->title = $request->input('pageTitle');
            $page->description = $request->input('pageContent');

            //PAGE ADDESS SUCCESSFULLY
            //REDIRECT TO PAGES 
            if ($page->save()) 
            {
                Session::flash('message', "New Page Added Successfully");
                return Redirect::route('pages');
            } 
            //PAGE ADD FAILED
            //REDIRECT BACK TO PAGE ADD WINDOW WITH ERROR
            else 
            {
                return Redirect::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }
        }
        //SHOW INPUT FOR CREATING NEW PAGE
        else 
        {
            return view('pages.addPage');
        }
    }

    public function changePageStatus(Request $request)
    {
        //GET PAGE INSTANCE
        $page = Page::find($request->input('itemId'));

        //UPDATE PAGE STATUS IF NEEDED
        $page->status = ($request->input('status') == 1 ? "active" : "inactive");

        //RETURN 1 IF UPDATED SUCCESSFULLY
        if ($page->save()) 
        {
            echo json_encode(array('status' => 1));
        }
        //RETURN 0 IF FAILED TO UPDATE
        else 
        {
            echo json_encode(array('status' => 0));
        }
    }

    public function deletePage(Request $request)
    {
        //GET PAGE INSTANCE
        $page = Page::find($request->input('itemId'));

        //RETURN 0 IF FAILED TO DELETE
        if (!$page->forceDelete()) {
            echo json_encode(array('status' => 0));
        }
        //RETURN 1 IF DELETED SUCCESSFULLY
        else {
            echo json_encode(array('status' => 1));
        }
    }

    public function editPage(Request $request, $pageId)
    {
        //UPDATE PAGE DATA
        if ($request->isMethod('post')) 
        {
            //GET PAGE INSTANCE
            $page = Page::find($pageId);

            //UPDATE TITLE IF REQUIRED
            if($page->title != $request->input('pageTitle')) $page->title = $request->input('pageTitle');

            //UPDATE DESCRIPTION IF REQUIRED
            if($page->description != $request->input('pageContent')) $page->description = $request->input('pageContent');

            //RETURN 1 IF UPDATED SUCCESSFULLY
            if($page->save())
            {
                Session::flash('message', "Page Updated Successfully");
                return Redirect::route('pages');
            }
            //RETURN 0 IF FAILED TO UPDATE
            else
            {
                return Redirect::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }
        }
        //SHOW PAGE-UPDATE VIEW
        else 
        {
            //GET PAGE INSTANCE    
            $page = Page::find($pageId);

            //PREPARE VIEW DATA
            $this->viewData['itemDetails'] = $page->toArray();

            //RENDER PAGE EDIT VIEW
            return view('pages.editPage', $this->viewData);
        }
    }
}
