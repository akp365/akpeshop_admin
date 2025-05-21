<?php

namespace App\Http\Controllers;

use App\Models\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use DataTables;
use Redirect;
use Session;

class CategoryController extends Controller
{
    //METHOD TO SHOW/LIST-UP EXISTING CATEGORIES
    public function index(Request $request){
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data = Category::get();

            //PARENT CATS
            $parentCats = Category::where('parent_id', '=', 0)->get();

            return DataTables::of( $data )
                ->addIndexColumn()
                ->addColumn('parent_cat', function($row) use ($parentCats){
                    if( $row->parent_id != 0 )
                    {
                        foreach( $parentCats as $key=>$parentCat )
                        {
                            if( $row->parent_id == $parentCat['id'] ) return $parentCat['title'];
                        }
                    }
                    else
                    {
                        return "NO PARENT";
                    }      
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    if ($row->status == "active") {
                        $editUrl = route('edit-category', ['catId' => $row->id]);
                        $btn = '   
                                        <button class="unpublish_it btn btn-warning btn-stroke btn-circle" title="Deactivate" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>   
                                        <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->title . '\',' . $row->id . ')"><i class="fa fa-trash"></i></button>
                                    ';
                    }
                    //ADD ACTION BUTTONS ALONG WITH 'UNPUBLISH' BUTTON
                    else {
                        $editUrl = route('edit-category', ['catId' => $row->id]);
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
            return view('categories.categoryList');
        }
    }

    //METHOD TO CHANGE CATEGORY STATUS
    public function changeCategoryStatus(Request $request){
        //GET MENU INSTANCE
        $cat = Category::find($request->input('itemId'));

        //UPDATE MENU STATUS IF NEEDED
        $cat->status = ($request->input('status') == 1 ? "active" : "inactive");

        //RETURN 1 IF UPDATED SUCCESSFULLY
        if ($cat->save()) 
        {
            echo json_encode(array('status' => 1));
        }
        //RETURN 0 IF FAILED TO UPDATE
        else 
        {
            echo json_encode(array('status' => 0));
        }
    }


    //METHOD TO ADD NEW CATEGORY
    public function addNewCategory(Request $request){
        //SAVING NEW PAGE
        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate(
                [
                    'title' => 
                    [
                        'required',
                        'max:30',
                        Rule::unique('categories')->where(function($query) use($request) {
                            $query->where('parent_id', '=', $request->input('parent_id') ?? 0 );
                        })
                    ],
                    'order_num' => 'required',
                ],
            );

            
            //INITIATE NEW PAGE OBJECT
            $category = new Category();
            $category->title = $request->input('title');
            $category->order_num = $request->input('order_num');
            $category->parent_id = $request->input('parent_id') ?? 0;
            $category->icon = $request->input('icon') ?? NULL;

            //PAGE ADDESS SUCCESSFULLY
            //REDIRECT TO PAGES 
            if ($category->save()) 
            {
                Session::flash('message', "New category added");
                return Redirect::route('categories');
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
            $parentCategories = Category::where('parent_id', '=', 0)->get();

            return view('categories.addCategory', compact('parentCategories'));
        }
    }



    //METHOD TO EDIT CATEGORY
    public function editCategory(Request $request, $catId){
        //UPDATE PAGE DATA
        if ($request->isMethod('post')) 
        {
            $category = Category::find($catId);
            //dd($category);

            if($category->title != $request->title) $category->title = $request->title;
            if($category->order_num != $request->order_num) $category->order_num = $request->order_num;
            if($category->icon != $request->icon) $category->icon = $request->icon;
            if($category->parent_id != $request->parent_id) $category->parent_id = $request->parent_id;

            //RETURN 1 IF UPDATED SUCCESSFULLY
            if($category->save())
            {
                Session::flash('message', "Category updated successfully");
                return Redirect::route('categories');
            }
            //RETURN 0 IF FAILED TO UPDATE
            else
            {
                return Redirec::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }

        }
        else
        {
            $category = Category::find($catId);
            //dd($category);

            //-- COLLECT PARENT CATEGORY LIST
            $parentCategories = Category::where('parent_id', '=', 0)->get();

            //-- LOAD EDIT PAGE
            return view('categories.editCategory', compact('parentCategories', 'category'));
        }
    }



    //METHOD TO DELETE A CATEGORY
    public function deleteCategory(Request $request){
        //GET MENU INSTANCE
        $cat = Category::find($request->input('itemId'));

        //RETURN 0 IF FAILED TO DELETE
        if (!$cat->forceDelete()) {
            echo json_encode(array('status' => 0));
        }
        //RETURN 1 IF DELETED SUCCESSFULLY
        else {
            echo json_encode(array('status' => 1));
        }
    }
}
