<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

use function Symfony\Component\String\u;

class TeacherController extends Controller
{
     public function registerTeacher(Request $request)
    {
      
        $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName'=>'required|string|max:100',  
           'personalPhoto'=>'nullable/image/mimes:jpeg,png,jpg,gif,svg/max:2048',
           'gender'=>'required|string|max:10',  
        'subject'=>'required|string|max:100', 
        'class'=>'required|string|max:100', 
        'division'=>'required|string|max:100',    
            'mobile' => 'required|string|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->hasFile('personalPhoto')) {
            $image = $request->file('personalPhoto');
            $personalPhotoName = time() . '_personal.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $personalPhotoName);
            $personalPhotoPath = 'images/' . $personalPhotoName;
        } else {
            return response()->json(['error' => 'The personal photo was not uploaded'], 400);
        }

   

     
        $teacher = Teacher::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,   

            'personalPhoto' => $personalPhotoPath,
            'gender'=>      $request->gender,
            'subject'=>      $request->subject,  
            'class'=>      $request->class   ,  
            'division'=>      $request->division   ,    
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);


         User::create([
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'userType' => 'teacher', 
            'related_id' => $teacher->id, 
        ]);

        return response()->json([
            'message' => 'teacher registered successfully',
            'teacher' => $teacher
        ], 201);
    }

    public function showProfileTeacher($id){
        $teacher = Teacher::find($id);
        if(!$teacher)
            return response()->json("Error.... Teacher Is Not Found");
        return response()->json([
            'firstName'=>$teacher->firstName,
            'lastName'=>$teacher->lastName,
            'personalPhoto' => $teacher->personalPhotoPath,
            'mobile' => $teacher->mobile,
            "userType"=>'Teacher'
        ]);
    }
   
}
