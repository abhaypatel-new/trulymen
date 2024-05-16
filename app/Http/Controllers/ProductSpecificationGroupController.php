<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Group;
use App\Models\Pipeline;
use Illuminate\Http\Request;

class ProductSpecificationGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            [
                'auth',
                'XSS',
            ]
        );
    }

    /**
     * Display a listing of the relabel.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('manage label'))
        {
            $groups   = Group::all();


            return view('groups.index')->with('groups', $groups);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new relabel.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('create label'))
        {
         

            return view('groups.create');
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created relabel in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        if(\Auth::user()->can('create label'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20'
                                   
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('groups.index')->with('error', $messages->first());
            }

            $group              = new Group();
            $group->name        = $request->name;
            $group->created_by  = \Auth::user()->ownerId();
            $group->save();

            return redirect()->route('groups.index')->with('success', __('Group successfully created!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified relabel.
     *
     * @param \App\Label $label
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return redirect()->route('groups.index');
    }

    /**
     * Show the form for editing the specified relabel.
     *
     * @param \App\Label $label
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        if(\Auth::user()->can('edit label'))
        {
            if($group->created_by == \Auth::user()->ownerId())
            {
                
                return view('groups.edit', compact('group'));
            }
            else
            {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified relabel in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Label $label
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        if(\Auth::user()->can('edit label'))
        {

            if($group->created_by == \Auth::user()->ownerId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                      'name' => 'required|max:20',
                                     
                                  ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('users')->with('error', $messages->first());
                }

                $group->name        = $request->name;
                $group->save();

                return redirect()->route('groups.index')->with('success', __('Group successfully updated!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified relabel from storage.
     *
     * @param \App\Label $label
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        if(\Auth::user()->can('delete label'))
        {
            if($group->created_by == \Auth::user()->ownerId())
            {
                $group->delete();

                return redirect()->route('groups.index')->with('success', __('Group successfully deleted!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
 }
}
