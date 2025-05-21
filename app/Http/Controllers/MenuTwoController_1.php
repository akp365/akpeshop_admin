<?php

namespace App\Http\Controllers;

use App\Models\Models\MenuTwo;
use App\Models\Models\Page;
use Illuminate\Http\Request;
use Session;
use Redirect;

use DataTables;

class MenuTwoController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data = MenuTwo::get();

            return DataTables::of( $data )
                ->addIndexColumn()
                ->addColumn('page_name', function ( $row ) {
                    if( $row->page()->exists() )
                    {
                        return $row->page->title;
                    }
                    else
                    {
                        return 'NA';
                    }
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    if ($row->status == "active") {
                        $editUrl = route('edit-menu-2', ['menuId' => $row->id]);
                        $btn = '   
                                        <button class="unpublish_it btn btn-warning btn-stroke btn-circle" title="Deactivate" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>   
                                        <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->title . '\',' . $row->id . ')"><i class="fa fa-trash"></i></button>
                                    ';
                    }
                    //ADD ACTION BUTTONS ALONG WITH 'UNPUBLISH' BUTTON
                    else {
                        $editUrl = route('edit-menu-2', ['menuId' => $row->id]);
                        $btn =  '
                                        <button class="publish_it btn btn-success btn-stroke btn-circle" title="Activate" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button>                                      
                                        <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->title . '\',' . $row->id . ')"><i class="fa fa-trash"></i></button>
                                ';
                    }

                    return $btn;
                })
                ->rawColumns([ 'action' ])
                ->make(true);
        } else {
            return view('menus_2.menuList');
        }
    }

    function addNewMenu(Request $request)
    {
        //SAVING NEW PAGE
        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate([
                'title' => 'required|unique:menu_one|max:30',
                'order_num' => 'required',
                'url' => 'required_without:page_id',
                'page_id' => 'required_without:url',
            ]);

            
            //INITIATE NEW PAGE OBJECT
            $page = new MenuTwo();
            $page->title = $request->input('title');
            $page->order_num = $request->input('order_num');
            $page->url = $request->input('url') ?? NULL;
            $page->page_id = $request->input('page_id') ?? NULL;

            //PAGE ADDESS SUCCESSFULLY
            //REDIRECT TO PAGES 
            if ($page->save()) 
            {
                Session::flash('message', "New item added to menu-one");
                return Redirect::route('menus-2');
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
            //COLLECT MENUS
            $pages = Page::get();

            return view('menus_2.addMenu', compact('pages'));
        }
    }

    public function changeMenuStatus(Request $request)
    {
        //GET MENU INSTANCE
        $menu = MenuTwo::find($request->input('itemId'));

        //UPDATE MENU STATUS IF NEEDED
        $menu->status = ($request->input('status') == 1 ? "active" : "inactive");

        //RETURN 1 IF UPDATED SUCCESSFULLY
        if ($menu->save()) 
        {
            echo json_encode(array('status' => 1));
        }
        //RETURN 0 IF FAILED TO UPDATE
        else 
        {
            echo json_encode(array('status' => 0));
        }
    }

    public function deleteMenu(Request $request)
    {
        //GET MENU INSTANCE
        $menu = MenuTwo::find($request->input('itemId'));

        //RETURN 0 IF FAILED TO DELETE
        if (!$menu->forceDelete()) {
            echo json_encode(array('status' => 0));
        }
        //RETURN 1 IF DELETED SUCCESSFULLY
        else {
            echo json_encode(array('status' => 1));
        }
    }

    public function editMenu(Request $request, $menuId)
    {
        //UPDATE MENU DATA
        if ($request->isMethod('post')) 
        {
            //GET MENU INSTANCE
            $menu = MenuTwo::find($menuId);

            //UPDATE TITLE IF REQUIRED
            if($menu->title != $request->input('title')) $menu->title = $request->input('title');

            //UPDATE URL  IF REQUIRED
            if($menu->url != $request->input('url')) $menu->url = $request->input('url');

            //UPDATE MENU-ID  IF REQUIRED
            if($menu->page_id != $request->input('page_id')) $menu->page_id = $request->input('page_id');

            //UPDATE ORDER-NUMBER  IF REQUIRED
            if($menu->order_num != $request->input('order_num')) $menu->order_num = $request->input('order_num');

            //RETURN 1 IF UPDATED SUCCESSFULLY
            if($menu->save())
            {
                Session::flash('message', "Menu updated successfully");
                return Redirect::route('menus-2');
            }
            //RETURN 0 IF FAILED TO UPDATE
            else
            {
                return Redirect::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }
        }
        //SHOW MENU-UPDATE VIEW
        else 
        {
            //GET MENU INSTANCE    
            $menu = MenuTwo::find($menuId);

            //COLLECT MENUS
            $pages = Page::get();

            //PREPARE VIEW DATA
            $this->viewData['itemDetails'] = $menu->toArray();
            $this->viewData['pages'] = $pages;

            //RENDER MENU EDIT VIEW
            return view('menus_2.editMenu', $this->viewData);
        }
    }
}
