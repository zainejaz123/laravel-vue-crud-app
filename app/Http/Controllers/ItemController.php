<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Item::orderBy('id', 'DESC')->get();
    }

    public function getSingleProfile($id)
    {
        $profile = Item::find($id);

        if($profile) {
            $data['profile'] = $profile;
            $data['status'] = 200;
            return $data;
        }

        $data['message'] = __('users.not_found_error');
        $data['status'] = 400;

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = __('users.added');

        $newItem = new Item;
        $newItem->name = $request["name"];
        $newItem->job_title = $request["job_title"];
        $newItem->start_date = $request["start_date"];
        
        if(!$newItem->end_date){
            $newItem->end_date = $request["end_date"];
        }
        $newItem->description = $request["description"];
        $newItem->working_now = $request["working_now"];
       
        $newItem->save();


        $data['message'] = $message;
        $data['user'] = $newItem;
        $data['status'] = 200;
        return $data;
        
        
   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentItem = Item::find( $id );
        $message = __('users.updated');
        $deleteSuccessMessage = __('users.delete_success_msg');
        $data = [];

        if($currentItem){
            $currentItem->name = $request["name"];
            $currentItem->job_title = $request["job_title"];
            $currentItem->start_date = $request["start_date"];
            
            if(!$currentItem->end_date){
                $currentItem->end_date = $request["end_date"];
            }
            $currentItem->description = $request["description"];
            $currentItem->working_now = $request["working_now"];
            $currentItem->save();

            $data['message'] = $message;
            $data['user'] = $currentItem;
            $data['status'] = 200;

            return $data;
        }

        $data['message'] = $not_found_error;
        $data['status'] = 400;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $not_found_error = __('users.not_found_error');
        $deleteSuccessMessage = __('users.delete_success_msg');
        $data = [];

        $existingItem = Item::find($id);

        if( $existingItem ){
            $existingItem->delete();

            $data['message'] = $deleteSuccessMessage;
            $data['status'] = 200;

            return $data;
        }

        $data['message'] = $not_found_error;
        $data['status'] = 400;

        return $data;
    }

    public function destroyAll(Request $request)
    {

        $data = [];
        $ids = explode(",", $request->ids);
        Item::whereIn('id', $ids)->delete();

        $data['message'] = 'Users has been deleted successfully';
        $data['status'] = 200;

        return $data;

    }
}
