<?php

namespace App\Http\Controllers;

use App\Models\Sample_data;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Illuminate\Support\Facades\File;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = Sample_data::latest()->get();
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                        return $button;
                    })
                    ->editColumn('image', function($data){
                        return '<img src="'.asset('images/'.$data->image).'" style="height: 5rem;">';
                    })//untuk input gambar
                    ->rawColumns(['action', 'image'])
                    ->make(true);
        }
        return view ('sample_data');
    }


    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = array(
            'first_name'    =>  'required',
            'last_name'     =>  'required',
            'select_file'   =>  'required|image|mimes:jpeg,png,jpg'         
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('select_file');
        $newNameImage = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $newNameImage);

        $sampleData = new Sample_data;
        $sampleData->first_name = $request->first_name;
        $sampleData->last_name = $request->last_name;
        $sampleData->image = $newNameImage;
        $sampleData->save();

        return response()->json(['success' => 'Data Added successfully.']);

    }
    

    /**
     * Display the specified resource.
     */
    public function show(Sample_data $sample_data)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Sample_data::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = validator::make($request->all(),[
            'first_name'        =>  'required',
            'last_name'         =>  'required',
        ]);

        if($rules->fails())
        {
            return response()->json(['errors' => $rules->errors()->all()]);
        }
        // else{
            $sampleData = Sample_data::find($id);
    //         if($data){
    //             $sampleData = new Sample_data;
    //             $sampleData->first_name = $request->first_name;
    //             $sampleData->last_name = $request->last_name;
    //             $image = $request->file('select_file');
    //             $newNameImage = rand() . '.' . $image->getClientOriginalExtension();
    //             $image->move(public_path('images'), $newNameImage);
    //             $sampleData->image = $newNameImage;
    //             if($data->hasFile('image'))
    //             {
    //                 $path="public/images/".$data->image;
    //                 if(File::exists($path))
    //                 {
    //                     File::delete($path);
    //                 }
                   
    //             }
    //              $sampleData->save();
    //             return response()->json([
    //                 'success' => 'Data is successfully updated'
    //             ], 200);
    //             }   
    //         else
    //         {
    //             return response()->json([
    //                 'massage' => 'fail'
    //             ], 200);
    //         }   
    //     }
    // }
    
        // $rules = array(
        //     'first_name'        =>  'required',
        //     'last_name'         =>  'required',         
        // );

       

        // $error = Validator::make($request->all(), $rules);

        // if($error->fails())
        // {
        //     return response()->json(['errors' => $error->errors()->all()]);
        // }
        $des =  'images/'.$sampleData->image;
        if(File::exists($des))
           {
            File::delete($des); 
           }            
        $image = $request->file('select_file');
        $newNameImage = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $newNameImage);
        // $sampleData = new Sample_data;
        $sampleData->first_name = $request->first_name;
        $sampleData->last_name = $request->last_name;
        $sampleData->image = $newNameImage;
        $form_data = array(
            'first_name'    =>  $request->first_name,
            'last_name'     =>  $request->last_name,
            'select_file'   => $newNameImage

        );
        $sampleData->update();
        return response()->json([
                            'success' => 'Data is successfully updated'
                        ], 200);
        // Sample_data::whereId($request->hidden_id)->update($form_data);

      


        }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Sample_data::findOrFail($id);
        $data->delete();
    }

    //coba
    function action(Request $request)
    {
     $validation = Validator::make($request->all(), [
      'select_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
     ]);
     if($validation->passes())
     {
      $image = $request->file('select_file');
      $new_name = rand() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('images'), $new_name);
      return response()->json([
       'message'   => 'Image Upload Successfully',
       'uploaded_image' => '<img src="/images/'.$new_name.'" class="img-thumbnail" width="300" />',
       'class_name'  => 'alert-success'
      ]);
     }
     else
     {
      return response()->json([
       'message'   => $validation->errors()->all(),
       'uploaded_image' => '',
       'class_name'  => 'alert-danger'
      ]);
     }
    }
}

